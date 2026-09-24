CREATE DATABASE IF NOT EXISTS reservation_hotels CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE reservation_hotels;

CREATE TABLE Hotel (
  id_hotel INT AUTO_INCREMENT PRIMARY KEY,
  nom_hotel VARCHAR(100) NOT NULL, adresse VARCHAR(200) NOT NULL,
  ville VARCHAR(50) NOT NULL, pays VARCHAR(50) NOT NULL,
  telephone VARCHAR(20) NULL, email VARCHAR(100) NULL,
  categorie_etoiles TINYINT NULL, date_ouverture DATE NULL,
  CONSTRAINT chk_hotel_etoiles CHECK (categorie_etoiles BETWEEN 1 AND 5)
) ENGINE=InnoDB;

CREATE TABLE Client (
  id_client INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE, mot_de_passe VARCHAR(255) NOT NULL,
  telephone VARCHAR(20) NULL, adresse VARCHAR(200) NULL, ville VARCHAR(50) NULL,
  pays VARCHAR(50) NULL, cin_passeport VARCHAR(30) NULL UNIQUE,
  date_naissance DATE NULL, date_inscription DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  statut_fidelite ENUM('Standard','Silver','Gold','Platinum') NULL
) ENGINE=InnoDB;

CREATE TABLE Type_Hebergement (
  id_type INT AUTO_INCREMENT PRIMARY KEY, libelle VARCHAR(50) NOT NULL,
  description TEXT NULL, capacite_max TINYINT NOT NULL,
  superficie_m2 DECIMAL(5,2) NULL, prix_base_nuit DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Equipement (
  id_equipement INT AUTO_INCREMENT PRIMARY KEY, nom_equipement VARCHAR(50) NOT NULL,
  description VARCHAR(200) NULL
) ENGINE=InnoDB;

CREATE TABLE Chambre (
  id_chambre INT AUTO_INCREMENT PRIMARY KEY, numero_chambre VARCHAR(10) NOT NULL,
  id_hotel INT NOT NULL, id_type INT NOT NULL, etage TINYINT NULL,
  prix_nuit DECIMAL(10,2) NOT NULL,
  vue ENUM('Mer','Jardin','Ville','Piscine','Aucune') NULL,
  fumeur BOOLEAN NULL DEFAULT FALSE,
  statut_chambre ENUM('Disponible','Occupée','Maintenance','Nettoyage') NULL,
  CONSTRAINT uq_chambre_hotel_numero UNIQUE (id_hotel, numero_chambre),
  CONSTRAINT fk_chambre_hotel FOREIGN KEY (id_hotel) REFERENCES Hotel(id_hotel),
  CONSTRAINT fk_chambre_type FOREIGN KEY (id_type) REFERENCES Type_Hebergement(id_type)
) ENGINE=InnoDB;

CREATE TABLE Chambre_Equipement (
  id_chambre INT NOT NULL, id_equipement INT NOT NULL,
  PRIMARY KEY (id_chambre, id_equipement),
  CONSTRAINT fk_ce_chambre FOREIGN KEY (id_chambre) REFERENCES Chambre(id_chambre),
  CONSTRAINT fk_ce_equipement FOREIGN KEY (id_equipement) REFERENCES Equipement(id_equipement)
) ENGINE=InnoDB;

CREATE TABLE Employe (
  id_employe INT AUTO_INCREMENT PRIMARY KEY, id_hotel INT NOT NULL,
  nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, poste VARCHAR(50) NOT NULL,
  telephone VARCHAR(20) NULL, email VARCHAR(100) NULL, date_embauche DATE NULL,
  CONSTRAINT fk_employe_hotel FOREIGN KEY (id_hotel) REFERENCES Hotel(id_hotel)
) ENGINE=InnoDB;

CREATE TABLE Promotion (
  id_promotion INT AUTO_INCREMENT PRIMARY KEY, code_promo VARCHAR(30) NOT NULL UNIQUE,
  description VARCHAR(200) NULL, pourcentage_reduction DECIMAL(5,2) NOT NULL,
  date_debut DATE NOT NULL, date_fin DATE NOT NULL, actif BOOLEAN NULL,
  CONSTRAINT chk_promotion_reduction CHECK (pourcentage_reduction BETWEEN 0 AND 100)
) ENGINE=InnoDB;

CREATE TABLE Reservation (
  id_reservation INT AUTO_INCREMENT PRIMARY KEY, id_client INT NOT NULL, id_chambre INT NOT NULL,
  id_employe INT NULL, id_promotion INT NULL, date_reservation DATETIME NULL,
  date_arrivee DATE NOT NULL, date_depart DATE NOT NULL, nb_adultes TINYINT NOT NULL,
  nb_enfants TINYINT NULL,
  statut_reservation ENUM('En attente','Confirmée','Enregistrée','Terminée','Annulée') NULL,
  source_reservation ENUM('Site web','Téléphone','Agence','Sur place') NULL,
  montant_total DECIMAL(10,2) NOT NULL,
  CONSTRAINT chk_reservation_dates CHECK (date_depart > date_arrivee),
  CONSTRAINT fk_reservation_client FOREIGN KEY (id_client) REFERENCES Client(id_client),
  CONSTRAINT fk_reservation_chambre FOREIGN KEY (id_chambre) REFERENCES Chambre(id_chambre),
  CONSTRAINT fk_reservation_employe FOREIGN KEY (id_employe) REFERENCES Employe(id_employe),
  CONSTRAINT fk_reservation_promotion FOREIGN KEY (id_promotion) REFERENCES Promotion(id_promotion)
) ENGINE=InnoDB;

CREATE TABLE Service (
  id_service INT AUTO_INCREMENT PRIMARY KEY, nom_service VARCHAR(50) NOT NULL,
  description VARCHAR(200) NULL, prix_unitaire DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Reservation_Service (
  id_reservation INT NOT NULL, id_service INT NOT NULL, quantite INT NOT NULL DEFAULT 1,
  date_utilisation DATE NULL, prix_unitaire_applique DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id_reservation, id_service),
  CONSTRAINT fk_rs_reservation FOREIGN KEY (id_reservation) REFERENCES Reservation(id_reservation),
  CONSTRAINT fk_rs_service FOREIGN KEY (id_service) REFERENCES Service(id_service)
) ENGINE=InnoDB;

CREATE TABLE Paiement (
  id_paiement INT AUTO_INCREMENT PRIMARY KEY, id_reservation INT NOT NULL,
  montant DECIMAL(10,2) NOT NULL, date_paiement DATETIME NULL,
  mode_paiement ENUM('Carte bancaire','Espèces','Virement','Chèque','PayPal') NOT NULL,
  statut_paiement ENUM('En attente','Validé','Refusé','Remboursé') NULL,
  reference_transaction VARCHAR(100) NULL,
  CONSTRAINT fk_paiement_reservation FOREIGN KEY (id_reservation) REFERENCES Reservation(id_reservation)
) ENGINE=InnoDB;

CREATE TABLE Facture (
  id_facture INT AUTO_INCREMENT PRIMARY KEY, id_reservation INT NOT NULL UNIQUE,
  numero_facture VARCHAR(30) NOT NULL UNIQUE, date_emission DATE NOT NULL,
  montant_ht DECIMAL(10,2) NOT NULL, taux_tva DECIMAL(5,2) NOT NULL,
  montant_ttc DECIMAL(10,2) NOT NULL,
  statut_facture ENUM('Émise','Payée','Impayée','Annulée') NULL,
  CONSTRAINT fk_facture_reservation FOREIGN KEY (id_reservation) REFERENCES Reservation(id_reservation)
) ENGINE=InnoDB;

CREATE TABLE Annulation (
  id_annulation INT AUTO_INCREMENT PRIMARY KEY, id_reservation INT NOT NULL UNIQUE,
  date_annulation DATETIME NULL, motif VARCHAR(255) NULL, montant_rembourse DECIMAL(10,2) NULL,
  CONSTRAINT fk_annulation_reservation FOREIGN KEY (id_reservation) REFERENCES Reservation(id_reservation)
) ENGINE=InnoDB;

CREATE TABLE Avis (
  id_avis INT AUTO_INCREMENT PRIMARY KEY, id_client INT NOT NULL, id_reservation INT NOT NULL UNIQUE,
  note TINYINT NOT NULL, commentaire TEXT NULL, date_avis DATETIME NULL,
  CONSTRAINT chk_avis_note CHECK (note BETWEEN 1 AND 5),
  CONSTRAINT fk_avis_client FOREIGN KEY (id_client) REFERENCES Client(id_client),
  CONSTRAINT fk_avis_reservation FOREIGN KEY (id_reservation) REFERENCES Reservation(id_reservation)
) ENGINE=InnoDB;
