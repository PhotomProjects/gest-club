-- GEST CLUB - Jeu de données V1 --
-- Comptes de développement :
--   - admin@gest-club.test   / gestclub123
--   - alice@gest-club.test   / gestclub123
--   - bruno@gest-club.test   / gestclub123
--   - camille@gest-club.test / gestclub123
--   - david@gest-club.test   / gestclub123

-- Cas couverts :
--   - événement futur ouvert ;
--   - événement futur complet ;
--   - événement terminé (statut calculé) ;
--   - événement annulé ;
--   - réservation 1 place ;
--   - réservation 2 places ;
--   - réservation annulée avec place réutilisée ensuite ;
--   - billet utilisé / billet jamais utilisé ;
--   - intervenant inactif conservé dans l'historique ;
--   - matchs Simple / Triple Threat / Tag Team.

SET NAMES utf8mb4;

SET @seed_now = CURRENT_TIMESTAMP;
SET @seed_today = CURRENT_DATE;

SET @event_1_start = DATE_ADD(DATE_ADD(@seed_today, INTERVAL 30 DAY), INTERVAL 20 HOUR);
SET @event_1_end = DATE_ADD(@event_1_start, INTERVAL 3 HOUR);

SET @event_2_start = DATE_ADD(DATE_ADD(@seed_today, INTERVAL 60 DAY), INTERVAL 20 HOUR);
SET @event_2_end = DATE_ADD(@event_2_start, INTERVAL 3 HOUR);

SET @event_3_start = DATE_ADD(DATE_SUB(@seed_today, INTERVAL 10 DAY), INTERVAL 20 HOUR);
SET @event_3_end = DATE_ADD(@event_3_start, INTERVAL 3 HOUR);

SET @event_4_start = DATE_ADD(DATE_ADD(@seed_today, INTERVAL 90 DAY), INTERVAL 20 HOUR);
SET @event_4_end = DATE_ADD(@event_4_start, INTERVAL 3 HOUR);

START TRANSACTION;

-- UTILISATEUR --

INSERT INTO utilisateur (
    id_utilisateur,
    prenom,
    nom,
    email,
    mdp_hash,
    role_utilisateur,
    date_creation_compte
) VALUES
    (1, 'Admin', 'Club', 'admin@gest-club.test', '$2y$12$nRgJzMdvDGc4HxTFTgrWfO0BVDtzG5WuqfhwoHhVwLYcL0IxKV1.W', 'ADMIN',  '2026-08-01 09:00:00'),
    (2, 'Alice', 'Martin', 'alice@gest-club.test', '$2y$12$nRgJzMdvDGc4HxTFTgrWfO0BVDtzG5WuqfhwoHhVwLYcL0IxKV1.W', 'MEMBRE', '2026-08-05 10:15:00'),
    (3, 'Bruno', 'Leroy', 'bruno@gest-club.test', '$2y$12$nRgJzMdvDGc4HxTFTgrWfO0BVDtzG5WuqfhwoHhVwLYcL0IxKV1.W', 'MEMBRE', '2026-08-06 14:20:00'),
    (4, 'Camille', 'Petit', 'camille@gest-club.test', '$2y$12$nRgJzMdvDGc4HxTFTgrWfO0BVDtzG5WuqfhwoHhVwLYcL0IxKV1.W', 'MEMBRE', '2026-08-07 16:45:00'),
    (5, 'David', 'Moreau', 'david@gest-club.test', '$2y$12$nRgJzMdvDGc4HxTFTgrWfO0BVDtzG5WuqfhwoHhVwLYcL0IxKV1.W', 'MEMBRE', '2026-08-08 11:30:00');


-- PLACE --
-- Deux places de démonstration par combinaison tribune / niveau.
-- Tarifs fixes par niveau :
-- HAUT = 25 €, MILIEU = 35 €, BAS = 45 €

