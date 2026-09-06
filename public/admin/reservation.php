<?php

declare(strict_types=1);

use App\Repositories\ReservationRepository;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

// Récupération de la réservation.
$idReservation = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT,
    [
        'options' => [
            'min_range' => 1,
        ],
    ]
);

if ($idReservation === false || $idReservation === null) {
    http_response_code(404);
    exit('Réservation introuvable.');
}

$reservationRepository = new ReservationRepository($pdo);

$reservation = $reservationRepository->findByIdForAdmin($idReservation);

if ($reservation === null) {
    http_response_code(404);
    exit('Réservation introuvable.');
}

$places = $reservationRepository->findPlacesByIdForAdmin($idReservation);

if ($places === []) {
    http_response_code(500);
    exit('Les places de la réservation sont introuvables.');
}

$pageTitle = 'Détail de la réservation';
$topbarTitle = 'Réservations';
$adminSection = 'reservations';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/reservation.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';