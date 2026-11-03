<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-star me-2"></i> Notes</h1>
    <div>
        <?php if (hasPermission('notes', 'add')): ?><a href="/notes/new" class="btn btn-primary">
            <i class="fa-regular fa-plus me-2"></i> Ajouter une note
        </a>
        <a href="/notes/bulk-select" class="btn btn-success">
            <i class="fa-regular fa-layer-group me-2"></i> Saisie groupée
        </a><?php endif; ?>
    </div>
</div>

<?php if (!empty($grouped)): ?>
    <?php foreach ($grouped as $group): ?>
        <div class="card shadow-sm border-0 rounded-4 mb-3">
            <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                <strong><i class="fa-regular fa-user me-2"></i> <?= esc($group['etudiant_nom']) ?></strong>
                <span class="badge bg-secondary"><?= count($group['notes']) ?> note(s)</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>EC</th>
                                <th>Type</th>
                                <th>Note</th>
                                <th>Coefficient</th>
                                <th>Enseignant</th>
                                <th>Session</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($group['notes'] as $n): ?>
                                <tr>
                                    <td><?= esc($n['ec_libelle']) ?></td>
                                    <td>
                                        <?php
                                        $typeLabel = match($n['type']) {
                                            'cc'        => 'Contrôle continu',
                                            'examen'    => 'Examen',
                                            'rattrapage'=> 'Rattrapage',
                                            default     => $n['type']
                                        };
                                        ?>
                                        <span class="badge bg-info text-dark"><?= $typeLabel ?></span>
                                    </td>
                                    <td class="fw-bold <?= $n['note'] >= 10 ? 'text-success' : 'text-danger' ?>">
                                        <?= number_format($n['note'], 2) ?>
                                    </td>
                                    <td><?= $n['coefficient'] ?></td>
                                    <td><?= esc($n['enseignant_nom']) ?></td>
                                    <td><?= esc($n['session'] ?? '-') ?></td>
                                    <td>
                                        <?php if (hasPermission('notes', 'edit')): ?><a href="/notes/edit/<?= $n['id'] ?>" class="btn btn-sm btn-primary" title="Modifier"><i class="fa-regular fa-pen-to-square"></i></a><?php endif; ?>
                                        <?php if (hasPermission('notes', 'delete')): ?><a href="/notes/delete/<?= $n['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Confirmer ?')"><i class="fa-regular fa-trash-can"></i></a><?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="text-center text-muted py-5">
        <i class="fa-regular fa-circle-info fa-3x d-block mb-3"></i>
        <p class="fs-5">Aucune note enregistrée pour le moment.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
