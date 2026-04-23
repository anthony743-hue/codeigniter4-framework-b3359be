<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpruntModel extends Model
{
    protected $table            = 'emprunt';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Champs autorisés pour l'écriture
    protected $allowedFields    = [
        'livre_id', 
        'utilisateur_id', 
        'date_emprunt', 
        'date_retour_prevu',
        'date_retour',
        'notes'
    ];

    // Gestion automatique des timestamps
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // Règles de validation
    protected $validationRules = [
        'livre_id'          => 'required|is_not_unique[livre.id]',
        'utilisateur_id'    => 'required|is_not_unique[utilisateur.id]',
        'date_emprunt'      => 'required|valid_date[Y-m-d H:i:s]',
        'date_retour_prevu' => 'permit_empty|valid_date[Y-m-d H:i:s]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getLastEmpruntForLivre($livreId)
    {
        return $this->where('livre_id', $livreId)
                    ->orderBy('date_emprunt', 'DESC')
                    ->first();
    }
}