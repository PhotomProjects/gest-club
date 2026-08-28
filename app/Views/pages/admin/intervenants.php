<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Intervenants</h1>
            <p class="admin-header__description">Gérez les personnes pouvant participer aux matchs.</p>
        </div>
        <div class="admin-header__actions">
            <a class="btn btn--primary" href="/admin/intervenant-form.php">
                <span class="btn__plus" aria-hidden="true">+</span>
                Ajouter un intervenant
            </a>
        </div>
    </div>

    <section class="panel">
        <div class="panel__body panel__body--flush">
            <div class="table-wrap">
                <table class="table">
                    <caption class="visually-hidden">Liste des intervenants</caption>
                    <thead>
                        <tr>
                            <th scope="col">Intervenant</th>
                            <th scope="col" class="is-center">Participations</th>
                            <th scope="col">Statut</th>
                            <th scope="col" class="is-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <span class="cell-strong">Triple H</span>
                            </td>
                            <td class="is-center cell-num">5</td>
                            <td>
                                <span class="badge badge--muted">Inactif</span>
                            </td>
                            <td class="is-actions">
                                <a class="btn btn--secondary btn--sm" href="/admin/intervenant-form-modification.php">
                                    Gérer
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="cell-strong">Sasha Banks</span>
                            </td>
                            <td class="is-center cell-num">8</td>
                            <td>
                                <span class="badge badge--success">Actif</span>
                            </td>
                            <td class="is-actions">
                                <a class="btn btn--secondary btn--sm" href="/admin/intervenant-form-modification.php">
                                    Gérer
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="cell-strong">Roman Reigns</span>
                            </td>
                            <td class="is-center cell-num">15</td>
                            <td>
                                <span class="badge badge--success">Actif</span>
                            </td>
                            <td class="is-actions">
                                <a class="btn btn--secondary btn--sm" href="/admin/intervenant-form-modification.php">
                                    Gérer
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="cell-strong">Becky Lynch</span>
                            </td>
                            <td class="is-center cell-num">3</td>
                            <td>
                                <span class="badge badge--success">Actif</span>
                            </td>
                            <td class="is-actions">
                                <a class="btn btn--secondary btn--sm" href="/admin/intervenant-form-modification.php">
                                    Gérer
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="cell-strong">John Cena</span>
                            </td>
                            <td class="is-center cell-num">1</td>
                            <td>
                                <span class="badge badge--muted">Inactif</span>
                            </td>
                            <td class="is-actions">
                                <a class="btn btn--secondary btn--sm" href="/admin/intervenant-form-modification.php">
                                    Gérer
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="cell-strong">Charlotte Flair</span>
                            </td>
                            <td class="is-center cell-num">7</td>
                            <td>
                                <span class="badge badge--success">Actif</span>
                            </td>
                            <td class="is-actions">
                                <a class="btn btn--secondary btn--sm" href="/admin/intervenant-form-modification.php">
                                    Gérer
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="cell-strong">Seth Rollins</span>
                            </td>
                            <td class="is-center cell-num">5</td>
                            <td>
                                <span class="badge badge--success">Actif</span>
                            </td>
                            <td class="is-actions">
                                <a class="btn btn--secondary btn--sm" href="/admin/intervenant-form-modification.php">
                                    Gérer
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="cell-strong">Matt Cardona</span>
                            </td>
                            <td class="is-center cell-num">3</td>
                            <td>
                                <span class="badge badge--success">Actif</span>
                            </td>
                            <td class="is-actions">
                                <a class="btn btn--secondary btn--sm" href="/admin/intervenant-form-modification.php">
                                    Gérer
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="cell-strong">Chris Jericho</span>
                            </td>
                            <td class="is-center cell-num">10</td>
                            <td>
                                <span class="badge badge--muted">Inactif</span>
                            </td>
                            <td class="is-actions">
                                <a class="btn btn--secondary btn--sm" href="/admin/intervenant-form-modification.php">
                                    Gérer
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>