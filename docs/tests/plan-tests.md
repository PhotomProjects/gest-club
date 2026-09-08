# Plan de tests — GEST CLUB

## 1. Objectif

Ce document recense les principaux tests réalisés sur l'application GEST CLUB.

Les tests sont répartis en trois catégories :

- tests automatisés avec PHPUnit ;
- tests fonctionnels manuels ;
- tests de sécurité manuels.

L'objectif est de vérifier les principales règles métier, le fonctionnement des composants serveur, la persistance des données et les protections mises en place.

---

## 2. Environnement de test

Les tests automatisés sont exécutés avec PHPUnit dans le conteneur PHP de l’application.

Deux bases de données distinctes sont utilisées :

- Base de développement : `gest_club`
- Base de test : `gest_club_test`

Cette séparation évite que les tests automatisés modifient les données utilisées pendant le développement ou les démonstrations.

Le fichier `tests/bootstrap.php` configure automatiquement l’environnement de test :

```php
putenv('APP_ENV=test');
putenv('DB_DATABASE=gest_club_test');

date_default_timezone_set(
    getenv('APP_TIMEZONE') ?: 'Europe/Paris'
);
```

Ainsi, lorsque PHPUnit est lancé, les repositories et les services utilisent la base `gest_club_test`. Le fuseau horaire est également défini explicitement afin de rendre fiables les tests portant sur les dates.

### 2.1 Création de la base de test

La base `gest_club_test` doit être créée une première fois dans MariaDB.

Connexion à MariaDB avec le compte administrateur :

```bash
docker compose exec database sh -c 'mariadb -uroot -p"$MARIADB_ROOT_PASSWORD"'
```

Explication de la commande :

- `docker compose exec database` exécute une commande dans le conteneur du service `database` ;
- `sh -c` permet au shell du conteneur de lire les variables d’environnement ;
- `mariadb` lance le client MariaDB ;
- `-uroot` sélectionne le compte administrateur ;
- `-p"$MARIADB_ROOT_PASSWORD"` utilise le mot de passe défini dans l’environnement Docker sans l’écrire directement dans la commande.

Dans le terminal MariaDB, les commandes suivantes sont exécutées :

```sql
CREATE DATABASE IF NOT EXISTS gest_club_test
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON gest_club_test.* TO 'gest_club'@'%';

FLUSH PRIVILEGES;

SHOW GRANTS FOR 'gest_club'@'%';

EXIT;
```

Explication des commandes SQL :

- `CREATE DATABASE IF NOT EXISTS` crée la base uniquement si elle n’existe pas déjà ;
- `utf8mb4` permet de prendre en charge l’ensemble des caractères Unicode ;
- `GRANT ALL PRIVILEGES ON gest_club_test.*` autorise l’utilisateur de l’application à utiliser uniquement la base de test ;
- `FLUSH PRIVILEGES` applique immédiatement les droits ;
- `SHOW GRANTS` permet de vérifier les autorisations accordées ;
- `EXIT` ferme le client MariaDB.

Cette opération corrige notamment l’erreur suivante :

```text
SQLSTATE[HY000] [1044]
Access denied for user 'gest_club'@'%' to database 'gest_club_test'
```

### 2.2 Importation du schéma

Il est possible de vérifier si la base contient déjà les tables :

```bash
docker compose exec database sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" -e "SHOW TABLES FROM gest_club_test;"'
```

L’option `-e` exécute directement la requête SQL puis ferme le client MariaDB.

Si aucune table n’est présente, le schéma est importé avec la commande suivante :

```bash
docker compose exec -T database sh -c 'mariadb -uroot -p"$MARIADB_ROOT_PASSWORD" gest_club_test' < database/schema.sql
```

Explication de la commande :

- `-T` désactive le pseudo-terminal afin que la redirection fonctionne correctement ;
- `gest_club_test` désigne la base dans laquelle le schéma sera importé ;
- `< database/schema.sql` envoie le contenu du fichier SQL local vers MariaDB.

Les données de démonstration ne sont pas nécessaires. Chaque test d’intégration prépare ses propres données, puis les supprime après son exécution.

La création de la base et l’attribution des droits ne sont nécessaires qu’une seule fois tant que le volume Docker de MariaDB est conservé. Si ce volume est supprimé, cette préparation doit être répétée.

### 2.3 Exécution de PHPUnit

La suite complète est exécutée avec :

