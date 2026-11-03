<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-calendar-plus me-2"></i> Ajout groupé d'emplois du temps</h1>
    <a href="/emplois" class="btn btn-secondary">
        <i class="fa-regular fa-arrow-left me-2"></i> Retour
    </a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/emplois/bulk-store" id="bulkForm">
            <?= csrf_field() ?>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Formation</label>
                    <select name="formation_id" id="formationSelect" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($formations as $f): ?>
                            <option value="<?= $f['id'] ?>"><?= esc($f['affichage']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Sélectionnez la formation (cycle + niveau + libellé).</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Année universitaire</label>
                    <select name="annee_universitaire_id" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($annees as $a): ?>
                            <option value="<?= $a['id'] ?>"><?= esc($a['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Classe (optionnelle)</label>
                    <input type="text" name="classe" class="form-control" placeholder="Ex: L1 Info">
                </div>
            </div>

            <h5 class="mt-4">Cours à ajouter</h5>
            <div id="coursContainer">
                <!-- Ligne de cours par défaut -->
                <div class="row g-3 mb-3 cours-row">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">EC</label>
                        <select name="ecs[0][ec_id]" class="form-select ec-select" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach ($ecs as $ec): ?>
                                <option value="<?= $ec['id'] ?>">
                                    <?= esc($ec['formation_type'] . ' ' . $ec['formation_annee'] . ' - ' . $ec['formation_libelle'] . ' - ' . $ec['ue_libelle'] . ' - ' . $ec['libelle']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Salle</label>
                        <input type="text" name="ecs[0][salle]" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Jour</label>
                        <select name="ecs[0][jour]" class="form-select" required>
                            <?php foreach ($jours as $jour): ?>
                                <option value="<?= $jour ?>"><?= $jour ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Heure début</label>
                        <input type="time" name="ecs[0][heure_debut]" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Heure fin</label>
                        <input type="time" name="ecs[0][heure_fin]" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Type</label>
                        <select name="ecs[0][type_cours]" class="form-select">
                            <?php foreach ($types_cours as $key => $label): ?>
                                <option value="<?= $key ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-primary" id="addRow">
                <i class="fa-regular fa-plus me-2"></i> Ajouter un cours
            </button>

            <!-- Récapitulatif dynamique -->
            <div class="mt-4 p-3 bg-light rounded-3">
                <h6 class="fw-semibold">📋 Récapitulatif</h6>
                <div id="recap">
                    <p class="mb-1"><span class="text-muted">Formation :</span> <span id="recapFormation" class="fw-semibold">Non sélectionnée</span></p>
                    <p class="mb-1"><span class="text-muted">Année universitaire :</span> <span id="recapAnnee" class="fw-semibold">Non sélectionnée</span></p>
                    <p class="mb-1"><span class="text-muted">Nombre de cours :</span> <span id="recapCount" class="fw-semibold">1</span></p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                <i class="fa-regular fa-floppy-disk me-2"></i> Enregistrer tous les cours
            </button>
            <a href="/emplois" class="btn btn-secondary mt-3">Annuler</a>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formationSelect = document.getElementById('formationSelect');
    const anneeSelect = document.querySelector('select[name="annee_universitaire_id"]');
    const container = document.getElementById('coursContainer');
    const addBtn = document.getElementById('addRow');

    const recapFormation = document.getElementById('recapFormation');
    const recapAnnee = document.getElementById('recapAnnee');
    const recapCount = document.getElementById('recapCount');

    let rowIndex = 1;

    function updateRecap() {
        const selectedFormation = formationSelect.options[formationSelect.selectedIndex];
        recapFormation.textContent = selectedFormation ? selectedFormation.text : 'Non sélectionnée';
        const selectedAnnee = anneeSelect.options[anneeSelect.selectedIndex];
        recapAnnee.textContent = selectedAnnee ? selectedAnnee.text : 'Non sélectionnée';
        const rows = container.querySelectorAll('.cours-row');
        recapCount.textContent = rows.length;
    }

    function addRow() {
        const newRow = document.createElement('div');
        newRow.className = 'row g-3 mb-3 cours-row';
        newRow.innerHTML = `
            <div class="col-md-2">
                <label class="form-label fw-semibold">EC</label>
                <select name="ecs[${rowIndex}][ec_id]" class="form-select ec-select" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($ecs as $ec): ?>
                        <option value="<?= $ec['id'] ?>">
                            <?= esc($ec['formation_type'] . ' ' . $ec['formation_annee'] . ' - ' . $ec['formation_libelle'] . ' - ' . $ec['ue_libelle'] . ' - ' . $ec['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="ecs[${rowIndex}][salle]" class="form-control" placeholder="Salle" required>
            </div>
            <div class="col-md-2">
                <select name="ecs[${rowIndex}][jour]" class="form-select" required>
                    <?php foreach ($jours as $jour): ?>
                        <option value="<?= $jour ?>"><?= $jour ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="time" name="ecs[${rowIndex}][heure_debut]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <input type="time" name="ecs[${rowIndex}][heure_fin]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <select name="ecs[${rowIndex}][type_cours]" class="form-select">
                    <?php foreach ($types_cours as $key => $label): ?>
                        <option value="<?= $key ?>"><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        `;
        container.appendChild(newRow);
        rowIndex++;
        updateRecap();
    }

    addBtn.addEventListener('click', addRow);

    formationSelect.addEventListener('change', updateRecap);
    anneeSelect.addEventListener('change', updateRecap);

    updateRecap();
});
</script>

<?= $this->endSection() ?>