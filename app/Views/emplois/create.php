<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Ajouter un cours</h2>
<form method="post" action="/emplois">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label>EC</label>
        <select name="ec_id" class="form-control" required>
            <?php foreach ($ecs as $ec): ?>
                <option value="<?= $ec['id'] ?>"><?= esc($ec['libelle']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Année académique</label>
        <select name="annee_universitaire_id" class="form-control">
            <option value="">-- Sélectionner --</option>
            <?php foreach ($annees as $a): ?>
                <option value="<?= $a['id'] ?>"><?= esc($a['libelle']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Classe (ex: L1 Informatique)</label>
        <input type="text" name="classe" class="form-control" placeholder="Ex: L1 Info">
    </div>
    <div class="mb-3"><label>Salle</label><input type="text" name="salle" class="form-control" required></div>
    <div class="mb-3">
        <label>Jour</label>
        <select name="jour" class="form-control">
            <?php foreach (['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'] as $jour): ?>
                <option value="<?= $jour ?>"><?= $jour ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3"><label>Heure début</label><input type="time" name="heure_debut" class="form-control" required></div>
    <div class="mb-3"><label>Heure fin</label><input type="time" name="heure_fin" class="form-control" required></div>
    <div class="mb-3">
        <label>Type cours</label>
        <select name="type_cours" class="form-control">
            <option value="Cours">Cours</option>
            <option value="TD">TD</option>
            <option value="TP">TP</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
    <a href="/emplois" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>