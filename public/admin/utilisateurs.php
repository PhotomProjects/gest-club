<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

$pageTitle = 'Utilisateurs';
$adminSection = 'utilisateurs';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/utilisateurs.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';