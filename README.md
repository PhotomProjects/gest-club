# GEST CLUB

GEST CLUB est une application web de gestion d'événements pour un club de catch fictif. L'interface publique, présentée sous le nom **Lucha Tick'Est**, permet aux spectateurs de consulter les événements, de réserver une ou deux places et d'obtenir un billet unique avec QR code.

Une interface d'administration permet de gérer les événements, leur programme, les intervenants, les réservations, les utilisateurs et le contrôle des présences.

Le projet est réalisé dans le cadre de la préparation au titre professionnel **Développeur Web et Web Mobile (DWWM)**.

## Fonctionnalités de la V1

### Espace spectateur

- consulter la liste et le détail des événements ;
- créer un compte et se connecter ;
- modifier ses informations personnelles et son mot de passe ;
- réserver une ou deux places selon les disponibilités ;
- choisir une tribune et un niveau, les places exactes étant attribuées automatiquement ;
- identifier les tribunes ou niveaux complets avant la confirmation ;
- consulter et annuler ses réservations avant le début de l'événement ;
- obtenir un billet et un QR code uniques couvrant toute la réservation.

### Espace d'administration

- consulter un tableau de bord simplifié ;
- créer, modifier, consulter et annuler un événement ;
- programmer les matchs d'un événement ;
- ajouter, modifier et désactiver des intervenants ;
- consulter les réservations et les utilisateurs ;
- modifier le rôle d'un utilisateur ;
- scanner un QR code ou saisir manuellement un code de billet ;
- enregistrer et consulter les présences.

### Hors périmètre

- paiement en ligne ou sur place ;
- rôle de super-administrateur ;
- fonctionnalités sociales, votes et notations ;
- brouillons d'événements ;
- statistiques avancées ou en temps réel ;
- historique des contrôles refusés ;
- procédure de mot de passe oublié ;
- déploiement en production.

## Règles métier principales

- une réservation contient une ou deux places au maximum ;
- un utilisateur ne peut pas réserver plus de deux places pour un même événement ;
- une réservation produit un seul billet et un seul QR code ;
- les disponibilités sont vérifiées dans une transaction avant la confirmation ;
- les places sélectionnées sont verrouillées pendant la transaction afin de limiter les réservations concurrentes ;
- l'annulation d'une réservation libère ses places ;
- l'annulation d'un événement annule ses réservations et libère les places associées ;
- un billet ne peut être contrôlé qu'une seule fois et pendant la période de l'événement ;
- un événement passé, annulé ou complet ne peut plus être réservé.

## Architecture

Le projet utilise une architecture PHP monolithique en couches, volontairement simple et adaptée à un projet individuel.

Chaque fichier de `public/` agit comme point d'entrée HTTP : il charge l'application, contrôle la requête, appelle les composants nécessaires, puis affiche une vue ou effectue une redirection.

```text
Requête HTTP
    -> script public
    -> service métier
    -> repository
    -> PDO / MariaDB
    -> vue PHP ou redirection
```

Responsabilités principales :

- `public/` : points d'entrée HTTP et contrôle d'accès ;
- `app/Core/` : connexion PDO, authentification et protection CSRF ;
- `app/Repositories/` : accès aux données et requêtes SQL ;
- `app/Services/` : règles métier et transactions ;
- `app/Views/` : présentation HTML/PHP sans accès direct à la base ;
- `config/` : configuration et initialisation communes.

## Arborescence principale

```text
gest-club/
|-- app/
|   |-- Core/
|   |-- Repositories/
|   |-- Services/
|   `-- Views/
|       |-- layouts/
|       |-- pages/
|       `-- partials/
|-- config/
|-- database/
|   |-- schema.sql
|   `-- seed.sql
|-- docker/
|   |-- apache/
|   `-- php/
|-- docs/
|-- public/
|   |-- admin/
|   `-- assets/
|-- tests/
|   |-- Integration/
|   `-- Unit/
|-- compose.yaml
|-- composer.json
|-- phpunit.xml
`-- README.md
```

Seul le répertoire `public/` est exposé par Apache.

## Base de données

La V1 repose sur une base relationnelle MariaDB composée de douze tables :

1. `UTILISATEUR`
2. `EVENEMENT`
3. `PLACE`
4. `PLACE_EVENEMENT`
5. `INTERVENANT`
6. `TYPE_MATCH`
7. `MATCH_EVENEMENT`
8. `PARTICIPATION_MATCH`
9. `RESERVATION`
10. `RESERVATION_PLACE`
11. `BILLET`
12. `PRESENCE`

La confirmation d'une réservation est transactionnelle : l'événement et les disponibilités sont revérifiés, les places sont verrouillées, le quota est contrôlé, puis la réservation et le billet sont créés dans une même transaction.

Les fichiers SQL sont séparés :

- `database/schema.sql` crée la structure de la base ;
- `database/seed.sql` ajoute les données locales de démonstration.

## Stack technique

- PHP 8.3 ;
- Apache 2 ;
- MariaDB 11.4 avec InnoDB et `utf8mb4` ;
- PDO et requêtes préparées ;
- Composer et autoload PSR-4 ;
- PHPUnit 12 ;
- HTML5, CSS et JavaScript natif ;
- Docker Compose ;
- phpMyAdmin pour l'administration locale de la base.

## Installation locale

Les commandes utilisant une redirection avec `<` peuvent être exécutées depuis Bash ou Git Bash.

### Prérequis

- Git ;
- Docker Desktop ou Docker Engine avec Docker Compose.

### 1. Récupération du projet

```bash
git clone https://github.com/PhotomProjects/gest-club
cd gest-club
cp .env.example .env
```

Les identifiants locaux peuvent ensuite être adaptés dans `.env`. Ce fichier contient des informations sensibles et ne doit pas être versionné.

### 2. Démarrage des conteneurs

```bash
docker compose up -d --build
docker compose ps
```

Attendre que le service `database` soit indiqué comme sain (`healthy`).

### 3. Installation des dépendances PHP

```bash
docker compose exec app composer install
```

### 4. Importation du schéma

```bash
docker compose exec -T database sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' < database/schema.sql
```

L'option `-T` désactive le pseudo-terminal afin que la redirection du fichier SQL fonctionne correctement.

### 5. Importation des données de démonstration

```bash
docker compose exec -T database sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' < database/seed.sql
```

Le schéma doit être importé avant le jeu de données.

### 6. Accès à l'application

Adresses prévues par `.env.example` :

- application : `http://localhost:8080` ;
- phpMyAdmin : `http://localhost:8081`.

