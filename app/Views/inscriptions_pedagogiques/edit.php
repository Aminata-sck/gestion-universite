<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Modifier une inscription pédagogique</h2>
<form method="post" action="/inscriptions-pedagogiques/update/<?= $inscription['id'] ?>">
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
        <label>EC</label>
        <select name="ec_id" class="form-select" required>
    <?php foreach ($ecs as $ec): ?>
        <option value="<?= $ec['id'] ?>" <?= $ec['id'] == $inscription['ec_id'] ? 'selected' : '' ?>>
            <?= esc($ec['formation_type'] . ' ' . $ec['formation_annee'] . ' - ' . $ec['ue_libelle'] . ' - ' . $ec['libelle']) ?>
        </option>
    <?php endforeach; ?>
</select>
    </div>
    <div class="mb-3">
        <label>Semestre</label>
        <input type="number" name="semestre" class="form-control" value="<?= $inscription['semestre'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Statut</label>
        <select name="statut" class="form-control">
            <option value="en_attente" <?= $inscription['statut'] == 'en_attente' ? 'selected' : '' ?>>En attente</option>
            <option value="validée" <?= $inscription['statut'] == 'validée' ? 'selected' : '' ?>>Validée</option>
            <option value="refusée" <?= $inscription['statut'] == 'refusée' ? 'selected' : '' ?>>Refusée</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Date inscription</label>
        <input type="date" name="date_inscription" class="form-control" value="<?= $inscription['date_inscription'] ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/inscriptions-pedagogiques" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>