# GEST CLUB

GEST CLUB est une application web de gestion d'événements pour un club de catch fictif. Elle permet aux spectateurs de consulter les événements, de réserver une ou deux places et d'obtenir un billet unique avec QR code. Une interface de gestion permet d'administrer les événements, les intervenants, les réservations, les utilisateurs et les présences.

Le projet est réalisé dans le cadre de la formation au titre professionnel Développeur Web et Web Mobile (DWWM).

## Périmètre fonctionnel de la V1

### Espace spectateur

- consulter la liste et le détail des événements ;
- créer un compte et se connecter ;
- modifier ses informations personnelles et son mot de passe ;
- réserver une ou deux places selon les disponibilités ;
- consulter et annuler ses réservations avant le début de l'événement ;
- obtenir un billet unique et un QR code unique couvrant la réservation.

### Espace de gestion

- consulter un tableau de bord simplifié ;
- créer, modifier, consulter et annuler un événement ;
- programmer les matchs d'un événement ;
- ajouter et modifier les intervenants ;
- consulter les réservations et les utilisateurs ;
- contrôler manuellement un billet ou un QR code ;
- enregistrer et consulter les présences.

### Hors périmètre de la V1

- paiement en ligne ou sur place ;
- rôle de super-administrateur ;
- fonctionnalités sociales, votes et notations ;
- brouillons d'événements ;
- statistiques avancées ou temps réel ;
- historique des contrôles refusés ;
- procédure de mot de passe oublié.

## Architecture

Le projet utilise une architecture PHP monolithique en couches, volontairement simple et adaptée à un projet réalisé seul dans un délai contraint.

Chaque fichier de `public/` agit comme contrôleur de page : il charge l'application, contrôle la requête, appelle les composants nécessaires puis affiche une vue ou effectue une redirection.

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
- `app/Core/` : connexion PDO, authentification, session et protection CSRF ;
- `app/Repositories/` : accès aux données et requêtes SQL uniquement ;
- `app/Services/` : règles métier et pilotage des transactions ;
- `app/Views/` : présentation HTML/PHP sans accès direct à la base ;
- `config/` : initialisation commune et configuration de l'application.

## Arborescence principale

```text
gest-club/
|-- app/
|   |-- Core/
|   |-- Repositories/
|   |-- Services/
|   `-- Views/
|       |-- pages/
|       |   `-- admin/
|       `-- partials/
|-- config/
|-- database/
|-- docker/
|   |-- apache/
|   `-- php/
|-- docs/
|-- public/
|   |-- admin/
|   `-- assets/
|-- storage/
|   |-- cache/
|   `-- logs/
|-- tests/
|   |-- Integration/
|   `-- Unit/
|-- compose.yaml
`-- composer.json
```

Seul le répertoire `public/` doit être exposé par Apache.

## Base de données

La V1 repose sur une base relationnelle MariaDB composée de 12 tables :

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

`TYPE_MATCH` contient des valeurs de référence insérées par le jeu de données initial. Quelques intervenants sont également fournis pour la démonstration, mais ils restent administrables depuis l'espace de gestion.

La confirmation d'une réservation doit être transactionnelle : disponibilités revérifiées, places verrouillées, quota de deux places contrôlé, réservation créée et billet généré dans une même transaction. Une présence ne peut être enregistrée qu'après validation du billet.

## Stack technique

- PHP 8.3 ;
- Apache 2 ;
- MariaDB 11.4 avec InnoDB et `utf8mb4` ;
- PDO et requêtes préparées ;
- Composer et autoload PSR-4 ;
- HTML5, CSS et JavaScript natif ;
- Docker Compose ;
- phpMyAdmin pour l'administration locale de la base.

## Installation locale

### Prérequis

- Git ;
- Docker Desktop ou Docker Engine avec Docker Compose.

### Préparation

```bash
git clone <URL_DU_DEPOT>
cd gest-club
cp .env.example .env
```

Adapter ensuite les mots de passe locaux dans `.env`. Ce fichier ne doit jamais être versionné ni distribué.

### Démarrage

Construire les images, démarrer les services puis installer les dépendances PHP :

```bash
docker compose up -d --build
docker compose exec app composer install
```

Adresses locales prévues par `.env.example` :

- application : `http://localhost:8080` ;
- phpMyAdmin : `http://localhost:8081`.

Le schéma `database/schema.sql` doit être importé avant `database/seed.sql`.

### Arrêt

```bash
docker compose down
```

Pour supprimer également les données locales de MariaDB :

```bash
docker compose down -v
```

Cette dernière commande supprime définitivement le volume local de la base de données.

## Auteur

Projet de formation réalisé par Florian Authelin.
