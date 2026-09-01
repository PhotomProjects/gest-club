<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

$pageTitle = 'Réservations';
$adminSection = 'reservations';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/reservations.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';