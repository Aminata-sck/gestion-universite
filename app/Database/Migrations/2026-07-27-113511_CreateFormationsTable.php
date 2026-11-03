<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFormationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'libelle' => ['type' => 'VARCHAR', 'constraint' => 150],
            'type' => ['type' => 'ENUM("Licence","Master","Doctorat")', 'default' => 'Licence'],
            'parcours' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'duree' => ['type' => 'INT', 'comment' => 'Durée en années'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('formations');
    }

    public function down()
    {
        $this->forge->dropTable('formations');
    }
}