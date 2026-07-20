<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table            = 'type_operation';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['label'];
    
    protected $useTimestamps = false;
    
    public function getByLabel($label)
    {
        return $this->where('label', $label)->first();
    }
}