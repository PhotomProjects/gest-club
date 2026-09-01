<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

$auth->requireLogin();

$pageTitle = 'Réservation';
$currentSection = 'events';

$view = dirname(__DIR__) . '/app/Views/pages/public/reservation.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';