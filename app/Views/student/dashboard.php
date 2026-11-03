<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📊 Tableau de bord étudiant</h1>
    <span class="badge bg-primary rounded-pill px-3 py-2">
        <i class="fa-regular fa-user me-1"></i> <?= esc($user['prenom'] . ' ' . $user['nom']) ?>
    </span>
</div>

<div class="row g-4">
    <!-- Carte : Mes informations -->
    <div class="col-md-6">
        <div class="card h-100 shadow-sm border-0 rounded-4">
            <div class="card-header bg-primary bg-opacity-10 border-0 rounded-top-4 d-flex align-items-center">
                <i class="fa-regular fa-address-card text-primary me-2"></i>
                <span class="fw-semibold">Mes informations</span>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fa-regular fa-user text-primary me-2"></i>
                        <strong><?= esc($user['prenom'] . ' ' . $user['nom']) ?></strong>
                    </li>
                    <li class="mb-2">
                        <i class="fa-regular fa-envelope text-primary me-2"></i>
                        <?= esc($user['email']) ?>
                    </li>
                    <?php if ($inscription): ?>
                        <li class="mb-2">
                            <i class="fa-regular fa-graduation-cap text-primary me-2"></i>
                            <?= esc($inscription['formation_libelle']) ?>
                        </li>
                        <li>
                            <i class="fa-regular fa-calendar text-primary me-2"></i>
                            <?= esc($inscription['annee_libelle']) ?>
                        </li>
                    <?php else: ?>
                        <li>
                            <span class="badge bg-warning text-dark">
                                <i class="fa-regular fa-triangle-exclamation me-1"></i> Aucune inscription administrative validée
                            </span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Carte : Prochains cours -->
    <div class="col-md-6">
        <div class="card h-100 shadow-sm border-0 rounded-4">
            <div class="card-header bg-success bg-opacity-10 border-0 rounded-top-4 d-flex align-items-center">
                <i class="fa-regular fa-calendar-check text-success me-2"></i>
                <span class="fw-semibold">Emploi du temps (prochains cours)</span>
            </div>
            <div class="card-body">
                <?php if (!empty($emplois)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach (array_slice($emplois, 0, 5) as $cours): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>
                                    <i class="fa-regular fa-clock text-muted me-2"></i>
                                    <?= esc($cours['ec_libelle']) ?>
                                </span>
                                <span class="badge bg-primary rounded-pill">
                                    <?= $cours['jour'] ?> <?= date('H:i', strtotime($cours['heure_debut'])) ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="/student/schedule" class="btn btn-link text-success mt-2 p-0">
                        Voir tout l'emploi du temps <i class="fa-regular fa-arrow-right ms-1"></i>
                    </a>
                <?php else: ?>
                    <p class="text-muted text-center py-3">
                        <i class="fa-regular fa-circle-info fa-2x d-block mb-2"></i>
                        Aucun cours planifié.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <!-- Dernières notes -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-info bg-opacity-10 border-0 rounded-top-4 d-flex align-items-center">
                <i class="fa-regular fa-star text-info me-2"></i>
                <span class="fw-semibold">Dernières notes</span>
            </div>
            <div class="card-body">
                <?php if (!empty($notes)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($notes as $note): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><?= esc($note['ec_libelle']) ?></span>
                                <span class="badge <?= $note['note'] >= 10 ? 'bg-success' : 'bg-danger' ?> rounded-pill">
                                    <?= $note['note'] ?> / 20
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="/student/grades" class="btn btn-link text-info mt-2 p-0">
                        Voir toutes mes notes <i class="fa-regular fa-arrow-right ms-1"></i>
                    </a>
                <?php else: ?>
                    <p class="text-muted text-center py-3">
                        <i class="fa-regular fa-circle-info fa-2x d-block mb-2"></i>
                        Aucune note disponible.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-secondary bg-opacity-10 border-0 rounded-top-4 d-flex align-items-center">
                <i class="fa-regular fa-bolt text-secondary me-2"></i>
                <span class="fw-semibold">Actions rapides</span>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="/student/grades" class="btn btn-outline-primary d-flex align-items-center justify-content-center">
                    <i class="fa-regular fa-file-lines me-2"></i> Relevé de notes
                </a>
                <a href="/student/schedule" class="btn btn-outline-success d-flex align-items-center justify-content-center">
                    <i class="fa-regular fa-calendar me-2"></i> Emploi du temps
                </a>
                <a href="/student/absences" class="btn btn-outline-danger d-flex align-items-center justify-content-center">
                    <i class="fa-regular fa-circle-xmark me-2"></i> Mes absences
                </a>
            </div>
        </div>
    </div>
    <!-- Carte : Mes paiements -->
<div class="col-md-4 mb-3">
    <div class="card h-100 shadow-sm border-0 rounded-4">
        <div class="card-body d-flex flex-column">
            <div class="bg-info bg-opacity-10 rounded-circle p-3 mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-regular fa-credit-card fa-2x text-info"></i>
            </div>
            <h5 class="card-title fw-bold">Mes paiements</h5>
            <p class="card-text small text-muted flex-grow-1">Consultez l’historique de vos paiements (frais d’inscription et mensualités).</p>
            <a href="/student/payments" class="btn btn-outline-info mt-2 align-self-start">
                <i class="fa-regular fa-arrow-right me-2"></i> Voir mes paiements
            </a>
        </div>
    </div>
</div>
</div>

<!-- Petit style personnalisé -->
<style>
    .card {
        transition: transform 0.15s ease;
    }
    .card:hover {
        transform: translateY(-3px);
    }
    .list-group-item {
        background-color: transparent;
        border-color: rgba(0,0,0,0.05);
    }
    .btn-outline-primary:hover i,
    .btn-outline-success:hover i,
    .btn-outline-danger:hover i {
        color: white;
    }
</style>

<?= $this->endSection() ?>