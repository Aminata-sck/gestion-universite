<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Modifier la formation</h2>
<form method="post" action="/formations/update/<?= $formation['id'] ?>">
    <?= csrf_field() ?>
    <div class="mb-3"><label>Libellé</label><input type="text" name="libelle" class="form-control" value="<?= esc($formation['libelle']) ?>" required></div>
    <div class="mb-3">
        <label>Type</label>
        <select name="type" class="form-control">
            <option value="Licence" <?= $formation['type'] == 'Licence' ? 'selected' : '' ?>>Licence</option>
            <option value="Master" <?= $formation['type'] == 'Master' ? 'selected' : '' ?>>Master</option>
            <option value="Doctorat" <?= $formation['type'] == 'Doctorat' ? 'selected' : '' ?>>Doctorat</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Niveau / Année</label>
        <select name="annee" class="form-control">
            <option value="">-- Sélectionner --</option>
            <option value="L1" <?= $formation['annee'] == 'L1' ? 'selected' : '' ?>>Licence 1</option>
            <option value="L2" <?= $formation['annee'] == 'L2' ? 'selected' : '' ?>>Licence 2</option>
            <option value="L3" <?= $formation['annee'] == 'L3' ? 'selected' : '' ?>>Licence 3</option>
            <option value="M1" <?= $formation['annee'] == 'M1' ? 'selected' : '' ?>>Master 1</option>
            <option value="M2" <?= $formation['annee'] == 'M2' ? 'selected' : '' ?>>Master 2</option>
            <option value="D1" <?= $formation['annee'] == 'D1' ? 'selected' : '' ?>>Doctorat 1</option>
            <option value="D2" <?= $formation['annee'] == 'D2' ? 'selected' : '' ?>>Doctorat 2</option>
            <option value="D3" <?= $formation['annee'] == 'D3' ? 'selected' : '' ?>>Doctorat 3</option>
        </select>
    </div>
    <div class="mb-3"><label>Durée (années)</label><input type="number" name="duree" class="form-control" value="<?= $formation['duree'] ?>" required></div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/formations" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>