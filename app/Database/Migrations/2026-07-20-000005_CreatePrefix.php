<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrefixesTable extends Migration
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
            'prefixes' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'unique' => true,
                'null' => false,
            ],
            'id_operateur' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->addForeignKey('id_operateur', 'operateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('prefixes');
    }

    public function down()
    {
        $this->forge->dropTable('prefixes');
    }
}