INSERT INTO place (
    id_place,
    numero_place,
    rangee_place,
    niveau_place,
    tribune_place,
    prix_place
) VALUES
    -- Tribune Nord
    (1,  '1', 'A', 'HAUT',   'NORD', 25.00),
    (2,  '2', 'A', 'HAUT',   'NORD', 25.00),
    (3,  '1', 'B', 'MILIEU', 'NORD', 35.00),
    (4,  '2', 'B', 'MILIEU', 'NORD', 35.00),
    (9,  '1', 'E', 'BAS',    'NORD', 45.00),
    (10, '2', 'E', 'BAS',    'NORD', 45.00),

    -- Tribune Sud
    (11, '1', 'F', 'HAUT',   'SUD',  25.00),
    (12, '2', 'F', 'HAUT',   'SUD',  25.00),
    (7,  '1', 'D', 'MILIEU', 'SUD',  35.00),
    (8,  '2', 'D', 'MILIEU', 'SUD',  35.00),
    (5,  '1', 'C', 'BAS',    'SUD',  45.00),
    (6,  '2', 'C', 'BAS',    'SUD',  45.00),

    -- Tribune Est
    (13, '1', 'G', 'HAUT',   'EST',  25.00),
    (14, '2', 'G', 'HAUT',   'EST',  25.00),
    (15, '1', 'H', 'MILIEU', 'EST',  35.00),
    (16, '2', 'H', 'MILIEU', 'EST',  35.00),
    (17, '1', 'I', 'BAS',    'EST',  45.00),
    (18, '2', 'I', 'BAS',    'EST',  45.00),

    -- Tribune Ouest
    (19, '1', 'J', 'HAUT',   'OUEST', 25.00),
    (20, '2', 'J', 'HAUT',   'OUEST', 25.00),
    (21, '1', 'K', 'MILIEU', 'OUEST', 35.00),
    (22, '2', 'K', 'MILIEU', 'OUEST', 35.00),
    (23, '1', 'L', 'BAS',    'OUEST', 45.00),
    (24, '2', 'L', 'BAS',    'OUEST', 45.00);


-- EVENEMENT --
-- 1 : futur ouvert
-- 2 : futur complet (calculé via PLACE_EVENEMENT)
-- 3 : terminé (calculé via les dates)
-- 4 : annulé (statut stocké)

INSERT INTO evenement (
    id_evenement,
    nom_evenement,
    description_evenement,
    image_evenement,
    date_heure_debut_evenement,
    date_heure_fin_evenement,
    statut_evenement,
    date_creation_evenement
) VALUES
    (
        1,
        'Collision Nocturne',
        'Soirée de catch avec plusieurs affrontements et un main event.',
        NULL,
        @event_1_start,
        @event_1_end,
        'OUVERT',
        DATE_SUB(@seed_now, INTERVAL 20 DAY)
    ),
    (
        2,
        'Finale d''Automne',
        'Événement complet utilisé pour tester l''indisponibilité des places.',
        NULL,
        @event_2_start,
        @event_2_end,
        'OUVERT',
        DATE_SUB(@seed_now, INTERVAL 19 DAY)
    ),
    (
        3,
        'Summer Slam Local',
        'Événement passé utilisé pour tester les réservations et billets historiques.',
        NULL,
        @event_3_start,
        @event_3_end,
        'OUVERT',
        DATE_SUB(@seed_now, INTERVAL 30 DAY)
    ),
    (
        4,
        'Show Annulé',
        'Événement annulé après ouverture des réservations.',
        NULL,
        @event_4_start,
        @event_4_end,
        'ANNULE',
        DATE_SUB(@seed_now, INTERVAL 18 DAY)
    );


-- INTERVENANT --
-- L'intervenant 10 est désormais INACTIF mais reste dans
-- une participation historique de l'événement passé.

INSERT INTO intervenant (
    id_intervenant,
    nom_scene,
    statut_intervenant
) VALUES
    (1,  'Black Viper', 'ACTIF'),
    (2,  'Iron Wolf',   'ACTIF'),
    (3,  'Nova Kane',   'ACTIF'),
    (4,  'Rex Steel',   'ACTIF'),
    (5,  'Blaze Fox',   'ACTIF'),
    (6,  'Titan Cross', 'ACTIF'),
    (7,  'Maya Storm',  'ACTIF'),
    (8,  'Jade Fury',   'ACTIF'),
    (9,  'Ref Phoenix', 'ACTIF'),
    (10, 'Old Guard',   'INACTIF');


