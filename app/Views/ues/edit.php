<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Modifier l'UE</h2>
<form method="post" action="/ues/update/<?= $ue['id'] ?>">
    <?= csrf_field() ?>
    
    <div class="mb-3">
        <label>Code</label>
        <input type="text" name="code" class="form-control" value="<?= esc($ue['code']) ?>" readonly>
    </div>
    
    <div class="mb-3">
        <label>Libellé</label>
        <input type="text" name="libelle" class="form-control" value="<?= esc($ue['libelle']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Cycle</label>
        <select name="cycle" class="form-control" required>
            <?php foreach ($cycles as $cycle): ?>
                <option value="<?= $cycle ?>" <?= ($cycle == ($ue['cycle'] ?? 'Licence')) ? 'selected' : '' ?>>
                    <?= $cycle ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Formation</label>
        <select name="formation_id" class="form-control" required>
            <?php foreach ($formations as $f): ?>
                <option value="<?= $f['id'] ?>" <?= $f['id'] == $ue['formation_id'] ? 'selected' : '' ?>>
                    <?= esc($f['affichage']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="mb-3">
        <label>Semestre</label>
        <input type="number" name="semestre" class="form-control" value="<?= $ue['semestre'] ?>" required>
    </div>
    
    <div class="mb-3">
        <label>Crédits ECTS</label>
        <input type="number" step="0.5" name="credits_ects" class="form-control" value="<?= $ue['credits_ects'] ?>">
    </div>
    
    <div class="mb-3">
        <label>Coefficient</label>
        <input type="number" step="0.1" name="coefficient" class="form-control" value="<?= $ue['coefficient'] ?>">
    </div>
    
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/ues" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>