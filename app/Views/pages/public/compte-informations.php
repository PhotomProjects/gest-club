<main class="page">
    <div class="container">
        <section class="form-panel">
            <h1>Mes informations</h1>
            <form class="form" action="#" method="post">
                <div class="field">
                    <label class="field__label" for="nom">Nom</label>
                    <input class="input" type="text" id="nom" name="nom" value="DUPONT" autocomplete="family-name"
                        required>
                </div>
                <div class="field">
                    <label class="field__label" for="prenom">Prénom</label>
                    <input class="input" type="text" id="prenom" name="prenom" value="Camille" autocomplete="given-name"
                        required>
                </div>
                <div class="field">
                    <label class="field__label" for="email">Adresse e-mail</label>
                    <input class="input" type="email" id="email" name="email" value="prenom.nom@exemple.fr"
                        autocomplete="email" required>
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