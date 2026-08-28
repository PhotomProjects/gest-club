<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

$pageTitle = 'Accès refusé';
$robots = 'noindex';

$currentSection = null;
$isAuthenticated = false;

$view = dirname(__DIR__) . '/app/Views/pages/public/403.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';