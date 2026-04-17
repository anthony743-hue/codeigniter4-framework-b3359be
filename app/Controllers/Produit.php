<?php

namespace App\Controllers;

use App\Models\ProduitModel;

class Produit extends BaseController
{
    public function index()
    {
        $p = new ProduitModel();
        $data['titre'] = "salut tout le monde";
        return view('produits', $data);
    }

    public function show($id){
        $p = new ProduitModel();
        $data['produit'] = $p->find($id);
        return view('produit', $data);
    }
}