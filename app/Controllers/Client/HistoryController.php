<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\HistoriqueModel;
use App\Models\TypeOperationModel;

class HistoryController extends BaseController
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
        
        
        $db = \Config\Database::connect();
        
        $sql = "
            SELECT * FROM historiques 
            WHERE user1 = ? OR user2 = ?
            GROUP BY id
            ORDER BY date_transaction DESC 
            LIMIT 100
        ";
        
        $transactions = $db->query($sql, [$user['id'], $user['id']])->getResultArray();
        
        
        $types = $this->typeOperationModel->findAll();
        $typeMap = [];
        foreach ($types as $t) {
            $typeMap[$t['id']] = $t['label'];
        }
        
        
        foreach ($transactions as &$tx) {
            $tx['type_label'] = $typeMap[$tx['type_mvt']] ?? 'Inconnu';
            
            
            $typeLabel = $tx['type_label'];
            $tx['is_depot'] = ($typeLabel === 'dépôt');
            $tx['is_retrait'] = ($typeLabel === 'retrait');
            $tx['is_transfert'] = ($typeLabel === 'transfert');
            
            if ($tx['is_depot']) {
                $tx['signe'] = '+';
                $tx['couleur'] = 'text-emerald-600';
                $tx['montant_affiche'] = $tx['montant'];
                $tx['montant_total'] = $tx['montant'];
            } elseif ($tx['is_retrait']) {
                $tx['signe'] = '-';
                $tx['couleur'] = 'text-rose-600';
                $tx['montant_affiche'] = $tx['montant'] + $tx['frais_appliques'];
                $tx['montant_total'] = $tx['montant'] + $tx['frais_appliques'];
            } elseif ($tx['is_transfert']) {
                if ($tx['user1'] == $user['id']) {
                    $tx['signe'] = '-';
                    $tx['couleur'] = 'text-rose-600';
                    $tx['montant_affiche'] = $tx['montant'] + $tx['frais_appliques'];
                    $tx['montant_total'] = $tx['montant'] + $tx['frais_appliques'];
                } else {
                    $tx['signe'] = '+';
                    $tx['couleur'] = 'text-emerald-600';
                    $tx['montant_affiche'] = $tx['montant'];
                    $tx['montant_total'] = $tx['montant'];
                }
            }
            
            
            if ($tx['user1'] == $user['id']) {
                $tx['autre_user'] = $tx['user2'] ? $this->userModel->find($tx['user2']) : null;
            } else {
                $tx['autre_user'] = $tx['user1'] ? $this->userModel->find($tx['user1']) : null;
            }
        }
        
       
        $total_depots = 0;
        $total_retraits = 0;
        $total_transferts = 0;
        $total_frais = 0;
        
        foreach ($transactions as $tx) {
            if ($tx['is_depot']) {
                $total_depots += $tx['montant'];
            } elseif ($tx['is_retrait']) {
                $total_retraits += $tx['montant'] + $tx['frais_appliques'];
            } elseif ($tx['is_transfert']) {
                if ($tx['user1'] == $user['id']) {
                    $total_transferts += $tx['montant'] + $tx['frais_appliques'];
                }
            }
            $total_frais += $tx['frais_appliques'];
        }
        
        $data = [
            'user' => $user,
            'transactions' => $transactions,
            'total_depots' => $total_depots,
            'total_retraits' => $total_retraits,
            'total_transferts' => $total_transferts,
            'total_frais' => $total_frais,
            'title' => 'Historique Client'
        ];
        
        return view('client/history', $data);
    }
}