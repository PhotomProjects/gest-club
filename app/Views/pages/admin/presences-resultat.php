<?php
$dateReservation = new DateTimeImmutable($ticketControle['date_reservation']);
$dateDebut = new DateTimeImmutable($ticketControle['date_heure_debut_evenement']);
$referenceBillet = sprintf(
    'B-%06d',
    (int) $ticketControle['id_billet']
);
$referenceReservation = sprintf(
    'R-%s-%06d',
    $dateReservation->format('Y'),
    (int) $ticketControle['id_reservation']
);
$nbPlaces = (int) $ticketControle['nb_places'];
$statutBillet = $ticketControle['statut_billet'];

$libellesTribunes = ['NORD' => 'Nord', 'SUD' => 'Sud', 'EST' => 'Est', 'OUEST' => 'Ouest',];
$libellesNiveaux = ['BAS' => 'bas', 'MILIEU' => 'milieu', 'HAUT' => 'haut',];

$configurationResultat = match ($statutBillet) {
    'ACTIF' => [
        'classe' => 'result--ok',
        'marque' => 'OK',
        'titre' => 'Entrée autorisée',
        'message' => "Le billet est valide. Vérifiez les informations, puis validez l'entrée.",
        'badge_classe' => 'badge--success',
        'badge_libelle' => 'Actif',
    ],

    'UTILISE' => [
        'classe' => 'result--warn',
        'marque' => '!',
        'titre' => 'Billet déjà utilisé',
        'message' => "Une présence a déjà été enregistrée pour ce billet.",
        'badge_classe' => 'badge--muted',
        'badge_libelle' => 'Utilisé',
    ],

    default => [
        'classe' => 'result--error',
        'marque' => 'X',
        'titre' => 'Entrée refusée',
        'message' => "Ce billet ou sa réservation est annulé.",
        'badge_classe' => 'badge--danger',
        'badge_libelle' => 'Annulé',
    ],
};

if (
    $statutBillet === 'UTILISE' && $ticketControle['date_heure_controle'] !== null
) {
    $dateControle = new DateTimeImmutable($ticketControle['date_heure_controle']);

    $configurationResultat['message'] .= sprintf(
        ' Contrôle effectué le %s.',
        $dateControle->format('d/m/Y à H:i')
    );
}
?>
<main id="main-content" class="admin-content" tabindex="-1">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Résultat du contrôle</h1>
        </div>
    </div>

    <article class="panel result <?= $configurationResultat['classe'] ?>">
        <span class="result__mark" aria-hidden="true"><?= $configurationResultat['marque'] ?></span>

        <div class="result__body">
            <h2 class="result__title">
                <?= htmlspecialchars($configurationResultat['titre']) ?>
            </h2>
            <p class="result__message">
                <?= htmlspecialchars($configurationResultat['message']) ?>
            </p>
            <p class="result__status">
                <span class="badge <?= $configurationResultat['badge_classe'] ?>">
                    <?= htmlspecialchars($configurationResultat['badge_libelle']) ?>
                </span>
            </p>
        </div>
    </article>

    <section class="panel">
        <div class="panel__body">
            <dl class="kv">
                <div class="kv__row">
                    <dt class="kv__key">Billet</dt>
                    <dd class="kv__value">
                        <?= htmlspecialchars($referenceBillet) ?>
                    </dd>
                </div>
                <div class="kv__row">
                    <dt class="kv__key">Réservation</dt>
                    <dd class="kv__value">
                        <?= htmlspecialchars($referenceReservation) ?>
                    </dd>
                </div>
                <div class="kv__row">
                    <dt class="kv__key">Spectateur</dt>
                    <dd class="kv__value">
                        <?= htmlspecialchars($ticketControle['prenom'] . ' ' . $ticketControle['nom']) ?>
                    </dd>
                </div>
                <div class="kv__row">
                    <dt class="kv__key">Évènement</dt>
                    <dd class="kv__value">
                        <?= htmlspecialchars($ticketControle['nom_evenement']) ?>
                        —
                        <time datetime="<?= $dateDebut->format('Y-m-d\TH:i') ?>">
                            <?= $dateDebut->format('d/m/Y à H:i') ?>
                        </time>
                    </dd>
                </div>
                <div class="kv__row">
                    <dt class="kv__key">Places</dt>
                    <dd class="kv__value">
                        <?= $nbPlaces ?>
                        place<?= $nbPlaces > 1 ? 's' : '' ?>
                        — Tribune
                        <?= htmlspecialchars($libellesTribunes[$ticketControle['tribune_place']]) ?>,
                        niveau
                        <?= htmlspecialchars($libellesNiveaux[$ticketControle['niveau_place']]) ?>
                        — Places
                        <?= htmlspecialchars($ticketControle['places_attribuees']) ?>
                    </dd>
                </div>
            </dl>
        </div>
        <div class="panel__foot">
            <a class="btn btn--ghost" href="/admin/presences-scan.php">
                Contrôler un autre billet
            </a>
            <?php if ($statutBillet === 'ACTIF'): ?>
                <form action="/admin/presences-resultat.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <input type="hidden" name="action" value="valider">
                    <input type="hidden" name="id_billet" value="<?= (int) $ticketControle['id_billet'] ?>">
                    <button class="btn btn--primary" type="submit">Valider l'entrée (<?= $nbPlaces ?> pers.)</button>
                </form>
            <?php endif; ?>
        </div>
    </section>
</main>