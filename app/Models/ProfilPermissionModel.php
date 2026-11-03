<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\PermissionModel; // ← IMPORTANT : importer le modèle Permission

class ProfilPermissionModel extends Model
{
    protected $table = 'profil_permissions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['profil_id', 'module', 'permission_id', 'menu_id', 'sous_menu_id', 'value'];
    protected $useTimestamps = false;

    private function getPermissionId($action)
    {
        $permModel = new PermissionModel();
        $perm = $permModel->where('nom', $action)->first();
        return $perm ? $perm['id'] : null;
    }

    // Récupère les permissions d'un profil sous forme associative
    public function getPermissionsByProfil($profilId)
    {
        $rows = $this
            ->select('profil_permissions.*, permissions.nom as action_nom')
            ->join('permissions', 'permissions.id = profil_permissions.permission_id')
            ->where('profil_id', $profilId)
            ->findAll();

        $perms = [];
        foreach ($rows as $row) {
            $perms[$row['module']][$row['action_nom']] = (bool)$row['value'];
        }
        return $perms;
    }

    // Synchronise les permissions pour un profil
    public function syncPermissions($profilId, $permissions, $menuId = null, $sousMenuId = null)
    {
        // Supprimer les anciennes permissions pour ce profil (et éventuellement filtrées par menu/sous-menu)
        $this->where('profil_id', $profilId);
        if ($menuId !== null) {
            $this->where('menu_id', $menuId);
        }
        if ($sousMenuId !== null) {
            $this->where('sous_menu_id', $sousMenuId);
        }
        $this->delete();

        // Réinsérer les nouvelles permissions
        $data = [];
        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action => $value) {
                $permId = $this->getPermissionId($action);
                if ($permId) {
                    $row = [
                        'profil_id'     => $profilId,
                        'module'        => $module,
                        'permission_id' => $permId,
                        'value'         => (int)$value,
                    ];
                    if ($menuId !== null) {
                        $row['menu_id'] = $menuId;
                    }
                    if ($sousMenuId !== null) {
                        $row['sous_menu_id'] = $sousMenuId;
                    }
                    $data[] = $row;
                }
            }
        }

        if (!empty($data)) {
            $this->insertBatch($data);
        }
    }
}