<?php

declare(strict_types=1);

use App\Repositories\EvenementRepository;
use App\Repositories\MatchRepository;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

// Récupération de l'identifiant dans l'URL.
$id = (int) ($_GET['id'] ?? 0);

// Recherche de l'événement.
$evenementRepository = new EvenementRepository($pdo);
$evenement = $evenementRepository->findById($id);

// L'événement doit exister.
if ($evenement === null) {
    require __DIR__ . '/404.php';
    exit;
}

// Récupération du programme.
$matchRepository = new MatchRepository($pdo);
$matchs = $matchRepository->findByEvenementId($id);

$pageTitle = "Gérer l'évènement";
$topbarTitle = 'Évènements';
$adminSection = 'evenements';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/evenement.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';