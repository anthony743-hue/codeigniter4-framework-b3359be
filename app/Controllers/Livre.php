<?php

namespace App\Controllers;

use App\Models\LivreModel;
use App\Models\StatusModel;
use App\Models\EmpruntModel;
use App\Models\EtudiantModel;
use App\Models\CategorieModel;

class Livre extends BaseController
{
    private function getLivre($id)
    {
        $livreModel = new LivreModel();
        $livre = $livreModel->find($id);
        if (!$livre) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Livre non trouvé');
        }
        return $livre;
    }

    private function getStatusId($nom)
    {
        $statusModel = new StatusModel();
        $status = $statusModel->getStatusByNom($nom);
        return $status ? $status->id : null;
    }

    public function index($p = 1)
    {
        $request = $this->request;
        $title = $request->getGet('title') ?? '';
        $categorie = $request->getGet('categorie') ?? '';

        $p = max(1, (int)$p);
        $livreModel = new LivreModel();
        $categorieModel = new CategorieModel();
        
        $data['livres'] = $livreModel->getLivres($title, $categorie, $p);
        $data['categories'] = $categorieModel->findAll();
        $data['title_filter'] = $title;
        $data['categorie_filter'] = $categorie;
        
        return view('livre/list', $data);
    }

    public function getDetailled($id)
    {
        $livreModel = new LivreModel();
        $statusModel = new StatusModel();
        $categorieModel = new CategorieModel();
        $empruntModel = new EmpruntModel();
        $livre = $livreModel->find($id);
        
        // Vérifier si le livre existe
        if (!$livre) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Livre non trouvé");
        }
        $status = $statusModel->find($livre->status_id);
        
        // Récupérer la catégorie du livre
        $categorie = $categorieModel->find($livre->categorie_id);
        
        // Récupérer le dernier emprunt
        $dernierEmprunt = $empruntModel->getLastEmpruntForLivre($id);
 
        // Préparer les données pour la vue
        $data = [
            'livre' => $livre,
            'status' => $status,
            'categorie' => $categorie,
            'dernierEmprunt' => $dernierEmprunt,
            'title' => $livre->titre,
        ];
 
        return view('livre/detailled', $data);
    }

    public function add()
    {
        $request = $this->request;
        $categorieModel = new CategorieModel();
        
        if ($request->getMethod() === 'post') {
            $data = $request->getPost();
            $date_publication = $data['date_publication'] ?? '';
            $livreModel = new LivreModel();

            $dt['errors'] = [];
            
            // Validation manuelle de la date
            if (!$livreModel->pasDansLeFutur($date_publication)) {
                $dt['errors']['date_publication'] = 'La date de publication ne peut pas être dans le futur.';
            }

            // Gestion de l'upload d'image
            $image = $request->getFile('image_couverture');
            if ($image && $image->isValid()) {
                // Vérifier le type de fichier
                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                if (!in_array($image->getMimeType(), $allowedTypes)) {
                    $dt['errors']['image_couverture'] = 'Le fichier doit être au format JPEG, PNG ou WEBP.';
                }
                
                // Vérifier la taille (2 Mo maximum)
                if ($image->getSize() > 2 * 1024 * 1024) {
                    $dt['errors']['image_couverture'] = 'La taille de l\'image ne doit pas dépasser 2 Mo.';
                }
                
                // Si pas d'erreur, déplacer le fichier
                if (empty($dt['errors']['image_couverture'])) {
                    $newName = $image->getRandomName();
                    $image->move(ROOTPATH . 'public/uploads/', $newName);
                    $data['nom_fichier_couverture'] = $newName;
                }
            } elseif ($image && !$image->isValid()) {
                // Erreur d'upload
                $dt['errors']['image_couverture'] = 'Erreur lors de l\'upload de l\'image.';
            }

            // Si pas d'erreurs, tenter l'insertion
            if (empty($dt['errors'])) {
                // Définir le statut par défaut à "disponible"
                $data['status_id'] = $this->getStatusId('disponible');
                
                if ($livreModel->insert($data)) {
                    return redirect()->to('/livres')->with('success', 'Livre ajouté avec succès');
                } else {
                    $dt['errors']['general'] = $livreModel->errors();
                }
            }

            // En cas d'erreur, réafficher le formulaire avec les erreurs et les catégories
            $dt['categories'] = $categorieModel->findAll();
            return view('livre/add', $dt);
        } else {
            // Affichage du formulaire
            $data['categories'] = $categorieModel->findAll();
            return view('livre/add', $data);
        }
    }

    public function delete($id)
    {
        $this->getLivre($id); // Just to check if exists
        $livreModel = new LivreModel();
        if ($livreModel->delete($id)) {
            return redirect()->to('/livres')->with('success', 'Livre supprimé avec succès');
        } else {
            return redirect()->to('/livres')->with('error', 'Erreur lors de la suppression du livre');
        }
    }

    public function pret($id)
    {
        $request = $this->request;
        $livre = $this->getLivre($id);

        if ($request->getMethod() == 'get') {
            $data['livre'] = $livre;
            return view('livre/pret', $data);
        } else {
            $disponibleId = $this->getStatusId('disponible');
            if ($livre->status_id != $disponibleId) {
                return redirect()->to('/livres')->with('error', 'Le livre est indisponible');
            }

            $postData = $request->getPost();
            $emprunteurNom = $postData['emprunteur_nom'] ?? '';
            
            if (empty($emprunteurNom)) {
                return redirect()->to('/livres')->with('error', 'Le nom de l\'emprunteur est requis');
            }

            // Chercher ou créer l'utilisateur
            $etudiantModel = new EtudiantModel();
            $etudiant = $etudiantModel->where('nom', $emprunteurNom)->first();
            
            if (!$etudiant) {
                // Créer un nouvel utilisateur
                $etudiantId = $etudiantModel->insert(['nom' => $emprunteurNom]);
                if (!$etudiantId) {
                    return redirect()->to('/livres')->with('error', 'Erreur lors de la création de l\'emprunteur');
                }
            } else {
                $etudiantId = $etudiant['id'];
            }

            // Créer l'emprunt
            $empruntData = [
                'livre_id' => $id,
                'utilisateur_id' => $etudiantId,
                'date_emprunt' => date('Y-m-d H:i:s'),
                'date_retour_prevu' => date('Y-m-d H:i:s', strtotime('+30 days')), // 30 jours par défaut
            ];
            
            $empruntModel = new EmpruntModel();
            if ($empruntModel->insert($empruntData)) {
                $livre->status_id = $this->getStatusId('prete');
                $livreModel = new LivreModel();
                $livreModel->save($livre);

                return redirect()->to('/livres')->with('success', 'Livre emprunté avec succès');
            } else {
                return redirect()->to('/livres')->with('error', 'Erreur lors de l\'emprunt du livre');
            }
        }
    }

    public function retour($id)
    {
        $request = $this->request;
        $livre = $this->getLivre($id);

        if ($request->getMethod() == 'get') {
            $data['livre'] = $livre;
            return view('livre/retour', $data);
        } else {
            $empruntModel = new EmpruntModel();
            $dernierEmprunt = $empruntModel->getLastEmpruntForLivre($id);
            if (!$dernierEmprunt || $dernierEmprunt['date_retour'] != null) {
                return redirect()->to('/livres')->with('error', 'Le livre n\'est pas actuellement emprunté');
            }

            // Mettre à jour l'emprunt avec la date de retour
            $updateData = [
                'id' => $dernierEmprunt['id'],
                'date_retour' => date('Y-m-d H:i:s'),
            ];
            
            if ($empruntModel->save($updateData)) {
                $livre->status_id = $this->getStatusId('disponible');
                $livreModel = new LivreModel();
                $livreModel->save($livre);

                return redirect()->to('/livres')->with('success', 'Livre retourné avec succès');
            } else {
                return redirect()->to('/livres')->with('error', 'Erreur lors du retour du livre');
            }
        }
    }
}