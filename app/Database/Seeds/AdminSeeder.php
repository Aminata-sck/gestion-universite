<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'email'    => 'admin@univ.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'nom'      => 'Super',
            'prenom'   => 'Admin',
            'role'     => 'admin',
            'statut'   => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('users')->insert($data);
    }
}