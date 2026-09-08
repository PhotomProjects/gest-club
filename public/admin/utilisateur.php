<?php

declare(strict_types=1);

use App\Repositories\ReservationRepository;
use App\Repositories\UtilisateurRepository;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

// Récupération de l'utilisateur.
$idUtilisateur = (int) ($_GET['id'] ?? 0);

$utilisateurRepository = new UtilisateurRepository($pdo);
$utilisateur = $utilisateurRepository->findById($idUtilisateur);

if ($utilisateur === null) {
    require __DIR__ . '/404.php';
    exit;
}

// Réservations associées.
$reservationRepository = new ReservationRepository($pdo);
$reservations = $reservationRepository->findByUserForAdmin($idUtilisateur);

$pageTitle = 'Fiche utilisateur';
$topbarTitle = 'Utilisateurs';
$adminSection = 'utilisateurs';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/utilisateur.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';