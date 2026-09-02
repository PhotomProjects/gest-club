<?php
$filtres = [
    'a-venir' => [
        'libelle' => 'À venir',
        'titre_vide' => 'Aucune réservation à venir',
        'texte_vide' => 'Vos prochaines réservations apparaîtront ici.',
    ],
    'passees' => [
        'libelle' => 'Passées',
        'titre_vide' => 'Aucune réservation passée',
        'texte_vide' => 'Vos anciennes réservations apparaîtront ici.',
    ],
    'annulees' => [
        'libelle' => 'Annulées',
        'titre_vide' => 'Aucune réservation annulée',
        'texte_vide' => 'Vos réservations annulées apparaîtront ici.',
    ],
];

$libellesTribunes = ['NORD' => 'Nord', 'SUD' => 'Sud', 'EST' => 'Est', 'OUEST' => 'Ouest',];
$libellesNiveaux = ['BAS' => 'bas', 'MILIEU' => 'milieu', 'HAUT' => 'haut',];
$libelleStatut = match ($filtre) {
    'a-venir' => 'Confirmée',
    'passees' => 'Passée',
    'annulees' => 'Annulée',
};
$classeStatut = match ($filtre) {
    'a-venir' => 'badge--success',
    'passees' => 'badge--muted',
    'annulees' => 'badge--danger',
};
?>
<main class="page">
    <div class="container">
        <div class="page-header">
            <h1>Mes réservations</h1>
        </div>
        <?php if ($messageReservation !== null): ?>
            <div class="notice reservation-message <?= $messageReservation['type'] === 'danger'
                ? 'notice--danger' : '' ?>" role="<?= $messageReservation['type'] === 'danger'
                    ? 'alert'
                    : 'status' ?>">
                <p>
                    <?= htmlspecialchars($messageReservation['texte']) ?>
                </p>
            </div>
        <?php endif; ?>
        <nav class="tabs" aria-label="Filtres des réservations">
            <?php foreach ($filtres as $codeFiltre => $configuration): ?>
                <a class="tab <?= $filtre === $codeFiltre ? 'is-active' : '' ?>"
                    href="/mes-reservations.php?filtre=<?= $codeFiltre ?>" <?= $filtre === $codeFiltre ? 'aria-current="page"' : '' ?>>
                    <?= htmlspecialchars($configuration['libelle']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <?php if ($reservations === []): ?>
            <div class="empty-state">
                <p class="empty-state__title">
                    <?= htmlspecialchars($filtres[$filtre]['titre_vide']) ?>
                </p>
                <p>
                    <?= htmlspecialchars($filtres[$filtre]['texte_vide']) ?>
                </p>
                <a class="btn btn--primary" href="/evenements.php">Voir les évènements</a>
            </div>
        <?php else: ?>
            <div class="reservation-list">
                <?php foreach ($reservations as $reservation): ?>
                    <?php
                    $dateReservation = new DateTimeImmutable($reservation['date_reservation']);
                    $dateDebut = new DateTimeImmutable($reservation['date_heure_debut_evenement']);
                    $dateFin = new DateTimeImmutable($reservation['date_heure_fin_evenement']);
                    $referenceReservation = sprintf(
                        'R-%s-%06d',
                        $dateReservation->format('Y'),
                        (int) $reservation['id_reservation']
                    );
                    $nbPlaces = (int) $reservation['nb_places'];
                    $prixTotal = (float) $reservation['prix_total'];
                    ?>

                    <article class="reservation">
                        <?php if ($reservation['image_evenement'] !== null): ?>
                            <div class="reservation__media media media--4x3">
                                <img src="/assets/images/<?= htmlspecialchars($reservation['image_evenement']) ?>" alt="">
                            </div>
                        <?php else: ?>
                            <div class="reservation__media media media--4x3 media--placeholder">
                                <span>IMG</span>
                            </div>
                        <?php endif; ?>
                        <div class="reservation__body">
                            <h2 class="reservation__title">
                                <?= htmlspecialchars($reservation['nom_evenement']) ?>
                            </h2>
                            <p class="reservation__meta">
                                <time datetime="<?= $dateDebut->format('Y-m-d\TH:i') ?>">
                                    <?= $dateDebut->format('d/m/Y à H:i') ?>
                                </time>
                                -
                                <time datetime="<?= $dateFin->format('Y-m-d\TH:i') ?>">
                                    <?= $dateFin->format('H:i') ?>
                                </time>
                            </p>
                            <p class="reservation__meta">
                                Réservation
                                <?= htmlspecialchars($referenceReservation) ?>
                            </p>
                            <p class="reservation__seat">
                                <span>
                                    <?= $nbPlaces ?>
                                    place<?= $nbPlaces > 1 ? 's' : '' ?>
                                    —
                                    Tribune
                                    <?= htmlspecialchars($libellesTribunes[$reservation['tribune_place']]) ?>,
                                    niveau
                                    <?= htmlspecialchars($libellesNiveaux[$reservation['niveau_place']]) ?>
                                </span>
                                <span>
                                    Places attribuées :
                                    <?= htmlspecialchars($reservation['places_attribuees']) ?>
                                </span>
                            </p>
                        </div>
                        <div class="reservation__side">
                            <span class="badge <?= $classeStatut ?>">
                                <?= $libelleStatut ?>
                            </span>
                            <p class="reservation__price">
                                Prix total :
                                <strong>
                                    <?= number_format($prixTotal, 0, ',', ' ') ?> €
                                </strong>
                            </p>
                            <a class="btn btn--primary" href="/billet.php?id=<?= $reservation['id_billet'] ?>">
                                Voir le billet
                            </a>
                            <?php if ($filtre === 'a-venir'): ?>
                                <form class="reservation-cancel-form" action="/mes-reservations.php" method="post">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                    <input type="hidden" name="action" value="annuler">
                                    <input type="hidden" name="id_reservation" value="<?= $reservation['id_reservation'] ?>">
                                    <button class="btn btn--ghost" type="submit">Annuler la réservation</button>
                                </form>
                            <?php endif; ?>
                            <a class="btn btn--ghost" href="/evenement.php?id=<?= $reservation['id_evenement'] ?>">
                                Voir l'évènement
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>