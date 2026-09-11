<?php

declare(strict_types=1);

$pageTitle = $pageTitle ?? 'Administration';
$adminSection = $adminSection ?? null;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="robots" content="noindex, nofollow">

    <title>
        <?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> | Gestion Lucha Tick'Est
    </title>

    <link rel="icon" href="/assets/images/favicon/favicon.ico">
    <link rel="stylesheet" href="/assets/css/base.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="stylesheet" href="/assets/css/admin-components.css">
    <script src="/assets/js/admin.js" defer></script>
</head>

<body>

    <a class="skip-link" href="#main-content">
        Aller au contenu principal
    </a>

    <p class="admin-small-screen">
        L'espace de gestion est conçu pour un écran d'ordinateur (1100 px minimum).
    </p>

    <div class="admin">

        <?php require __DIR__ . '/../partials/admin/sidebar.php'; ?>

        <div class="admin-main">

            <?php require __DIR__ . '/../partials/admin/topbar.php'; ?>

            <?php require $view; ?>

        </div>

    </div>

</body>

</html>