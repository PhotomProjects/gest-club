<?php

declare(strict_types=1);

use App\Repositories\EvenementRepository;

require dirname(__DIR__) . '/config/bootstrap.php';

$evenementRepository = new EvenementRepository($pdo);

// Sélection des deux prochains événements non annulés.
$evenementsAccueil = [];

foreach ($evenementRepository->findUpcoming() as $evenement) {
    if ($evenement['statut_evenement'] === 'ANNULE') {
        continue;
    }

    $evenementsAccueil[] = $evenement;

    if (count($evenementsAccueil) === 2) {
        break;
    }
}

$pageTitle = 'Accueil';
$currentSection = 'home';

$view = dirname(__DIR__) . '/app/Views/pages/public/accueil.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';