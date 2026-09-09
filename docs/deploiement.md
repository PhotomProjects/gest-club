# Déploiement — GEST CLUB

## 1. Périmètre du document

Ce document décrit l'installation reproductible de GEST CLUB dans son environnement Docker local. Cette configuration convient au développement, aux tests et à une démonstration devant le jury.

La configuration Docker actuelle n'est pas destinée à être exposée directement sur Internet. Les prérequis d'une mise en production sont recensés en fin de document.

Toutes les commandes sont à exécuter depuis la racine du projet, dans un terminal Bash ou Git Bash.

## 2. Services utilisés

| Service      | Rôle                             | Adresse locale par défaut                       |
| ------------ | -------------------------------- | ----------------------------------------------- |
| `app`        | PHP 8.3 et Apache                | `http://localhost:8080`                         |
| `database`   | MariaDB 11.4                     | accessible uniquement par les autres conteneurs |
| `phpmyadmin` | Administration locale de MariaDB | `http://localhost:8081`                         |

Apache expose uniquement le répertoire `public/` de l'application.

## 3. Prérequis

- Git, sauf si le projet est fourni sous forme d'archive ;
- Docker Desktop, ou Docker Engine avec le module Docker Compose ;
- les ports `8080` et `8081` disponibles, ou deux autres ports définis dans `.env`.

Vérifier l'installation :

```bash
docker --version
docker compose version
```

## 4. Première installation locale

### 4.1 Récupérer le projet

Depuis le dépôt Git :

```bash
git clone https://github.com/PhotomProjects/gest-club.git
cd gest-club
```

Si une archive a été fournie, l'extraire puis ouvrir un terminal dans le dossier contenant `compose.yaml`.

### 4.2 Configurer l'environnement

Créer le fichier local `.env` à partir du modèle :

```bash
cp .env.example .env
```

Modifier au minimum ces deux valeurs dans `.env` :

```dotenv
DB_PASSWORD=mot_de_passe_local_unique
DB_ROOT_PASSWORD=autre_mot_de_passe_local_unique
```

Les autres valeurs peuvent rester inchangées pour une installation locale. Les mots de passe doivent être différents et ne pas reprendre les exemples ci-dessus.

Le fichier `.env` contient des secrets, il est ignoré par Git et ne doit jamais être joint à une archive distribuée. Vérifier la syntaxe de la configuration sans afficher ses valeurs :

```bash
docker compose config --quiet
```

### 4.3 Construire et démarrer les conteneurs

```bash
docker compose up -d --build
docker compose ps
```

Le service `database` doit être indiqué comme sain (`healthy`) avant l'importation des fichiers SQL.

### 4.4 Installer les dépendances PHP

```bash
docker compose exec app composer install --no-interaction
```

Cette commande installe notamment PHPUnit et la bibliothèque utilisée pour générer les QR codes.
--no-interaction indique à Composer de ne poser aucune question dans le terminal pendant l’installation.

### 4.5 Initialiser la base de développement

Importer d'abord la structure, puis les données de démonstration :

```bash
docker compose exec -T database sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' < database/schema.sql

docker compose exec -T database sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' < database/seed.sql
```

Ces commandes sont prévues pour une base vide. `schema.sql` ne doit pas être réimporté sur une base déjà initialisée.

Le jeu de données calcule les dates des événements par rapport à la date d'importation. Il contient plusieurs scénarios de démonstration : événement ouvert, complet, terminé et annulé.

### 4.6 Vérifier l'installation

Afficher les tables créées :

```bash
docker compose exec database sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" -e "SHOW TABLES FROM $MARIADB_DATABASE;"'
```

Ouvrir ensuite :

- l'application : `http://localhost:8080` ;
- l'administration : `http://localhost:8080/admin/` ;
- phpMyAdmin : `http://localhost:8081`.

Comptes de démonstration principaux :

| Rôle           | Adresse e-mail         | Mot de passe  |
| -------------- | ---------------------- | ------------- |
| Administrateur | `admin@gest-club.test` | `gestclub123` |
| Membre         | `alice@gest-club.test` | `gestclub123` |

Ces comptes sont réservés à la démonstration. Le fichier `database/seed.sql` et ses identifiants fixes ne doivent jamais être utilisés en production.

## 5. Utilisation quotidienne

Démarrer les services existants :

```bash
docker compose up -d
```

Consulter leur état :

```bash
docker compose ps
```

Afficher les journaux de l'application et de MariaDB :

```bash
docker compose logs -f app database
```

Arrêter les services sans supprimer les données :

```bash
docker compose down
```

Après une modification de `Dockerfile`, de la configuration Apache ou de la configuration PHP, reconstruire l'image :

```bash
docker compose up -d --build
```

## 6. Réinitialiser l'environnement local

La commande suivante arrête les conteneurs et supprime le volume MariaDB :

```bash
docker compose down -v
```

Cette opération supprime définitivement les bases de développement et de test. Elle ne doit être exécutée que si cette perte est volontaire.

Recréer ensuite l'environnement :

```bash
docker compose up -d

docker compose exec -T database sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' < database/schema.sql

docker compose exec -T database sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' < database/seed.sql
```

Le dossier `vendor/` est stocké dans le projet et n'est pas supprimé avec le volume MariaDB. Relancer `composer install` seulement s'il est absent ou si `composer.lock` a changé.

## 7. Sauvegarder et restaurer les données

### 7.1 Sauvegarder MariaDB

Créer un dossier hors du dépôt, puis exporter la base :

```bash
mkdir -p ../gest-club-backups

backup_file="../gest-club-backups/gest-club-$(date +%Y%m%d-%H%M%S).sql"

docker compose exec -T database sh -c 'mariadb-dump -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" --single-transaction --default-character-set=utf8mb4 "$MARIADB_DATABASE"' > "$backup_file"

test -s "$backup_file" && printf 'Sauvegarde créée : %s\n' "$backup_file"
```

