<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

$pageTitle = 'Mes informations';
$currentSection = null;
$isAuthenticated = true;

$view = dirname(__DIR__) . '/app/Views/pages/public/compte-informations.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';