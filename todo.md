Voici le guide de réalisation de votre TP sous forme de fichier **Markdown (.md)**, structuré en To-Do List détaillée pour suivre votre progression étape par étape.

---

# 📚 Checklist : Développement Application Bibliothèque CI4

## 🗄️ Phase 1 : Infrastructure & Configuration
- [ ] **1.1 Initialisation de la BDD** : Créer la base `bibliotheque` avec un encodage **UTF-8**[cite: 8].
- [ ] **1.2 Création de la table `livres`** : Inclure les colonnes pour les métadonnées (titre, auteur, ISBN unique, année), le contenu (résumé, fichier couverture) et l'état (statut, timestamps)[cite: 9].
- [ ] **1.3 Création de la table `emprunts`** : Préparer la structure pour le traçage (identifiant, lien vers le livre, nom de l'emprunteur, dates)[cite: 10].
- [ ] **1.4 Connexion au Framework** : Configurer les accès à la base de données dans le fichier `.env` ou `app/Config/Database.php`[cite: 11].

## 🛣️ Phase 2 : Architecture du Routage
- [ ] **2.1 Routes de consultation** :
    - [ ] `GET /` : Page d'accueil listant les ouvrages[cite: 14].
    - [ ] `GET /livres/detail/(:num)` : Affichage de la fiche individuelle[cite: 15].
- [ ] **2.2 Routes de gestion (CRUD)** :
    - [ ] `GET /livres/create` : Accès au formulaire d'ajout[cite: 16].
    - [ ] `POST /livres/store` : Traitement de l'enregistrement[cite: 16].
    - [ ] `POST /livres/delete/(:num)` : Action de suppression[cite: 17].
- [ ] **2.3 Routes de mouvement** :
    - [ ] `POST /livres/preter/(:num)` : Validation d'un prêt[cite: 18].
    - [ ] `POST /livres/retourner/(:num)` : Validation d'un retour[cite: 18].

## 🏗️ Phase 3 : Intelligence des Modèles (MVC)
- [ ] **3.1 LivreModel** :
    - [ ] Configurer les champs autorisés et les timestamps automatiques[cite: 25].
    - [ ] Implémenter les règles de validation (titre, auteur, ISBN unique, année obligatoire)[cite: 27, 28].
    - [ ] Personnaliser les messages d'erreur en français[cite: 29].
    - [ ] Ajouter une méthode de validation pour empêcher les dates dans le futur[cite: 30].
    - [ ] Développer la logique de recherche (mot-clé/catégorie) et de pagination (10/page)[cite: 31, 32].
- [ ] **3.2 EmpruntModel** :
    - [ ] Déclarer la table et les champs[cite: 35].
    - [ ] Créer la méthode pour récupérer l'historique le plus récent d'un livre[cite: 36].

## 🎮 Phase 4 : Pilotage par les Contrôleurs
- [ ] **4.1 Contrôleur `Livre`** :
    - [ ] `index()` : Orchestrer l'affichage paginé et les résultats de recherche[cite: 39, 40].
    - [ ] `detail()` : Gérer la récupération des données ou l'erreur 404[cite: 41].
    - [ ] `store()` : Gérer la validation stricte de l'image (type, poids 2Mo) et le stockage physique[cite: 47, 48, 49].
- [ ] **4.2 Contrôleur `Mouvement`** :
    - [ ] `preter()` : Vérifier la disponibilité réelle avant d'enregistrer le prêt[cite: 57, 59].
    - [ ] `retourner()` : Identifier l'emprunt actif pour clore la transaction et libérer le livre[cite: 60].

## 🎨 Phase 5 : Interface & Expérience Utilisateur
- [ ] **5.1 Architecture Template** : Créer le layout commun avec navigation et alertes flash[cite: 63].
- [ ] **5.2 Vue Catalogue** :
    - [ ] Intégrer le formulaire de recherche dynamique[cite: 65].
    - [ ] Construire le tableau avec indicateurs de statut colorés (vert/rouge)[cite: 66].
    - [ ] Mettre en place les formulaires d'action inline et la pagination[cite: 67, 68, 70].
- [ ] **5.3 Vue Formulaire** :
    - [ ] Utiliser `old()` pour conserver les saisies après une erreur[cite: 75].
    - [ ] Afficher les messages d'erreur ciblés sous chaque champ[cite: 74].

## 🛡️ Phase 6 : Sécurité & Validation Finale
- [ ] **6.1 CSRF Global** : Activer le filtre de protection dans `Filters.php`[cite: 79].
- [ ] **6.2 Intégrité des Formulaires** : Vérifier la présence du jeton dans chaque formulaire `POST`[cite: 80].
- [ ] **6.3 Prévention XSS** : Appliquer systématiquement la fonction `esc()` sur les données affichées[cite: 83].
- [ ] **6.4 Contraintes Client** : Brider la saisie de l'année au maximum actuel directement dans le HTML[cite: 77].

---