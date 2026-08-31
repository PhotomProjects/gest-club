<?php

declare(strict_types=1);

use App\Core\Database;

// Chargement de l'autoload Composer.
require dirname(__DIR__) . '/vendor/autoload.php';

// Chargement de la configuration de l'application.
$config = require __DIR__ . '/app.php';

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