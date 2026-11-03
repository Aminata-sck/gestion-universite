<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnneesUniversitaireTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'libelle' => ['type' => 'VARCHAR', 'constraint' => 20, 'unique' => true],
            'date_debut' => ['type' => 'DATE'],
            'date_fin' => ['type' => 'DATE'],
            'active' => ['type' => 'TINYINT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('annees_universitaires');
    }

    public function down()
    {
        $this->forge->dropTable('annees_universitaires');
    }
}