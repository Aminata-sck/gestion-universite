<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\ECModel;
use App\Models\NoteModel;
use App\Models\AbsenceModel;
use App\Models\EmploiModel;
use App\Models\InscriptionPedagogiqueModel;

class TeacherController extends Controller
{
    protected $teacherId;

    public function __construct()
    {
        // Vérifier que l'utilisateur est connecté et a le rôle 'enseignant'
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'enseignant') {
            return redirect()->to('/login')->with('error', 'Accès réservé aux enseignants.');
        }
        $this->teacherId = session()->get('user_id');
    }

    // --------------------------------------------------------------
    // TABLEAU DE BORD
    // --------------------------------------------------------------
    public function dashboard()
    {
        $userModel = new UserModel();
        $user = $userModel->find($this->teacherId);

        $ecModel = new ECModel();
        $ecs = $ecModel->where('enseignant_id', $this->teacherId)->findAll();
        $ecIds = array_column($ecs, 'id');

        $emplois = [];
        if (!empty($ecIds)) {
            $emploiModel = new EmploiModel();
            $emplois = $emploiModel
                ->select('emplois_du_temps.*, ecs.libelle as ec_libelle')
                ->join('ecs', 'ecs.id = emplois_du_temps.ec_id')
                ->whereIn('ecs.id', $ecIds)
                ->orderBy('emplois_du_temps.jour', 'ASC')
                ->orderBy('emplois_du_temps.heure_debut', 'ASC')
                ->findAll();
        }

        $noteModel = new NoteModel();
        $dernieresNotes = $noteModel
            ->select('notes.*, ecs.libelle as ec_libelle, CONCAT(users.nom, " ", users.prenom) as etudiant_nom')
            ->join('ecs', 'ecs.id = notes.ec_id')
            ->join('users', 'users.id = notes.etudiant_id')
            ->where('notes.enseignant_id', $this->teacherId)
            ->orderBy('notes.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        $data = [
            'title'           => 'Tableau de bord enseignant',
            'user'            => $user,
            'total_ec'        => count($ecs),
            'ecs'             => $ecs,
            'emplois'         => $emplois,
            'dernieres_notes' => $dernieresNotes,
        ];
        return view('teacher/dashboard', $data);
    }

    // --------------------------------------------------------------
    // LISTE DES COURS AVEC DETAILS (EMPLOIS, NOTES, ABSENCES)
    // --------------------------------------------------------------
    public function courses()
    {
        $ecModel = new ECModel();
        $ecs = $ecModel
            ->select('ecs.*, ues.libelle as ue_libelle')
            ->join('ues', 'ues.id = ecs.ue_id')
            ->where('enseignant_id', $this->teacherId)
            ->findAll();

        $emploiModel = new EmploiModel();
        $noteModel = new NoteModel();
        $absenceModel = new AbsenceModel();

        foreach ($ecs as &$ec) {
            // Emplois du temps
            $ec['emplois'] = $emploiModel->where('ec_id', $ec['id'])->findAll();

            // Notes
            $ec['notes'] = $noteModel
                ->select('notes.*, CONCAT(users.nom, " ", users.prenom) as etudiant_nom')
                ->join('users', 'users.id = notes.etudiant_id')
                ->where('notes.ec_id', $ec['id'])
                ->findAll();

            // Absences (liées aux emplois de cet EC)
            $emploisIds = array_column($ec['emplois'], 'id');
            if (!empty($emploisIds)) {
                $ec['absences'] = $absenceModel
                    ->select('absences.*, CONCAT(users.nom, " ", users.prenom) as etudiant_nom, emplois_du_temps.jour, emplois_du_temps.heure_debut')
                    ->join('users', 'users.id = absences.etudiant_id')
                    ->join('emplois_du_temps', 'emplois_du_temps.id = absences.emploi_id')
                    ->whereIn('absences.emploi_id', $emploisIds)
                    ->findAll();
            } else {
                $ec['absences'] = [];
            }
        }

        $data['ecs'] = $ecs;
        $data['title'] = 'Mes cours';
        return view('teacher/courses', $data);
    }

    // --------------------------------------------------------------
    // GESTION DES NOTES
    // --------------------------------------------------------------
    public function grades($ecId)
    {
        $ecModel = new ECModel();
        $ec = $ecModel->find($ecId);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }

        $inscModel = new InscriptionPedagogiqueModel();
        $data['etudiants'] = $inscModel
            ->select('inscriptions_pedagogiques.*, users.nom, users.prenom, users.id as user_id')
            ->join('users', 'users.id = inscriptions_pedagogiques.etudiant_id')
            ->where('ec_id', $ecId)
            ->where('inscriptions_pedagogiques.statut', 'validée')
            ->findAll();

        $data['ec'] = $ec;
        $data['title'] = 'Saisie des notes - ' . $ec['libelle'];
        return view('teacher/grades', $data);
    }

    public function saveGrades()
    {
        $post = $this->request->getPost();
        $ecId = $post['ec_id'];
        $noteModel = new NoteModel();

        $ecModel = new ECModel();
        $ec = $ecModel->find($ecId);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }

        foreach ($post['notes'] as $etudiantId => $note) {
            if ($note !== '') {
                $existing = $noteModel
                    ->where('etudiant_id', $etudiantId)
                    ->where('ec_id', $ecId)
                    ->where('type', $post['type'])
                    ->first();

                if ($existing) {
                    $noteModel->update($existing['id'], [
                        'note' => $note,
                        'coefficient' => $post['coefficient'] ?? 1,
                        'session' => $post['session'] ?? null,
                    ]);
                } else {
                    $noteModel->save([
                        'etudiant_id'   => $etudiantId,
                        'ec_id'         => $ecId,
                        'type'          => $post['type'],
                        'note'          => $note,
                        'coefficient'   => $post['coefficient'] ?? 1,
                        'enseignant_id' => $this->teacherId,
                        'date_saisie'   => date('Y-m-d H:i:s'),
                        'session'       => $post['session'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->to('/teacher/courses')->with('success', 'Notes enregistrées.');
    }

    public function editGrade($id)
    {
        $noteModel = new NoteModel();
        $note = $noteModel->find($id);
        if (!$note) {
            return redirect()->to('/teacher/courses')->with('error', 'Note introuvable.');
        }
        $ecModel = new ECModel();
        $ec = $ecModel->find($note['ec_id']);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }
        $data['note'] = $note;
        $data['ec'] = $ec;
        $data['types'] = ['cc' => 'Contrôle continu', 'examen' => 'Examen', 'rattrapage' => 'Rattrapage'];
        return view('teacher/grade_edit', $data);
    }

    public function updateGrade($id)
    {
        $post = $this->request->getPost();
        $noteModel = new NoteModel();
        $note = $noteModel->find($id);
        if (!$note) {
            return redirect()->to('/teacher/courses')->with('error', 'Note introuvable.');
        }
        $ecModel = new ECModel();
        $ec = $ecModel->find($note['ec_id']);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }
        $data = [
            'note'        => $post['note'],
            'type'        => $post['type'],
            'coefficient' => $post['coefficient'],
            'session'     => $post['session'],
        ];
        if ($noteModel->update($id, $data)) {
            return redirect()->to('/teacher/courses')->with('success', 'Note mise à jour.');
        }
        return redirect()->back()->with('error', 'Erreur lors de la mise à jour.')->withInput();
    }

    public function deleteGrade($id)
    {
        $noteModel = new NoteModel();
        $note = $noteModel->find($id);
        if (!$note) {
            return redirect()->to('/teacher/courses')->with('error', 'Note introuvable.');
        }
        $ecModel = new ECModel();
        $ec = $ecModel->find($note['ec_id']);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }
        if ($noteModel->delete($id)) {
            return redirect()->to('/teacher/courses')->with('success', 'Note supprimée.');
        }
        return redirect()->back()->with('error', 'Erreur lors de la suppression.');
    }

    // --------------------------------------------------------------
    // GESTION DES ABSENCES
    // --------------------------------------------------------------
    public function absences($ecId)
    {
        $ecModel = new ECModel();
        $ec = $ecModel->find($ecId);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }

        $emploiModel = new EmploiModel();
        $data['emplois'] = $emploiModel->where('ec_id', $ecId)->findAll();

        $inscModel = new InscriptionPedagogiqueModel();
        $data['etudiants'] = $inscModel
            ->select('inscriptions_pedagogiques.*, users.nom, users.prenom, users.id as user_id')
            ->join('users', 'users.id = inscriptions_pedagogiques.etudiant_id')
            ->where('ec_id', $ecId)
            ->where('inscriptions_pedagogiques.statut', 'validée')
            ->findAll();

        $data['ec'] = $ec;
        $data['title'] = 'Saisie des absences - ' . $ec['libelle'];
        return view('teacher/absences', $data);
    }

    public function saveAbsences()
    {
        $post = $this->request->getPost();
        $ecId = $post['ec_id'];
        $emploiId = $post['emploi_id'];
        $dateAbsence = $post['date_absence'];

        $absenceModel = new AbsenceModel();
        $ecModel = new ECModel();
        $ec = $ecModel->find($ecId);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }

        foreach ($post['etudiants'] as $etudiantId => $absent) {
            if ($absent == '1') {
                $existing = $absenceModel
                    ->where('etudiant_id', $etudiantId)
                    ->where('emploi_id', $emploiId)
                    ->where('date_absence', $dateAbsence)
                    ->first();
                if (!$existing) {
                    $absenceModel->save([
                        'etudiant_id'   => $etudiantId,
                        'emploi_id'     => $emploiId,
                        'date_absence'  => $dateAbsence,
                        'justifie'      => 0,
                        'enseignant_id' => $this->teacherId,
                    ]);
                }
            }
        }

        return redirect()->to('/teacher/courses')->with('success', 'Absences enregistrées.');
    }

    public function editAbsence($id)
    {
        $absenceModel = new AbsenceModel();
        $absence = $absenceModel->find($id);
        if (!$absence) {
            return redirect()->to('/teacher/courses')->with('error', 'Absence introuvable.');
        }
        $emploiModel = new EmploiModel();
        $emploi = $emploiModel->find($absence['emploi_id']);
        if (!$emploi) {
            return redirect()->to('/teacher/courses')->with('error', 'Cours introuvable.');
        }
        $ecModel = new ECModel();
        $ec = $ecModel->find($emploi['ec_id']);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }
        $data['absence'] = $absence;
        $data['ec'] = $ec;
        return view('teacher/absence_edit', $data);
    }

    public function updateAbsence($id)
    {
        $post = $this->request->getPost();
        $absenceModel = new AbsenceModel();
        $absence = $absenceModel->find($id);
        if (!$absence) {
            return redirect()->to('/teacher/courses')->with('error', 'Absence introuvable.');
        }
        $emploiModel = new EmploiModel();
        $emploi = $emploiModel->find($absence['emploi_id']);
        if (!$emploi) {
            return redirect()->to('/teacher/courses')->with('error', 'Cours introuvable.');
        }
        $ecModel = new ECModel();
        $ec = $ecModel->find($emploi['ec_id']);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }
        $data = [
            'date_absence' => $post['date_absence'],
            'justifie'     => isset($post['justifie']) ? 1 : 0,
            'motif'        => $post['motif'],
        ];
        if ($absenceModel->update($id, $data)) {
            return redirect()->to('/teacher/courses')->with('success', 'Absence mise à jour.');
        }
        return redirect()->back()->with('error', 'Erreur lors de la mise à jour.')->withInput();
    }

    public function deleteAbsence($id)
    {
        $absenceModel = new AbsenceModel();
        $absence = $absenceModel->find($id);
        if (!$absence) {
            return redirect()->to('/teacher/courses')->with('error', 'Absence introuvable.');
        }
        $emploiModel = new EmploiModel();
        $emploi = $emploiModel->find($absence['emploi_id']);
        if (!$emploi) {
            return redirect()->to('/teacher/courses')->with('error', 'Cours introuvable.');
        }
        $ecModel = new ECModel();
        $ec = $ecModel->find($emploi['ec_id']);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }
        if ($absenceModel->delete($id)) {
            return redirect()->to('/teacher/courses')->with('success', 'Absence supprimée.');
        }
        return redirect()->back()->with('error', 'Erreur lors de la suppression.');
    }

    // --------------------------------------------------------------
    // GESTION DES EMPLOIS DU TEMPS (CRÉNEAUX)
    // --------------------------------------------------------------
    public function schedule()
    {
        $ecModel = new ECModel();
        $ecs = $ecModel->where('enseignant_id', $this->teacherId)->findAll();
        $ecIds = array_column($ecs, 'id');

        $emplois = [];
        if (!empty($ecIds)) {
            $emploiModel = new EmploiModel();
            $emplois = $emploiModel
                ->select('emplois_du_temps.*, ecs.libelle as ec_libelle, annees_universitaires.libelle as annee_libelle')
                ->join('ecs', 'ecs.id = emplois_du_temps.ec_id')
                ->join('annees_universitaires', 'annees_universitaires.id = emplois_du_temps.annee_universitaire_id', 'left')
                ->whereIn('ecs.id', $ecIds)
                ->orderBy('emplois_du_temps.jour', 'ASC')
                ->orderBy('emplois_du_temps.heure_debut', 'ASC')
                ->findAll();
        }

        $data = [
            'title'   => 'Mon emploi du temps',
            'emplois' => $emplois,
        ];
        return view('teacher/schedule', $data);
    }

    public function scheduleCreate($ecId)
    {
        $ecModel = new ECModel();
        $ec = $ecModel->find($ecId);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }
        $data['ec'] = $ec;
        $data['jours'] = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $data['types'] = ['CM' => 'Cours', 'TD' => 'TD', 'TP' => 'TP'];
        return view('teacher/schedule_create', $data);
    }

    public function scheduleStore()
    {
        $post = $this->request->getPost();
        $ecId = $post['ec_id'];
        $ecModel = new ECModel();
        $ec = $ecModel->find($ecId);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }
        $data = [
            'ec_id'       => $ecId,
            'salle'       => $post['salle'],
            'jour'        => $post['jour'],
            'heure_debut' => $post['heure_debut'],
            'heure_fin'   => $post['heure_fin'],
            'type_cours'  => $post['type_cours'],
        ];
        $model = new EmploiModel();
        if ($model->save($data)) {
            return redirect()->to('/teacher/courses')->with('success', 'Cours ajouté à l\'emploi du temps.');
        }
        return redirect()->back()->with('error', 'Erreur lors de l\'ajout.')->withInput();
    }

    public function scheduleEdit($id)
    {
        $model = new EmploiModel();
        $emploi = $model->find($id);
        if (!$emploi) {
            return redirect()->to('/teacher/courses')->with('error', 'Cours introuvable.');
        }
        $ecModel = new ECModel();
        $ec = $ecModel->find($emploi['ec_id']);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }
        $data['emploi'] = $emploi;
        $data['ec'] = $ec;
        $data['jours'] = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $data['types'] = ['CM' => 'Cours', 'TD' => 'TD', 'TP' => 'TP'];
        return view('teacher/schedule_edit', $data);
    }

    public function scheduleUpdate($id)
    {
        $post = $this->request->getPost();
        $model = new EmploiModel();
        $emploi = $model->find($id);
        if (!$emploi) {
            return redirect()->to('/teacher/courses')->with('error', 'Cours introuvable.');
        }
        $ecModel = new ECModel();
        $ec = $ecModel->find($emploi['ec_id']);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }
        $data = [
            'salle'       => $post['salle'],
            'jour'        => $post['jour'],
            'heure_debut' => $post['heure_debut'],
            'heure_fin'   => $post['heure_fin'],
            'type_cours'  => $post['type_cours'],
        ];
        if ($model->update($id, $data)) {
            return redirect()->to('/teacher/courses')->with('success', 'Cours mis à jour.');
        }
        return redirect()->back()->with('error', 'Erreur lors de la mise à jour.')->withInput();
    }

    public function scheduleDelete($id)
    {
        $model = new EmploiModel();
        $emploi = $model->find($id);
        if (!$emploi) {
            return redirect()->to('/teacher/courses')->with('error', 'Cours introuvable.');
        }
        $ecModel = new ECModel();
        $ec = $ecModel->find($emploi['ec_id']);
        if (!$ec || $ec['enseignant_id'] != $this->teacherId) {
            return redirect()->to('/teacher/courses')->with('error', 'Accès interdit.');
        }
        if ($model->delete($id)) {
            return redirect()->to('/teacher/courses')->with('success', 'Cours supprimé.');
        }
        return redirect()->back()->with('error', 'Erreur lors de la suppression.');
    }
}