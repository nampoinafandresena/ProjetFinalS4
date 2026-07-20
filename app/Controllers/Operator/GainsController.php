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
        $db = \Config\Database::connect();
        
        // Récupérer toutes les transactions avec détails
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
        
        // Récupérer les préfixes
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
        
        // ============================================
        // 1. GAINS TELMA (NOTRE OPÉRATEUR)
        // ============================================
        $gainsTelma = [
            'total_frais' => 0,
            'total_volume' => 0,
            'total_transactions' => 0,
            'clients_actifs' => 0,
            'par_type' => []
        ];
        
        // Initialiser les stats par type pour Telma
        foreach ($types as $t) {
            $gainsTelma['par_type'][$t['id']] = [
                'label' => $t['label'],
                'frais' => 0,
                'volume' => 0,
                'count' => 0
            ];
        }
        
        $clientsTelma = [];
        
        foreach ($transactions as $tx) {
            $prefix = substr($tx['sender_numero'] ?? '', 0, 3);
            $estTelma = in_array($prefix, ['034', '038']);
            
            if ($estTelma) {
                $typeId = $tx['type_mvt'];
                $montant = (float)$tx['montant'];
                $frais = (float)$tx['frais_appliques'];
                
                $gainsTelma['total_frais'] += $frais;
                $gainsTelma['total_volume'] += $montant;
                $gainsTelma['total_transactions']++;
                
                if ($tx['user1']) {
                    $clientsTelma[] = $tx['user1'];
                }
                
                if (isset($gainsTelma['par_type'][$typeId])) {
                    $gainsTelma['par_type'][$typeId]['frais'] += $frais;
                    $gainsTelma['par_type'][$typeId]['volume'] += $montant;
                    $gainsTelma['par_type'][$typeId]['count']++;
                }
            }
        }
        
        $gainsTelma['clients_actifs'] = count(array_unique($clientsTelma));
        
        // ============================================
        // 2. GAINS AUTRES OPÉRATEURS
        // ============================================
        $gainsAutres = [
            'total_frais' => 0,
            'total_volume' => 0,
            'total_transactions' => 0,
            'clients_actifs' => 0,
            'par_operateur' => []
        ];
        
        // Initialiser les stats par opérateur
        foreach ($operateurs as $op) {
            if ($op['operateur'] != 'Telma') {
                $gainsAutres['par_operateur'][$op['id']] = [
                    'id' => $op['id'],
                    'operateur' => $op['operateur'],
                    'frais' => 0,
                    'volume' => 0,
                    'transactions' => 0
                ];
            }
        }
        
        $clientsAutres = [];
        
        foreach ($transactions as $tx) {
            $prefix = substr($tx['sender_numero'] ?? '', 0, 3);
            $estTelma = in_array($prefix, ['034', '038']);
            
            if (!$estTelma) {
                $typeId = $tx['type_mvt'];
                $montant = (float)$tx['montant'];
                $frais = (float)$tx['frais_appliques'];
                
                $gainsAutres['total_frais'] += $frais;
                $gainsAutres['total_volume'] += $montant;
                $gainsAutres['total_transactions']++;
                
                if ($tx['user1']) {
                    $clientsAutres[] = $tx['user1'];
                }
                
                // Trouver l'opérateur correspondant
                $operateurId = $prefixMap[$prefix] ?? null;
                if ($operateurId && isset($gainsAutres['par_operateur'][$operateurId])) {
                    $gainsAutres['par_operateur'][$operateurId]['frais'] += $frais;
                    $gainsAutres['par_operateur'][$operateurId]['volume'] += $montant;
                    $gainsAutres['par_operateur'][$operateurId]['transactions']++;
                }
            }
        }
        
        $gainsAutres['clients_actifs'] = count(array_unique($clientsAutres));
        
        // Supprimer les opérateurs sans transactions
        $gainsAutres['par_operateur'] = array_filter($gainsAutres['par_operateur'], function($op) {
            return $op['transactions'] > 0;
        });
        
        // Classer par frais
        usort($gainsAutres['par_operateur'], function($a, $b) {
            return $b['frais'] - $a['frais'];
        });
        
        $data = [
            'transactions' => $transactions,
            'operateurs' => $operateurs,
            'gainsTelma' => $gainsTelma,
            'gainsAutres' => $gainsAutres,
            'title' => 'Rapport des gains'
        ];
        
        return view('pages/operator-gains', $data);
    }
}