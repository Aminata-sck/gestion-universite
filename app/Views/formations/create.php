<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Ajouter une formation</h2>
<form method="post" action="/formations">
    <?= csrf_field() ?>
    <div class="mb-3"><label>Libellé</label><input type="text" name="libelle" class="form-control" required></div>
    <div class="mb-3">
        <label>Type</label>
        <select name="type" class="form-control">
            <option value="Licence">Licence</option>
            <option value="Master">Master</option>
            <option value="Doctorat">Doctorat</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Niveau / Année</label>
        <select name="annee" class="form-control">
            <option value="">-- Sélectionner --</option>
            <option value="L1">Licence 1</option>
            <option value="L2">Licence 2</option>
            <option value="L3">Licence 3</option>
            <option value="M1">Master 1</option>
            <option value="M2">Master 2</option>
            <option value="D1">Doctorat 1</option>
            <option value="D2">Doctorat 2</option>
            <option value="D3">Doctorat 3</option>
        </select>
    </div>
    <div class="mb-3"><label>Durée (années)</label><input type="number" name="duree" class="form-control" required></div>
    <button type="submit" class="btn btn-primary">Créer</button>
    <a href="/formations" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>