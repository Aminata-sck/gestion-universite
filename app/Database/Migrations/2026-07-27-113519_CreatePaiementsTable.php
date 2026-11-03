<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaiementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'etudiant_id' => ['type' => 'INT', 'unsigned' => true],
            'montant' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'date_paiement' => ['type' => 'DATE'],
            'mode' => ['type' => 'ENUM("especes","cheque","virement","en_ligne")'],
            'reference' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'statut' => ['type' => 'ENUM("en_attente","payé","annulé")', 'default' => 'en_attente'],
            'echeance' => ['type' => 'DATE', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('etudiant_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('paiements');
    }

    public function down()
    {
        $this->forge->dropTable('paiements');
    }
}