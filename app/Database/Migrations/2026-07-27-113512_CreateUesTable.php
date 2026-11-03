<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'code' => ['type' => 'VARCHAR', 'constraint' => 20, 'unique' => true],
            'libelle' => ['type' => 'VARCHAR', 'constraint' => 150],
            'formation_id' => ['type' => 'INT', 'unsigned' => true],
            'semestre' => ['type' => 'TINYINT'],
            'credits_ects' => ['type' => 'FLOAT', 'default' => 0],
            'coefficient' => ['type' => 'FLOAT', 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('formation_id', 'formations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ues');
    }

    public function down()
    {
        $this->forge->dropTable('ues');
    }
}