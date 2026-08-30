CREATE TABLE utilisateur (
    id_utilisateur INT NOT NULL AUTO_INCREMENT,
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mdp_hash VARCHAR(255) NOT NULL,
    role_utilisateur VARCHAR(10) NOT NULL DEFAULT 'MEMBRE',
    date_creation_compte DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_utilisateur
        PRIMARY KEY (id_utilisateur),

    CONSTRAINT uq_utilisateur_email
        UNIQUE (email),

    CONSTRAINT chk_utilisateur_prenom_non_vide
        CHECK (CHAR_LENGTH(TRIM(prenom)) > 0),

    CONSTRAINT chk_utilisateur_nom_non_vide
        CHECK (CHAR_LENGTH(TRIM(nom)) > 0),

    CONSTRAINT chk_utilisateur_email_non_vide
        CHECK (CHAR_LENGTH(TRIM(email)) > 0),

    CONSTRAINT chk_utilisateur_mdp_hash_non_vide
        CHECK (CHAR_LENGTH(TRIM(mdp_hash)) > 0),

    CONSTRAINT chk_utilisateur_role
        CHECK (role_utilisateur IN ('MEMBRE', 'ADMIN'))
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE place (
    id_place INT NOT NULL AUTO_INCREMENT,
    numero_place VARCHAR(10) NOT NULL,
    rangee_place VARCHAR(10) NOT NULL,
    niveau_place VARCHAR(10) NOT NULL,
    tribune_place VARCHAR(10) NOT NULL,
    prix_place DECIMAL(10,2) NOT NULL,

    CONSTRAINT pk_place
        PRIMARY KEY (id_place),

    CONSTRAINT uq_place_localisation
        UNIQUE (
            tribune_place,
            niveau_place,
            rangee_place,
            numero_place
        ),

    CONSTRAINT chk_place_numero_rangee_non_vides
        CHECK (
            CHAR_LENGTH(TRIM(numero_place)) > 0
            AND CHAR_LENGTH(TRIM(rangee_place)) > 0
        ),

    CONSTRAINT chk_place_niveau
        CHECK (
            niveau_place IN ('HAUT', 'MILIEU', 'BAS')
        ),

    CONSTRAINT chk_place_tribune
        CHECK (
            tribune_place IN ('NORD', 'SUD', 'EST', 'OUEST')
        ),

    CONSTRAINT chk_place_prix
        CHECK (prix_place > 0)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE evenement (
    id_evenement INT NOT NULL AUTO_INCREMENT,
    nom_evenement VARCHAR(150) NOT NULL,
    description_evenement TEXT NOT NULL,
    image_evenement VARCHAR(255) NULL,
    date_heure_debut_evenement DATETIME NOT NULL,
    date_heure_fin_evenement DATETIME NOT NULL,
    statut_evenement VARCHAR(10) NOT NULL DEFAULT 'OUVERT',
    date_creation_evenement DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_evenement
        PRIMARY KEY (id_evenement),

    CONSTRAINT chk_evenement_nom_non_vide
        CHECK (
            CHAR_LENGTH(TRIM(nom_evenement)) > 0
        ),

    CONSTRAINT chk_evenement_description
        CHECK (
            CHAR_LENGTH(TRIM(description_evenement))
            BETWEEN 1 AND 2000
        ),

    CONSTRAINT chk_evenement_image_non_vide
        CHECK (
            image_evenement IS NULL
            OR CHAR_LENGTH(TRIM(image_evenement)) > 0
        ),

    CONSTRAINT chk_evenement_dates
        CHECK (
            date_heure_fin_evenement > date_heure_debut_evenement
        ),

    CONSTRAINT chk_evenement_statut
        CHECK (
            statut_evenement IN ('OUVERT', 'ANNULE')
        ),

    INDEX idx_evenement_statut_debut (
        statut_evenement,
        date_heure_debut_evenement
    )
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE intervenant (
    id_intervenant INT NOT NULL AUTO_INCREMENT,
    nom_scene VARCHAR(100) NOT NULL,
    statut_intervenant VARCHAR(10) NOT NULL DEFAULT 'ACTIF',

    CONSTRAINT pk_intervenant
        PRIMARY KEY (id_intervenant),

    CONSTRAINT uq_intervenant_nom_scene
        UNIQUE (nom_scene),

    CONSTRAINT chk_intervenant_nom_scene_non_vide
        CHECK (
            CHAR_LENGTH(TRIM(nom_scene)) > 0
        ),

    CONSTRAINT chk_intervenant_statut
        CHECK (
            statut_intervenant IN ('ACTIF', 'INACTIF')
        )
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE type_match (
    id_type_match INT NOT NULL AUTO_INCREMENT,
    libelle_type_match VARCHAR(100) NOT NULL,
    description_type_match TEXT NOT NULL,
    nombre_catcheurs_min TINYINT UNSIGNED NOT NULL,
    nombre_catcheurs_max TINYINT UNSIGNED NOT NULL,
    nombre_camps TINYINT UNSIGNED NOT NULL,

    CONSTRAINT pk_type_match
        PRIMARY KEY (id_type_match),

    CONSTRAINT uq_type_match_libelle
        UNIQUE (libelle_type_match),

    CONSTRAINT chk_type_match_libelle_non_vide
        CHECK (
            CHAR_LENGTH(TRIM(libelle_type_match)) > 0
        ),

    CONSTRAINT chk_type_match_description
        CHECK (
            CHAR_LENGTH(TRIM(description_type_match))
            BETWEEN 1 AND 2000
        ),

    CONSTRAINT chk_type_match_catcheurs
        CHECK (
            nombre_catcheurs_min >= 2
            AND nombre_catcheurs_max >= nombre_catcheurs_min
        ),

    CONSTRAINT chk_type_match_camps
        CHECK (
            nombre_camps >= 2
            AND nombre_camps <= nombre_catcheurs_min
        )
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE place_evenement (
    id_place_evenement INT NOT NULL AUTO_INCREMENT,
    statut_place VARCHAR(10) NOT NULL DEFAULT 'DISPONIBLE',
    id_evenement INT NOT NULL,
    id_place INT NOT NULL,

    CONSTRAINT pk_place_evenement
        PRIMARY KEY (id_place_evenement),

    CONSTRAINT uq_place_evenement
        UNIQUE (id_evenement, id_place),

    CONSTRAINT chk_place_evenement_statut
        CHECK (
            statut_place IN ('DISPONIBLE', 'RESERVEE')
        ),

    CONSTRAINT fk_place_evenement_evenement
        FOREIGN KEY (id_evenement)
        REFERENCES evenement (id_evenement)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,

    CONSTRAINT fk_place_evenement_place
        FOREIGN KEY (id_place)
        REFERENCES place (id_place)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,

    INDEX idx_place_evenement_disponibilite (
        id_evenement,
        statut_place
    )
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE match_evenement (
    id_match INT NOT NULL AUTO_INCREMENT,
    nom_match VARCHAR(150) NOT NULL,
    ordre_match TINYINT UNSIGNED NOT NULL,
    id_evenement INT NOT NULL,
    id_type_match INT NOT NULL,

    CONSTRAINT pk_match_evenement
        PRIMARY KEY (id_match),

    CONSTRAINT uq_match_evenement_ordre
        UNIQUE (id_evenement, ordre_match),

    CONSTRAINT chk_match_evenement_nom_non_vide
        CHECK (
            CHAR_LENGTH(TRIM(nom_match)) > 0
        ),

    CONSTRAINT chk_match_evenement_ordre_positif
        CHECK (
            ordre_match >= 1
        ),

    CONSTRAINT fk_match_evenement_evenement
        FOREIGN KEY (id_evenement)
        REFERENCES evenement (id_evenement)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,

    CONSTRAINT fk_match_evenement_type
        FOREIGN KEY (id_type_match)
        REFERENCES type_match (id_type_match)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE participation_match (
    id_participation_match INT NOT NULL AUTO_INCREMENT,
    role_participation VARCHAR(10) NOT NULL,
    camp_participation TINYINT UNSIGNED NULL,
    ordre_participation TINYINT UNSIGNED NOT NULL,
    id_match INT NOT NULL,
    id_intervenant INT NOT NULL,

    CONSTRAINT pk_participation_match
        PRIMARY KEY (id_participation_match),

    CONSTRAINT uq_participation_match_intervenant
        UNIQUE (id_match, id_intervenant),

    CONSTRAINT uq_participation_match_ordre
        UNIQUE (id_match, ordre_participation),

    CONSTRAINT chk_participation_match_role
        CHECK (
            role_participation IN (
                'CATCHEUR',
                'ARBITRE',
                'MANAGER'
            )
        ),

    CONSTRAINT chk_participation_match_camp
        CHECK (
            (
                role_participation IN ('CATCHEUR', 'MANAGER')
                AND camp_participation IS NOT NULL
                AND camp_participation >= 1
            )
            OR
            (
                role_participation = 'ARBITRE'
                AND camp_participation IS NULL
            )
        ),

    CONSTRAINT chk_participation_match_ordre
        CHECK (
            ordre_participation >= 1
        ),

    CONSTRAINT fk_participation_match_match
        FOREIGN KEY (id_match)
        REFERENCES match_evenement (id_match)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,

    CONSTRAINT fk_participation_match_intervenant
        FOREIGN KEY (id_intervenant)
        REFERENCES intervenant (id_intervenant)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE reservation (
    id_reservation INT NOT NULL AUTO_INCREMENT,

    reference_reservation CHAR(32)
        CHARACTER SET ascii
        COLLATE ascii_bin
        NOT NULL,

    date_reservation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    statut_reservation VARCHAR(10) NOT NULL DEFAULT 'CONFIRMEE',
    id_utilisateur INT NOT NULL,
    id_evenement INT NOT NULL,

    CONSTRAINT pk_reservation
        PRIMARY KEY (id_reservation),

    CONSTRAINT uq_reservation_reference
        UNIQUE (reference_reservation),

    CONSTRAINT chk_reservation_reference
        CHECK (
            reference_reservation REGEXP '^[0-9a-f]{32}$'
        ),

    CONSTRAINT chk_reservation_statut
        CHECK (
            statut_reservation IN ('CONFIRMEE', 'ANNULEE')
        ),

    CONSTRAINT fk_reservation_utilisateur
        FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateur (id_utilisateur)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,

    CONSTRAINT fk_reservation_evenement
        FOREIGN KEY (id_evenement)
        REFERENCES evenement (id_evenement)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,

    INDEX idx_reservation_utilisateur_evenement_statut (
        id_utilisateur,
        id_evenement,
        statut_reservation
    )
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE reservation_place (
    id_reservation_place INT NOT NULL AUTO_INCREMENT,
    prix_applique DECIMAL(10,2) NOT NULL,
    position_place TINYINT UNSIGNED NOT NULL,
    id_place_evenement INT NOT NULL,
    id_reservation INT NOT NULL,

    CONSTRAINT pk_reservation_place
        PRIMARY KEY (id_reservation_place),

    CONSTRAINT uq_reservation_place_position
        UNIQUE (
            id_reservation,
            position_place
        ),

    CONSTRAINT uq_reservation_place_attribution
        UNIQUE (
            id_reservation,
            id_place_evenement
        ),

    CONSTRAINT chk_reservation_place_prix
        CHECK (
            prix_applique > 0
        ),

    CONSTRAINT chk_reservation_place_position
        CHECK (
            position_place IN (1, 2)
        ),

    CONSTRAINT fk_reservation_place_place_evenement
        FOREIGN KEY (id_place_evenement)
        REFERENCES place_evenement (id_place_evenement)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,

    CONSTRAINT fk_reservation_place_reservation
        FOREIGN KEY (id_reservation)
        REFERENCES reservation (id_reservation)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE billet (
    id_billet INT NOT NULL AUTO_INCREMENT,

    code_billet CHAR(64)
        CHARACTER SET ascii
        COLLATE ascii_bin
        NOT NULL,

    id_reservation INT NOT NULL,

    CONSTRAINT pk_billet
        PRIMARY KEY (id_billet),

    CONSTRAINT uq_billet_code
        UNIQUE (code_billet),

    CONSTRAINT uq_billet_reservation
        UNIQUE (id_reservation),

    CONSTRAINT chk_billet_code
        CHECK (
            code_billet REGEXP '^[0-9a-f]{64}$'
        ),

    CONSTRAINT fk_billet_reservation
        FOREIGN KEY (id_reservation)
        REFERENCES reservation (id_reservation)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


CREATE TABLE presence (
    id_presence INT NOT NULL AUTO_INCREMENT,
    date_heure_controle DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_billet INT NOT NULL,

    CONSTRAINT pk_presence
        PRIMARY KEY (id_presence),

    CONSTRAINT uq_presence_billet
        UNIQUE (id_billet),

    CONSTRAINT fk_presence_billet
        FOREIGN KEY (id_billet)
        REFERENCES billet (id_billet)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;