<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-calendar me-2"></i> Emplois du temps</h1>
    <div>
        <?php if (hasPermission('emplois', 'add')): ?><a href="/emplois/bulk-add" class="btn btn-success">
            <i class="fa-regular fa-plus me-2"></i> Ajout groupé
        </a>
        <a href="/emplois/new" class="btn btn-primary">
            <i class="fa-regular fa-plus me-2"></i> Ajouter un cours
        </a><?php endif; ?>
    </div>
</div>

<?php if (!empty($grouped)): ?>
    <?php foreach ($grouped as $group): ?>
        <div class="card shadow-sm border-0 rounded-4 mb-3">
            <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <strong>
                    <i class="fa-regular fa-building-columns me-2"></i>
                    <?= esc($group['formation_nom']) ?>
                    <span class="badge bg-info ms-2"><?= esc($group['formation_type']) ?></span>
                </strong>
                <span class="badge bg-secondary"><?= count($group['cours']) ?> cours</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>EC</th>
                                <th>UE</th>
                                <th>Type</th>
                                <th>Année</th>
                                <th>Classe</th>
                                <th>Salle</th>
                                <th>Jour</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($group['cours'] as $cours): ?>
                                <tr>
                                    <td><?= esc($cours['ec_libelle']) ?></td>
                                    <td><?= esc($cours['ue_libelle']) ?></td>
                                    <td>
                                        <?php
                                        $typeClass = match($cours['ec_type']) {
                                            'CM' => 'bg-primary',
                                            'TD' => 'bg-info text-dark',
                                            'TP' => 'bg-warning text-dark',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $typeClass ?>"><?= esc($cours['ec_type']) ?></span>
                                    </td>
                                   <td><?= esc($e['formation_type'] . ' ' . $e['formation_annee'] . ' - ' . $e['ec_libelle']) ?></td>
                                    <td><?= esc($cours['classe'] ?? '-') ?></td>
                                    <td><?= esc($cours['salle']) ?></td>
                                    <td><?= esc($cours['jour']) ?></td>
                                    <td><?= substr($cours['heure_debut'], 0, 5) ?></td>
                                    <td><?= substr($cours['heure_fin'], 0, 5) ?></td>
                                    <td>
                                        <?php if (hasPermission('emplois', 'edit')): ?><a href="/emplois/edit/<?= $cours['id'] ?>" class="btn btn-sm btn-primary" title="Modifier"><i class="fa-regular fa-pen-to-square"></i></a><?php endif; ?>
                                        <?php if (hasPermission('emplois', 'delete')): ?><a href="/emplois/delete/<?= $cours['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Confirmer ?')"><i class="fa-regular fa-trash-can"></i></a><?php endif; ?>
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
        <p class="fs-5">Aucun cours planifié.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
