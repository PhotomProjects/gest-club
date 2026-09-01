<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

$auth->requireLogin();

$pageTitle = 'Réservation confirmée';
$currentSection = 'events';

$view = dirname(__DIR__) . '/app/Views/pages/public/reservation-confirmation.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';