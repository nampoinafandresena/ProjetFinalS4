<?php 

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = "user";
    protected $primaryKey = "id";
    protected $allowedFields = ["numero", "solde", "role"];

    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'numero' => 'required|is_unique[user.numero]|min_length[10]',
        'solde'  => 'numeric|greater_than_equal_to[0]',
        'role'   => 'required|in_list[admin,client]',
    ];

    protected $validationMessages = [
        'numero' => [
            'is_unique' => 'Ce numéro d\'utilisateur existe déjà.',
            'required' => 'Le numéro est obligatoire.',
            'min_length' => 'Le numéro doit contenir au moins 10 caractères.',
        ],
        'role' => [
            'in_list' => 'Le rôle doit être admin ou client.',
        ],
    ];

    public function findByNumero(string $numero)
    {
        return $this->where('numero', $numero)->first();
    }

    public function getSolde(int $userId)
    {
        $user = $this->find($userId);
        return $user ? $user['solde'] : null;
    }

    public function updateSolde(int $userId, float $montant, string $operation = 'add')
    {
        $user = $this->find($userId);
        if (!$user) {
            return false;
        }

        $nouveauSolde = ($operation === 'add') 
            ? $user['solde'] + $montant 
            : $user['solde'] - $montant;

        if ($nouveauSolde < 0) {
            return false;
        }

        return $this->update($userId, ['solde' => $nouveauSolde]);
    }

    public function getUsersByRole(string $role)
    {
        return $this->where('role', $role)->findAll();
    }

    public function searchUsers(string $search)
    {
        return $this->like('numero', $search)
                    ->orLike('role', $search)
                    ->findAll();
    }

    public function getClients()
    {
        return $this->where('role', 'client')
                    ->orderBy('id', 'DESC')
                    ->findAll();
    }

    /**
     * Récupère les clients avec pagination
     */
    public function getClientsPaginated($perPage = 10)
    {
        return $this->where('role', 'client')
                    ->orderBy('id', 'DESC')
                    ->paginate($perPage);
    }
}