-- TYPE_MATCH --

INSERT INTO type_match (
    id_type_match,
    libelle_type_match,
    description_type_match,
    nombre_catcheurs_min,
    nombre_catcheurs_max,
    nombre_camps
) VALUES
    (1, 'Simple', 'Match opposant exactement deux catcheurs répartis en deux camps.', 2, 2, 2),
    (2, 'No Holds Barred', 'Match sans disqualification opposant exactement deux catcheurs.', 2, 2, 2),
    (3, 'Steel Cage', 'Match en cage opposant exactement deux catcheurs.', 2, 2, 2),
    (4, 'Tag Team 2v2', 'Match par équipes avec quatre catcheurs répartis en deux camps.', 4, 4, 2),
    (5, 'Iron Man', 'Match Iron Man opposant exactement deux catcheurs.', 2, 2, 2);


-- PLACE_EVENEMENT --
-- L'événement 1 utilise les 24 places afin de couvrir toutes les combinaisons tribune / niveau.
-- Les autres événements utilisent un sous-ensemble de huit places pour conserver les différents scénarios de test du seed.

INSERT INTO place_evenement (
    id_place_evenement,
    statut_place,
    id_evenement,
    id_place
) VALUES
    -- Événement 1 : trois places actuellement réservées
    (1,  'RESERVEE',   1, 1),
    (2,  'RESERVEE',   1, 2),
    (3,  'RESERVEE',   1, 3),
    (4,  'DISPONIBLE', 1, 4),
    (5,  'DISPONIBLE', 1, 5),
    (6,  'DISPONIBLE', 1, 6),
    (7,  'DISPONIBLE', 1, 7),
    (8,  'DISPONIBLE', 1, 8),

    -- Événement 2 : complet
    (9,  'RESERVEE', 2, 1),
    (10, 'RESERVEE', 2, 2),
    (11, 'RESERVEE', 2, 3),
    (12, 'RESERVEE', 2, 4),
    (13, 'RESERVEE', 2, 5),
    (14, 'RESERVEE', 2, 6),
    (15, 'RESERVEE', 2, 7),
    (16, 'RESERVEE', 2, 8),

    -- Événement 3 : passé
    (17, 'DISPONIBLE', 3, 1),
    (18, 'DISPONIBLE', 3, 2),
    (19, 'RESERVEE',   3, 3),
    (20, 'RESERVEE',   3, 4),
    (21, 'RESERVEE',   3, 5),
    (22, 'DISPONIBLE', 3, 6),
    (23, 'DISPONIBLE', 3, 7),
    (24, 'DISPONIBLE', 3, 8),

    -- Événement 4 : annulé, les réservations historiques sont conservées
    (25, 'DISPONIBLE', 4, 1),
    (26, 'DISPONIBLE', 4, 2),
    (27, 'DISPONIBLE', 4, 3),
    (28, 'DISPONIBLE', 4, 4),
    (29, 'RESERVEE',   4, 5),
    (30, 'RESERVEE',   4, 6),
    (31, 'DISPONIBLE', 4, 7),
    (32, 'DISPONIBLE', 4, 8),

    -- Places supplémentaires de l'événement 1 :
    -- toutes les combinaisons tribune / niveau sont disponibles.
    (33, 'DISPONIBLE', 1, 9),
    (34, 'DISPONIBLE', 1, 10),
    (35, 'DISPONIBLE', 1, 11),
    (36, 'DISPONIBLE', 1, 12),
    (37, 'DISPONIBLE', 1, 13),
    (38, 'DISPONIBLE', 1, 14),
    (39, 'DISPONIBLE', 1, 15),
    (40, 'DISPONIBLE', 1, 16),
    (41, 'DISPONIBLE', 1, 17),
    (42, 'DISPONIBLE', 1, 18),
    (43, 'DISPONIBLE', 1, 19),
    (44, 'DISPONIBLE', 1, 20),
    (45, 'DISPONIBLE', 1, 21),
    (46, 'DISPONIBLE', 1, 22),
    (47, 'DISPONIBLE', 1, 23),
    (48, 'DISPONIBLE', 1, 24);


