<?php
$dateReservation = new DateTimeImmutable($reservation['date_reservation']);
$dateDebut = new DateTimeImmutable($reservation['date_heure_debut_evenement']);
$dateFin = new DateTimeImmutable($reservation['date_heure_fin_evenement']);
$nbPlaces = (int) $reservation['nb_places'];
$prixTotal = (float) $reservation['prix_total'];

$referenceReservation = sprintf(
    'R-%s-%06d',
    $dateReservation->format('Y'),
    (int) $reservation['id_reservation']
);
?>
<main id="main-content" class="page" tabindex="-1">
    <div class="container">
        <section class="card confirmation">
            <h1>Votre réservation est confirmée.</h1>
            <div class="confirmation__event">
                <p class="ticket-ref">Réservation <?= htmlspecialchars($referenceReservation) ?></p>
                <p><?= htmlspecialchars($reservation['nom_evenement']) ?></p>
                <p class="confirmation__meta">
                    <time datetime="<?= $dateDebut->format('Y-m-d\TH:i') ?>">
                        <?= $dateDebut->format('d/m/Y') ?>
                        à
                        <?= $dateDebut->format('H\hi') ?>
                    </time>
                    -
                    <time datetime="<?= $dateFin->format('Y-m-d\TH:i') ?>">
                        <?= $dateFin->format('H\hi') ?>
                    </time>
                </p>
            </div>
            <div class="confirmation__summary">
                <p>
                    <?= $nbPlaces ?>
                    place<?= $nbPlaces > 1 ? 's' : '' ?>
                    réservée<?= $nbPlaces > 1 ? 's' : '' ?>
                </p>
                <p class="confirmation__price">
                    Prix total :
                    <?= number_format($prixTotal, 0, ',', ' ') ?> €
                </p>
            </div>
            <p class="confirmation__message">Présentez votre QR code à l'entrée pour valider votre présence.</p>
            <p class="confirmation__note">
                Un seul billet et un seul QR code sont générés pour l'ensemble de la réservation.<br>
                Après validation à l'entrée, le billet passe au statut « Utilisé ».<br>
                En cas d'empêchement, annulez la réservation avant le début de l'évènement afin de libérer les places.
            </p>
            <div class="confirmation__actions">
                <a class="btn btn--primary btn--lg" href="/billet.php?id=<?= $reservation['id_billet'] ?>">
                    Voir mon billet
                </a>
                <a class="btn btn--ghost btn--lg" href="/mes-reservations.php">Mes réservations</a>
            </div>
        </section>
    </div>
</main>