<?php

use App\Models\ProfilPermissionModel;

if (!function_exists('hasPermission')) {
    function hasPermission($module, $action = 'read')
    {
        // Vérifier si l'utilisateur est connecté
        $user = session()->get();
        if (!isset($user['user_id']) || !isset($user['profil_id'])) {
            return false;
        }

        // Les admins et scolarité ont tout (optionnel, à garder ou non)
        if ($user['role'] === 'admin') {
            return true;
        }

        // Pour les autres rôles, on vérifie les permissions
        $permModel = new ProfilPermissionModel();
        $permission = $permModel
            ->select('profil_permissions.value')
            ->join('permissions', 'permissions.id = profil_permissions.permission_id')
            ->where('profil_id', $user['profil_id'])
            ->where('module', $module)
            ->where('permissions.nom', $action)
            ->first();

        return $permission && $permission['value'] == 1;
    }
}
