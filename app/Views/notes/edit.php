<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Modifier une note</h2>
<form method="post" action="/notes/update/<?= $note['id'] ?>">
    <?= csrf_field() ?>

    <!-- Étudiant -->
    <div class="mb-3">
        <label>Étudiant</label>
        <select name="etudiant_id" id="etudiant_id" class="form-control" required>
            <option value="">-- Choisir un étudiant --</option>
            <?php foreach ($etudiants as $e): ?>
                <option value="<?= $e['id'] ?>" <?= $e['id'] == $note['etudiant_id'] ? 'selected' : '' ?>>
                    <?= esc($e['nom'] . ' ' . $e['prenom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- EC (dynamique) -->
    <div class="mb-3">
        <label>EC</label>
        <select name="ec_id" id="ec_id" class="form-control" required>
            <option value="">-- Chargement en cours --</option>
        </select>
    </div>

    <!-- Enseignant (lecture seule) -->
    <div class="mb-3">
        <label>Enseignant</label>
        <input type="text" id="enseignant_nom" class="form-control" readonly>
        <input type="hidden" name="enseignant_id" id="enseignant_id" value="<?= $note['enseignant_id'] ?? '' ?>">
    </div>

    <!-- Autres champs -->
    <div class="mb-3">
        <label>Type</label>
        <select name="type" class="form-control">
            <option value="cc" <?= $note['type'] == 'cc' ? 'selected' : '' ?>>Contrôle continu</option>
            <option value="examen" <?= $note['type'] == 'examen' ? 'selected' : '' ?>>Examen</option>
            <option value="rattrapage" <?= $note['type'] == 'rattrapage' ? 'selected' : '' ?>>Rattrapage</option>
        </select>
    </div>
    <div class="mb-3"><label>Note</label><input type="number" step="0.01" name="note" class="form-control" value="<?= $note['note'] ?>" required></div>
    <div class="mb-3"><label>Coefficient</label><input type="number" step="0.1" name="coefficient" class="form-control" value="<?= $note['coefficient'] ?>"></div>
    <div class="mb-3"><label>Session</label><input type="text" name="session" class="form-control" value="<?= esc($note['session']) ?>"></div>

    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/notes" class="btn btn-secondary">Annuler</a>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const etudiantSelect = document.getElementById('etudiant_id');
    const ecSelect = document.getElementById('ec_id');
    const enseignantNom = document.getElementById('enseignant_nom');
    const enseignantId = document.getElementById('enseignant_id');

    function loadECs(studentId, selectedEcId = null) {
        if (!studentId) {
            ecSelect.innerHTML = '<option value="">-- Sélectionner un étudiant --</option>';
            enseignantNom.value = '';
            enseignantId.value = '';
            return;
        }

        ecSelect.innerHTML = '<option value="">Chargement...</option>';

        fetch('/notes/get-ecs/' + studentId)
            .then(response => response.json())
            .then(data => {
                ecSelect.innerHTML = '';
                if (data.length === 0) {
                    ecSelect.innerHTML = '<option value="">Aucun EC trouvé (vérifier les inscriptions pédagogiques)</option>';
                    enseignantNom.value = '';
                    enseignantId.value = '';
                    return;
                }
                data.forEach(ec => {
                    const option = document.createElement('option');
                    option.value = ec.id;
                    option.textContent = ec.libelle;
                    option.dataset.enseignantId = ec.enseignant_id || '';
                    option.dataset.enseignantNom = ec.enseignant_nom || 'Non affecté';
                    if (selectedEcId && ec.id == selectedEcId) {
                        option.selected = true;
                    }
                    ecSelect.appendChild(option);
                });
                // Mettre à jour l'enseignant pour l'EC sélectionné
                const selectedOption = ecSelect.options[ecSelect.selectedIndex];
                if (selectedOption) {
                    enseignantNom.value = selectedOption.dataset.enseignantNom || '';
                    enseignantId.value = selectedOption.dataset.enseignantId || '';
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                ecSelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    }

    // Gestion du changement d'étudiant
    etudiantSelect.addEventListener('change', function() {
        const studentId = this.value;
        loadECs(studentId);
    });

    // Mise à jour de l'enseignant quand l'EC change
    ecSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption) {
            enseignantNom.value = selectedOption.dataset.enseignantNom || '';
            enseignantId.value = selectedOption.dataset.enseignantId || '';
        }
    });

    // Chargement initial pour l'édition (si un étudiant est pré-sélectionné)
    const initialStudentId = etudiantSelect.value;
    const initialEcId = <?= isset($note['ec_id']) ? $note['ec_id'] : 'null' ?>;
    if (initialStudentId) {
        loadECs(initialStudentId, initialEcId);
    }
});
</script>
<?= $this->endSection() ?>