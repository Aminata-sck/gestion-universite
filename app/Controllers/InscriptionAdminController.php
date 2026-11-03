<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\InscriptionAdminModel;
use App\Models\UserModel;
use App\Models\FormationModel;
use App\Models\AnneeUniversitaireModel;

class InscriptionAdminController extends Controller
{
   public function index()
{
    $model = new InscriptionAdminModel();
    $inscriptions = $model
        ->select('inscriptions_administratives.*, 
                  CONCAT(etudiant.nom, " ", etudiant.prenom) as etudiant_nom,
                  formations.libelle as formation_libelle,
                  formations.type as formation_type,
                  formations.annee as formation_annee,
                  formations.id as formation_id,
                  annees_universitaires.libelle as annee_libelle')
        ->join('users as etudiant', 'etudiant.id = inscriptions_administratives.etudiant_id')
        ->join('formations', 'formations.id = inscriptions_administratives.formation_id')
        ->join('annees_universitaires', 'annees_universitaires.id = inscriptions_administratives.annee_universitaire_id')
        ->orderBy('formations.libelle', 'ASC')
        ->findAll();

    $grouped = [];
    foreach ($inscriptions as $ins) {
        $key = $ins['formation_id'] . '|' . $ins['formation_libelle'];
        if (!isset($grouped[$key])) {
            $grouped[$key] = [
                'formation_id'   => $ins['formation_id'],
                'formation_nom'  => $ins['formation_libelle'],
                'formation_type' => $ins['formation_type'],
                'formation_annee'=> $ins['formation_annee'],
                'etudiants'      => []
            ];
        }
        $grouped[$key]['etudiants'][] = $ins;
    }

    $data = [
        'title'   => 'Inscriptions administratives',
        'grouped' => $grouped,
    ];
    return view('inscriptions_admin/index', $data);
}
    public function new()
{
    $userModel = new UserModel();
    $formationModel = new FormationModel();
    $anneeModel = new AnneeUniversitaireModel();

    $data['etudiants'] = $userModel->where('role', 'etudiant')->findAll();
    $data['formations'] = $formationModel
        ->select('id, CONCAT(type, " ", annee, " - ", libelle) as affichage')
        ->findAll();
    $data['annees'] = $anneeModel->findAll();
    return view('inscriptions_admin/create', $data);
}

    public function create()
    {
        $model = new InscriptionAdminModel();
        $data = $this->request->getPost();
        $etudiant_id = $data['etudiant_id'] ?? null;
        $annee_id = $data['annee_universitaire_id'] ?? null;

        // Vérifier si l'étudiant a déjà une inscription (non rejetée) pour cette année
        if ($etudiant_id && $annee_id) {
            $existing = $model
                ->where('etudiant_id', $etudiant_id)
                ->where('annee_universitaire_id', $annee_id)
                ->where('statut !=', 'rejetée')
                ->first();

            if ($existing) {
                return redirect()->back()
                                 ->with('error', 'Cet étudiant est déjà inscrit pour cette année universitaire.')
                                 ->withInput();
            }
        }

        // Si pas de doublon, on enregistre
        if ($model->save($data)) {
            return redirect()->to('/inscriptions-admin')->with('success', 'Inscription administrative créée.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la création.')->withInput();
        }
    }

   public function edit($id)
{
    $model = new InscriptionAdminModel();
    $data['inscription'] = $model->find($id);
    if (!$data['inscription']) {
        return redirect()->to('/inscriptions-admin')->with('error', 'Inscription introuvable.');
    }

    $userModel = new UserModel();
    $formationModel = new FormationModel();
    $anneeModel = new AnneeUniversitaireModel();

    $data['etudiants'] = $userModel->where('role', 'etudiant')->findAll();
    $data['formations'] = $formationModel
        ->select('id, CONCAT(type, " ", annee, " - ", libelle) as affichage')
        ->findAll();
    $data['annees'] = $anneeModel->findAll();
    return view('inscriptions_admin/edit', $data);
}

    public function update($id)
    {
        $model = new InscriptionAdminModel();
        $data = $this->request->getPost();
        $etudiant_id = $data['etudiant_id'] ?? null;
        $annee_id = $data['annee_universitaire_id'] ?? null;

        // Vérifier s'il existe une autre inscription pour le même étudiant/année (différente de celle en cours)
        if ($etudiant_id && $annee_id) {
            $existing = $model
                ->where('etudiant_id', $etudiant_id)
                ->where('annee_universitaire_id', $annee_id)
                ->where('statut !=', 'rejetée')
                ->where('id !=', $id)
                ->first();

            if ($existing) {
                return redirect()->back()
                                 ->with('error', 'Cet étudiant a déjà une inscription pour cette année (autre que celle-ci).')
                                 ->withInput();
            }
        }

        // Mise à jour
        if ($model->update($id, $data)) {
            return redirect()->to('/inscriptions-admin')->with('success', 'Inscription mise à jour.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour.')->withInput();
        }
    }

    public function delete($id)
    {
        $model = new InscriptionAdminModel();
        $model->delete($id);
        return redirect()->to('/inscriptions-admin')->with('success', 'Inscription supprimée.');
    }
    // Affichage du formulaire d'inscription groupée
public function bulkAdd()
{
    $formationModel = new FormationModel();
    $anneeModel = new AnneeUniversitaireModel();
    $userModel = new UserModel();

    $data['formations'] = $formationModel
        ->select('id, CONCAT(type, " ", annee, " - ", libelle) as affichage')
        ->findAll();
    $data['annees'] = $anneeModel->findAll();
    $data['etudiants'] = $userModel->where('role', 'etudiant')->where('statut', 1)->findAll();
    $data['title'] = 'Inscription administrative groupée';
    return view('inscriptions_admin/bulk_add', $data);
}

// Traitement de l'inscription groupée
public function bulkStore()
{
    $post = $this->request->getPost();
    $formationId = $post['formation_id'];
    $anneeId = $post['annee_universitaire_id'];
    $statut = $post['statut'] ?? 'en_attente';
    $dateInscription = $post['date_inscription'] ?? date('Y-m-d');
    $etudiantsIds = $post['etudiants'] ?? [];

    if (empty($etudiantsIds)) {
        return redirect()->back()->with('error', 'Aucun étudiant sélectionné.')->withInput();
    }

    $model = new InscriptionAdminModel();
    $count = 0;
    foreach ($etudiantsIds as $etudiantId) {
        // Vérifier si l'étudiant est déjà inscrit pour cette année (statut != rejetée)
        $existing = $model
            ->where('etudiant_id', $etudiantId)
            ->where('annee_universitaire_id', $anneeId)
            ->where('statut !=', 'rejetée')
            ->first();
        if (!$existing) {
            $model->save([
                'etudiant_id'            => $etudiantId,
                'formation_id'           => $formationId,
                'annee_universitaire_id' => $anneeId,
                'date_inscription'       => $dateInscription,
                'statut'                 => $statut,
            ]);
            $count++;
        }
    }

    return redirect()->to('/inscriptions-admin')->with('success', "$count étudiant(s) inscrit(s) avec succès.");
}
}