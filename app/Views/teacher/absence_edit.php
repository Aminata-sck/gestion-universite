<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fa-regular fa-pen-to-square me-2"></i> Modifier l’absence – <?= esc($ec['libelle']) ?></h1>
    <a href="/teacher/courses" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour</a>
</div>
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/teacher/absences/update/<?= $absence['id'] ?>">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Étudiant</label>
                    <input type="text" class="form-control" value="<?= esc($absence['etudiant_nom'] ?? '') ?>" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Date</label>
                    <input type="date" name="date_absence" class="form-control" value="<?= $absence['date_absence'] ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Justifiée</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="justifie" value="1" <?= $absence['justifie'] ? 'checked' : '' ?>>
                        <label class="form-check-label">Oui</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Motif</label>
                    <input type="text" name="motif" class="form-control" value="<?= esc($absence['motif']) ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3"><i class="fa-regular fa-floppy-disk me-2"></i>Mettre à jour</button>
            <a href="/teacher/courses" class="btn btn-secondary mt-3">Annuler</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>