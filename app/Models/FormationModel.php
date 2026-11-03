<?php namespace App\Models;

use CodeIgniter\Model;

class FormationModel extends Model
{
    protected $table = 'formations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle', 'type', 'parcours', 'duree', 'archive', 'annee'];
    protected $useTimestamps = true;

    // Désactiver complètement le soft delete
    protected $useSoftDeletes = false;
}