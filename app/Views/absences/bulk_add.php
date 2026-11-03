<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fa-regular fa-circle-xmark me-2"></i> Saisie groupée d'absences</h1>
    <a href="/absences" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/absences/bulk-store">
            <?= csrf_field() ?>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Cours (emploi du temps)</label>
                    <select name="emploi_id" id="emploiSelect" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($emplois as $emp): ?>
                            <option value="<?= $emp['id'] ?>" data-ec-id="<?= $emp['ec_id'] ?>">
                                <?= esc($emp['ec_libelle']) ?> - <?= $emp['jour'] ?> <?= date('H:i', strtotime($emp['heure_debut'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Date de l'absence</label>
                    <input type="date" name="date_absence" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Enseignant</label>
                    <select name="enseignant_id" class="form-select" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($enseignants as $ens): ?>
                            <option value="<?= $ens['id'] ?>"><?= esc($ens['nom'] . ' ' . $ens['prenom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <h5 class="mt-4">Sélectionner les étudiants absents</h5>
            <div id="studentListContainer">
                <p class="text-muted">Veuillez d'abord sélectionner un cours.</p>
            </div>

            <button type="submit" class="btn btn-primary mt-3"><i class="fa-regular fa-floppy-disk me-2"></i>Enregistrer les absences</button>
            <a href="/absences" class="btn btn-secondary mt-3">Annuler</a>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emploiSelect = document.getElementById('emploiSelect');
    const container = document.getElementById('studentListContainer');

    emploiSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const ecId = selectedOption.dataset.ecId;
        if (!ecId) {
            container.innerHTML = '<p class="text-muted">Veuillez sélectionner un cours.</p>';
            return;
        }

        container.innerHTML = '<p>Chargement...</p>';

        fetch('/absences/get-etudiants/' + ecId)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = '<p class="text-muted">Aucun étudiant inscrit à ce cours.</p>';
                    return;
                }
                let html = '<div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th><input type="checkbox" id="selectAll"></th><th>Nom</th><th>Prénom</th><th>Email</th></tr></thead><tbody>';
                data.forEach(etudiant => {
                    html += `<tr>
                        <td><input type="checkbox" name="etudiants[]" value="${etudiant.user_id}"></td>
                        <td>${etudiant.nom}</td>
                        <td>${etudiant.prenom}</td>
                        <td>${etudiant.email}</td>
                    </tr>`;
                });
                html += '</tbody></table></div>';
                container.innerHTML = html;

                // Gérer le "Tout sélectionner"
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
    });
});
</script>

<?= $this->endSection() ?>