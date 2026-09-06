<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Réservations</h1>
            <p class="admin-header__description">Consultez l'ensemble des réservations enregistrées.</p>
        </div>
    </div>
    <?php if ($reservations === []): ?>
        <div class="empty-state">
            <p class="empty-state__title">Aucune réservation</p>
            <p>Aucune réservation n'est enregistrée.</p>
        </div>
    <?php else: ?>
        <section class="panel">
            <div class="panel__body panel__body--flush">
                <div class="table-wrap">
                    <table class="table">
                        <caption class="visually-hidden">Liste des réservations</caption>
                        <thead>
                            <tr>
                                <th scope="col">N°</th>
                                <th scope="col">Utilisateur</th>
                                <th scope="col">Évènement</th>
                                <th scope="col">Places</th>
                                <th scope="col">Total</th>
                                <th scope="col">Statut</th>
                                <th scope="col" class="is-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                                <?php
                                $dateReservation = new DateTimeImmutable($reservation['date_reservation']);
                                $dateEvenement = new DateTimeImmutable($reservation['date_heure_debut_evenement']);
                                $referenceReservation = sprintf(
                                    'R-%s-%06d',
                                    $dateReservation->format('Y'),
                                    (int) $reservation['id_reservation']
                                );
                                $nbPlaces = (int) $reservation['nb_places'];
                                $prixTotal = (float) $reservation['prix_total'];
                                ?>
                                <tr>
                                    <td class="cell-num">
                                        <?= htmlspecialchars($referenceReservation, ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                    <td>
                                        <a class="link"
                                            href="/admin/utilisateur.php?id=<?= (int) $reservation['id_utilisateur'] ?>">
                                            <?= htmlspecialchars($reservation['prenom'] . ' ' . $reservation['nom'], ENT_QUOTES, 'UTF-8') ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($reservation['nom_evenement'], ENT_QUOTES, 'UTF-8') ?>
                                        <time datetime="<?= $dateEvenement->format('Y-m-d\TH:i') ?>">
                                            <?= $dateEvenement->format('d/m/Y à H:i') ?>
                                        </time>
                                    </td>
                                    <td>
                                        <?= $nbPlaces ?>
                                        place<?= $nbPlaces > 1 ? 's' : '' ?>
                                    </td>
                                    <td>
                                        <?= number_format($prixTotal, 2, ',', ' ') ?>
                                        €
                                    </td>
                                    <td>
                                        <?php if ($reservation['statut_reservation'] === 'CONFIRMEE'): ?>
                                            <span class="badge badge--success">Confirmée</span>
                                        <?php else: ?>
                                            <span class="badge badge--danger">Annulée</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="is-actions">
                                        <a class="btn btn--ghost"
                                            href="/admin/reservation.php?id=<?= (int) $reservation['id_reservation'] ?>">
                                            Voir
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