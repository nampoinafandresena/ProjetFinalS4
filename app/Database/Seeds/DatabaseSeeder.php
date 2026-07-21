<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ============================================
        // 1. OPÉRATEURS
        // ============================================
        $this->db->table('operateur')->truncate();
        $operateurs = [
            ['operateur' => 'Orange', 'commission' => 2.5],
            ['operateur' => 'Airtel', 'commission' => 2.0],
            ['operateur' => 'Telma', 'commission' => 0.0],
        ];
        $this->db->table('operateur')->insertBatch($operateurs);
        

        // Récupérer les IDs des opérateurs
        $orange = $this->db->table('operateur')->where('operateur', 'Orange')->get()->getRow();
        $airtel = $this->db->table('operateur')->where('operateur', 'Airtel')->get()->getRow();
        $telma = $this->db->table('operateur')->where('operateur', 'Telma')->get()->getRow();

      
        $this->db->table('prefixes')->truncate();
        $prefixes = [
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
        $this->db->table('prefixes')->insertBatch($prefixes);
       
        $this->db->table('type_operation')->truncate();
        $types = [
            ['label' => 'dépôt'],
            ['label' => 'retrait'],
            ['label' => 'transfert'],
        ];
        $this->db->table('type_operation')->insertBatch($types);
        

        // Récupérer les IDs des types d'opération
        $retrait = $this->db->table('type_operation')->where('label', 'retrait')->get()->getRow();
        $transfert = $this->db->table('type_operation')->where('label', 'transfert')->get()->getRow();

        $this->db->table('bareme_frais')->truncate();
        
        $baremes = [
            // Retrait - Telma
            ['min' => 100, 'max' => 1000, 'frais' => 20, 'id_type_operation' => $retrait->id, 'id_operateur' => $telma->id],
            ['min' => 1001, 'max' => 5000, 'frais' => 30, 'id_type_operation' => $retrait->id, 'id_operateur' => $telma->id],
            ['min' => 5001, 'max' => 10000, 'frais' => 60, 'id_type_operation' => $retrait->id, 'id_operateur' => $telma->id],
            ['min' => 10001, 'max' => 25000, 'frais' => 120, 'id_type_operation' => $retrait->id, 'id_operateur' => $telma->id],
            ['min' => 25001, 'max' => 50000, 'frais' => 250, 'id_type_operation' => $retrait->id, 'id_operateur' => $telma->id],
            ['min' => 50001, 'max' => 100000, 'frais' => 500, 'id_type_operation' => $retrait->id, 'id_operateur' => $telma->id],
            ['min' => 100001, 'max' => 250000, 'frais' => 1000, 'id_type_operation' => $retrait->id, 'id_operateur' => $telma->id],
            ['min' => 250001, 'max' => 500000, 'frais' => 1000, 'id_type_operation' => $retrait->id, 'id_operateur' => $telma->id],
            ['min' => 500001, 'max' => 1000000, 'frais' => 1800, 'id_type_operation' => $retrait->id, 'id_operateur' => $telma->id],
            ['min' => 1000001, 'max' => 2000000, 'frais' => 2200, 'id_type_operation' => $retrait->id, 'id_operateur' => $telma->id],
            
            // Transfert - Telma
            ['min' => 100, 'max' => 1000, 'frais' => 20, 'id_type_operation' => $transfert->id, 'id_operateur' => $telma->id],
            ['min' => 1001, 'max' => 5000, 'frais' => 30, 'id_type_operation' => $transfert->id, 'id_operateur' => $telma->id],
            ['min' => 5001, 'max' => 10000, 'frais' => 60, 'id_type_operation' => $transfert->id, 'id_operateur' => $telma->id],
            ['min' => 10001, 'max' => 25000, 'frais' => 120, 'id_type_operation' => $transfert->id, 'id_operateur' => $telma->id],
            ['min' => 25001, 'max' => 50000, 'frais' => 250, 'id_type_operation' => $transfert->id, 'id_operateur' => $telma->id],
            ['min' => 50001, 'max' => 100000, 'frais' => 500, 'id_type_operation' => $transfert->id, 'id_operateur' => $telma->id],
            ['min' => 100001, 'max' => 250000, 'frais' => 1000, 'id_type_operation' => $transfert->id, 'id_operateur' => $telma->id],
            ['min' => 250001, 'max' => 500000, 'frais' => 1000, 'id_type_operation' => $transfert->id, 'id_operateur' => $telma->id],
            ['min' => 500001, 'max' => 1000000, 'frais' => 1800, 'id_type_operation' => $transfert->id, 'id_operateur' => $telma->id],
            ['min' => 1000001, 'max' => 2000000, 'frais' => 2200, 'id_type_operation' => $transfert->id, 'id_operateur' => $telma->id],
        ];
        
        $this->db->table('bareme_frais')->insertBatch($baremes);
      

      
        $this->db->table('user')->truncate();
        $users = [
            // Admin
            [
                'numero' => '0000000000',
                'solde' => null,
                // 'id_epargne' => null,
                'role' => 'admin',
            ],
            // Clients Telma
            [
                'numero' => '0340000001',
                'solde' => 0.0,
                // 'id_epargne' => null,

                'role' => 'client',
            ],
            [
                'numero' => '0340000002',
                'solde' => 0.0,
                // 'id_epargne' => null,

                'role' => 'client',
            ],
            [
                'numero' => '0340000003',
                'solde' => 0.0,
                // 'id_epargne' => null,

                'role' => 'client',
            ],
            // Client Orange (pour tester les transferts inter-opérateurs)
            [
                'numero' => '0330000001',
                'solde' => 0.0,
                // 'id_epargne' => null,

                'role' => 'client',
            ],
            // Client Airtel (pour tester les transferts inter-opérateurs)
            [
                'numero' => '0390000001',
                'solde' => 0.0,
                // 'id_epargne' => null,

                'role' => 'client',
            ],
        ];
        $this->db->table('user')->insertBatch($users);

         
        
        
    }
}