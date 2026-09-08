<?php

declare(strict_types=1);

use App\Repositories\UtilisateurRepository;
use App\Services\UtilisateurService;

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

$role = $utilisateur['role_utilisateur'];
$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Protection CSRF.
    $csrf->verify($_POST['csrf_token'] ?? null);

    $role = recupererChampPost('role');

    if (!in_array($role, ['MEMBRE', 'ADMIN'], true)) {
        $erreurs['role'] = 'Le rôle sélectionné est invalide.';
    }

    if ($erreurs === []) {
        try {
            $utilisateurService = new UtilisateurService($pdo);
            $utilisateurService->updateRole($idUtilisateur, $role);

            header(
                'Location: /admin/utilisateurs.php'
            );
            exit;
        } catch (\DomainException $exception) {
            $erreurs['general'] = $exception->getMessage();
        }
    }
}

$pageTitle = 'Modifier un utilisateur';
$topbarTitle = 'Utilisateurs';
$adminSection = 'utilisateurs';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/utilisateur-form-modification.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';