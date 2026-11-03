<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-chalkboard-user text-primary me-2"></i> Tableau de bord enseignant</h1>
    <span class="badge bg-primary rounded-pill px-3 py-2">
        <i class="fa-regular fa-user me-1"></i> <?= session()->get('prenom') . ' ' . session()->get('nom') ?>
    </span>
</div>

<div class="row g-4">
    <!-- Carte : Mes cours -->
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0 rounded-4 text-white bg-gradient-primary">
            <div class="card-body d-flex flex-column align-items-start">
                <div class="bg-white bg-opacity-25 rounded-circle p-3 mb-3">
                    <i class="fa-regular fa-book-open fa-2x"></i>
                </div>
                <h5 class="card-title fw-bold">Mes cours</h5>
                <p class="card-text display-6 fw-bold"><?= $total_ec ?></p>
                <p class="small opacity-75">Éléments constitutifs à enseigner</p>
                <a href="/teacher/courses" class="btn btn-light mt-2">
                    <i class="fa-regular fa-arrow-right me-2"></i> Voir mes cours
                </a>
            </div>
        </div>
    </div>

    <!-- Carte : Emploi du temps -->
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0 rounded-4 text-white bg-gradient-success">
            <div class="card-body d-flex flex-column align-items-start">
                <div class="bg-white bg-opacity-25 rounded-circle p-3 mb-3">
                    <i class="fa-regular fa-calendar fa-2x"></i>
                </div>
                <h5 class="card-title fw-bold">Emploi du temps</h5>
                <p class="card-text display-6 fw-bold"><?= count($emplois) ?></p>
                <p class="small opacity-75">Cours planifiés</p>
                <a href="/teacher/schedule" class="btn btn-light mt-2">
                    <i class="fa-regular fa-arrow-right me-2"></i> Consulter
                </a>
            </div>
        </div>
    </div>

    <!-- Carte : Dernières notes -->
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0 rounded-4 text-white bg-gradient-info">
            <div class="card-body d-flex flex-column align-items-start">
                <div class="bg-white bg-opacity-25 rounded-circle p-3 mb-3">
                    <i class="fa-regular fa-star fa-2x"></i>
                </div>
                <h5 class="card-title fw-bold">Dernières notes</h5>
                <p class="card-text display-6 fw-bold"><?= count($dernieres_notes) ?></p>
                <p class="small opacity-75">Notes saisies récemment</p>
            </div>
        </div>
    </div>
</div>

<!-- Dernières notes saisies -->
<?php if (!empty($dernieres_notes)): ?>
    <div class="card shadow-sm border-0 rounded-4 mt-4">
        <div class="card-header bg-light border-0 rounded-top-4 d-flex align-items-center">
            <i class="fa-regular fa-list-check text-primary me-2"></i>
            <span class="fw-semibold">Dernières notes saisies</span>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <?php foreach ($dernieres_notes as $note): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <i class="fa-regular fa-user text-muted me-2"></i>
                            <span class="fw-semibold"><?= esc($note['etudiant_nom']) ?></span>
                            <span class="text-muted mx-2">•</span>
                            <span class="text-muted"><?= esc($note['ec_libelle']) ?></span>
                        </div>
                        <span class="badge <?= $note['note'] >= 10 ? 'bg-success' : 'bg-danger' ?> rounded-pill px-3">
                            <?= $note['note'] ?> / 20
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php else: ?>
    <div class="card shadow-sm border-0 rounded-4 mt-4">
        <div class="card-body text-center text-muted py-4">
            <i class="fa-regular fa-circle-info fa-2x d-block mb-2"></i>
            <p>Aucune note saisie pour le moment.</p>
            <p class="small">Les notes apparaîtront ici dès que vous en saisirez.</p>
        </div>
    </div>
<?php endif; ?>

<!-- Style personnalisé -->
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #198754, #157347);
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #0dcaf0, #0aa2c0);
    }
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    .btn-light {
        background-color: rgba(255,255,255,0.9);
        border: none;
        font-weight: 500;
        transition: background-color 0.2s ease;
    }
    .btn-light:hover {
        background-color: white;
        transform: scale(1.02);
    }
    .list-group-item {
        border-color: rgba(0,0,0,0.05);
        background-color: transparent;
    }
    .list-group-item:first-child {
        border-top: none;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
</style>

<?= $this->endSection() ?>