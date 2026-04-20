<?php

namespace App\Controllers;

use App\Models\LivreModel;

class Livre extends BaseController
{
    public function index($p=1)
    {
        $request = $this->request;
        $title = $request->getGet('title')  ?? '';
        $categorie = $request->getGet('categorie')  ?? '';

        $p = max(1, (int)$p);
        // $db = \Config\Database::connect();
        // dd($db->getVersion());
        $l = new LivreModel();
        $data['livres'] = $l->getLivres($title, $categorie, $p);
        return view('livre/list', $data);
    }

    public function getDetailled($id){
        $livreModel = new LivreModel();
        $livre = $livreModel->find($id);
        if (!$livre) {
            return abort(404, 'Livre non trouvé');
        }
        $empruntModel = new \App\Models\EmpruntModel();
        $dernierEmprunt = $empruntModel->getLastEmpruntForLivre($id);
        $livre['emprunt'] = $dernier;
        return view('livre/detail', ['livre' => $livre]);
    }

    public function add(){
        $request = $this->request;
        if ($request->getMethod() === 'post') {
            $data = $request->getPost();
            $date_publication = $data['date_publication'] ?? '';
            $livreModel = new LivreModel();

            $data['errors'] = [];
            if( $livreModel->pasDansLeFutur($date_publication) === false){
                $data['errors']['date_publication'] = 'La date de publication ne peut pas être dans le futur.';
                return view('livre/add', $data);
            }

            if ($livreModel->insert($data)) {
                return redirect()->to('/livres').with('success', 'Livre ajouté avec succès');
            } else {
                $data['errors']['general'] = $livreModel->errors();
                return view('livre/add', $data);
            }
        } else {
            return view('livre/add');
        }
    }

    public function delete($id){
        $livreModel = new LivreModel();
        if ($livreModel->delete($id)) {
            return redirect()->to('/livres').with('success', 'Livre supprimé avec succès');
        } else {
            return redirect()->to('/livres').with('error', 'Erreur lors de la suppression du livre');
        }
    }

    public function pret($id){
        $livreModel = new LivreModel();
        $livre = $livreModel->find($id);
        if(!$livre){
            $data['error'] = 'Livre non trouvé';
            return view('livres/add', $data);
        }
    }   

    public function retour($id){

    }
}