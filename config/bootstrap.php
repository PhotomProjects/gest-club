<?php

declare(strict_types=1);

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Repositories\UtilisateurRepository;

// Chargement de l'autoload Composer.
require dirname(__DIR__) . '/vendor/autoload.php';

// Chargement de la configuration de l'application.
$config = require __DIR__ . '/app.php';

$eventLocation = $config['event_location'];

// Configuration de l'affichage des erreurs.
error_reporting(E_ALL);

if ($config['debug']) {
    ini_set('display_errors', '1');
} else {
    ini_set('display_errors', '0');
}

// Configuration du fuseau horaire.
date_default_timezone_set($config['timezone']);

// Démarrage de la session.
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);

    session_start();
}

// Connexion à la base de données.
$database = new Database($config['database']);
$pdo = $database->getConnection();

// Synchronisation du fuseau horaire de MariaDB avec celui de PHP. date('P') retourne par exemple +02:00 en été et +01:00 en hiver.
$pdo->exec('SET time_zone = ' . $pdo->quote(date('P')));

// État de l'utilisateur connecté.
$sessionUtilisateur = $_SESSION['utilisateur'] ?? null;
$utilisateurConnecte = is_array($sessionUtilisateur) ? $sessionUtilisateur : null;

// Synchronise le rôle de la session avec la base de données.
if (
    $utilisateurConnecte !== null && isset($utilisateurConnecte['id'])
) {
    $utilisateurRepository = new UtilisateurRepository($pdo);
    $utilisateurActuel = $utilisateurRepository->findById((int) $utilisateurConnecte['id']);

    // Le compte n'existe plus.
    if ($utilisateurActuel === null) {
        unset($_SESSION['utilisateur']);
        $utilisateurConnecte = null;
    } else {
        $roleActuel = $utilisateurActuel['role_utilisateur'];

        // Si le rôle a changé, renouvelle également l'identifiant de session.
        if (
            ($utilisateurConnecte['role'] ?? null) !== $roleActuel
        ) {
            session_regenerate_id(true);
        }

        $_SESSION['utilisateur']['role'] = $roleActuel;

        $utilisateurConnecte = $_SESSION['utilisateur'];
    }
}

$auth = new Auth($utilisateurConnecte);
$isAuthenticated = $auth->isAuthenticated();

// Protection CSRF.
$csrf = new Csrf();
$csrfToken = $csrf->getToken();

// Récupère un champ POST uniquement s'il contient une chaîne.
function recupererChampPost(string $nomChamp): string
{
    $valeur = $_POST[$nomChamp] ?? '';
    return is_string($valeur) ? $valeur : '';
}

// Vérifie les règles communes des mots de passe.
function estMotDePasseValide(string $motDePasse): bool
{
    return strlen($motDePasse) >= 8
        && preg_match('/[a-z]/', $motDePasse)
        && preg_match('/[A-Z]/', $motDePasse)
        && preg_match('/[0-9]/', $motDePasse)
        && preg_match('/[^a-zA-Z0-9\s]/', $motDePasse)
        && !preg_match('/\s/', $motDePasse);
}