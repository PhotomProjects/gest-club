<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

$pageTitle = 'Détail de la réservation';
$topbarTitle = 'Réservations';
$adminSection = 'reservations';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/reservation.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';