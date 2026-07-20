<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixesSeeder extends Seeder
{
    public function run()
    {
        // Vider la table
        $this->db->table('prefixes')->truncate();
        
        // Récupérer les IDs des opérateurs
        $orange = $this->db->table('operateur')->where('operateur', 'Orange')->get()->getRow();
        $airtel = $this->db->table('operateur')->where('operateur', 'Airtel')->get()->getRow();
        $telma = $this->db->table('operateur')->where('operateur', 'Telma')->get()->getRow();

        $data = [
            // Orange
            ['prefixes' => '032', 'id_operateur' => $orange->id, 'actif' => 1],
            ['prefixes' => '037', 'id_operateur' => $orange->id, 'actif' => 1],
            
            // Airtel
            ['prefixes' => '033', 'id_operateur' => $airtel->id, 'actif' => 1],
            ['prefixes' => '039', 'id_operateur' => $airtel->id, 'actif' => 1],
            
            // Telma
            ['prefixes' => '034', 'id_operateur' => $telma->id, 'actif' => 1],
            ['prefixes' => '038', 'id_operateur' => $telma->id, 'actif' => 1],
        ];

        $this->db->table('prefixes')->insertBatch($data);
    }
}