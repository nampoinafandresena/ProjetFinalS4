<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixesModel extends Model
{
    protected $table            = 'prefixes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['prefixes', 'id_operateur'];
    
    protected $useTimestamps = false;
    
   
    public function getAllPrefixesWithOperateur()
    {
        $builder = $this->db->table('prefixes p');
        $builder->select('p.*, o.operateur');
        $builder->join('operateur o', 'p.id_operateur = o.id', 'left');
        $builder->orderBy('p.prefixes', 'ASC');
        return $builder->get()->getResultArray();
    }
    
   
    public function getAllPrefixes()
    {
        return $this->orderBy('prefixes', 'ASC')->findAll();
    }
    
    
    public function prefixExists($prefix)
    {
        return $this->where('prefixes', $prefix)->countAllResults() > 0;
    }
    
    
    public function getOperateurByPrefix($prefix)
    {
        $builder = $this->db->table('prefixes p');
        $builder->select('o.operateur');
        $builder->join('operateur o', 'p.id_operateur = o.id', 'left');
        $builder->where('p.prefixes', $prefix);
        $result = $builder->get()->getRow();
        return $result ? $result->operateur : null;
    }
}