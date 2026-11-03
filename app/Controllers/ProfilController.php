<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ProfilModel;
use App\Models\ProfilPermissionModel;

class ProfilController extends Controller
{
    protected $profilModel;
    protected $permModel;

    private $modules = [
        'formations'                => 'Formations',
        'ues'                       => 'UE',
        'ecs'                       => 'EC',
        'inscriptions_admin'        => 'Inscriptions administratives',
        'inscriptions_pedagogiques' => 'Inscriptions pédagogiques',
        'inscriptions_groupées'     => 'Inscription groupée',
        'emplois'                   => 'Emplois du temps',
        'notes'                     => 'Notes',
        'absences'                  => 'Absences',
        'paiements'                 => 'Paiements',
        'utilisateurs'              => 'Utilisateurs',
        'profils'                   => 'Gestion des profils',
    ];

    private $actions = ['read', 'add', 'edit', 'delete'];

    public function __construct()
    {
        $this->profilModel = new ProfilModel();
        $this->permModel = new ProfilPermissionModel();
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'scolarite'])) {
            return redirect()->to('/dashboard')->with('error', 'Accès interdit.');
        }
    }

    public function index()
    {
        $data['profils'] = $this->profilModel->findAll();
        $data['title'] = 'Gestion des profils';
        return view('profils/index', $data);
    }

    public function new()
    {
        $data['modules'] = $this->modules;
        $data['actions'] = $this->actions;
        $data['title'] = 'Ajouter un profil';
        return view('profils/create', $data);
    }

    public function create()
    {
        $post = $this->request->getPost();
        $profilId = $this->profilModel->insert([
            'nom'    => $post['nom'],
            'statut' => $post['statut'] ?? 1,
        ]);

        if (!$profilId) {
            return redirect()->back()->with('error', 'Erreur lors de la création.')->withInput();
        }

        $permissions = $this->buildPermissionsFromPost($post);
        $this->permModel->syncPermissions($profilId, $permissions);

        return redirect()->to('/profils')->with('success', 'Profil créé.');
    }

    public function edit($id)
{
    $data['profil'] = $this->profilModel->find($id);
    if (!$data['profil']) {
        return redirect()->to('/profils')->with('error', 'Profil introuvable.');
    }
    $data['modules'] = $this->modules;
    $data['actions'] = $this->actions;
    $data['permissions'] = $this->permModel->getPermissionsByProfil($id);
    $data['title'] = 'Modifier le profil';
    return view('profils/edit', $data);
}

    public function update($id)
    {
        $post = $this->request->getPost();
        $this->profilModel->update($id, [
            'nom'    => $post['nom'],
            'statut' => $post['statut'] ?? 1,
        ]);

        $permissions = $this->buildPermissionsFromPost($post);
        $this->permModel->syncPermissions($id, $permissions);

        return redirect()->to('/profils')->with('success', 'Profil mis à jour.');
    }

    public function delete($id)
    {
        $userModel = new \App\Models\UserModel();
        $used = $userModel->where('profil_id', $id)->countAllResults();
        if ($used > 0) {
            return redirect()->back()->with('error', 'Ce profil est utilisé par des utilisateurs.');
        }
        $this->profilModel->delete($id);
        $this->permModel->where('profil_id', $id)->delete();
        return redirect()->to('/profils')->with('success', 'Profil supprimé.');
    }

    private function buildPermissionsFromPost($post)
    {
        $permissions = [];
        foreach ($this->modules as $module => $label) {
            foreach ($this->actions as $action) {
                $key = $module . '_' . $action;
                $permissions[$module][$action] = isset($post[$key]) ? 1 : 0;
            }
        }
        return $permissions;
    }
}