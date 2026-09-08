<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/bootstrap.php';

$auth->requireRole('ADMIN');

http_response_code(404);

$pageTitle = 'Page introuvable';
$topbarTitle = 'Erreur 404';
$adminSection = null;

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/404.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';