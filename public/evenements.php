<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

$pageTitle = 'Évènements';
$currentSection = 'events';
$isAuthenticated = false;

$view = dirname(__DIR__) . '/app/Views/pages/public/evenements.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';