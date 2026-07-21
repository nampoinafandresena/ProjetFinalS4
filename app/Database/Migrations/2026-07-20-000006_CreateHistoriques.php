<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHistoriques extends Migration
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
            'user1' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'comment' => 'Envoyeur',
            ],
            'user2' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Receveur (peut être NULL pour dépôt/retrait)',
            ],
            'type_mvt' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'montant' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => false,
            ],
            'frais_appliques' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'null' => false,
            ],
           
            'commission_appliquee' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'null' => false,
            ],
            'operateur_destinataire' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'montant_total' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00,
                'null' => false,
            ],
            'date_transaction' => [
                'type' => 'DATETIME',
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
        
        // Clés étrangères
        $this->forge->addForeignKey('user1', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user2', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('type_mvt', 'type_operation', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('operateur_destinataire', 'operateur', 'id', 'SET NULL', 'CASCADE');
        
        // Index pour performances
        $this->forge->addKey('user1', false, false, 'idx_historiques_user1');
        $this->forge->addKey('user2', false, false, 'idx_historiques_user2');
        $this->forge->addKey('type_mvt', false, false, 'idx_historiques_type');
        $this->forge->addKey('date_transaction', false, false, 'idx_historiques_date');
        $this->forge->addKey('operateur_destinataire', false, false, 'idx_historiques_operateur_dest');
        
        $this->forge->createTable('historiques');
    }

    public function down()
    {
        $this->forge->dropTable('historiques');
    }
}