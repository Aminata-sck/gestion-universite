<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fa-regular fa-layer-group me-2"></i> Ajouter une UE</h1>
    <a href="/ues" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/ues" id="ueForm">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">Code (généré)</label>
                <input type="text" name="code" class="form-control" value="<?= $code ?? '' ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Libellé</label>
                <input type="text" name="libelle" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Cycle</label>
                <select name="cycle" class="form-select" required>
                    <?php foreach ($cycles as $cycle): ?>
                        <option value="<?= $cycle ?>" <?= ($cycle == 'Licence') ? 'selected' : '' ?>><?= $cycle ?></option>
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

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Semestre</label>
                    <input type="number" name="semestre" class="form-control" min="1" max="2" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Crédits ECTS</label>
                    <input type="number" step="0.5" name="credits_ects" class="form-control" value="6">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Coefficient</label>
                    <input type="number" step="0.1" name="coefficient" class="form-control" value="1">
                </div>
            </div>

            <!-- Récapitulatif dynamique -->
            <div class="mt-4 p-3 bg-light rounded-3">
                <h6 class="fw-semibold">📋 Récapitulatif</h6>
                <div id="recap">
                    <p class="mb-1"><span class="text-muted">Formation :</span> <span id="recapFormation" class="fw-semibold">Non sélectionnée</span></p>
                    <p class="mb-1"><span class="text-muted">Cycle :</span> <span id="recapCycle" class="fw-semibold">Licence</span></p>
                    <p class="mb-1"><span class="text-muted">Semestre :</span> <span id="recapSemestre" class="fw-semibold">-</span></p>
                    <p class="mb-1"><span class="text-muted">Crédits ECTS :</span> <span id="recapCredits" class="fw-semibold">6</span></p>
                    <p class="mb-0"><span class="text-muted">Coefficient :</span> <span id="recapCoeff" class="fw-semibold">1</span></p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3"><i class="fa-regular fa-floppy-disk me-2"></i>Créer</button>
            <a href="/ues" class="btn btn-secondary mt-3">Annuler</a>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('ueForm');
    const formationSelect = form.querySelector('select[name="formation_id"]');
    const cycleSelect = form.querySelector('select[name="cycle"]');
    const semestreInput = form.querySelector('input[name="semestre"]');
    const creditsInput = form.querySelector('input[name="credits_ects"]');
    const coeffInput = form.querySelector('input[name="coefficient"]');

    const recapFormation = document.getElementById('recapFormation');
    const recapCycle = document.getElementById('recapCycle');
    const recapSemestre = document.getElementById('recapSemestre');
    const recapCredits = document.getElementById('recapCredits');
    const recapCoeff = document.getElementById('recapCoeff');

    function updateRecap() {
        const selectedOption = formationSelect.options[formationSelect.selectedIndex];
        recapFormation.textContent = selectedOption ? selectedOption.text : 'Non sélectionnée';
        recapCycle.textContent = cycleSelect.value;
        recapSemestre.textContent = semestreInput.value || '-';
        recapCredits.textContent = creditsInput.value || '0';
        recapCoeff.textContent = coeffInput.value || '0';
    }

    formationSelect.addEventListener('change', updateRecap);
    cycleSelect.addEventListener('change', updateRecap);
    semestreInput.addEventListener('input', updateRecap);
    creditsInput.addEventListener('input', updateRecap);
    coeffInput.addEventListener('input', updateRecap);

    updateRecap();
});
</script>

<?= $this->endSection() ?>