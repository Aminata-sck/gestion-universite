<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Ajouter une absence</h2>
<form method="post" action="/absences">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label>Étudiant</label>
        <select name="etudiant_id" class="form-control" required>
            <?php foreach ($etudiants as $e): ?>
                <option value="<?= $e['id'] ?>"><?= esc($e['nom'] . ' ' . $e['prenom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Cours (emploi du temps)</label>
        <select name="emploi_id" class="form-control" required>
            <?php if (!empty($emplois)): ?>
                <?php foreach ($emplois as $emp): ?>
                    <option value="<?= $emp['id'] ?>">
                        <?= esc($emp['ec_libelle']) ?> - <?= $emp['jour'] ?> <?= date('H:i', strtotime($emp['heure_debut'])) ?> (<?= esc($emp['salle']) ?>)
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">Aucun cours disponible</option>
            <?php endif; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Date absence</label>
        <input type="date" name="date_absence" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Justifiée</label>
        <select name="justifie" class="form-control">
            <option value="0">Non</option>
            <option value="1">Oui</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Motif</label>
        <textarea name="motif" class="form-control" rows="2"></textarea>
    </div>
    <div class="mb-3">
        <label>Enseignant</label>
        <select name="enseignant_id" class="form-control" required>
            <?php foreach ($enseignants as $ens): ?>
                <option value="<?= $ens['id'] ?>"><?= esc($ens['nom'] . ' ' . $ens['prenom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="/absences" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>