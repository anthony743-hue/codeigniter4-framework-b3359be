CREATE DATABASE bibliotheque CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bibliotheque;

CREATE TABLE status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE livres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(150) NOT NULL,
    isbn VARCHAR(20) UNIQUE NOT NULL,
    annee_publication INT NOT NULL,
    categorie VARCHAR(100),
    resume TEXT,
    nom_fichier_couverture VARCHAR(255),
    statut ENUM('disponible', 'prêté') DEFAULT 'disponible',
    created_at DATETIME,
    updated_at DATETIME
);

ALTER TABLE livres ADD COLUMN description TEXT;

CREATE TABLE emprunts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livre_id INT NOT NULL,
    nom_emprunteur VARCHAR(150) NOT NULL,
    date_emprunt DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_retour DATETIME NULL,
    FOREIGN KEY (livre_id) REFERENCES livres(id) ON DELETE CASCADE
);

-- Insertion des statuts
INSERT INTO status (nom) VALUES ('disponible');
INSERT INTO status (nom) VALUES ('prete');