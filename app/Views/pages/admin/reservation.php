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
<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>
                Réservation
                <?= htmlspecialchars($referenceReservation, ENT_QUOTES, 'UTF-8') ?>
            </h1>
            <div class="record-meta admin-header__meta">
                <?php if ($reservation['statut_reservation'] === 'CONFIRMEE'): ?>
                    <span class="badge badge--success">Confirmée</span>
                <?php else: ?>
                    <span class="badge badge--danger">Annulée</span>
                <?php endif; ?>
                <span>
                    Créée le
                    <time datetime="<?= $dateReservation->format('Y-m-d\TH:i') ?>">
                        <?= $dateReservation->format('d/m/Y à H:i') ?>
                    </time>
                </span>
            </div>
        </div>
        <div class="admin-header__actions">
            <a class="btn btn--ghost" href="/admin/reservations.php">Retour</a>
        </div>
    </div>
    <div class="reservation-details-layout">

        <!-- Informations de la réservation -->
        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Informations</h2>
            </div>
            <div class="panel__body">
                <dl class="kv">
                    <div class="kv__row">
                        <dt class="kv__key">Utilisateur</dt>
                        <dd class="kv__value">
                            <a class="link"
                                href="/admin/utilisateur.php?id=<?= (int) $reservation['id_utilisateur'] ?>">
                                <?= htmlspecialchars($reservation['prenom'] . ' ' . $reservation['nom'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </dd>
                    </div>
                    <div class="kv__row">
                        <dt class="kv__key">E-mail</dt>
                        <dd class="kv__value">
                            <?= htmlspecialchars($reservation['email'], ENT_QUOTES, 'UTF-8') ?>
                        </dd>
                    </div>
                    <div class="kv__row">
                        <dt class="kv__key">Évènement</dt>
                        <dd class="kv__value">
                            <a class="link" href="/admin/evenement.php?id=<?= (int) $reservation['id_evenement'] ?>">
                                <?= htmlspecialchars($reservation['nom_evenement'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </dd>
                    </div>
                    <div class="kv__row">
                        <dt class="kv__key">Date</dt>
                        <dd class="kv__value">
                            <time datetime="<?= $dateEvenement->format('Y-m-d\TH:i') ?>">
                                <?= $dateEvenement->format('d/m/Y · H:i') ?>
                            </time>
                        </dd>
                    </div>
                    <div class="kv__row">
                        <dt class="kv__key">Places</dt>
                        <dd class="kv__value">
                            <?= $nbPlaces ?>
                            place<?= $nbPlaces > 1 ? 's' : '' ?>
                        </dd>
                    </div>
                    <div class="kv__row">
                        <dt class="kv__key">Attribution</dt>
                        <dd class="kv__value">
                            <?php foreach ($places as $index => $place): ?>
                                <?php if ($index > 0): ?>
                                    <br>
                                <?php endif; ?>
                                Tribune
                                <?= htmlspecialchars(ucfirst(strtolower($place['tribune_place'])), ENT_QUOTES, 'UTF-8') ?>
                                · Niveau
                                <?= htmlspecialchars(ucfirst(strtolower($place['niveau_place'])), ENT_QUOTES, 'UTF-8') ?>
                                · Rangée
                                <?= htmlspecialchars($place['rangee_place'], ENT_QUOTES, 'UTF-8') ?>
                                · Place
                                <?= htmlspecialchars($place['numero_place'], ENT_QUOTES, 'UTF-8') ?>
                            <?php endforeach; ?>
                        </dd>
                    </div>
                    <div class="kv__row">
                        <dt class="kv__key">Prix total</dt>
                        <dd class="kv__value">
                            <?= number_format($prixTotal, 2, ',', ' ') ?>€
                        </dd>
                    </div>
                </dl>
            </div>
        </section>

        <div class="reservation-side">
            <!-- Billet -->
            <section class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">Billet associé</h2>
                </div>
                <div class="panel__body">
                    <?php if ($reservation['id_billet'] === null): ?>
                        <p>Aucun billet associé.</p>
                    <?php else: ?>
                        <dl class="kv">
                            <div class="kv__row">
                                <dt class="kv__key">N° Billet</dt>
                                <dd class="kv__value">
                                    B-<?= str_pad((string) $reservation['id_billet'], 6, '0', STR_PAD_LEFT) ?>
                                </dd>
                            </div>
                            <div class="kv__row">
                                <dt class="kv__key">Statut</dt>
                                <dd class="kv__value">
                                    <?php if ($reservation['statut_billet'] === 'ACTIF'): ?>
                                        <span class="badge badge--success">Actif</span>
                                    <?php elseif ($reservation['statut_billet'] === 'UTILISE'): ?>
                                        <span class="badge badge--muted">Utilisé</span>
                                    <?php else: ?>
                                        <span class="badge badge--danger">Annulé</span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                        </dl>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Présence -->
            <section class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">Contrôle d'entrée</h2>
                </div>
                <div class="panel__body">
                    <?php if ($reservation['id_presence'] === null): ?>
                        <p class="entry-status__empty">Aucune entrée enregistrée.</p>
                    <?php else: ?>
                        <?php
                        $dateControle = new DateTimeImmutable($reservation['date_heure_controle']);
                        ?>
                        <dl class="kv">
                            <div class="kv__row">
                                <dt class="kv__key">Statut</dt>
                                <dd class="kv__value">
                                    <span class="badge badge--success">Entrée enregistrée</span>
                                </dd>
                            </div>
                            <div class="kv__row">
                                <dt class="kv__key">Contrôlé le</dt>
                                <dd class="kv__value">
                                    <time datetime="<?= $dateControle->format('Y-m-d\TH:i') ?>">
                                        <?= $dateControle->format('d/m/Y à H:i') ?>
                                    </time>
                                </dd>
                            </div>
                        </dl>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</main>