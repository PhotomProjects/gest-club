<?php

$dateDebut = new DateTimeImmutable($evenement['date_heure_debut_evenement']);
$dateFin = new DateTimeImmutable($evenement['date_heure_fin_evenement']);
$maintenant = new DateTimeImmutable();
$estTermine = $dateFin <= $maintenant;
$estComplet = (int) $evenement['places_disponibles'] === 0;
$programmeModifiable = $evenement['statut_evenement'] !== 'ANNULE' && !$estTermine;

if ($evenement['statut_evenement'] === 'ANNULE') {
    $statut = [
        'label' => 'Annulé',
        'class' => 'badge--danger',
    ];
} elseif ($estTermine) {
    $statut = [
        'label' => 'Terminé',
        'class' => 'badge--muted',
    ];
} elseif ($estComplet) {
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
<main id="main-content" class="admin-content" tabindex="-1">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Gérer l'évènement</h1>
        </div>
        <div class="admin-header__actions">
            <a class="btn btn--ghost" href="/admin/evenements.php">Retour aux évènements</a>
        </div>
    </div>
    <div class="record-head">
        <div class="record-head__id">
            <h2 class="record-head__title">
                <?= htmlspecialchars($evenement['nom_evenement'], ENT_QUOTES, 'UTF-8') ?>
            </h2>
            <div class="record-meta">
                <span class="badge <?= $statut['class'] ?>">
                    <?= htmlspecialchars($statut['label'], ENT_QUOTES, 'UTF-8') ?>
                </span>
            </div>
        </div>
        <div class="admin-header__actions">
            <?php if ($programmeModifiable): ?>
                <a class="btn btn--ghost"
                    href="/admin/evenement-form-modification.php?id=<?= (int) $evenement['id_evenement'] ?>">
                    Modifier
                </a>
            <?php endif; ?>
            <?php if (
                $evenement['statut_evenement'] !== 'ANNULE' && !$estTermine
            ): ?>
                <a class="btn btn--danger"
                    href="/admin/evenement-annulation.php?id=<?= (int) $evenement['id_evenement'] ?>">
                    Annuler l'évènement
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="event-details-layout">

        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Résumé de l'évènement</h2>
            </div>
            <div class="panel__body">
                <dl class="kv">
                    <div class="kv__row">
                        <dt class="kv__key">Début</dt>
                        <dd class="kv__value">
                            <time datetime="<?= $dateDebut->format('Y-m-d\TH:i') ?>">
                                <?= $dateDebut->format('d/m/Y · H:i') ?>
                            </time>
                        </dd>
                    </div>
                    <div class="kv__row">
                        <dt class="kv__key">Fin</dt>
                        <dd class="kv__value">
                            <time datetime="<?= $dateFin->format('Y-m-d\TH:i') ?>">
                                <?= $dateFin->format('d/m/Y · H:i') ?>
                            </time>
                        </dd>
                    </div>
                </dl>
                <p class="event-description__label">Description</p>
                <p class="event-description__text">
                    <?= (htmlspecialchars($evenement['description_evenement'], ENT_QUOTES, 'UTF-8')) ?>
                </p>
            </div>
        </section>

        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Programme</h2>
                <span class="panel__meta">
                    <?= count($matchs) ?>
                    <?= count($matchs) > 1 ? 'matchs' : 'match' ?>
                </span>
            </div>
            <div class="panel__body">
                <?php if (empty($matchs)): ?>
                    <p>Aucun match programmé pour cet évènement.</p>
                <?php else: ?>
                    <ol class="program">
                        <?php foreach ($matchs as $index => $match): ?>
                            <li class="program__item">
                                <span class="program__index" aria-hidden="true">
                                    <?= $index + 1 ?>
                                </span>
                                <div class="program__body">
                                    <p class="program__name">
                                        <?= htmlspecialchars($match['nom_match'], ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                    <p class="program__type">
                                        <?= htmlspecialchars($match['libelle_type_match'], ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                </div>
                                <?php if ($programmeModifiable): ?>
                                    <a class="btn btn--ghost btn--sm"
                                        href="/admin/evenement-match-modification.php?id=<?= (int) $match['id_match'] ?>">
                                        Modifier
                                    </a>
                                    <a class="btn btn--ghost btn--sm"
                                        href="/admin/evenement-match-suppression.php?id=<?= (int) $match['id_match'] ?>">
                                        Supprimer
                                    </a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>
                <?php if ($programmeModifiable): ?>
                    <a class="btn btn--primary btn--sm"
                        href="/admin/evenement-match.php?id_evenement=<?= (int) $evenement['id_evenement'] ?>">
                        <span aria-hidden="true" class="btn__plus">
                            +
                        </span>
                        Ajouter un match
                    </a>
                <?php endif; ?>
            </div>
        </section>

    </div>

    <section class="panel">
        <div class="panel__head">
            <h2 class="panel__title">Disponibilités</h2>
        </div>
        <div class="panel__body">
            <div class="stat-row">
                <span class="stat-row__label">Places réservées</span>
                <span class="stat-row__value">
                    <?= (int) $evenement['places_reservees'] ?>
                    /
                    <?= (int) $evenement['places_total'] ?>
                </span>
            </div>
        </div>
    </section>
</main>