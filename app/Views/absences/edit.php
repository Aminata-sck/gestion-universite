<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Modifier une absence</h2>
<form method="post" action="/absences/update/<?= $absence['id'] ?>">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label>Étudiant</label>
        <select name="etudiant_id" class="form-control" required>
            <?php foreach ($etudiants as $e): ?>
                <option value="<?= $e['id'] ?>" <?= $e['id'] == $absence['etudiant_id'] ? 'selected' : '' ?>><?= esc($e['nom'] . ' ' . $e['prenom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Cours (emploi)</label>
        <select name="emploi_id" class="form-control" required>
            <?php foreach ($emplois as $emp): ?>
                <option value="<?= $emp['id'] ?>" <?= $emp['id'] == $absence['emploi_id'] ? 'selected' : '' ?>><?= esc($emp['salle'] . ' - ' . $emp['jour'] . ' ' . $emp['heure_debut']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3"><label>Date absence</label><input type="date" name="date_absence" class="form-control" value="<?= $absence['date_absence'] ?>" required></div>
    <div class="mb-3">
        <label>Justifiée</label>
        <select name="justifie" class="form-control">
            <option value="0" <?= $absence['justifie'] == 0 ? 'selected' : '' ?>>Non</option>
            <option value="1" <?= $absence['justifie'] == 1 ? 'selected' : '' ?>>Oui</option>
        </select>
    </div>
    <div class="mb-3"><label>Motif</label><textarea name="motif" class="form-control" rows="2"><?= esc($absence['motif']) ?></textarea></div>
    <div class="mb-3">
        <label>Enseignant</label>
        <select name="enseignant_id" class="form-control" required>
            <?php foreach ($enseignants as $ens): ?>
                <option value="<?= $ens['id'] ?>" <?= $ens['id'] == $absence['enseignant_id'] ? 'selected' : '' ?>><?= esc($ens['nom'] . ' ' . $ens['prenom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/absences" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>