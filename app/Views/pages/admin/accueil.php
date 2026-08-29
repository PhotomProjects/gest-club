<main class="admin-content">
    <h1 class="visually-hidden">Vue d'ensemble</h1>
    <div class="dashboard-layout">
        <div class="dashboard-panels">
            <section class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">Prochains évènements</h2>
                </div>
                <div class="panel__body">
                    <div class="empty-state">
                        <p class="empty-state__title">Aucun évènement à venir</p>
                        <p>Les prochains évènements apparaîtront ici dès qu'ils seront créés.</p>
                    </div>
                </div>
            </section>

            <section class="panel">
                <div class="panel__head">
                    <h2 class="panel__title">Réservations récentes</h2>
                </div>
                <div class="panel__body">
                    <div class="empty-state">
                        <p class="empty-state__title">Aucune réservation récente</p>
                        <p>Les nouvelles réservations apparaîtront ici.</p>
                    </div>
                </div>
            </section>

        </div>
        <div class="quick-actions">
            <a class="btn btn--primary btn--lg" href="/admin/evenement-form.php">
                <span class="btn__plus" aria-hidden="true">+</span>
                Créer un évènement
            </a>
            <a class="btn btn--secondary btn--lg" href="/admin/presences-scan.php">Contrôler un billet</a>
        </div>
    </div>
</main>