<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table            = 'bareme_frais';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['min', 'max', 'frais', 'id_type_operation', 'id_operateur'];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    /**
     * Calcule les frais selon le montant, le type d'opération et l'opérateur
     */
    public function calculerFrais($montant, $typeOperationId = null, $operateurId = null)
    {
        if ($montant < 100) {
            return 0;
        }
        
        $this->where('min <=', $montant)
             ->where('max >=', $montant);
        
        if ($typeOperationId) {
            $this->where('id_type_operation', $typeOperationId);
        }
        
        if ($operateurId) {
            $this->where('id_operateur', $operateurId);
        }
        
        $result = $this->orderBy('min', 'ASC')->first();
        return $result ? $result['frais'] : 0;
    }
    
    /**
     * Récupère tous les barèmes avec les détails
     */
    public function getAllBaremesWithDetails()
    {
        return $this->select('bareme_frais.*, type_operation.label as type_operation, operateur.operateur')
                    ->join('type_operation', 'type_operation.id = bareme_frais.id_type_operation', 'left')
                    ->join('operateur', 'operateur.id = bareme_frais.id_operateur', 'left')
                    ->orderBy('bareme_frais.id_type_operation', 'ASC')
                    ->orderBy('bareme_frais.min', 'ASC')
                    ->findAll();
    }
    
    /**
     * Récupère tous les barèmes
     */
    public function getAllBaremes()
    {
        return $this->orderBy('min', 'ASC')->findAll();
    }
}