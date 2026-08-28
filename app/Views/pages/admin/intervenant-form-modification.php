<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Modifier un intervenant</h1>
        </div>
    </div>

    <div class="admin-centered">
        <form class="panel" action="#" method="post">
            <div class="panel__body">
                <div class="form">
                    <div class="field">
                        <label class="field__label" for="scene">Nom de scène</label>
                        <input class="input" type="text" id="scene" name="nom_scene" value="Triple H"
                            placeholder="Ex. Triple H" required>
                    </div>
                    <fieldset class="field">
                        <legend class="field__label">Statut</legend>
                        <div class="radio-row">
                            <label class="radio">
                                <input type="radio" name="statut" value="actif" checked>Actif
                            </label>
                            <label class="radio">
                                <input type="radio" name="statut" value="inactif">Inactif
                            </label>
                        </div>
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