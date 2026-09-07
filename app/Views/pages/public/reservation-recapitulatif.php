<?php
$dateDebut = new DateTimeImmutable(
    $evenement['date_heure_debut_evenement']
);
$dateFin = new DateTimeImmutable(
    $evenement['date_heure_fin_evenement']
);
$libellesTribunes = ['NORD' => 'Nord', 'SUD' => 'Sud', 'EST' => 'Est', 'OUEST' => 'Ouest',];
$libellesNiveaux = ['BAS' => 'Bas', 'MILIEU' => 'Milieu', 'HAUT' => 'Haut',];
?>
<main class="page">
    <div class="container">
        <div class="page-header">
            <h1>Résumé de la réservation</h1>
        </div>
        <div class="reservation-layout">
            <article class="card event-summary">
                <h2 class="card__title">Récapitulatif de l'évènement</h2>
                <?php if ($evenement['image_evenement'] !== null): ?>
                    <div class="media media--16x9">
                        <img src="/assets/images/<?= htmlspecialchars($evenement['image_evenement']) ?>" alt="">
                    </div>
                <?php else: ?>
                    <div class="media media--16x9 media--placeholder">
                        <span>IMG</span>
                    </div>
                <?php endif; ?>
                <h3 class="event-summary__title">
                    <?= htmlspecialchars($evenement['nom_evenement']) ?>
                </h3>
                <div class="meta-list">
                    <time class="meta-item" datetime="<?= $dateDebut->format('Y-m-d') ?>">
                        <img class="icon" src="/assets/images/icons/calendar-days.svg" width="20" height="20" alt=""
                            aria-hidden="true">
                        <?= $dateDebut->format('d/m/Y') ?>
                    </time>
                    <span class="meta-item">
                        <img class="icon" src="/assets/images/icons/clock.svg" width="20" height="20" alt=""
                            aria-hidden="true">
                        <time datetime="<?= $dateDebut->format('Y-m-d\TH:i') ?>">
                            <?= $dateDebut->format('H:i') ?>
                        </time>
                        <span aria-hidden="true">-</span>
                        <time datetime="<?= $dateFin->format('Y-m-d\TH:i') ?>">
                            <?= $dateFin->format('H:i') ?>
                        </time>
                    </span>
                    <span class="meta-item">
                        <img class="icon" src="/assets/images/icons/map-pin.svg" width="20" height="20" alt=""
                            aria-hidden="true">
                        <?= htmlspecialchars($eventLocation) ?>
                    </span>
                </div>
                <p class="event-summary__description">
                    <?= htmlspecialchars($evenement['description_evenement']) ?>
                </p>
            </article>
            <div class="reservation-side">
                <section class="card">
                    <h2 class="card__title">Détails de la réservation</h2>
                    <dl class="detail-list">
                        <div class="detail">
                            <dt class="detail__label">Nombre de places</dt>
                            <dd class="detail__value"><?= $nbPlaces ?></dd>
                        </div>
                        <div class="detail">
                            <dt class="detail__label">Emplacement</dt>
                            <dd class="detail__value">
                                Tribune
                                <?= htmlspecialchars($libellesTribunes[$tribune]) ?>,
                                niveau
                                <?= htmlspecialchars(mb_strtolower($libellesNiveaux[$niveau])) ?>
                            </dd>
                        </div>
                        <div class="detail">
                            <dt class="detail__label">Prix unitaire</dt>
                            <dd class="detail__value">
                                <?= number_format($prixUnitaire, 0, ',', ' ') ?> €
                            </dd>
                        </div>
                        <div class="detail detail--total">
                            <dt class="detail__label">Prix total</dt>
                            <dd class="detail__value">
                                <?= number_format($prixTotal, 0, ',', ' ') ?> €
                            </dd>
                        </div>
                    </dl>
                </section>
                <div class="notice">
                    <p>Les places exactes seront attribuées après confirmation de la réservation.</p>
                </div>
                <div class="reservation-actions">
                    <a class="btn btn--ghost btn--lg" href="/reservation.php?id=<?= $idEvenement ?>">Modifier</a>
                    <form action="/reservation-confirmation.php" method="post">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <input type="hidden" name="id_evenement" value="<?= $idEvenement ?>">
                        <input type="hidden" name="nb_places" value="<?= $nbPlaces ?>">
                        <input type="hidden" name="tribune" value="<?= htmlspecialchars($tribune) ?>">
                        <input type="hidden" name="niveau" value="<?= htmlspecialchars($niveau) ?>">
                        <button class="btn btn--primary btn--lg" type="submit">Confirmer la réservation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>