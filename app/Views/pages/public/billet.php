<?php
$dateReservation = new DateTimeImmutable($billet['date_reservation']);
$dateDebut = new DateTimeImmutable($billet['date_heure_debut_evenement']);
$dateFin = new DateTimeImmutable($billet['date_heure_fin_evenement']);

$referenceReservation = sprintf(
    'R-%s-%06d',
    $dateReservation->format('Y'),
    (int) $billet['id_reservation']
);

$nbPlaces = (int) $billet['nb_places'];
$prixTotal = (float) $billet['prix_total'];
$premierePlace = $places[0];

$libellesTribunes = ['NORD' => 'Nord', 'SUD' => 'Sud', 'EST' => 'Est', 'OUEST' => 'Ouest',];
$libellesNiveaux = ['BAS' => 'bas', 'MILIEU' => 'milieu', 'HAUT' => 'haut',];

$statutReservation = $billet['statut_reservation'];

$classeReservation = $statutReservation === 'CONFIRMEE' ? 'badge--success' : 'badge--danger';
$libelleReservation = $statutReservation === 'CONFIRMEE' ? 'Confirmée' : 'Annulée';
$statutBillet = $billet['statut_billet'];
$classesBillet = ['ACTIF' => 'badge--success', 'UTILISE' => 'badge--muted', 'ANNULE' => 'badge--danger',];
$libellesBillet = ['ACTIF' => 'Actif', 'UTILISE' => 'Utilisé', 'ANNULE' => 'Annulé',];
?>
<main class="page">
    <div class="container">
        <div class="page-header">
            <h1>Mon billet</h1>
        </div>
        <div class="ticket-layout">
            <div class="ticket-column">

                <article class="card event-summary">
                    <h2 class="card__title">Récapitulatif de l'évènement</h2>
                    <?php if ($billet['image_evenement'] !== null): ?>
                        <div class="media media--16x9">
                            <img src="/assets/images/<?= htmlspecialchars($billet['image_evenement']) ?>" alt="">
                        </div>
                    <?php else: ?>
                        <div class="media media--16x9 media--placeholder">
                            <span>IMG</span>
                        </div>
                    <?php endif; ?>
                    <h3 class="event-summary__title"><?= htmlspecialchars($billet['nom_evenement']) ?></h3>
                    <div class="meta-list">
                        <time datetime="<?= $dateDebut->format('Y-m-d') ?>">
                            <?= $dateDebut->format('d/m/Y') ?>
                        </time>
                        <span>
                            <time datetime="<?= $dateDebut->format('Y-m-d\TH:i') ?>">
                                <?= $dateDebut->format('H:i') ?>
                            </time>
                            -
                            <time datetime="<?= $dateFin->format('Y-m-d\TH:i') ?>">
                                <?= $dateFin->format('H:i') ?>
                            </time>
                        </span>
                    </div>
                    <div class="event-summary__actions">
                        <a class="btn btn--ghost btn--sm" href="/evenement.php?id=<?= $billet['id_evenement'] ?>">
                            Voir l'évènement
                        </a>
                    </div>
                </article>

                <article class="card">
                    <div class="panel-head">
                        <h2>Détails de la réservation</h2>
                        <span class="badge <?= $classeReservation ?>">
                            <?= $libelleReservation ?>
                        </span>
                    </div>
                    <p class="ticket-ref">Réservation <?= htmlspecialchars($referenceReservation) ?></p>
                    <dl class="detail-list">
                        <div class="detail">
                            <dt class="detail__label">Nombre de places</dt>
                            <dd class="detail__value"><?= $nbPlaces ?></dd>
                        </div>
                        <div class="detail">
                            <dt class="detail__label">Emplacement</dt>
                            <dd class="detail__value">
                                Tribune
                                <?= htmlspecialchars($libellesTribunes[$premierePlace['tribune_place']]) ?>,
                                niveau
                                <?= htmlspecialchars($libellesNiveaux[$premierePlace['niveau_place']]) ?>
                            </dd>
                        </div>
                        <?php foreach ($places as $place): ?>
                            <div class="detail">
                                <dt class="detail__label">
                                    Place
                                    <?= (int) $place['position_place'] ?>
                                </dt>
                                <dd class="detail__value">
                                    Rangée
                                    <?= htmlspecialchars($place['rangee_place']) ?>,
                                    place
                                    <?= htmlspecialchars($place['numero_place']) ?>
                                </dd>
                            </div>
                        <?php endforeach; ?>
                        <div class="detail detail--total">
                            <dt class="detail__label">Prix total</dt>
                            <dd class="detail__value">
                                <?= number_format($prixTotal, 0, ',', ' ') ?> €
                            </dd>
                        </div>
                    </dl>
                </article>

            </div>
            <div class="ticket-column">
                <a class="btn btn--ghost" href="/mes-reservations.php">Retour à mes réservations</a>

                <section class="card qr-panel">
                    <h2 class="qr-panel__title">Votre QR code</h2>
                    <?php if ($statutBillet === 'ACTIF'): ?>
                        <p class="qr-panel__text">
                            <span>Ce QR code correspond à l'ensemble de la réservation.</span>
                            <span>Il devient inutilisable après validation à l'entrée.</span>
                        </p>
                        <div class="qr-frame">
                            <img src="<?= htmlspecialchars($qrCodeDataUri, ENT_QUOTES, 'UTF-8') ?>" alt="QR code du billet">
                        </div>
                    <?php elseif ($statutBillet === 'UTILISE'): ?>
                        <div class="notice notice--warning">
                            <p>Ce billet a déjà été utilisé lors du contrôle d'entrée.
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="notice notice--danger">
                            <p>Ce billet est annulé et ne peut plus être utilisé.</p>
                        </div>
                    <?php endif; ?>
                    <span class="badge <?= $classesBillet[$statutBillet] ?>">
                        <?= $libellesBillet[$statutBillet] ?>
                    </span>
                </section>

            </div>
        </div>
    </div>
</main>