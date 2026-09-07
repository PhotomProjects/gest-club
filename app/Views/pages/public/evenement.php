<?php
$dateDebut = new DateTimeImmutable($evenement['date_heure_debut_evenement']);
$dateFin = new DateTimeImmutable($evenement['date_heure_fin_evenement']);
$maintenant = new DateTimeImmutable();
$estAnnule = $evenement['statut_evenement'] === 'ANNULE';
$estComplet = (int) $evenement['places_disponibles'] === 0;
$estEnCours = !$estAnnule && $dateDebut <= $maintenant && $dateFin > $maintenant;
$estTermine = !$estAnnule && $dateFin <= $maintenant;
$estReservable = !$estAnnule && !$estComplet && $dateDebut > $maintenant;
?>
<main class="page">
    <div class="container">
        <div class="event-detail">

            <article class="card">
                <?php if ($evenement['image_evenement'] !== null): ?>
                    <div class="media media--16x9">
                        <img src="/assets/images/<?= htmlspecialchars($evenement['image_evenement']) ?>" alt="">
                    </div>
                <?php else: ?>
                    <div class="media media--16x9 media--placeholder">
                        <span>IMG</span>
                    </div>
                <?php endif; ?>
                <h1 class="event-detail__title">
                    <?= htmlspecialchars($evenement['nom_evenement']) ?>
                </h1>
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
                <p class="event-detail__description">
                    <?= htmlspecialchars($evenement['description_evenement']) ?>
                </p>
            </article>

            <section class="card card--flat">
                <h2 class="card__title">Programme de la carte</h2>
                <?php if (empty($matchs)): ?>
                    <p>Aucun match programmé pour cet évènement.</p>
                <?php else: ?>
                    <ul class="match-list">
                        <?php foreach ($matchs as $match): ?>
                            <li class="match">
                                <p class="match__type">
                                    <?= htmlspecialchars($match['libelle_type_match']) ?>
                                </p>
                                <p class="match__cast">
                                    <?= htmlspecialchars($match['nom_match']) ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>

        </div>
        <div class="cta-bar">
            <div>
                <?php if ($estAnnule): ?>
                    <p class="cta-bar__title">Évènement annulé</p>
                <?php elseif ($estTermine): ?>
                    <p class="cta-bar__title">Évènement terminé</p>
                <?php elseif ($estEnCours): ?>
                    <p class="cta-bar__title">Évènement en cours</p>
                    <p>Les réservations sont désormais fermées.</p>
                <?php elseif ($estComplet): ?>
                    <p class="cta-bar__title">Évènement complet</p>
                <?php else: ?>
                    <p class="cta-bar__title">Réservations ouvertes</p>
                <?php endif; ?>
            </div>
            <?php if ($estReservable): ?>
                <a class="btn btn--primary btn--lg" href="/reservation.php?id=<?= (int) $evenement['id_evenement'] ?>">
                    Réserver
                </a>
            <?php endif; ?>
        </div>
    </div>
</main>