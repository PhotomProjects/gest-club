<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

$pageTitle = 'Sécurité du compte';
$currentSection = null;
$isAuthenticated = true;

$view = dirname(__DIR__) . '/app/Views/pages/public/compte-securite.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';