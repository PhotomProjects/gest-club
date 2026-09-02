<?php

declare(strict_types=1);

use App\Services\ReservationService;
use App\Repositories\ReservationRepository;

require dirname(__DIR__) . '/config/bootstrap.php';

$auth->requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification du token CSRF.
    $csrf->verify($_POST['csrf_token'] ?? null);

    // Validation des valeurs transmises par le récapitulatif.
    $idEvenement = filter_var(recupererChampPost('id_evenement'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1,],]);
    $nbPlaces = filter_var(recupererChampPost('nb_places'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 2,],]);
    $tribune = recupererChampPost('tribune');
    $niveau = recupererChampPost('niveau');
    $tribunesAutorisees = ['NORD', 'SUD', 'EST', 'OUEST',];
    $niveauxAutorises = ['BAS', 'MILIEU', 'HAUT',];

    if (
        $idEvenement === false || $nbPlaces === false || !in_array($tribune, $tribunesAutorisees, true) || !in_array($niveau, $niveauxAutorises, true)
    ) {
        http_response_code(400);
        exit('Données de réservation invalides.');
    }

    $reservationService = new ReservationService($pdo);

    try {
        $reservationId = $reservationService->confirm((int) $utilisateurConnecte['id'], $idEvenement, $nbPlaces, $tribune, $niveau);
    } catch (\DomainException $exception) {
        // Conservation temporaire de l'erreur et de la sélection. Ces données seront supprimées après leur lecture.
        $_SESSION['erreur_confirmation_reservation'] = [
            'id_evenement' => $idEvenement,
            'message' => $exception->getMessage(),
            'selection' => [
                'nb_places' => $nbPlaces,
                'tribune' => $tribune,
                'niveau' => $niveau,
            ],
        ];

        header(
            'Location: /reservation.php?id=' . $idEvenement
        );
        exit;
    }

    // Redirection après l'écriture en base. Un rafraîchissement répète ainsi une requête GET et non le POST.
    header(
        'Location: /reservation-confirmation.php?id='
        . $reservationId
    );
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit('Méthode non autorisée.');
}

$idReservation = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1,],]);

if ($idReservation === false || $idReservation === null) {
    header('Location: /mes-reservations.php');
    exit;
}

// Recherche de la réservation en vérifiant son propriétaire.
$reservationRepository = new ReservationRepository($pdo);

$reservation = $reservationRepository->findByIdForUser(
    $idReservation,
    (int) $utilisateurConnecte['id']
);

if ($reservation === null) {
    http_response_code(404);
    exit('Réservation introuvable.');
}

$pageTitle = 'Réservation confirmée';
$currentSection = 'events';

$view = dirname(__DIR__) . '/app/Views/pages/public/reservation-confirmation.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';