<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Ajouter un intervenant</h1>
        </div>
    </div>
    <div class="admin-centered">
        <form class="panel" action="#" method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <div class="panel__body">
                <div class="form">
                    <div class="field">
                        <label class="field__label" for="scene">Nom de scène</label>
                        <input class="input" type="text" id="scene" name="nom_scene" placeholder="Ex. Triple H" required
                            maxlength="100">
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
                <button class="btn btn--primary" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</main>