<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-cubes me-2"></i> Ajouter un EC</h1>
    <a href="/ues/<?= $ue['id'] ?>" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour à l'UE</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/ecs/ue/<?= $ue['id'] ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">UE parente</label>
                <input type="text" class="form-control" value="<?= esc($ue['formation_type'] . ' ' . $ue['formation_annee'] . ' - ' . $ue['libelle']) ?>" readonly>
                <small class="text-muted">Le rattachement est fixé par la fiche UE d'origine.</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Libellé</label>
                <input type="text" name="libelle" class="form-control" placeholder="Ex: CM Programmation Web" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Coefficient</label>
                <input type="number" step="0.1" name="coefficient" class="form-control" value="<?= $coefficient_default ?? 1 ?>" required>
                <small class="text-muted">
                    <i class="fa-regular fa-circle-info me-1"></i>
                    Coefficient par défaut de l'UE : <strong><?= $coefficient_default ?? 'non défini' ?></strong> (vous pouvez le modifier pour cet EC)
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Type</label>
                <select name="type" class="form-select" required>
                    <option value="CM">Cours</option>
                    <option value="TD">TD</option>
                    <option value="TP">TP</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Enseignant</label>
                <select name="enseignant_id" class="form-select" required>
                    <?php foreach ($enseignants as $ens): ?>
                        <option value="<?= $ens['id'] ?>"><?= esc($ens['nom'] . ' ' . $ens['prenom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk me-2"></i>Créer</button>
            <a href="/ues/<?= $ue['id'] ?>" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
