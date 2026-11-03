<?php namespace App\Models;

use CodeIgniter\Model;

class UEModel extends Model
{
    protected $table = 'ues';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code', 'libelle', 'formation_id', 'semestre', 'credits_ects', 'coefficient', 'cycle'];
    protected $useTimestamps = false;
}