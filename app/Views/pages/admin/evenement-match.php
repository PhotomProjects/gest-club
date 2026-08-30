<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Ajouter un match</h1>
            <p class="admin-header__description">Évènement : RAW is WAR: 1000th Ep.</p>
        </div>
    </div>
    <div class="admin-centered admin-centered--wide">
        <form action="#" method="post">
            <section class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">Informations du match</h2>
                </div>
                <div class="panel__body">
                    <div class="form">
                        <div class="field">
                            <label class="field__label" for="m-nom">Nom du match</label>
                            <input class="input" id="m-nom" name="nom" placeholder="Ex. Triple H vs. Randy Orton"
                                required type="text">
                        </div>
                        <div class="field-pair">
                            <div class="field">
                                <label class="field__label" for="m-type">Type de match</label>
                                <select class="select" id="m-type" name="type_match" required>
                                    <option value="">Sélectionner un type de match</option>
                                    <option value="no-holds-barred">No Holds Barred Match</option>
                                    <option value="fatal-4-way">Fatal 4 Way</option>
                                    <option value="steel-cage">Steel Cage Match</option>
                                    <option value="iron-man">Iron Man Match</option>
                                </select>
                            </div>
                            <div class="field">
                                <label class="field__label" for="m-ordre">Ordre dans le programme</label>
                                <input class="input" id="m-ordre" min="1" name="ordre" required type="number" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <h3 class="form-section__title">Catcheurs</h3>
                        <div class="participant-list" id="participant-list">
                            <div class="participant">
                                <span aria-hidden="true" class="participant__index">1</span>
                                <select class="select" name="catcheur[]" required>
                                    <option value="">Sélectionner un catcheur</option>
                                    <option value="triple-h">Triple H</option>
                                    <option value="randy-orton">Randy Orton</option>
                                </select>
                                <select class="select" name="camp[]" required>
                                    <option value="">Sélectionner un camp</option>
                                    <option value="A">Camp A</option>
                                    <option value="B">Camp B</option>
                                </select>
                                <button class="btn btn--ghost btn--sm participant-remove" type="button">
                                    Retirer
                                </button>
                            </div>
                            <div class="participant">
                                <span aria-hidden="true" class="participant__index">2</span>
                                <select class="select" name="catcheur[]" required>
                                    <option value="">Sélectionner un catcheur</option>
                                    <option value="triple-h">Triple H</option>
                                    <option value="randy-orton">Randy Orton</option>
                                </select>
                                <select class="select" name="camp[]" required>
                                    <option value="">Sélectionner un camp</option>
                                    <option value="1">Camp A</option>
                                    <option value="2">Camp B</option>
                                </select>
                                <button class="btn btn--ghost btn--sm participant-remove" type="button">
                                    Retirer
                                </button>
                            </div>
                        </div>
                        <button class="btn btn--primary btn--sm participant-add" id="participant-add" type="button">
                            <span aria-hidden="true" class="btn__plus">+</span>
                            Ajouter un participant
                        </button>
                    </div>
                    <div class="form-section">
                        <h3 class="form-section__title">Arbitre</h3>
                        <select aria-label="Arbitre" class="select" name="arbitre" required>
                            <option value="">Sélectionner un arbitre</option>
                            <option>Charles Robinson</option>
                            <option>Mike Chioda</option>
                        </select>
                    </div>
                    <div class="form-section">
                        <h3 class="form-section__title">Managers
                            <span class="optional">(facultatif)</span>
                        </h3>
                        <div class="participant-list" id="manager-list"></div>

                        <!-- modèle caché pour créer de nouvelles lignes -->
                        <div class="participant participant--manager" id="manager-template" hidden>
                            <select class="select" name="manager[]" disabled required>
                                <option value="">Sélectionner un manager</option>
                                <option>Paul Heyman</option>
                            </select>
                            <select class="select" name="manager_camp[]" disabled required>
                                <option value="">Camp associé</option>
                                <option value="1">Camp A</option>
                                <option value="2">Camp B</option>
                            </select>
                            <button class="btn btn--ghost btn--sm manager-remove" type="button">
                                Retirer
                            </button>
                        </div>

                        <button class="btn btn--primary btn--sm participant-add" id="manager-add" type="button">
                            <span aria-hidden="true" class="btn__plus">+</span>
                            Ajouter un manager
                        </button>
                    </div>
                </div>
                <div class="panel__foot">
                    <div class="admin-form-actions">
                        <a class="btn btn--ghost" href="/admin/evenement-form.php">Annuler</a>
                        <button class="btn btn--primary" type="submit">Ajouter au programme</button>
                    </div>
                </div>
            </section>
        </form>
    </div>
</main>