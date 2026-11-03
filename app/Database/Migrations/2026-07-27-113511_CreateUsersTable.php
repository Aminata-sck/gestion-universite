<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'nom' => ['type' => 'VARCHAR', 'constraint' => 100],
            'prenom' => ['type' => 'VARCHAR', 'constraint' => 100],
            'photo' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'telephone' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'role' => ['type' => 'ENUM("admin","scolarite","enseignant","etudiant")', 'default' => 'etudiant'],
            'statut' => ['type' => 'TINYINT', 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}