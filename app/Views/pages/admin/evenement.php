<main class="admin-content">
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
            <h2 class="record-head__title">RAW is WAR: 1000th Ep.</h2>
            <div class="record-meta">
                <span class="badge badge--success">Ouvert</span>
            </div>
        </div>
        <div class="admin-header__actions">
            <a class="btn btn--ghost" href="/admin/evenement-form-modification.php">Modifier</a>
            <a class="btn btn--danger" href="/admin/evenement-annulation.php">Annuler l'évènement</a>
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
                            <time datetime="2026-07-26T20:00">26/07/2026 · 20:00</time>
                        </dd>
                    </div>
                    <div class="kv__row">
                        <dt class="kv__key">Fin</dt>
                        <dd class="kv__value">
                            <time datetime="2026-07-27T00:00">27/07/2026 · 00:00</time>
                        </dd>
                    </div>
                </dl>
                <p class="event-description__label">Description</p>
                <p class="event-description__text">
                    Dans cette édition spéciale de RAW, les invités ayant marqué l'histoire de RAW à
                    travers les années se retrouvent pour une soirée exceptionnelle.
                </p>
            </div>
        </section>

        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Programme</h2>
                <span class="panel__meta">3 matchs</span>
            </div>
            <div class="panel__body">
                <ol class="program">
                    <li class="program__item">
                        <span class="program__index" aria-hidden="true">1</span>
                        <div class="program__body">
                            <p class="program__name">Triple H vs. Randy Orton</p>
                            <p class="program__type">No Holds Barred Match</p>
                        </div>
                    </li>
                    <li class="program__item">
                        <span class="program__index" aria-hidden="true">2</span>
                        <div class="program__body">
                            <p class="program__name">John Cena vs. The Miz vs. CM Punk</p>
                            <p class="program__type">Steel Cage Match</p>
                        </div>
                    </li>
                    <li class="program__item">
                        <span class="program__index" aria-hidden="true">3</span>
                        <div class="program__body">
                            <p class="program__name">Sheamus vs. Drew McIntyre</p>
                            <p class="program__type">Iron Man Match</p>
                        </div>
                    </li>
                </ol>
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
                <span class="stat-row__value">0 / 120</span>
            </div>
        </div>
    </section>
</main>