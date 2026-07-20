<?php 

namespace App\Models;
use CodeIgniter\Model;

class TypeOperationModel extends Model{
    protected $table = "type_operation";
    protected $primaryKey = "id";
    protected $allowedFields = ["label"];

    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'label' => 'required|is_unique[type_operation.label]|min_length[3]|max_length[50]',
    ];

    protected $validationMessages = [
        'label' => [
            'is_unique' => 'Ce type d\'opération existe déjà.',
            'required' => 'Le label est obligatoire.',
        ],
    ];

    public function findByLabel(string $label) {
        return $this->where('label', $label)->first();
    }

    public function getOperationTypes() {
        return $this->findAll();
    }

    public function getOperationTypeId(string $label) {
        $type = $this->where('label', $label)->first();
        return $type ? $type['id'] : null;
    }
}