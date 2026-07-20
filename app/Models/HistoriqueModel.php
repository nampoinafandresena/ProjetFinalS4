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
    protected $allowedFields    = ['user1', 'user2', 'type_mvt', 'montant', 'frais_appliques', 'date_transaction'];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
  
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
    
   
    public function getHistoriqueAvecDetails($userId, $limit = 10)
    {
        $sql = "
            SELECT h.*, 
                   u1.numero as sender_numero, 
                   u2.numero as receiver_numero, 
                   t.label as type_label
            FROM historiques h
            LEFT JOIN user u1 ON h.user1 = u1.id
            LEFT JOIN user u2 ON h.user2 = u2.id
            LEFT JOIN type_operation t ON h.type_mvt = t.id
            WHERE h.user1 = ? OR h.user2 = ?
            GROUP BY h.id
            ORDER BY h.date_transaction DESC
            LIMIT ?
        ";
        
        return $this->db->query($sql, [$userId, $userId, $limit])->getResultArray();
    }
}