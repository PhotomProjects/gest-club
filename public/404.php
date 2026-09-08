<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/bootstrap.php';

http_response_code(404);

$pageTitle = 'Page introuvable';
$robots = 'noindex';

$currentSection = null;

$view = dirname(__DIR__) . '/app/Views/pages/public/404.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';