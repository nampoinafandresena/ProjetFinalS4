<?php 

namespace App\Models;
use CodeIgniter\Model;

class BaremeFraisModel extends Model{
    protected $table = "bareme_frais";
    protected $primaryKey = "id";
    protected $allowedFields = ["min", "max", "frais"];

    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'min' => 'required|numeric|greater_than_equal_to[0]',
        'max' => 'required|numeric|greater_than[0]',
        'frais' => 'required|numeric|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'min' => [
            'required' => 'Le montant minimum est obligatoire.',
            'numeric' => 'Le montant minimum doit être un nombre.',
            'greater_than_equal_to' => 'Le montant minimum doit être supérieur ou égal à 0.',
        ],
        'max' => [
            'required' => 'Le montant maximum est obligatoire.',
            'numeric' => 'Le montant maximum doit être un nombre.',
            'greater_than' => 'Le montant maximum doit être supérieur à 0.',
        ],
        'frais' => [
            'required' => 'Les frais sont obligatoires.',
            'numeric' => 'Les frais doivent être un nombre.',
            'greater_than_equal_to' => 'Les frais doivent être supérieur ou égal à 0.',
        ],
    ];

    public function getFraisByMontant(float $montant) {
        $result = $this->where('min <=', $montant)
                       ->where('max >=', $montant)
                       ->first();
        return $result ? $result['frais'] : 0;
    }

    public function getAllBaremes() {
        return $this->orderBy('min', 'ASC')->findAll();
    }

    public function validateBaremeInterval($min, $max) {
        // verifier si le nouvel intervalle chevauche des intervalles existants
        $existing = $this->where('min <=', $max)
                         ->where('max >=', $min)
                         ->findAll();
        return empty($existing);
    }

    public function insertBareme($min, $max, $frais) {
        if (!$this->validateBaremeInterval($min, $max)) {
            return false;
        }
        
        return $this->insert([
            'min' => $min,
            'max' => $max,
            'frais' => $frais
        ]);
    }
}