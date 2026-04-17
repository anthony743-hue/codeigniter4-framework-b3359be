<?php

namespace App\Models;

use CodeIgniter\Model;

class LivreModel extends Model
{
    protected $table = 'livre';
    protected $primaryKey = 'id';
    protected $allowedFields = ['isbn', 'titre', 'auteur', 'categorie_id', 'resume', 'status_id', 'date_publication'];
    protected $useTimestamps = true;
}