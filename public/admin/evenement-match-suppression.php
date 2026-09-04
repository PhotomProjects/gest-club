<?php

declare(strict_types=1);

use App\Repositories\MatchRepository;
use App\Services\MatchService;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

// Récupération du match.
$idMatch = (int) ($_GET['id'] ?? 0);

$matchRepository = new MatchRepository($pdo);
$match = $matchRepository->findById($idMatch);

if ($match === null) {
    http_response_code(404);
    exit('Match introuvable.');
}

$idEvenement = (int) $match['id_evenement'];

$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf->verify($_POST['csrf_token'] ?? null);

    try {
        $matchService = new MatchService($pdo);
        $matchService->delete($idMatch);

        header(
            'Location: /admin/evenement.php?id=' . $idEvenement
        );
        exit;
    } catch (\DomainException $exception) {
        $erreur = $exception->getMessage();
    }
}

$pageTitle = 'Supprimer un match';
$topbarTitle = 'Évènements';
$adminSection = 'evenements';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/evenement-match-suppression.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';