<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1>
        <i class="fa-regular fa-circle-xmark text-danger me-2"></i>
        Saisie des absences - <?= esc($ec['libelle']) ?>
    </h1>
    <span class="badge bg-secondary rounded-pill px-3 py-2">
        <i class="fa-regular fa-users me-1"></i> <?= count($etudiants) ?> étudiant(s)
    </span>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/teacher/save-absences">
            <?= csrf_field() ?>
            <input type="hidden" name="ec_id" value="<?= $ec['id'] ?>">

            <!-- Champs de sélection -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="emploi_id" class="form-label fw-semibold">
                        <i class="fa-regular fa-calendar text-primary me-1"></i> Cours (emploi du temps)
                    </label>
                    <?php if (!empty($emplois)): ?>
                        <select name="emploi_id" id="emploi_id" class="form-select" required>
                            <?php foreach ($emplois as $emp): ?>
                                <option value="<?= $emp['id'] ?>">
                                    <?= $emp['jour'] ?> - <?= date('H:i', strtotime($emp['heure_debut'])) ?> (<?= esc($emp['salle']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fa-regular fa-triangle-exclamation me-2"></i>
                            Aucun emploi du temps trouvé pour cet EC. Veuillez d'abord créer un emploi du temps.
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="date_absence" class="form-label fw-semibold">
                        <i class="fa-regular fa-calendar-day text-primary me-1"></i> Date de l'absence
                    </label>
                    <input type="date" name="date_absence" id="date_absence" class="form-control" required value="<?= date('Y-m-d') ?>">
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
                            <th class="text-center" style="width: 120px;">
                                <i class="fa-regular fa-circle-xmark text-danger me-1"></i>
                                Absent
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($etudiants)): ?>
                            <?php foreach ($etudiants as $etudiant): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-secondary rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                                <?= strtoupper(substr($etudiant['prenom'], 0, 1)) . strtoupper(substr($etudiant['nom'], 0, 1)) ?>
                                            </span>
                                            <div>
                                                <div class="fw-semibold"><?= esc($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></div>
                                                <small class="text-muted"><?= esc($etudiant['email'] ?? '') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="etudiants[<?= $etudiant['user_id'] ?>]" 
                                                   value="1" id="abs_<?= $etudiant['user_id'] ?>"
                                                   style="width: 48px; height: 24px; cursor: pointer;">
                                            <label class="form-check-label visually-hidden" for="abs_<?= $etudiant['user_id'] ?>">
                                                Absent
                                            </label>
                                        </div>
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
                    <i class="fa-regular fa-floppy-disk me-2"></i> Enregistrer les absences
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
    .form-check-input:checked {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
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
    .badge-circle {
        background-color: #e9ecef;
        color: #495057;
        font-weight: 600;
    }
</style>

<?= $this->endSection() ?>