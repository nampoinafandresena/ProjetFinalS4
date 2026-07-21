<?php

namespace App\Controllers;

use App\Models\OperateurModel;
use App\Models\PrefixesModel;
use App\Models\TypeOperationModel;
use App\Models\BaremeFraisModel;
use App\Models\UserModel;
use App\Models\HistoriqueModel;

class AdminController extends BaseController
{
    // ============================================
    // DASHBOARD
    // ============================================

    public function dashboard()
    {
        $userModel = new UserModel();
        $prefixModel = new PrefixesModel();
        $operateurModel = new OperateurModel();
        $historiqueModel = new HistoriqueModel();

        $transactions = $historiqueModel->findAll();

        $data = [
            'total_users' => $userModel->countAll(),
            'total_clients' => $userModel->where('role', 'client')->countAllResults(),
            'total_admins' => $userModel->where('role', 'admin')->countAllResults(),
            'total_prefixes' => count($prefixModel->findAll()),
            'total_operateurs' => count($operateurModel->findAll()),
            'total_transactions' => count($transactions),
            'total_volume' => array_sum(array_column($transactions, 'montant')),
            'total_frais' => array_sum(array_column($transactions, 'frais_appliques')),
            'title' => 'Tableau de bord - Opérateur'
        ];

        return view('pages/admin-dashboard', $data);
    }

    // ============================================
    // GESTION DES OPÉRATEURS ET COMMISSIONS
    // ============================================

    public function operateurs()
    {
        $operateurModel = new OperateurModel();
        $prefixModel = new PrefixesModel();
        
        $operateurs = $operateurModel->findAll();
        
        // Ajouter le nombre de préfixes pour chaque opérateur
        foreach ($operateurs as &$op) {
            $op['nb_prefixes'] = $prefixModel->where('id_operateur', $op['id'])->countAllResults();
        }
        
        $data = [
            'operateurs' => $operateurs,
            'title' => 'Gestion des opérateurs et commissions'
        ];
        
        return view('pages/admin-operateurs', $data);
    }

    public function updateOperateur($id)
    {
        $operateurModel = new OperateurModel();
        
        $commission = $this->request->getPost('commission');
        
        if ($commission === null || $commission < 0 || $commission > 100) {
            return redirect()->to('/admin/operateurs')->with('error', 'Commission invalide (0-100%)');
        }
        
        $data = [
            'commission' => (float)$commission
        ];
        
        if ($operateurModel->update($id, $data)) {
            return redirect()->to('/admin/operateurs')->with('success', 'Commission mise à jour avec succès');
        }
        
        return redirect()->to('/admin/operateurs')->with('error', 'Erreur lors de la mise à jour');
    }

    // ============================================
    // GESTION DES PRÉFIXES
    // ============================================

    public function prefixe()
    {
        $prefixModel = new PrefixesModel();
        $operateurModel = new OperateurModel();
        $listPrefixes = $prefixModel->getPrefixesWithOperateur();
        $listAllOperateurs = $operateurModel->getAllOperateurs();

        $data = [
            'prefixes' => $listPrefixes,
            'total' => count($listPrefixes),
            'actifs' => $this->countActifs($listPrefixes),
            'inactifs' => $this->countInactifs($listPrefixes),
            'operateurs' => $this->getOperateursStats($listPrefixes),
            'operateursList' => $listAllOperateurs,
            'title' => 'Gestion des préfixes'
        ];

        return view('pages/admin-prefixes', $data);
    }

