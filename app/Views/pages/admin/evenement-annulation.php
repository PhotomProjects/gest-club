<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Annuler l'évènement</h1>
            <p class="admin-header__description">Vérifiez les conséquences avant de confirmer l'annulation.</p>
        </div>
        <div class="admin-header__actions">
            <a class="btn btn--ghost" href="/admin/evenement.php">Retour à l'évènement</a>
        </div>
    </div>
    <div class="admin-centered">
        <form action="#" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <section class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">RAW is WAR: 1000th Ep.</h2>
                </div>
                <div class="panel__body">
                    <dl class="kv">
                        <div class="kv__row">
                            <dt class="kv__key">Date et heure</dt>
                            <dd class="kv__value">
                                <time datetime="2026-07-26T20:00">26/07/2026 · 20:00</time>
                            </dd>
                        </div>
                        <div class="kv__row">
                            <dt class="kv__key">Lieu</dt>
                            <dd class="kv__value">Lucha Pit Arena, Paris</dd>
                        </div>
                    </dl>
                    <div class="notice notice--danger cancellation-warning">
                        <p>
                            Les réservations confirmées associées seront annulées et leurs billets passeront au statut «
                            Annulé ». Cette action est irréversible.
                        </p>
                    </div>
                </div>
                <div class="panel__foot">
                    <div class="admin-form-actions">
                        <a class="btn btn--ghost" href="/admin/evenement.php">Retour</a>
                        <button class="btn btn--danger" type="submit">Confirmer l'annulation</button>
                    </div>
                </div>
            </section>
        </form>
    </div>
</main>