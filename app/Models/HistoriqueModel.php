<?php 

namespace App\Models;
use CodeIgniter\Model;

class HistoriqueModel extends Model{
    protected $table = "historiques";
    protected $primaryKey = "id";
    protected $allowedFields = ["user1", "user2", "type_mvt", "montant", "frais_appliques", "date_transaction"];

    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $createdField = 'date_transaction';

    protected $validationRules = [
        'user1' => 'required|integer|is_not_unique[user.id]',
        'user2' => 'permit_empty|integer|is_not_unique[user.id]',
        'type_mvt' => 'required|integer|is_not_unique[type_operation.id]',
        'montant' => 'required|numeric|greater_than[0]',
        'frais_appliques' => 'permit_empty|numeric|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'user1' => [
            'required' => 'L\'utilisateur émetteur est obligatoire.',
            'is_not_unique' => 'L\'utilisateur émetteur n\'existe pas.',
        ],
        'user2' => [
            'is_not_unique' => 'L\'utilisateur receveur n\'existe pas.',
        ],
        'type_mvt' => [
            'required' => 'Le type d\'opération est obligatoire.',
            'is_not_unique' => 'Le type d\'opération n\'existe pas.',
        ],
        'montant' => [
            'required' => 'Le montant est obligatoire.',
            'numeric' => 'Le montant doit être un nombre.',
            'greater_than' => 'Le montant doit être supérieur à 0.',
        ],
    ];

    
}