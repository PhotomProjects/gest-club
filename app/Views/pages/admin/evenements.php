<main id="main-content" class="admin-content" tabindex="-1">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Évènements</h1>
        </div>
        <div class="admin-header__actions">
            <a class="btn btn--primary" href="/admin/evenement-form.php">
                <span class="btn__plus" aria-hidden="true">+</span>
                Créer un évènement
            </a>
        </div>
    </div>
    <?php if (empty($evenements)): ?>
        <div class="empty-state">
            <p class="empty-state__title">Aucun évènement enregistré</p>
            <p>Créez un premier évènement pour ouvrir les réservations.</p>
            <a class="btn btn--primary" href="/admin/evenement-form.php">Créer un évènement</a>
        </div>
    <?php else: ?>

        <section class="panel">
            <div class="panel__body panel__body--flush">
                <div class="table-wrap">
                    <table class="table">
                        <caption class="visually-hidden">Liste des évènements</caption>
                        <thead>
                            <tr>
                                <th scope="col">Évènement</th>
                                <th scope="col">Date</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Places</th>
                                <th scope="col" class="is-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($evenements as $evenement): ?>
                                <?php
                                $dateDebut = new DateTimeImmutable($evenement['date_heure_debut_evenement']);
                                $dateFin = new DateTimeImmutable($evenement['date_heure_fin_evenement']);
                                $maintenant = new DateTimeImmutable();

                                if ($evenement['statut_evenement'] === 'ANNULE') {
                                    $statut = [
                                        'label' => 'Annulé',
                                        'class' => 'badge--danger',
                                    ];
                                } elseif ($dateFin <= $maintenant) {
                                    $statut = [
                                        'label' => 'Terminé',
                                        'class' => 'badge--muted',
                                    ];
                                } elseif ((int) $evenement['places_disponibles'] === 0) {
                                    $statut = [
                                        'label' => 'Complet',
                                        'class' => 'badge--warning',
                                    ];
                                } else {
                                    $statut = [
                                        'label' => 'Ouvert',
                                        'class' => 'badge--success',
                                    ];
                                }
                                ?>
                                <tr>
                                    <td>
                                        <span class="cell-strong">
                                            <?= htmlspecialchars($evenement['nom_evenement'], ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <time datetime="<?= $dateDebut->format('Y-m-d\TH:i') ?>" class="cell-num">
                                            <?= $dateDebut->format('d/m/Y · H:i') ?>
                                        </time>
                                    </td>
                                    <td>
                                        <span class="badge <?= $statut['class'] ?>">
                                            <?= htmlspecialchars($statut['label'], ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td class="cell-num">
                                        <?php if (
                                            $evenement['statut_evenement'] === 'ANNULE' || $dateFin <= $maintenant
                                        ): ?>
                                            —
                                        <?php else: ?>
                                            <?= (int) $evenement['places_disponibles'] ?>
                                            /
                                            <?= (int) $evenement['places_total'] ?>
                                            disponibles
                                        <?php endif; ?>
                                    </td>
                                    <td class="is-actions">
                                        <a class="btn btn--ghost"
                                            href="/admin/evenement.php?id=<?= (int) $evenement['id_evenement'] ?>">
                                            Gérer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    <?php endif; ?>
</main>