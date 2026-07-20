<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBaremeFrais extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'min' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => false,
            ],
            'max' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => false,
            ],
            'frais' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey(['min', 'max'], false);
        $this->forge->createTable('bareme_frais');
    }

    public function down()
    {
        $this->forge->dropTable('bareme_frais');
    }
}