<?php

declare(strict_types=1);

use App\Services\IntervenantService;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

// Valeurs initiales.
$nomScene = '';
$statut = 'ACTIF';

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Protection CSRF.
    $csrf->verify($_POST['csrf_token'] ?? null);

    $nomScene = trim(recupererChampPost('nom_scene'));
    $statut = recupererChampPost('statut');

    // Validation des champs.
    if ($nomScene === '') {
        $erreurs['nom_scene'] = 'Le nom de scène est obligatoire.';
    } elseif (mb_strlen($nomScene) > 100) {
        $erreurs['nom_scene'] = 'Le nom de scène ne doit pas dépasser 100 caractères.';
    }

    if (!in_array($statut, ['ACTIF', 'INACTIF'], true)) {
        $erreurs['statut'] = 'Le statut sélectionné est invalide.';
    }

    if ($erreurs === []) {
        try {
            $intervenantService = new IntervenantService($pdo);

            $intervenantService->create(
                $nomScene,
                $statut
            );

            header(
                'Location: /admin/intervenants.php'
            );
            exit;
        } catch (\DomainException $exception) {
            $erreurs['general'] = $exception->getMessage();
        }
    }
}

$pageTitle = 'Ajouter un intervenant';
$topbarTitle = 'Intervenants';
$adminSection = 'intervenants';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/intervenant-form.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';