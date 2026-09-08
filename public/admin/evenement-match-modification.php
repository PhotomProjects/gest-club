<?php

declare(strict_types=1);

use App\Repositories\EvenementRepository;
use App\Repositories\IntervenantRepository;
use App\Repositories\MatchRepository;
use App\Services\MatchService;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

// Récupération du match.
$idMatch = (int) ($_GET['id'] ?? 0);

$matchRepository = new MatchRepository($pdo);
$match = $matchRepository->findById($idMatch);

if ($match === null) {
    require __DIR__ . '/404.php';
    exit;
}

// Récupération de l'événement associé.
$idEvenement = (int) $match['id_evenement'];

$evenementRepository = new EvenementRepository($pdo);
$evenement = $evenementRepository->findById($idEvenement);

if ($evenement === null) {
    require __DIR__ . '/404.php';
    exit;
}

// Données nécessaires au formulaire.
$typesMatch = $matchRepository->findAllTypes();

$intervenantRepository = new IntervenantRepository($pdo);
$intervenants = $intervenantRepository->findActive();

// Valeurs principales provenant de la BDD.
$nom = $match['nom_match'];
$idTypeMatch = (int) $match['id_type_match'];
$ordre = (int) $match['ordre_match'];

$catcheurs = [];
$camps = [];

$arbitre = '';

$managers = [];
$managerCamps = [];

// Reconstruction des participations existantes.
$participations = $matchRepository->findParticipationsByMatchId($idMatch);

foreach ($participations as $participation) {
    $idIntervenant = (int) $participation['id_intervenant'];
    $camp = $participation['camp_participation'];

    if ($participation['role_participation'] === 'CATCHEUR') {
        $catcheurs[] = $idIntervenant;
        $camps[] = (string) $camp;
    }

    if ($participation['role_participation'] === 'ARBITRE') {
        $arbitre = (string) $idIntervenant;
    }

    if ($participation['role_participation'] === 'MANAGER') {
        $managers[] = $idIntervenant;
        $managerCamps[] = (string) $camp;
    }
}

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

    // Validation simple des champs.
    if ($nom === '') {
        $erreurs['nom'] = 'Le nom du match est obligatoire.';
    } elseif (mb_strlen($nom) > 150) {
        $erreurs['nom'] =
            'Le nom du match ne doit pas dépasser 150 caractères.';
    }

    if ($idTypeMatch <= 0) {
        $erreurs['type_match'] = 'Le type de match est obligatoire.';
    }

    if ($ordre < 1 || $ordre > 255) {
        $erreurs['ordre'] = "L'ordre du match doit être compris entre 1 et 255.";
    }

    if ($catcheurs === []) {
        $erreurs['catcheurs'] = 'Les catcheurs du match doivent être sélectionnés.';
    }

    if ((int) $arbitre <= 0) {
        $erreurs['arbitre'] = 'Un arbitre doit être sélectionné.';
    }

    // Modification.
    if ($erreurs === []) {
        try {
            $matchService = new MatchService($pdo);

            $matchService->update(
                $idMatch,
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

$pageTitle = 'Modifier un match';
$topbarTitle = 'Évènements';
$adminSection = 'evenements';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/evenement-match-modification.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';