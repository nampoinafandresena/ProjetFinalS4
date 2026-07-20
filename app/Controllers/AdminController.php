<?php 

namespace App\Controllers;

use App\Models\OperateurModel;
use App\Models\PrefixesModel;

class AdminController extends BaseController
{
    public function dashboard(){
        return view('pages/admin-dashboard');
    }

    public function prefixe(){
        $prefixModel = new PrefixesModel();
        $operateurModel = new OperateurModel();
        $listPrefixes = $prefixModel->getPrefixesWithOperateur(); // Méthode qui joint les opérateurs
        $listAllOperateurs = $operateurModel->getAllOperateurs(); 
        
        $data = [
            'prefixes' => $listPrefixes,
            'total' => count($listPrefixes),
            'actifs' => $this->countActifs($listPrefixes),
            'inactifs' => $this->countInactifs($listPrefixes),
            'operateurs' => $this->getOperateursStats($listPrefixes),
            'operateursList' => $listAllOperateurs
        ];
        
        return view('pages/admin-prefixes', $data);
    }

    public function addPrefix(){
        $prefixModel = new PrefixesModel();
        $input = $this->request->getPost();
        if(!$this->validate($prefixModel->getValidationRules())){
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $prefixModel->save($input);
        return redirect()->back()->with('success', 'Préfixe ajouté avec succès.');
    }

    // Méthodes helper pour les statistiques
    private function countActifs($prefixes) {
        $count = 0;
        foreach ($prefixes as $prefix) {
            if (isset($prefix['actif']) && $prefix['actif'] == 1) {
                $count++;
            }
        }
        return $count;
    }

    private function countInactifs($prefixes) {
        $count = 0;
        foreach ($prefixes as $prefix) {
            if (isset($prefix['actif']) && $prefix['actif'] == 0) {
                $count++;
            }
        }
        return $count;
    }

    private function getOperateursStats($prefixes) {
        $stats = [];
        foreach ($prefixes as $prefix) {
            $operateur = $prefix['operateur'] ?? 'Inconnu';
            if (!isset($stats[$operateur])) {
                $stats[$operateur] = 0;
            }
            $stats[$operateur]++;
        }
        return $stats;
    }
}