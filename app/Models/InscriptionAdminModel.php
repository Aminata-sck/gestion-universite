<?php namespace App\Models;

use CodeIgniter\Model;

class InscriptionAdminModel extends Model
{
    protected $table = 'inscriptions_administratives';
    protected $primaryKey = 'id';
    protected $allowedFields = ['etudiant_id', 'formation_id', 'annee_universitaire_id', 'date_inscription', 'statut'];
    
    // Garder les timestamps mais uniquement pour created_at
    protected $useTimestamps = true;
    protected $createdAt  = 'created_at';
    protected $updatedAt  = false; // ← désactive updated_at
}