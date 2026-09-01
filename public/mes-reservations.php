<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

$auth->requireLogin();

$pageTitle = 'Mes réservations';
$currentSection = null;

$view = dirname(__DIR__) . '/app/Views/pages/public/mes-reservations.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';