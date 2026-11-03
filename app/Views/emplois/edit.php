<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Modifier un cours</h2>
<form method="post" action="/emplois/update/<?= $emploi['id'] ?>">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label>EC</label>
        <select name="ec_id" class="form-control" required>
            <?php foreach ($ecs as $ec): ?>
                <option value="<?= $ec['id'] ?>" <?= $ec['id'] == $emploi['ec_id'] ? 'selected' : '' ?>><?= esc($ec['libelle']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Année académique</label>
        <select name="annee_universitaire_id" class="form-control">
            <option value="">-- Aucune --</option>
            <?php foreach ($annees as $a): ?>
                <option value="<?= $a['id'] ?>" <?= $a['id'] == $emploi['annee_universitaire_id'] ? 'selected' : '' ?>><?= esc($a['libelle']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Classe (ex: L1 Informatique)</label>
        <input type="text" name="classe" class="form-control" value="<?= esc($emploi['classe']) ?>" placeholder="Ex: L1 Info">
    </div>
    <div class="mb-3"><label>Salle</label><input type="text" name="salle" class="form-control" value="<?= esc($emploi['salle']) ?>" required></div>
    <div class="mb-3">
        <label>Jour</label>
        <select name="jour" class="form-control">
            <?php foreach (['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'] as $jour): ?>
                <option value="<?= $jour ?>" <?= $jour == $emploi['jour'] ? 'selected' : '' ?>><?= $jour ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3"><label>Heure début</label><input type="time" name="heure_debut" class="form-control" value="<?= $emploi['heure_debut'] ?>" required></div>
    <div class="mb-3"><label>Heure fin</label><input type="time" name="heure_fin" class="form-control" value="<?= $emploi['heure_fin'] ?>" required></div>
    <div class="mb-3">
        <label>Type cours</label>
        <select name="type_cours" class="form-control">
            <option value="CM" <?= $emploi['type_cours'] == 'CM' ? 'selected' : '' ?>>Cours</option>
            <option value="TD" <?= $emploi['type_cours'] == 'TD' ? 'selected' : '' ?>>TD</option>
            <option value="TP" <?= $emploi['type_cours'] == 'TP' ? 'selected' : '' ?>>TP</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/emplois" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>