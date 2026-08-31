<main class="page">
    <div class="container">
        <div class="page-header">
            <h1>Prochains évènements</h1>
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
                        $dateDebut = new DateTime($evenement['date_heure_debut_evenement']);
                        $estComplet = (int) $evenement['places_disponibles'] === 0;
                        ?>
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
                                <?php if ($evenement['statut_evenement'] === 'ANNULE'): ?>
                                    <span class="badge badge--danger">Annulé</span>
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