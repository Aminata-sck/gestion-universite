<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInscriptionsAdministrativesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'etudiant_id' => ['type' => 'INT', 'unsigned' => true],
            'formation_id' => ['type' => 'INT', 'unsigned' => true],
            'annee_universitaire_id' => ['type' => 'INT', 'unsigned' => true],
            'date_inscription' => ['type' => 'DATE'],
            'statut' => ['type' => 'ENUM("en_attente","validée","rejetée")', 'default' => 'en_attente'],
            'pieces_justificatives' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('etudiant_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('formation_id', 'formations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('annee_universitaire_id', 'annees_universitaires', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('inscriptions_administratives');
    }

    public function down()
    {
        $this->forge->dropTable('inscriptions_administratives');
    }
}