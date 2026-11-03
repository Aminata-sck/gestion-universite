<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-circle-xmark me-2"></i> Absences</h1>
    <div>
        <?php if (hasPermission('absences', 'add')): ?><a href="/absences/new" class="btn btn-primary">
            <i class="fa-regular fa-plus me-2"></i> Ajouter
        </a>
        <a href="/absences/bulk-add" class="btn btn-success">
            <i class="fa-regular fa-users me-2"></i> Saisie groupée
        </a><?php endif; ?>
    </div>
</div>

<?php if (!empty($grouped)): ?>
    <?php foreach ($grouped as $group): ?>
        <div class="card shadow-sm border-0 rounded-4 mb-3">
            <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <strong>
                    <i class="fa-regular fa-calendar me-2"></i>
                    <?= esc($group['cours_libelle']) ?>
                    <span class="badge bg-secondary ms-2"><?= $group['jour'] ?> <?= date('H:i', strtotime($group['heure_debut'])) ?></span>
                    <span class="badge bg-info ms-1"><?= $group['salle'] ?></span>
                </strong>
                <span class="badge bg-secondary"><?= count($group['absences']) ?> absence(s)</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Étudiant</th>
                                <th>Date</th>
                                <th>Justifiée</th>
                                <th>Motif</th>
                                <th>Enseignant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($group['absences'] as $a): ?>
                                <tr>
                                    <td><?= esc($a['etudiant_nom']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($a['date_absence'])) ?></td>
                                    <td>
                                        <?php if ($a['justifie']): ?>
                                            <span class="badge bg-success"><i class="fa-regular fa-circle-check me-1"></i> Oui</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><i class="fa-regular fa-circle-xmark me-1"></i> Non</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($a['motif']) ?: '-' ?></td>
                                    <td><?= esc($a['enseignant_nom']) ?></td>
                                    <td>
                                        <?php if (hasPermission('absences', 'edit')): ?><a href="/absences/edit/<?= $a['id'] ?>" class="btn btn-sm btn-primary" title="Modifier"><i class="fa-regular fa-pen-to-square"></i></a><?php endif; ?>
                                        <?php if (hasPermission('absences', 'delete')): ?><a href="/absences/delete/<?= $a['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Confirmer ?')"><i class="fa-regular fa-trash-can"></i></a><?php endif; ?>
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
        <p class="fs-5">Aucune absence enregistrée.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
