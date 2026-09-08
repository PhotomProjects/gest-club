<?php

declare(strict_types=1);

use App\Repositories\EvenementRepository;
use App\Repositories\IntervenantRepository;
use App\Repositories\MatchRepository;
use App\Services\MatchService;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

// Récupération de l'événement.
$idEvenement = (int) ($_GET['id_evenement'] ?? 0);

$evenementRepository = new EvenementRepository($pdo);
$evenement = $evenementRepository->findById($idEvenement);

if ($evenement === null) {
    require __DIR__ . '/404.php';
    exit;
}

// Données nécessaires au formulaire.
$matchRepository = new MatchRepository($pdo);
$typesMatch = $matchRepository->findAllTypes();

$intervenantRepository = new IntervenantRepository($pdo);
$intervenants = $intervenantRepository->findActive();

// Valeurs initiales.
$nom = '';
$idTypeMatch = 0;
$ordre = 1;
$catcheurs = ['', ''];
$camps = ['', ''];
$arbitre = '';
$managers = [];
$managerCamps = [];
$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Protection CSRF.
    $csrf->verify($_POST['csrf_token'] ?? null);

    // Valeurs simples.
    $nom = trim(recupererChampPost('nom'));
    $idTypeMatch = (int) recupererChampPost('type_match');
    $ordre = (int) recupererChampPost('ordre');
    $arbitre = recupererChampPost('arbitre');

    // Valeurs multiples.
    $catcheurs = is_array($_POST['catcheur'] ?? null) ? $_POST['catcheur'] : [];
    $camps = is_array($_POST['camp'] ?? null) ? $_POST['camp'] : [];
    $managers = is_array($_POST['manager'] ?? null) ? $_POST['manager'] : [];
    $managerCamps = is_array($_POST['manager_camp'] ?? null) ? $_POST['manager_camp'] : [];

    // Nom.
    if ($nom === '') {
        $erreurs['nom'] = 'Le nom du match est obligatoire.';
    } elseif (mb_strlen($nom) > 150) {
        $erreurs['nom'] = 'Le nom du match ne doit pas dépasser 150 caractères.';
    }

    // Type.
    if ($idTypeMatch <= 0) {
        $erreurs['type_match'] = 'Le type de match est obligatoire.';
    }

    // Ordre.
    if ($ordre < 1 || $ordre > 255) {
        $erreurs['ordre'] = "L'ordre du match doit être compris entre 1 et 255.";
    }

    // Catcheurs.
    if ($catcheurs === []) {
        $erreurs['catcheurs'] = 'Les catcheurs du match doivent être sélectionnés.';
    }

    // Arbitre.
    if ((int) $arbitre <= 0) {
        $erreurs['arbitre'] = 'Un arbitre doit être sélectionné.';
    }

    // Création.
    if ($erreurs === []) {
        try {
            $matchService = new MatchService($pdo);

            $matchService->create(
                $idEvenement,
                $nom,
                $idTypeMatch,
                $ordre,
                $catcheurs,
                $camps,
                (int) $arbitre,
                $managers,
                $managerCamps
            );

            header(
                'Location: /admin/evenement.php?id=' . $idEvenement
            );
            exit;
        } catch (\DomainException $exception) {
            $erreurs['general'] = $exception->getMessage();
        }
    }
}

$pageTitle = 'Ajouter un match';
$topbarTitle = 'Évènements';
$adminSection = 'evenements';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/evenement-match.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';