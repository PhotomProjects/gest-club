<main id="main-content" class="admin-content" tabindex="-1">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Supprimer le match</h1>
            <p class="admin-header__description">
                Cette action supprimera le match du programme.
            </p>
        </div>
    </div>
    <div class="admin-centered admin-centered--wide">
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
            <?php if ($erreur !== null): ?>
                <div class="notice">
                    <p>
                        <?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endif; ?>
            <section class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">Confirmer la suppression</h2>
                </div>
                <div class="panel__body">
                    <p>
                        Voulez-vous vraiment supprimer le match
                        <strong>
                            <?= htmlspecialchars($match['nom_match'], ENT_QUOTES, 'UTF-8') ?>
                        </strong>
                        ?
                    </p>
                    <p>Les participants associés à ce match seront également supprimés du programme.</p>
                </div>
                <div class="panel__foot">
                    <div class="admin-form-actions">
                        <a class="btn btn--ghost" href="/admin/evenement.php?id=<?= $idEvenement ?>">Annuler</a>
                        <button class="btn btn--primary" type="submit">Confirmer la suppression</button>
                    </div>
                </div>
            </section>
        </form>
    </div>
</main>