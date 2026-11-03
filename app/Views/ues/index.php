<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1 class="h2 fw-bold">
        <i class="fa-regular fa-layer-group text-primary me-2"></i>
        Unités d'Enseignement
    </h1>
    <div class="d-flex flex-wrap gap-2">
        <?php if (hasPermission('ues', 'add')): ?><a href="/ues/new" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
            <i class="fa-regular fa-plus me-2"></i>Ajouter
        </a>
        <a href="/ues/bulk-add" class="btn btn-success rounded-pill px-4 py-2 shadow-sm">
            <i class="fa-regular fa-layer-group me-2"></i>Ajout groupé
        </a><?php endif; ?>
    </div>
</div>

<?php if (!empty($grouped)): ?>
    <div class="accordion" id="ueAccordion">
        <?php $i = 0; foreach ($grouped as $key => $group): ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading_<?= $i ?>">
                    <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?= $i ?>" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="collapse_<?= $i ?>">
                        <strong><?= esc($group['formation_nom']) ?></strong>
                        <span class="badge bg-secondary ms-3"><?= count($group['ues']) ?> UE</span>
                    </button>
                </h2>
                <div id="collapse_<?= $i ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" aria-labelledby="heading_<?= $i ?>" data-bs-parent="#ueAccordion">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Libellé</th>
                                        <th class="text-center">Semestre</th>
                                        <th class="text-center">Crédits ECTS</th>
                                        <th class="text-center">Coefficient</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($group['ues'] as $ue): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">
                                                    <i class="fa-regular fa-hashtag me-1"></i>
                                                    <?= esc($ue['code']) ?>
                                                </span>
                                            </td>
                                            <td class="fw-semibold"><?= esc($ue['libelle']) ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">
                                                    S<?= $ue['semestre'] ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">
                                                    <?= $ue['credits_ects'] ?> ECTS
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">
                                                    <?= $ue['coefficient'] ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="/ues/<?= $ue['id'] ?>" class="btn btn-sm btn-outline-secondary rounded-circle" title="Ouvrir la fiche UE"><i class="fa-regular fa-folder-open"></i></a>
                                                    <?php if (hasPermission('ues', 'edit')): ?><a href="/ues/edit/<?= $ue['id'] ?>" class="btn btn-sm btn-outline-primary rounded-circle" title="Modifier"><i class="fa-regular fa-pen-to-square"></i></a><?php endif; ?>
                                                    <?php if (hasPermission('ues', 'delete')): ?><a href="/ues/delete/<?= $ue['id'] ?>" class="btn btn-sm btn-outline-danger rounded-circle" title="Supprimer" onclick="return confirm('Confirmer la suppression ?')"><i class="fa-regular fa-trash-can"></i></a><?php endif; ?>
                                                </div>
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
    <div class="text-center py-5">
        <div class="text-muted">
            <i class="fa-regular fa-circle-info fa-3x d-block mb-3 text-primary opacity-25"></i>
            <p class="fs-5 fw-semibold">Aucune UE enregistrée</p>
            <p class="small">Commencez par ajouter une unité d'enseignement.</p>
            <a href="/ues/new" class="btn btn-primary rounded-pill px-4 mt-2">
                <i class="fa-regular fa-plus me-2"></i>Ajouter une UE
            </a>
        </div>
    </div>
<?php endif; ?>

<!-- Styles personnalisés -->
<style>
    .accordion-item {
        border: none;
        margin-bottom: 0.5rem;
    }
    .accordion-button {
        background: #f8f9fa;
        border-radius: 0.75rem !important;
        padding: 1rem 1.25rem;
        box-shadow: none;
        font-weight: 500;
    }
    .accordion-button:not(.collapsed) {
        background: #e7f0ff;
        color: #0d6efd;
        box-shadow: none;
    }
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(13,110,253,0.25);
    }
    .accordion-body {
        background: #ffffff;
        border-radius: 0 0 0.75rem 0.75rem;
        padding: 1.25rem;
    }
    .table th {
        font-weight: 600;
        color: #495057;
        border-bottom-width: 2px;
    }
    .table td {
        vertical-align: middle;
        padding: 0.75rem 0.5rem;
    }
    .btn-outline-primary,
    .btn-outline-danger {
        width: 34px;
        height: 34px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-width: 1.5px;
        transition: all 0.2s ease;
    }
    .btn-outline-primary {
        border-color: #d1d5db;
        color: #6b7280;
    }
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
        box-shadow: 0 0 0 4px rgba(13,110,253,0.15);
        transform: translateY(-2px);
    }
    .btn-outline-danger {
        border-color: #d1d5db;
        color: #6b7280;
    }
    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #fff;
        box-shadow: 0 0 0 4px rgba(220,53,69,0.15);
        transform: translateY(-2px);
    }
    .badge {
        font-weight: 500;
        font-size: 0.85rem;
    }
    .badge.bg-opacity-10 {
        background-color: rgba(0,0,0,0.05) !important;
    }
    .badge.bg-info.bg-opacity-10 {
        background-color: rgba(13,202,240,0.15) !important;
        color: #0d6efd !important;
    }
    .badge.bg-success.bg-opacity-10 {
        background-color: rgba(25,135,84,0.12) !important;
        color: #198754 !important;
    }
    .badge.bg-warning.bg-opacity-10 {
        background-color: rgba(255,193,7,0.15) !important;
        color: #d39e00 !important;
    }
    .badge.bg-secondary.bg-opacity-10 {
        background-color: rgba(108,117,125,0.12) !important;
        color: #6c757d !important;
    }
    @media (max-width: 768px) {
        .btn {
            font-size: 0.85rem;
            padding: 0.35rem 1rem;
        }
        .d-flex.justify-content-between {
            flex-direction: column;
            align-items: stretch !important;
        }
        .d-flex.justify-content-between .btn {
            width: 100%;
            justify-content: center;
        }
        .table th, .table td {
            white-space: nowrap;
        }
    }
</style>

<?= $this->endSection() ?>
