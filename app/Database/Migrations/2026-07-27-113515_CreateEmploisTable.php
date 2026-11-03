<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmploisDuTempsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'ec_id' => ['type' => 'INT', 'unsigned' => true],
            'salle' => ['type' => 'VARCHAR', 'constraint' => 50],
            'jour' => ['type' => 'ENUM("Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi")'],
            'heure_debut' => ['type' => 'TIME'],
            'heure_fin' => ['type' => 'TIME'],
            'type_cours' => ['type' => 'ENUM("CM","TD","TP")'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('ec_id', 'ecs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('emplois_du_temps');
    }

    public function down()
    {
        $this->forge->dropTable('emplois_du_temps');
    }
}