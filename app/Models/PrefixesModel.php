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
    protected $allowedFields    = ['prefixes', 'id_operateur', 'actif'];
    
    protected $useTimestamps = false;

    protected $validationRules = [
        'prefixes' => 'required|is_unique[prefixes.prefixes]|min_length[2]|max_length[5]|regex_match[/^[0-9]+$/]',
        'id_operateur' => 'required|integer|is_not_unique[operateur.id]',
        'actif' => 'permit_empty|integer|in_list[0,1]',
    ];

    protected $validationMessages = [
        'prefixes' => [
            'is_unique' => 'Ce préfixe existe déjà.',
            'required' => 'Le préfixe est obligatoire.',
            'min_length' => 'Le préfixe doit contenir au moins 2 caractères.',
            'max_length' => 'Le préfixe ne peut pas dépasser 5 caractères.',
            'regex_match' => 'Le préfixe doit contenir uniquement des chiffres.',
        ],
        'id_operateur' => [
            'required' => 'L\'opérateur est obligatoire.',
            'is_not_unique' => 'L\'opérateur sélectionné n\'existe pas.',
        ],
    ];

    public function getPrefixesWithOperateur()
    {
        return $this->select('prefixes.*, operateur.operateur')
                    ->join('operateur', 'operateur.id = prefixes.id_operateur')
                    ->orderBy('prefixes.prefixes', 'ASC')
                    ->findAll();
    }

    public function getActivePrefixes()
    {
        return $this->where('actif', 1)
                    ->orderBy('prefixes', 'ASC')
                    ->findAll();
    }

    public function getPrefixesByOperateur(int $operateurId)
    {
        return $this->where('id_operateur', $operateurId)
                    ->orderBy('prefixes', 'ASC')
                    ->findAll();
    }

    public function togglePrefix(int $id)
    {
        $prefix = $this->find($id);
        if (!$prefix) {
            return false;
        }
        $newStatus = $prefix['actif'] == 1 ? 0 : 1;
        return $this->update($id, ['actif' => $newStatus]);
    }

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