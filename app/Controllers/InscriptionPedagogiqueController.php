<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\InscriptionPedagogiqueModel;
use App\Models\UserModel;
use App\Models\ECModel;
use App\Models\FormationModel;
use App\Models\InscriptionAdminModel;
use App\Models\AnneeUniversitaireModel;

class InscriptionPedagogiqueController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new InscriptionPedagogiqueModel();
    }

    public function index()
{
    $model = new InscriptionPedagogiqueModel();
    $inscriptions = $model
        ->select('inscriptions_pedagogiques.*, 
                  CONCAT(etudiant.nom, " ", etudiant.prenom) as etudiant_nom,
                  ecs.libelle as ec_libelle,
                  ecs.type as ec_type,
                  ecs.id as ec_id,
                  ues.libelle as ue_libelle')
        ->join('users as etudiant', 'etudiant.id = inscriptions_pedagogiques.etudiant_id')
        ->join('ecs', 'ecs.id = inscriptions_pedagogiques.ec_id')
        ->join('ues', 'ues.id = ecs.ue_id')
        ->orderBy('ues.libelle', 'ASC')
        ->orderBy('ecs.libelle', 'ASC')
        ->findAll();

    // Grouper par EC
    $grouped = [];
    foreach ($inscriptions as $ins) {
        $key = $ins['ec_id'] . '|' . $ins['ec_libelle'];
        if (!isset($grouped[$key])) {
            $grouped[$key] = [
                'ec_id'      => $ins['ec_id'],
                'ec_libelle' => $ins['ec_libelle'],
                'ec_type'    => $ins['ec_type'],
                'ue_libelle' => $ins['ue_libelle'],
                'etudiants'  => []
            ];
        }
        $grouped[$key]['etudiants'][] = $ins;
    }

    $data = [
        'title'   => 'Inscriptions pédagogiques',
        'grouped' => $grouped,
    ];
    return view('inscriptions_pedagogiques/index', $data);
}

 public function new()
{
    $userModel = new UserModel();
    $ecModel = new ECModel();
    $formationModel = new FormationModel();

    // Récupérer les EC avec les informations de formation et d'UE
    $ecs = $ecModel
        ->select('ecs.*, 
                  ues.libelle as ue_libelle,
                  formations.libelle as formation_libelle,
                  formations.type as formation_type,
                  formations.annee as formation_annee')
        ->join('ues', 'ues.id = ecs.ue_id')
        ->join('formations', 'formations.id = ues.formation_id')
        ->findAll();

    $data['etudiants'] = $userModel->where('role', 'etudiant')->findAll();
    $data['ecs'] = $ecs;
    $data['formations'] = $formationModel
        ->select('id, CONCAT(type, " ", annee, " - ", libelle) as affichage')
        ->findAll();
    return view('inscriptions_pedagogiques/create', $data);
}

    public function create()
    {
        $data = $this->request->getPost();
        if ($this->model->save($data)) {
            return redirect()->to('/inscriptions-pedagogiques')->with('success', 'Inscription pédagogique créée.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la création.')->withInput();
        }
    }

    public function edit($id)
{
    $model = new InscriptionPedagogiqueModel();
    $data['inscription'] = $model->find($id);
    if (!$data['inscription']) {
        return redirect()->to('/inscriptions-pedagogiques')->with('error', 'Inscription introuvable.');
    }

    $userModel = new UserModel();
    $ecModel = new ECModel();

    $data['etudiants'] = $userModel->where('role', 'etudiant')->findAll();
    $data['ecs'] = $ecModel
        ->select('ecs.*, ues.libelle as ue_libelle, formations.type as formation_type, formations.annee as formation_annee')
        ->join('ues', 'ues.id = ecs.ue_id')
        ->join('formations', 'formations.id = ues.formation_id')
        ->findAll();

    return view('inscriptions_pedagogiques/edit', $data);
}
    public function update($id)
    {
        $data = $this->request->getPost();
        if ($this->model->update($id, $data)) {
            return redirect()->to('/inscriptions-pedagogiques')->with('success', 'Inscription mise à jour.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour.')->withInput();
        }
    }

    public function delete($id)
    {
        if ($this->model->delete($id)) {
            return redirect()->to('/inscriptions-pedagogiques')->with('success', 'Inscription supprimée.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    // --- Inscription groupée ---

   public function bulkAdd()
{
    $formationModel = new FormationModel();
    $ecModel = new ECModel();
    $anneeModel = new AnneeUniversitaireModel();

    $data['formations'] = $formationModel
        ->select('id, CONCAT(type, " ", annee, " - ", libelle) as affichage')
        ->findAll();
    $data['ecs'] = $ecModel->select('id, libelle')->findAll();
    $data['annees'] = $anneeModel->where('active', 1)->findAll();
    $data['title'] = 'Inscription groupée à un EC';
    return view('inscriptions_pedagogiques/bulk_add', $data);
}

    public function bulkStore()
    {
        $post = $this->request->getPost();
        $formationId = $post['formation_id'];
        $ecId = $post['ec_id'];
        $semestre = $post['semestre'];
        $statut = $post['statut'] ?? 'validée';
        $anneeId = $post['annee_universitaire_id'];

        // Récupérer les étudiants inscrits à cette formation pour l'année donnée
        $inscriptionAdminModel = new InscriptionAdminModel();
        $etudiants = $inscriptionAdminModel
            ->where('formation_id', $formationId)
            ->where('annee_universitaire_id', $anneeId)
            ->where('statut', 'validée')
            ->findAll();

        $count = 0;
        foreach ($etudiants as $etudiant) {
            // Vérifier si l'étudiant n'est pas déjà inscrit à cet EC pour ce semestre
            $existing = $this->model
                ->where('etudiant_id', $etudiant['etudiant_id'])
                ->where('ec_id', $ecId)
                ->where('semestre', $semestre)
                ->first();
            if (!$existing) {
                $this->model->save([
                    'etudiant_id' => $etudiant['etudiant_id'],
                    'ec_id'       => $ecId,
                    'semestre'    => $semestre,
                    'statut'      => $statut,
                    'date_inscription' => date('Y-m-d'),
                ]);
                $count++;
            }
        }

        return redirect()->to('/inscriptions-pedagogiques')
                         ->with('success', "$count étudiants ont été inscrits à l'EC.");
    }
}