<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-cubes me-2"></i> Éléments Constitutifs (EC)</h1>
    <div>
        <?php if (hasPermission('ecs', 'add')): ?><a href="/ecs/bulk-add" class="btn btn-success">
            <i class="fa-regular fa-plus me-2"></i> Ajout groupé
        </a>
        <a href="/ues" class="btn btn-primary">
            <i class="fa-regular fa-folder-open me-2"></i> Choisir une UE
        </a><?php endif; ?>
    </div>
</div>

<?php if (!empty($grouped_ecs)): ?>
    <div class="accordion" id="ecAccordion">
        <?php $i = 0; foreach ($grouped_ecs as $ueId => $group): ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading_<?= $ueId ?>">
                    <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?= $ueId ?>" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="collapse_<?= $ueId ?>">
<strong>
    <?= esc($group['formation_type'] . ' ' . $group['formation_annee'] . ' - ' . $group['ue_libelle']) ?>
    <span class="badge bg-secondary ms-3"><?= count($group['ecs']) ?> EC</span>
</strong>
                        <span class="badge bg-secondary ms-3"><?= count($group['ecs']) ?> EC</span>
                        <span class="badge bg-info ms-2 ms-md-3">Coeff UE : <?= $group['ue_coefficient'] ?? 1 ?></span>
                    </button>
                </h2>
                <div id="collapse_<?= $ueId ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" aria-labelledby="heading_<?= $ueId ?>" data-bs-parent="#ecAccordion">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Libellé</th>
                                        <th>Coefficient EC</th>
                                        <th>Type</th>
                                        <th>Enseignant</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($group['ecs'] as $ec): ?>
                                        <tr>
                                            <td><?= esc($ec['libelle']) ?></td>
                                            <td><?= $ec['coefficient'] ?></td>
                                            <td>
                                                <?php
                                                $typeClass = match($ec['type']) {
                                                    'CM' => 'bg-primary',
                                                    'TD' => 'bg-info text-dark',
                                                    'TP' => 'bg-warning text-dark',
                                                    default => 'bg-secondary'
                                                };
                                                ?>
                                                <span class="badge <?= $typeClass ?>"><?= esc($ec['type']) ?></span>
                                            </td>
                                            <td><?= esc($ec['enseignant_nom'] ?? 'Non affecté') ?></td>
                                            <td class="text-center">
                                                <?php if (hasPermission('ecs', 'edit')): ?><a href="/ecs/edit/<?= $ec['id'] ?>" class="btn btn-sm btn-primary" title="Modifier"><i class="fa-regular fa-pen-to-square"></i></a><?php endif; ?>
                                                <?php if (hasPermission('ecs', 'delete')): ?><a href="/ecs/delete/<?= $ec['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Confirmer ?')"><i class="fa-regular fa-trash-can"></i></a><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php $i++; endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center text-muted py-5">
        <i class="fa-regular fa-circle-info fa-3x d-block mb-3"></i>
        <p class="fs-5">Aucun EC enregistré pour le moment.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
