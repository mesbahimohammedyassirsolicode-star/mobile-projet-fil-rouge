CREATE DATABASE IF NOT EXISTS smart_tourism
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE smart_tourism;

-- =========================================
-- 1. UTILISATEUR
-- =========================================

CREATE TABLE utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    date_naissance DATE,
    photo VARCHAR(255),
    date_inscription DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- =========================================
-- 2. DESTINATION
-- =========================================

CREATE TABLE destination (
    id_destination INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    ville VARCHAR(100) NOT NULL,
    region VARCHAR(100),
    pays VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7)
) ENGINE=InnoDB;


-- =========================================
-- 3. CATEGORIE
-- =========================================

CREATE TABLE categorie (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
) ENGINE=InnoDB;


-- =========================================
-- 4. LIEU TOURISTIQUE
-- =========================================

CREATE TABLE lieu_touristique (
    id_lieu INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    adresse VARCHAR(255),
    prix_entree DECIMAL(10,2),
    horaires VARCHAR(100),
    image VARCHAR(255),
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),

    id_destination INT NOT NULL,
    id_categorie INT NOT NULL,

    CONSTRAINT fk_lieu_destination
        FOREIGN KEY (id_destination)
        REFERENCES destination(id_destination)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_lieu_categorie
        FOREIGN KEY (id_categorie)
        REFERENCES categorie(id_categorie)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT chk_lieu_prix
        CHECK (prix_entree IS NULL OR prix_entree >= 0)
) ENGINE=InnoDB;


-- =========================================
-- 5. ACTIVITE
-- =========================================

CREATE TABLE activite (
    id_activite INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    duree INT,
    prix DECIMAL(10,2),
    image VARCHAR(255),
    niveau VARCHAR(30),

    id_destination INT NOT NULL,
    id_categorie INT NOT NULL,

    CONSTRAINT fk_activite_destination
        FOREIGN KEY (id_destination)
        REFERENCES destination(id_destination)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_activite_categorie
        FOREIGN KEY (id_categorie)
        REFERENCES categorie(id_categorie)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT chk_activite_duree
        CHECK (duree IS NULL OR duree > 0),

    CONSTRAINT chk_activite_prix
        CHECK (prix IS NULL OR prix >= 0)
) ENGINE=InnoDB;


-- =========================================
-- 6. FAVORI LIEU
-- =========================================

CREATE TABLE favori_lieu (
    id_favori_lieu INT AUTO_INCREMENT PRIMARY KEY,
    date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    id_utilisateur INT NOT NULL,
    id_lieu INT NOT NULL,

    CONSTRAINT fk_favori_lieu_utilisateur
        FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateur(id_utilisateur)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_favori_lieu_lieu
        FOREIGN KEY (id_lieu)
        REFERENCES lieu_touristique(id_lieu)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT uq_favori_lieu
        UNIQUE (id_utilisateur, id_lieu)
) ENGINE=InnoDB;


-- =========================================
-- 7. FAVORI ACTIVITE
-- =========================================

CREATE TABLE favori_activite (
    id_favori_activite INT AUTO_INCREMENT PRIMARY KEY,
    date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    id_utilisateur INT NOT NULL,
    id_activite INT NOT NULL,

    CONSTRAINT fk_favori_activite_utilisateur
        FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateur(id_utilisateur)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_favori_activite_activite
        FOREIGN KEY (id_activite)
        REFERENCES activite(id_activite)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT uq_favori_activite
        UNIQUE (id_utilisateur, id_activite)
) ENGINE=InnoDB;


-- =========================================
-- 8. AVIS LIEU
-- =========================================

