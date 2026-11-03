<?php namespace App\Models;

use CodeIgniter\Model;

class NoteModel extends Model
{
    protected $table = 'notes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['etudiant_id', 'ec_id', 'type', 'note', 'coefficient', 'date_saisie', 'enseignant_id', 'session'];
    protected $useTimestamps = false;

    
}