```bash
docker compose exec app vendor/bin/phpunit
```

Cette commande :

- exécute PHPUnit dans le conteneur `app` ;
- utilise la configuration définie dans `phpunit.xml` ;
- charge le fichier `tests/bootstrap.php` ;
- sélectionne automatiquement la base `gest_club_test` ;
- exécute les tests unitaires et les tests d’intégration.

Le résultat attendu est :

```text
Tests: 13
Errors: 0
Failures: 0
OK
```

---

# 3. Tests automatisés

## 3.1 Tests unitaires

| ID     | Composant | Scénario                                    | Résultat attendu            | Résultat |
| ------ | --------- | ------------------------------------------- | --------------------------- | -------- |
| AUT-01 | Auth      | Aucun utilisateur fourni                    | Utilisateur non authentifié | Conforme |
| AUT-02 | Auth      | Utilisateur avec identifiant et rôle        | Utilisateur authentifié     | Conforme |
| AUT-03 | Auth      | Utilisateur avec identifiant mais sans rôle | Utilisateur non authentifié | Conforme |

## 3.2 Tests d'intégration

| ID     | Composant             | Scénario                                                                      | Résultat attendu                                                              | Résultat |
| ------ | --------------------- | ----------------------------------------------------------------------------- | ----------------------------------------------------------------------------- | -------- |
| AUT-04 | UtilisateurRepository | Création puis recherche d'un utilisateur                                      | Les données enregistrées puis relues sont cohérentes                          | Conforme |
| AUT-05 | ReservationService    | Réservation de 2 places disponibles                                           | Réservation confirmée, 2 places réservées et billet créé                      | Conforme |
| AUT-06 | ReservationService    | L'utilisateur possède déjà 2 places et tente d'en réserver une supplémentaire | La nouvelle réservation est refusée et la réservation existante reste intacte | Conforme |
| AUT-07 | ReservationService    | Une seule place disponible pour une demande de 2 places                       | La réservation est refusée sans création partielle de données                 | Conforme |
| AUT-08 | PresenceService       | Valider un billet utilisable                                                  | Une présence est créée                                                        | Conforme |
| AUT-09 | PresenceService       | Scanner deux fois le même billet                                              | Le second contrôle est refusé et aucune deuxième présence n’est créée         | Conforme |
| AUT-10 | PresenceService       | Scanner un billet lié à une réservation annulée                               | L’entrée est refusée                                                          | Conforme |
| AUT-11 | EvenementService      | Créer un événement futur                                                      | L’événement et ses places sont créés                                          | Conforme |
| AUT-12 | EvenementService      | Créer un événement à une date passée                                          | La création est refusée et aucun événement n’est enregistré                   | Conforme |
| AUT-13 | EvenementService      | Annuler complètement un événement                                             | L’événement et ses réservations sont annulés, puis les places sont libérées   | Conforme |

---

# 4. Tests fonctionnels manuels

