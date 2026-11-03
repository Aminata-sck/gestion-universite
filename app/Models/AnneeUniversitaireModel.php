<?php namespace App\Models;

use CodeIgniter\Model;

class AnneeUniversitaireModel extends Model
{
    protected $table = 'annees_universitaires';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle', 'date_debut', 'date_fin', 'active'];
    protected $useTimestamps = true;
}