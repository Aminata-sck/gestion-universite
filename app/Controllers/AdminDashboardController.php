<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\FormationModel;
use App\Models\InscriptionAdminModel;
use App\Models\InscriptionPedagogiqueModel;
use App\Models\PaiementModel;
use App\Models\AbsenceModel;
use App\Models\AnneeUniversitaireModel;
use App\Models\ECModel;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'scolarite'])) {
            return redirect()->to('/dashboard')->with('error', 'Accès interdit.');
        }
    }

    public function index()
    {
        $userModel = new UserModel();
        $formationModel = new FormationModel();
        $inscriptionAdminModel = new InscriptionAdminModel();
        $inscriptionPedagogiqueModel = new InscriptionPedagogiqueModel();
        $paiementModel = new PaiementModel();
        $absenceModel = new AbsenceModel();
        $anneeModel = new AnneeUniversitaireModel();
        $ecModel = new ECModel();

        // Année active
        $anneeActive = $anneeModel->where('active', 1)->first();
        $anneeId = $anneeActive ? $anneeActive['id'] : null;

        // Statistiques globales
        $data['total_etudiants'] = $userModel->where('role', 'etudiant')->countAllResults();
        $data['total_enseignants'] = $userModel->where('role', 'enseignant')->countAllResults();
        $data['total_formations'] = $formationModel->where('archive', 0)->countAllResults();
        $data['total_ec'] = $ecModel->countAllResults();
        $data['total_paiements'] = $paiementModel->countAllResults();

        // Statistiques pour l'année active
        if ($anneeId) {
            $data['inscriptions_annee'] = $inscriptionAdminModel
                ->where('annee_universitaire_id', $anneeId)
                ->where('statut !=', 'rejetée')
                ->countAllResults();
            $data['absences_annee'] = $absenceModel->countAllResults();
            $data['paiements_annee'] = $paiementModel->countAllResults();
        } else {
            $data['inscriptions_annee'] = 0;
            $data['absences_annee'] = 0;
            $data['paiements_annee'] = 0;
        }

        // Dernières inscriptions
        $data['dernieres_inscriptions'] = $inscriptionAdminModel
            ->select('inscriptions_administratives.*, CONCAT(users.nom, " ", users.prenom) as etudiant_nom, formations.libelle as formation_libelle')
            ->join('users', 'users.id = inscriptions_administratives.etudiant_id')
            ->join('formations', 'formations.id = inscriptions_administratives.formation_id')
            ->orderBy('inscriptions_administratives.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        $data['title'] = 'Tableau de bord administrateur';
        return view('admin/dashboard', $data);
    }
}