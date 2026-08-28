<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

$pageTitle = 'Résultat du contrôle';
$topbarTitle = 'Contrôle des billets';
$adminSection = 'presences';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/presences-resultat.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';