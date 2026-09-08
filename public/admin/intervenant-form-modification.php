<?php

declare(strict_types=1);

use App\Repositories\IntervenantRepository;
use App\Services\IntervenantService;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

// Récupération de l'intervenant.
$idIntervenant = (int) ($_GET['id'] ?? 0);

$intervenantRepository = new IntervenantRepository($pdo);

$intervenant = $intervenantRepository->findById($idIntervenant);

if ($intervenant === null) {
    require __DIR__ . '/404.php';
    exit;
}

// Valeurs initiales.
$nomScene = $intervenant['nom_scene'];
$statut = $intervenant['statut_intervenant'];

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Protection CSRF.
    $csrf->verify($_POST['csrf_token'] ?? null);

    $nomScene = trim(recupererChampPost('nom_scene'));
    $statut = recupererChampPost('statut');

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

            $intervenantService->update(
                $idIntervenant,
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

$pageTitle = 'Modifier un intervenant';
$topbarTitle = 'Intervenants';
$adminSection = 'intervenants';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/intervenant-form-modification.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';