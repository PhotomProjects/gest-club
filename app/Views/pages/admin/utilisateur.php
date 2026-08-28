<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Dupont Martin</h1>
            <div class="record-meta admin-header__meta">
                <span>Membre depuis le
                    <time datetime="2026-05-18">18/05/2026</time>
                </span>
            </div>
        </div>
        <div class="admin-header__actions">
            <a class="btn btn--ghost" href="/admin/utilisateurs.php">Retour</a>
            <button class="btn btn--secondary" type="button">Promouvoir administrateur</button>
        </div>
    </div>
    <div class="user-details-layout">

        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Compte</h2>
            </div>
            <div class="panel__body">
                <dl class="kv">
                    <div class="kv__row">
                        <dt class="kv__key">Nom</dt>
                        <dd class="kv__value">Dupont Martin</dd>
                    </div>
                    <div class="kv__row">
                        <dt class="kv__key">E-mail</dt>
                        <dd class="kv__value">dupont.martin@gmail.com</dd>
                    </div>
                    <div class="kv__row">
                        <dt class="kv__key">Rôle</dt>
                        <dd class="kv__value">Membre</dd>
                    </div>
                </dl>
            </div>
        </section>

        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Réservations associées</h2>
            </div>

            <div class="panel__body panel__body--flush">
                <div class="table-wrap">
                    <table class="table table--compact">
                        <caption class="visually-hidden">Réservations associées à l'utilisateur</caption>
                        <thead>
                            <tr>
                                <th scope="col">N°</th>
                                <th scope="col">Évènement</th>
                                <th scope="col">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="cell-num">
                                    <a class="link" href="/admin/reservation.php">R-2026-100</a>
                                </td>
                                <td>RAW -
                                    <time datetime="2026-10-01">01/10/2026</time>
                                </td>
                                <td>
                                    <span class="badge badge--success">Confirmée</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell-num">
                                    <a class="link" href="/admin/reservation.php">R-2026-088</a>
                                </td>
                                <td>SummerSlam -
                                    <time datetime="2026-09-23">23/09/2026</time>
                                </td>
                                <td>
                                    <span class="badge badge--success">Confirmée</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel__foot">
                <a class="btn btn--ghost btn--block" href="/admin/reservations.php">Voir toutes les réservations</a>
            </div>
        </section>

    </div>
</main>