<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Créer un évènement</h1>
            <p class="admin-header__description">
                Renseignez les informations nécessaires pour créer un nouvel évènement.
            </p>
        </div>
    </div>
    <?php if (isset($erreurs['general'])): ?>
        <div class="notice">
            <p>
                <?= htmlspecialchars($erreurs['general']) ?>
            </p>
        </div>
    <?php endif; ?>
    <form enctype="multipart/form-data" method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
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
                                <input class="input<?= isset($erreurs['nom']) ? ' is-invalid' : '' ?>" id="ev-nom"
                                    name="nom" placeholder="Ex. RAW is WAR: 1000th Ep." required type="text"
                                    maxlength="150" value="<?= htmlspecialchars($nom) ?>">
                                <?php if (isset($erreurs['nom'])): ?>
                                    <p class="field__error">
                                        <?= htmlspecialchars($erreurs['nom']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="field-pair">
                                <div class="field">
                                    <label class="field__label" for="ev-date-debut">Date de début</label>
                                    <input class="input<?= isset($erreurs['date_debut']) ? ' is-invalid' : '' ?>"
                                        id="ev-date-debut" name="date_debut" required type="date"
                                        value="<?= htmlspecialchars($dateDebut) ?>">
                                    <?php if (isset($erreurs['date_debut'])): ?>
                                        <p class="field__error">
                                            <?= htmlspecialchars($erreurs['date_debut']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="field">
                                    <label class="field__label" for="ev-heure-debut">Heure de début</label>
                                    <input class="input<?= isset($erreurs['heure_debut']) ? ' is-invalid' : '' ?>"
                                        id="ev-heure-debut" name="heure_debut" required type="time"
                                        value="<?= htmlspecialchars($heureDebut) ?>">
                                    <?php if (isset($erreurs['heure_debut'])): ?>
                                        <p class="field__error">
                                            <?= htmlspecialchars($erreurs['heure_debut']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="field-pair">
                                <div class="field">
                                    <label class="field__label" for="ev-date-fin">Date de fin</label>
                                    <input class="input<?= isset($erreurs['date_fin']) ? ' is-invalid' : '' ?>"
                                        id="ev-date-fin" name="date_fin" required type="date"
                                        value="<?= htmlspecialchars($dateFin) ?>">
                                    <?php if (isset($erreurs['date_fin'])): ?>
                                        <p class="field__error">
                                            <?= htmlspecialchars($erreurs['date_fin']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="field">
                                    <label class="field__label" for="ev-heure-fin">Heure de fin</label>
                                    <input class="input<?= isset($erreurs['heure_fin']) ? ' is-invalid' : '' ?>"
                                        id="ev-heure-fin" name="heure_fin" required type="time"
                                        value="<?= htmlspecialchars($heureFin) ?>">
                                    <?php if (isset($erreurs['heure_fin'])): ?>
                                        <p class="field__error">
                                            <?= htmlspecialchars($erreurs['heure_fin']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (isset($erreurs['dates'])): ?>
                                <p class="field__error">
                                    <?= htmlspecialchars($erreurs['dates']) ?>
                                </p>
                            <?php endif; ?>
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
                        <div class="field">
                            <label class="field__label" for="ev-image">Image de couverture
                                <span class="optional">(facultatif)</span>
                            </label>
                            <input accept="image/jpeg,image/png"
                                class="input<?= isset($erreurs['image']) ? ' is-invalid' : '' ?>" id="ev-image"
                                name="image" type="file">
                            <p class="field__hint">JPG ou PNG · 5 Mo maximum.</p>
                            <p class="field__error" id="ev-image-error" hidden></p>
                            <?php if (isset($erreurs['image'])): ?>
                                <p class="field__error">
                                    <?= htmlspecialchars($erreurs['image']) ?>
                                </p>
                            <?php endif; ?>
                            <div class="upload__preview" id="ev-image-preview" data-image-mode="create" hidden></div>
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
                        <textarea class="textarea<?= isset($erreurs['description']) ? ' is-invalid' : '' ?>"
                            id="ev-desc" name="description" maxlength="2000" required
                            placeholder="Décrivez l'évènement, ses enjeux et les informations utiles aux visiteurs."><?= htmlspecialchars($description) ?></textarea>
                        <?php if (isset($erreurs['description'])): ?>
                            <p class="field__error">
                                <?= htmlspecialchars($erreurs['description']) ?>
                            </p>
                        <?php endif; ?>
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
                        <a class="btn btn--primary btn--sm program-add" href="/admin/evenement-match.php">
                            <span aria-hidden="true" class="btn__plus">+</span>
                            Ajouter un match
                        </a>
                    </div>
                </section>

            </div>
        </div>
        <div class="admin-form-actions">
            <a class="btn btn--ghost" href="/admin/evenements.php">Retour</a>
            <button class="btn btn--primary" type="submit">
                Créer l'évènement
            </button>
        </div>
    </form>
</main>