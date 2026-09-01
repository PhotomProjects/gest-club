<?php

declare(strict_types=1);

use App\Repositories\UtilisateurRepository;

require dirname(__DIR__) . '/config/bootstrap.php';

$auth->requireLogin();

$utilisateurRepository = new UtilisateurRepository($pdo);

// Vérification du token CSRF.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf->verify($_POST['csrf_token'] ?? null);
}

$erreurs = [];

$nom = $utilisateurConnecte['nom'];
$prenom = $utilisateurConnecte['prenom'];
$email = $utilisateurConnecte['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim(recupererChampPost('nom'));
    $prenom = trim(recupererChampPost('prenom'));
    $email = trim(recupererChampPost('email'));

    // Vérification des champs obligatoires.
    if ($nom === '') {
        $erreurs['nom'] = 'Le nom est obligatoire.';
    } elseif (mb_strlen($nom) > 100) {
        $erreurs['nom'] = 'Le nom ne doit pas dépasser 100 caractères.';
    }
    if ($prenom === '') {
        $erreurs['prenom'] = 'Le prénom est obligatoire.';
    } elseif (mb_strlen($prenom) > 100) {
        $erreurs['prenom'] = 'Le prénom ne doit pas dépasser 100 caractères.';
    }

    // Vérification de l'adresse e-mail.
    if ($email === '') {
        $erreurs['email'] = "L'adresse e-mail est obligatoire.";
    } elseif (mb_strlen($email) > 255) {
        $erreurs['email'] = "L'adresse e-mail ne doit pas dépasser 255 caractères.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs['email'] = "L'adresse e-mail est invalide.";
    }

    // Vérification de l'unicité de l'adresse e-mail.
    if ($erreurs === []) {
        $utilisateurExistant = $utilisateurRepository->findByEmail($email);
        if (
            $utilisateurExistant !== null && (int) $utilisateurExistant['id_utilisateur'] !== (int) $utilisateurConnecte['id']
        ) {
            $erreurs['email'] = 'Cette adresse e-mail est déjà utilisée.';
        }
    }

    // Mise à jour des informations.
    if ($erreurs === []) {
        $utilisateurRepository->updateInformations(
            (int) $utilisateurConnecte['id'],
            $prenom,
            $nom,
            $email
        );

        $_SESSION['utilisateur']['prenom'] = $prenom;
        $_SESSION['utilisateur']['nom'] = $nom;
        $_SESSION['utilisateur']['email'] = $email;

        header('Location: /compte-informations.php?modification=succes');
        exit;
    }
}

$pageTitle = 'Mes informations';
$currentSection = null;

$modificationReussie = ($_GET['modification'] ?? '') === 'succes';

$view = dirname(__DIR__) . '/app/Views/pages/public/compte-informations.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';