<?php namespace App\Models;

use CodeIgniter\Model;

class ECModel extends Model
{
    protected $table = 'ecs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['ue_id', 'libelle', 'coefficient', 'type', 'enseignant_id'];
    protected $useTimestamps = true;
}