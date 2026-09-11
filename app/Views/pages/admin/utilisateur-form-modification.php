<main id="main-content" class="admin-content" tabindex="-1">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Modifier l'utilisateur</h1>
            <p class="admin-header__description">Gérez le rôle du compte utilisateur.</p>
        </div>
    </div>
    <div class="admin-centered">
        <form class="panel" method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <?php if (isset($erreurs['general'])): ?>
                <div class="notice">
                    <p>
                        <?= htmlspecialchars($erreurs['general'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>
            <div class="panel__body">
                <div class="form">
                    <div class="field">
                        <span class="field__label">Utilisateur</span>
                        <p>
                            <?= htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <p>
                            <?= htmlspecialchars($utilisateur['email'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    </div>
                    <fieldset class="field">
                        <legend class="field__label">Rôle</legend>
                        <div class="radio-row">
                            <label class="radio">
                                <input type="radio" name="role" value="MEMBRE" <?= $role === 'MEMBRE' ? 'checked' : '' ?>>
                                Membre
                            </label>
                            <label class="radio">
                                <input type="radio" name="role" value="ADMIN" <?= $role === 'ADMIN' ? 'checked' : '' ?>>
                                Admin
                            </label>
                        </div>
                        <?php if (isset($erreurs['role'])): ?>
                            <p class="field__error">
                                <?= htmlspecialchars($erreurs['role'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        <?php endif; ?>
                    </fieldset>
                </div>
            </div>
            <div class="panel__foot">
                <a class="btn btn--ghost" href="/admin/utilisateurs.php">Annuler</a>
                <button class="btn btn--primary" type="submit">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</main>