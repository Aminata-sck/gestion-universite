<?php namespace App\Models;

use CodeIgniter\Model;

class PaiementModel extends Model
{
    protected $table = 'paiements';
    protected $primaryKey = 'id';
    protected $allowedFields = ['etudiant_id', 'montant', 'date_paiement', 'mode', 'reference', 'statut', 'echeance','description'];
    protected $useTimestamps = false;
}