<?php

declare(strict_types=1);

use App\Repositories\EvenementRepository;
use App\Repositories\MatchRepository;

require dirname(__DIR__) . '/config/bootstrap.php';

// Récupération de l'identifiant dans l'URL.
$id = (int) ($_GET['id'] ?? 0);

// Recherche de l'événement.
$evenementRepository = new EvenementRepository($pdo);
$evenement = $evenementRepository->findById($id);

// Si l'événement n'existe pas, on retourne une erreur 404.
if ($evenement === null) {
    http_response_code(404);
    exit('Événement introuvable.');
}

// Récupération des matchs de l'événement.
$matchRepository = new MatchRepository($pdo);
$matchs = $matchRepository->findByEvenementId($id);

$pageTitle = $evenement['nom_evenement'];
$currentSection = 'events';

$view = dirname(__DIR__) . '/app/Views/pages/public/evenement.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';