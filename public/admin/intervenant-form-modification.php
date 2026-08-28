<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/config/bootstrap.php';

$pageTitle = 'Modifier un intervenant';
$topbarTitle = 'Intervenants';
$adminSection = 'intervenants';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/intervenant-form-modification.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';