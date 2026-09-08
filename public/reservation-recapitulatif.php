<?php

declare(strict_types=1);

use App\Repositories\EvenementRepository;
use App\Repositories\PlaceRepository;
use App\Repositories\ReservationRepository;

require dirname(__DIR__) . '/config/bootstrap.php';

$auth->requireLogin();

// Le récapitulatif doit uniquement recevoir le formulaire de réservation.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /evenements.php');
    exit;
}

// Vérification du token CSRF.
$csrf->verify($_POST['csrf_token'] ?? null);

// Récupération et validation des données.
$idEvenement = filter_var(recupererChampPost('id_evenement'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1,],]);
$nbPlaces = filter_var(recupererChampPost('nb_places'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 2,],]);
$tribune = recupererChampPost('tribune');
$niveau = recupererChampPost('niveau');
$tribunesAutorisees = ['NORD', 'SUD', 'EST', 'OUEST'];
$niveauxAutorises = ['BAS', 'MILIEU', 'HAUT'];

if (
    $idEvenement === false || $nbPlaces === false || !in_array($tribune, $tribunesAutorisees, true) || !in_array($niveau, $niveauxAutorises, true)
) {
    http_response_code(400);
    exit('Données de réservation invalides.');
}

// Recherche de l'événement.
$evenementRepository = new EvenementRepository($pdo);
$evenement = $evenementRepository->findById($idEvenement);

if ($evenement === null) {
    require __DIR__ . '/404.php';
    exit;
}

// Vérification que l'événement peut encore être réservé.
$dateDebut = new DateTimeImmutable($evenement['date_heure_debut_evenement']);
$estReservable = $evenement['statut_evenement'] === 'OUVERT' && $dateDebut > new DateTimeImmutable() && (int) $evenement['places_disponibles'] > 0;

if (!$estReservable) {
    header('Location: /evenement.php?id=' . $idEvenement);
    exit;
}

// Récupération des disponibilités.
$placeRepository = new PlaceRepository($pdo);
$disponibilites = $placeRepository->findAvailabilityByEventId($idEvenement);

// Préparation des disponibilités et des prix.
$disponibilitesParZone = [];
$prixParNiveau = [];
$placesDisponiblesEvenement = 0;
$placesDisponiblesTribune = 0;
$placesDisponiblesNiveau = 0;
$placesDisponiblesZone = 0;

$prixUnitaire = null;

foreach ($disponibilites as $disponibilite) {
    $tribuneCourante = $disponibilite['tribune_place'];
    $niveauCourant = $disponibilite['niveau_place'];
    $nombreDisponible = (int) $disponibilite['places_disponibles'];
    $prix = (float) $disponibilite['prix_place'];

    $disponibilitesParZone[$tribuneCourante][$niveauCourant] = $nombreDisponible;
    $prixParNiveau[$niveauCourant] = $prix;
    $placesDisponiblesEvenement += $nombreDisponible;
    if ($tribuneCourante === $tribune) {
        $placesDisponiblesTribune += $nombreDisponible;
    }
    if ($niveauCourant === $niveau) {
        $placesDisponiblesNiveau += $nombreDisponible;
    }
    if (
        $tribuneCourante === $tribune && $niveauCourant === $niveau
    ) {
        $placesDisponiblesZone = $nombreDisponible;
        $prixUnitaire = $prix;
    }
}

// Contrôle du quota de deux places par utilisateur et événement.
$reservationRepository = new ReservationRepository($pdo);
$nombrePlacesDejaReservees = $reservationRepository->countConfirmedPlacesByUserAndEvent((int) $utilisateurConnecte['id'], $idEvenement);

// Vérification des règles métier.
$erreurReservation = null;

if ($nombrePlacesDejaReservees + $nbPlaces > 2) {
    $erreurReservation = 'Vous pouvez réserver au maximum deux places pour cet évènement.';
} elseif ($prixUnitaire === null) {
    $erreurReservation = "Cette combinaison de tribune et de niveau n'est pas proposée.";
} elseif ($placesDisponiblesEvenement < $nbPlaces) {
    $erreurReservation = "Il ne reste pas assez de places disponibles pour cet évènement.";
} elseif ($placesDisponiblesNiveau < $nbPlaces) {
    $erreurReservation = "Il ne reste pas assez de places disponibles dans ce niveau.";
} elseif ($placesDisponiblesTribune < $nbPlaces) {
    $erreurReservation = "Il ne reste pas assez de places disponibles dans cette tribune.";
} elseif ($placesDisponiblesZone < $nbPlaces) {
    $erreurReservation = "Il ne reste pas assez de places dans la tribune et le niveau sélectionnés.";
}

// Conservation de la sélection pour les vues.
$selectionReservation = ['nb_places' => $nbPlaces, 'tribune' => $tribune, 'niveau' => $niveau,];

// Retour au formulaire lorsque la sélection est impossible.
if ($erreurReservation !== null) {
    $pageTitle = 'Réservation';
    $currentSection = 'events';

    $view = dirname(__DIR__) . '/app/Views/pages/public/reservation.php';

    require dirname(__DIR__) . '/app/Views/layouts/public.php';

    exit;
}

// Calcul du prix exclusivement côté serveur.
$prixTotal = $prixUnitaire * $nbPlaces;

$pageTitle = 'Résumé de la réservation';
$currentSection = 'events';

$view = dirname(__DIR__) . '/app/Views/pages/public/reservation-recapitulatif.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';