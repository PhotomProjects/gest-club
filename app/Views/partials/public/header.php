<header class="site-header">
    <div class="container site-header__inner">
        <input class="nav-toggle" type="checkbox" id="nav-toggle" aria-label="Menu de navigation"
            aria-controls="mobile-menu">
        <label class="burger" for="nav-toggle">
            <img class="icon icon--button" src="/assets/images/icons/menu.svg" width="24" height="24" alt=""
                aria-hidden="true">
            <span class="visually-hidden">Ouvrir le menu</span>
        </label>
        <a class="brand" href="/">
            <img class="brand__logo" src="/assets/images/gest-club-logo.png" alt="">
            <span class="brand__name">Lucha Tick'Est</span>
        </a>
        <?php require __DIR__ . '/navigation.php'; ?>
    </div>
</header>