    public function addPrefix()
    {
        $prefixModel = new PrefixesModel();
        $input = $this->request->getPost();

        if (!$this->validate($prefixModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $prefixModel->save($input);
        return redirect()->to('/admin/prefixe')->with('success', 'Préfixe ajouté avec succès.');
    }

    public function togglePrefix($id)
    {
        $prefixModel = new PrefixesModel();
        if ($prefixModel->togglePrefix($id)) {
            return redirect()->to('/admin/prefixe')->with('success', 'Préfixe activé/désactivé avec succès');
        }
        return redirect()->to('/admin/prefixe')->with('error', 'Erreur lors de la modification');
    }

    public function deletePrefix($id)
    {
        $prefixModel = new PrefixesModel();
        if ($prefixModel->delete($id)) {
            return redirect()->to('/admin/prefixe')->with('success', 'Préfixe supprimé avec succès');
        }
        return redirect()->to('/admin/prefixe')->with('error', 'Erreur lors de la suppression');
    }

    // ============================================
    // BARÈME FRAIS (UNIQUEMENT TELMA)
    // ============================================

    public function baremeFrais()
    {
        $baremeModel = new BaremeFraisModel();
        $typeModel = new TypeOperationModel();
        $operateurModel = new OperateurModel();

        // Récupérer l'ID de Telma
        $telma = $operateurModel->where('operateur', 'Telma')->first();
        $telmaId = $telma['id'] ?? null;

        // Filtrer les barèmes pour ne garder que Telma
        $baremes = $baremeModel->getAllBaremesWithDetails();
        $baremes = array_filter($baremes, function($b) use ($telmaId) {
            return $b['id_operateur'] == $telmaId;
        });

        $types = $typeModel->findAll();
        $operateurs = $operateurModel->findAll();

        $baremesGrouped = [];
        foreach ($baremes as $bareme) {
            $type = $bareme['type_operation'] ?? 'Inconnu';
            if (!isset($baremesGrouped[$type])) {
                $baremesGrouped[$type] = [];
            }
            $baremesGrouped[$type][] = $bareme;
        }

        $data = [
            'baremes' => $baremes,
            'baremesGrouped' => $baremesGrouped,
            'types' => $types,
            'operateurs' => $operateurs,
            'title' => 'Gestion des barèmes de frais'
        ];

        return view('pages/operator-operations', $data);
    }

    public function addBareme()
    {
        $baremeModel = new BaremeFraisModel();

        $data = [
            'min' => $this->request->getPost('min'),
            'max' => $this->request->getPost('max'),
            'frais' => $this->request->getPost('frais'),
            'id_type_operation' => $this->request->getPost('id_type_operation'),
            'id_operateur' => $this->request->getPost('id_operateur')
        ];

        // Vérifier si la tranche existe déjà
        $existing = $baremeModel
            ->where('id_type_operation', $data['id_type_operation'])
            ->where('id_operateur', $data['id_operateur'])
            ->where('min <=', $data['max'])
            ->where('max >=', $data['min'])
            ->first();

        if ($existing) {
            return redirect()->to('/admin/bareme-frais')->with('error', 'Cette tranche de montant existe déjà pour cet opérateur.');
        }

        if ($baremeModel->insert($data)) {
            return redirect()->to('/admin/bareme-frais')->with('success', 'Barème ajouté avec succès');
        }

        return redirect()->to('/admin/bareme-frais')->with('error', 'Erreur lors de l\'ajout');
    }

    public function deleteBareme($id)
    {
        $baremeModel = new BaremeFraisModel();

        if ($baremeModel->delete($id)) {
            return redirect()->to('/admin/bareme-frais')->with('success', 'Barème supprimé avec succès');
        }

        return redirect()->to('/admin/bareme-frais')->with('error', 'Erreur lors de la suppression');
    }

    public function updateBareme($id)
    {
        $baremeModel = new BaremeFraisModel();

        $data = [
            'min' => $this->request->getPost('min'),
            'max' => $this->request->getPost('max'),
            'frais' => $this->request->getPost('frais'),
            'id_type_operation' => $this->request->getPost('id_type_operation'),
            'id_operateur' => $this->request->getPost('id_operateur')
        ];

        // Vérifier si la tranche existe déjà (sauf pour l'élément en cours)
        $existing = $baremeModel
            ->where('id_type_operation', $data['id_type_operation'])
            ->where('id_operateur', $data['id_operateur'])
            ->where('min <=', $data['max'])
            ->where('max >=', $data['min'])
            ->where('id !=', $id)
            ->first();

        if ($existing) {
            return redirect()->to('/admin/bareme-frais')->with('error', 'Cette tranche de montant existe déjà pour cet opérateur.');
        }

        if ($baremeModel->update($id, $data)) {
            return redirect()->to('/admin/bareme-frais')->with('success', 'Barème mis à jour avec succès');
        }

        return redirect()->to('/admin/bareme-frais')->with('error', 'Erreur lors de la mise à jour');
    }

    // ============================================
    // CLIENTS
    // ============================================

    public function clients()
    {
        $userModel = new UserModel();
        $clients = $userModel->getClients();

        $data = [
            'clients' => $clients,
            'title' => 'Liste des clients'
        ];

        return view('pages/operator-clients', $data);
    }

    // ============================================
    // MÉTHODES HELPER
    // ============================================

    private function countActifs($prefixes)
    {
        $count = 0;
        foreach ($prefixes as $prefix) {
            if (isset($prefix['actif']) && $prefix['actif'] == 1) {
                $count++;
            }
        }
        return $count;
    }

    private function countInactifs($prefixes)
    {
        $count = 0;
        foreach ($prefixes as $prefix) {
            if (isset($prefix['actif']) && $prefix['actif'] == 0) {
                $count++;
            }
        }
        return $count;
    }

    private function getOperateursStats($prefixes)
    {
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