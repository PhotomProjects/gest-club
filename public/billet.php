<?php

declare(strict_types=1);

use App\Repositories\BilletRepository;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;

require dirname(__DIR__) . '/config/bootstrap.php';

$auth->requireLogin();

$idBillet = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1,],]);

if ($idBillet === false || $idBillet === null) {
    http_response_code(404);
    exit('Billet introuvable.');
}

$billetRepository = new BilletRepository($pdo);

$billet = $billetRepository->findByIdForUser(
    $idBillet,
    (int) $utilisateurConnecte['id']
);

if ($billet === null) {
    http_response_code(404);
    exit('Billet introuvable.');
}

$places = $billetRepository->findPlacesByIdForUser(
    $idBillet,
    (int) $utilisateurConnecte['id']
);

if ($places === []) {
    http_response_code(500);
    exit('Les places du billet sont introuvables.');
}

// Génération du QR uniquement si le billet est actif.
$qrCodeDataUri = null;

if ($billet['statut_billet'] === 'ACTIF') {
    $qrCode = new QrCode((string) $billet['code_billet']);
    $writer = new SvgWriter();
    $result = $writer->write($qrCode);

    $qrCodeDataUri = $result->getDataUri();
}

$pageTitle = 'Mon billet';
$currentSection = null;

$view = dirname(__DIR__) . '/app/Views/pages/public/billet.php';

require dirname(__DIR__) . '/app/Views/layouts/public.php';