CREATE TABLE avis_lieu (
    id_avis_lieu INT AUTO_INCREMENT PRIMARY KEY,
    note TINYINT NOT NULL,
    commentaire TEXT,
    date_avis DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    id_utilisateur INT NOT NULL,
    id_lieu INT NOT NULL,

    CONSTRAINT fk_avis_lieu_utilisateur
        FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateur(id_utilisateur)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_avis_lieu_lieu
        FOREIGN KEY (id_lieu)
        REFERENCES lieu_touristique(id_lieu)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT chk_avis_lieu_note
        CHECK (note BETWEEN 1 AND 5),

    CONSTRAINT uq_avis_lieu
        UNIQUE (id_utilisateur, id_lieu)
) ENGINE=InnoDB;


-- =========================================
-- 9. AVIS ACTIVITE
-- =========================================

CREATE TABLE avis_activite (
    id_avis_activite INT AUTO_INCREMENT PRIMARY KEY,
    note TINYINT NOT NULL,
    commentaire TEXT,
    date_avis DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    id_utilisateur INT NOT NULL,
    id_activite INT NOT NULL,

    CONSTRAINT fk_avis_activite_utilisateur
        FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateur(id_utilisateur)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_avis_activite_activite
        FOREIGN KEY (id_activite)
        REFERENCES activite(id_activite)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT chk_avis_activite_note
        CHECK (note BETWEEN 1 AND 5),

    CONSTRAINT uq_avis_activite
        UNIQUE (id_utilisateur, id_activite)
) ENGINE=InnoDB;


-- =========================================
-- 10. VOYAGE
-- =========================================

CREATE TABLE voyage (
    id_voyage INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    budget DECIMAL(10,2),
    description TEXT,

    id_utilisateur INT NOT NULL,
    id_destination INT NOT NULL,

    CONSTRAINT fk_voyage_utilisateur
        FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateur(id_utilisateur)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_voyage_destination
        FOREIGN KEY (id_destination)
        REFERENCES destination(id_destination)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT chk_voyage_dates
        CHECK (date_fin >= date_debut),

    CONSTRAINT chk_voyage_budget
        CHECK (budget IS NULL OR budget >= 0)
) ENGINE=InnoDB;


-- =========================================
-- 11. ETAPE LIEU
-- =========================================

CREATE TABLE etape_lieu (
    id_etape_lieu INT AUTO_INCREMENT PRIMARY KEY,
    date_etape DATE NOT NULL,
    heure_debut TIME,
    heure_fin TIME,
    ordre INT NOT NULL,
    notes TEXT,

    id_voyage INT NOT NULL,
    id_lieu INT NOT NULL,

    CONSTRAINT fk_etape_lieu_voyage
        FOREIGN KEY (id_voyage)
        REFERENCES voyage(id_voyage)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_etape_lieu_lieu
        FOREIGN KEY (id_lieu)
        REFERENCES lieu_touristique(id_lieu)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT chk_etape_lieu_ordre
        CHECK (ordre > 0),

    CONSTRAINT chk_etape_lieu_heures
        CHECK (
            heure_fin IS NULL
            OR heure_debut IS NULL
            OR heure_fin > heure_debut
        )
) ENGINE=InnoDB;


-- =========================================
-- 12. ETAPE ACTIVITE
-- =========================================

CREATE TABLE etape_activite (
    id_etape_activite INT AUTO_INCREMENT PRIMARY KEY,
    date_etape DATE NOT NULL,
    heure_debut TIME,
    heure_fin TIME,
    ordre INT NOT NULL,
    notes TEXT,

    id_voyage INT NOT NULL,
    id_activite INT NOT NULL,

    CONSTRAINT fk_etape_activite_voyage
        FOREIGN KEY (id_voyage)
        REFERENCES voyage(id_voyage)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_etape_activite_activite
        FOREIGN KEY (id_activite)
        REFERENCES activite(id_activite)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT chk_etape_activite_ordre
        CHECK (ordre > 0),

    CONSTRAINT chk_etape_activite_heures
        CHECK (
            heure_fin IS NULL
            OR heure_debut IS NULL
            OR heure_fin > heure_debut
        )
) ENGINE=InnoDB;