Ne pas versionner cette sauvegarde : elle peut contenir des données personnelles et des secrets fonctionnels tels que les références de billets.

### 7.2 Restaurer MariaDB

Arrêter temporairement l'application pour éviter toute écriture pendant la restauration :

```bash
docker compose stop app

docker compose exec -T database sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' < ../gest-club-backups/NOM_DE_LA_SAUVEGARDE.sql

docker compose start app
```

La restauration remplace l'état courant de la base par celui de la sauvegarde. Une copie de l'état courant doit être réalisée avant l'opération si son contenu doit être conservé.

### 7.3 Sauvegarder les images envoyées

Les images ajoutées depuis l'administration sont enregistrées dans `public/assets/images/evenements/` et ignorées par Git. Elles ne sont pas incluses dans l'export MariaDB : ce dossier doit être sauvegardé séparément avec la base.

## 8. Tests automatisés

Les tests d'intégration utilisent une base isolée nommée `gest_club_test`. Sa création, l'attribution des droits et l'importation du schéma sont détaillées dans [`tests/plan-tests.md`](tests/plan-tests.md).

Une fois cette base préparée, exécuter la suite complète :

```bash
docker compose exec app vendor/bin/phpunit
```

## 9. Diagnostic rapide

### Erreur concernant `vendor/autoload.php`

Les dépendances ne sont pas installées ou le dossier `vendor/` est incomplet :

```bash
docker compose exec app composer install --no-interaction
```

### Erreur de connexion à la base

Vérifier l'état et les journaux du service :

```bash
docker compose ps
docker compose logs database
```

Après une modification des identifiants MariaDB dans `.env`, un volume déjà créé conserve les anciens identifiants. En environnement local, sauvegarder les données utiles puis réinitialiser volontairement le volume avec `docker compose down -v`.

### Ports déjà utilisés

Modifier les ports exposés dans `.env`, par exemple :

```dotenv
APP_PORT=8082
PHPMYADMIN_PORT=8083
APP_URL=http://localhost:8082
```

Redémarrer ensuite les services :

```bash
docker compose up -d
```

### Impossible d'enregistrer une image

Le processus Apache doit pouvoir écrire dans `public/assets/images/evenements/`. Vérifier les droits du dossier sur l'hôte et dans le conteneur. Ne pas résoudre ce problème avec des droits globaux `777` sur un serveur de production.

### Accès refusé après une modification de `.env`

Si MariaDB affiche une erreur similaire à :

```text
ERROR 1045 (28000): Access denied for user
```

le volume MariaDB peut avoir été initialisé avec d’anciens identifiants. La modification de `DB_PASSWORD` ou de `DB_ROOT_PASSWORD` dans `.env` ne met pas automatiquement à jour les comptes enregistrés dans une base existante.

Si les données locales peuvent être supprimées, réinitialiser le volume :

```bash
docker compose down --volumes --remove-orphans
docker compose up -d
docker compose ps
```

Attendre que le service `database` soit indiqué comme sain (`healthy`), puis réimporter la structure et les données :

```bash
docker compose exec -T database sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' < database/schema.sql

docker compose exec -T database sh -c 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' < database/seed.sql
```

> **Attention :** l’option `--volumes` supprime définitivement les bases contenues dans le volume MariaDB du projet. Effectuer une sauvegarde avant cette opération si les données doivent être conservées.

## 10. Conditions avant une mise en production

Une cible d'hébergement doit être choisie avant de produire une procédure exacte. La configuration actuelle doit au minimum être adaptée sur les points suivants :

- utiliser `APP_ENV=production`, `APP_DEBUG=false` et une URL publique en HTTPS ;
- employer des secrets longs, uniques et fournis au déploiement, jamais intégrés à l'image ou au dépôt ;
- utiliser une configuration PHP de production avec `display_errors=Off` et des journaux non publics ;
- terminer la connexion HTTPS sur Apache ou sur un reverse proxy correctement configuré afin que les cookies de session portent l'attribut `Secure` ;
- retirer phpMyAdmin du déploiement public, ou en limiter strictement l'accès à un réseau d'administration ;
- construire une image immuable contenant le code et les dépendances, sans montage du dépôt avec `.:/var/www/html` ;
- installer les dépendances avec `composer install --no-dev --classmap-authoritative` ;
- conserver uniquement `public/` comme racine web ;
- donner au compte du serveur web les droits d'écriture sur le seul dossier d'images nécessaire, sans utiliser `777` ;
- rendre persistants et sauvegarder à la fois MariaDB et les images envoyées ;
- importer uniquement `database/schema.sql`, jamais les données ni les comptes de démonstration ;
- prévoir une procédure sécurisée et ponctuelle pour créer le premier compte administrateur ;
- mettre en place des sauvegardes testées, une supervision des erreurs et les mises à jour de sécurité des images Docker et des dépendances.

Tant que ces adaptations ne sont pas réalisées et vérifiées, le projet doit être présenté comme déployable localement avec Docker, et non comme prêt pour un hébergement public.

## 11. Vérifications finales

- `docker compose config --quiet` ne retourne aucune erreur ;
- les trois services sont démarrés et MariaDB est saine ;
- les douze tables sont présentes ;
- la page d'accueil s'affiche ;
- la connexion membre et administrateur fonctionne ;
- une réservation génère un billet et son QR code ;
- l'administration permet d'ajouter une image d'événement ;
- l'arrêt puis le redémarrage des conteneurs conservent les données ;
- la suite PHPUnit s'exécute sur la base de test isolée.
