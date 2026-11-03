<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\AbsenceModel;
use App\Models\UserModel;
use App\Models\EmploiModel;
use App\Models\InscriptionPedagogiqueModel;

class AbsenceController extends Controller
{
    // --------------------------------------------------------------
    // INDEX : regroupement par cours (emploi du temps)
    // --------------------------------------------------------------
    public function index()
    {
        $model = new AbsenceModel();
        $absences = $model
            ->select('absences.*, 
                      CONCAT(etudiant.nom, " ", etudiant.prenom) as etudiant_nom,
                      emplois_du_temps.salle,
                      emplois_du_temps.jour,
                      emplois_du_temps.heure_debut,
                      emplois_du_temps.heure_fin,
                      emplois_du_temps.id as emploi_id,
                      ecs.libelle as ec_libelle,
                      CONCAT(enseignant.nom, " ", enseignant.prenom) as enseignant_nom')
            ->join('users as etudiant', 'etudiant.id = absences.etudiant_id')
            ->join('emplois_du_temps', 'emplois_du_temps.id = absences.emploi_id')
            ->join('ecs', 'ecs.id = emplois_du_temps.ec_id')
            ->join('users as enseignant', 'enseignant.id = absences.enseignant_id')
            ->orderBy('emplois_du_temps.jour', 'ASC')
            ->orderBy('emplois_du_temps.heure_debut', 'ASC')
            ->findAll();

        // Grouper par cours (emploi_id)
        $grouped = [];
        foreach ($absences as $a) {
            $key = $a['emploi_id'] . '|' . $a['ec_libelle'];
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'emploi_id'      => $a['emploi_id'],
                    'cours_libelle'  => $a['ec_libelle'],
                    'salle'          => $a['salle'],
                    'jour'           => $a['jour'],
                    'heure_debut'    => $a['heure_debut'],
                    'absences'       => []
                ];
            }
            $grouped[$key]['absences'][] = $a;
        }

        $data = [
            'title'   => 'Absences',
            'grouped' => $grouped,
        ];
        return view('absences/index', $data);
    }

    // --------------------------------------------------------------
    // AJOUT SIMPLE (formulaire)
    // --------------------------------------------------------------
    public function new()
    {
        $userModel = new UserModel();
        $emploiModel = new EmploiModel();
        
        $data['etudiants'] = $userModel->where('role', 'etudiant')->findAll();
        $data['emplois'] = $emploiModel->select('emplois_du_temps.*, ecs.libelle as ec_libelle')
            ->join('ecs', 'ecs.id = emplois_du_temps.ec_id')
            ->findAll();
        $data['enseignants'] = $userModel->where('role', 'enseignant')->findAll();
        
        return view('absences/create', $data);
    }

    public function create()
    {
        $model = new AbsenceModel();
        if ($model->save($this->request->getPost())) {
            return redirect()->to('/absences')->with('success', 'Absence enregistrée.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement.')->withInput();
        }
    }

    // --------------------------------------------------------------
    // MODIFICATION
    // --------------------------------------------------------------
    public function edit($id)
    {
        $model = new AbsenceModel();
        $data['absence'] = $model->find($id);
        if (!$data['absence']) {
            return redirect()->to('/absences')->with('error', 'Absence introuvable.');
        }
        $userModel = new UserModel();
        $emploiModel = new EmploiModel();
        $data['etudiants'] = $userModel->where('role', 'etudiant')->findAll();
        $data['emplois'] = $emploiModel->select('emplois_du_temps.*, ecs.libelle as ec_libelle')
            ->join('ecs', 'ecs.id = emplois_du_temps.ec_id')
            ->findAll();
        $data['enseignants'] = $userModel->where('role', 'enseignant')->findAll();
        return view('absences/edit', $data);
    }

    public function update($id)
    {
        $model = new AbsenceModel();
        if ($model->update($id, $this->request->getPost())) {
            return redirect()->to('/absences')->with('success', 'Absence mise à jour.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour.')->withInput();
        }
    }

    // --------------------------------------------------------------
    // SUPPRESSION
    // --------------------------------------------------------------
    public function delete($id)
    {
        $model = new AbsenceModel();
        $model->delete($id);
        return redirect()->to('/absences')->with('success', 'Absence supprimée.');
    }

    // --------------------------------------------------------------
    // SAISIE GROUPÉE (formulaire)
    // --------------------------------------------------------------
    public function bulkAdd()
    {
        $emploiModel = new EmploiModel();
        $userModel = new UserModel();

        // Récupérer les emplois avec l'EC lié (sans sélectionner ecs.salle)
        $emplois = $emploiModel
            ->select('emplois_du_temps.*, ecs.libelle as ec_libelle, ecs.id as ec_id')
            ->join('ecs', 'ecs.id = emplois_du_temps.ec_id')
            ->findAll();

        $enseignants = $userModel->where('role', 'enseignant')->where('statut', 1)->findAll();

        $data = [
            'title'       => 'Saisie groupée d\'absences',
            'emplois'     => $emplois,
            'enseignants' => $enseignants,
        ];
        return view('absences/bulk_add', $data);
    }

    // --------------------------------------------------------------
    // TRAITEMENT DE LA SAISIE GROUPÉE
    // --------------------------------------------------------------
    public function bulkStore()
    {
        $post = $this->request->getPost();
        $emploiId = $post['emploi_id'];
        $dateAbsence = $post['date_absence'];
        $enseignantId = $post['enseignant_id'];
        $etudiantsIds = $post['etudiants'] ?? [];

        if (empty($etudiantsIds)) {
            return redirect()->back()->with('error', 'Aucun étudiant sélectionné.')->withInput();
        }

        $model = new AbsenceModel();
        $count = 0;
        foreach ($etudiantsIds as $etudiantId) {
            $existing = $model
                ->where('etudiant_id', $etudiantId)
                ->where('emploi_id', $emploiId)
                ->where('date_absence', $dateAbsence)
                ->first();
            if (!$existing) {
                $model->save([
                    'etudiant_id'   => $etudiantId,
                    'emploi_id'     => $emploiId,
                    'date_absence'  => $dateAbsence,
                    'justifie'      => 0,
                    'motif'         => null,
                    'enseignant_id' => $enseignantId,
                ]);
                $count++;
            }
        }

        return redirect()->to('/absences')->with('success', "$count absence(s) enregistrée(s) avec succès.");
    }

    // --------------------------------------------------------------
    // AJAX : récupère les étudiants inscrits à un EC (pour bulk_add)
    // --------------------------------------------------------------
    public function getEtudiantsByEc($ecId)
    {
        $inscModel = new InscriptionPedagogiqueModel();
        $etudiants = $inscModel
            ->select('users.id as user_id, users.nom, users.prenom, users.email')
            ->join('users', 'users.id = inscriptions_pedagogiques.etudiant_id')
            ->where('ec_id', $ecId)
            ->where('inscriptions_pedagogiques.statut', 'validée') // ← correction
            ->findAll();

        return $this->response->setJSON($etudiants);
    }
}