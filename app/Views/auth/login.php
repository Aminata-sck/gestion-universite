<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center align-items-center min-vh-75">
    <div class="col-md-6 col-lg-5 col-xl-4">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-5">
                <!-- Logo / Titre -->
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                        <i class="fa-solid fa-graduation-cap fa-3x text-primary"></i>
                    </div>
                    <h2 class="fw-bold text-dark">Connexion</h2>
                    <p class="text-muted small">Connectez-vous à votre espace universitaire</p>
                </div>

                <!-- Formulaire -->
                <form method="post" action="/login">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            <i class="fa-regular fa-envelope text-primary me-1"></i> Email
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa-regular fa-envelope text-muted"></i>
                            </span>
                            <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" 
                                   placeholder="exemple@universite.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">
                            <i class="fa-regular fa-lock text-primary me-1"></i> Mot de passe
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fa-regular fa-lock text-muted"></i>
                            </span>
                            <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" 
                                   placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold py-2">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> Se connecter
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="/forgot-password" class="text-decoration-none small">
                            <i class="fa-regular fa-circle-question me-1"></i> Mot de passe oublié ?
                        </a>
                    </div>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted small mb-0">
                            <i class="fa-regular fa-circle-info me-1"></i>
                            Contactez l'administrateur si vous n'avez pas de compte.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Styles additionnels pour cette page -->
<style>
    .min-vh-75 {
        min-height: 75vh;
    }
    .input-group-text {
        border-right: none;
        background-color: #f8f9fa;
    }
    .form-control {
        border-left: none;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }
    .input-group:focus-within .input-group-text {
        border-color: #0d6efd;
        background-color: #e7f0ff;
    }
    .card {
        backdrop-filter: blur(4px);
        background: rgba(255, 255, 255, 0.95);
    }
    .btn-primary {
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
        border: none;
        transition: transform 0.1s ease;
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
    }
    .btn-primary:active {
        transform: translateY(0);
    }
</style>

<?= $this->endSection() ?>