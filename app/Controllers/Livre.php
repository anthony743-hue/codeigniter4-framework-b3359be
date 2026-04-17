<?php

namespace App\Controllers;

use App\Models\LivreModel;

class Livre extends BaseController
{
    public function index()
    {
        $l = new LivreModel();
        $data['livres'] = $l->findAll();
        return view('livre/list', $data);
    }

    public function getDetailled($id){
        $livreModel = new LivreModel();
        $livre = $livreModel->find($id);
        if (!$livre) {
            return redirect()->to('/livres')->with('error', 'Livre non trouvé.');
        }
        return view('livre/detail', ['livre' => $livre]);
    }
}