| ID     | Fonctionnalité     | Scénario                                      | Résultat attendu                                                     | Résultat |
| ------ | ------------------ | --------------------------------------------- | -------------------------------------------------------------------- | -------- |
| FCT-01 | Inscription        | Création d'un compte avec des données valides | Compte créé                                                          | Conforme |
| FCT-02 | Inscription        | Adresse e-mail déjà utilisée                  | Création refusée avec message d'erreur                               | Conforme |
| FCT-03 | Connexion          | Identifiants corrects                         | Connexion réussie                                                    | Conforme |
| FCT-04 | Connexion          | Mot de passe incorrect                        | Connexion refusée                                                    | Conforme |
| FCT-05 | Compte             | Modification des informations personnelles    | Informations enregistrées                                            | Conforme |
| FCT-06 | Événement admin    | Création d'un événement                       | Événement créé et places associées générées                          | Conforme |
| FCT-07 | Événement admin    | Création avec une image JPG/PNG valide        | Événement et image enregistrés                                       | Conforme |
| FCT-08 | Événement admin    | Modification d'un événement futur             | Modifications enregistrées                                           | Conforme |
| FCT-09 | Événement admin    | Annulation d'un événement                     | Événement et réservations concernées annulés                         | Conforme |
| FCT-10 | Match admin        | Création d'un match valide                    | Match et participations enregistrés                                  | Conforme |
| FCT-11 | Match admin        | Modification d'un match                       | Modifications enregistrées                                           | Conforme |
| FCT-12 | Match admin        | Suppression d'un match                        | Match supprimé                                                       | Conforme |
| FCT-13 | Intervenant admin  | Création d'un intervenant                     | Intervenant créé                                                     | Conforme |
| FCT-14 | Intervenant admin  | Passage d'un intervenant en INACTIF           | Intervenant conservé mais non proposé dans les nouveaux matchs       | Conforme |
| FCT-15 | Utilisateur admin  | Passage MEMBRE vers ADMIN                     | Nouveau rôle appliqué                                                | Conforme |
| FCT-16 | Utilisateur admin  | Consultation de la fiche utilisateur          | Informations et réservations affichées                               | Conforme |
| FCT-17 | Réservation        | Réservation d'une place disponible            | Réservation confirmée et billet créé                                 | Conforme |
| FCT-18 | Réservation        | Réservation de deux places disponibles        | Deux places attribuées et total correct                              | Conforme |
| FCT-19 | Réservation        | Annulation d'une réservation                  | Réservation annulée et places libérées                               | Conforme |
| FCT-20 | Mes réservations   | Consultation des réservations du compte       | Réservations correctes affichées                                     | Conforme |
| FCT-21 | Billet             | Consultation d'un billet actif                | Billet et QR Code affichés                                           | Conforme |
| FCT-22 | Présence           | Premier contrôle d'un billet valide           | Présence enregistrée                                                 | Conforme |
| FCT-23 | Réservations admin | Consultation de la liste globale              | Réservations affichées avec utilisateur, événement, places et statut | Conforme |
| FCT-24 | Réservation admin  | Consultation du détail                        | Utilisateur, événement, places, billet et présence affichés          | Conforme |
| FCT-25 | Overview admin     | Consultation du dashboard                     | Événements à venir et réservations récentes affichés                 | Conforme |

---

# 5. Tests de sécurité manuels

| ID     | Scénario                                                          | Résultat attendu                             | Résultat |
| ------ | ----------------------------------------------------------------- | -------------------------------------------- | -------- |
| SEC-01 | Un membre accède directement à une page `/admin/`                 | Accès refusé et page 403                     | Conforme |
| SEC-02 | Suppression du token CSRF d'un formulaire POST                    | Requête refusée                              | Conforme |
| SEC-03 | Modification du token CSRF                                        | Requête refusée                              | Conforme |
| SEC-04 | Accès à une ressource avec un identifiant inexistant              | Réponse 404                                  | Conforme |
| SEC-05 | Accès à une URL inexistante                                       | Page 404 avec code HTTP 404                  | Conforme |
| SEC-06 | Admin rétrogradé en MEMBRE alors que sa session est ouverte       | Perte des droits admin à la requête suivante | Conforme |
| SEC-07 | Tentative de rétrogradation du dernier administrateur             | Modification refusée                         | Conforme |
| SEC-08 | Accès direct au formulaire de modification d'un événement annulé  | Accès refusé                                 | Conforme |
| SEC-09 | Accès direct au formulaire de modification d'un événement terminé | Accès refusé                                 | Conforme |
| SEC-10 | Double contrôle du même billet                                    | Deuxième contrôle refusé                     | Conforme |
| SEC-11 | Contrôle d'un billet annulé                                       | Contrôle refusé                              | Conforme |
| SEC-12 | Réservation sur un événement annulé ou terminé                    | Réservation refusée côté serveur             | Conforme |
| SEC-13 | Mot de passe ne respectant pas les règles définies                | Validation serveur refusée                   | Conforme |

---

# 6. Bilan

La suite automatisée contient treize tests :

- trois tests unitaires consacrés au composant d’authentification ;
- dix tests d’intégration consacrés aux repositories et aux services métier.

Les tests d’intégration utilisent la base isolée gest_club_test. Ils vérifient notamment :

- la création et la recherche d’un utilisateur ;
- les règles de réservation et la gestion du nombre de places ;
- la validation d’un billet et la protection contre le double passage ;
- le refus d’une réservation annulée ;
- la validation des dates d’un événement ;
- la génération automatique des places ;
- l’annulation complète d’un événement, de ses réservations et la libération de ses places.

Les tests automatisés sont complétés par des tests fonctionnels et des tests de sécurité réalisés manuellement depuis l’interface web.

L’exécution finale de PHPUnit donne treize tests réussis sur treize, sans erreur ni échec. L’ensemble valide les principales règles métier de l’application ainsi que l’isolation de l’environnement de test.
