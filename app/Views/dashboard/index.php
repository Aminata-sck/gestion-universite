<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="mb-1">
            <i class="fa-regular fa-house-chimney text-primary me-2"></i>
            Bienvenue, <span class="text-primary"><?= session()->get('prenom') . ' ' . session()->get('nom') ?></span>
        </h1>
        <p class="text-muted small">
            <i class="fa-regular fa-circle-user me-1"></i>
            Rôle : <span class="badge bg-secondary"><?= session()->get('role') ?></span>
        </p>
    </div>
</div>

<div class="row g-4">
    <!-- Module : Utilisateurs -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'scolarite'): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 rounded-4 text-white bg-gradient-purple">
                <div class="card-body d-flex flex-column">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-regular fa-users fa-2x"></i>
                    </div>
                    <h5 class="card-title fw-bold">Utilisateurs</h5>
                    <p class="card-text small opacity-75 flex-grow-1">Gérer les comptes, importer des étudiants/enseignants.</p>
                    <a href="/users" class="btn btn-light mt-2 align-self-start">
                        <i class="fa-regular fa-arrow-right me-2"></i> Accéder
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Module : Formations -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'scolarite'): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 rounded-4 text-white bg-gradient-blue">
                <div class="card-body d-flex flex-column">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-regular fa-book-open fa-2x"></i>
                    </div>
                    <h5 class="card-title fw-bold">Formations</h5>
                    <p class="card-text small opacity-75 flex-grow-1">Gérer les formations, parcours et durées.</p>
                    <a href="/formations" class="btn btn-light mt-2 align-self-start">
                        <i class="fa-regular fa-arrow-right me-2"></i> Accéder
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Module : UE -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'scolarite'): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 rounded-4 text-white bg-gradient-teal">
                <div class="card-body d-flex flex-column">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-regular fa-layer-group fa-2x"></i>
                    </div>
                    <h5 class="card-title fw-bold">Unités d'Enseignement</h5>
                    <p class="card-text small opacity-75 flex-grow-1">Gérer les UE, coefficients et crédits ECTS.</p>
                    <a href="/ues" class="btn btn-light mt-2 align-self-start">
                        <i class="fa-regular fa-arrow-right me-2"></i> Accéder
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Module : EC -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'scolarite'): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 rounded-4 text-white bg-gradient-orange">
                <div class="card-body d-flex flex-column">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-regular fa-cubes fa-2x"></i>
                    </div>
                    <h5 class="card-title fw-bold">Éléments Constitutifs</h5>
                    <p class="card-text small opacity-75 flex-grow-1">Gérer les EC, affecter les enseignants.</p>
                    <a href="/ecs" class="btn btn-light mt-2 align-self-start">
                        <i class="fa-regular fa-arrow-right me-2"></i> Accéder
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Module : Inscriptions administratives -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'scolarite'): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 rounded-4 text-white bg-gradient-green">
                <div class="card-body d-flex flex-column">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-regular fa-pen-to-square fa-2x"></i>
                    </div>
                    <h5 class="card-title fw-bold">Inscriptions admin</h5>
                    <p class="card-text small opacity-75 flex-grow-1">Valider les dossiers étudiants.</p>
                    <a href="/inscriptions-admin" class="btn btn-light mt-2 align-self-start">
                        <i class="fa-regular fa-arrow-right me-2"></i> Accéder
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Module : Inscriptions pédagogiques -->
    <!-- Supprimé pour les enseignants (condition admin ou scolarite uniquement) -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'scolarite'): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 rounded-4 text-white bg-gradient-pink">
                <div class="card-body d-flex flex-column">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-regular fa-list-check fa-2x"></i>
                    </div>
                    <h5 class="card-title fw-bold">Inscriptions pédagogiques</h5>
                    <p class="card-text small opacity-75 flex-grow-1">Gérer les choix d'UE par les étudiants.</p>
                    <a href="/inscriptions-pedagogiques" class="btn btn-light mt-2 align-self-start">
                        <i class="fa-regular fa-arrow-right me-2"></i> Accéder
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Module : Notes -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'scolarite' || session()->get('role') === 'enseignant'): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 rounded-4 text-white bg-gradient-indigo">
                <div class="card-body d-flex flex-column">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-regular fa-star fa-2x"></i>
                    </div>
                    <h5 class="card-title fw-bold">Notes & Évaluations</h5>
                    <p class="card-text small opacity-75 flex-grow-1">Saisir et consulter les notes.</p>
                    <a href="/notes" class="btn btn-light mt-2 align-self-start">
                        <i class="fa-regular fa-arrow-right me-2"></i> Accéder
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Module : Emplois du temps -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'scolarite' || session()->get('role') === 'enseignant'): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 rounded-4 text-white bg-gradient-cyan">
                <div class="card-body d-flex flex-column">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-regular fa-calendar fa-2x"></i>
                    </div>
                    <h5 class="card-title fw-bold">Emplois du temps</h5>
                    <p class="card-text small opacity-75 flex-grow-1">Gérer les créneaux et salles.</p>
                    <a href="/emplois" class="btn btn-light mt-2 align-self-start">
                        <i class="fa-regular fa-arrow-right me-2"></i> Accéder
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Module : Absences -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'scolarite' || session()->get('role') === 'enseignant'): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 rounded-4 text-white bg-gradient-red">
                <div class="card-body d-flex flex-column">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-regular fa-circle-xmark fa-2x"></i>
                    </div>
                    <h5 class="card-title fw-bold">Absences</h5>
                    <p class="card-text small opacity-75 flex-grow-1">Saisir et suivre les absences.</p>
                    <a href="/absences" class="btn btn-light mt-2 align-self-start">
                        <i class="fa-regular fa-arrow-right me-2"></i> Accéder
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Module : Paiements -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'scolarite'): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 rounded-4 text-white bg-gradient-gold">
                <div class="card-body d-flex flex-column">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-regular fa-credit-card fa-2x"></i>
                    </div>
                    <h5 class="card-title fw-bold">Paiements</h5>
                    <p class="card-text small opacity-75 flex-grow-1">Gérer les frais de scolarité.</p>
                    <a href="/paiements" class="btn btn-light mt-2 align-self-start">
                        <i class="fa-regular fa-arrow-right me-2"></i> Accéder
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Style personnalisé -->
<style>
    .bg-gradient-purple {
        background: linear-gradient(135deg, #6f42c1, #5a32a3);
    }
    .bg-gradient-blue {
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
    }
    .bg-gradient-teal {
        background: linear-gradient(135deg, #20c997, #1a9e7a);
    }
    .bg-gradient-orange {
        background: linear-gradient(135deg, #fd7e14, #e06b0a);
    }
    .bg-gradient-green {
        background: linear-gradient(135deg, #198754, #157347);
    }
    .bg-gradient-pink {
        background: linear-gradient(135deg, #d63384, #b82a6e);
    }
    .bg-gradient-indigo {
        background: linear-gradient(135deg, #6610f2, #520dc2);
    }
    .bg-gradient-cyan {
        background: linear-gradient(135deg, #0dcaf0, #0aa2c0);
    }
    .bg-gradient-red {
        background: linear-gradient(135deg, #dc3545, #b02a37);
    }
    .bg-gradient-gold {
        background: linear-gradient(135deg, #ffc107, #d4a200);
    }

    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15) !important;
    }
    .btn-light {
        background-color: rgba(255,255,255,0.9);
        border: none;
        font-weight: 500;
        transition: background-color 0.2s ease, transform 0.1s ease;
    }
    .btn-light:hover {
        background-color: #ffffff;
        transform: scale(1.03);
    }
    .rounded-circle {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    @media (max-width: 768px) {
        .col-md-4 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
    @media (max-width: 480px) {
        .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>

<?= $this->endSection() ?>