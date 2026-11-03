<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-calendar text-primary me-2"></i> Mon emploi du temps</h1>
    <span class="badge bg-primary rounded-pill px-3 py-2">
        <i class="fa-regular fa-clock me-1"></i> <?= count($emplois) ?> cours
    </span>
</div>

<?php if (!empty($emplois)): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Jour</th>
                    <th>Heure début</th>
                    <th>Heure fin</th>
                    <th>EC</th>
                    <th>Classe</th>
                    <th>Salle</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($emplois as $cours): ?>
                    <tr>
                        <td><span class="badge bg-secondary"><?= esc($cours['jour']) ?></span></td>
                        <td><?= date('H:i', strtotime($cours['heure_debut'])) ?></td>
                        <td><?= date('H:i', strtotime($cours['heure_fin'])) ?></td>
                        <td><?= esc($cours['ec_libelle']) ?></td>
                        <td><?= esc($cours['classe']) ?: '<span class="text-muted fst-italic">-</span>' ?></td>
                        <td><?= esc($cours['salle']) ?></td>
                        <td>
                            <?php
                            $typeClass = match($cours['type_cours']) {
                                'CM' => 'bg-primary',
                                'TD' => 'bg-info text-dark',
                                'TP' => 'bg-warning text-dark',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge <?= $typeClass ?>"><?= esc($cours['type_cours']) ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="text-center text-muted py-5">
        <i class="fa-regular fa-calendar-circle-plus fa-3x d-block mb-3 text-primary"></i>
        <p class="fs-5">Aucun cours planifié pour le moment.</p>
        <p class="small">Votre emploi du temps s'affichera ici dès que l'administration aura programmé vos cours.</p>
    </div>
<?php endif; ?>

<div class="mt-4">
    <a href="/teacher/dashboard" class="btn btn-secondary">
        <i class="fa-regular fa-arrow-left me-2"></i> Retour au tableau de bord
    </a>
</div>

<!-- Style supplémentaire -->
<style>
    .table th {
        white-space: nowrap;
        font-weight: 600;
    }
    .table tbody tr {
        transition: background-color 0.15s ease;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .badge {
        font-weight: 500;
    }
</style>

<?= $this->endSection() ?>