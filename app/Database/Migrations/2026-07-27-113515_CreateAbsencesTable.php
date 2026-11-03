<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAbsencesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'etudiant_id' => ['type' => 'INT', 'unsigned' => true],
            'emploi_id'   => ['type' => 'INT', 'unsigned' => true],
            'date_absence' => ['type' => 'DATE'],
            'justifie'    => ['type' => 'TINYINT', 'default' => 0],
            'motif'       => ['type' => 'TEXT', 'null' => true],
            'enseignant_id' => ['type' => 'INT', 'unsigned' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        // On ajoute uniquement les clés étrangères vers users
        $this->forge->addForeignKey('etudiant_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('enseignant_id', 'users', 'id', 'CASCADE', 'CASCADE');
        // On ne met PAS la clé étrangère vers emplois_du_temps pour l'instant
        $this->forge->createTable('absences');
    }

    public function down()
    {
        $this->forge->dropTable('absences');
    }
}