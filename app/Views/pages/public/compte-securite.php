<main class="page">
    <div class="container">
        <section class="form-panel">
            <h1>Sécurité du compte</h1>
            <form class="form" action="#" method="post">
                <div class="field">
                    <label class="field__label" for="actuel">Mot de passe actuel</label>
                    <input class="input" type="password" id="actuel" name="mot_de_passe_actuel"
                        autocomplete="current-password" required>
                </div>
                <div class="field">
                    <label class="field__label" for="nouveau">Nouveau mot de passe</label>
                    <input class="input" type="password" id="nouveau" name="nouveau_mot_de_passe"
                        autocomplete="new-password" minlength="8" aria-describedby="new-password-rules" required>
                </div>
                <div class="field">
                    <label class="field__label" for="nouveau2">Confirmer le nouveau mot de passe</label>
                    <input class="input" type="password" id="nouveau2" name="nouveau_mot_de_passe_confirmation"
                        autocomplete="new-password" minlength="8" required>
                </div>
                <div class="rules" id="new-password-rules">
                    <p>Le nouveau mot de passe doit contenir :</p>
                    <ul>
                        <li>8 caractères minimum ;</li>
                        <li>une majuscule et une minuscule ;</li>
                        <li>un chiffre ;</li>
                        <li>un caractère spécial.</li>
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