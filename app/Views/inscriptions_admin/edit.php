<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Modifier une inscription administrative</h2>
<form method="post" action="/inscriptions-admin/update/<?= $inscription['id'] ?>">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label>Étudiant</label>
        <select name="etudiant_id" class="form-control" required>
            <?php foreach ($etudiants as $e): ?>
                <option value="<?= $e['id'] ?>" <?= $e['id'] == $inscription['etudiant_id'] ? 'selected' : '' ?>>
                    <?= esc($e['nom'] . ' ' . $e['prenom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
    <label>Formation</label>
    <select name="formation_id" class="form-control" required>
    <?php foreach ($formations as $f): ?>
        <option value="<?= $f['id'] ?>" <?= $f['id'] == $inscription['formation_id'] ? 'selected' : '' ?>>
            <?= esc($f['affichage']) ?>
        </option>
    <?php endforeach; ?>
</select>
</div>
    <div class="mb-3">
        <label>Année universitaire</label>
        <select name="annee_universitaire_id" class="form-control" required>
            <?php foreach ($annees as $a): ?>
                <option value="<?= $a['id'] ?>" <?= $a['id'] == $inscription['annee_universitaire_id'] ? 'selected' : '' ?>>
                    <?= esc($a['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Date inscription</label>
        <input type="date" name="date_inscription" class="form-control" value="<?= $inscription['date_inscription'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Statut</label>
        <select name="statut" class="form-control">
            <option value="en_attente" <?= $inscription['statut'] == 'en_attente' ? 'selected' : '' ?>>En attente</option>
            <option value="validée" <?= $inscription['statut'] == 'validée' ? 'selected' : '' ?>>Validée</option>
            <option value="rejetée" <?= $inscription['statut'] == 'rejetée' ? 'selected' : '' ?>>Rejetée</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/inscriptions-admin" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>