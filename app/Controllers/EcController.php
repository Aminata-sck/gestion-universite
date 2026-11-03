<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ECModel;
use App\Models\UEModel;
use App\Models\UserModel;

class EcController extends Controller
{
    public function index()
    {
        $ecs = (new ECModel())
            ->select('ecs.*, ues.libelle as ue_libelle, ues.id as ue_id, ues.coefficient as ue_coefficient, formations.type as formation_type, formations.annee as formation_annee, CONCAT(users.nom, " ", users.prenom) as enseignant_nom')
            ->join('ues', 'ues.id = ecs.ue_id')->join('formations', 'formations.id = ues.formation_id')
            ->join('users', 'users.id = ecs.enseignant_id', 'left')->findAll();
        $grouped = [];
        foreach ($ecs as $ec) {
            $ueId = $ec['ue_id'];
            if (!isset($grouped[$ueId])) $grouped[$ueId] = ['ue_libelle' => $ec['ue_libelle'], 'ue_coefficient' => $ec['ue_coefficient'] ?? 1, 'formation_type' => $ec['formation_type'], 'formation_annee' => $ec['formation_annee'], 'ecs' => []];
            $grouped[$ueId]['ecs'][] = $ec;
        }
        return view('ecs/index', ['grouped_ecs' => $grouped, 'title' => 'Éléments Constitutifs']);
    }

    public function new()
    {
        return redirect()->to('/ues')->with('success', "Ouvrez la fiche de l'UE concernée pour y ajouter un EC.");
    }

    public function create()
    {
        return redirect()->to('/ues')->with('error', "Un EC doit être créé depuis la fiche de son UE.");
    }

    public function newForUe($ueId)
    {
        $ue = (new UEModel())->select('ues.*, formations.libelle as formation_libelle, formations.type as formation_type, formations.annee as formation_annee')->join('formations', 'formations.id = ues.formation_id')->find($ueId);
        if (!$ue) return redirect()->to('/ues')->with('error', 'UE introuvable.');
        return view('ecs/create', ['ue' => $ue, 'enseignants' => (new UserModel())->where('role', 'enseignant')->findAll(), 'coefficient_default' => $ue['coefficient'] ?? 1]);
    }

    /** L'ue_id est déterminé par l'URL et n'est jamais lu dans la requête POST. */
    public function createForUe($ueId)
    {
        $ue = (new UEModel())->find($ueId);
        if (!$ue) return redirect()->to('/ues')->with('error', 'UE introuvable.');
        $rules = ['libelle' => 'required|max_length[255]', 'coefficient' => 'required|decimal', 'type' => 'required|in_list[CM,TD,TP]', 'enseignant_id' => 'required|is_natural_no_zero'];
        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('error', 'Veuillez renseigner correctement tous les champs.');
        $enseignantId = (int) $this->request->getPost('enseignant_id');
        if (!(new UserModel())->where('id', $enseignantId)->where('role', 'enseignant')->first()) return redirect()->back()->withInput()->with('error', "L'enseignant sélectionné est invalide.");
        $data = ['ue_id' => (int) $ueId, 'libelle' => trim((string) $this->request->getPost('libelle')), 'coefficient' => $this->request->getPost('coefficient'), 'type' => $this->request->getPost('type'), 'enseignant_id' => $enseignantId];
        if (!(new ECModel())->insert($data)) return redirect()->back()->withInput()->with('error', "Erreur lors de la création de l'EC.");
        return redirect()->to('/ues/' . $ueId)->with('success', "EC ajouté à l'UE « {$ue['libelle']} ».");
    }

    public function edit($id)
    {
        $ec = (new ECModel())->find($id);
        if (!$ec) return redirect()->to('/ecs')->with('error', 'EC introuvable.');
        return view('ecs/edit', ['ec' => $ec, 'ue' => (new UEModel())->find($ec['ue_id']), 'enseignants' => (new UserModel())->where('role', 'enseignant')->findAll()]);
    }

    /** La mise à jour ne permet pas de déplacer l'EC vers une autre UE. */
    public function update($id)
    {
        $model = new ECModel(); $ec = $model->find($id);
        if (!$ec) return redirect()->to('/ecs')->with('error', 'EC introuvable.');
        $model->update($id, ['libelle' => $this->request->getPost('libelle'), 'coefficient' => $this->request->getPost('coefficient'), 'type' => $this->request->getPost('type'), 'enseignant_id' => $this->request->getPost('enseignant_id')]);
        return redirect()->to('/ues/' . $ec['ue_id'])->with('success', 'EC mis à jour.');
    }

    public function delete($id)
    {
        $model = new ECModel(); $ec = $model->find($id);
        if (!$ec) return redirect()->to('/ecs')->with('error', 'EC introuvable.');
        $model->delete($id);
        return redirect()->to('/ues/' . $ec['ue_id'])->with('success', 'EC supprimé.');
    }

    public function bulkAdd() { return redirect()->to('/ues')->with('success', "Ajoutez les EC depuis la fiche de leur UE."); }
    public function bulkStore() { return redirect()->to('/ues')->with('error', "Un EC doit être ajouté depuis la fiche de son UE."); }
}
