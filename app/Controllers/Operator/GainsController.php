<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;
use App\Models\HistoriqueModel;
use App\Models\UserModel;
use App\Models\OperateurModel;
use App\Models\PrefixesModel;
use App\Models\TypeOperationModel;

class GainsController extends BaseController
{
    protected $historiqueModel;
    protected $userModel;
    protected $operateurModel;
    protected $prefixesModel;
    protected $typeOperationModel;
    
    public function __construct()
    {
        $this->historiqueModel = new HistoriqueModel();
        $this->userModel = new UserModel();
        $this->operateurModel = new OperateurModel();
        $this->prefixesModel = new PrefixesModel();
        $this->typeOperationModel = new TypeOperationModel();
    }
    
    public function index()
    {
        // Récupérer toutes les transactions
        $db = \Config\Database::connect();
        
        // Récupérer toutes les transactions avec les détails des utilisateurs
        $sql = "
            SELECT h.*, 
                   u1.numero as sender_numero, 
                   u2.numero as receiver_numero, 
                   t.label as type_label
            FROM historiques h
            LEFT JOIN user u1 ON h.user1 = u1.id
            LEFT JOIN user u2 ON h.user2 = u2.id
            LEFT JOIN type_operation t ON h.type_mvt = t.id
            ORDER BY h.date_transaction DESC
        ";
        
        $transactions = $db->query($sql)->getResultArray();
        
        // Récupérer les opérateurs
        $operateurs = $this->operateurModel->findAll();
        
        // Récupérer les préfixes pour déterminer l'opérateur de chaque client
        $prefixes = $this->prefixesModel->findAll();
        $prefixMap = [];
        foreach ($prefixes as $p) {
            $prefixMap[$p['prefixes']] = $p['id_operateur'];
        }
        
        // Récupérer les types d'opération
        $types = $this->typeOperationModel->findAll();
        $typeMap = [];
        foreach ($types as $t) {
            $typeMap[$t['id']] = $t['label'];
        }
        
        // Statistiques globales
        $total_frais = 0;
        $total_volume = 0;
        $total_transactions = count($transactions);
        
        // Statistiques par opérateur
        $statsOperateurs = [];
        foreach ($operateurs as $op) {
            $statsOperateurs[$op['id']] = [
                'id' => $op['id'],
                'operateur' => $op['operateur'],
                'frais' => 0,
                'volume' => 0,
                'transactions' => 0,
                'retrait_frais' => 0,
                'retrait_volume' => 0,
                'retrait_count' => 0,
                'transfert_frais' => 0,
                'transfert_volume' => 0,
                'transfert_count' => 0,
                'depot_volume' => 0,
                'depot_count' => 0,
            ];
        }
        
        // Statistiques par type d'opération (global)
        $statsType = [];
        foreach ($types as $t) {
            $statsType[$t['id']] = [
                'label' => $t['label'],
                'frais' => 0,
                'volume' => 0,
                'count' => 0,
            ];
        }
        
        // Parcourir les transactions
        foreach ($transactions as $tx) {
            $typeId = $tx['type_mvt'];
            $typeLabel = $typeMap[$typeId] ?? 'Inconnu';
            $montant = (float)$tx['montant'];
            $frais = (float)$tx['frais_appliques'];
            
            // Totaux globaux
            $total_volume += $montant;
            $total_frais += $frais;
            
            // Stats par type
            if (isset($statsType[$typeId])) {
                $statsType[$typeId]['volume'] += $montant;
                $statsType[$typeId]['frais'] += $frais;
                $statsType[$typeId]['count']++;
            }
            
            // Déterminer l'opérateur du client (user1 = envoyeur)
            if ($tx['user1']) {
                $senderNumero = $tx['sender_numero'] ?? '';
                $prefix = substr($senderNumero, 0, 3);
                $operateurId = $prefixMap[$prefix] ?? null;
                
                if ($operateurId && isset($statsOperateurs[$operateurId])) {
                    $statsOperateurs[$operateurId]['volume'] += $montant;
                    $statsOperateurs[$operateurId]['frais'] += $frais;
                    $statsOperateurs[$operateurId]['transactions']++;
                    
                    if ($typeLabel === 'retrait') {
                        $statsOperateurs[$operateurId]['retrait_volume'] += $montant;
                        $statsOperateurs[$operateurId]['retrait_frais'] += $frais;
                        $statsOperateurs[$operateurId]['retrait_count']++;
                    } elseif ($typeLabel === 'transfert') {
                        $statsOperateurs[$operateurId]['transfert_volume'] += $montant;
                        $statsOperateurs[$operateurId]['transfert_frais'] += $frais;
                        $statsOperateurs[$operateurId]['transfert_count']++;
                    } elseif ($typeLabel === 'dépôt') {
                        $statsOperateurs[$operateurId]['depot_volume'] += $montant;
                        $statsOperateurs[$operateurId]['depot_count']++;
                    }
                }
            }
        }
        
        // Supprimer les opérateurs sans transactions
        $statsOperateurs = array_filter($statsOperateurs, function($op) {
            return $op['transactions'] > 0;
        });
        
        // Classer les opérateurs par frais collectés
        usort($statsOperateurs, function($a, $b) {
            return $b['frais'] - $a['frais'];
        });
        
        $data = [
            'transactions' => $transactions,
            'operateurs' => $operateurs,
            'statsOperateurs' => $statsOperateurs,
            'statsType' => $statsType,
            'total_frais' => $total_frais,
            'total_volume' => $total_volume,
            'total_transactions' => $total_transactions,
            'title' => 'Rapport des gains'
        ];
        
        return view('pages/operator-gains', $data);
    }
}