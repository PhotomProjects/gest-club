<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

$pageTitle = 'Accueil';
$currentSection = 'home';
$isAuthenticated = false;

$view = dirname(__DIR__) . '/app/Views/pages/public/accueil.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';