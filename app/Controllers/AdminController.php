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
    // GESTION DES PRÉFIXES
    // ============================================

    public function prefixe()
    {
        $prefixModel = new PrefixesModel();
        $operateurModel = new OperateurModel();
        $listPrefixes = $prefixModel->getPrefixesWithOperateur(3);
        $listPrefixesOtherOperateurs = $prefixModel->getPrefixesByOperateurWithOperateur(3);
        $listAllOperateurs = $operateurModel->getAllOperateurs();

        $data = [
            'prefixes' => $listPrefixes,
            'otherPrefixes' => $listPrefixesOtherOperateurs,
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
    public function clients()
    {
        $userModel = new UserModel();
        $historiqueModel = new HistoriqueModel();
        
        // Récupérer les clients avec pagination
        $clients = $userModel->getClientsPaginated(10);
        
        // Calculer les statistiques
        $totalClients = $userModel->where('role', 'client')->countAllResults();
        $totalFonds = $userModel->selectSum('solde')
                                ->where('role', 'client')
                                ->first()['solde'] ?? 0;
        
        // Pour chaque client, récupérer la date de dernière transaction
        foreach ($clients as &$client) {
            $lastTransaction = $historiqueModel
                ->where('user1', $client['id'])
                ->orWhere('user2', $client['id'])
                ->orderBy('date_transaction', 'DESC')
                ->first();
            
            $client['last_transaction'] = $lastTransaction ? $lastTransaction['date_transaction'] : null;
            $client['initials'] = substr($client['numero'], -2);
        }
        
        $data = [
            'clients' => $clients,
            'total_clients' => $totalClients,
            'total_fonds' => $totalFonds,
            'pager' => $userModel->pager,
            'title' => 'Gestion des clients'
        ];

        return view('pages/operator-clients', $data);
    }

    /**
     * Génère les initiales à partir du numéro de téléphone
     */
    private function getInitials($numero)
    {
        // Prendre les 2 derniers chiffres du numéro
        $lastDigits = substr($numero, -2);
        // Ou prendre les 2 premières lettres du nom (si vous avez un champ nom)
        // Pour l'instant, on utilise les 2 derniers chiffres
        return $lastDigits;
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