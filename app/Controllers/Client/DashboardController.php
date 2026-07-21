<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\HistoriqueModel;
use App\Models\TypeOperationModel;

class DashboardController extends BaseController
{
    protected $userModel;
    protected $historiqueModel;
    protected $typeOperationModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->historiqueModel = new HistoriqueModel();
        $this->typeOperationModel = new TypeOperationModel();
    }
    
    public function index()
    {
        $user = session()->get('user');
        
        if (!$user) {
            return redirect()->to('/client/login')->with('error', 'Veuillez vous connecter');
        }
        
        $userData = $this->userModel->find($user['id']);
        if ($userData) {
            $user['solde'] = $userData['solde'];
            session()->set('user', $user);
        }
        
        
        // UTILISER LA METHODE DU MODELE
        
        $transactions = $this->historiqueModel->getTransactionsByUser($user['id'], 10);
        
        $types = $this->typeOperationModel->findAll();
        $typeMap = [];
        foreach ($types as $t) {
            $typeMap[$t['id']] = $t['label'];
        }
        
        foreach ($transactions as &$tx) {
            $tx['type_label'] = $typeMap[$tx['type_mvt']] ?? 'Inconnu';
        }
        
        $total_in = 0;
        $total_out = 0;
        
        foreach ($transactions as $tx) {
            $typeLabel = $tx['type_label'] ?? '';
            
            if ($typeLabel === 'dépôt') {
                $total_in += $tx['montant'];
            } elseif ($typeLabel === 'retrait') {
                $total_out += $tx['montant'] + $tx['frais_appliques'];
            } elseif ($typeLabel === 'transfert') {
                if ($tx['user1'] == $user['id']) {
                    $total_out += $tx['montant'] + $tx['frais_appliques'];
                } else {
                    $total_in += $tx['montant'];
                }
            }
        }
        
        $data = [
            'user' => $user,
            'recent_transactions' => $transactions,
            'total_in' => $total_in,
            'total_out' => $total_out,
            'title' => 'Dashboard Client'
        ];
        
        return view('client/dashboard', $data);
    }
}