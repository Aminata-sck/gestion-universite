<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UEModel;
use App\Models\ECModel;
use App\Models\FormationModel;

class UeController extends Controller
{
    protected $model;
    protected $formationModel;

    public function __construct()
    {
        $this->model = new UEModel();
        $this->formationModel = new FormationModel();
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'scolarite'])) {
            return redirect()->to('/dashboard')->with('error', 'Accès interdit.');
        }
    }

    // Génération d'un code unique au format UE-YYYY-NNNN
    private function generateCode()
    {
        $annee = date('Y');
        $prefix = 'UE-' . $annee . '-';
        // Récupérer le dernier code existant pour cette année
        $last = $this->model
            ->select('code')
            ->like('code', $prefix, 'after')
            ->orderBy('code', 'DESC')
            ->first();
        if ($last) {
            // Extraire les 4 derniers caractères (le numéro)
            $lastNum = (int) substr($last['code'], -4);
            $numero = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $numero = '0001';
        }
        return $prefix . $numero;
    }

 public function index()
{
    $ues = $this->model
        ->select('ues.*, 
                  formations.libelle as formation_libelle, 
                  formations.type as formation_type,
                  formations.annee as formation_annee')
        ->join('formations', 'formations.id = ues.formation_id')
        ->orderBy('formations.libelle', 'ASC')
        ->findAll();

    // Grouper par formation
    $grouped = [];
    foreach ($ues as $ue) {
        $key = $ue['formation_id'] . '|' . $ue['formation_type'] . ' ' . $ue['formation_annee'] . ' - ' . $ue['formation_libelle'];
        if (!isset($grouped[$key])) {
            $grouped[$key] = [
                'formation_id'   => $ue['formation_id'],
                'formation_nom'  => $ue['formation_type'] . ' ' . $ue['formation_annee'] . ' - ' . $ue['formation_libelle'],
                'ues'            => []
            ];
        }
        $grouped[$key]['ues'][] = $ue;
    }

    $data = [
        'title'   => 'Unités d\'Enseignement',
        'grouped' => $grouped,
    ];
    return view('ues/index', $data);
}

    public function new()
    {
        $data['formations'] = $this->formationModel
            ->select('id, CONCAT(type, " ", annee, " - ", libelle) as affichage')
            ->findAll();
        $data['code'] = $this->generateCode();
        $data['cycles'] = ['Licence', 'Master', 'Doctorat'];
        return view('ues/create', $data);
    }

    public function create()
    {
        $data = $this->request->getPost();
        $data['code'] = $this->generateCode();
        if ($this->model->save($data)) {
            return redirect()->to('/ues')->with('success', 'UE créée.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la création.')->withInput();
        }
    }

    public function edit($id)
    {
        $data['ue'] = $this->model->find($id);
        if (!$data['ue']) {
            return redirect()->to('/ues')->with('error', 'UE introuvable.');
        }
        $data['formations'] = $this->formationModel
            ->select('id, CONCAT(type, " ", annee, " - ", libelle) as affichage')
            ->findAll();
        $data['cycles'] = ['Licence', 'Master', 'Doctorat'];
        return view('ues/edit', $data);
    }

    /** Affiche la fiche pédagogique d'une UE et ses éléments constitutifs. */
    public function show($id)
    {
        $ue = $this->model
            ->select('ues.*, formations.libelle as formation_libelle, formations.type as formation_type, formations.annee as formation_annee')
            ->join('formations', 'formations.id = ues.formation_id')
            ->find($id);

        if (!$ue) {
            return redirect()->to('/ues')->with('error', 'UE introuvable.');
        }

        $ecs = (new ECModel())
            ->select('ecs.*, CONCAT(users.nom, " ", users.prenom) as enseignant_nom')
            ->join('users', 'users.id = ecs.enseignant_id', 'left')
            ->where('ecs.ue_id', $id)
            ->orderBy('ecs.type', 'ASC')
            ->orderBy('ecs.libelle', 'ASC')
            ->findAll();

        return view('ues/show', [
            'title' => 'Fiche UE',
            'ue' => $ue,
            'ecs' => $ecs,
        ]);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        // On ne modifie pas le code
        if ($this->model->update($id, $data)) {
            return redirect()->to('/ues')->with('success', 'UE mise à jour.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour.')->withInput();
        }
    }

    public function delete($id)
    {
        if ($this->model->delete($id)) {
            return redirect()->to('/ues')->with('success', 'UE supprimée.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    // --------------------------------------------------------------
    // AJOUT GROUPÉ
    // --------------------------------------------------------------
    public function bulkAdd()
    {
        $data['formations'] = $this->formationModel
            ->select('id, CONCAT(type, " ", annee, " - ", libelle) as affichage')
            ->findAll();
        return view('ues/bulk_add', $data);
    }

    public function bulkStore()
    {
        $post = $this->request->getPost();
        $formationId = $post['formation_id'] ?? null;
        $semestre = $post['semestre'] ?? null;
        $credits = $post['credits_ects'] ?? 6;
        $coefficient = $post['coefficient'] ?? 1;
        $cycle = $post['cycle'] ?? 'Licence';
        $libellesRaw = $post['libelles'] ?? '';

        $libelles = array_filter(array_map('trim', explode("\n", $libellesRaw)));

        if (empty($formationId) || empty($semestre) || empty($libelles)) {
            return redirect()->back()->with('error', 'Veuillez remplir tous les champs.')->withInput();
        }

        $count = 0;
        foreach ($libelles as $libelle) {
            // Générer un code unique pour chaque UE
            $code = $this->generateCode();
            $data = [
                'code'          => $code,
                'libelle'       => $libelle,
                'formation_id'  => $formationId,
                'semestre'      => $semestre,
                'credits_ects'  => $credits,
                'coefficient'   => $coefficient,
                'cycle'         => $cycle,
            ];
            if ($this->model->save($data)) {
                $count++;
            }
        }

        return redirect()->to('/ues')->with('success', "$count UE(s) créée(s) avec succès.");
    }
}
