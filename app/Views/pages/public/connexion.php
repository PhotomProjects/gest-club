<main class="page">
    <div class="container">
        <?php if ($inscriptionReussie): ?>
            <div class="notice auth-notice" role="status">
                <p>
                    Votre compte a bien été créé.
                    Vous pouvez maintenant vous connecter avec vos identifiants.
                </p>
            </div>
        <?php endif; ?>

        <div class="auth-grid">

            <section class="auth-col">
                <h1>Connexion</h1>
                <form class="form" action="/connexion.php" method="post" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <input type="hidden" name="formulaire" value="connexion">
                    <div class="field">
                        <label class="field__label" for="login-email">Adresse e-mail</label>
                        <input class="input" type="email" id="login-email" name="email"
                            placeholder="prenom.nom@exemple.fr" autocomplete="email"
                            value="<?= htmlspecialchars($emailConnexion) ?>" maxlength="255" required>
                        <?php if (isset($erreursConnexion['email'])): ?>
                            <p class="field__error">
                                <?= htmlspecialchars($erreursConnexion['email']) ?>
                            </p>
                        <?php endif; ?>
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
                        <?php if (isset($erreursConnexion['mot_de_passe'])): ?>
                            <p class="field__error">
                                <?= htmlspecialchars($erreursConnexion['mot_de_passe']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($erreursConnexion['identifiants'])): ?>
                        <p class="field__error">
                            <?= htmlspecialchars($erreursConnexion['identifiants']) ?>
                        </p>
                    <?php endif; ?>
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
                <form class="form" action="/connexion.php" method="post" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <input type="hidden" name="formulaire" value="inscription">
                    <div class="field">
                        <label class="field__label" for="signup-nom">Nom</label>
                        <input class="input" type="text" id="signup-nom" name="nom" placeholder="DUPONT"
                            autocomplete="family-name" value="<?= htmlspecialchars($nom) ?>" maxlength="100" required>
                        <?php if (isset($erreursInscription['nom'])): ?>
                            <p class="field__error">
                                <?= htmlspecialchars($erreursInscription['nom']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="field">
                        <label class="field__label" for="signup-prenom">Prénom</label>
                        <input class="input" type="text" id="signup-prenom" name="prenom" placeholder="Camille"
                            autocomplete="given-name" value="<?= htmlspecialchars($prenom) ?>" maxlength="100" required>
                        <?php if (isset($erreursInscription['prenom'])): ?>
                            <p class="field__error">
                                <?= htmlspecialchars($erreursInscription['prenom']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="field">
                        <label class="field__label" for="signup-email">Adresse e-mail</label>
                        <input class="input" type="email" id="signup-email" name="email"
                            placeholder="prenom.nom@exemple.fr" autocomplete="email"
                            value="<?= htmlspecialchars($email) ?>" maxlength="255" required>
                        <?php if (isset($erreursInscription['email'])): ?>
                            <p class="field__error">
                                <?= htmlspecialchars($erreursInscription['email']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="field">
                        <label class="field__label" for="signup-email2">Confirmer l'adresse e-mail</label>
                        <input class="input" type="email" id="signup-email2" name="email_confirmation"
                            placeholder="prenom.nom@exemple.fr" autocomplete="email"
                            value="<?= htmlspecialchars($emailConfirmation) ?>"
                            aria-describedby="signup-email-confirmation-error" maxlength="255" required>
                        <p class="field__error" id="signup-email-confirmation-error"
                            <?= isset($erreursInscription['email_confirmation']) ? '' : 'hidden' ?>>
                            <?= htmlspecialchars(
                                $erreursInscription['email_confirmation']
                                ?? 'Les adresses e-mail ne correspondent pas.'
                            ) ?>
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
                        <?php if (isset($erreursInscription['mot_de_passe'])): ?>
                            <p class="field__error">
                                <?= htmlspecialchars($erreursInscription['mot_de_passe']) ?>
                            </p>
                        <?php endif; ?>
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
                        <p class="field__error" id="signup-password-confirmation-error"
                            <?= isset($erreursInscription['mot_de_passe_confirmation']) ? '' : 'hidden' ?>>
                            <?= htmlspecialchars(
                                $erreursInscription['mot_de_passe_confirmation']
                                ?? 'Les mots de passe ne correspondent pas.'
                            ) ?>
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
                                un caractère spécial et aucun espace.
                            </li>
                        </ul>
                    </div>
                    <p class="form-privacy">
                        Les informations renseignées servent à créer le compte et à gérer les réservations.
                        <a class="link" href="/donnees-personnelles.php">
                            En savoir plus sur les données personnelles
                        </a>.
                    </p>
                    <div class="form-actions">
                        <button class="btn btn--primary btn--lg" type="submit">S'inscrire</button>
                    </div>
                </form>
            </section>

        </div>
    </div>
</main>