<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusModel extends Model
{
    protected $table = 'status';
    protected $allowedFields = ['id', 'nom'];
    protected $returnType = 'object';


    public function getStatusByNom($nom)
    {
        return $this->where('nom', $nom)->first();
    }
}