<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

$pageTitle = 'Fiche utilisateur';
$topbarTitle = 'Utilisateurs';
$adminSection = 'utilisateurs';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/utilisateur.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';