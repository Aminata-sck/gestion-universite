<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fa-regular fa-list-check me-2"></i> Ajouter une inscription pédagogique</h1>
    <a href="/inscriptions-pedagogiques" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/inscriptions-pedagogiques" id="pedagogiqueForm">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">Étudiant</label>
                <select name="etudiant_id" class="form-select" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($etudiants as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= esc($e['nom'] . ' ' . $e['prenom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">EC (Élément Constitutif)</label>
                <select name="ec_id" class="form-select" required>
    <option value="">-- Sélectionner --</option>
    <?php foreach ($ecs as $ec): ?>
        <option value="<?= $ec['id'] ?>">
            <?= esc($ec['formation_type'] . ' ' . $ec['formation_annee'] . ' - ' . $ec['formation_libelle'] . ' - ' . $ec['ue_libelle'] . ' - ' . $ec['libelle']) ?>
        </option>
    <?php endforeach; ?>
</select>
                <small class="text-muted">Sélectionnez l'EC (cycle + formation + UE + libellé).</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Semestre</label>
                <input type="number" name="semestre" class="form-control" min="1" max="2" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Statut</label>
                <select name="statut" class="form-select">
                    <option value="en_attente">En attente</option>
                    <option value="validée">Validée</option>
                    <option value="refusée">Refusée</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Date d'inscription</label>
                <input type="date" name="date_inscription" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <!-- Récapitulatif dynamique -->
            <div class="mt-4 p-3 bg-light rounded-3">
                <h6 class="fw-semibold">📋 Récapitulatif</h6>
                <div id="recap">
                    <p class="mb-1"><span class="text-muted">Étudiant :</span> <span id="recapEtudiant" class="fw-semibold">Non sélectionné</span></p>
                    <p class="mb-1"><span class="text-muted">EC :</span> <span id="recapEc" class="fw-semibold">Non sélectionné</span></p>
                    <p class="mb-1"><span class="text-muted">Semestre :</span> <span id="recapSemestre" class="fw-semibold">-</span></p>
                    <p class="mb-1"><span class="text-muted">Statut :</span> <span id="recapStatut" class="fw-semibold">en_attente</span></p>
                    <p class="mb-0"><span class="text-muted">Date :</span> <span id="recapDate" class="fw-semibold"><?= date('d/m/Y') ?></span></p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3"><i class="fa-regular fa-floppy-disk me-2"></i>Enregistrer</button>
            <a href="/inscriptions-pedagogiques" class="btn btn-secondary mt-3">Annuler</a>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pedagogiqueForm');
    const etudiantSelect = form.querySelector('select[name="etudiant_id"]');
    const ecSelect = form.querySelector('select[name="ec_id"]');
    const semestreInput = form.querySelector('input[name="semestre"]');
    const statutSelect = form.querySelector('select[name="statut"]');
    const dateInput = form.querySelector('input[name="date_inscription"]');

    const recapEtudiant = document.getElementById('recapEtudiant');
    const recapEc = document.getElementById('recapEc');
    const recapSemestre = document.getElementById('recapSemestre');
    const recapStatut = document.getElementById('recapStatut');
    const recapDate = document.getElementById('recapDate');

    function updateRecap() {
        const selectedEtudiant = etudiantSelect.options[etudiantSelect.selectedIndex];
        recapEtudiant.textContent = selectedEtudiant ? selectedEtudiant.text : 'Non sélectionné';
        const selectedEc = ecSelect.options[ecSelect.selectedIndex];
        recapEc.textContent = selectedEc ? selectedEc.text : 'Non sélectionné';
        recapSemestre.textContent = semestreInput.value || '-';
        recapStatut.textContent = statutSelect.value;
        recapDate.textContent = dateInput.value ? dateInput.value.split('-').reverse().join('/') : 'Non définie';
    }

    etudiantSelect.addEventListener('change', updateRecap);
    ecSelect.addEventListener('change', updateRecap);
    semestreInput.addEventListener('input', updateRecap);
    statutSelect.addEventListener('change', updateRecap);
    dateInput.addEventListener('change', updateRecap);

    updateRecap();
});
</script>

<?= $this->endSection() ?>