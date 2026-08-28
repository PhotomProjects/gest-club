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
                        <input class="input" type="password" id="login-password" name="mot_de_passe"
                            placeholder="Votre mot de passe" autocomplete="current-password" required>
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
                            placeholder="prenom.nom@exemple.fr" required>
                    </div>
                    <div class="field">
                        <label class="field__label" for="signup-password">Mot de passe</label>
                        <input class="input" type="password" id="signup-password" name="mot_de_passe"
                            placeholder="Votre mot de passe" autocomplete="new-password" minlength="8"
                            aria-describedby="password-rules" required>
                    </div>
                    <div class="field">
                        <label class="field__label" for="signup-password2">Confirmer le mot de passe</label>
                        <input class="input" type="password" id="signup-password2" name="mot_de_passe_confirmation"
                            placeholder="Confirmer le mot de passe" autocomplete="new-password" minlength="8" required>
                    </div>
                    <div class="rules" id="password-rules">
                        <p>Le mot de passe doit contenir :</p>
                        <ul>
                            <li>8 caractères minimum ;</li>
                            <li>une majuscule et une minuscule ;</li>
                            <li>un chiffre ;</li>
                            <li>un caractère spécial.</li>
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