-- MATCH_EVENEMENT --

INSERT INTO match_evenement (
    id_match,
    nom_match,
    ordre_match,
    id_evenement,
    id_type_match
) VALUES
    (1, 'Black Viper vs Iron Wolf', 1, 1, 1),
    (2, 'Nova Kane vs Rex Steel', 2, 1, 2),
    (3, 'Titan Cross vs Jade Fury', 1, 2, 1),
    (4, 'Black Viper & Nova Kane vs Iron Wolf & Rex Steel', 2, 2, 4),
    (5, 'Maya Storm vs Jade Fury', 1, 3, 1),
    (6, 'Blaze Fox vs Titan Cross', 1, 4, 1);


-- PARTICIPATION_MATCH --
-- Règle V1 respectée dans les données :
--   - composition conforme au TYPE_MATCH ;
--   - exactement un arbitre par match ;
--   - managers optionnels.

INSERT INTO participation_match (
    id_participation_match,
    role_participation,
    camp_participation,
    ordre_participation,
    id_match,
    id_intervenant
) VALUES
    -- Match 1 : Simple
    (1,  'CATCHEUR', 1,    1, 1, 1),
    (2,  'CATCHEUR', 2,    2, 1, 2),
    (3,  'ARBITRE',  NULL, 3, 1, 9),

    -- Match 2 : No Holds Barred
    (4,  'CATCHEUR', 1,    1, 2, 3),
    (5,  'CATCHEUR', 2,    2, 2, 4),
    (7,  'ARBITRE',  NULL, 3, 2, 9),

    -- Match 3 : Simple
    (8,  'CATCHEUR', 1,    1, 3, 6),
    (9,  'CATCHEUR', 2,    2, 3, 8),
    (10, 'ARBITRE',  NULL, 3, 3, 9),

    -- Match 4 : Tag Team 2v2
    (11, 'CATCHEUR', 1,    1, 4, 1),
    (12, 'CATCHEUR', 1,    2, 4, 3),
    (13, 'CATCHEUR', 2,    3, 4, 2),
    (14, 'CATCHEUR', 2,    4, 4, 4),
    (15, 'ARBITRE',  NULL, 5, 4, 9),

    -- Match 5 : historique, avec manager désormais inactif
    (16, 'CATCHEUR', 1,    1, 5, 7),
    (17, 'CATCHEUR', 2,    2, 5, 8),
    (18, 'ARBITRE',  NULL, 3, 5, 9),
    (19, 'MANAGER',  1, 4, 5, 10),

    -- Match 6 : événement annulé
    (20, 'CATCHEUR', 1,    1, 6, 5),
    (21, 'CATCHEUR', 2,    2, 6, 6),
    (22, 'ARBITRE',  NULL, 3, 6, 9);


-- RESERVATION --

INSERT INTO reservation (
    id_reservation,
    date_reservation,
    statut_reservation,
    id_utilisateur,
    id_evenement
) VALUES
    -- Événement 1 : futur ouvert
    -- Alice réserve deux places
    (1, DATE_SUB(@seed_now, INTERVAL 6 DAY), 'CONFIRMEE', 2, 1),

    -- Bruno réserve une place puis annule
    (2, DATE_SUB(@seed_now, INTERVAL 5 DAY), 'ANNULEE', 3, 1),

    -- Bruno peut ensuite effectuer une nouvelle réservation
    (3, DATE_SUB(@seed_now, INTERVAL 4 DAY), 'CONFIRMEE', 3, 1),

    -- Événement 2 : quatre réservations de deux places = complet
    (4, DATE_SUB(@seed_now, INTERVAL 3 DAY), 'CONFIRMEE', 2, 2),
    (5, DATE_ADD(DATE_SUB(@seed_now, INTERVAL 3 DAY),INTERVAL 15 MINUTE), 'CONFIRMEE', 3, 2),
    (6, DATE_ADD(DATE_SUB(@seed_now, INTERVAL 3 DAY),INTERVAL 30 MINUTE), 'CONFIRMEE', 4, 2),
    (7, DATE_ADD(DATE_SUB(@seed_now, INTERVAL 3 DAY),INTERVAL 45 MINUTE), 'CONFIRMEE', 5, 2),

    -- Événement 3 : passé
    -- Les réservations ont été réalisées avant l'événement
    (8, DATE_SUB(@event_3_start, INTERVAL 5 DAY), 'CONFIRMEE', 2, 3),
    (9, DATE_SUB(@event_3_start, INTERVAL 4 DAY), 'CONFIRMEE', 3, 3),

    -- Événement 4 : réservation conservée malgré l'annulation
    (10, DATE_SUB(@seed_now, INTERVAL 2 DAY), 'CONFIRMEE', 4, 4);


