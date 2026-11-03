<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'password', 'nom', 'prenom', 'photo', 'telephone', 'role', 'profil_id', 'statut'];
    protected $useTimestamps = true;
    protected $createdAt  = 'created_at';
    protected $updatedAt  = false; // ← empêche l'insertion de updated_at
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
}
