DROP DATABASE IF EXISTS Bibliotheque;
CREATE DATABASE Bibliotheque;

-- ============================================================================
-- BASE DE DONNÉES : GESTION BIBLIOTHÈQUE CODEIGNITER
-- SGBD : PostgreSQL
-- Structure optimale : 4 tables
-- ============================================================================

-- Suppression des tables si elles existent (dev/test)
DROP TABLE IF EXISTS emprunt CASCADE;
DROP TABLE IF EXISTS livre CASCADE;
DROP TABLE IF EXISTS categorie CASCADE;
DROP TABLE IF EXISTS utilisateur CASCADE;

-- ============================================================================
-- TABLE 1 : CATÉGORIES (Données statiques/semi-statiques)
-- ============================================================================
CREATE TABLE categorie (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE status (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(20) NOT NULL UNIQUE
);

-- Index sur le nom pour les recherches rapides
CREATE INDEX idx_categorie_nom ON categorie(nom);

-- ============================================================================
-- TABLE 2 : LIVRES (Catalogue principal)
-- ============================================================================
CREATE TABLE livre (
    id SERIAL PRIMARY KEY,
    
    -- Identifiants et métadonnées
    isbn VARCHAR(20) NOT NULL UNIQUE,
    titre VARCHAR(255) NOT NULL CHECK (length(titre) >= 3),
    auteur VARCHAR(150) NOT NULL,
    categorie_id INTEGER NOT NULL REFERENCES categorie(id) ON DELETE RESTRICT,
    date_publication date NOT NULL CHECK (date_publication <= CURRENT_DATE),
    resume TEXT,
    -- Informations du livre
    -- État de disponibilité
    status_id INTEGER NOT NULL REFERENCES status(id) ON DELETE RESTRICT
);

ALTER TABLE livre ADD COLUMN nom_fichier_couverture TEXT;
ALTER TABLE livre ADD COLUMN date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE livre ADD COLUMN date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- Indices pour optimiser les recherches
CREATE INDEX idx_livre_isbn ON livre(isbn);
CREATE INDEX idx_livre_titre ON livre(titre);
CREATE INDEX idx_livre_categorie_id ON livre(categorie_id);
CREATE INDEX idx_livre_auteur ON livre(auteur);

-- ============================================================================
-- TABLE 3 : UTILISATEURS (Emprunteurs)
-- ============================================================================
CREATE TABLE utilisateur (
    id SERIAL PRIMARY KEY,
    -- Identité
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(120) UNIQUE,
    telephone VARCHAR(20),

    -- Statut    
    -- Métadonnées
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indices
CREATE INDEX idx_utilisateur_nom ON utilisateur(nom);
CREATE INDEX idx_utilisateur_email ON utilisateur(email);

-- ============================================================================
-- TABLE 4 : EMPRUNTS (Historique et mouvements)
-- ============================================================================
CREATE TABLE emprunt (
    id SERIAL PRIMARY KEY,
    
    -- Références
    livre_id INTEGER NOT NULL REFERENCES livre(id) ON DELETE CASCADE,
    utilisateur_id INTEGER NOT NULL REFERENCES utilisateur(id) ON DELETE CASCADE,
    
    -- Dates importantes
    date_emprunt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_retour_prevu TIMESTAMP,
    date_retour_reel TIMESTAMP,    
    -- Métadonnées
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
SELECT * FROM emprunt WHERE livre_id = 1 ORDER BY date_emprunt DESC LIMIT 1;

-- Indices pour optimiser les requêtes
CREATE INDEX idx_emprunt_livre_id ON emprunt(livre_id);
CREATE INDEX idx_emprunt_utilisateur_id ON emprunt(utilisateur_id);
CREATE INDEX idx_emprunt_date_emprunt ON emprunt(date_emprunt);