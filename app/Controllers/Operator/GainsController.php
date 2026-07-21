<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;
use App\Models\HistoriqueModel;
use App\Models\UserModel;
use App\Models\OperateurModel;
use App\Models\PrefixesModel;
use App\Models\TypeOperationModel;
use App\Models\BaremeFraisModel;

class GainsController extends BaseController
{
    protected $historiqueModel;
    protected $userModel;
    protected $operateurModel;
    protected $prefixesModel;
    protected $typeOperationModel;
    protected $baremeFraisModel;
    
    public function __construct()
    {
        $this->historiqueModel = new HistoriqueModel();
        $this->userModel = new UserModel();
        $this->operateurModel = new OperateurModel();
        $this->prefixesModel = new PrefixesModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->baremeFraisModel = new BaremeFraisModel();
    }
    
    public function index()
    {
        try {
            // Récupérer toutes les transactions avec détails
            $transactions = $this->getAllTransactions();
            
            // Récupérer les opérateurs
            $operateurs = $this->operateurModel->findAll();
            
            // Récupérer les types d'opération
            $types = $this->typeOperationModel->findAll();
            
            
            // 1. STATISTIQUES GLOBALES
            
            $resume = $this->getResumeGains($transactions);
            
            
            // 2. MONTANTS À REVERSER
            
            $montantsAReverser = $this->getMontantsAReverser($transactions);
            
            
            // 3. STATISTIQUES PAR TYPE
            
            $statsType = $this->getStatsByType($transactions);
            
            
            // 4. SÉPARER NOTRE OPÉRATEUR DES AUTRES
            
            $notreOperateur = null;
            $autresOperateurs = [];
            
            foreach ($operateurs as $op) {
                if ($op['operateur'] == 'Telma') {
                    // Préfixes réellement configurés pour Telma (admin/prefixe), plutôt
                    // qu'une liste ['034','038'] codée en dur qui ignorait toute
                    // modification faite depuis l'admin.
                    $prefixesTelma = array_column($this->prefixesModel->getPrefixesByOperateur($op['id']), 'prefixes');

                    $fraisTelma = 0;
                    $volumeTelma = 0;
                    $transactionsTelma = 0;
                    
                    foreach ($transactions as $tx) {
                        $prefix = substr($tx['sender_numero'] ?? '', 0, 3);
                        if (in_array($prefix, $prefixesTelma, true)) {
                            $fraisTelma += (float)$tx['frais_appliques'];
                            $volumeTelma += (float)$tx['montant'];
                            $transactionsTelma++;
                        }
                    }
                    
                    $notreOperateur = [
                        'operateur_id' => $op['id'],
                        'operateur' => $op['operateur'],
                        'frais' => $fraisTelma,
                        'volume' => $volumeTelma,
                        'transactions' => $transactionsTelma,
                        'commission' => 0
                    ];
                } else {
                    // Trouver les commissions des autres opérateurs
                    $commissionOp = 0;
                    $volumeOp = 0;
                    $transactionsOp = 0;
                    
                    foreach ($transactions as $tx) {
                        if ($tx['operateur_destinataire'] == $op['id']) {
                            $commissionOp += (float)$tx['commission_appliquee'];
                            $volumeOp += (float)$tx['montant'];
                            $transactionsOp++;
                        }
                    }
                    
                    if ($commissionOp > 0) {
                        $autresOperateurs[] = [
                            'operateur_id' => $op['id'],
                            'operateur' => $op['operateur'],
                            'frais' => 0,
                            'commission' => $commissionOp,
                            'volume' => $volumeOp,
                            'transactions' => $transactionsOp,
                            'commission_pourcentage' => $op['commission'] ?? 0
                        ];
                    }
                }
            }
            
            // Trier les autres opérateurs par total des gains
            usort($autresOperateurs, function($a, $b) {
                return $b['commission'] - $a['commission'];
            });
            
            $data = [
                'transactions' => $transactions,
                'operateurs' => $operateurs,
                'notreOperateur' => $notreOperateur,
                'autresOperateurs' => $autresOperateurs,
                'montantsAReverser' => $montantsAReverser,
                'statsType' => $statsType,
                'total_frais' => $resume['total_frais'],
                'total_commission' => $resume['total_commission'],
                'total_volume' => $resume['total_volume'],
                'total_transactions' => count($transactions),
                'title' => 'Rapport des gains'
            ];
            
            return view('pages/operator-gains', $data);
            
        } catch (\Exception $e) {
            // En cas d'erreur, afficher un message clair
            log_message('error', 'GainsController error: ' . $e->getMessage());
            return view('pages/operator-gains', [
                'transactions' => [],
                'operateurs' => [],
                'notreOperateur' => null,
                'autresOperateurs' => [],
                'montantsAReverser' => [],
                'statsType' => [],
                'total_frais' => 0,
                'total_commission' => 0,
                'total_volume' => 0,
                'total_transactions' => 0,
                'error' => $e->getMessage(),
                'title' => 'Rapport des gains'
            ]);
        }
    }
    
    
    // MÉTHODES PRIVÉES
    
    
    private function getAllTransactions()
    {
        $db = \Config\Database::connect();
        
        $sql = "
            SELECT h.*, 
                   u1.numero as sender_numero, 
                   u1.role as sender_role,
                   u2.numero as receiver_numero, 
                   u2.role as receiver_role,
                   t.label as type_label,
                   o.operateur as operateur_dest_label,
                   o.commission as operateur_commission
            FROM historiques h
            LEFT JOIN user u1 ON h.user1 = u1.id
            LEFT JOIN user u2 ON h.user2 = u2.id
            LEFT JOIN type_operation t ON h.type_mvt = t.id
            LEFT JOIN operateur o ON h.operateur_destinataire = o.id
            ORDER BY h.date_transaction DESC
            LIMIT 1000
        ";
        
        return $db->query($sql)->getResultArray();
    }
    
