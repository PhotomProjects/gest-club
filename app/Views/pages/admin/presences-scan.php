<main id="main-content" class="admin-content" tabindex="-1">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Interface de contrôle</h1>
        </div>
    </div>
    <?php if ($messageControle !== null): ?>
        <div class="notice" role="status">
            <p>
                <?= htmlspecialchars($messageControle) ?>
            </p>
        </div>
    <?php endif; ?>
    <div class="ticket-control-layout">
        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Scanner un QR code</h2>
            </div>
            <div class="panel__body">
                <div class="scan-preview" id="camera-preview">
                    <span id="camera-placeholder">Aperçu caméra</span>
                    <video id="camera-video" autoplay playsinline muted hidden></video>
                </div>
                <p class="field__error" id="camera-error" role="alert" hidden></p>
                <button class="btn btn--primary scan-action" id="camera-button" type="button">
                    Activer la caméra
                </button>
            </div>
        </section>

        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Saisie manuelle</h2>
            </div>
            <div class="panel__body">
                <p class="scan-help" id="code-help">
                    Scannez le QR code ou saisissez la référence affichée sur la réservation.
                </p>
                <form class="form" id="ticket-search-form" action="/admin/presences-resultat.php" method="post"
                    novalidate>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <input type="hidden" name="action" value="rechercher">
                    <div class="field">
                        <label class="field__label" for="code">Code QR ou référence de réservation</label>
                        <input class="input <?= $erreurControle !== null ? 'is-invalid' : '' ?>" type="text" id="code"
                            name="code" value="<?= htmlspecialchars($codeControle) ?>" placeholder="Ex. R-2026-000014"
                            aria-describedby="<?= $erreurControle !== null ? 'code-help code-error' : 'code-help' ?>"
                            required>
                        <?php if ($erreurControle !== null): ?>
                            <p class="field__error" id="code-error" role="alert">
                                <?= htmlspecialchars($erreurControle) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <button class="btn btn--primary" type="submit">Rechercher le billet</button>
                </form>
            </div>
        </section>

    </div>
</main>