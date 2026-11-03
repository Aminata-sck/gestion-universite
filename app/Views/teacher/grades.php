<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1>
        <i class="fa-regular fa-star text-warning me-2"></i>
        Saisie des notes - <?= esc($ec['libelle']) ?>
    </h1>
    <span class="badge bg-secondary rounded-pill px-3 py-2">
        <i class="fa-regular fa-users me-1"></i> <?= count($etudiants) ?> étudiant(s)
    </span>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/teacher/save-grades">
            <?= csrf_field() ?>
            <input type="hidden" name="ec_id" value="<?= $ec['id'] ?>">

            <!-- Paramètres de la note -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="type" class="form-label fw-semibold">
                        <i class="fa-regular fa-tag text-primary me-1"></i> Type
                    </label>
                    <select name="type" id="type" class="form-select">
                        <option value="cc">Contrôle continu</option>
                        <option value="examen" selected>Examen</option>
                        <option value="rattrapage">Rattrapage</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="coefficient" class="form-label fw-semibold">
                        <i class="fa-regular fa-scale-balanced text-primary me-1"></i> Coefficient
                    </label>
                    <input type="number" step="0.1" name="coefficient" id="coefficient" class="form-control" 
                           value="<?= $ec['coefficient'] ?>" required>
                    <small class="text-muted">Coefficient par défaut de l'EC (modifiable)</small>
                </div>
                <div class="col-md-4">
                    <label for="session" class="form-label fw-semibold">
                        <i class="fa-regular fa-calendar text-primary me-1"></i> Session
                    </label>
                    <input type="text" name="session" id="session" class="form-control" 
                           placeholder="Ex: 2025-2026" value="<?= date('Y') . '-' . (date('Y') + 1) ?>">
                </div>
            </div>

            <!-- Liste des étudiants -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <i class="fa-regular fa-user text-muted me-2"></i>
                                Étudiant
                            </th>
                            <th class="text-center" style="width: 200px;">
                                <i class="fa-regular fa-pen-to-square text-primary me-1"></i>
                                Note / 20
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($etudiants)): ?>
                            <?php foreach ($etudiants as $etudiant): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-secondary rounded-circle" 
                                                  style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                                <?= strtoupper(substr($etudiant['prenom'], 0, 1)) . strtoupper(substr($etudiant['nom'], 0, 1)) ?>
                                            </span>
                                            <div>
                                                <div class="fw-semibold"><?= esc($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></div>
                                                <small class="text-muted"><?= esc($etudiant['email'] ?? '') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" 
                                               name="notes[<?= $etudiant['user_id'] ?>]" 
                                               class="form-control text-center" 
                                               placeholder="–"
                                               min="0" max="20"
                                               style="font-size: 1.1rem; font-weight: 500;">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">
                                    <i class="fa-regular fa-circle-info fa-2x d-block mb-2"></i>
                                    Aucun étudiant inscrit à ce cours pour le moment.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Boutons d'action -->
            <div class="d-flex gap-2 mt-4 flex-wrap">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-regular fa-floppy-disk me-2"></i> Enregistrer les notes
                </button>
                <a href="/teacher/courses" class="btn btn-secondary">
                    <i class="fa-regular fa-arrow-left me-2"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Style personnalisé -->
<style>
    .form-select, .form-control {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .form-select:focus, .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }
    .table th {
        font-weight: 600;
        border-bottom-width: 2px;
    }
    .table tbody tr {
        transition: background-color 0.15s ease;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        opacity: 0.5;
    }
    .badge-circle {
        background-color: #e9ecef;
        color: #495057;
        font-weight: 600;
    }
    .btn {
        font-weight: 500;
    }
</style>

<?= $this->endSection() ?>