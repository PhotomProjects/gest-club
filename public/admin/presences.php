<?php

declare(strict_types=1);

use App\Repositories\PresenceRepository;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit('Méthode non autorisée.');
}

$presenceRepository = new PresenceRepository($pdo);

$presences = $presenceRepository->findLatest();
$statistiquesPresence = $presenceRepository->getStatistics();

$pageTitle = 'Contrôle des billets';
$topbarTitle = 'Contrôle des billets';
$adminSection = 'presences';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/presences.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';