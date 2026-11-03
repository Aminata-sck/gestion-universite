<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Modifier l'EC</h2>
<form method="post" action="/ecs/update/<?= $ec['id'] ?>">
    <?= csrf_field() ?>
    <div class="mb-3"><label>UE parente</label><input type="text" class="form-control" value="<?= esc($ue['libelle'] ?? '') ?>" readonly><small class="text-muted">Le rattachement à l'UE est conservé pour garantir la traçabilité.</small></div>
    <div class="mb-3"><label>Libellé</label><input type="text" name="libelle" class="form-control" value="<?= esc($ec['libelle']) ?>" required></div>
    <div class="mb-3"><label>Coefficient</label><input type="number" step="0.1" name="coefficient" class="form-control" value="<?= $ec['coefficient'] ?>" required></div>
    <div class="mb-3">
        <label>Type</label>
        <select name="type" class="form-control" required>
            <option value="CM" <?= $ec['type'] == 'CM' ? 'selected' : '' ?>>CM</option>
            <option value="TD" <?= $ec['type'] == 'TD' ? 'selected' : '' ?>>TD</option>
            <option value="TP" <?= $ec['type'] == 'TP' ? 'selected' : '' ?>>TP</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Enseignant</label>
        <select name="enseignant_id" class="form-control" required>
            <?php foreach ($enseignants as $ens): ?>
                <option value="<?= $ens['id'] ?>" <?= $ens['id'] == $ec['enseignant_id'] ? 'selected' : '' ?>><?= esc($ens['nom'] . ' ' . $ens['prenom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/ues/<?= $ec['ue_id'] ?>" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>
