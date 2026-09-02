<?php

declare(strict_types=1);

use App\Repositories\ReservationRepository;
use App\Services\ReservationService;

require dirname(__DIR__) . '/config/bootstrap.php';

$auth->requireLogin();

// Traitement de l'annulation.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf->verify($_POST['csrf_token'] ?? null);

    $action = recupererChampPost('action');

    $idReservation = filter_var(
        recupererChampPost('id_reservation'),
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1,],]
    );

    if (
        $action !== 'annuler' || $idReservation === false
    ) {
        http_response_code(400);
        exit("Données d'annulation invalides.");
    }

    $reservationService = new ReservationService($pdo);

    try {
        $reservationService->cancel(
            (int) $utilisateurConnecte['id'],
            $idReservation
        );

        $_SESSION['message_reservation'] = [
            'type' => 'success',
            'texte' => 'La réservation a bien été annulée.',
        ];

        $filtreRedirection = 'annulees';
    } catch (\DomainException $exception) {
        $_SESSION['message_reservation'] = [
            'type' => 'danger',
            'texte' => $exception->getMessage(),
        ];

        $filtreRedirection = 'a-venir';
    }

    // Redirection après le POST : un rafraîchissement ne répète pas l'annulation.
    header(
        'Location: /mes-reservations.php?filtre='
        . $filtreRedirection
    );
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit('Méthode non autorisée.');
}

// Récupération et validation du filtre.
$filtreRecu = $_GET['filtre'] ?? 'a-venir';
$filtresAutorises = ['a-venir', 'passees', 'annulees',];
$filtre = is_string($filtreRecu) && in_array($filtreRecu, $filtresAutorises, true) ? $filtreRecu : 'a-venir';

// Récupération du message temporaire.
$messageReservation = $_SESSION['message_reservation'] ?? null;

unset($_SESSION['message_reservation']);

if (
    !is_array($messageReservation)
    || !in_array(
        $messageReservation['type'] ?? null,
        ['success', 'danger'],
        true
    )
    || !is_string($messageReservation['texte'] ?? null)
) {
    $messageReservation = null;
}

// Récupération des réservations de l'utilisateur connecté.
$reservationRepository = new ReservationRepository($pdo);

$reservations = $reservationRepository->findByUserAndFilter(
    (int) $utilisateurConnecte['id'],
    $filtre
);

$pageTitle = 'Mes réservations';
$currentSection = null;

$view = dirname(__DIR__) . '/app/Views/pages/public/mes-reservations.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';