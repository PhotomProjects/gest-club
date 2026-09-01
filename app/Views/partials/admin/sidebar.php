<aside class="admin-sidebar">
    <a class="admin-brand" href="/admin/">
        <img src="/assets/images/gest-club-logo.png" alt="">
        <span>Lucha Tick'Est</span>
    </a>
    <nav class="admin-nav" aria-label="Navigation de gestion">
        <a class="admin-nav__link<?= $adminSection === 'dashboard' ? ' is-active' : '' ?>" href="/admin/"
            <?= $adminSection === 'dashboard' ? 'aria-current="page"' : '' ?>>
            Vue d'ensemble
        </a>
        <a class="admin-nav__link<?= $adminSection === 'intervenants' ? ' is-active' : '' ?>"
            href="/admin/intervenants.php" <?= $adminSection === 'intervenants' ? 'aria-current="page"' : '' ?>>
            Intervenants
        </a>
        <a class="admin-nav__link<?= $adminSection === 'evenements' ? ' is-active' : '' ?>" href="/admin/evenements.php"
            <?= $adminSection === 'evenements' ? 'aria-current="page"' : '' ?>>
            Évènements
        </a>
        <a class="admin-nav__link<?= $adminSection === 'reservations' ? ' is-active' : '' ?>"
            href="/admin/reservations.php" <?= $adminSection === 'reservations' ? 'aria-current="page"' : '' ?>>
            Réservations
        </a>
        <a class="admin-nav__link<?= $adminSection === 'presences' ? ' is-active' : '' ?>" href="/admin/presences.php"
            <?= $adminSection === 'presences' ? 'aria-current="page"' : '' ?>>
            Contrôle des billets
        </a>
        <a class="admin-nav__link<?= $adminSection === 'utilisateurs' ? ' is-active' : '' ?>"
            href="/admin/utilisateurs.php" <?= $adminSection === 'utilisateurs' ? 'aria-current="page"' : '' ?>>
            Utilisateurs
        </a>
    </nav>
    <div class="admin-sidebar__footer">
        <form action="/deconnexion.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <button class="admin-nav__link admin-logout" type="submit">Déconnexion</button>
        </form>
    </div>
</aside>