## Comptes de démonstration

Ces comptes sont exclusivement destinés à l'environnement local :

| Rôle           | Adresse électronique   | Mot de passe  |
| -------------- | ---------------------- | ------------- |
| Administrateur | `admin@gest-club.test` | `gestclub123` |
| Membre         | `david@gest-club.test` | `gestclub123` |

D'autres membres sont présents dans le jeu de données afin d'alimenter les réservations de démonstration.

## Tests automatisés

La suite PHPUnit contient des tests unitaires et des tests d'intégration :

- authentification et autorisations ;
- création et recherche d'un utilisateur ;
- réservation d'une ou deux places ;
- quota maximal de deux places ;
- refus lorsque les places sont insuffisantes ;
- validation et double contrôle d'un billet ;
- refus d'un billet annulé ou contrôlé hors période ;
- création d'un événement et génération de ses places ;
- validation des dates ;
- annulation complète d'un événement et libération des places.

### Base dédiée aux tests

Les tests d'intégration utilisent `gest_club_test` afin de ne pas modifier les données de développement.

La base et ses droits doivent être préparés une première fois. Connexion à MariaDB :

```bash
docker compose exec database sh -c 'mariadb -uroot -p"$MARIADB_ROOT_PASSWORD"'
```

Dans le terminal MariaDB :

```sql
CREATE DATABASE IF NOT EXISTS gest_club_test
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON gest_club_test.* TO 'gest_club'@'%';

FLUSH PRIVILEGES;

SHOW GRANTS FOR 'gest_club'@'%';

EXIT;
```

Si `DB_USERNAME` a été modifié dans `.env`, le nom `gest_club` doit être remplacé dans les commandes `GRANT` et `SHOW GRANTS`.

Importation du schéma dans la base de test :

```bash
docker compose exec -T database sh -c 'mariadb -uroot -p"$MARIADB_ROOT_PASSWORD" gest_club_test' < database/schema.sql
```

Le seed de démonstration n'est pas nécessaire : les tests d'intégration préparent et nettoient leurs propres données.

Cette préparation reste valable tant que le volume Docker de MariaDB est conservé.

### Exécution de PHPUnit

```bash
docker compose exec app vendor/bin/phpunit
```

Le fichier `tests/bootstrap.php` sélectionne automatiquement `gest_club_test` et configure le fuseau horaire des tests. Le résultat attendu est de treize tests réussis, sans erreur ni échec.

Les scénarios fonctionnels et de sécurité réalisés manuellement sont documentés dans `docs/tests/plan-tests.md`.

## Sécurité mise en œuvre

- mots de passe enregistrés avec `password_hash()` et vérifiés avec `password_verify()` ;
- requêtes préparées PDO ;
- protection CSRF des actions sensibles ;
- contrôle de l'authentification et du rôle administrateur ;
- renouvellement de l'identifiant de session après connexion ;
- cookies de session `HttpOnly` et `SameSite=Lax` ;
- échappement des données affichées dans les vues ;
- validation des données côté serveur ;
- transactions et verrouillage des places pendant une réservation ;
- pages dédiées pour les réponses HTTP 403 et 404.

## Commandes utiles

Afficher l'état des services :

```bash
docker compose ps
```

Afficher les logs de l'application :

```bash
docker compose logs app
```

Suivre les logs en temps réel :

```bash
docker compose logs -f app
```

Arrêter les conteneurs sans supprimer les données :

```bash
docker compose down
```

## Dépannage

### Accès refusé après une modification de `.env`

La modification de `DB_PASSWORD` ou de `DB_ROOT_PASSWORD` dans `.env` ne met pas automatiquement à jour les comptes déjà enregistrés dans le volume MariaDB.

Si MariaDB affiche une erreur similaire à :

```text
ERROR 1045 (28000): Access denied for user
```

et que les données locales peuvent être supprimées, réinitialiser le volume :

```bash
docker compose down --volumes --remove-orphans
docker compose up -d
docker compose ps
```

Attendre que `database` soit indiqué comme sain, puis réimporter `database/schema.sql` et `database/seed.sql` avec les commandes d'installation.

Cette opération supprime définitivement les bases `gest_club` et `gest_club_test` contenues dans le volume local. La base de test et ses droits doivent ensuite être recréés.

### Réinitialisation complète des données locales

```bash
docker compose down -v
docker compose up -d
```

Cette commande supprime le volume MariaDB. Le schéma, le seed et la base de test doivent ensuite être réimportés.

## Auteur

Projet de formation réalisé par Florian Authelin.
