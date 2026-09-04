<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Intervenants</h1>
            <p class="admin-header__description">Gérez les personnes pouvant participer aux matchs.</p>
        </div>
        <div class="admin-header__actions">
            <a class="btn btn--primary" href="/admin/intervenant-form.php">
                <span class="btn__plus" aria-hidden="true">
                    +
                </span>
                Ajouter un intervenant
            </a>
        </div>
    </div>
    <?php if (empty($intervenants)): ?>
        <div class="empty-state">
            <p class="empty-state__title">Aucun intervenant enregistré</p>
            <p>Ajoutez un premier intervenant pour pouvoir le sélectionner dans les matchs.</p>
            <a class="btn btn--primary" href="/admin/intervenant-form.php">Ajouter un intervenant</a>
        </div>
    <?php else: ?>
        <section class="panel">
            <div class="panel__body panel__body--flush">
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nom de scène</th>
                                <th>Statut</th>
                                <th class="is-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($intervenants as $intervenant): ?>
                                <tr>
                                    <td>
                                        <span class="cell-strong">
                                            <?= htmlspecialchars($intervenant['nom_scene'], ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($intervenant['statut_intervenant'] === 'ACTIF'): ?>
                                            <span class="badge badge--success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge badge--muted">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="is-actions">
                                        <a class="btn btn--ghost"
                                            href="/admin/intervenant-form-modification.php?id=<?= (int) $intervenant['id_intervenant'] ?>">
                                            Modifier
                                        </a>
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