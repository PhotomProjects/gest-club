<?php
$maintenant = new DateTimeImmutable();
?>

<div class="banner" aria-hidden="true"></div>

<main class="page">
    <div class="container">
        <div class="page-header">
            <h1>Réservez vos places</h1>
            <p>Évènements à venir et en cours</p>
        </div>
        <section>
            <?php if ($evenementsAccueil === []): ?>
                <div class="empty-state">
                    <p class="empty-state__title">Aucun évènement à venir</p>
                    <p>Les prochains évènements apparaîtront ici dès qu'ils seront disponibles.</p>
                </div>
            <?php else: ?>
                <div class="event-grid">
                    <?php foreach ($evenementsAccueil as $evenement): ?>
                        <?php
                        $dateDebut = new DateTimeImmutable($evenement['date_heure_debut_evenement']);
                        $dateFin = new DateTimeImmutable($evenement['date_heure_fin_evenement']);
                        $estEnCours = $dateDebut <= $maintenant && $dateFin > $maintenant;
                        $estComplet = (int) $evenement['places_disponibles'] === 0; ?>
                        <article class="event-card">
                            <?php if ($evenement['image_evenement'] !== null): ?>
                                <div class="event-card__media media media--4x3">
                                    <img src="/assets/images/<?= htmlspecialchars($evenement['image_evenement']) ?>" alt="">
                                </div>
                            <?php else: ?>
                                <div class="event-card__media media media--4x3 media--placeholder">
                                    <span>IMG</span>
                                </div>
                            <?php endif; ?>
                            <div class="event-card__body">
                                <h2 class="event-card__title">
                                    <?= htmlspecialchars($evenement['nom_evenement']) ?>
                                </h2>
                                <div class="meta-list">
                                    <time class="meta-item" datetime="<?= $dateDebut->format('Y-m-d') ?>">
                                        <img class="icon" src="/assets/images/icons/calendar-days.svg" width="20" height="20"
                                            alt="" aria-hidden="true">
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
                                <?php if ($estEnCours): ?>
                                    <span class="badge badge--success">En cours</span>
                                <?php elseif ($estComplet): ?>
                                    <span class="badge badge--warning">Complet</span>
                                <?php endif; ?>
                            </div>
                            <div class="event-card__action">
                                <a class="btn btn--primary" href="/evenement.php?id=<?= (int) $evenement['id_evenement'] ?>">
                                    Voir l'évènement
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="event-list-action">
                    <a class="btn btn--ghost" href="/evenements.php">
                        Voir tous les évènements
                    </a>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>