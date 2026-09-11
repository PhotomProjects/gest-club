<main id="main-content" class="admin-content" tabindex="-1">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Modifier l'intervenant</h1>
            <p class="admin-header__description">Modifiez les informations de l'intervenant.</p>
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
                        <label class="field__label" for="scene">Nom de scène</label>
                        <input class="input<?= isset($erreurs['nom_scene']) ? ' is-invalid' : '' ?>" type="text"
                            id="scene" name="nom_scene" maxlength="100" placeholder="Ex. Triple H"
                            value="<?= htmlspecialchars($nomScene, ENT_QUOTES, 'UTF-8') ?>" required>
                        <?php if (isset($erreurs['nom_scene'])): ?>
                            <p class="field__error">
                                <?= htmlspecialchars($erreurs['nom_scene'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <fieldset class="field">
                        <legend class="field__label">Statut</legend>
                        <div class="radio-row">
                            <label class="radio">
                                <input type="radio" name="statut" value="ACTIF" <?= $statut === 'ACTIF' ? 'checked' : '' ?>>
                                Actif
                            </label>
                            <label class="radio">
                                <input type="radio" name="statut" value="INACTIF" <?= $statut === 'INACTIF' ? 'checked' : '' ?>>
                                Inactif
                            </label>
                        </div>
                        <?php if (isset($erreurs['statut'])): ?>
                            <p class="field__error">
                                <?= htmlspecialchars($erreurs['statut'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        <?php endif; ?>
                    </fieldset>
                </div>
            </div>
            <div class="panel__foot">
                <a class="btn btn--ghost" href="/admin/intervenants.php">Annuler</a>
                <button class="btn btn--primary" type="submit">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</main>