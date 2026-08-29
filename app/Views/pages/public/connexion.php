<main class="page">
    <div class="container">
        <div class="auth-grid">

            <section class="auth-col">
                <h1>Connexion</h1>
                <form class="form" action="#" method="post">
                    <div class="field">
                        <label class="field__label" for="login-email">Adresse e-mail</label>
                        <input class="input" type="email" id="login-email" name="email"
                            placeholder="prenom.nom@exemple.fr" autocomplete="email" required>
                    </div>
                    <div class="field">
                        <label class="field__label" for="login-password">Mot de passe</label>
                        <div class="password-field">
                            <input class="input" type="password" id="login-password" name="mot_de_passe"
                                placeholder="Votre mot de passe" autocomplete="current-password" required>
                            <button id="login-password-toggle" class="password-toggle" type="button"
                                aria-pressed="false">
                                Afficher
                            </button>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button class="btn btn--primary btn--lg" type="submit">Se connecter</button>
                    </div>
                </form>
            </section>

            <div class="form-divider" aria-hidden="true">
                <span>OU</span>
            </div>

            <section class="auth-col">
                <h2>Inscription</h2>
                <form class="form" action="#" method="post">
                    <div class="field">
                        <label class="field__label" for="signup-nom">Nom</label>
                        <input class="input" type="text" id="signup-nom" name="nom" placeholder="DUPONT"
                            autocomplete="family-name" required>
                    </div>
                    <div class="field">
                        <label class="field__label" for="signup-prenom">Prénom</label>
                        <input class="input" type="text" id="signup-prenom" name="prenom" placeholder="Camille"
                            autocomplete="given-name" required>
                    </div>
                    <div class="field">
                        <label class="field__label" for="signup-email">Adresse e-mail</label>
                        <input class="input" type="email" id="signup-email" name="email"
                            placeholder="prenom.nom@exemple.fr" autocomplete="email" required>
                    </div>
                    <div class="field">
                        <label class="field__label" for="signup-email2">Confirmer l'adresse e-mail</label>
                        <input class="input" type="email" id="signup-email2" name="email_confirmation"
                            placeholder="prenom.nom@exemple.fr" autocomplete="email"
                            aria-describedby="signup-email-confirmation-error" required>
                        <p class="field__error" id="signup-email-confirmation-error" hidden>
                            Les adresses e-mail ne correspondent pas.
                        </p>
                    </div>
                    <div class="field">
                        <label class="field__label" for="signup-password">Mot de passe</label>
                        <div class="password-field">
                            <input class="input" type="password" id="signup-password" name="mot_de_passe"
                                placeholder="Votre mot de passe" autocomplete="new-password" minlength="8"
                                aria-describedby="password-rules" required>
                            <button id="signup-password-toggle" class="password-toggle" type="button"
                                aria-pressed="false">
                                Afficher
                            </button>
                        </div>
                    </div>
                    <div class="field">
                        <label class="field__label" for="signup-password2">Confirmer le mot de passe</label>
                        <div class="password-field">
                            <input class="input" type="password" id="signup-password2" name="mot_de_passe_confirmation"
                                placeholder="Confirmer le mot de passe" autocomplete="new-password" minlength="8"
                                aria-describedby="signup-password-confirmation-error" required>
                            <button id="signup-password2-toggle" class="password-toggle" type="button"
                                aria-pressed="false">
                                Afficher
                            </button>
                        </div>
                        <p class="field__error" id="signup-password-confirmation-error" hidden>
                            Les mots de passe ne correspondent pas.
                        </p>
                    </div>
                    <div class="rules" id="password-rules">
                        <p>Le mot de passe doit contenir :</p>
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
                        <button class="btn btn--primary btn--lg" type="submit">S'inscrire</button>
                    </div>
                </form>
            </section>

        </div>
    </div>
</main>