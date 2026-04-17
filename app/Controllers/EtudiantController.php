<?php

namespace App\Controllers;

use App\Models\EtudiantModel;

class Etudiant extends BaseController
{
    public function index()
    {
        $e = new EtudiantModel();
        $data['etudiants'] = $e->findAll();
        return view('etudiants', $data);
    }
}