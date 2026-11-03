<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
    <div>
        <a href="/ues" class="text-decoration-none small"><i class="fa-regular fa-arrow-left me-1"></i>Retour aux UE</a>
        <h1 class="h2 fw-bold mt-2 mb-1"><?= esc($ue['libelle']) ?></h1>
        <p class="text-muted mb-0"><?= esc($ue['formation_type'] . ' ' . $ue['formation_annee'] . ' - ' . $ue['formation_libelle']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <?php if (hasPermission('ues', 'edit')): ?><a href="/ues/edit/<?= $ue['id'] ?>" class="btn btn-outline-primary"><i class="fa-regular fa-pen-to-square me-2"></i>Modifier l'UE</a><?php endif; ?>
        <?php if (hasPermission('ecs', 'add')): ?><a href="/ecs/ue/<?= $ue['id'] ?>/new" class="btn btn-primary"><i class="fa-regular fa-plus me-2"></i>Ajouter un EC</a><?php endif; ?>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card border-0 bg-light h-100"><div class="card-body"><small class="text-muted d-block">Code</small><strong><?= esc($ue['code']) ?></strong></div></div></div>
    <div class="col-md-3"><div class="card border-0 bg-light h-100"><div class="card-body"><small class="text-muted d-block">Semestre</small><strong>S<?= esc($ue['semestre']) ?></strong></div></div></div>
    <div class="col-md-3"><div class="card border-0 bg-light h-100"><div class="card-body"><small class="text-muted d-block">Crédits</small><strong><?= esc($ue['credits_ects']) ?> ECTS</strong></div></div></div>
    <div class="col-md-3"><div class="card border-0 bg-light h-100"><div class="card-body"><small class="text-muted d-block">Coefficient</small><strong><?= esc($ue['coefficient']) ?></strong></div></div></div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Éléments constitutifs <span class="badge bg-secondary ms-1"><?= count($ecs) ?></span></h2>
        <small class="text-muted">Chaque EC ci-dessous est rattaché à cette UE.</small>
    </div>
    <div class="card-body p-0">
        <?php if ($ecs): ?>
            <div class="table-responsive"><table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>Libellé</th><th>Type</th><th>Coefficient</th><th>Enseignant</th><th class="text-center">Actions</th></tr></thead>
                <tbody><?php foreach ($ecs as $ec): ?><tr>
                    <td class="fw-semibold"><?= esc($ec['libelle']) ?></td><td><span class="badge bg-secondary"><?= esc($ec['type']) ?></span></td><td><?= esc($ec['coefficient']) ?></td><td><?= esc($ec['enseignant_nom'] ?: 'Non affecté') ?></td>
                    <td class="text-center"><?php if (hasPermission('ecs', 'edit')): ?><a class="btn btn-sm btn-outline-primary" href="/ecs/edit/<?= $ec['id'] ?>" title="Modifier"><i class="fa-regular fa-pen-to-square"></i></a><?php endif; ?> <?php if (hasPermission('ecs', 'delete')): ?><a class="btn btn-sm btn-outline-danger" href="/ecs/delete/<?= $ec['id'] ?>" title="Supprimer" onclick="return confirm('Confirmer la suppression de cet EC ?')"><i class="fa-regular fa-trash-can"></i></a><?php endif; ?></td>
                </tr><?php endforeach; ?></tbody>
            </table></div>
        <?php else: ?><div class="text-center text-muted py-5"><i class="fa-regular fa-cubes fa-2x d-block mb-2"></i>Aucun EC n'est encore rattaché à cette UE.</div><?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
