<?php 

namespace App\Models;
use CodeIgniter\Model;

class PrefixesModel extends Model{
    protected $table = "prefixes";
    protected $primaryKey = "id";
    protected $allowedFields = ["prefixes", "id_operateur"];

    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'prefixes' => 'required|is_unique[prefixes.prefixes]|min_length[2]|max_length[5]|regex_match[/^[0-9]+$/]',
        'id_operateur' => 'required|integer|is_not_unique[operateur.id]',
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

    public function getPrefixesWithOperateur() {
        return $this->select('prefixes.*, operateur.operateur')
                    ->join('operateur', 'operateur.id = prefixes.id_operateur')
                    ->orderBy('prefixes.prefixes', 'ASC')
                    ->findAll();
    }

    public function getPrefixesByOperateur(int $operateurId) {
        return $this->where('id_operateur', $operateurId)
                    ->orderBy('prefixes', 'ASC')
                    ->findAll();
    }

    public function isValidPrefix(string $numero) {
        $prefixes = $this->findAll();
        foreach ($prefixes as $prefix) {
            if (strpos($numero, $prefix['prefixes']) === 0) {
                return true;
            }
        }
        return false;
    }

    public function getPrefixFromNumber(string $numero) {
        $prefixes = $this->findAll();
        foreach ($prefixes as $prefix) {
            if (strpos($numero, $prefix['prefixes']) === 0) {
                return $prefix;
            }
        }
        return null;
    }

    public function getOperateurByNumber(string $numero) {
        $prefix = $this->getPrefixFromNumber($numero);
        if ($prefix) {
            $operateurModel = new OperateurModel();
            return $operateurModel->find($prefix['id_operateur']);
        }
        return null;
    }

    public function prefixExists(string $prefix) {
        return $this->where('prefixes', $prefix)->first() !== null;
    }

    public function deletePrefix(int $id) {
        return $this->delete($id);
    }
}