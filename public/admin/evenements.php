<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

use App\Repositories\EvenementRepository;

// Authentification et autorisation.
$auth->requireRole('ADMIN');

$evenementRepository = new EvenementRepository($pdo);
$evenements = $evenementRepository->findAll();

$pageTitle = 'Évènements';
$adminSection = 'evenements';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/evenements.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';