<?php

declare(strict_types=1);

$pageTitle = $pageTitle ?? 'Accueil';
$robots = $robots ?? null;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if ($robots !== null): ?>
        <meta name="robots" content="<?= htmlspecialchars($robots, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>

    <title>
        <?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> | Lucha Tick'Est
    </title>

    <link rel="icon" href="/assets/images/favicon/favicon.ico">
    <link rel="stylesheet" href="/assets/css/base.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/site.css">
    <link rel="stylesheet" href="/assets/css/pages.css">
    <script src="/assets/js/site.js" defer></script>
</head>

<body>

    <a class="skip-link" href="#main-content">Aller au contenu principal</a>

    <?php require __DIR__ . '/../partials/public/header.php'; ?>

    <?php require $view; ?>

    <?php require __DIR__ . '/../partials/public/footer.php'; ?>

</body>

</html>