<?php namespace App\Models;

use CodeIgniter\Model;

class AbsenceModel extends Model
{
    protected $table = 'absences';
    protected $primaryKey = 'id';
    protected $allowedFields = ['etudiant_id', 'emploi_id', 'date_absence', 'justifie', 'motif', 'enseignant_id'];
    protected $useTimestamps = false;
}