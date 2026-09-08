<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

$pageTitle = 'Données personnelles';
$currentSection = null;

$view = dirname(__DIR__) . '/app/Views/pages/public/donnees-personnelles.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';
