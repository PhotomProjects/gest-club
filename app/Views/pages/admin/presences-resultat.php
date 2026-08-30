<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Résultat du contrôle</h1>
        </div>
    </div>

    <article class="panel result result--ok">
        <span class="result__mark" aria-hidden="true">OK</span>
        <div class="result__body">
            <h2 class="result__title">Entrée autorisée</h2>
            <p class="result__message">Le billet est valide. Vérifiez les informations, puis validez l'entrée.</p>
            <p class="result__status">
                <span class="badge badge--success">Actif</span>
            </p>
        </div>
    </article>

    <section class="panel">
        <div class="panel__body">
            <dl class="kv">
                <div class="kv__row">
                    <dt class="kv__key">Billet</dt>
                    <dd class="kv__value">BIL-001170</dd>
                </div>
                <div class="kv__row">
                    <dt class="kv__key">Utilisateur</dt>
                    <dd class="kv__value">Lore Manon</dd>
                </div>
                <div class="kv__row">
                    <dt class="kv__key">Évènement</dt>
                    <dd class="kv__value">RAW -
                        <time datetime="2026-10-01T20:00">01/10/2026 à 20:00</time>
                    </dd>
                </div>
                <div class="kv__row">
                    <dt class="kv__key">Places</dt>
                    <dd class="kv__value">2 places - Tribune Est - Niveau haut</dd>
                </div>
            </dl>
        </div>
        <div class="panel__foot">
            <a class="btn btn--ghost" href="/admin/presences-scan.php">Contrôler un autre billet</a>
            <button class="btn btn--primary" type="button">Valider l'entrée (2 pers.)</button>
        </div>
    </section>
</main>