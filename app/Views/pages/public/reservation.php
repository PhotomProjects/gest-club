<?php
$dateDebut = new DateTimeImmutable($evenement['date_heure_debut_evenement']);
$dateFin = new DateTimeImmutable($evenement['date_heure_fin_evenement']);

$selectionReservation = $selectionReservation ?? [];
$nbPlacesSelectionne = (int) ($selectionReservation['nb_places'] ?? 1);
$tribuneSelectionnee = $selectionReservation['tribune'] ?? 'NORD';
$niveauSelectionne = $selectionReservation['niveau'] ?? 'BAS';
$erreurReservation = $erreurReservation ?? null;
?>
<main id="main-content" class="page" tabindex="-1">
    <div class="container">
        <div class="page-header">
            <h1>Réservation</h1>
        </div>
        <div class="reservation-layout">
            <article class="card event-summary">
                <h2 class="card__title">Récapitulatif de l'évènement</h2>
                <?php if ($evenement['image_evenement'] !== null): ?>
                    <div class="media media--16x9">
                        <img src="/assets/images/<?= htmlspecialchars($evenement['image_evenement']) ?>" alt="">
                    </div>
                <?php else: ?>
                    <div class="media media--16x9 media--placeholder">
                        <span>IMG</span>
                    </div>
                <?php endif; ?>
                <h3 class="event-summary__title">
                    <?= htmlspecialchars($evenement['nom_evenement']) ?>
                </h3>
                <div class="meta-list">
                    <time class="meta-item" datetime="<?= $dateDebut->format('Y-m-d') ?>">
                        <img class="icon" src="/assets/images/icons/calendar-days.svg" width="20" height="20" alt=""
                            aria-hidden="true">
                        <?= $dateDebut->format('d/m/Y') ?>
                    </time>
                    <span class="meta-item">
                        <img class="icon" src="/assets/images/icons/clock.svg" width="20" height="20" alt=""
                            aria-hidden="true">
                        <time datetime="<?= $dateDebut->format('Y-m-d\TH:i') ?>">
                            <?= $dateDebut->format('H:i') ?>
                        </time>
                        <span aria-hidden="true">-</span>
                        <time datetime="<?= $dateFin->format('Y-m-d\TH:i') ?>">
                            <?= $dateFin->format('H:i') ?>
                        </time>
                    </span>
                    <span class="meta-item">
                        <img class="icon" src="/assets/images/icons/map-pin.svg" width="20" height="20" alt=""
                            aria-hidden="true">
                        <?= htmlspecialchars($eventLocation) ?>
                    </span>
                </div>
                <p class="event-summary__description">
                    <?= htmlspecialchars($evenement['description_evenement']) ?>
                </p>
            </article>
            <div class="reservation-side">
                <section class="card">
                    <h2 class="card__title">Choisissez vos options de réservation</h2>
                    <form class="reservation-form" action="/reservation-recapitulatif.php" method="post"
                        data-availability="<?= htmlspecialchars(json_encode($disponibilitesParZone, JSON_THROW_ON_ERROR), ENT_QUOTES, 'UTF-8') ?>"
                        novalidate>
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <input type="hidden" name="id_evenement" value="<?= $evenement['id_evenement'] ?>">
                        <?php if ($erreurReservation !== null): ?>
                            <div class="notice notice--danger" role="alert">
                                <p>
                                    <?= htmlspecialchars($erreurReservation) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        <fieldset class="options">
                            <legend class="options__legend">Nombre de places</legend>
                            <div class="options__list">
                                <label class="option">
                                    <input type="radio" name="nb_places" value="1" <?= $nbPlacesSelectionne === 1 ? 'checked' : '' ?>>
                                    <span class="option__box">
                                        <span class="option__label">1</span>
                                    </span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="nb_places" value="2" <?= $nbPlacesSelectionne === 2 ? 'checked' : '' ?>>
                                    <span class="option__box">
                                        <span class="option__label">2</span>
                                    </span>
                                </label>
                            </div>
                        </fieldset>
                        <fieldset class="options">
                            <legend class="options__legend">Tribune</legend>
                            <div class="options__list">
                                <label class="option">
                                    <input type="radio" name="tribune" value="NORD" <?= $tribuneSelectionnee === 'NORD' ? 'checked' : '' ?>>
                                    <span class="option__box">
                                        <span class="option__label">Nord</span>
                                        <span class="option__status" data-option-status hidden></span>
                                    </span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="tribune" value="EST" <?= $tribuneSelectionnee === 'EST' ? 'checked' : '' ?>>
                                    <span class="option__box">
                                        <span class="option__label">Est</span>
                                        <span class="option__status" data-option-status hidden></span>
                                    </span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="tribune" value="SUD" <?= $tribuneSelectionnee === 'SUD' ? 'checked' : '' ?>>
                                    <span class="option__box">
                                        <span class="option__label">Sud</span>
                                        <span class="option__status" data-option-status hidden></span>
                                    </span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="tribune" value="OUEST" <?= $tribuneSelectionnee === 'OUEST' ? 'checked' : '' ?>>
                                    <span class="option__box">
                                        <span class="option__label">Ouest</span>
                                        <span class="option__status" data-option-status hidden></span>
                                    </span>
                                </label>
                            </div>
                        </fieldset>
                        <fieldset class="options">
                            <legend class="options__legend">Niveau</legend>
                            <div class="options__list">
                                <label class="option">
                                    <input type="radio" name="niveau" value="BAS"
                                        data-price="<?= $prixParNiveau['BAS'] ?>" <?= $niveauSelectionne === 'BAS' ? 'checked' : '' ?>>
                                    <span class="option__box">
                                        <span class="option__label">Bas</span>
                                        <span class="option__meta">
                                            <?= number_format($prixParNiveau['BAS'], 0, ',', ' ') ?> € / place
                                        </span>
                                        <span class="option__status" data-option-status hidden></span>
                                    </span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="niveau" value="MILIEU"
                                        data-price="<?= $prixParNiveau['MILIEU'] ?>" <?= $niveauSelectionne === 'MILIEU' ? 'checked' : '' ?>>
                                    <span class="option__box">
                                        <span class="option__label">Milieu</span>
                                        <span class="option__meta">
                                            <?= number_format($prixParNiveau['MILIEU'], 0, ',', ' ') ?> € / place
                                        </span>
                                        <span class="option__status" data-option-status hidden></span>
                                    </span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="niveau" value="HAUT"
                                        data-price="<?= $prixParNiveau['HAUT'] ?>" <?= $niveauSelectionne === 'HAUT' ? 'checked' : '' ?>>
                                    <span class="option__box">
                                        <span class="option__label">Haut</span>
                                        <span class="option__meta">
                                            <?= number_format($prixParNiveau['HAUT'], 0, ',', ' ') ?> € / place
                                        </span>
                                        <span class="option__status" data-option-status hidden></span>
                                    </span>
                                </label>
                            </div>
                        </fieldset>
                        <div class="notice">
                            <p>Les places exactes seront attribuées après confirmation de la réservation.</p>
                        </div>
                        <p id="reservation-status" class="visually-hidden" role="status" aria-atomic="true"></p>
                        <div class="reservation-total">
                            <span>Total</span>
                            <strong id="reservation-total">— €</strong>
                        </div>
                        <div class="reservation-actions">
                            <button class="btn btn--primary btn--lg" type="submit">Continuer</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</main>