<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <h1 class="h2 fw-bold">
        <i class="fa-regular fa-credit-card text-primary me-2"></i>
        Paiements
    </h1>
    <div class="d-flex flex-wrap gap-2">
        <?php if (hasPermission('paiements', 'add')): ?><a href="/paiements/new" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
            <i class="fa-regular fa-plus me-2"></i>Ajouter
        </a>
        <a href="/paiements/bulk-add" class="btn btn-success rounded-pill px-4 py-2 shadow-sm">
            <i class="fa-regular fa-users me-2"></i>Paiement groupé
        </a><?php endif; ?>
    </div>
</div>

<?php if (!empty($grouped)): ?>
    <?php foreach ($grouped as $group): ?>
        <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden transition-card">
            <div class="card-header bg-gradient-to-r from-light to-white border-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-regular fa-building-columns fs-4 text-primary"></i>
                    <span class="fw-bold fs-5"><?= esc($group['formation_nom'] ?? 'Sans formation') ?></span>
                    <?php if (!empty($group['formation_type'])): ?>
                        <span class="badge bg-<?= $group['formation_type'] == 'Licence' ? 'primary' : ($group['formation_type'] == 'Master' ? 'info' : 'secondary') ?> rounded-pill px-3">
                            <?= esc($group['formation_type']) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">
                    <i class="fa-regular fa-receipt me-1"></i>
                    <?= count($group['paiements'] ?? []) ?> paiement(s)
                </span>
            </div>
            <div class="card-body p-0">
                <div class="accordion" id="accordionPaiements-<?= $group['formation_id'] ?>">
                    <?php 
                    // Regrouper les paiements par étudiant au sein de cette formation
                    $etudiants = [];
                    foreach ($group['paiements'] as $p) {
                        $key = $p['etudiant_id'] . '|' . $p['etudiant_nom'];
                        if (!isset($etudiants[$key])) {
                            $etudiants[$key] = [
                                'id'   => $p['etudiant_id'],
                                'nom'  => $p['etudiant_nom'],
                                'paiements' => []
                            ];
                        }
                        $etudiants[$key]['paiements'][] = $p;
                    }
                    $i = 0;
                    foreach ($etudiants as $etudiant): 
                        $i++;
                        $collapseId = 'collapse_' . $group['formation_id'] . '_' . $etudiant['id'];
                    ?>
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="heading_<?= $collapseId ?>">
                                <button class="accordion-button <?= $i > 1 ? 'collapsed' : '' ?> bg-light bg-opacity-50" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>" aria-expanded="<?= $i === 1 ? 'true' : 'false' ?>" aria-controls="<?= $collapseId ?>">
                                    <div class="d-flex justify-content-between w-100 me-3">
                                        <span class="fw-semibold"><i class="fa-regular fa-user me-2"></i><?= esc($etudiant['nom']) ?></span>
                                        <span class="badge bg-secondary rounded-pill"><?= count($etudiant['paiements']) ?> paiement(s)</span>
                                    </div>
                                </button>
                            </h2>
                            <div id="<?= $collapseId ?>" class="accordion-collapse collapse <?= $i === 1 ? 'show' : '' ?>" aria-labelledby="heading_<?= $collapseId ?>" data-bs-parent="#accordionPaiements-<?= $group['formation_id'] ?>">
                                <div class="accordion-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light bg-opacity-50">
                                                <tr>
                                                    <th>Période</th>
                                                    <th>Montant</th>
                                                    <th>Date paiement</th>
                                                    <th>Mode</th>
                                                    <th>Référence</th>
                                                    <th>Statut</th>
                                                    <th>Échéance</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($etudiant['paiements'] as $p): ?>
                                                    <tr>
                                                        <td>
                                                            <?php
                                                            $periode = $p['description'] ?? null;
                                                            if (empty($periode) && !empty($p['date_paiement'])) {
                                                                $periode = 'Paiement ' . date('F Y', strtotime($p['date_paiement']));
                                                            }
                                                            ?>
                                                            <?php if (!empty($periode)): ?>
                                                                <span class="badge bg-light text-dark rounded-pill px-3">
                                                                    <i class="fa-regular fa-calendar me-1"></i>
                                                                    <?= esc($periode) ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="text-muted">-</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="fw-bold text-primary"><?= number_format($p['montant'] ?? 0, 0, ',', ' ') ?> FCFA</td>
                                                        <td><span class="badge bg-light text-dark rounded-pill px-3"><?= date('d/m/Y', strtotime($p['date_paiement'] ?? 'now')) ?></span></td>
                                                        <td>
                                                            <?php
                                                            $mode = $p['mode'] ?? 'especes';
                                                            $modeClass = match($mode) {
                                                                'especes'   => 'secondary',
                                                                'cheque'    => 'primary',
                                                                'virement'  => 'info',
                                                                'en_ligne'  => 'success',
                                                                default     => 'secondary'
                                                            };
                                                            ?>
                                                            <span class="badge bg-<?= $modeClass ?> bg-opacity-10 text-<?= $modeClass ?> rounded-pill px-3">
                                                                <i class="fa-regular fa-<?= $mode == 'especes' ? 'coins' : ($mode == 'cheque' ? 'file-invoice' : ($mode == 'virement' ? 'arrows-spin' : 'globe')) ?> me-1"></i>
                                                                <?= ucfirst($mode) ?>
                                                            </span>
                                                        </td>
                                                        <td><code class="bg-light px-2 py-1 rounded text-dark"><?= esc($p['reference'] ?? '') ?></code></td>
                                                        <td>
                                                            <?php
                                                            $statut = $p['statut'] ?? 'en_attente';
                                                            $statutClass = match($statut) {
                                                                'payé'      => 'success',
                                                                'en_attente'=> 'warning',
                                                                'annulé'    => 'danger',
                                                                default     => 'secondary'
                                                            };
                                                            ?>
                                                            <span class="badge bg-<?= $statutClass ?> bg-opacity-10 text-<?= $statutClass ?> rounded-pill px-3">
                                                                <?= ucfirst($statut) ?>
                                                            </span>
                                                        </td>
                                                        <td><?= !empty($p['echeance']) ? date('d/m/Y', strtotime($p['echeance'])) : '<span class="text-muted">-</span>' ?></td>
                                                        <td>
                                                            <div class="d-flex gap-1">
                                                                <?php if (hasPermission('paiements', 'edit')): ?><a href="/paiements/edit/<?= $p['id'] ?? 0 ?>" class="btn btn-sm btn-outline-primary rounded-circle" title="Modifier"><i class="fa-regular fa-pen-to-square"></i></a><?php endif; ?>
                                                                <?php if (hasPermission('paiements', 'delete')): ?><a href="/paiements/delete/<?= $p['id'] ?? 0 ?>" class="btn btn-sm btn-outline-danger rounded-circle" title="Supprimer" onclick="return confirm('Confirmer la suppression ?')"><i class="fa-regular fa-trash-can"></i></a><?php endif; ?>
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
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="text-center py-5">
        <div class="text-primary mb-3">
            <i class="fa-regular fa-circle-info fa-5x opacity-25"></i>
        </div>
        <h4 class="text-muted">Aucun paiement enregistré</h4>
        <p class="text-muted">Commencez par ajouter un paiement.</p>
        <a href="/paiements/new" class="btn btn-primary rounded-pill px-4 mt-2">
            <i class="fa-regular fa-plus me-2"></i>Ajouter un paiement
        </a>
    </div>
