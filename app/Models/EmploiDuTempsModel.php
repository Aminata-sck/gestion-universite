<?php namespace App\Models;

use CodeIgniter\Model;

class EmploiModel extends Model
{
    protected $table = 'emplois_du_temps';
    protected $primaryKey = 'id';
    protected $allowedFields = ['ec_id', 'salle', 'jour', 'heure_debut', 'heure_fin', 'type_cours'];
    protected $useTimestamps = false;
}