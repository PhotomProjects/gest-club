<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

$pageTitle = 'Réservation';
$currentSection = 'events';
$isAuthenticated = true;

$view = dirname(__DIR__) . '/app/Views/pages/public/reservation.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';