<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /');
    exit;
}

$csrf->verify($_POST['csrf_token'] ?? null);

$_SESSION = [];

session_destroy();

header('Location: /connexion.php');
exit;