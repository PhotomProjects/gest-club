<main class="admin-content">
    <div class="admin-header">
        <div class="admin-header__text">
            <h1>Interface de contrôle</h1>
        </div>
    </div>
    <div class="ticket-control-layout">
        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Scanner un QR code</h2>
            </div>
            <div class="panel__body">
                <div class="scan-preview" id="camera-preview">
                    <span id="camera-placeholder">Aperçu caméra</span>
                    <video id="camera-video" autoplay playsinline hidden></video>
                </div>
                <p class="field__error" id="camera-error" hidden></p>
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
                    Utilisez le numéro du billet ou de la réservation lorsque la caméra n'est pas disponible.
                </p>
                <form class="form" action="#" method="post">
                    <div class="field">
                        <label class="field__label" for="code">N° du billet ou de la réservation</label>
                        <input class="input" type="text" id="code" name="code" placeholder="Ex. BIL-01170 ou R-2026-108"
                            aria-describedby="code-help" required>
                    </div>
                    <button class="btn btn--primary" type="submit">Rechercher le billet</button>
                </form>
            </div>
        </section>

    </div>
</main>