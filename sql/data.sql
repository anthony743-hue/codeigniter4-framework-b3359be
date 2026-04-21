-- ============================================================================
-- INSERTION DES CATÉGORIES
-- ============================================================================
INSERT INTO categories (nom, description) VALUES 
('Informatique', 'Livres sur la programmation, les bases de données et l''IA.'),
('Littérature Classique', 'Œuvres majeures de la littérature mondiale.'),
('Science-Fiction', 'Romans d''anticipation et univers futuristes.'),
('Développement Personnel', 'Ouvrages sur la psychologie et la productivité.'),
('Bande Dessinée', 'Albums illustrés et romans graphiques.');

-- ============================================================================
-- INSERTION DES UTILISATEURS
-- ============================================================================
INSERT INTO utilisateurs (nom, prenom, email, telephone) VALUES 
('Rakoto', 'Jean', 'jean.rakoto@email.mg', '0341122233'),
('Rasoa', 'Marie', 'marie.rasoa@email.mg', '0324455566'),
('Andria', 'Tahina', 'tahina.andria@email.mg', '0337788899'),
('Randria', 'Luc', 'luc.randria@email.mg', '0349900011');

-- ============================================================================
-- INSERTION DES LIVRES
-- ============================================================================
-- Note : status_id 1 = disponible, 2 = prêté
INSERT INTO livres (isbn, titre, auteur, categorie_id, date_publication, resume, status_id) VALUES 
('9782100814756', 'Code Complete', 'Steve McConnell', 1, '2004-06-09', 'Un guide pratique sur la construction de logiciels.', 1),
('9780132350884', 'Clean Code', 'Robert C. Martin', 1, '2008-08-01', 'Artisanat du logiciel et code propre.', 2),
('9782253006329', 'Les Misérables', 'Victor Hugo', 2, '1862-04-03', 'L''histoire de Jean Valjean dans la France du XIXe siècle.', 1),
('9782070415793', '1984', 'George Orwell', 3, '1949-06-08', 'Un roman dystopique sur la surveillance de masse.', 2),
('9782266291330', 'Dune', 'Frank Herbert', 3, '1965-08-01', 'Épopée politique et écologique sur la planète Arrakis.', 1),
('9782221250105', 'Atomic Habits', 'James Clear', 4, '2018-10-16', 'Une méthode simple pour construire de bonnes habitudes.', 1),
('9782205073010', 'Astérix chez les Pictes', 'Jean-Yves Ferri', 5, '2013-10-24', 'Nouvelles aventures des célèbres Gaulois.', 1);

-- ============================================================================
-- INSERTION DES EMPRUNTS
-- ============================================================================
-- 1. Un emprunt déjà rendu (Livre 1 par Utilisateur 1)
INSERT INTO emprunts (livre_id, utilisateur_id, date_emprunt, date_retour, date_retour_prevu, notes) 
VALUES (1, 1, '2026-03-01 10:00:00', '2026-03-15 14:30:00', '2026-03-15 17:00:00', 'Rendu en excellent état.');

-- 2. Un emprunt en cours (Livre 2 par Utilisateur 2)
INSERT INTO emprunts (livre_id, utilisateur_id, date_emprunt, date_retour_prevu) 
VALUES (2, 2, '2026-04-10 09:15:00', '2026-05-10 09:15:00');

-- 3. Un emprunt en cours (Livre 4 par Utilisateur 3)
INSERT INTO emprunts (livre_id, utilisateur_id, date_emprunt, date_retour_prevu) 
VALUES (4, 3, '2026-04-18 16:45:00', '2026-05-18 16:45:00');

-- ============================================================================
-- MISE À JOUR DES TIMESTAMPS DE MODIFICATION (Spécifique PostgreSQL)
-- ============================================================================
UPDATE livre SET date_modification = CURRENT_TIMESTAMP;

INSERT INTO status (nom) VALUES 
('disponible'), ('prete');