-- RESERVATION_PLACE --
-- La réservation 2 est annulée mais sa ligne historique est
-- conservée. La même PLACE_EVENEMENT #3 est ensuite utilisée
-- par la réservation 3 confirmée.

INSERT INTO reservation_place (
    id_reservation_place,
    prix_applique,
    position_place,
    id_place_evenement,
    id_reservation
) VALUES
    -- Réservation 1 : deux places
    (1,  25.00, 1, 1,  1),
    (2,  25.00, 2, 2,  1),

    -- Réservation 2 : une place annulée
    (3,  35.00, 1, 3,  2),

    -- Réservation 3 : la place #3 est réutilisée
    (4,  35.00, 1, 3,  3),

    -- Événement 2 complet
    (5,  25.00, 1, 9,  4),
    (6,  25.00, 2, 10, 4),
    (7,  35.00, 1, 11, 5),
    (8,  35.00, 2, 12, 5),
    (9,  45.00, 1, 13, 6),
    (10, 45.00, 2, 14, 6),
    (11, 35.00, 1, 15, 7),
    (12, 35.00, 2, 16, 7),

    -- Événement passé
    (13, 35.00, 1, 19, 8),
    (14, 35.00, 2, 20, 8),
    (15, 45.00, 1, 21, 9),

    -- Événement annulé
    (16, 45.00, 1, 29, 10),
    (17, 45.00, 2, 30, 10);


-- BILLET --
-- Un billet unique par réservation, y compris pour deux places.

INSERT INTO billet (
    id_billet,
    code_billet,
    id_reservation
) VALUES
    (1,  'd016b4ab21ec23ad4ffb826231080305b7428846f5e12ee3dc5350b67253a6a3',  1),
    (2,  'eb8526f9eb6856623e473302c37d9da93ccc8b7b7ea56f111e3e21552a7354ca',  2),
    (3,  '0ef230b20b97dae665885ab1640f99f67406f633da85047f3f22a17b277f151c',  3),
    (4,  '1eb220ec872564264d6cf8dd302b6e9161b3ac3c3052e8524cfc381775036c0c',  4),
    (5,  'a2de2f038bdf1b33530b0b07f9f4f451f0acb31638ed375a6135e769b55cd74d',  5),
    (6,  'a41f2d21f60b4407f6e2d1a344f9b1b799b28d95929c54024802f9c32647f854',  6),
    (7,  'd336c951cc3b6daf8f0c11481d87faede49262ea88bf886bb29a4f3692230f12',  7),
    (8,  '502e8fb7aba3b87c95b2ffccabf92d0d4c27b6eb5a75de39c9c066741d6e2ce4',  8),
    (9,  '9436907c719c4d3e3e120a58a3c0bfe77d085944b92ab7034ebef2a0fc2e4cc7',  9),
    (10, 'a2f16cee2ed7dba2ae7ba8b4e648d7a546ec9893cd5c8050f4d6e3f7dc175647', 10);


-- PRESENCE --
-- Le billet 8 couvre deux places : une seule présence signifie
-- que le groupe associé au billet a été admis.
-- Le billet 9 est un exemple de billet passé jamais utilisé.

INSERT INTO presence (
    id_presence,
    date_heure_controle,
    id_billet
) VALUES
    (1, DATE_ADD(@event_3_start, INTERVAL 25 MINUTE), 8);


COMMIT;
