<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PaiementModel;
use App\Models\UserModel;
use App\Models\FormationModel;
use App\Models\AnneeUniversitaireModel;
use App\Models\InscriptionAdminModel;

class PaiementController extends Controller
{
    protected $model;
    protected $userModel;

    public function __construct()
    {
        $this->model = new PaiementModel();
        $this->userModel = new UserModel();
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'scolarite'])) {
            return redirect()->to('/dashboard')->with('error', 'Accès interdit.');
        }
    }

    // --- Génération d'une référence unique ---
    private function generateReference()
    {
        $annee = date('Y');
        $count = $this->model->where('YEAR(date_paiement)', $annee)->countAllResults();
        $numero = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        return 'PAY-' . $annee . '-' . $numero;
    }

    // --------------------------------------------------------------
    // INDEX : regroupement des paiements par formation
    // --------------------------------------------------------------
    public function index()
    {
        $paiements = $this->model
            ->select('paiements.*, 
                      CONCAT(users.nom, " ", users.prenom) as etudiant_nom,
                      formations.libelle as formation_libelle,
                      formations.type as formation_type,
                      formations.id as formation_id')
            ->join('users', 'users.id = paiements.etudiant_id')
            ->join('inscriptions_administratives', 'inscriptions_administratives.etudiant_id = users.id AND inscriptions_administratives.statut = "validée"', 'left')
            ->join('formations', 'formations.id = inscriptions_administratives.formation_id', 'left')
            ->orderBy('formations.libelle', 'ASC')
            ->findAll();

        $grouped = [];
        foreach ($paiements as $p) {
            $key = $p['formation_id'] . '|' . ($p['formation_libelle'] ?? 'Sans formation');
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'formation_id'   => $p['formation_id'],
                    'formation_nom'  => $p['formation_libelle'] ?? 'Sans formation',
                    'formation_type' => $p['formation_type'] ?? '',
                    'paiements'      => []
                ];
            }
            $grouped[$key]['paiements'][] = $p;
        }

        $data = [
            'title'   => 'Paiements',
            'grouped' => $grouped,
        ];
        return view('paiements/index', $data);
    }

    // --------------------------------------------------------------
    // AJOUT SIMPLE
    // --------------------------------------------------------------
    public function new()
    {
        $data['etudiants'] = $this->userModel->where('role', 'etudiant')->findAll();
        $data['reference'] = $this->generateReference();
        return view('paiements/create', $data);
    }

    public function create()
    {
        $data = $this->request->getPost();
        if (empty($data['reference'])) {
            $data['reference'] = $this->generateReference();
        }
        // Générer la description à partir du champ 'periode'
        if (!empty($data['periode'])) {
            $mois = [
                'inscription' => 'Frais d\'inscription',
                'octobre'     => 'Octobre',
                'novembre'    => 'Novembre',
                'decembre'    => 'Décembre',
                'janvier'     => 'Janvier',
                'fevrier'     => 'Février',
                'mars'        => 'Mars',
                'avril'       => 'Avril',
                'mai'         => 'Mai',
                'juin'        => 'Juin',
                'juillet'     => 'Juillet',
            ];
            $label = $mois[$data['periode']] ?? $data['periode'];
            $data['description'] = $label . ' ' . date('Y');
        } else {
            $data['description'] = $data['description'] ?? null;
        }

        if ($this->model->save($data)) {
            return redirect()->to('/paiements')->with('success', 'Paiement enregistré.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement.')->withInput();
        }
    }

    // --------------------------------------------------------------
    // MODIFICATION
    // --------------------------------------------------------------
    public function edit($id)
    {
        $data['paiement'] = $this->model->find($id);
        if (!$data['paiement']) {
            return redirect()->to('/paiements')->with('error', 'Paiement introuvable.');
        }
        $data['etudiants'] = $this->userModel->where('role', 'etudiant')->findAll();
        return view('paiements/edit', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        if (empty($data['reference'])) {
            $data['reference'] = $this->generateReference();
        }
        if ($this->model->update($id, $data)) {
            return redirect()->to('/paiements')->with('success', 'Paiement mis à jour.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour.')->withInput();
        }
    }

    // --------------------------------------------------------------
    // SUPPRESSION
    // --------------------------------------------------------------
    public function delete($id)
    {
        if ($this->model->delete($id)) {
            return redirect()->to('/paiements')->with('success', 'Paiement supprimé.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    // --------------------------------------------------------------
    // SAISIE PROGRESSIVE (formulaire principal)
    // --------------------------------------------------------------
    public function bulkAdd()
    {
        $userModel = new UserModel();
        $anneeModel = new AnneeUniversitaireModel();

        $data['etudiants'] = $userModel->where('role', 'etudiant')->where('statut', 1)->findAll();
        $data['annees'] = $anneeModel->findAll();
        $data['mois'] = [
            'inscription' => 'Frais d\'inscription',
            'octobre'     => 'Octobre',
            'novembre'    => 'Novembre',
            'decembre'    => 'Décembre',
            'janvier'     => 'Janvier',
            'fevrier'     => 'Février',
            'mars'        => 'Mars',
            'avril'       => 'Avril',
            'mai'         => 'Mai',
            'juin'        => 'Juin',
            'juillet'     => 'Juillet',
        ];
        return view('paiements/bulk_add', $data);
    }

    // --------------------------------------------------------------
    // TRAITEMENT DU PAIEMENT GROUPÉ (formulaire POST)
    // --------------------------------------------------------------
    public function bulkStore()
    {
        $post = $this->request->getPost();
        $etudiantId = $post['etudiant_id'];
        $anneeId = $post['annee_universitaire_id'];
        $periode = $post['periode'] ?? null;
        $montant = $post['montant'];
        $mode = $post['mode'] ?? 'virement';
        $statut = $post['statut'] ?? 'payé';
        $echeance = $post['echeance'] ?? null;

        if (empty($etudiantId) || empty($anneeId) || empty($periode) || empty($montant)) {
            return redirect()->back()->with('error', 'Tous les champs sont requis.')->withInput();
        }

        // Vérifier l'inscription
        $inscriptionAdminModel = new InscriptionAdminModel();
        $inscrit = $inscriptionAdminModel
            ->where('etudiant_id', $etudiantId)
            ->where('annee_universitaire_id', $anneeId)
            ->where('statut', 'validée')
            ->first();

        if (!$inscrit) {
            return redirect()->back()->with('error', 'Cet étudiant n\'est pas inscrit pour cette année.')->withInput();
        }

        // Générer la description
        $mois = [
            'inscription' => 'Frais d\'inscription',
            'octobre'     => 'Octobre',
            'novembre'    => 'Novembre',
            'decembre'    => 'Décembre',
            'janvier'     => 'Janvier',
            'fevrier'     => 'Février',
            'mars'        => 'Mars',
            'avril'       => 'Avril',
            'mai'         => 'Mai',
            'juin'        => 'Juin',
            'juillet'     => 'Juillet',
        ];
        $label = $mois[$periode] ?? $periode;
        $description = $label . ' ' . date('Y');

        $reference = $this->generateReference() . '-' . strtoupper(substr($periode, 0, 3));

        $data = [
            'etudiant_id'    => $etudiantId,
            'montant'        => $montant,
            'date_paiement'  => date('Y-m-d'),
            'mode'           => $mode,
            'reference'      => $reference,
            'statut'         => $statut,
            'echeance'       => $echeance,
            'description'    => $description,
        ];

        if ($this->model->save($data)) {
            return redirect()->to('/paiements')->with('success', 'Paiement enregistré avec succès.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement.')->withInput();
        }
    }

    // --------------------------------------------------------------
    // AJAX : récupère les paiements d'un étudiant pour une année
    // --------------------------------------------------------------
    public function getPaiementsByEtudiant($etudiantId, $anneeId)
    {
        $anneeModel = new AnneeUniversitaireModel();
        $annee = $anneeModel->find($anneeId);
        $anneeDebut = $annee ? date('Y', strtotime($annee['date_debut'])) : date('Y');

        $paiements = $this->model
            ->where('etudiant_id', $etudiantId)
            ->where('YEAR(date_paiement)', $anneeDebut)
            ->findAll();

        return $this->response->setJSON($paiements);
    }

    // --------------------------------------------------------------
    // AJAX : ajoute un paiement progressif (sans recharger la page)
    // --------------------------------------------------------------
    public function ajouterPaiementProgressif()
    {
        $post = $this->request->getPost();
        $etudiantId = $post['etudiant_id'];
        $anneeId = $post['annee_universitaire_id'];
        $periode = $post['periode'];
        $montant = $post['montant'];
        $mode = $post['mode'] ?? 'virement';
        $statut = $post['statut'] ?? 'payé';
        $echeance = $post['echeance'] ?? null;

        if (empty($etudiantId) || empty($anneeId) || empty($periode) || empty($montant)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tous les champs sont requis.']);
        }

        $inscriptionAdminModel = new InscriptionAdminModel();
        $inscrit = $inscriptionAdminModel
            ->where('etudiant_id', $etudiantId)
            ->where('annee_universitaire_id', $anneeId)
            ->where('statut', 'validée')
            ->first();

        if (!$inscrit) {
            return $this->response->setJSON(['success' => false, 'message' => 'Étudiant non inscrit pour cette année.']);
        }

        $reference = $this->generateReference() . '-' . strtoupper(substr($periode, 0, 3));
        $mois = [
            'inscription' => 'Frais d\'inscription',
            'octobre'     => 'Octobre',
            'novembre'    => 'Novembre',
            'decembre'    => 'Décembre',
            'janvier'     => 'Janvier',
            'fevrier'     => 'Février',
            'mars'        => 'Mars',
            'avril'       => 'Avril',
            'mai'         => 'Mai',
            'juin'        => 'Juin',
            'juillet'     => 'Juillet',
        ];
        $label = $mois[$periode] ?? $periode;
        $description = $label . ' ' . date('Y');

        $data = [
            'etudiant_id'    => $etudiantId,
            'montant'        => $montant,
            'date_paiement'  => date('Y-m-d'),
            'mode'           => $mode,
            'reference'      => $reference,
            'statut'         => $statut,
            'echeance'       => $echeance,
            'description'    => $description,
        ];

        if ($this->model->save($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Paiement ajouté avec succès.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors de l\'enregistrement.']);
        }
    }

    // --------------------------------------------------------------
    // AJAX : récupère les étudiants d'une formation pour l'année donnée
    // --------------------------------------------------------------
    public function getEtudiantsByFormation($formationId, $anneeId)
    {
        $inscriptionAdminModel = new InscriptionAdminModel();
        $etudiants = $inscriptionAdminModel
            ->select('users.id, users.nom, users.prenom, users.email')
            ->join('users', 'users.id = inscriptions_administratives.etudiant_id')
            ->where('formation_id', $formationId)
            ->where('annee_universitaire_id', $anneeId)
            ->where('statut', 'validée')
            ->findAll();

        return $this->response->setJSON($etudiants);
    }
}