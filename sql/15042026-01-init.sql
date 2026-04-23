-- ============================================================================
-- BASE DE DONNÉES : GESTION BIBLIOTHÈQUE (Version MySQL)
-- ============================================================================

DROP DATABASE IF EXISTS Bibliotheque;
CREATE DATABASE Bibliotheque CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE Bibliotheque;

-- Suppression des tables (L'ordre est important à cause des contraintes FK)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS emprunt;
DROP TABLE IF EXISTS livre;
DROP TABLE IF EXISTS categorie;
DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS status;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- TABLE 1 : CATÉGORIES & STATUTS
-- ============================================================================

CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_categorie_nom (nom)
) ENGINE=InnoDB;

CREATE TABLE status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(20) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- ============================================================================
-- TABLE 2 : LIVRES
-- ============================================================================

CREATE TABLE livre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Identifiants et métadonnées
    isbn VARCHAR(20) NOT NULL UNIQUE,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(150) NOT NULL,
    categorie_id INT NOT NULL,
    date_publication DATE NOT NULL,
    resume TEXT,
    
    -- État de disponibilité
    status_id INT NOT NULL,
    isDisponible BOOLEAN DEFAULT TRUE,
    
    -- Informations techniques
    nom_fichier_couverture VARCHAR(255),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Contraintes de clés étrangères
    CONSTRAINT fk_livre_categorie FOREIGN KEY (categorie_id) REFERENCES categorie(id) ON DELETE RESTRICT,
    CONSTRAINT fk_livre_status FOREIGN KEY (status_id) REFERENCES status(id) ON DELETE RESTRICT,
    
    -- MySQL ne gère pas les contraintes CHECK de la même manière que PG sur les anciennes versions,
    -- mais nous gardons la logique via des index pour la performance.
    INDEX idx_livre_isbn (isbn),
    INDEX idx_livre_titre (titre),
    INDEX idx_livre_auteur (auteur)
) ENGINE=InnoDB;

-- ============================================================================
-- TABLE 3 : UTILISATEURS
-- ============================================================================

CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    email VARCHAR(120) UNIQUE,
    telephone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_utilisateur_nom (nom),
    INDEX idx_utilisateur_email (email)
) ENGINE=InnoDB;

-- ============================================================================
-- TABLE 4 : EMPRUNTS
-- ============================================================================

CREATE TABLE emprunt (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livre_id INT NOT NULL,
    utilisateur_id INT NOT NULL,
    
    date_emprunt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_retour DATETIME NULL,
    date_retour_prevu DATETIME NULL,
    
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_emprunt_livre FOREIGN KEY (livre_id) REFERENCES livre(id) ON DELETE CASCADE,
    CONSTRAINT fk_emprunt_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE,
    
    INDEX idx_emprunt_date_retour (date_retour)
) ENGINE=InnoDB;

-- ============================================================================
-- DONNÉES INITIALES
-- ============================================================================

INSERT INTO status (nom) VALUES ('disponible'), ('prete');