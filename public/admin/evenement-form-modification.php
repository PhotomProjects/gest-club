<?php

declare(strict_types=1);

use App\Repositories\EvenementRepository;
use App\Services\EvenementService;
use App\Services\ImageService;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

// Récupération de l'identifiant.
$id = (int) ($_GET['id'] ?? 0);

$evenementRepository = new EvenementRepository($pdo);
$evenement = $evenementRepository->findById($id);

if ($evenement === null) {
    http_response_code(404);
    exit('Événement introuvable.');
}

// Protection CSRF.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf->verify($_POST['csrf_token'] ?? null);
}

// Valeurs initiales provenant de la BDD.
$nom = $evenement['nom_evenement'];
$dateDebutObjet = new DateTimeImmutable($evenement['date_heure_debut_evenement']);
$dateFinObjet = new DateTimeImmutable($evenement['date_heure_fin_evenement']);
$dateDebut = $dateDebutObjet->format('Y-m-d');
$heureDebut = $dateDebutObjet->format('H:i');
$dateFin = $dateFinObjet->format('Y-m-d');
$heureFin = $dateFinObjet->format('H:i');
$description = $evenement['description_evenement'];

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim(recupererChampPost('nom'));
    $dateDebut = recupererChampPost('date_debut');
    $heureDebut = recupererChampPost('heure_debut');
    $dateFin = recupererChampPost('date_fin');
    $heureFin = recupererChampPost('heure_fin');
    $description = trim(recupererChampPost('description'));

    // Nom de l'événement.
    if ($nom === '') {
        $erreurs['nom'] = "Le nom de l'évènement est obligatoire.";
    } elseif (mb_strlen($nom) > 150) {
        $erreurs['nom'] = "Le nom de l'évènement ne doit pas dépasser 150 caractères.";
    }

    // Date de début.
    if ($dateDebut === '') {
        $erreurs['date_debut'] = 'La date de début est obligatoire.';
    }

    // Heure de début.
    if ($heureDebut === '') {
        $erreurs['heure_debut'] = "L'heure de début est obligatoire.";
    }

    // Date de fin.
    if ($dateFin === '') {
        $erreurs['date_fin'] = 'La date de fin est obligatoire.';
    }

    // Heure de fin.
    if ($heureFin === '') {
        $erreurs['heure_fin'] = "L'heure de fin est obligatoire.";
    }

    // Description.
    if ($description === '') {
        $erreurs['description'] = 'La description est obligatoire.';
    } elseif (mb_strlen($description) > 2000) {
        $erreurs['description'] = 'La description ne doit pas dépasser 2000 caractères.';
    }

    // Vérification de la cohérence des dates.
    if (
        !isset(
        $erreurs['date_debut'],
        $erreurs['heure_debut'],
        $erreurs['date_fin'],
        $erreurs['heure_fin']
    )
    ) {
        $debut = DateTimeImmutable::createFromFormat('Y-m-d H:i', $dateDebut . ' ' . $heureDebut);
        $fin = DateTimeImmutable::createFromFormat('Y-m-d H:i', $dateFin . ' ' . $heureFin);

        if ($debut === false || $fin === false) {
            $erreurs['dates'] = "Les dates de l'évènement sont invalides.";
        } elseif ($fin <= $debut) {
            $erreurs['dates'] = 'La date de fin doit être postérieure à la date de début.';
        }
    }

    $nouvelleImage = null;

    if ($erreurs === []) {
        try {
            $imageService = new ImageService();
            $nouvelleImage = $imageService->uploadEventImage($_FILES['image'] ?? []);
        } catch (\DomainException $exception) {
            $erreurs['image'] = $exception->getMessage();
        }
    }

    if ($erreurs === []) {
        $image = $nouvelleImage ?? $evenement['image_evenement'];

        try {
            $evenementService = new EvenementService($pdo);
            $evenementService->update(
                $id,
                $nom,
                $description,
                $image,
                $dateDebut . ' ' . $heureDebut,
                $dateFin . ' ' . $heureFin
            );

            // Si une nouvelle image a été enregistrée, l'ancienne image uploadée peut être supprimée.
            if ($nouvelleImage !== null) {
                $imageService->deleteEventImage(
                    $evenement['image_evenement']
                );
            }

            header(
                'Location: /admin/evenement.php?id=' . $id
            );
            exit;
        } catch (\Throwable $exception) {
            // Si la mise à jour échoue après l'upload, supprime la nouvelle image devenue inutile.
            if ($nouvelleImage !== null) {
                $imageService->deleteEventImage($nouvelleImage);
            }

            if ($exception instanceof \DomainException) {
                $erreurs['general'] = $exception->getMessage();
            } else {
                throw $exception;
            }
        }
    }
}

$pageTitle = 'Modifier un évènement';
$topbarTitle = 'Évènements';
$adminSection = 'evenements';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/evenement-form-modification.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';