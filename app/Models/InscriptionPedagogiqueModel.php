<?php namespace App\Models;

use CodeIgniter\Model;

class InscriptionPedagogiqueModel extends Model
{
    protected $table = 'inscriptions_pedagogiques';
    protected $primaryKey = 'id';
    protected $allowedFields = ['etudiant_id', 'ec_id', 'semestre', 'statut', 'date_inscription'];
    
    // Désactiver complètement les timestamps
    protected $useTimestamps = false;
}