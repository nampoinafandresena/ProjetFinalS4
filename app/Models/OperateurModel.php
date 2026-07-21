<?php 

namespace App\Models;
use CodeIgniter\Model;

class OperateurModel extends Model{
    protected $table = "operateur";
    protected $primaryKey = "id";
    protected $allowedFields = ["operateur", "commission"];

    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'operateur' => 'required|is_unique[operateur.operateur]|min_length[2]|max_length[50]',
    ];

    protected $validationMessages = [
        'operateur' => [
            'is_unique' => 'Cet opérateur existe déjà.',
            'required' => 'Le nom de l\'opérateur est obligatoire.',
            'min_length' => 'Le nom doit contenir au moins 2 caractères.',
        ],
    ];

    public function findByOperateur(string $operateur) {
        return $this->where('operateur', $operateur)->first();
    }

    public function getAllOperateurs() {
        return $this->orderBy('operateur', 'ASC')->findAll();
    }

    public function getOperateursWithPrefixes() {
        return $this->select('operateur.*')
                    ->join('prefixes', 'operateur.id = prefixes.id_operateur', 'left')
                    ->groupBy('operateur.id')
                    ->orderBy('operateur.operateur', 'ASC')
                    ->findAll();
    }
}