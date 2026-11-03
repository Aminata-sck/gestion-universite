<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEcsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'ue_id' => ['type' => 'INT', 'unsigned' => true],
            'libelle' => ['type' => 'VARCHAR', 'constraint' => 150],
            'coefficient' => ['type' => 'FLOAT', 'default' => 1],
            'type' => ['type' => 'ENUM("CM","TD","TP")', 'default' => 'CM'],
            'enseignant_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('ue_id', 'ues', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('enseignant_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('ecs');
    }

    public function down()
    {
        $this->forge->dropTable('ecs');
    }
}