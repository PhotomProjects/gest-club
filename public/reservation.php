<?php

declare(strict_types=1);

use App\Repositories\EvenementRepository;
use App\Repositories\PlaceRepository;

require dirname(__DIR__) . '/config/bootstrap.php';

// Récupération de l'identifiant de l'événement.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1) {
    require __DIR__ . '/404.php';
    exit;
}

// Conservation de l'événement demandé avant la connexion.
if (!$auth->isAuthenticated()) {
    $_SESSION['id_evenement_apres_connexion'] = $id;

    header('Location: /connexion.php');
    exit;
}

// Récupération de l'événement.
$evenementRepository = new EvenementRepository($pdo);
$evenement = $evenementRepository->findById($id);

if ($evenement === null) {
    require __DIR__ . '/404.php';
    exit;
}

// Vérification que l'événement peut encore être réservé.
$dateDebut = new DateTimeImmutable($evenement['date_heure_debut_evenement']);
$estReservable = $evenement['statut_evenement'] === 'OUVERT' && $dateDebut > new DateTimeImmutable() && (int) $evenement['places_disponibles'] > 0;

if (!$estReservable) {
    header('Location: /evenement.php?id=' . $id);
    exit;
}

// Récupération des disponibilités par tribune et niveau.
$placeRepository = new PlaceRepository($pdo);
$disponibilites = $placeRepository->findAvailabilityByEventId($id);

// Préparation des données pour la vue.
$disponibilitesParZone = [];
$prixParNiveau = [];

foreach ($disponibilites as $disponibilite) {
    $tribune = $disponibilite['tribune_place'];
    $niveau = $disponibilite['niveau_place'];
    $disponibilitesParZone[$tribune][$niveau] = (int) $disponibilite['places_disponibles'];
    $prixParNiveau[$niveau] = (float) $disponibilite['prix_place'];
}

// Récupération d'une éventuelle erreur survenue à la confirmation.
$erreurConfirmation = $_SESSION['erreur_confirmation_reservation'] ?? null;
unset($_SESSION['erreur_confirmation_reservation']);

if (
    is_array($erreurConfirmation)
    && (int) ($erreurConfirmation['id_evenement'] ?? 0) === $id
    && is_string($erreurConfirmation['message'] ?? null)
    && is_array($erreurConfirmation['selection'] ?? null)
) {
    $erreurReservation = $erreurConfirmation['message'];
    $selectionReservation = $erreurConfirmation['selection'];
}

$pageTitle = 'Réservation';
$currentSection = 'events';

$view = dirname(__DIR__) . '/app/Views/pages/public/reservation.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';