-- Insérer les catégories
INSERT INTO categorie (nom, description) VALUES
('Informatique', 'Livres sur la programmation et les technologies'),
('Roman', 'Littérature générale et contes'),
('Finance', 'Économie et gestion d''entreprise'),
('Sciences', 'Livres scientifiques et techniques'),
('Histoire', 'Récits historiques et documentaires');

INSERT INTO status (nom) VALUES
('Disponible'), ('Prete');

-- Insérer quelques livres
INSERT INTO livre (isbn, titre, auteur, categorie_id, resume, date_publication, status_id) VALUES
('978-2-213-65670-1', 'Clean Code', 'Robert C. Martin', 1, 'Un guide complet pour écrire du code propre et maintenable.', CURRENT_DATE, 1),
('978-2-100-80535-6', 'Les Misérables', 'Victor Hugo', 2, 'L''épopée de Jean Valjean dans la France du XIXe siècle.', CURRENT_DATE, 1),
('978-0-596-00712-6', 'JavaScript: The Good Parts', 'Douglas Crockford', 1, 'Les éléments essentiels de JavaScript.', CURRENT_DATE, 1),
('978-2-253-06963-2', 'Le Seigneur des Anneaux', 'J.R.R. Tolkien', 2, 'L''aventure épique au cœur de la Terre du Milieu.', CURRENT_DATE, 1),
('978-2-707-14285-6', 'Freakonomics', 'Steven D. Levitt', 3, 'L''économie cachée des choses ordinaires.', CURRENT_DATE, 1);

-- Insérer des utilisateurs de test
INSERT INTO utilisateur (nom, prenom, email) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com'),
('Martin', 'Marie', 'marie.martin@example.com'),
('Bernard', 'Luc', 'luc.bernard@example.com');