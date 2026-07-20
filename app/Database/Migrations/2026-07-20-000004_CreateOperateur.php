<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOperateur extends Migration
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
            'operateur' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
                'null' => false,
            ],

            'commission' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00,
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
        $this->forge->createTable('operateur');
    }

    public function down()
    {
        $this->forge->dropTable('operateur');
    }
}