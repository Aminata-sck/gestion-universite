<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1>
        <i class="fa-regular fa-users text-primary me-2"></i>
        Étudiants de <?= esc($ec['libelle']) ?>
    </h1>
    <span class="badge bg-primary rounded-pill px-3 py-2">
        <i class="fa-regular fa-user-group me-1"></i> <?= count($etudiants) ?> étudiant(s)
    </span>
</div>

<?php if (!empty($etudiants)): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etudiants as $etudiant): ?>
                    <tr>
                        <td>
                            <span class="fw-semibold"><?= esc($etudiant['nom']) ?></span>
                        </td>
                        <td><?= esc($etudiant['prenom']) ?></td>
                        <td>
                            <a href="mailto:<?= esc($etudiant['email']) ?>" class="text-decoration-none">
                                <i class="fa-regular fa-envelope text-muted me-1"></i>
                                <?= esc($etudiant['email']) ?>
                            </a>
                        </td>
                        <td>
                            <?php if (!empty($etudiant['telephone'])): ?>
                                <i class="fa-regular fa-phone text-muted me-1"></i>
                                <?= esc($etudiant['telephone']) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="text-center text-muted py-5">
        <i class="fa-regular fa-circle-info fa-3x d-block mb-3 text-primary"></i>
        <p class="fs-5">Aucun étudiant inscrit à ce cours pour le moment.</p>
        <p class="small">Les étudiants apparaîtront ici dès qu'ils s'inscriront à cet EC.</p>
    </div>
<?php endif; ?>

<div class="mt-4">
    <a href="/teacher/courses" class="btn btn-secondary">
        <i class="fa-regular fa-arrow-left me-2"></i> Retour à mes cours
    </a>
</div>

<!-- Style supplémentaire -->
<style>
    .table th {
        font-weight: 600;
        white-space: nowrap;
    }
    .table tbody tr {
        transition: background-color 0.15s ease;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-weight: 500;
    }
    .btn {
        font-weight: 500;
    }
</style>

<?= $this->endSection() ?>