<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

// Protection CSRF.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf->verify($_POST['csrf_token'] ?? null);
}

$pageTitle = 'Interface de contrôle';
$topbarTitle = 'Contrôle des billets';
$adminSection = 'presences';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/presences-scan.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';