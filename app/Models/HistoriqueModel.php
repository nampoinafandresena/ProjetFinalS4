<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoriqueModel extends Model
{
    protected $table            = 'historiques';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user1', 'user2', 'type_mvt', 'montant', 
        'frais_appliques', 'commission_appliquee', 
        'montant_total', 'operateur_destinataire', 'date_transaction'
    ];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    /**
     * Récupère les transactions d'un utilisateur (sans doublons)
     */
    public function getTransactionsByUser($userId, $limit = 10)
    {
        $sql = "
            SELECT * FROM historiques 
            WHERE user1 = ? 
            UNION 
            SELECT * FROM historiques 
            WHERE user2 = ? 
            ORDER BY date_transaction DESC 
            LIMIT ?
        ";
        
        return $this->db->query($sql, [$userId, $userId, $limit])->getResultArray();
    }
    
    /**
     * Récupère l'historique avec détails complets
     */
    public function getHistoriqueAvecDetails($userId, $limit = 10)
    {
        $sql = "
            SELECT h.*, 
                   u1.numero as sender_numero, 
                   u2.numero as receiver_numero, 
                   t.label as type_label,
                   o.operateur as operateur_dest_label,
                   o.commission as operateur_commission
            FROM historiques h
            LEFT JOIN user u1 ON h.user1 = u1.id
            LEFT JOIN user u2 ON h.user2 = u2.id
            LEFT JOIN type_operation t ON h.type_mvt = t.id
            LEFT JOIN operateur o ON h.operateur_destinataire = o.id
            WHERE h.user1 = ? OR h.user2 = ?
            GROUP BY h.id
            ORDER BY h.date_transaction DESC
            LIMIT ?
        ";
        
        return $this->db->query($sql, [$userId, $userId, $limit])->getResultArray();
    }

    /**
     * Récupère toutes les transactions avec détails (pour admin)
     */
    public function getAllTransactionsWithDetails()
    {
        $sql = "
            SELECT h.*, 
                   u1.numero as sender_numero, 
                   u1.role as sender_role,
                   u2.numero as receiver_numero, 
                   u2.role as receiver_role,
                   t.label as type_label,
                   o.operateur as operateur_dest_label,
                   o.commission as operateur_commission,
                   op_env.operateur as operateur_env_label
            FROM historiques h
            LEFT JOIN user u1 ON h.user1 = u1.id
            LEFT JOIN user u2 ON h.user2 = u2.id
            LEFT JOIN type_operation t ON h.type_mvt = t.id
            LEFT JOIN operateur o ON h.operateur_destinataire = o.id
            LEFT JOIN prefixes p ON substr(u1.numero, 1, 3) = p.prefixes
            LEFT JOIN operateur op_env ON p.id_operateur = op_env.id
            ORDER BY h.date_transaction DESC
        ";
        
        return $this->db->query($sql)->getResultArray();
    }

    /**
     * Récupère les gains par opérateur (frais + commissions)
     */
    public function getGainsParOperateur()
    {
        // Frais collectés par chaque opérateur (via leurs clients)
        $sqlFrais = "
            SELECT 
                operateur.id as operateur_id,
                operateur.operateur,
                SUM(h.frais_appliques) as total_frais,
                COUNT(h.id) as nb_transactions,
                SUM(h.montant) as total_volume
            FROM historiques h
            JOIN user u ON h.user1 = u.id
            JOIN prefixes p ON substr(u.numero, 1, 3) = p.prefixes
            JOIN operateur ON p.id_operateur = operateur.id
            WHERE h.frais_appliques > 0
            GROUP BY operateur.id
            ORDER BY total_frais DESC
        ";
        
        // Commissions collectées par les opérateurs (transferts vers eux)
        $sqlCommission = "
            SELECT 
                operateur.id as operateur_id,
                operateur.operateur,
                SUM(h.commission_appliquee) as total_commission,
                COUNT(h.id) as nb_transactions,
                SUM(h.montant) as total_volume
            FROM historiques h
            JOIN operateur ON h.operateur_destinataire = operateur.id
            WHERE h.commission_appliquee > 0
            GROUP BY operateur.id
            ORDER BY total_commission DESC
        ";
        
        $frais = $this->db->query($sqlFrais)->getResultArray();
        $commissions = $this->db->query($sqlCommission)->getResultArray();
        
        return [
            'frais' => $frais,
            'commissions' => $commissions
        ];
    }

    /**
     * Récupère les montants à reverser à chaque opérateur
     */
    public function getMontantsAReverser()
    {
        $sql = "
            SELECT 
                operateur.id as operateur_id,
                operateur.operateur,
                operateur.commission as commission_pourcentage,
                SUM(h.commission_appliquee) as total_commission,
                SUM(h.montant) as total_montant_transfere,
                COUNT(h.id) as nb_transactions,
                MIN(h.date_transaction) as premiere_transaction,
                MAX(h.date_transaction) as derniere_transaction
            FROM historiques h
            JOIN operateur ON h.operateur_destinataire = operateur.id
            WHERE h.commission_appliquee > 0
            GROUP BY operateur.id
            ORDER BY total_commission DESC
        ";
        
        return $this->db->query($sql)->getResultArray();
    }

    /**
     * Récupère le résumé des gains globaux
     */
    public function getResumeGains()
    {
        // Total des frais (notre opérateur et autres)
        $sqlFraisTotal = "
            SELECT 
                SUM(h.frais_appliques) as total_frais,
                COUNT(h.id) as nb_frais
            FROM historiques h
            WHERE h.frais_appliques > 0
        ";
        
        // Total des commissions (autres opérateurs)
        $sqlCommissionTotal = "
            SELECT 
                SUM(h.commission_appliquee) as total_commission,
                COUNT(h.id) as nb_commission
            FROM historiques h
            WHERE h.commission_appliquee > 0
        ";
        
        // Total des transactions
        $sqlTotalTransactions = "
            SELECT 
                COUNT(h.id) as total_transactions,
                SUM(h.montant) as total_volume
            FROM historiques h
        ";
        
        $fraisTotal = $this->db->query($sqlFraisTotal)->getRowArray();
        $commissionTotal = $this->db->query($sqlCommissionTotal)->getRowArray();
        $totalTransactions = $this->db->query($sqlTotalTransactions)->getRowArray();
        
        return [
            'total_frais' => $fraisTotal['total_frais'] ?? 0,
            'nb_frais' => $fraisTotal['nb_frais'] ?? 0,
            'total_commission' => $commissionTotal['total_commission'] ?? 0,
            'nb_commission' => $commissionTotal['nb_commission'] ?? 0,
            'total_transactions' => $totalTransactions['total_transactions'] ?? 0,
            'total_volume' => $totalTransactions['total_volume'] ?? 0,
            'total_gains' => ($fraisTotal['total_frais'] ?? 0) + ($commissionTotal['total_commission'] ?? 0)
        ];
    }

    /**
     * Récupère les transactions par type d'opération
     */
    public function getStatsByType()
    {
        $sql = "
            SELECT 
                t.label as type_label,
                t.id as type_id,
                COUNT(h.id) as nb_transactions,
                SUM(h.montant) as total_volume,
                SUM(h.frais_appliques) as total_frais,
                SUM(h.commission_appliquee) as total_commission
            FROM historiques h
            JOIN type_operation t ON h.type_mvt = t.id
            GROUP BY t.id
            ORDER BY nb_transactions DESC
        ";
        
        return $this->db->query($sql)->getResultArray();
    }
}