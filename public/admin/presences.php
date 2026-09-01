<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

$pageTitle = 'Contrôle des billets';
$adminSection = 'presences';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/presences.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';