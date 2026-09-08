<?php

declare(strict_types=1);

use App\Repositories\EvenementRepository;
use App\Services\EvenementService;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

// Récupération de l'identifiant.
$id = (int) ($_GET['id'] ?? 0);

$evenementRepository = new EvenementRepository($pdo);
$evenement = $evenementRepository->findById($id);

if ($evenement === null) {
    require __DIR__ . '/404.php';
    exit;
}

$dateDebut = new DateTimeImmutable($evenement['date_heure_debut_evenement']);

$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf->verify($_POST['csrf_token'] ?? null);

    try {
        $evenementService = new EvenementService($pdo);
        $evenementService->cancel($id);

        header(
            'Location: /admin/evenement.php?id=' . $id
        );
        exit;
    } catch (\DomainException $exception) {
        $erreur = $exception->getMessage();
    }
}

$pageTitle = "Annuler l'évènement";
$topbarTitle = 'Évènements';
$adminSection = 'evenements';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/evenement-annulation.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';