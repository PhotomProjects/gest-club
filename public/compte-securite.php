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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $motDePasseActuel = recupererChampPost('mot_de_passe_actuel');
    $nouveauMotDePasse = recupererChampPost('nouveau_mot_de_passe');
    $nouveauMotDePasseConfirmation = recupererChampPost('nouveau_mot_de_passe_confirmation');

    // Vérification des champs obligatoires.
    if ($motDePasseActuel === '') {
        $erreurs['mot_de_passe_actuel'] = 'Le mot de passe actuel est obligatoire.';
    }
    if ($nouveauMotDePasse === '') {
        $erreurs['nouveau_mot_de_passe'] = 'Le nouveau mot de passe est obligatoire.';
    } elseif (!estMotDePasseValide($nouveauMotDePasse)) {
        $erreurs['nouveau_mot_de_passe'] = 'Le nouveau mot de passe ne respecte pas les règles demandées.';
    }

    // Confirmation du nouveau mot de passe.
    if ($nouveauMotDePasseConfirmation === '') {
        $erreurs['nouveau_mot_de_passe_confirmation'] = 'La confirmation du nouveau mot de passe est obligatoire.';
    } elseif ($nouveauMotDePasse !== $nouveauMotDePasseConfirmation) {
        $erreurs['nouveau_mot_de_passe_confirmation'] = 'Les mots de passe ne correspondent pas.';
    }

    // Vérification du mot de passe actuel.
    if ($erreurs === []) {
        $utilisateur = $utilisateurRepository->findById(
            (int) $utilisateurConnecte['id']
        );

        if (
            $utilisateur === null || !password_verify($motDePasseActuel, $utilisateur['mdp_hash'])
        ) {
            $erreurs['mot_de_passe_actuel'] = 'Le mot de passe actuel est incorrect.';
        }
    }

    // Mise à jour.
    if ($erreurs === []) {
        $passwordHash = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
        $utilisateurRepository->updatePassword(
            (int) $utilisateurConnecte['id'],
            $passwordHash
        );

        session_regenerate_id(true);

        header('Location: /compte-securite.php?modification=succes');
        exit;
    }
}

$pageTitle = 'Sécurité du compte';
$currentSection = null;

$modificationReussie = ($_GET['modification'] ?? '') === 'succes';

$view = dirname(__DIR__) . '/app/Views/pages/public/compte-securite.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';