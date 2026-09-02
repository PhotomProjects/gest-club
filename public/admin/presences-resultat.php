<?php

declare(strict_types=1);

use App\Services\PresenceService;

require dirname(__DIR__, 2) . '/config/bootstrap.php';

// Authentification et autorisation.
$auth->requireRole('ADMIN');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/presences-scan.php');
    exit;
}

$csrf->verify($_POST['csrf_token'] ?? null);

$action = recupererChampPost('action');
$presenceService = new PresenceService($pdo);

// Recherche et affichage d'un billet.
if ($action === 'rechercher') {
    $codeControle = recupererChampPost('code');
    try {
        $ticketControle = $presenceService->findTicket($codeControle);
    } catch (\DomainException $exception) {
        $_SESSION['erreur_controle_billet'] = [
            'message' => $exception->getMessage(),
            'code' => mb_substr(
                trim($codeControle),
                0,
                100
            ),
        ];

        header('Location: /admin/presences-scan.php');
        exit;
    }

    $pageTitle = 'Résultat du contrôle';
    $topbarTitle = 'Contrôle des billets';
    $adminSection = 'presences';

    $view = dirname(__DIR__, 2) . '/app/Views/pages/admin/presences-resultat.php';

    require dirname(__DIR__, 2) . '/app/Views/layouts/admin.php';

    exit;
}

// Validation définitive de l'entrée.
if ($action === 'valider') {
    $idBillet = filter_var(recupererChampPost('id_billet'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1,],]);
    if ($idBillet === false) {
        http_response_code(400);
        exit('Identifiant du billet invalide.');
    }
    try {
        $presenceService->validateEntry($idBillet);

        $_SESSION['message_controle_billet'] = "L'entrée a bien été validée.";
    } catch (\DomainException $exception) {
        $_SESSION['erreur_controle_billet'] = [
            'message' => $exception->getMessage(),
            'code' => '',
        ];
    }

    // PRG : le rafraîchissement ne répète pas la validation.
    header('Location: /admin/presences-scan.php');
    exit;
}

http_response_code(400);
exit('Action de contrôle invalide.');