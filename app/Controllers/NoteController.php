<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\NoteModel;
use App\Models\UserModel;
use App\Models\ECModel;
use App\Models\InscriptionPedagogiqueModel;

class NoteController extends Controller
{
    // --------------------------------------------------------------
    // INDEX : affichage des notes regroupées par étudiant
    // --------------------------------------------------------------
    public function index()
    {
        $model = new NoteModel();
        // Récupérer toutes les notes avec les infos liées
        $notes = $model
            ->select('notes.*, 
                      CONCAT(etudiant.nom, " ", etudiant.prenom) as etudiant_nom,
                      ecs.libelle as ec_libelle,
                      CONCAT(enseignant.nom, " ", enseignant.prenom) as enseignant_nom')
            ->join('users as etudiant', 'etudiant.id = notes.etudiant_id')
            ->join('ecs', 'ecs.id = notes.ec_id')
            ->join('users as enseignant', 'enseignant.id = notes.enseignant_id')
            ->orderBy('etudiant_nom', 'ASC')
            ->orderBy('ecs.libelle', 'ASC')
            ->findAll();

        // Regrouper par étudiant (pour éviter les répétitions)
        $grouped = [];
        foreach ($notes as $n) {
            $key = $n['etudiant_id'] . '|' . $n['etudiant_nom'];
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'etudiant_id'   => $n['etudiant_id'],
                    'etudiant_nom'  => $n['etudiant_nom'],
                    'notes'         => []
                ];
            }
            $grouped[$key]['notes'][] = $n;
        }

        $data = [
            'title'   => 'Notes',
            'grouped' => $grouped,
        ];
        return view('notes/index', $data);
    }

    // --------------------------------------------------------------
    // AJOUT SIMPLE d'une note (formulaire)
    // --------------------------------------------------------------
    public function new()
    {
        $userModel = new UserModel();
        $ecModel = new ECModel();

        $ec_id = $this->request->getGet('ec_id');
        $coefficient_default = 1;
        $type_default = 'examen';
        if ($ec_id) {
            $ec = $ecModel->find($ec_id);
            if ($ec) {
                $coefficient_default = $ec['coefficient'] ?? 1;
                $type_default = $ec['type'] ?? 'examen';
            }
        }

        $data['etudiants'] = $userModel->where('role', 'etudiant')->findAll();
        $data['ecs'] = $ecModel->findAll();
        $data['enseignants'] = $userModel->where('role', 'enseignant')->findAll();
        $data['coefficient_default'] = $coefficient_default;
        $data['type_default'] = $type_default;
        $data['selected_ec'] = $ec_id;
        $data['title'] = 'Saisir une note';
        return view('notes/create', $data);
    }

    public function create()
    {
        $model = new NoteModel();
        $data = $this->request->getPost();
        $data['date_saisie'] = date('Y-m-d H:i:s');
        // Vérifier que l'enseignant_id est bien présent
        if (empty($data['enseignant_id'])) {
            $ecModel = new ECModel();
            $ec = $ecModel->find($data['ec_id']);
            $data['enseignant_id'] = $ec['enseignant_id'] ?? null;
        }
        if ($model->save($data)) {
            return redirect()->to('/notes')->with('success', 'Note saisie.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la saisie.')->withInput();
        }
    }

    // --------------------------------------------------------------
    // AJOUT GROUPÉ de notes (pour un EC donné)
    // --------------------------------------------------------------
    // Dans NoteController.php, méthode bulkAdd()
