<main class="admin-content">
    <h1 class="visually-hidden">Vue d'ensemble</h1>
    <div class="dashboard-layout">
        <div class="dashboard-panels">

            <section class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">Prochains évènements</h2>
                </div>
                <div class="panel__body panel__body--flush">
                    <div class="table-wrap">
                        <table class="table">
                            <caption class="visually-hidden">Prochains évènements</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Évènement</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Lieu</th>
                                    <th scope="col" class="is-center">Matchs</th>
                                    <th scope="col">Statut</th>
                                    <th scope="col" class="is-center">Places réservées</th>
                                    <th scope="col" class="is-actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="cell-strong">RAW</span>
                                    </td>
                                    <td class="cell-num">
                                        <time datetime="2026-10-01T20:00">01/10/2026
                                            <span class="cell-sub">20:00</span>
                                        </time>
                                    </td>
                                    <td>Lucha Pit Arena, Paris</td>
                                    <td class="is-center cell-num">8</td>
                                    <td>
                                        <span class="badge badge--success">Ouvert</span>
                                    </td>
                                    <td class="is-center cell-num">58 / 120</td>
                                    <td class="is-actions">
                                        <a class="btn btn--secondary btn--sm" href="/admin/evenement.php">Gérer</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="cell-strong">Smackdown</span>
                                    </td>
                                    <td class="cell-num">
                                        <time datetime="2026-10-06T20:00">06/10/2026
                                            <span class="cell-sub">20:00</span>
                                        </time>
                                    </td>
                                    <td>Lucha Pit Arena, Paris</td>
                                    <td class="is-center cell-num">6</td>
                                    <td>
                                        <span class="badge badge--success">Ouvert</span>
                                    </td>
                                    <td class="is-center cell-num">27 / 120</td>
                                    <td class="is-actions">
                                        <a class="btn btn--secondary btn--sm" href="/admin/evenement.php">Gérer</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="cell-strong">Live Event</span>
                                    </td>
                                    <td class="cell-num">
                                        <time datetime="2026-10-10T20:00">10/10/2026
                                            <span class="cell-sub">20:00</span>
                                        </time>
                                    </td>
                                    <td>Lucha Pit Arena, Paris</td>
                                    <td class="is-center cell-num">12</td>
                                    <td>
                                        <span class="badge badge--success">Ouvert</span>
                                    </td>
                                    <td class="is-center cell-num">75 / 120</td>
                                    <td class="is-actions">
                                        <a class="btn btn--secondary btn--sm" href="/admin/evenement.php">Gérer</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="cell-strong">RAW</span>
                                    </td>
                                    <td class="cell-num">
                                        <time datetime="2026-10-11T20:00">11/10/2026
                                            <span class="cell-sub">20:00</span>
                                        </time>
                                    </td>
                                    <td>Lucha Pit Arena, Paris</td>
                                    <td class="is-center cell-num">8</td>
                                    <td>
                                        <span class="badge badge--success">Ouvert</span>
                                    </td>
                                    <td class="is-center cell-num">12 / 120</td>
                                    <td class="is-actions">
                                        <a class="btn btn--secondary btn--sm" href="/admin/evenement.php">Gérer</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="cell-strong">Smackdown</span>
                                    </td>
                                    <td class="cell-num">
                                        <time datetime="2026-10-12T20:00">12/10/2026
                                            <span class="cell-sub">20:00</span>
                                        </time>
                                    </td>
                                    <td>Lucha Pit Arena, Paris</td>
                                    <td class="is-center cell-num">8</td>
                                    <td>
                                        <span class="badge badge--success">Ouvert</span>
                                    </td>
                                    <td class="is-center cell-num">41 / 120</td>
                                    <td class="is-actions">
                                        <a class="btn btn--secondary btn--sm" href="/admin/evenement.php">Gérer</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">Réservations récentes</h2>
                </div>
                <div class="panel__body panel__body--flush">
                    <div class="table-wrap">
                        <table class="table">
                            <caption class="visually-hidden">Réservations récentes</caption>
                            <thead>
                                <tr>
                                    <th scope="col">N° Réservation</th>
                                    <th scope="col">Utilisateur</th>
                                    <th scope="col">Évènement</th>
                                    <th scope="col">Statut</th>
                                    <th scope="col">Places</th>
                                    <th scope="col" class="is-right">Total</th>
                                    <th scope="col" class="is-actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="cell-num">R-2026-105</td>
                                    <td>Cosner Phil</td>
                                    <td>
                                        <span class="cell-strong">Live Event</span>
                                        <time class="cell-sub" datetime="2026-10-20T20:00">20/10/2026 · 20:00</time>
                                    </td>
                                    <td>
                                        <span class="badge badge--success">Confirmée</span>
                                    </td>
                                    <td>2 places
                                        <span class="cell-sub">Sud · Niveau haut</span>
                                    </td>
                                    <td class="is-right cell-num">50 €</td>
                                    <td class="is-actions">
                                        <a class="btn btn--secondary btn--sm" href="/admin/reservation.php">Gérer</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="cell-num">R-2026-104</td>
                                    <td>Mivoka Bruno</td>
                                    <td>
                                        <span class="cell-strong">Smackdown</span>
                                        <time class="cell-sub" datetime="2026-10-12T20:00">12/10/2026 · 20:00</time>
                                    </td>
                                    <td>
                                        <span class="badge badge--success">Confirmée</span>
                                    </td>
                                    <td>1 place
                                        <span class="cell-sub">Est · Niveau milieu</span>
                                    </td>
                                    <td class="is-right cell-num">35 €</td>
                                    <td class="is-actions">
                                        <a class="btn btn--secondary btn--sm" href="/admin/reservation.php">Gérer</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="cell-num">R-2026-103</td>
                                    <td>Lohm Aline</td>
                                    <td>
                                        <span class="cell-strong">RAW</span>
                                        <time class="cell-sub" datetime="2026-10-11T20:00">11/10/2026 · 20:00</time>
                                    </td>
                                    <td>
                                        <span class="badge badge--success">Confirmée</span>
                                    </td>
                                    <td>2 places
                                        <span class="cell-sub">Nord · Niveau bas</span>
                                    </td>
                                    <td class="is-right cell-num">90 €</td>
                                    <td class="is-actions">
                                        <a class="btn btn--secondary btn--sm" href="/admin/reservation.php">Gérer</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="cell-num">R-2026-102</td>
                                    <td>Vaillant Ambre</td>
                                    <td>
                                        <span class="cell-strong">Live Event</span>
                                        <time class="cell-sub" datetime="2026-10-10T20:00">10/10/2026 · 20:00</time>
                                    </td>
                                    <td>
                                        <span class="badge badge--success">Confirmée</span>
                                    </td>
                                    <td>2 places
                                        <span class="cell-sub">Sud · Niveau bas</span>
                                    </td>
                                    <td class="is-right cell-num">90 €</td>
                                    <td class="is-actions">
                                        <a class="btn btn--secondary btn--sm" href="/admin/reservation.php">Gérer</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="cell-num">R-2026-100</td>
                                    <td>Lore Manon</td>
                                    <td>
                                        <span class="cell-strong">RAW</span>
                                        <time class="cell-sub" datetime="2026-10-01T20:00">01/10/2026 · 20:00</time>
                                    </td>
                                    <td>
                                        <span class="badge badge--success">Confirmée</span>
                                    </td>
                                    <td>2 places
                                        <span class="cell-sub">Est · Niveau haut</span>
                                    </td>
                                    <td class="is-right cell-num">50 €</td>
                                    <td class="is-actions">
                                        <a class="btn btn--secondary btn--sm" href="/admin/reservation.php">Gérer</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </div>

        <div class="quick-actions">
            <a class="btn btn--primary btn--lg" href="/admin/evenement-form.php">
                <span class="btn__plus" aria-hidden="true">+</span>
                Créer un évènement
            </a>
            <a class="btn btn--secondary btn--lg" href="/admin/presences-scan.php">Contrôler un billet</a>
        </div>
    </div>
</main>