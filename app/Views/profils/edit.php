<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Modifier le profil</h2>
<form method="post" action="/profils/update/<?= $profil['id'] ?>">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label>Nom du profil</label>
        <input type="text" name="nom" class="form-control" value="<?= old('nom', $profil['nom'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label>Statut</label>
        <select name="statut" class="form-control">
            <option value="1" <?= ($profil['statut'] == 1) ? 'selected' : '' ?>>Actif</option>
            <option value="0" <?= ($profil['statut'] == 0) ? 'selected' : '' ?>>Inactif</option>
        </select>
    </div>

    <h4>Permissions</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Module</th>
                <?php foreach ($actions as $action): ?>
                    <th><?= ucfirst($action) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modules as $module => $label): ?>
                <tr>
                    <td><?= $label ?></td>
                    <?php foreach ($actions as $action): ?>
                        <td>
                            <input type="checkbox" name="<?= $module . '_' . $action ?>" value="1"
                                <?= (isset($permissions[$module][$action]) && $permissions[$module][$action]) ? 'checked' : '' ?>>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/profils" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>