<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

$pageTitle = 'Mes réservations';
$currentSection = null;
$isAuthenticated = true;

$view = dirname(__DIR__) . '/app/Views/pages/public/mes-reservations.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';