<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-edit me-2"></i> Inscriptions administratives</h1>
    <div>
        <?php if (hasPermission('inscriptions_admin', 'add')): ?><a href="/inscriptions-admin/new" class="btn btn-primary">
            <i class="fa-regular fa-plus me-2"></i> Ajouter
        </a>
        <a href="/inscriptions-admin/bulk-add" class="btn btn-success">
            <i class="fa-regular fa-users me-2"></i> Inscription groupée
        </a><?php endif; ?>
    </div>
</div>

<?php if (!empty($grouped)): ?>
    <?php foreach ($grouped as $group): ?>
        <div class="card shadow-sm border-0 rounded-4 mb-3">
            <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <strong>
    <i class="fa-regular fa-building-columns me-2"></i>
    <?= esc($group['formation_type'] . ' ' . $group['formation_annee'] . ' - ' . $group['formation_nom']) ?>
</strong>
                <span class="badge bg-secondary"><?= count($group['etudiants']) ?> étudiant(s)</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Étudiant</th>
                                <th>Année universitaire</th>
                                <th>Date d'inscription</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($group['etudiants'] as $ins): ?>
                                <tr>
                                    <td><?= esc($ins['etudiant_nom']) ?></td>
                                    <td><?= esc($ins['annee_libelle']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($ins['date_inscription'])) ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = match($ins['statut']) {
                                            'validée'   => 'bg-success',
                                            'en_attente'=> 'bg-warning text-dark',
                                            'rejetée'   => 'bg-danger',
                                            default     => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($ins['statut']) ?></span>
                                    </td>
                                    <td>
                                        <?php if (hasPermission('inscriptions_admin', 'edit')): ?><a href="/inscriptions-admin/edit/<?= $ins['id'] ?>" class="btn btn-sm btn-primary" title="Modifier"><i class="fa-regular fa-pen-to-square"></i></a><?php endif; ?>
                                        <?php if (hasPermission('inscriptions_admin', 'delete')): ?><a href="/inscriptions-admin/delete/<?= $ins['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Confirmer ?')"><i class="fa-regular fa-trash-can"></i></a><?php endif; ?>
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
        <p class="fs-5">Aucune inscription administrative.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
