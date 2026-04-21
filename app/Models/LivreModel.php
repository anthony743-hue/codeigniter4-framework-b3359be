<?php

namespace App\Models;

use CodeIgniter\Model;

class LivreModel extends Model
{
    protected $table            = 'livres';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['isbn', 'titre', 'auteur', 'categorie_id', 'resume', 'status_id', 'date_publication', 'isDisponible', 'image_couverture', 'nom_fichier_couverture'];
    
    // Dates
    protected $useTimestamps    = true;
    protected $createdField     = 'date_creation';
    protected $updatedField     = 'date_modification';
    protected $returnType = 'object';

    // Règles de validation
    protected $validationRules = [
        'titre'            => 'required|min_length[3]',
        'auteur'           => 'required',
        'isbn'             => 'required|is_unique[livre.isbn,id,{id}]',
        'date_publication' => 'permit_empty|required|valid_date[Y-m-d]',
        'image_couverture' => 'permit_empty|is_image[image_couverture]|mime_in[image_couverture,image/jpeg,image/png,image/webp]|max_size[image_couverture,2048]'
        ];

    // Messages d'erreur personnalisés (Optionnel)
    protected $validationMessages = [
        'titre' => [
            'required'   => 'Le titre est obligatoire.',
            'min_length' => 'Le titre doit comporter au moins 3 caractères.'
        ],
        'isbn' => [
            'is_unique'  => 'Cet ISBN existe déjà dans la base de données.'
        ],
        'date_publication' => [
            'required'   => 'La date de publication est obligatoire.'
        ],
        'image_couverture' => [
            'is_image'   => 'Le fichier doit être une image.',
            'mime_in'    => 'Le fichier doit être au format JPG, JPEG, PNG ou WEBP.',
            'max_size'   => 'La taille de l\'image ne doit pas dépasser 2MB.'
        ]
    ];

    // Paramétrage du comportement
    protected $skipValidation = false;

    public function getLivres($title = '', $categorie = '', $page = 1, $step = 10)
    {
        $builder = $this->builder();
        // if ($title == '') {
        //     die('Aucun titre fourni, affichage de tous les livres.');    
        // }
        // $builder->like('titre', $title);
        if ($categorie) {
            $builder->where('categorie_id', $categorie);
        }
        $offset = ($page - 1) * $step;
        return $builder->get($step, $offset)->getResultObject();
    }

    public function pasDansLeFutur(string $str, string &$error = null): bool
    {
        $dateSaisie = strtotime($str);
        $maintenant = time();

        if ($dateSaisie > $maintenant) {
            $error = "La date de publication ne peut pas être dans le futur.";
            return false;
        }

        return true;
    }
}