<?php endif; ?>

<style>
    .transition-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.08) !important;
    }
    .bg-gradient-to-r {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
    }
    .table th {
        font-weight: 600;
        color: #495057;
        border-bottom-width: 2px;
        white-space: nowrap;
    }
    .table td {
        vertical-align: middle;
        padding: 0.75rem 0.5rem;
    }
    .btn-outline-primary,
    .btn-outline-danger {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-width: 1.5px;
        transition: all 0.15s ease;
    }
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: #fff;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.2);
    }
    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #fff;
        box-shadow: 0 0 0 3px rgba(220,53,69,0.2);
    }
    .rounded-pill {
        border-radius: 50rem !important;
    }
    .badge.bg-opacity-10 {
        background-color: rgba(0,0,0,0.06) !important;
    }
    code {
        font-size: 0.85rem;
        background-color: #f1f3f5;
        padding: 0.15rem 0.5rem;
        border-radius: 0.25rem;
        color: #212529;
    }
    .accordion-button:not(.collapsed) {
        background-color: #e9ecef !important;
        color: #0d6efd !important;
    }
    .accordion-button:focus {
        box-shadow: none !important;
    }
    .accordion-button {
        padding: 0.75rem 1.25rem;
        font-weight: 500;
    }
    @media (max-width: 768px) {
        .btn {
            font-size: 0.9rem;
            padding: 0.4rem 1rem;
        }
        .card-header {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .table th, .table td {
            white-space: nowrap;
        }
    }
</style>

<?= $this->endSection() ?>
