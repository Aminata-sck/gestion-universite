<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-4">
        <h2>Réinitialiser le mot de passe</h2>
        <form method="post" action="/reset-password">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= $token ?? '' ?>">
            <div class="mb-3">
                <label for="password" class="form-label">Nouveau mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm" class="form-label">Confirmer</label>
                <input type="password" class="form-control" id="confirm" name="confirm" required>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>