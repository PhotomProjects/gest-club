<main class="page">
    <div class="container">
        <section class="form-panel">
            <h1>Mes informations</h1>
            <?php if ($modificationReussie): ?>
                <div class="notice">
                    <p>Vos informations ont bien été mises à jour.</p>
                </div>
            <?php endif; ?>
            <form class="form" action="#" method="post" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <div class="field">
                    <label class="field__label" for="nom">Nom</label>
                    <input class="input" type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>"
                        autocomplete="family-name" maxlength="100" required>
                    <?php if (isset($erreurs['nom'])): ?>
                        <p class="field__error">
                            <?= htmlspecialchars($erreurs['nom']) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="field">
                    <label class="field__label" for="prenom">Prénom</label>
                    <input class="input" type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($prenom) ?>"
                        autocomplete="given-name" maxlength="100" required>
                    <?php if (isset($erreurs['prenom'])): ?>
                        <p class="field__error">
                            <?= htmlspecialchars($erreurs['prenom']) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="field">
                    <label class="field__label" for="email">Adresse e-mail</label>
                    <input class="input" type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>"
                        autocomplete="email" maxlength="255" required>
                    <?php if (isset($erreurs['email'])): ?>
                        <p class="field__error">
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