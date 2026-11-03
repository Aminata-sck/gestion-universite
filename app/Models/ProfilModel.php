<?php namespace App\Models;

use CodeIgniter\Model;

class ProfilModel extends Model
{
    protected $table = 'profils';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'statut'];
    protected $useTimestamps = false;
}