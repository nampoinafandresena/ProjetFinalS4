<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperateurSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['operateur' => 'Orange'],
            ['operateur' => 'Airtel'],
            ['operateur' => 'Telma'],
        ];

        $this->db->table('operateur')->insertBatch($data);
    }
}