<?php

declare(strict_types=1);

use App\Repositories\UtilisateurRepository;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

$utilisateurRepository = new UtilisateurRepository($pdo);
$utilisateurs = $utilisateurRepository->findAll();

$pageTitle = 'Utilisateurs';
$topbarTitle = 'Utilisateurs';
$adminSection = 'utilisateurs';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/utilisateurs.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';