<?php 

namespace App\Models;
use CodeIgniter\Model;

class BaremeFraisDetailsModel extends Model{
    protected $table = "bareme_frais_details";
    protected $primaryKey = "id";
    protected $allowedFields = ["min", "max", "frais", "id_type_operation", "id_operateur"];

    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'min' => 'required|numeric|greater_than_equal_to[0]',
        'max' => 'required|numeric|greater_than_equal_to[0]',
        'frais' => 'required|numeric|greater_than_equal_to[0]',
        'id_type_operation' => 'required|integer|is_not_unique[type_operation.id]',
        'id_operateur' => 'required|integer|is_not_unique[operateur.id]',
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
            'greater_than_equal_to' => 'Le montant maximum doit être supérieur ou égal à 0.',
        ],
        'frais' => [
            'required' => 'Les frais sont obligatoires.',
            'numeric' => 'Les frais doivent être un nombre.',
            'greater_than_equal_to' => 'Les frais doivent être supérieur ou égal à 0.',
        ],
        'id_type_operation' => [
            'required' => 'Le type d\'opération est obligatoire.',
            'is_not_unique' => 'Le type d\'opération sélectionné n\'existe pas.',
        ],
        'id_operateur' => [
            'required' => 'L\'opérateur est obligatoire.',
            'is_not_unique' => 'L\'opérateur sélectionné n\'existe pas.',
        ],
    ];

    public function getAllBaremesWithDetails() {
        return $this->select('bareme_frais_details.*, type_operation.label as type_operation, operateur.operateur')
                    ->join('type_operation', 'type_operation.id = bareme_frais_details.id_type_operation')
                    ->join('operateur', 'operateur.id = bareme_frais_details.id_operateur')
                    ->orderBy('bareme_frais_details.id_type_operation', 'ASC')
                    ->orderBy('bareme_frais_details.min', 'ASC')
                    ->findAll();
    }

    public function getBaremesByType(int $typeOperationId) {
        return $this->where('id_type_operation', $typeOperationId)
                    ->orderBy('min', 'ASC')
                    ->findAll();
    }

    public function getBaremesByOperateur(int $operateurId) {
        return $this->where('id_operateur', $operateurId)
                    ->orderBy('min', 'ASC')
                    ->findAll();
    }

    public function getBaremesByTypeAndOperateur(int $typeOperationId, int $operateurId) {
        return $this->where('id_type_operation', $typeOperationId)
                    ->where('id_operateur', $operateurId)
                    ->orderBy('min', 'ASC')
                    ->findAll();
    }

    public function getFraisByMontantTypeOperateur(float $montant, int $typeOperationId, int $operateurId) {
        $result = $this->where('id_type_operation', $typeOperationId)
                       ->where('id_operateur', $operateurId)
                       ->where('min <=', $montant)
                       ->where('max >=', $montant)
                       ->first();
        return $result ? $result['frais'] : 0;
    }

    public function trancheExists(float $min, float $max, int $typeOperationId, int $operateurId, ?int $excludeId = null) {
        $this->where('id_type_operation', $typeOperationId)
             ->where('id_operateur', $operateurId)
             ->where('min <=', $max)
             ->where('max >=', $min);
        
        if ($excludeId) {
            $this->where('id !=', $excludeId);
        }
        
        return $this->first() !== null;
    }

    public function insertBareme($data) {
        // Vérifier si la tranche existe déjà
        if ($this->trancheExists($data['min'], $data['max'], $data['id_type_operation'], $data['id_operateur'])) {
            return false;
        }
        return $this->insert($data);
    }

    public function updateBareme(int $id, $data) {
        // Vérifier si la tranche existe déjà (sauf pour l'élément en cours)
        if ($this->trancheExists($data['min'], $data['max'], $data['id_type_operation'], $data['id_operateur'], $id)) {
            return false;
        }
        return $this->update($id, $data);
    }

    public function deleteBareme(int $id) {
        return $this->delete($id);
    }

    public function getFraisOrDefault(float $montant, int $typeOperationId, int $operateurId, float $default = 0) {
        $frais = $this->getFraisByMontantTypeOperateur($montant, $typeOperationId, $operateurId);
        return $frais !== null ? $frais : $default;
    }

    public function getBaremesGroupedByType() {
        $results = $this->select('bareme_frais_details.*, type_operation.label as type_operation')
                        ->join('type_operation', 'type_operation.id = bareme_frais_details.id_type_operation')
                        ->orderBy('id_type_operation', 'ASC')
                        ->orderBy('min', 'ASC')
                        ->findAll();
        
        $grouped = [];
        foreach ($results as $row) {
            $type = $row['type_operation'];
            if (!isset($grouped[$type])) {
                $grouped[$type] = [];
            }
            $grouped[$type][] = $row;
        }
        return $grouped;
    }

    public function getFraisByOperateurForType(int $typeOperationId) {
        return $this->select('bareme_frais_details.*, operateur.operateur')
                    ->join('operateur', 'operateur.id = bareme_frais_details.id_operateur')
                    ->where('id_type_operation', $typeOperationId)
                    ->orderBy('id_operateur', 'ASC')
                    ->orderBy('min', 'ASC')
                    ->findAll();
    }
}