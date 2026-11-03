<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfilIdToUsers extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('profil_id', 'users')) {
            $this->forge->addColumn('users', [
                'profil_id' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'role',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('profil_id', 'users')) {
            $this->forge->dropColumn('users', 'profil_id');
        }
    }
}
