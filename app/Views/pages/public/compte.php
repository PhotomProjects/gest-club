<main id="main-content" class="page" tabindex="-1">
    <div class="container">
        <div class="profile-header">
            <div class="profile-header__id">
                <h1>
                    <?= htmlspecialchars(
                        $utilisateurConnecte['prenom'] . ' ' . $utilisateurConnecte['nom']
                    ) ?>
                </h1>
                <p>
                    <?= htmlspecialchars($utilisateurConnecte['email']) ?>
                </p>
            </div>
            <form action="/deconnexion.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <button type="submit" class="btn btn--ghost">
                    <img class="icon" src="/assets/images/icons/log-out.svg" width="20" height="20" alt=""
                        aria-hidden="true">
                    <span>Se déconnecter</span>
                </button>
            </form>
        </div>

        <section class="account-links">
            <h2>Accès rapide</h2>
            <div>
                <a class="link-list__item" href="/mes-reservations.php">Mes réservations</a>
                <a class="link-list__item" href="/compte-informations.php">Informations personnelles</a>
                <a class="link-list__item" href="/compte-securite.php">Mot de passe & sécurité</a>
            </div>
        </section>

    </div>
</main>