<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fa-regular fa-edit me-2"></i> Ajouter une inscription administrative</h1>
    <a href="/inscriptions-admin" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/inscriptions-admin" id="inscriptionForm">
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
                <label class="form-label fw-semibold">Formation</label>
                <select name="formation_id" class="form-select" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($formations as $f): ?>
                        <option value="<?= $f['id'] ?>"><?= esc($f['affichage']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted">Sélectionnez la formation (cycle + niveau + libellé).</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Année universitaire</label>
                <select name="annee_universitaire_id" class="form-select" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($annees as $a): ?>
                        <option value="<?= $a['id'] ?>"><?= esc($a['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Date d'inscription</label>
                <input type="date" name="date_inscription" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Statut</label>
                <select name="statut" class="form-select">
                    <option value="en_attente">En attente</option>
                    <option value="validée">Validée</option>
                    <option value="rejetée">Rejetée</option>
                </select>
            </div>

            <!-- Récapitulatif dynamique -->
            <div class="mt-4 p-3 bg-light rounded-3">
                <h6 class="fw-semibold">📋 Récapitulatif</h6>
                <div id="recap">
                    <p class="mb-1"><span class="text-muted">Étudiant :</span> <span id="recapEtudiant" class="fw-semibold">Non sélectionné</span></p>
                    <p class="mb-1"><span class="text-muted">Formation :</span> <span id="recapFormation" class="fw-semibold">Non sélectionnée</span></p>
                    <p class="mb-1"><span class="text-muted">Année universitaire :</span> <span id="recapAnnee" class="fw-semibold">Non sélectionnée</span></p>
                    <p class="mb-1"><span class="text-muted">Statut :</span> <span id="recapStatut" class="fw-semibold">en_attente</span></p>
                    <p class="mb-0"><span class="text-muted">Date d'inscription :</span> <span id="recapDate" class="fw-semibold"><?= date('d/m/Y') ?></span></p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3"><i class="fa-regular fa-floppy-disk me-2"></i>Enregistrer</button>
            <a href="/inscriptions-admin" class="btn btn-secondary mt-3">Annuler</a>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('inscriptionForm');
    const etudiantSelect = form.querySelector('select[name="etudiant_id"]');
    const formationSelect = form.querySelector('select[name="formation_id"]');
    const anneeSelect = form.querySelector('select[name="annee_universitaire_id"]');
    const statutSelect = form.querySelector('select[name="statut"]');
    const dateInput = form.querySelector('input[name="date_inscription"]');

    const recapEtudiant = document.getElementById('recapEtudiant');
    const recapFormation = document.getElementById('recapFormation');
    const recapAnnee = document.getElementById('recapAnnee');
    const recapStatut = document.getElementById('recapStatut');
    const recapDate = document.getElementById('recapDate');

    function updateRecap() {
        const selectedEtudiant = etudiantSelect.options[etudiantSelect.selectedIndex];
        recapEtudiant.textContent = selectedEtudiant ? selectedEtudiant.text : 'Non sélectionné';
        const selectedFormation = formationSelect.options[formationSelect.selectedIndex];
        recapFormation.textContent = selectedFormation ? selectedFormation.text : 'Non sélectionnée';
        const selectedAnnee = anneeSelect.options[anneeSelect.selectedIndex];
        recapAnnee.textContent = selectedAnnee ? selectedAnnee.text : 'Non sélectionnée';
        recapStatut.textContent = statutSelect.value;
        recapDate.textContent = dateInput.value ? dateInput.value.split('-').reverse().join('/') : 'Non définie';
    }

    etudiantSelect.addEventListener('change', updateRecap);
    formationSelect.addEventListener('change', updateRecap);
    anneeSelect.addEventListener('change', updateRecap);
    statutSelect.addEventListener('change', updateRecap);
    dateInput.addEventListener('change', updateRecap);

    updateRecap();
});
</script>

<?= $this->endSection() ?>