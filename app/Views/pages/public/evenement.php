<?php
$dateDebut = new DateTime($evenement['date_heure_debut_evenement']);
$dateFin = new DateTime($evenement['date_heure_fin_evenement']);
$estAnnule = $evenement['statut_evenement'] === 'ANNULE';
$estComplet = (int) $evenement['places_disponibles'] === 0;
$estTermine = $dateFin < new DateTime();
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
                <?php elseif ($estComplet): ?>
                    <p class="cta-bar__title">Évènement complet</p>
                <?php else: ?>
                    <p class="cta-bar__title">Réservations ouvertes</p>
                <?php endif; ?>
            </div>
            <?php if (!$estAnnule && !$estTermine && !$estComplet): ?>
                <a class="btn btn--primary btn--lg" href="/reservation.php?id=<?= $evenement['id_evenement'] ?>">
                    Réserver
                </a>
            <?php endif; ?>
        </div>
    </div>
</main>