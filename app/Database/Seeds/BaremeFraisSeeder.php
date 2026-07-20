<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BaremeFraisSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['min' => 100, 'max' => 1000, 'frais' => 50],
            ['min' => 1001, 'max' => 5000, 'frais' => 50],
            ['min' => 5001, 'max' => 10000, 'frais' => 100],
            ['min' => 10001, 'max' => 25000, 'frais' => 200],
            ['min' => 25001, 'max' => 50000, 'frais' => 400],
            ['min' => 50001, 'max' => 100000, 'frais' => 800],
            ['min' => 100001, 'max' => 250000, 'frais' => 1500],
            ['min' => 250001, 'max' => 500000, 'frais' => 1500],
            ['min' => 500001, 'max' => 1000000, 'frais' => 2500],
            ['min' => 1000001, 'max' => 2000000, 'frais' => 3000],
        ];

        $this->db->table('bareme_frais')->insertBatch($data);
    }
}