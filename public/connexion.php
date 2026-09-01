<?php

declare(strict_types=1);

use App\Repositories\UtilisateurRepository;

require dirname(__DIR__) . '/config/bootstrap.php';

$inscriptionReussie = $_SESSION['inscription_reussie'] ?? false;
unset($_SESSION['inscription_reussie']);

$erreursConnexion = [];
$emailConnexion = '';

$utilisateurRepository = new UtilisateurRepository($pdo);

// Vérification du token CSRF pour tous les formulaires POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf->verify($_POST['csrf_token'] ?? null);
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formulaire'] ?? '') === 'connexion'
) {
    // Récupération des données du formulaire.
    $emailConnexion = trim(recupererChampPost('email'));
    $motDePasseConnexion = recupererChampPost('mot_de_passe');

    // Vérification de l'adresse e-mail.
    if ($emailConnexion === '') {
        $erreursConnexion['email'] = "L'adresse e-mail est obligatoire.";
    } elseif (mb_strlen($emailConnexion) > 255) {
        $erreursConnexion['email'] = "L'adresse e-mail ne doit pas dépasser 255 caractères.";
    } elseif (!filter_var($emailConnexion, FILTER_VALIDATE_EMAIL)) {
        $erreursConnexion['email'] = "L'adresse e-mail est invalide.";
    }

    // Vérification du mot de passe.
    if ($motDePasseConnexion === '') {
        $erreursConnexion['mot_de_passe'] = 'Le mot de passe est obligatoire.';
    }

    // Vérification des identifiants.
    if ($erreursConnexion === []) {
        $utilisateur = $utilisateurRepository->findByEmail($emailConnexion);
        if (
            $utilisateur === null || !password_verify($motDePasseConnexion, $utilisateur['mdp_hash'])
        ) {
            $erreursConnexion['identifiants'] = 'Adresse e-mail ou mot de passe incorrect.';
        } else {
            session_regenerate_id(true);

            $_SESSION['utilisateur'] = [
                'id' => $utilisateur['id_utilisateur'],
                'prenom' => $utilisateur['prenom'],
                'nom' => $utilisateur['nom'],
                'email' => $utilisateur['email'],
                'role' => $utilisateur['role_utilisateur'],
            ];

            if ($utilisateur['role_utilisateur'] === 'ADMIN') {
                header('Location: /admin/index.php');
            } else {
                header('Location: /compte.php');
            }

            exit;
        }
    }
}

$erreursInscription = [];
$nom = '';
$prenom = '';
$email = '';
$emailConfirmation = '';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formulaire'] ?? '') === 'inscription'
) {
    // Récupération des données du formulaire.
    $nom = trim(recupererChampPost('nom'));
    $prenom = trim(recupererChampPost('prenom'));
    $email = trim(recupererChampPost('email'));
    $emailConfirmation = trim(recupererChampPost('email_confirmation'));
    $motDePasse = recupererChampPost('mot_de_passe');
    $motDePasseConfirmation = recupererChampPost('mot_de_passe_confirmation');

    // Vérification des champs obligatoires.
    if ($nom === '') {
        $erreursInscription['nom'] = 'Le nom est obligatoire.';
    } elseif (mb_strlen($nom) > 100) {
        $erreursInscription['nom'] = 'Le nom ne doit pas dépasser 100 caractères.';
    }
    if ($prenom === '') {
        $erreursInscription['prenom'] = 'Le prénom est obligatoire.';
    } elseif (mb_strlen($prenom) > 100) {
        $erreursInscription['prenom'] = 'Le prénom ne doit pas dépasser 100 caractères.';
    }

    // Vérification de l'adresse e-mail.
    if ($email === '') {
        $erreursInscription['email'] = "L'adresse e-mail est obligatoire.";
    } elseif (mb_strlen($email) > 255) {
        $erreursInscription['email'] = "L'adresse e-mail ne doit pas dépasser 255 caractères.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreursInscription['email'] = "L'adresse e-mail est invalide.";
    }
    if ($emailConfirmation === '') {
        $erreursInscription['email_confirmation'] = "La confirmation de l'adresse e-mail est obligatoire.";
    } elseif (mb_strlen($emailConfirmation) > 255) {
        $erreursInscription['email_confirmation'] = "L'adresse e-mail ne doit pas dépasser 255 caractères.";
    } elseif ($email !== $emailConfirmation) {
        $erreursInscription['email_confirmation'] = "Les adresses e-mail ne correspondent pas.";
    }

    // Mot de passe.
    if ($motDePasse === '') {
        $erreursInscription['mot_de_passe'] = 'Le mot de passe est obligatoire.';
    } elseif (!estMotDePasseValide($motDePasse)) {
        $erreursInscription['mot_de_passe'] = 'Le mot de passe ne respecte pas les règles demandées.';
    }
    // Confirmation du mot de passe.
    if ($motDePasseConfirmation === '') {
        $erreursInscription['mot_de_passe_confirmation'] = 'La confirmation du mot de passe est obligatoire.';
    } elseif ($motDePasse !== $motDePasseConfirmation) {
        $erreursInscription['mot_de_passe_confirmation'] = 'Les mots de passe ne correspondent pas.';
    }

    // Vérification de l'unicité de l'adresse e-mail.
    if ($erreursInscription === []) {
        $utilisateurExistant = $utilisateurRepository->findByEmail($email);
        if ($utilisateurExistant !== null) {
            $erreursInscription['email'] = 'Cette adresse e-mail est déjà utilisée.';
        }
    }

    // Création du compte.
    if ($erreursInscription === []) {
        $passwordHash = password_hash($motDePasse, PASSWORD_DEFAULT);

        $utilisateurRepository->create(
            $prenom,
            $nom,
            $email,
            $passwordHash
        );

        $_SESSION['inscription_reussie'] = true;

        header('Location: /connexion.php');
        exit;
    }
}

$pageTitle = 'Connexion';
$currentSection = null;

$view = dirname(__DIR__) . '/app/Views/pages/public/connexion.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';