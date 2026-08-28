<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

$pageTitle = "Vérifier l'évènement";
$topbarTitle = 'Évènements';
$adminSection = 'evenements';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/evenement-recapitulatif.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';