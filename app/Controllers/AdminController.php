<?php 

namespace App\Controllers;

use App\Models\OperateurModel;
use App\Models\PrefixesModel;
use App\Models\TypeOperationModel;
use App\Models\BaremeFraisDetailsModel;

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

    public function baremeFrais(){
        $baremeModel = new BaremeFraisDetailsModel();
        $typeModel = new TypeOperationModel();
        $operateurModel = new OperateurModel();
        
        // Récupérer les données
        $baremes = $baremeModel->getAllBaremesWithDetails();
        $types = $typeModel->findAll();
        $operateurs = $operateurModel->findAll();
        
        // Grouper les barèmes par type
        $baremesGrouped = [];
        foreach ($baremes as $bareme) {
            $type = $bareme['type_operation'];
            if (!isset($baremesGrouped[$type])) {
                $baremesGrouped[$type] = [];
            }
            $baremesGrouped[$type][] = $bareme;
        }
        
        // Passer toutes les variables à la vue
        $data = [
            'baremes' => $baremes,
            'baremesGrouped' => $baremesGrouped,
            'types' => $types,           // <-- Ajouté
            'operateurs' => $operateurs   // <-- Ajouté
        ];
        
        return view('pages/operator-operations', $data);
    }

    // Ajouter un barème
    public function addBareme() {
        $baremeModel = new BaremeFraisDetailsModel();
        
        $data = [
            'min' => $this->request->getPost('min'),
            'max' => $this->request->getPost('max'),
            'frais' => $this->request->getPost('frais'),
            'id_type_operation' => $this->request->getPost('id_type_operation'),
            'id_operateur' => $this->request->getPost('id_operateur')
        ];
        
        // Vérifier si les données sont valides
        if ($baremeModel->insertBareme($data)) {
            return redirect()->to('/bareme-frais')->with('success', 'Barème ajouté avec succès');
        }
        
        return redirect()->to('/bareme-frais')->with('error', 'Cette tranche existe déjà ou erreur de validation');
    }

    // Supprimer un barème
    public function deleteBareme($id) {
        $baremeModel = new BaremeFraisDetailsModel();
        
        if ($baremeModel->deleteBareme($id)) {
            return redirect()->to('/bareme-frais')->with('success', 'Barème supprimé avec succès');
        }
        
        return redirect()->to('/bareme-frais')->with('error', 'Erreur lors de la suppression');
    }

    // Mettre à jour un barème
    public function updateBareme($id) {
        $baremeModel = new BaremeFraisDetailsModel();
        
        $data = [
            'min' => $this->request->getPost('min'),
            'max' => $this->request->getPost('max'),
            'frais' => $this->request->getPost('frais'),
            'id_type_operation' => $this->request->getPost('id_type_operation'),
            'id_operateur' => $this->request->getPost('id_operateur')
        ];
        
        if ($baremeModel->updateBareme($id, $data)) {
            return redirect()->to('/bareme-frais')->with('success', 'Barème mis à jour avec succès');
        }
        
        return redirect()->to('/bareme-frais')->with('error', 'Cette tranche existe déjà ou erreur de validation');
    }

    // methodes helper pour les statistiques
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