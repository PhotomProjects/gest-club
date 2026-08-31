<?php

declare(strict_types=1);

use App\Repositories\EvenementRepository;

require dirname(__DIR__) . '/config/bootstrap.php';

// Récupération des événements depuis la base de données.
$evenementRepository = new EvenementRepository($pdo);
$evenements = $evenementRepository->findUpcoming();

$pageTitle = 'Évènements';
$currentSection = 'events';
$isAuthenticated = false;

$view = dirname(__DIR__) . '/app/Views/pages/public/evenements.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';