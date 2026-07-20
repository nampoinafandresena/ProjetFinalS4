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
    protected $allowedFields    = ['min', 'max', 'frais'];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    
    public function calculerFrais($montant)
    {
        if ($montant < 100) {
            return 0;
        }
        
        $result = $this->where('min <=', $montant)
                       ->where('max >=', $montant)
                       ->orderBy('min', 'ASC')
                       ->first();
        
        return $result ? $result['frais'] : 0;
    }
    
    
    public function getAllBaremes()
    {
        return $this->orderBy('min', 'ASC')->findAll();
    }
}