<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\HistoriqueModel;
use App\Models\BaremeFraisModel;
use App\Models\TypeOperationModel;
use App\Models\PrefixesModel;

class OperationController extends BaseController
{
    protected $userModel;
    protected $historiqueModel;
    protected $baremeFraisModel;
    protected $typeOperationModel;
    protected $prefixesModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->historiqueModel = new HistoriqueModel();
        $this->baremeFraisModel = new BaremeFraisModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->prefixesModel = new PrefixesModel();
    }
    
    // ============================================
    // DÉPÔT (SANS FRAIS)
    // ============================================
    
    public function depot()
    {
        if (!session()->get('user')) {
            return redirect()->to('/client/login')->with('error', 'Veuillez vous connecter');
        }
        
        $user = session()->get('user');
        $montant = $this->request->getPost('montant');
        
        if (empty($montant) || $montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide');
        }
        
        $montant = (float)$montant;
        
        $result = $this->userModel->updateSolde($user['id'], $montant, 'add');
        if (!$result) {
            return redirect()->back()->with('error', 'Erreur lors du dépôt');
        }
        
        $typeDepot = $this->typeOperationModel->where('label', 'dépôt')->first();
        
        $data = [
            'user1' => $user['id'],
            'user2' => null,
            'type_mvt' => $typeDepot['id'],
            'montant' => $montant,
            'frais_appliques' => 0,
            'date_transaction' => date('Y-m-d H:i:s'),
        ];
        
        $this->historiqueModel->insert($data);
        
        $userData = $this->userModel->find($user['id']);
        session()->set('user', array_merge($user, ['solde' => $userData['solde']]));
        
        return redirect()->to('/client/dashboard')->with('success', 'Dépôt de ' . number_format($montant, 0, ',', ' ') . ' Ar effectué avec succès');
    }
    
    // ============================================
    // RETRAIT (AVEC FRAIS)
    // ============================================
    
    public function retrait()
    {
        if (!session()->get('user')) {
            return redirect()->to('/client/login')->with('error', 'Veuillez vous connecter');
        }
        
        $user = session()->get('user');
        $montant = $this->request->getPost('montant');
        
        if (empty($montant) || $montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide');
        }
        
        $montant = (float)$montant;
        
        // Récupérer l'opérateur du client via son préfixe
        $prefix = substr($user['numero'], 0, 3);
        $operateur = $this->prefixesModel->getOperateurByPrefix($prefix);
        $operateurId = null;
        if ($operateur) {
            $operateurData = $this->prefixesModel->where('prefixes', $prefix)->first();
            $operateurId = $operateurData['id_operateur'] ?? null;
        }
        
        $typeRetrait = $this->typeOperationModel->where('label', 'retrait')->first();
        
        // Calculer les frais selon le montant, le type et l'opérateur
        $frais = $this->baremeFraisModel->calculerFrais($montant, $typeRetrait['id'], $operateurId);
        $montantTotal = $montant + $frais;
        
        if ($user['solde'] < $montantTotal) {
            return redirect()->back()->with('error', 'Solde insuffisant. Montant + frais = ' . number_format($montantTotal, 0, ',', ' ') . ' Ar');
        }
        
        $result = $this->userModel->updateSolde($user['id'], $montantTotal, 'subtract');
        if (!$result) {
            return redirect()->back()->with('error', 'Erreur lors du retrait');
        }
        
        $data = [
            'user1' => $user['id'],
            'user2' => null,
            'type_mvt' => $typeRetrait['id'],
            'montant' => $montant,
            'frais_appliques' => $frais,
            'date_transaction' => date('Y-m-d H:i:s'),
        ];
        
        $this->historiqueModel->insert($data);
        
        $userData = $this->userModel->find($user['id']);
        session()->set('user', array_merge($user, ['solde' => $userData['solde']]));
        
        return redirect()->to('/client/dashboard')->with('success', 'Retrait de ' . number_format($montant, 0, ',', ' ') . ' Ar effectué avec succès (frais: ' . number_format($frais, 0, ',', ' ') . ' Ar)');
    }
    
    // ============================================
    // TRANSFERT (AVEC FRAIS - SANS ENVOI RÉEL)
    // ============================================
    
    public function transfert()
    {
        if (!session()->get('user')) {
            return redirect()->to('/client/login')->with('error', 'Veuillez vous connecter');
        }
        
        $user = session()->get('user');
        $destinataire = $this->request->getPost('destinataire');
        $montant = $this->request->getPost('montant');
        
        if (empty($destinataire)) {
            return redirect()->back()->with('error', 'Veuillez saisir le numéro du destinataire');
        }
        
        if (empty($montant) || $montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide');
        }
        
        $montant = (float)$montant;
        
        if ($destinataire === $user['numero']) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous transférer à vous-même');
        }
        
        // ============================================
        // VÉRIFICATION : LE DESTINATAIRE EXISTE
        // ============================================
        $destinataireUser = $this->userModel->findByNumero($destinataire);
        if (!$destinataireUser) {
            return redirect()->back()->with('error', 'Le destinataire n\'existe pas');
        }
        
        if ($destinataireUser['role'] !== 'client') {
            return redirect()->back()->with('error', 'Le destinataire n\'est pas un client valide');
        }
        
        // Récupérer l'opérateur du client
        $prefix = substr($user['numero'], 0, 3);
        $operateurData = $this->prefixesModel->where('prefixes', $prefix)->first();
        $operateurId = $operateurData['id_operateur'] ?? null;
        
        $typeTransfert = $this->typeOperationModel->where('label', 'transfert')->first();
        
        // Calculer les frais selon le montant, le type et l'opérateur
        $frais = $this->baremeFraisModel->calculerFrais($montant, $typeTransfert['id'], $operateurId);
        $montantTotal = $montant + $frais;
        
        if ($user['solde'] < $montantTotal) {
            return redirect()->back()->with('error', 'Solde insuffisant. Montant + frais = ' . number_format($montantTotal, 0, ',', ' ') . ' Ar');
        }
        
        // ============================================
        // 1. DÉBITER L'ENVOYEUR (SEULEMENT)
        //    L'ARGENT N'EST PAS ENVOYÉ AU DESTINATAIRE
        // ============================================
        $result = $this->userModel->updateSolde($user['id'], $montantTotal, 'subtract');
        if (!$result) {
            return redirect()->back()->with('error', 'Erreur lors du transfert (débit)');
        }
        
        // ============================================
        // 2. CRÉER L'HISTORIQUE POUR L'ENVOYEUR
        // ============================================
        $data = [
            'user1' => $user['id'],
            'user2' => $destinataireUser['id'],
            'type_mvt' => $typeTransfert['id'],
            'montant' => $montant,
            'frais_appliques' => $frais,
            'date_transaction' => date('Y-m-d H:i:s'),
        ];
        
        $this->historiqueModel->insert($data);
        
        // ============================================
        // 3. LE DESTINATAIRE N'EST PAS CRÉDITÉ
        //    (L'ARGENT EST BLOQUÉ CHEZ L'OPÉRATEUR)
        // ============================================
        // Commenté : le destinataire ne reçoit pas l'argent
        // $this->userModel->updateSolde($destinataireUser['id'], $montant, 'add');
        
        $userData = $this->userModel->find($user['id']);
        session()->set('user', array_merge($user, ['solde' => $userData['solde']]));
        
        return redirect()->to('/client/dashboard')->with('success', 'Transfert de ' . number_format($montant, 0, ',', ' ') . ' Ar vers ' . $destinataire . ' enregistré dans votre historique (frais: ' . number_format($frais, 0, ',', ' ') . ' Ar). Le destinataire recevra l\'argent ultérieurement.');
    }
    
    // ============================================
    // API : CALCULER LES FRAIS
    // ============================================
    
    public function calculerFrais()
    {
        if (!session()->get('user')) {
            return $this->response->setJSON(['error' => 'Non authentifié'], 401);
        }
        
        $user = session()->get('user');
        $montant = $this->request->getPost('montant');
        
        if (empty($montant) || $montant <= 0) {
            return $this->response->setJSON(['error' => 'Montant invalide', 'frais' => 0]);
        }
        
        $montant = (float)$montant;
        
        $prefix = substr($user['numero'], 0, 3);
        $operateurData = $this->prefixesModel->where('prefixes', $prefix)->first();
        $operateurId = $operateurData['id_operateur'] ?? null;
        
        $typeRetrait = $this->typeOperationModel->where('label', 'retrait')->first();
        $typeTransfert = $this->typeOperationModel->where('label', 'transfert')->first();
        
        $fraisRetrait = $this->baremeFraisModel->calculerFrais($montant, $typeRetrait['id'], $operateurId);
        $fraisTransfert = $this->baremeFraisModel->calculerFrais($montant, $typeTransfert['id'], $operateurId);
        
        return $this->response->setJSON([
            'success' => true,
            'montant' => $montant,
            'frais_retrait' => $fraisRetrait,
            'frais_retrait_formate' => number_format($fraisRetrait, 0, ',', ' ') . ' Ar',
            'frais_transfert' => $fraisTransfert,
            'frais_transfert_formate' => number_format($fraisTransfert, 0, ',', ' ') . ' Ar',
        ]);
    }
}