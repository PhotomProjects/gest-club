<main id="main-content" class="page" tabindex="-1">
    <div class="container">
        <section class="form-panel">
            <h1>Mes informations</h1>
            <?php if ($modificationReussie): ?>
                <div class="notice" role="status">
                    <p>Vos informations ont bien été mises à jour.</p>
                </div>
            <?php endif; ?>
            <form class="form" action="#" method="post" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <div class="field">
                    <label class="field__label" for="nom">Nom</label>
                    <input class="input<?= isset($erreurs['nom']) ? ' is-invalid' : '' ?>" type="text" id="nom"
                        name="nom" value="<?= htmlspecialchars($nom) ?>" autocomplete="family-name" maxlength="100"
                        required <?= isset($erreurs['nom']) ? 'aria-invalid="true" aria-describedby="nom-error"' : '' ?>>
                    <?php if (isset($erreurs['nom'])): ?>
                        <p class="field__error" id="nom-error">
                            <?= htmlspecialchars($erreurs['nom']) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="field">
                    <label class="field__label" for="prenom">Prénom</label>
                    <input class="input<?= isset($erreurs['prenom']) ? ' is-invalid' : '' ?>" type="text" id="prenom"
                        name="prenom" value="<?= htmlspecialchars($prenom) ?>" autocomplete="given-name" maxlength="100"
                        required <?= isset($erreurs['prenom']) ? 'aria-invalid="true" aria-describedby="prenom-error"' : '' ?>>
                    <?php if (isset($erreurs['prenom'])): ?>
                        <p class="field__error" id="prenom-error">
                            <?= htmlspecialchars($erreurs['prenom']) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="field">
                    <label class="field__label" for="email">Adresse e-mail</label>
                    <input class="input<?= isset($erreurs['email']) ? ' is-invalid' : '' ?>" type="email" id="email"
                        name="email" value="<?= htmlspecialchars($email) ?>" autocomplete="email" maxlength="255"
                        required <?= isset($erreurs['email']) ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?>>
                    <?php if (isset($erreurs['email'])): ?>
                        <p class="field__error" id="email-error">
                            <?= htmlspecialchars($erreurs['email']) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="form-actions">
                    <button class="btn btn--primary btn--lg" type="submit">Enregistrer</button>
                </div>
            </form>
            <div class="form-back">
                <a class="link" href="/compte.php">Retour à mon compte</a>
            </div>
        </section>
    </div>
</main>