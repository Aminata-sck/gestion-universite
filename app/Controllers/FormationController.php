<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\FormationModel;

class FormationController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new FormationModel();
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'scolarite'])) {
            return redirect()->to('/dashboard')->with('error', 'Accès interdit.');
        }
    }

    public function index()
    {
        $data['formations'] = $this->model->where('archive', 0)->findAll();
        return view('formations/index', $data);
    }

    public function archived()
    {
        $data['formations'] = $this->model->where('archive', 1)->findAll();
        return view('formations/archived', $data);
    }

    public function new()
    {
        return view('formations/create');
    }

    public function create()
    {
        $data = $this->request->getPost();
        $data['archive'] = 0;
        $this->model->save($data);
        return redirect()->to('/formations')->with('success', 'Formation créée.');
    }

    public function edit($id)
    {
        $data['formation'] = $this->model->find($id);
        return view('formations/edit', $data);
    }

    public function update($id)
    {
        $this->model->update($id, $this->request->getPost());
        return redirect()->to('/formations')->with('success', 'Formation mise à jour.');
    }

    public function archive($id)
    {
        $this->model->update($id, ['archive' => 1]);
        return redirect()->to('/formations')->with('success', 'Formation archivée.');
    }

    public function restore($id)
    {
        $this->model->update($id, ['archive' => 0]);
        return redirect()->to('/formations')->with('success', 'Formation restaurée.');
    }
}