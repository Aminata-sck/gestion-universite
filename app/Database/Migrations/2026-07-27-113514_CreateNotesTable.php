<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'etudiant_id' => ['type' => 'INT', 'unsigned' => true],
            'ec_id' => ['type' => 'INT', 'unsigned' => true],
            'type' => ['type' => 'ENUM("cc","examen","rattrapage")', 'default' => 'examen'],
            'note' => ['type' => 'FLOAT'],
            'coefficient' => ['type' => 'FLOAT', 'default' => 1],
            'date_saisie' => ['type' => 'DATETIME'],
            'enseignant_id' => ['type' => 'INT', 'unsigned' => true],
            'session' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('etudiant_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ec_id', 'ecs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('enseignant_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('notes');
    }

    public function down()
    {
        $this->forge->dropTable('notes');
    }
}