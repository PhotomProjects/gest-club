<main id="main-content" class="admin-content" tabindex="-1">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Annuler l'évènement</h1>
            <p class="admin-header__description">
                Cette action annulera également les réservations confirmées associées.
            </p>
        </div>
    </div>
    <?php if ($erreur !== null): ?>
        <div class="notice">
            <p>
                <?= htmlspecialchars($erreur) ?>
            </p>
        </div>
    <?php endif; ?>

    <section class="panel">
        <div class="panel__head">
            <h2 class="panel__title">Confirmer l'annulation</h2>
        </div>
        <div class="panel__body">
            <p>
                Vous êtes sur le point d'annuler
                <strong>
                    <?= htmlspecialchars($evenement['nom_evenement'], ENT_QUOTES, 'UTF-8') ?>
                </strong>.
            </p>
            <dl class="kv">
                <div class="kv__row">
                    <dt class="kv__key">Date</dt>
                    <dd class="kv__value">
                        <time datetime="<?= $dateDebut->format('Y-m-d\TH:i') ?>">
                            <?= $dateDebut->format('d/m/Y · H:i') ?>
                        </time>
                    </dd>
                </div>
            </dl>
        </div>
    </section>

    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <div class="admin-form-actions">
            <a class="btn btn--ghost" href="/admin/evenement.php?id=<?= $id ?>">Retour</a>
            <button class="btn btn--danger" type="submit">Confirmer l'annulation</button>
        </div>
    </form>
</main>