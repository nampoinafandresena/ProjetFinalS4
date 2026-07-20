<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixesSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les IDs des opérateurs
        $orange = $this->db->table('operateur')->where('operateur', 'Orange')->get()->getRow();
        $airtel = $this->db->table('operateur')->where('operateur', 'Airtel')->get()->getRow();
        $telma = $this->db->table('operateur')->where('operateur', 'Telma')->get()->getRow();

        $data = [
            // Orange
            ['prefixes' => '033', 'id_operateur' => $orange->id],
            ['prefixes' => '034', 'id_operateur' => $orange->id],
            
            // Airtel
            ['prefixes' => '037', 'id_operateur' => $airtel->id],
            ['prefixes' => '038', 'id_operateur' => $airtel->id],
            
            // Telma
            ['prefixes' => '032', 'id_operateur' => $telma->id],
            ['prefixes' => '039', 'id_operateur' => $telma->id],
        ];

        $this->db->table('prefixes')->insertBatch($data);
    }
}