<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

$pageTitle = 'Connexion';
$currentSection = null;
$isAuthenticated = false;

$view = dirname(__DIR__) . '/app/Views/pages/public/connexion.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';