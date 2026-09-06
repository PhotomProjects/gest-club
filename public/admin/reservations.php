<?php

declare(strict_types=1);

use App\Repositories\ReservationRepository;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

$auth->requireRole('ADMIN');

$reservationRepository = new ReservationRepository($pdo);

$reservations = $reservationRepository->findAllForAdmin();

$pageTitle = 'Réservations';
$topbarTitle = 'Réservations';
$adminSection = 'reservations';

$view = dirname(__DIR__, 2) . '/app/Views/pages/admin/reservations.php';

require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';