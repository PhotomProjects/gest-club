<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

$pageTitle = 'Résumé de la réservation';
$currentSection = 'events';
$isAuthenticated = true;

$view = dirname(__DIR__) . '/app/Views/pages/public/reservation-recapitulatif.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';