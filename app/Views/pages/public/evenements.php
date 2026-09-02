<?php
$maintenant = new DateTimeImmutable();
?>
<main class="page">
    <div class="container">
        <div class="page-header">
            <h1>Évènements à venir et en cours</h1>
        </div>
        <section>
            <?php if (empty($evenements)): ?>
                <div class="empty-state">
                    <p class="empty-state__title">Aucun évènement programmé</p>
                    <p>Les prochains évènements apparaîtront ici dès qu'ils seront disponibles.</p>
                </div>
            <?php else: ?>
                <div class="event-grid">
                    <?php foreach ($evenements as $evenement): ?>
                        <?php
                        $dateDebut = new DateTimeImmutable($evenement['date_heure_debut_evenement']);
                        $dateFin = new DateTimeImmutable($evenement['date_heure_fin_evenement']);
                        $estAnnule = $evenement['statut_evenement'] === 'ANNULE';
                        $estComplet = (int) $evenement['places_disponibles'] === 0;
                        $estEnCours = !$estAnnule && $dateDebut <= $maintenant && $dateFin > $maintenant; ?>
                        <article class="event-row">
                            <div class="event-row__date">
                                <span class="event-row__day">
                                    <?= $dateDebut->format('d/m/Y') ?>
                                </span>
                                <time class="event-row__time" datetime="<?= $dateDebut->format('Y-m-d\TH:i') ?>">
                                    <?= $dateDebut->format('H\hi') ?>
                                </time>
                            </div>
                            <div class="event-row__body">
                                <h2 class="event-row__title">
                                    <?= htmlspecialchars($evenement['nom_evenement']) ?>
                                </h2>
                            </div>
                            <div class="event-row__action">
                                <?php if ($estAnnule): ?>
                                    <span class="badge badge--danger">Annulé</span>
                                <?php elseif ($estEnCours): ?>
                                    <span class="badge badge--success">En cours</span>
                                <?php elseif ($estComplet): ?>
                                    <span class="badge badge--warning">Complet</span>
                                <?php endif; ?>
                                <a class="btn btn--primary" href="/evenement.php?id=<?= $evenement['id_evenement'] ?>">
                                    Voir l'évènement
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>