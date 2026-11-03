<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Ajouter un utilisateur</h2>
<form method="post" action="/users">
    <?= csrf_field() ?>
    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
    <div class="mb-3"><label>Mot de passe</label><input type="password" name="password" class="form-control" required></div>
    <div class="mb-3"><label>Nom</label><input type="text" name="nom" class="form-control" required></div>
    <div class="mb-3"><label>Prénom</label><input type="text" name="prenom" class="form-control" required></div>
    <div class="mb-3"><label>Téléphone</label><input type="text" name="telephone" class="form-control"></div>
    <div class="mb-3">
        <label>Statut</label>
        <select name="statut" class="form-control">
            <option value="1">Actif</option>
            <option value="0">Inactif</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Profil RBAC</label>
        <select name="profil_id" class="form-control">
            <option value="">Choisir un profil</option>
            <?php foreach ($profils as $profil): ?>
                <option value="<?= $profil['id'] ?>"><?= esc($profil['nom']) ?></option>
            <?php endforeach; ?>
        </select>
        <small class="text-muted">Ce profil définit tous les menus et actions autorisés pour cet utilisateur.</small>
    </div>
    <button type="submit" class="btn btn-primary">Créer</button>
    <a href="/users" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>
