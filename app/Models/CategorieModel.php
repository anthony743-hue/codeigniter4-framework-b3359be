<?php

namespace App\Models;

use CodeIgniter\Model;

class CategorieModel extends Model
{
    // Configuration de la table
    protected $table            = 'categorie';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
        protected $returnType = 'object';
 // Ou 'object' selon votre préférence
    protected $useSoftDeletes   = false;
    

    // Champs autorisés pour l'insertion et la mise à jour (Sécurité)
    protected $allowedFields    = ['nom', 'description'];

    // Gestion du temps
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // À laisser vide si non présent dans la table
    protected $dateFormat       = 'datetime';

    // Règles de validation intégrées
    protected $validationRules      = [
        'nom' => 'required|min_length[3]|max_length[100]|is_unique[categorie.nom,id,{id}]',
    ];
    
    protected $validationMessages   = [
        'nom' => [
            'is_unique' => 'Cette catégorie existe déjà.',
            'required'  => 'Le nom de la catégorie est obligatoire.'
        ],
    ];

    /**
     * Récupérer toutes les catégories triées par nom
     */
    public function getAllCategories()
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }

    /**
     * Recherche spécifique par nom (Utile pour le filtrage)
     */
    public function getByNom(string $nom)
    {
        return $this->where('nom', $nom)->first();
    }
}