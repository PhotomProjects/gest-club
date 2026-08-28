<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

$pageTitle = "Annuler l'évènement";
$topbarTitle = 'Évènements';
$adminSection = 'evenements';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/evenement-annulation.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';