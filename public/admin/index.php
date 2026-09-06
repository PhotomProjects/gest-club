<?php

declare(strict_types=1);

use App\Repositories\EvenementRepository;
use App\Repositories\ReservationRepository;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

// Prochains évènements.
$evenementRepository = new EvenementRepository($pdo);

$maintenant = new DateTimeImmutable();

$prochainsEvenements = array_filter(
    $evenementRepository->findUpcoming(),
    function (array $evenement) use ($maintenant): bool {
        $dateDebut = new DateTimeImmutable($evenement['date_heure_debut_evenement']);

        return $evenement['statut_evenement'] !== 'ANNULE' && $dateDebut > $maintenant;
    }
);

$prochainsEvenements = array_slice(array_values($prochainsEvenements), 0, 3);

// Réservations récentes.
$reservationRepository = new ReservationRepository($pdo);

$reservationsRecentes = array_slice($reservationRepository->findAllForAdmin(), 0, 5);

$pageTitle = "Vue d'ensemble";
$adminSection = 'dashboard';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/accueil.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';