<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

$pageTitle = "Vue d'ensemble";
$adminSection = 'dashboard';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/accueil.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';