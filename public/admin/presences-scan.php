<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit('Méthode non autorisée.');
}

// Récupération d'une éventuelle erreur de recherche.
$erreurControle = $_SESSION['erreur_controle_billet'] ?? null;
unset($_SESSION['erreur_controle_billet']);

$codeControle = '';

if (
    is_array($erreurControle) && is_string($erreurControle['message'] ?? null) && is_string($erreurControle['code'] ?? null)
) {
    $codeControle = $erreurControle['code'];
    $erreurControle = $erreurControle['message'];
} else {
    $erreurControle = null;
}

$messageControle = $_SESSION['message_controle_billet'] ?? null;
unset($_SESSION['message_controle_billet']);

if (!is_string($messageControle)) {
    $messageControle = null;
}

$pageTitle = 'Interface de contrôle';
$topbarTitle = 'Contrôle des billets';
$adminSection = 'presences';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/presences-scan.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';