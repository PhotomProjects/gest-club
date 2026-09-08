<?php

declare(strict_types=1);

// Environnement dédié aux tests automatisés.
putenv('APP_ENV=test');
putenv('APP_DEBUG=false');
putenv('DB_DATABASE=gest_club_test');
date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'Europe/Paris');

// Chargement de l'autoload Composer.
require dirname(__DIR__) . '/vendor/autoload.php';