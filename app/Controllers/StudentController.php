<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\InscriptionAdminModel;
use App\Models\FormationModel;
use App\Models\AnneeUniversitaireModel;
use App\Models\InscriptionPedagogiqueModel;
use App\Models\ECModel;
use App\Models\NoteModel;
use App\Models\EmploiModel;
use App\Models\AbsenceModel;

class StudentController extends Controller
{
    protected $userId;

    public function __construct()
    {
        // Vérifier que l'utilisateur est connecté et a le rôle 'etudiant'
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'etudiant') {
            return redirect()->to('/login')->with('error', 'Accès réservé aux étudiants.');
        }
        $this->userId = session()->get('user_id');
    }

    public function dashboard()
    {
        $userModel = new UserModel();
        $user = $userModel->find($this->userId);

        // Récupérer l'inscription administrative en cours (formation, année)
        $inscriptionModel = new InscriptionAdminModel();
        $inscription = $inscriptionModel
            ->select('inscriptions_administratives.*, formations.libelle as formation_libelle, annees_universitaires.libelle as annee_libelle')
            ->join('formations', 'formations.id = inscriptions_administratives.formation_id')
            ->join('annees_universitaires', 'annees_universitaires.id = inscriptions_administratives.annee_universitaire_id')
            ->where('etudiant_id', $this->userId)
            ->where('statut', 'validée')
            ->orderBy('inscriptions_administratives.id', 'DESC')
            ->first();

        // Récupérer les dernières notes (5 dernières)
        $noteModel = new NoteModel();
        $notes = $noteModel
            ->select('notes.*, ecs.libelle as ec_libelle')
            ->join('ecs', 'ecs.id = notes.ec_id')
            ->where('etudiant_id', $this->userId)
            ->orderBy('notes.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // Récupérer l'emploi du temps de l'étudiant (cours pour ses EC)
        $ecModel = new ECModel();
        $emploiModel = new EmploiModel();
        // Récupérer les EC de l'étudiant via ses inscriptions pédagogiques validées
        $ecIds = $this->getStudentECIds();
        if (!empty($ecIds)) {
            $emplois = $emploiModel
                ->select('emplois_du_temps.*, ecs.libelle as ec_libelle')
                ->join('ecs', 'ecs.id = emplois_du_temps.ec_id')
                ->whereIn('ecs.id', $ecIds)
                ->orderBy('emplois_du_temps.jour', 'ASC')
                ->orderBy('emplois_du_temps.heure_debut', 'ASC')
                ->findAll();
        } else {
            $emplois = [];
        }

        $data = [
            'title'       => 'Tableau de bord étudiant',
            'user'        => $user,
            'inscription' => $inscription,
            'notes'       => $notes,
            'emplois'     => $emplois,
        ];
        return view('student/dashboard', $data);
    }

    // Afficher toutes les notes (relevé complet)
   // Afficher toutes les notes (relevé complet)
public function grades()
{
    $noteModel = new NoteModel();
    $userId = $this->userId;

    // Récupérer toutes les notes de l'étudiant avec les infos EC et UE
    $notes = $noteModel
        ->select('notes.*, ecs.libelle as ec_libelle, ecs.coefficient as ec_coeff, ues.libelle as ue_libelle, ues.id as ue_id, ues.semestre, ues.credits_ects, ues.coefficient as ue_coeff')
        ->join('ecs', 'ecs.id = notes.ec_id')
        ->join('ues', 'ues.id = ecs.ue_id')
        ->where('notes.etudiant_id', $userId)
        ->orderBy('ues.semestre', 'ASC')
        ->orderBy('ues.libelle', 'ASC')
        ->findAll();

    // Grouper par UE
    $ues = [];
    foreach ($notes as $note) {
        $ueId = $note['ue_id'];
        if (!isset($ues[$ueId])) {
            $ues[$ueId] = [
                'id'          => $ueId,
                'libelle'     => $note['ue_libelle'],
                'semestre'    => $note['semestre'],
                'credits'     => $note['credits_ects'] ?? 0,
                'ue_coeff'    => $note['ue_coeff'] ?? 1,
                'notes'       => [],
                'total_coeff' => 0,
                'total_note'  => 0,
            ];
        }
        $ues[$ueId]['notes'][] = $note;
        $ues[$ueId]['total_coeff'] += $note['ec_coeff'] ?? 1;
        $ues[$ueId]['total_note'] += ($note['note'] ?? 0) * ($note['ec_coeff'] ?? 1);
    }

    // Calculer la moyenne de chaque UE et les crédits acquis
    $semestres = [];
    foreach ($ues as &$ue) {
        $ue['moyenne'] = $ue['total_coeff'] > 0 ? round($ue['total_note'] / $ue['total_coeff'], 2) : null;
        $ue['valide'] = $ue['moyenne'] !== null && $ue['moyenne'] >= 10;
        $ue['credits_acquis'] = $ue['valide'] ? $ue['credits'] : 0;
        $semestre = $ue['semestre'];
        if (!isset($semestres[$semestre])) {
            $semestres[$semestre] = [
                'ues' => [],
                'total_ue_coeff' => 0,
                'total_ue_note'  => 0,
                'credits_obtenus' => 0,
            ];
        }
        $semestres[$semestre]['ues'][] = &$ue;
        $semestres[$semestre]['total_ue_coeff'] += $ue['ue_coeff'];
        $semestres[$semestre]['total_ue_note'] += $ue['moyenne'] !== null ? $ue['moyenne'] * $ue['ue_coeff'] : 0;
        $semestres[$semestre]['credits_obtenus'] += $ue['credits_acquis'];
    }

    // Calcul de la moyenne générale par semestre
    foreach ($semestres as &$sem) {
        $sem['moyenne_generale'] = $sem['total_ue_coeff'] > 0 ? round($sem['total_ue_note'] / $sem['total_ue_coeff'], 2) : 0;
        // Mention
        $sem['mention'] = $this->getMention($sem['moyenne_generale']);
    }

    // Total général des crédits
    $total_credits = array_sum(array_column($semestres, 'credits_obtenus'));

    $data = [
        'title'         => 'Mon relevé de notes',
        'ues'           => $ues, // pour affichage détaillé si besoin
        'semestres'     => $semestres,
        'total_credits' => $total_credits,
        'notes'         => $notes, // pour affichage détaillé si besoin
    ];
    return view('student/grades', $data);
}

// Méthode pour déterminer la mention en fonction de la moyenne
private function getMention($moyenne)
{
    if ($moyenne >= 16) return 'Très bien';
    if ($moyenne >= 14) return 'Bien';
    if ($moyenne >= 12) return 'Assez bien';
    if ($moyenne >= 10) return 'Passable';
    return 'Non validé';
}

    // Afficher l'emploi du temps complet
    public function schedule()
{
    $ecIds = $this->getStudentECIds();
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
    return view('student/schedule', $data);
}

    // Afficher les absences
    public function absences()
    {
        $absenceModel = new AbsenceModel();
        $absences = $absenceModel
            ->select('absences.*, emplois_du_temps.salle, emplois_du_temps.jour, emplois_du_temps.heure_debut, emplois_du_temps.heure_fin, ecs.libelle as ec_libelle')
            ->join('emplois_du_temps', 'emplois_du_temps.id = absences.emploi_id')
            ->join('ecs', 'ecs.id = emplois_du_temps.ec_id')
            ->where('etudiant_id', $this->userId)
            ->orderBy('absences.date_absence', 'DESC')
            ->findAll();

        $data = [
            'title'    => 'Mes absences',
            'absences' => $absences,
        ];
        return view('student/absences', $data);
    }

    // Helper : récupérer les ID des EC de l'étudiant (via inscriptions pédagogiques validées)
    private function getStudentECIds()
    {
        $inscPedagogiqueModel = new InscriptionPedagogiqueModel();
        $inscriptions = $inscPedagogiqueModel
            ->where('etudiant_id', $this->userId)
            ->where('statut', 'validée')
            ->findAll();
        return array_column($inscriptions, 'ec_id');
    }
    // Afficher l'historique des paiements de l'étudiant
public function payments()
{
    $paiementModel = new \App\Models\PaiementModel();
    $userId = $this->userId;

    // Récupérer tous les paiements de l'étudiant
    $paiements = $paiementModel
        ->where('etudiant_id', $userId)
        ->orderBy('date_paiement', 'DESC')
        ->findAll();

    // Statistiques
    $totalPaye = 0;
    $totalEnAttente = 0;
    $totalAnnule = 0;

    foreach ($paiements as $p) {
        if ($p['statut'] === 'payé') {
            $totalPaye += $p['montant'];
        } elseif ($p['statut'] === 'en_attente') {
            $totalEnAttente += $p['montant'];
        } elseif ($p['statut'] === 'annulé') {
            $totalAnnule += $p['montant'];
        }
    }

    $data = [
        'title'          => 'Mes paiements',
        'paiements'      => $paiements,
        'totalPaye'      => $totalPaye,
        'totalEnAttente' => $totalEnAttente,
        'totalAnnule'    => $totalAnnule,
    ];
    return view('student/payments', $data);
}
}