<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'numero' => '0383833838',
                'solde' => null,
                'role' => 'client',
            ],
            [
                'numero' => '0000000000',
                'solde' => null,
                'role' => 'admin',
            ],
            [
                'numero' => '0330000001',
                'solde' => 5000.00,
                'role' => 'client',
            ],
            [
                'numero' => '0340000002',
                'solde' => 2500.00,
                'role' => 'client',
            ],
            [
                'numero' => '0340000003',
                'solde' => null,
                'role' => 'client',
            ],
        ];

        $this->db->table('user')->insertBatch($data);
    }
}