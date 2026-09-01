<main class="page">
    <div class="container">
        <div class="page-header">
            <h1>Réservation</h1>
        </div>
        <div class="reservation-layout">
            <article class="card event-summary">
                <h2 class="card__title">Récapitulatif de l'évènement</h2>
                <div class="media media--16x9 media--placeholder">
                    <span>IMG</span>
                </div>
                <h3 class="event-summary__title">RAW is WAR: 1000th Ep.</h3>
                <div class="meta-list">
                    <time datetime="2026-07-26">Dim. 26 juillet 2026</time>
                    <span>
                        <time datetime="2026-07-26T20:00">20:00</time>
                        -
                        <time datetime="2026-07-26T23:00">23:00</time>
                    </span>
                    <span>Lucha Pit Arena, Paris</span>
                </div>
                <p class="event-summary__description">
                    Dans cette édition spéciale de RAW, nous célébrons le 1000e épisode avec des invités
                    ayant marqué l'histoire de RAW à travers les années. De l'Attitude Era, en passant à la
                    Ruthless Era, nos légendes seront sans pitié.
                </p>
            </article>
            <div class="reservation-side">
                <section class="card">
                    <h2 class="card__title">Choisissez vos options de réservation</h2>
                    <form class="reservation-form" action="/reservation-recapitulatif.php" method="post" novalidate>
                        <fieldset class="options">
                            <legend class="options__legend">Nombre de places</legend>
                            <div class="options__list">
                                <label class="option">
                                    <input type="radio" name="nb_places" value="1" checked>
                                    <span class="option__box">
                                        <span class="option__label">1</span>
                                    </span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="nb_places" value="2">
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
                                    <input type="radio" name="tribune" value="nord" checked>
                                    <span class="option__box">
                                        <span class="option__label">Nord</span>
                                    </span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="tribune" value="est">
                                    <span class="option__box">
                                        <span class="option__label">Est</span>
                                    </span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="tribune" value="sud">
                                    <span class="option__box">
                                        <span class="option__label">Sud</span>
                                    </span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="tribune" value="ouest">
                                    <span class="option__box">
                                        <span class="option__label">Ouest</span>
                                    </span>
                                </label>
                            </div>
                        </fieldset>
                        <fieldset class="options">
                            <legend class="options__legend">Niveau</legend>
                            <div class="options__list">
                                <label class="option">
                                    <input type="radio" name="niveau" value="bas" data-price="45" checked>
                                    <span class="option__box">
                                        <span class="option__label">Bas</span>
                                        <span class="option__meta">45 € / place</span>
                                    </span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="niveau" value="milieu" data-price="35">
                                    <span class="option__box">
                                        <span class="option__label">Milieu</span>
                                        <span class="option__meta">35 € / place</span>
                                    </span>
                                </label>
                                <label class="option">
                                    <input type="radio" name="niveau" value="haut" data-price="25">
                                    <span class="option__box">
                                        <span class="option__label">Haut</span>
                                        <span class="option__meta">25 € / place</span>
                                    </span>
                                </label>
                            </div>
                        </fieldset>
                        <div class="notice">
                            <p>Les places exactes seront attribuées après confirmation de la réservation.</p>
                        </div>
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