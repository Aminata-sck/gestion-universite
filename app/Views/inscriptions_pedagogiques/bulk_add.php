<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-users me-2"></i> Inscription groupée à un EC</h1>
    <a href="/inscriptions-pedagogiques" class="btn btn-secondary">
        <i class="fa-regular fa-arrow-left me-2"></i> Retour
    </a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/inscriptions-pedagogiques/bulk-store" id="bulkForm">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Formation</label>
                    <select name="formation_id" id="formationSelect" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($formations as $f): ?>
                            <option value="<?= $f['id'] ?>"><?= esc($f['affichage']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Sélectionnez la formation dont les étudiants seront inscrits.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">EC</label>
                    <select name="ec_id" id="ecSelect" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($ecs as $e): ?>
                            <option value="<?= $e['id'] ?>"><?= esc($e['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Semestre</label>
                    <input type="number" name="semestre" id="semestreInput" class="form-control" min="1" max="2" value="1" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Statut</label>
                    <select name="statut" id="statutSelect" class="form-select">
                        <option value="validée">Validée</option>
                        <option value="en_attente">En attente</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Année universitaire</label>
                    <select name="annee_universitaire_id" id="anneeSelect" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($annees as $a): ?>
                            <option value="<?= $a['id'] ?>"><?= esc($a['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Seuls les étudiants inscrits à cette année seront concernés.</small>
                </div>
            </div>

            <!-- Récapitulatif dynamique -->
            <div class="mt-4 p-3 bg-light rounded-3">
                <h6 class="fw-semibold">📋 Récapitulatif</h6>
                <div id="recap">
                    <p class="mb-1"><span class="text-muted">Formation :</span> <span id="recapFormation" class="fw-semibold">Non sélectionnée</span></p>
                    <p class="mb-1"><span class="text-muted">EC :</span> <span id="recapEc" class="fw-semibold">Non sélectionné</span></p>
                    <p class="mb-1"><span class="text-muted">Semestre :</span> <span id="recapSemestre" class="fw-semibold">1</span></p>
                    <p class="mb-1"><span class="text-muted">Statut :</span> <span id="recapStatut" class="fw-semibold">validée</span></p>
                    <p class="mb-0"><span class="text-muted">Année universitaire :</span> <span id="recapAnnee" class="fw-semibold">Non sélectionnée</span></p>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-regular fa-floppy-disk me-2"></i> Inscrire les étudiants
                </button>
                <a href="/inscriptions-pedagogiques" class="btn btn-secondary">
                    <i class="fa-regular fa-xmark me-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formationSelect = document.getElementById('formationSelect');
    const ecSelect = document.getElementById('ecSelect');
    const semestreInput = document.getElementById('semestreInput');
    const statutSelect = document.getElementById('statutSelect');
    const anneeSelect = document.getElementById('anneeSelect');

    const recapFormation = document.getElementById('recapFormation');
    const recapEc = document.getElementById('recapEc');
    const recapSemestre = document.getElementById('recapSemestre');
    const recapStatut = document.getElementById('recapStatut');
    const recapAnnee = document.getElementById('recapAnnee');

    function updateRecap() {
        const selectedFormation = formationSelect.options[formationSelect.selectedIndex];
        recapFormation.textContent = selectedFormation ? selectedFormation.text : 'Non sélectionnée';
        const selectedEc = ecSelect.options[ecSelect.selectedIndex];
        recapEc.textContent = selectedEc ? selectedEc.text : 'Non sélectionné';
        recapSemestre.textContent = semestreInput.value || '1';
        recapStatut.textContent = statutSelect.value;
        const selectedAnnee = anneeSelect.options[anneeSelect.selectedIndex];
        recapAnnee.textContent = selectedAnnee ? selectedAnnee.text : 'Non sélectionnée';
    }

    formationSelect.addEventListener('change', updateRecap);
    ecSelect.addEventListener('change', updateRecap);
    semestreInput.addEventListener('input', updateRecap);
    statutSelect.addEventListener('change', updateRecap);
    anneeSelect.addEventListener('change', updateRecap);

    updateRecap();
});
</script>

<style>
    .rounded-4 {
        border-radius: 1rem !important;
    }
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.05) !important;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .rounded-3 {
        border-radius: 0.5rem !important;
    }
    .form-select, .form-control {
        border-radius: 0.5rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .form-select:focus, .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.15);
    }
</style>

<?= $this->endSection() ?>