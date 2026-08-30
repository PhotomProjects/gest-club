<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Modifier l'évènement</h1>
            <p class="admin-header__description">
                Modifiez les informations existantes et le programme, puis enregistrez vos changements.
            </p>
        </div>
    </div>
    <form action="#" enctype="multipart/form-data" method="post">
        <div class="event-form-layout">
            <div class="event-form-column">
                <section class="panel">
                    <div class="panel__head">
                        <h2 class="step-title">
                            <span aria-hidden="true" class="step-title__index">1</span>
                            Informations générales
                        </h2>
                    </div>
                    <div class="panel__body">
                        <div class="form">
                            <div class="field">
                                <label class="field__label" for="ev-nom">Nom de l'évènement</label>
                                <input class="input" id="ev-nom" name="nom" required type="text"
                                    value="RAW is WAR: 1000th Ep.">
                            </div>
                            <div class="field-pair">
                                <div class="field">
                                    <label class="field__label" for="ev-date">Date</label>
                                    <input class="input" id="ev-date" name="date" required type="date"
                                        value="2026-07-26">
                                </div>
                                <div class="field">
                                    <label class="field__label" for="ev-heure">Heure</label>
                                    <input class="input" id="ev-heure" name="heure" required type="time" value="20:00">
                                </div>
                            </div>
                            <div class="field">
                                <label class="field__label" for="ev-lieu">Lieu</label>
                                <input class="input" id="ev-lieu" name="lieu" required type="text"
                                    value="Lucha Pit Arena, Paris">
                            </div>
                        </div>
                    </div>
                </section>

                <section class="panel">
                    <div class="panel__head">
                        <h2 class="step-title">
                            <span aria-hidden="true" class="step-title__index">2</span>
                            Image de couverture
                        </h2>
                    </div>
                    <div class="panel__body">
                        <div class="upload__preview" id="ev-image-preview" data-image-mode="edit">Image actuelle</div>
                        <div class="field event-image-field">
                            <label class="field__label" for="ev-image">Remplacer l'image
                                <span class="optional">(facultatif)</span>
                            </label>
                            <input accept="image/jpeg,image/png" class="input" id="ev-image" name="image" type="file">
                            <p class="field__hint">
                                JPG ou PNG · 5 Mo maximum. Sans nouveau fichier, l'image actuelle est conservée.
                            </p>
                            <p class="field__error" id="ev-image-error" hidden></p>
                        </div>
                    </div>
                </section>

            </div>
            <div class="event-form-column">

                <section class="panel">
                    <div class="panel__head">
                        <h2 class="step-title">
                            <span aria-hidden="true" class="step-title__index">3</span>
                            Description
                        </h2>
                    </div>
                    <div class="panel__body">
                        <label class="visually-hidden" for="ev-desc">Description de l'évènement</label>
                        <textarea class="textarea" id="ev-desc"
                            name="description">Dans cette édition spéciale de RAW, les invités ayant marqué l'histoire du show se retrouvent pour une soirée exceptionnelle à la Lucha Pit Arena.</textarea>
                    </div>
                </section>

                <section class="panel">
                    <div class="panel__head">
                        <h2 class="step-title">
                            <span aria-hidden="true" class="step-title__index">4</span>
                            Programme et matchs
                        </h2>
                    </div>
                    <div class="panel__body">
                        <p class="program-help">Les matchs sont ajoutés dans l'ordre d'affichage.</p>
                        <ol class="program" id="program-list">
                            <li class="program__item">
                                <span aria-hidden="true" class="program__index">1</span>
                                <div class="program__body">
                                    <p class="program__name">Triple H vs. Randy Orton</p>
                                    <p class="program__type">No Holds Barred Match</p>
                                </div>
                                <div class="program__actions">
                                    <a aria-label="Modifier le match Triple H contre Randy Orton"
                                        class="btn btn--ghost btn--sm" href="/admin/evenement-match.php">
                                        Modifier
                                    </a>
                                    <button aria-label="Retirer le match Triple H contre Randy Orton du programme"
                                        class="btn btn--ghost btn--sm program-remove" type="button">
                                        Retirer
                                    </button>
                                </div>
                            </li>
                            <li class="program__item">
                                <span aria-hidden="true" class="program__index">2</span>
                                <div class="program__body">
                                    <p class="program__name">John Cena vs. The Miz vs. CM Punk</p>
                                    <p class="program__type">Steel Cage Match</p>
                                </div>
                                <div class="program__actions">
                                    <a aria-label="Modifier le match John Cena contre The Miz contre CM Punk"
                                        class="btn btn--ghost btn--sm" href="/admin/evenement-match.php">
                                        Modifier
                                    </a>
                                    <button
                                        aria-label="Retirer le match John Cena contre The Miz contre CM Punk du programme"
                                        class="btn btn--ghost btn--sm program-remove" type="button">
                                        Retirer
                                    </button>
                                </div>
                            </li>
                            <li class="program__item">
                                <span aria-hidden="true" class="program__index">3</span>
                                <div class="program__body">
                                    <p class="program__name">Sheamus vs. Drew McIntyre</p>
                                    <p class="program__type">Iron Man Match</p>
                                </div>
                                <div class="program__actions">
                                    <a aria-label="Modifier le match Sheamus contre Drew McIntyre"
                                        class="btn btn--ghost btn--sm" href="/admin/evenement-match.php">
                                        Modifier
                                    </a>
                                    <button aria-label="Retirer le match Sheamus contre Drew McIntyre du programme"
                                        class="btn btn--ghost btn--sm program-remove" type="button">
                                        Retirer
                                    </button>
                                </div>
                            </li>
                        </ol>
                        <a class="btn btn--primary btn--sm program-add" href="/admin/evenement-match.php">
                            <span aria-hidden="true" class="btn__plus">+</span>
                            Ajouter un match
                        </a>
                    </div>
                </section>

            </div>
        </div>
        <div class="admin-form-actions">
            <a class="btn btn--ghost" href="/admin/evenement.php">Annuler</a>
            <button class="btn btn--primary" type="submit">Enregistrer les modifications</button>
        </div>
    </form>
</main>