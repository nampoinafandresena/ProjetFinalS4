<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypeOperationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['label' => 'dépôt'],
            ['label' => 'retrait'],
            ['label' => 'transfert'],
        ];

        $this->db->table('type_operation')->insertBatch($data);
    }
}