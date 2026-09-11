<?php
$currentSection = $currentSection ?? null;
$isAuthenticated = $isAuthenticated ?? false;
?>

<nav class="main-nav" aria-label="Navigation principale">
    <a class="main-nav__link<?= $currentSection === 'home' ? ' is-active' : '' ?>" href="/" <?= $currentSection === 'home' ? 'aria-current="page"' : '' ?>>
        Accueil
    </a>

    <a class="main-nav__link<?= $currentSection === 'events' ? ' is-active' : '' ?>" href="/evenements.php"
        <?= $currentSection === 'events' ? 'aria-current="page"' : '' ?>>
        Évènements
    </a>
</nav>

<div class="header-actions">
    <?php if ($isAuthenticated): ?>
        <a class="btn btn--ghost btn--sm header-account" href="/compte.php" aria-label="Mon compte">
            <img class="icon icon--button" src="/assets/images/icons/circle-user-round.svg" width="24" height="24" alt=""
                aria-hidden="true">
            <span class="header-account__label">Mon compte</span>
        </a>
    <?php else: ?>
        <a class="btn btn--secondary btn--sm header-account" href="/connexion.php" aria-label="Se connecter">
            <img class="icon icon--button" src="/assets/images/icons/circle-user-round.svg" width="24" height="24" alt=""
                aria-hidden="true">
            <span class="header-account__label">Se connecter</span>
        </a>
    <?php endif; ?>
</div>

<nav id="mobile-menu" class="mobile-menu" aria-label="Navigation mobile">
    <div class="mobile-menu__head">
        <span class="mobile-menu__title">Menu</span>
        <label class="mobile-menu__close" for="nav-toggle">X<span class="visually-hidden">Fermer le menu</span></label>
    </div>
    <ul class="mobile-menu__list">
        <li><a class="mobile-menu__item" href="/">Accueil</a></li>
        <li><a class="mobile-menu__item" href="/evenements.php">Évènements</a></li>
        <?php if ($isAuthenticated): ?>
            <li><a class="mobile-menu__item" href="/mes-reservations.php">Mes réservations</a></li>
            <li><a class="mobile-menu__item" href="/compte.php">Compte</a></li>
            <li>
                <form action="/deconnexion.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <button class="mobile-menu__item mobile-menu__logout" type="submit">
                        Se déconnecter
                    </button>
                </form>
            </li>
        <?php else: ?>
            <li><a class="mobile-menu__item" href="/connexion.php">Se connecter</a></li>
        <?php endif; ?>
    </ul>
</nav>