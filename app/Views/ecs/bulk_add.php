<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fa-regular fa-layer-group me-2"></i> Ajout groupé d'EC</h1>
    <a href="/ecs" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form id="bulkForm" method="post" action="/ecs/bulk-store">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">UE</label>
                <select name="ue_id" class="form-select" required>
    <option value="">-- Sélectionner une UE --</option>
    <?php foreach ($ues as $u): ?>
        <option value="<?= $u['id'] ?>"><?= esc($u['formation_type'] . ' ' . $u['formation_annee'] . ' - ' . $u['libelle']) ?></option>
    <?php endforeach; ?>
</select>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="ecsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Libellé</th>
                            <th>Coefficient</th>
                            <th>Type</th>
                            <th>Enseignant</th>
                            <th style="width: 50px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="ec-row">
                            <td><input type="text" name="ecs[0][libelle]" class="form-control" placeholder="Ex: CM" required></td>
                            <td><input type="number" step="0.1" name="ecs[0][coefficient]" class="form-control" value="1"></td>
                            <td>
                                <select name="ecs[0][type]" class="form-select" required>
                                    <option value="CM">Cours</option>
                                    <option value="TD">TD</option>
                                    <option value="TP">TP</option>
                                </select>
                            </td>
                            <td>
                                <select name="ecs[0][enseignant_id]" class="form-select" required>
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach ($enseignants as $ens): ?>
                                        <option value="<?= $ens['id'] ?>"><?= esc($ens['nom'] . ' ' . $ens['prenom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row" disabled>
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn btn-outline-primary" id="addRow">
                <i class="fa-regular fa-plus me-2"></i>Ajouter une ligne
            </button>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk me-2"></i>Enregistrer tous les EC</button>
                <a href="/ecs" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.ec-row');
        rows.forEach((row, idx) => {
            const btn = row.querySelector('.remove-row');
            if (rows.length === 1) {
                btn.disabled = true;
            } else {
                btn.disabled = false;
            }
        });
    }

    function addRow() {
        const tbody = document.querySelector('#ecsTable tbody');
        const newRow = document.createElement('tr');
        newRow.className = 'ec-row';
        newRow.innerHTML = `
            <td><input type="text" name="ecs[${rowIndex}][libelle]" class="form-control" placeholder="Ex: TD" required></td>
            <td><input type="number" step="0.1" name="ecs[${rowIndex}][coefficient]" class="form-control" value="1"></td>
            <td>
                <select name="ecs[${rowIndex}][type]" class="form-select" required>
                    <option value="CM">Cours</option>
                    <option value="TD">TD</option>
                    <option value="TP">TP</option>
                </select>
            </td>
            <td>
                <select name="ecs[${rowIndex}][enseignant_id]" class="form-select" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($enseignants as $ens): ?>
                        <option value="<?= $ens['id'] ?>"><?= esc($ens['nom'] . ' ' . $ens['prenom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </td>
        `;
        tbody.appendChild(newRow);
        rowIndex++;
        updateRemoveButtons();
    }

    document.getElementById('addRow').addEventListener('click', addRow);

    document.querySelector('#ecsTable').addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            const row = e.target.closest('.ec-row');
            if (document.querySelectorAll('.ec-row').length > 1) {
                row.remove();
                updateRemoveButtons();
            }
        }
    });

    // Mettre à jour les boutons au chargement
    updateRemoveButtons();
});
</script>

<?= $this->endSection() ?>