    private function getResumeGains($transactions)
    {
        $total_frais = 0;
        $total_commission = 0;
        $total_volume = 0;
        
        foreach ($transactions as $tx) {
            $total_frais += (float)$tx['frais_appliques'];
            $total_commission += (float)$tx['commission_appliquee'];
            $total_volume += (float)$tx['montant'];
        }
        
        return [
            'total_frais' => $total_frais,
            'total_commission' => $total_commission,
            'total_volume' => $total_volume
        ];
    }
    
    private function getMontantsAReverser($transactions)
    {
        $result = [];
        
        foreach ($transactions as $tx) {
            if ($tx['operateur_destinataire'] && $tx['commission_appliquee'] > 0) {
                $id = $tx['operateur_destinataire'];
                if (!isset($result[$id])) {
                    $result[$id] = [
                        'operateur_id' => $id,
                        'operateur' => $tx['operateur_dest_label'] ?? 'Inconnu',
                        'total_commission' => 0,
                        'total_montant_transfere' => 0,
                        'nb_transactions' => 0,
                        'commission_pourcentage' => $tx['operateur_commission'] ?? 0,
                        'premiere_transaction' => $tx['date_transaction'],
                        'derniere_transaction' => $tx['date_transaction']
                    ];
                }
                $result[$id]['total_commission'] += (float)$tx['commission_appliquee'];
                $result[$id]['total_montant_transfere'] += (float)$tx['montant'];
                $result[$id]['nb_transactions']++;
                
                if ($tx['date_transaction'] < $result[$id]['premiere_transaction']) {
                    $result[$id]['premiere_transaction'] = $tx['date_transaction'];
                }
                if ($tx['date_transaction'] > $result[$id]['derniere_transaction']) {
                    $result[$id]['derniere_transaction'] = $tx['date_transaction'];
                }
            }
        }
        
        // Trier par montant décroissant
        usort($result, function($a, $b) {
            return $b['total_commission'] - $a['total_commission'];
        });
        
        return $result;
    }
    
    private function getStatsByType($transactions)
    {
        $result = [];
        $typeMap = [];
        
        // Récupérer les types
        $types = $this->typeOperationModel->findAll();
        foreach ($types as $t) {
            $typeMap[$t['id']] = $t['label'];
        }
        
        foreach ($transactions as $tx) {
            $typeId = $tx['type_mvt'];
            $label = $typeMap[$typeId] ?? 'Inconnu';
            
            if (!isset($result[$typeId])) {
                $result[$typeId] = [
                    'type_id' => $typeId,
                    'type_label' => $label,
                    'nb_transactions' => 0,
                    'total_volume' => 0,
                    'total_frais' => 0,
                    'total_commission' => 0
                ];
            }
            $result[$typeId]['nb_transactions']++;
            $result[$typeId]['total_volume'] += (float)$tx['montant'];
            $result[$typeId]['total_frais'] += (float)$tx['frais_appliques'];
            $result[$typeId]['total_commission'] += (float)$tx['commission_appliquee'];
        }
        
        return array_values($result);
    }
}