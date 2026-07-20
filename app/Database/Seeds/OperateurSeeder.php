<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperateurSeeder extends Seeder
{
    public function run()
    {
        // Vider la table
        $this->db->table('operateur')->truncate();
        
        $data = [
            ['operateur' => 'Orange', 'commission' => 2.5],   // 2.5%
            ['operateur' => 'Airtel', 'commission' => 2.0],   // 2.0%
            ['operateur' => 'Telma', 'commission' => 0.0], 
        ];

        $this->db->table('operateur')->insertBatch($data);
        
        
    }
}