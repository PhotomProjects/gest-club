<?php

$billetsControles = (int) ($statistiquesPresence['billets_controles'] ?? 0);
$placesAdmises = (int) ($statistiquesPresence['places_admises'] ?? 0);

$libellesTribunes = ['NORD' => 'Nord', 'SUD' => 'Sud', 'EST' => 'Est', 'OUEST' => 'Ouest',];
$libellesNiveaux = ['BAS' => 'bas', 'MILIEU' => 'milieu', 'HAUT' => 'haut',];
?>
<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Contrôle des billets</h1>
        </div>
        <div class="admin-header__actions">
            <a class="btn btn--primary" href="/admin/presences-scan.php">Contrôler un billet</a>
        </div>
    </div>
    <?php if ($presences === []): ?>
        <div class="empty-state">
            <p class="empty-state__title">Aucune présence enregistrée</p>
            <p>Les entrées validées apparaîtront ici après le premier contrôle.</p>
            <a class="btn btn--primary" href="/admin/presences-scan.php">Contrôler un billet</a>
        </div>
    <?php else: ?>
        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Dernières présences enregistrées</h2>
                <p class="panel__meta">
                    <?= $billetsControles ?>
                    billet<?= $billetsControles > 1 ? 's' : '' ?>
                    contrôlé<?= $billetsControles > 1 ? 's' : '' ?>
                    ·
                    <?= $placesAdmises ?>
                    place<?= $placesAdmises > 1 ? 's' : '' ?>
                    admise<?= $placesAdmises > 1 ? 's' : '' ?>
                </p>
            </div>
            <div class="panel__body panel__body--flush">
                <div class="table-wrap">
                    <table class="table">
                        <caption class="visually-hidden">Dernières présences enregistrées</caption>
                        <thead>
                            <tr>
                                <th scope="col">N° billet</th>
                                <th scope="col">Utilisateur</th>
                                <th scope="col">Évènement</th>
                                <th scope="col">Places</th>
                                <th scope="col">Validé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($presences as $presence): ?>
                                <?php
                                $dateEvenement = new DateTimeImmutable($presence['date_heure_debut_evenement']);
                                $dateControle = new DateTimeImmutable($presence['date_heure_controle']);
                                $nbPlaces = (int) $presence['nb_places'];
                                $referenceBillet = sprintf(
                                    'B-%06d',
                                    (int) $presence['id_billet']
                                );
                                $tribune = $libellesTribunes[$presence['tribune_place']] ?? $presence['tribune_place'];
                                $niveau = $libellesNiveaux[$presence['niveau_place']] ?? $presence['niveau_place'];
                                ?>
                                <tr>
                                    <td class="cell-num">
                                        <?= htmlspecialchars($referenceBillet) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($presence['prenom'] . ' ' . $presence['nom']) ?>
                                    </td>
                                    <td>
                                        <span class="cell-strong">
                                            <?= htmlspecialchars($presence['nom_evenement']) ?>
                                        </span>
                                        <time class="cell-sub" datetime="<?=
                                            $dateEvenement->format('Y-m-d\TH:i') ?>">
                                            <?= $dateEvenement->format('d/m/Y · H:i') ?>
                                        </time>
                                    </td>
                                    <td>
                                        <?= $nbPlaces ?>
                                        place<?= $nbPlaces > 1 ? 's' : '' ?>
                                        <span class="cell-sub">
                                            <?= htmlspecialchars($tribune) ?>
                                            · Niveau
                                            <?= htmlspecialchars($niveau) ?>
                                            ·
                                            <?= htmlspecialchars($presence['places_attribuees']) ?>
                                        </span>
                                    </td>
                                    <td class="cell-num">
                                        <time datetime="<?= $dateControle->format('Y-m-d\TH:i') ?>">
                                            <?= $dateControle->format('d/m/Y H:i') ?>
                                        </time>
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