public function bulkAdd()
{
    $ecId = $this->request->getGet('ec_id');
    $ecModel = new ECModel();
    $ec = $ecModel->find($ecId);
    if (!$ec) {
        return redirect()->to('/notes/bulk-select')->with('error', 'EC introuvable.');
    }

    // Récupérer les étudiants inscrits à cet EC (validés)
    $inscModel = new InscriptionPedagogiqueModel();
    $etudiants = $inscModel
        ->select('users.id as user_id, users.nom, users.prenom, users.email')
        ->join('users', 'users.id = inscriptions_pedagogiques.etudiant_id')
        ->where('ec_id', $ecId)
        ->where('inscriptions_pedagogiques.statut', 'validée') // ← PRÉCISION ICI
        ->findAll();

    $data = [
        'title'      => 'Saisie groupée - ' . $ec['libelle'],
        'ec'         => $ec,
        'etudiants'  => $etudiants,
        'types'      => ['cc' => 'Contrôle continu', 'examen' => 'Examen', 'rattrapage' => 'Rattrapage'],
    ];
    return view('notes/bulk_add', $data);
}

    public function bulkStore()
    {
        $post = $this->request->getPost();
        $ecId = $post['ec_id'];
        $type = $post['type'];
        $coefficient = $post['coefficient'] ?? 1;
        $session = $post['session'] ?? null;
        $notesData = $post['notes'] ?? [];

        $noteModel = new NoteModel();
        $ecModel = new ECModel();
        $ec = $ecModel->find($ecId);
        if (!$ec) {
            return redirect()->back()->with('error', 'EC introuvable.');
        }

        $count = 0;
        foreach ($notesData as $etudiantId => $note) {
            if ($note === '' || $note === null) continue;
            // Vérifier si une note existe déjà pour cet étudiant/EC/type
            $existing = $noteModel
                ->where('etudiant_id', $etudiantId)
                ->where('ec_id', $ecId)
                ->where('type', $type)
                ->first();
            if ($existing) {
                $noteModel->update($existing['id'], [
                    'note'        => $note,
                    'coefficient' => $coefficient,
                    'session'     => $session,
                    'date_saisie' => date('Y-m-d H:i:s'),
                ]);
            } else {
                $noteModel->save([
                    'etudiant_id'   => $etudiantId,
                    'ec_id'         => $ecId,
                    'type'          => $type,
                    'note'          => $note,
                    'coefficient'   => $coefficient,
                    'enseignant_id' => $ec['enseignant_id'] ?? null,
                    'date_saisie'   => date('Y-m-d H:i:s'),
                    'session'       => $session,
                ]);
            }
            $count++;
        }

        return redirect()->to('/notes')->with('success', "$count note(s) enregistrée(s) avec succès.");
    }

    // --------------------------------------------------------------
    // MODIFICATION d'une note
    // --------------------------------------------------------------
    public function edit($id)
    {
        $model = new NoteModel();
        $data['note'] = $model->find($id);
        if (!$data['note']) {
            return redirect()->to('/notes')->with('error', 'Note introuvable.');
        }
        $userModel = new UserModel();
        $ecModel = new ECModel();
        $data['etudiants'] = $userModel->where('role', 'etudiant')->findAll();
        $data['ecs'] = $ecModel->findAll();
        $data['enseignants'] = $userModel->where('role', 'enseignant')->findAll();
        $data['title'] = 'Modifier une note';
        return view('notes/edit', $data);
    }

    public function update($id)
    {
        $model = new NoteModel();
        $data = $this->request->getPost();
        // Ne pas modifier l'enseignant_id si déjà présent ? On le laisse tel quel.
        if ($model->update($id, $data)) {
            return redirect()->to('/notes')->with('success', 'Note mise à jour.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour.')->withInput();
        }
    }

    // --------------------------------------------------------------
    // SUPPRESSION d'une note
    // --------------------------------------------------------------
    public function delete($id)
    {
        $model = new NoteModel();
        $model->delete($id);
        return redirect()->to('/notes')->with('success', 'Note supprimée.');
    }

    // --------------------------------------------------------------
    // AJAX : récupère les EC d'un étudiant (pour formulaire simple)
    // --------------------------------------------------------------
    public function getEcsByStudent($studentId)
    {
        $inscModel = new InscriptionPedagogiqueModel();
        $ecs = $inscModel
            ->select('ecs.id, ecs.libelle, ecs.enseignant_id, CONCAT(users.nom, " ", users.prenom) as enseignant_nom')
            ->join('ecs', 'ecs.id = inscriptions_pedagogiques.ec_id')
            ->join('users', 'users.id = ecs.enseignant_id', 'left')
            ->where('etudiant_id', $studentId)
            ->where('inscriptions_pedagogiques.statut', 'validée')
            ->findAll();

        if (empty($ecs)) {
            return $this->response->setJSON([]);
        }
        foreach ($ecs as &$ec) {
            if (empty($ec['enseignant_nom'])) {
                $ec['enseignant_nom'] = 'Non affecté';
            }
        }
        return $this->response->setJSON($ecs);
    }
    // Sélection d'un EC pour saisie groupée
public function bulkSelect()
{
    $ecModel = new ECModel();
    $ecs = $ecModel->select('ecs.*, ues.libelle as ue_libelle')
        ->join('ues', 'ues.id = ecs.ue_id')
        ->findAll();
    $data = [
        'title' => 'Choisir un EC pour saisie groupée',
        'ecs'   => $ecs,
    ];
    return view('notes/bulk_select', $data);
}
}