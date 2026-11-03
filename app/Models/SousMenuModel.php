<?php namespace App\Models;

use CodeIgniter\Model;

class SousMenuModel extends Model
{
    protected $table = 'sous_menu';
    protected $primaryKey = 'id';
    protected $allowedFields = ['parent_id', 'nom', 'url', 'icon', 'permission', 'ordre', 'statut'];
    protected $useTimestamps = false;

    public function getMenus($role = null)
    {
        $builder = $this->where('statut', 1)->orderBy('ordre', 'ASC');
        $items = $builder->findAll();

        if ($role) {
            $items = array_filter($items, function($item) {
                $module = $this->moduleFromUrl($item['url'] ?? '');
                return $module === null || hasPermission($module, 'read');
            });
        }

        $tree = [];
        foreach ($items as $item) {
            if ($item['parent_id'] == 0) {
                $item['children'] = $this->getChildren($items, $item['id']);
                // Un parent sans URL ne doit pas apparaître lorsque tous ses sous-menus sont interdits.
                if (!empty($item['url']) || !empty($item['children'])) {
                    $tree[] = $item;
                }
            }
        }
        return $tree;
    }

    private function moduleFromUrl(string $url): ?string
    {
        $segment = explode('/', trim($url, '/'))[0] ?? '';
        return [
            'users' => 'utilisateurs',
            'formations' => 'formations',
            'ues' => 'ues',
            'ecs' => 'ecs',
            'inscriptions-admin' => 'inscriptions_admin',
            'inscriptions-pedagogiques' => 'inscriptions_pedagogiques',
            'emplois' => 'emplois',
            'notes' => 'notes',
            'absences' => 'absences',
            'paiements' => 'paiements',
            'profils' => 'profils',
        ][$segment] ?? null;
    }

    private function getChildren($items, $parentId)
    {
        $children = [];
        foreach ($items as $item) {
            if ($item['parent_id'] == $parentId) {
                $item['children'] = $this->getChildren($items, $item['id']);
                $children[] = $item;
            }
        }
        return $children;
    }
}
