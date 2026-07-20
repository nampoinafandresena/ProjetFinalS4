<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Exécuter les seeders dans l'ordre
        $this->call('OperateurSeeder');
        $this->call('PrefixesSeeder');
        
        // Optionnel : seeders existants
        // $this->call('TypeOperationSeeder');
        // $this->call('BaremeFraisSeeder');
        // $this->call('UserSeeder');
    }
}