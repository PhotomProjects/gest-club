<main id="main-content" class="admin-content" tabindex="-1">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Utilisateurs</h1>
            <p class="admin-header__description">Consultez les comptes inscrits et gérez leurs rôles.</p>
        </div>
    </div>
    <?php if (empty($utilisateurs)): ?>
        <div class="empty-state">
            <p class="empty-state__title">Aucun utilisateur inscrit</p>
            <p>Les comptes créés apparaîtront ici.</p>
        </div>
    <?php else: ?>
        <section class="panel">
            <div class="panel__body panel__body--flush">
                <div class="table-wrap">
                    <table class="table">
                        <caption class="visually-hidden">Liste des utilisateurs</caption>
                        <thead>
                            <tr>
                                <th scope="col">Utilisateur</th>
                                <th scope="col">Email</th>
                                <th scope="col">Inscription</th>
                                <th scope="col">Rôle</th>
                                <th scope="col" class="is-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($utilisateurs as $utilisateur): ?>
                                <?php
                                $dateCreation = new DateTimeImmutable($utilisateur['date_creation_compte']);
                                ?>
                                <tr>
                                    <td>
                                        <span class="cell-strong">
                                            <?= htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom'], ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($utilisateur['email'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                    <td>
                                        <time datetime="<?= $dateCreation->format('Y-m-d') ?>">
                                            <?= $dateCreation->format('d/m/Y') ?>
                                        </time>
                                    </td>
                                    <td>
                                        <?php if ($utilisateur['role_utilisateur'] === 'ADMIN'): ?>
                                            <span class="badge badge--success">Admin</span>
                                        <?php else: ?>
                                            <span class="badge badge--muted">Membre</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="is-actions">
                                        <a class="btn btn--ghost"
                                            href="/admin/utilisateur.php?id=<?= (int) $utilisateur['id_utilisateur'] ?>">
                                            Voir
                                        </a>
                                        <a class="btn btn--ghost"
                                            href="/admin/utilisateur-form-modification.php?id=<?= (int) $utilisateur['id_utilisateur'] ?>">
                                            Modifier le rôle
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