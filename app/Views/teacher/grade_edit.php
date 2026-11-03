<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fa-regular fa-pen-to-square me-2"></i> Modifier la note – <?= esc($ec['libelle']) ?></h1>
    <a href="/teacher/courses" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour</a>
</div>
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/teacher/grades/update/<?= $note['id'] ?>">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Étudiant</label>
                    <input type="text" class="form-control" value="<?= esc($note['etudiant_nom'] ?? '') ?>" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Note</label>
                    <input type="number" step="0.01" name="note" class="form-control" value="<?= $note['note'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="type" class="form-select">
                        <?php foreach ($types as $key => $label): ?>
                            <option value="<?= $key ?>" <?= $key == $note['type'] ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Coefficient</label>
                    <input type="number" step="0.1" name="coefficient" class="form-control" value="<?= $note['coefficient'] ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Session</label>
                    <input type="text" name="session" class="form-control" value="<?= esc($note['session']) ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3"><i class="fa-regular fa-floppy-disk me-2"></i>Mettre à jour</button>
            <a href="/teacher/courses" class="btn btn-secondary mt-3">Annuler</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>