<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-circle-xmark text-danger me-2"></i> Mes absences</h1>
    <span class="badge bg-secondary rounded-pill px-3 py-2">
        <i class="fa-regular fa-clock me-1"></i> <?= count($absences) ?> absence(s)
    </span>
</div>

<?php if (!empty($absences)): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>EC</th>
                    <th>Jour</th>
                    <th>Heure</th>
                    <th>Salle</th>
                    <th>Justifiée</th>
                    <th>Motif</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($absences as $abs): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($abs['date_absence'])) ?></td>
                        <td><?= esc($abs['ec_libelle']) ?></td>
                        <td><span class="badge bg-secondary"><?= esc($abs['jour']) ?></span></td>
                        <td><?= date('H:i', strtotime($abs['heure_debut'])) ?></td>
                        <td><?= esc($abs['salle']) ?></td>
                        <td>
                            <?php if ($abs['justifie']): ?>
                                <span class="badge bg-success"><i class="fa-regular fa-circle-check me-1"></i> Oui</span>
                            <?php else: ?>
                                <span class="badge bg-danger"><i class="fa-regular fa-circle-xmark me-1"></i> Non</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($abs['motif']) ?: '<span class="text-muted fst-italic">-</span>' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="text-center text-muted py-5">
        <i class="fa-regular fa-circle-check fa-3x d-block mb-3 text-success"></i>
        <p class="fs-5">Vous n’avez aucune absence enregistrée.</p>
        <p class="small">Continuez comme ça ! 😊</p>
    </div>
<?php endif; ?>

<div class="mt-4">
    <a href="/student/dashboard" class="btn btn-secondary">
        <i class="fa-regular fa-arrow-left me-2"></i> Retour au tableau de bord
    </a>
</div>

<?= $this->endSection() ?>