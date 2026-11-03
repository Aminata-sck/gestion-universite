<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\EmploiModel;
use App\Models\ECModel;
use App\Models\FormationModel;
use App\Models\AnneeUniversitaireModel;

class EmploiController extends Controller
{
    public function index()
    {
        $model = new EmploiModel();
        $data['emplois'] = $model->select('emplois_du_temps.*, 
                                           ecs.libelle as ec_libelle, 
                                           ecs.type as ec_type,
                                           ues.libelle as ue_libelle,
                                           formations.libelle as formation_libelle,
                                           formations.type as formation_type,
                                           formations.annee as formation_annee,
                                           annees_universitaires.libelle as annee_libelle')
            ->join('ecs', 'ecs.id = emplois_du_temps.ec_id')
            ->join('ues', 'ues.id = ecs.ue_id')
            ->join('formations', 'formations.id = ues.formation_id')
            ->join('annees_universitaires', 'annees_universitaires.id = emplois_du_temps.annee_universitaire_id', 'left')
            ->findAll();
        return view('emplois/index', $data);
    }

    // --------------------------------------------------------------
    // AJOUT SIMPLE
    // --------------------------------------------------------------
    public function new()
    {
        $ecModel = new ECModel();
        $anneeModel = new AnneeUniversitaireModel();
        $data['ecs'] = $ecModel->findAll();
        $data['annees'] = $anneeModel->findAll();
        return view('emplois/create', $data);
    }

    public function create()
    {
        $data = $this->request->getPost();
        if (empty($data['annee_universitaire_id'])) {
            $data['annee_universitaire_id'] = null;
        }
        $model = new EmploiModel();
        if ($model->save($data)) {
            return redirect()->to('/emplois')->with('success', 'Cours ajouté.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de l\'ajout.')->withInput();
        }
    }

    // --------------------------------------------------------------
    // MODIFICATION
    // --------------------------------------------------------------
    public function edit($id)
    {
        $model = new EmploiModel();
        $data['emploi'] = $model->find($id);
        if (!$data['emploi']) {
            return redirect()->to('/emplois')->with('error', 'Cours introuvable.');
        }
        $ecModel = new ECModel();
        $anneeModel = new AnneeUniversitaireModel();
        $data['ecs'] = $ecModel->findAll();
        $data['annees'] = $anneeModel->findAll();
        return view('emplois/edit', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        if (empty($data['annee_universitaire_id'])) {
            $data['annee_universitaire_id'] = null;
        }
        $model = new EmploiModel();
        if ($model->update($id, $data)) {
            return redirect()->to('/emplois')->with('success', 'Cours mis à jour.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour.')->withInput();
        }
    }

    // --------------------------------------------------------------
    // SUPPRESSION
    // --------------------------------------------------------------
    public function delete($id)
    {
        $model = new EmploiModel();
        if ($model->delete($id)) {
            return redirect()->to('/emplois')->with('success', 'Cours supprimé.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    // --------------------------------------------------------------
    // AJOUT GROUPÉ (formulaire)
    // --------------------------------------------------------------
    public function bulkAdd()
    {
        $formationModel = new FormationModel();
        $ecModel = new ECModel();
        $anneeModel = new AnneeUniversitaireModel();

        $data['formations'] = $formationModel
            ->select('id, CONCAT(type, " ", annee, " - ", libelle) as affichage')
            ->findAll();
        $data['ecs'] = $ecModel
            ->select('ecs.*, ues.libelle as ue_libelle, formations.libelle as formation_libelle, formations.type as formation_type, formations.annee as formation_annee')
            ->join('ues', 'ues.id = ecs.ue_id')
            ->join('formations', 'formations.id = ues.formation_id')
            ->findAll();
        $data['annees'] = $anneeModel->findAll();
        $data['jours'] = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $data['types_cours'] = ['CM' => 'Cours', 'TD' => 'TD', 'TP' => 'TP'];
        $data['title'] = 'Ajout groupé d\'emplois du temps';
        return view('emplois/bulk_add', $data);
    }

    // --------------------------------------------------------------
    // TRAITEMENT DE L'AJOUT GROUPÉ
    // --------------------------------------------------------------
    public function bulkStore()
    {
        $post = $this->request->getPost();
        $formationId = $post['formation_id'] ?? null;
        $ecsData = $post['ecs'] ?? [];
        $anneeId = $post['annee_universitaire_id'] ?? null;
        $classeGenerale = $post['classe'] ?? null;

        if (empty($formationId)) {
            return redirect()->back()->with('error', 'Veuillez sélectionner une formation.')->withInput();
        }
        if (empty($ecsData)) {
            return redirect()->back()->with('error', 'Aucun cours à ajouter.')->withInput();
        }

        $model = new EmploiModel();
        $count = 0;
        foreach ($ecsData as $ecData) {
            if (empty($ecData['ec_id']) || empty($ecData['salle']) || empty($ecData['jour']) || empty($ecData['heure_debut']) || empty($ecData['heure_fin'])) {
                continue;
            }
            $data = [
                'ec_id'         => $ecData['ec_id'],
                'salle'         => $ecData['salle'],
                'jour'          => $ecData['jour'],
                'heure_debut'   => $ecData['heure_debut'],
                'heure_fin'     => $ecData['heure_fin'],
                'type_cours'    => $ecData['type_cours'] ?? 'CM',
                'annee_universitaire_id' => $anneeId,
                'classe'        => $ecData['classe'] ?? $classeGenerale ?? null,
            ];
            if ($model->save($data)) {
                $count++;
            }
        }

        return redirect()->to('/emplois')->with('success', "$count cours ajoutés avec succès.");
    }
}