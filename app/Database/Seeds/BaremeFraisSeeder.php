<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BaremeFraisSeeder extends Seeder
{
    public function run()
    {
        // Vider la table
        $this->db->table('bareme_frais')->truncate();
        
        // Récupérer les IDs des types d'opération
        $retrait = $this->db->table('type_operation')->where('label', 'retrait')->get()->getRow();
        $transfert = $this->db->table('type_operation')->where('label', 'transfert')->get()->getRow();

        // Récupérer les IDs des opérateurs
        $orange = $this->db->table('operateur')->where('operateur', 'Orange')->get()->getRow();
        $airtel = $this->db->table('operateur')->where('operateur', 'Airtel')->get()->getRow();
        $telma = $this->db->table('operateur')->where('operateur', 'Telma')->get()->getRow();

        // ============================================
        // DONNÉES : ORANGE
        // ============================================
        $orangeRetrait = [
            ['min' => 100, 'max' => 1000, 'frais' => 50, 'id_type_operation' => $retrait->id, 'id_operateur' => $orange->id],
            ['min' => 1001, 'max' => 5000, 'frais' => 50, 'id_type_operation' => $retrait->id, 'id_operateur' => $orange->id],
            ['min' => 5001, 'max' => 10000, 'frais' => 100, 'id_type_operation' => $retrait->id, 'id_operateur' => $orange->id],
            ['min' => 10001, 'max' => 25000, 'frais' => 200, 'id_type_operation' => $retrait->id, 'id_operateur' => $orange->id],
            ['min' => 25001, 'max' => 50000, 'frais' => 400, 'id_type_operation' => $retrait->id, 'id_operateur' => $orange->id],
            ['min' => 50001, 'max' => 100000, 'frais' => 800, 'id_type_operation' => $retrait->id, 'id_operateur' => $orange->id],
            ['min' => 100001, 'max' => 250000, 'frais' => 1500, 'id_type_operation' => $retrait->id, 'id_operateur' => $orange->id],
            ['min' => 250001, 'max' => 500000, 'frais' => 1500, 'id_type_operation' => $retrait->id, 'id_operateur' => $orange->id],
            ['min' => 500001, 'max' => 1000000, 'frais' => 2500, 'id_type_operation' => $retrait->id, 'id_operateur' => $orange->id],
            ['min' => 1000001, 'max' => 2000000, 'frais' => 3000, 'id_type_operation' => $retrait->id, 'id_operateur' => $orange->id],
        ];

        $orangeTransfert = [
            ['min' => 100, 'max' => 1000, 'frais' => 50, 'id_type_operation' => $transfert->id, 'id_operateur' => $orange->id],
            ['min' => 1001, 'max' => 5000, 'frais' => 50, 'id_type_operation' => $transfert->id, 'id_operateur' => $orange->id],
            ['min' => 5001, 'max' => 10000, 'frais' => 100, 'id_type_operation' => $transfert->id, 'id_operateur' => $orange->id],
            ['min' => 10001, 'max' => 25000, 'frais' => 200, 'id_type_operation' => $transfert->id, 'id_operateur' => $orange->id],
            ['min' => 25001, 'max' => 50000, 'frais' => 400, 'id_type_operation' => $transfert->id, 'id_operateur' => $orange->id],
            ['min' => 50001, 'max' => 100000, 'frais' => 800, 'id_type_operation' => $transfert->id, 'id_operateur' => $orange->id],
            ['min' => 100001, 'max' => 250000, 'frais' => 1500, 'id_type_operation' => $transfert->id, 'id_operateur' => $orange->id],
            ['min' => 250001, 'max' => 500000, 'frais' => 1500, 'id_type_operation' => $transfert->id, 'id_operateur' => $orange->id],
            ['min' => 500001, 'max' => 1000000, 'frais' => 2500, 'id_type_operation' => $transfert->id, 'id_operateur' => $orange->id],
            ['min' => 1000001, 'max' => 2000000, 'frais' => 3000, 'id_type_operation' => $transfert->id, 'id_operateur' => $orange->id],
        ];

      
        $airtelRetrait = [
            ['min' => 100, 'max' => 1000, 'frais' => 30, 'id_type_operation' => $retrait->id, 'id_operateur' => $airtel->id],
            ['min' => 1001, 'max' => 5000, 'frais' => 40, 'id_type_operation' => $retrait->id, 'id_operateur' => $airtel->id],
            ['min' => 5001, 'max' => 10000, 'frais' => 80, 'id_type_operation' => $retrait->id, 'id_operateur' => $airtel->id],
            ['min' => 10001, 'max' => 25000, 'frais' => 150, 'id_type_operation' => $retrait->id, 'id_operateur' => $airtel->id],
            ['min' => 25001, 'max' => 50000, 'frais' => 300, 'id_type_operation' => $retrait->id, 'id_operateur' => $airtel->id],
            ['min' => 50001, 'max' => 100000, 'frais' => 600, 'id_type_operation' => $retrait->id, 'id_operateur' => $airtel->id],
            ['min' => 100001, 'max' => 250000, 'frais' => 1200, 'id_type_operation' => $retrait->id, 'id_operateur' => $airtel->id],
            ['min' => 250001, 'max' => 500000, 'frais' => 1200, 'id_type_operation' => $retrait->id, 'id_operateur' => $airtel->id],
            ['min' => 500001, 'max' => 1000000, 'frais' => 2000, 'id_type_operation' => $retrait->id, 'id_operateur' => $airtel->id],
            ['min' => 1000001, 'max' => 2000000, 'frais' => 2500, 'id_type_operation' => $retrait->id, 'id_operateur' => $airtel->id],
        ];

        $airtelTransfert = [
            ['min' => 100, 'max' => 1000, 'frais' => 30, 'id_type_operation' => $transfert->id, 'id_operateur' => $airtel->id],
            ['min' => 1001, 'max' => 5000, 'frais' => 40, 'id_type_operation' => $transfert->id, 'id_operateur' => $airtel->id],
            ['min' => 5001, 'max' => 10000, 'frais' => 80, 'id_type_operation' => $transfert->id, 'id_operateur' => $airtel->id],
            ['min' => 10001, 'max' => 25000, 'frais' => 150, 'id_type_operation' => $transfert->id, 'id_operateur' => $airtel->id],
            ['min' => 25001, 'max' => 50000, 'frais' => 300, 'id_type_operation' => $transfert->id, 'id_operateur' => $airtel->id],
            ['min' => 50001, 'max' => 100000, 'frais' => 600, 'id_type_operation' => $transfert->id, 'id_operateur' => $airtel->id],
            ['min' => 100001, 'max' => 250000, 'frais' => 1200, 'id_type_operation' => $transfert->id, 'id_operateur' => $airtel->id],
            ['min' => 250001, 'max' => 500000, 'frais' => 1200, 'id_type_operation' => $transfert->id, 'id_operateur' => $airtel->id],
            ['min' => 500001, 'max' => 1000000, 'frais' => 2000, 'id_type_operation' => $transfert->id, 'id_operateur' => $airtel->id],
            ['min' => 1000001, 'max' => 2000000, 'frais' => 2500, 'id_type_operation' => $transfert->id, 'id_operateur' => $airtel->id],
        ];

       
        $telmaRetrait = [
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
        ];

        $telmaTransfert = [
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

       
        $data = array_merge(
            $orangeRetrait,
            $orangeTransfert,
            $airtelRetrait,
            $airtelTransfert,
            $telmaRetrait,
            $telmaTransfert
        );

    
        $this->db->table('bareme_frais')->insertBatch($data);
       
        
    }
}