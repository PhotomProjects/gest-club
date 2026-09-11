<main id="main-content" class="admin-content" tabindex="-1">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Modifier le match</h1>
            <p class="admin-header__description">
                Évènement :
                <?= htmlspecialchars($evenement['nom_evenement'], ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>
    </div>
    <div class="admin-centered admin-centered--wide">
        <form method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <?php if (isset($erreurs['general'])): ?>
                <div class="notice">
                    <p>
                        <?= htmlspecialchars($erreurs['general'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>

            <section class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">Informations du match</h2>
                </div>
                <div class="panel__body">
                    <div class="form">
                        <div class="field">
                            <label class="field__label" for="m-nom">Nom du match</label>
                            <input class="input<?= isset($erreurs['nom']) ? ' is-invalid' : '' ?>" id="m-nom" name="nom"
                                type="text" maxlength="150" placeholder="Ex. Triple H vs. Randy Orton"
                                value="<?= htmlspecialchars($nom) ?>" required>
                            <?php if (isset($erreurs['nom'])): ?>
                                <p class="field__error">
                                    <?= htmlspecialchars($erreurs['nom']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="field-pair">
                            <div class="field">
                                <label class="field__label" for="m-type">Type de match</label>
                                <select class="select<?= isset($erreurs['type_match']) ? ' is-invalid' : '' ?>"
                                    id="m-type" name="type_match" required>
                                    <option value="">Sélectionner un type de match</option>
                                    <?php foreach ($typesMatch as $typeMatch): ?>
                                        <option value="<?= (int) $typeMatch['id_type_match'] ?>"
                                            data-participants="<?= (int) $typeMatch['nombre_catcheurs_max'] ?>"
                                            data-camps="<?= (int) $typeMatch['nombre_camps'] ?>" <?= $idTypeMatch === (int) $typeMatch['id_type_match'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($typeMatch['libelle_type_match'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($erreurs['type_match'])): ?>
                                    <p class="field__error">
                                        <?= htmlspecialchars($erreurs['type_match']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="field">
                                <label class="field__label" for="m-ordre">Ordre dans le programme</label>
                                <input class="input<?= isset($erreurs['ordre']) ? ' is-invalid' : '' ?>" id="m-ordre"
                                    name="ordre" type="number" min="1" max="255" value="<?= $ordre > 0 ? $ordre : 1 ?>"
                                    required>
                                <?php if (isset($erreurs['ordre'])): ?>
                                    <p class="field__error">
                                        <?= htmlspecialchars($erreurs['ordre']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Catcheurs -->
                    <fieldset class="form-section">
                        <legend class="form-section__title">Catcheurs</legend>
                        <div class="participant-list" id="participant-list">
                            <?php foreach ($catcheurs as $index => $idCatcheur): ?>
                                <div class="participant">
                                    <span aria-hidden="true" class="participant__index">
                                        <?= $index + 1 ?>
                                    </span>
                                    <select class="select" name="catcheur[]" aria-label="Catcheur <?= $index + 1 ?>"
                                        required>
                                        <option value="">Sélectionner un catcheur</option>
                                        <?php foreach ($intervenants as $intervenant): ?>
                                            <option value="<?= (int) $intervenant['id_intervenant'] ?>" <?= (int) $idCatcheur === (int) $intervenant['id_intervenant'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($intervenant['nom_scene'], ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select class="select" name="camp[]" aria-label="Camp du catcheur <?= $index + 1 ?>"
                                        required>
                                        <option value="">Sélectionner un camp</option>
                                        <option value="1" <?= ($camps[$index] ?? '') === '1' ? 'selected' : '' ?>>
                                            Camp A
                                        </option>
                                        <option value="2" <?= ($camps[$index] ?? '') === '2' ? 'selected' : '' ?>>
                                            Camp B
                                        </option>
                                    </select>
                                    <button class="btn btn--ghost btn--sm participant-remove" type="button">Retirer</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (isset($erreurs['catcheurs'])): ?>
                            <p class="field__error">
                                <?= htmlspecialchars($erreurs['catcheurs']) ?>
                            </p>
                        <?php endif; ?>
                        <button class="btn btn--primary btn--sm participant-add" id="participant-add" type="button">
                            <span aria-hidden="true" class="btn__plus">
                                +
                            </span>
                            Ajouter un participant
                        </button>
                    </fieldset>

                    <!-- Arbitre -->
                    <fieldset class="form-section">
                        <legend class="form-section__title">Arbitre</legend>
                        <select class="select<?= isset($erreurs['arbitre']) ? ' is-invalid' : '' ?>" name="arbitre"
                            aria-label="Sélectionner un arbitre" required>
                            <option value="">Sélectionner un arbitre</option>
                            <?php foreach ($intervenants as $intervenant): ?>
                                <option value="<?= (int) $intervenant['id_intervenant'] ?>" <?= (int) $arbitre === (int) $intervenant['id_intervenant'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($intervenant['nom_scene'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($erreurs['arbitre'])): ?>
                            <p class="field__error">
                                <?= htmlspecialchars($erreurs['arbitre']) ?>
                            </p>
                        <?php endif; ?>
                    </fieldset>

                    <!-- Managers -->
                    <fieldset class="form-section">
                        <legend class="form-section__title">
                            Managers
                            <span class="optional">
                                (facultatif)
                            </span>
                        </legend>
                        <div class="participant-list" id="manager-list">
                            <?php foreach ($managers as $index => $idManager): ?>
                                <div class="participant participant--manager">
                                    <select class="select" name="manager[]" aria-label="Manager <?= $index + 1 ?>" required>
                                        <option value="">Sélectionner un manager</option>
                                        <?php foreach ($intervenants as $intervenant): ?>
                                            <option value="<?= (int) $intervenant['id_intervenant'] ?>" <?= (int) $idManager === (int) $intervenant['id_intervenant'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($intervenant['nom_scene'], ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select class="select" name="manager_camp[]"
                                        aria-label="Camp du manager <?= $index + 1 ?>" required>
                                        <option value="">
                                            Camp associé
                                        </option>
                                        <option value="1" <?= ($managerCamps[$index] ?? '') === '1' ? 'selected' : '' ?>>
                                            Camp A
                                        </option>
                                        <option value="2" <?= ($managerCamps[$index] ?? '') === '2' ? 'selected' : '' ?>>
                                            Camp B
                                        </option>
                                    </select>
                                    <button class="btn btn--ghost btn--sm manager-remove" type="button">Retirer</button>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Modèle utilisé par JavaScript -->
                        <div class="participant participant--manager" id="manager-template" hidden>
                            <select class="select" name="manager[]" aria-label="Manager" disabled required>
                                <option value="">Sélectionner un manager</option>
                                <?php foreach ($intervenants as $intervenant): ?>
                                    <option value="<?= (int) $intervenant['id_intervenant'] ?>">
                                        <?= htmlspecialchars($intervenant['nom_scene'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <select class="select" name="manager_camp[]" aria-label="Camp du manager" disabled required>
                                <option value="">Camp associé</option>
                                <option value="1">Camp A</option>
                                <option value="2">Camp B</option>
                            </select>
                            <button class="btn btn--ghost btn--sm manager-remove" type="button">Retirer</button>
                        </div>
                        <button class="btn btn--primary btn--sm participant-add" id="manager-add" type="button">
                            <span aria-hidden="true" class="btn__plus">
                                +
                            </span>
                            Ajouter un manager
                        </button>
                    </fieldset>
                </div>
                <div class="panel__foot">
                    <div class="admin-form-actions">
                        <a class="btn btn--ghost" href="/admin/evenement.php?id=<?= $idEvenement ?>">
                            Annuler
                        </a>
                        <button class="btn btn--primary" type="submit">
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </section>
        </form>
    </div>
</main>