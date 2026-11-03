<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\ProfilModel;

class UserController extends Controller
{
    public function index()
    {
        $model = new UserModel();
        $data['users'] = $model->select('users.*, profils.nom AS profil_nom')
            ->join('profils', 'profils.id = users.profil_id', 'left')
            ->findAll();
        return view('users/index', $data);
    }

    public function new()
    {
        return view('users/create', ['profils' => (new ProfilModel())->where('statut', 1)->findAll()]);
    }

    public function create()
{
    $model = new UserModel();
    $email = $this->request->getPost('email');
    
    // Vérifier si l'email existe déjà
    if ($model->where('email', $email)->first()) {
        return redirect()->back()->with('error', 'Cet email est déjà utilisé.')->withInput();
    }

    $data = $this->request->getPost();
    $data['profil_id'] = !empty($data['profil_id']) ? (int) $data['profil_id'] : null;
    if ($data['profil_id'] === null) {
        return redirect()->back()->with('error', 'Veuillez attribuer un profil RBAC.')->withInput();
    }
    $data['role'] = 'scolarite';
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    $model->save($data);
    return redirect()->to('/users')->with('success', 'Utilisateur créé.');
}

    public function edit($id)
    {
        $model = new UserModel();
        $data['user'] = $model->find($id);
        $data['profils'] = (new ProfilModel())->where('statut', 1)->findAll();
        return view('users/edit', $data);
    }

    public function update($id)
    {
        $model = new UserModel();
        $user = $model->find($id);
        $data = $this->request->getPost();
        $data['profil_id'] = !empty($data['profil_id']) ? (int) $data['profil_id'] : null;
        if ($data['profil_id'] === null && ($user['role'] ?? null) !== 'admin') {
            return redirect()->back()->with('error', 'Veuillez attribuer un profil RBAC.')->withInput();
        }
        if (($user['role'] ?? null) !== 'admin') {
            $data['role'] = 'scolarite';
        }
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        $model->update($id, $data);
        return redirect()->to('/users')->with('success', 'Utilisateur mis à jour.');
    }

    public function delete($id)
    {
        $model = new UserModel();
        $model->delete($id);
        return redirect()->to('/users')->with('success', 'Utilisateur supprimé.');
    }
}
