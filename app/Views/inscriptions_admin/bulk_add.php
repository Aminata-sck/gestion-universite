<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fa-regular fa-users me-2"></i> Inscription administrative groupée</h1>
    <a href="/inscriptions-admin" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/inscriptions-admin/bulk-store" id="inscriptionForm">
            <?= csrf_field() ?>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Formation</label>
                    <select name="formation_id" id="formationSelect" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($formations as $f): ?>
                            <option value="<?= $f['id'] ?>"><?= esc($f['affichage']) ?></option>
                        <?php endforeach; ?>
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
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="en_attente">En attente</option>
                        <option value="validée">Validée</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Date d'inscription</label>
                <input type="date" name="date_inscription" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <!-- Récapitulatif dynamique -->
            <div class="mt-3 p-3 bg-light rounded-3">
                <h6 class="fw-semibold">📋 Récapitulatif</h6>
                <div id="recap">
                    <p class="mb-1"><span class="text-muted">Formation :</span> <span id="recapFormation" class="fw-semibold">Non sélectionnée</span></p>
                    <p class="mb-1"><span class="text-muted">Année universitaire :</span> <span id="recapAnnee" class="fw-semibold">Non sélectionnée</span></p>
                    <p class="mb-1"><span class="text-muted">Statut :</span> <span id="recapStatut" class="fw-semibold">en_attente</span></p>
                    <p class="mb-0"><span class="text-muted">Date d'inscription :</span> <span id="recapDate" class="fw-semibold"><?= date('d/m/Y') ?></span></p>
                </div>
            </div>

            <h5 class="mt-4">Sélectionner les étudiants</h5>
            <div id="studentListContainer">
                <p class="text-muted">Veuillez d'abord sélectionner une formation et une année.</p>
            </div>

            <button type="submit" class="btn btn-primary mt-3"><i class="fa-regular fa-floppy-disk me-2"></i>Inscrire les étudiants</button>
            <a href="/inscriptions-admin" class="btn btn-secondary mt-3">Annuler</a>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formationSelect = document.getElementById('formationSelect');
    const anneeSelect = document.getElementById('anneeSelect');
    const statutSelect = document.querySelector('select[name="statut"]');
    const dateInput = document.querySelector('input[name="date_inscription"]');
    const container = document.getElementById('studentListContainer');

    const recapFormation = document.getElementById('recapFormation');
    const recapAnnee = document.getElementById('recapAnnee');
    const recapStatut = document.getElementById('recapStatut');
    const recapDate = document.getElementById('recapDate');

    function updateRecap() {
        const selectedFormation = formationSelect.options[formationSelect.selectedIndex];
        recapFormation.textContent = selectedFormation ? selectedFormation.text : 'Non sélectionnée';
        const selectedAnnee = anneeSelect.options[anneeSelect.selectedIndex];
        recapAnnee.textContent = selectedAnnee ? selectedAnnee.text : 'Non sélectionnée';
        recapStatut.textContent = statutSelect.value;
        recapDate.textContent = dateInput.value ? dateInput.value.split('-').reverse().join('/') : 'Non définie';
    }

    function loadStudents() {
        const formationId = formationSelect.value;
        const anneeId = anneeSelect.value;
        if (!formationId || !anneeId) {
            container.innerHTML = '<p class="text-muted">Veuillez sélectionner une formation et une année.</p>';
            return;
        }

        container.innerHTML = '<p>Chargement...</p>';

        fetch('/inscriptions-admin/get-etudiants/' + formationId + '/' + anneeId)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = '<p class="text-muted">Aucun étudiant inscrit pour cette formation/année.</p>';
                    return;
                }
                let html = '<div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th><input type="checkbox" id="selectAll"></th><th>Nom</th><th>Prénom</th><th>Email</th></tr></thead><tbody>';
                data.forEach(etudiant => {
                    html += `<tr>
                        <td><input type="checkbox" name="etudiants[]" value="${etudiant.id}"></td>
                        <td>${etudiant.nom}</td>
                        <td>${etudiant.prenom}</td>
                        <td>${etudiant.email}</td>
                    </tr>`;
                });
                html += '</tbody></table></div>';
                container.innerHTML = html;
                const selectAll = document.getElementById('selectAll');
                if (selectAll) {
                    selectAll.addEventListener('change', function() {
                        const checkboxes = document.querySelectorAll('input[name="etudiants[]"]');
                        checkboxes.forEach(cb => cb.checked = this.checked);
                    });
                }
            })
            .catch(() => {
                container.innerHTML = '<p class="text-danger">Erreur lors du chargement des étudiants.</p>';
            });
    }

    formationSelect.addEventListener('change', function() { updateRecap(); loadStudents(); });
    anneeSelect.addEventListener('change', function() { updateRecap(); loadStudents(); });
    statutSelect.addEventListener('change', updateRecap);
    dateInput.addEventListener('change', updateRecap);

    updateRecap();
});
</script>

<?= $this->endSection() ?>