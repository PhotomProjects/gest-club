<main class="page">
    <div class="container">
        <section class="form-panel">
            <h1>Sécurité du compte</h1>
            <form class="form" action="#" method="post">
                <div class="field">
                    <label class="field__label" for="actuel">Mot de passe actuel</label>
                    <div class="password-field">
                        <input class="input" type="password" id="actuel" name="mot_de_passe_actuel"
                            autocomplete="current-password" required>
                        <button id="current-password-toggle" class="password-toggle" type="button" aria-pressed="false">
                            Afficher
                        </button>
                    </div>
                </div>
                <div class="field">
                    <label class="field__label" for="nouveau">Nouveau mot de passe</label>
                    <div class="password-field">
                        <input class="input" type="password" id="nouveau" name="nouveau_mot_de_passe"
                            autocomplete="new-password" minlength="8" aria-describedby="new-password-rules" required>
                        <button id="new-password-toggle" class="password-toggle" type="button" aria-pressed="false">
                            Afficher
                        </button>
                    </div>
                </div>
                <div class="field">
                    <label class="field__label" for="nouveau2">Confirmer le nouveau mot de passe</label>
                    <div class="password-field">
                        <input class="input" type="password" id="nouveau2" name="nouveau_mot_de_passe_confirmation"
                            autocomplete="new-password" minlength="8" aria-describedby="new-password-confirmation-error"
                            required>
                        <button id="new-password2-toggle" class="password-toggle" type="button" aria-pressed="false">
                            Afficher
                        </button>
                    </div>
                    <p class="field__error" id="new-password-confirmation-error" hidden>
                        Les mots de passe ne correspondent pas.
                    </p>
                </div>
                <div class="rules" id="new-password-rules">
                    <p>Le nouveau mot de passe doit contenir :</p>
                    <ul>
                        <li data-password-rule="length">
                            8 caractères minimum ;
                        </li>
                        <li data-password-rule="letter-case">
                            une majuscule et une minuscule ;
                        </li>
                        <li data-password-rule="number">
                            un chiffre ;
                        </li>
                        <li data-password-rule="special">
                            un caractère spécial.
                        </li>
                    </ul>
                </div>
                <div class="form-actions">
                    <button class="btn btn--primary btn--lg" type="submit">Mettre à jour</button>
                </div>
            </form>
            <div class="form-back">
                <a class="link" href="/compte.php">Retour à mon compte</a>
            </div>
        </section>
    </div>
</main>