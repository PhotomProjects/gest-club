<main class="admin-content">
    <h1 class="visually-hidden">Vue d'ensemble</h1>
    <div class="dashboard-layout">
        <div class="dashboard-panels">

            <!-- Prochains évènements -->
            <section class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">Prochains évènements</h2>
                </div>
                <?php if ($prochainsEvenements === []): ?>
                    <div class="panel__body">
                        <div class="empty-state">
                            <p class="empty-state__title">Aucun évènement à venir</p>
                            <p>Les prochains évènements apparaîtront ici dès qu'ils seront créés.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="panel__body panel__body--flush">
                        <div class="table-wrap">
                            <table class="table table--compact">
                                <caption class="visually-hidden">Prochains évènements</caption>
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
                                    <?php foreach ($prochainsEvenements as $evenement): ?>
                                        <?php
                                        $dateDebut = new DateTimeImmutable($evenement['date_heure_debut_evenement']);
                                        $estComplet = (int) $evenement['places_disponibles'] === 0; ?>
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
                                                <?php if ($estComplet): ?>
                                                    <span class="badge badge--warning">Complet</span>
                                                <?php else: ?>
                                                    <span class="badge badge--success">Ouvert</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="cell-num">
                                                <?= (int) $evenement['places_disponibles'] ?>
                                                /
                                                <?= (int) $evenement['places_total'] ?>
                                                disponibles
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
                    <div class="panel__foot">
                        <a class="btn btn--ghost" href="/admin/evenements.php">Voir tous les évènements</a>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Réservations récentes -->
            <section class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">Réservations récentes</h2>
                </div>
                <?php if ($reservationsRecentes === []): ?>
                    <div class="panel__body">
                        <div class="empty-state">
                            <p class="empty-state__title">Aucune réservation récente</p>
                            <p>Les nouvelles réservations apparaîtront ici.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="panel__body panel__body--flush">
                        <div class="table-wrap">
                            <table class="table table--compact">
                                <caption class="visually-hidden">Réservations récentes</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">N°</th>
                                        <th scope="col">Utilisateur</th>
                                        <th scope="col">Évènement</th>
                                        <th scope="col">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservationsRecentes as $reservation): ?>
                                        <?php
                                        $dateReservation = new DateTimeImmutable($reservation['date_reservation']);
                                        $referenceReservation = sprintf(
                                            'R-%s-%06d',
                                            $dateReservation->format('Y'),
                                            (int) $reservation['id_reservation']
                                        );
                                        ?>
                                        <tr>
                                            <td class="cell-num">
                                                <a class="link"
                                                    href="/admin/reservation.php?id=<?= (int) $reservation['id_reservation'] ?>">
                                                    <?= htmlspecialchars($referenceReservation, ENT_QUOTES, 'UTF-8') ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="link"
                                                    href="/admin/utilisateur.php?id=<?= (int) $reservation['id_utilisateur'] ?>">
                                                    <?= htmlspecialchars($reservation['prenom'] . ' ' . $reservation['nom'], ENT_QUOTES, 'UTF-8') ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="link"
                                                    href="/admin/evenement.php?id=<?= (int) $reservation['id_evenement'] ?>">
                                                    <?= htmlspecialchars($reservation['nom_evenement'], ENT_QUOTES, 'UTF-8') ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if ($reservation['statut_reservation'] === 'CONFIRMEE'): ?>
                                                    <span class="badge badge--success">Confirmée</span>
                                                <?php else: ?>
                                                    <span class="badge badge--danger">Annulée</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel__foot">
                        <a class="btn btn--ghost" href="/admin/reservations.php">Voir toutes les réservations</a>
                    </div>
                <?php endif; ?>
            </section>
        </div>

        <!-- Actions rapides -->
        <div class="quick-actions">
            <a class="btn btn--primary btn--lg" href="/admin/evenement-form.php">
                <span class="btn__plus" aria-hidden="true">+</span>
                Créer un évènement
            </a>
            <a class="btn btn--secondary btn--lg" href="/admin/presences-scan.php">Contrôler un billet</a>
        </div>
    </div>
</main>