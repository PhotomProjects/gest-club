<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

$pageTitle = 'Ajouter un intervenant';
$topbarTitle = 'Intervenants';
$adminSection = 'intervenants';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/intervenant-form.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';