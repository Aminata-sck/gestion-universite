<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-4">
        <h2>Mot de passe oublié</h2>
        <form method="post" action="/forgot-password">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer le lien</button>
            <a href="/login" class="btn btn-link">Retour à la connexion</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>