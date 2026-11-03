<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInscriptionsPedagogiquesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'etudiant_id' => ['type' => 'INT', 'unsigned' => true],
            'ec_id' => ['type' => 'INT', 'unsigned' => true],
            'semestre' => ['type' => 'TINYINT'],
            'statut' => ['type' => 'ENUM("en_attente","validée","refusée")', 'default' => 'en_attente'],
            'date_inscription' => ['type' => 'DATE'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('etudiant_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ec_id', 'ecs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('inscriptions_pedagogiques');
    }

    public function down()
    {
        $this->forge->dropTable('inscriptions_pedagogiques');
    }
}