<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Modifier un utilisateur</h2>
<form method="post" action="/users/update/<?= $user['id'] ?>">
    <?= csrf_field() ?>
    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required></div>
    <div class="mb-3"><label>Mot de passe (laisser vide pour ne pas changer)</label><input type="password" name="password" class="form-control"></div>
    <div class="mb-3"><label>Nom</label><input type="text" name="nom" class="form-control" value="<?= esc($user['nom']) ?>" required></div>
    <div class="mb-3"><label>Prénom</label><input type="text" name="prenom" class="form-control" value="<?= esc($user['prenom']) ?>" required></div>
    <div class="mb-3"><label>Téléphone</label><input type="text" name="telephone" class="form-control" value="<?= esc($user['telephone']) ?>"></div>
    <div class="mb-3">
        <label>Statut</label>
        <select name="statut" class="form-control">
            <option value="1" <?= $user['statut'] == 1 ? 'selected' : '' ?>>Actif</option>
            <option value="0" <?= $user['statut'] == 0 ? 'selected' : '' ?>>Inactif</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Profil RBAC</label>
        <select name="profil_id" class="form-control">
            <option value="">Choisir un profil</option>
            <?php foreach ($profils as $profil): ?>
                <option value="<?= $profil['id'] ?>" <?= (string) ($user['profil_id'] ?? '') === (string) $profil['id'] ? 'selected' : '' ?>><?= esc($profil['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/users" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>
