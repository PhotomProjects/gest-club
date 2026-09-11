<?php
$dateCreation = new DateTimeImmutable($utilisateur['date_creation_compte']);
?>
<main id="main-content" class="admin-content" tabindex="-1">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>
                <?= htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom'], ENT_QUOTES, 'UTF-8') ?>
            </h1>
            <div class="record-meta admin-header__meta">
                <span>
                    Membre depuis le
                    <time datetime="<?= $dateCreation->format('Y-m-d') ?>">
                        <?= $dateCreation->format('d/m/Y') ?>
                    </time>
                </span>
            </div>
        </div>
        <div class="admin-header__actions">
            <a class="btn btn--ghost" href="/admin/utilisateurs.php">Retour</a>
            <a class="btn btn--secondary"
                href="/admin/utilisateur-form-modification.php?id=<?= $idUtilisateur ?>">Modifier le rôle</a>
        </div>
    </div>
    <div class="user-details-layout">

        <!-- Compte -->
        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Compte</h2>
            </div>
            <div class="panel__body">
                <dl class="kv">
                    <div class="kv__row">
                        <dt class="kv__key">Nom</dt>
                        <dd class="kv__value">
                            <?= htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom'], ENT_QUOTES, 'UTF-8') ?>
                        </dd>
                    </div>
                    <div class="kv__row">
                        <dt class="kv__key">E-mail</dt>
                        <dd class="kv__value">
                            <?= htmlspecialchars($utilisateur['email'], ENT_QUOTES, 'UTF-8') ?>
                        </dd>
                    </div>
                    <div class="kv__row">
                        <dt class="kv__key">Rôle</dt>
                        <dd class="kv__value">
                            <?php if ($utilisateur['role_utilisateur'] === 'ADMIN'): ?>
                                <span class="badge badge--success">Admin</span>
                            <?php else: ?>
                                <span class="badge badge--muted">Membre</span>
                            <?php endif; ?>
                        </dd>
                    </div>
                </dl>
            </div>
        </section>

        <!-- Réservations -->
        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Réservations associées</h2>
                <span class="panel__meta">
                    <?= count($reservations) ?>
                </span>
            </div>
            <?php if ($reservations === []): ?>
                <div class="panel__body">
                    <p>Cet utilisateur n'a aucune réservation.</p>
                </div>
            <?php else: ?>
                <div class="panel__body panel__body--flush">
                    <div class="table-wrap">
                        <table class="table table--compact">
                            <caption class="visually-hidden">Réservations associées à l'utilisateur</caption>
                            <thead>
                                <tr>
                                    <th scope="col">N°</th>
                                    <th scope="col">Évènement</th>
                                    <th scope="col">Places</th>
                                    <th scope="col">Statut</th>
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
                                    ?>
                                    <tr>
                                        <td class="cell-num">
                                            <a class="link"
                                                href="/admin/reservation.php?id=<?= (int) $reservation['id_reservation'] ?>">
                                                <?= htmlspecialchars($referenceReservation) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($reservation['nom_evenement'], ENT_QUOTES, 'UTF-8') ?>
                                            -
                                            <time datetime="<?= $dateEvenement->format('Y-m-d') ?>">
                                                <?= $dateEvenement->format('d/m/Y') ?>
                                            </time>
                                        </td>
                                        <td>
                                            <?= $nbPlaces ?>
                                            place<?= $nbPlaces > 1 ? 's' : '' ?>
                                        </td>
                                        <td>
                                            <?php if ($reservation['statut_reservation'] === 'CONFIRMEE'): ?>
                                                <span class="badge badge--success">
                                                    Confirmée
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge--danger">
                                                    Annulée
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>