<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fa-regular fa-pen-to-square me-2"></i> Modifier le créneau – <?= esc($ec['libelle']) ?></h1>
    <a href="/teacher/courses" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour</a>
</div>
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/teacher/schedule/update/<?= $emploi['id'] ?>">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Salle</label>
                    <input type="text" name="salle" class="form-control" value="<?= esc($emploi['salle']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jour</label>
                    <select name="jour" class="form-select" required>
                        <?php foreach ($jours as $jour): ?>
                            <option value="<?= $jour ?>" <?= $jour == $emploi['jour'] ? 'selected' : '' ?>><?= $jour ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Heure début</label>
                    <input type="time" name="heure_debut" class="form-control" value="<?= $emploi['heure_debut'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Heure fin</label>
                    <input type="time" name="heure_fin" class="form-control" value="<?= $emploi['heure_fin'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="type_cours" class="form-select">
                        <?php foreach ($types as $key => $label): ?>
                            <option value="<?= $key ?>" <?= $key == $emploi['type_cours'] ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3"><i class="fa-regular fa-floppy-disk me-2"></i>Mettre à jour</button>
            <a href="/teacher/courses" class="btn btn-secondary mt-3">Annuler</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>