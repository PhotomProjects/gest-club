<?php

declare(strict_types=1);

use App\Repositories\IntervenantRepository;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

// Récupération des intervenants.
$intervenantRepository = new IntervenantRepository($pdo);
$intervenants = $intervenantRepository->findAll();

$pageTitle = 'Intervenants';
$topbarTitle = 'Intervenants';
$adminSection = 'intervenants';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/intervenants.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';