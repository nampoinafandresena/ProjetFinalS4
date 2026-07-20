<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\HistoriqueModel;
use App\Models\BaremeFraisModel;
use App\Models\TypeOperationModel;

class OperationController extends BaseController
{
    protected $userModel;
    protected $historiqueModel;
    protected $baremeFraisModel;
    protected $typeOperationModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->historiqueModel = new HistoriqueModel();
        $this->baremeFraisModel = new BaremeFraisModel();
        $this->typeOperationModel = new TypeOperationModel();
    }
    
   
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
        
        
        $frais = $this->baremeFraisModel->calculerFrais($montant);
        $montantTotal = $montant + $frais;
        
        
        if ($user['solde'] < $montantTotal) {
            return redirect()->back()->with('error', 'Solde insuffisant. Montant + frais = ' . number_format($montantTotal, 0, ',', ' ') . ' Ar');
        }
        
        
        $result = $this->userModel->updateSolde($user['id'], $montantTotal, 'subtract');
        if (!$result) {
            return redirect()->back()->with('error', 'Erreur lors du retrait');
        }
        
        
        $typeRetrait = $this->typeOperationModel->where('label', 'retrait')->first();
        
        
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
        
        
        $destinataireUser = $this->userModel->findByNumero($destinataire);
        if (!$destinataireUser) {
            return redirect()->back()->with('error', 'Le destinataire n\'existe pas');
        }
        
        // Vérifier que le destinataire est un client (pas admin)
        if ($destinataireUser['role'] !== 'client') {
            return redirect()->back()->with('error', 'Le destinataire n\'est pas un client valide');
        }
        
        
        $frais = $this->baremeFraisModel->calculerFrais($montant);
        $montantTotal = $montant + $frais;
        
        
        if ($user['solde'] < $montantTotal) {
            return redirect()->back()->with('error', 'Solde insuffisant. Montant + frais = ' . number_format($montantTotal, 0, ',', ' ') . ' Ar');
        }
       
        $result = $this->userModel->updateSolde($user['id'], $montantTotal, 'subtract');
        if (!$result) {
            return redirect()->back()->with('error', 'Erreur lors du transfert (débit)');
        }
        
        
        $result = $this->userModel->updateSolde($destinataireUser['id'], $montant, 'add');
        if (!$result) {
            
            $this->userModel->updateSolde($user['id'], $montantTotal, 'add');
            return redirect()->back()->with('error', 'Erreur lors du transfert (crédit)');
        }
        
        
        $typeTransfert = $this->typeOperationModel->where('label', 'transfert')->first();
        
        
        $data = [
            'user1' => $user['id'],
            'user2' => $destinataireUser['id'],
            'type_mvt' => $typeTransfert['id'],
            'montant' => $montant,
            'frais_appliques' => $frais,
            'date_transaction' => date('Y-m-d H:i:s'),
        ];
        
        $this->historiqueModel->insert($data);
        
        
        $userData = $this->userModel->find($user['id']);
        session()->set('user', array_merge($user, ['solde' => $userData['solde']]));
        
        return redirect()->to('/client/dashboard')->with('success', 'Transfert de ' . number_format($montant, 0, ',', ' ') . ' Ar vers ' . $destinataire . ' effectué avec succès (frais: ' . number_format($frais, 0, ',', ' ') . ' Ar)');
    }
    

    
    public function calculerFrais()
    {
        
        if (!session()->get('user')) {
            return $this->response->setJSON(['error' => 'Non authentifié'], 401);
        }
        
        $montant = $this->request->getPost('montant');
        
        if (empty($montant) || $montant <= 0) {
            return $this->response->setJSON(['error' => 'Montant invalide', 'frais' => 0]);
        }
        
        $montant = (float)$montant;
        
        
        $frais = $this->baremeFraisModel->calculerFrais($montant);
        
        return $this->response->setJSON([
            'success' => true,
            'montant' => $montant,
            'frais' => $frais,
            'frais_formate' => number_format($frais, 0, ',', ' ') . ' Ar'
        ]);
    }
}