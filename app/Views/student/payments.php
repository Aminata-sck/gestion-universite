<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-credit-card me-2"></i> Mes paiements</h1>
</div>

<!-- Statistiques -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success shadow-sm border-0 rounded-4">
            <div class="card-body">
                <h6 class="card-title"><i class="fa-regular fa-circle-check me-2"></i>Total payé</h6>
                <p class="card-text display-6 fw-bold"><?= number_format($totalPaye, 0, ',', ' ') ?> FCFA</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-dark bg-warning shadow-sm border-0 rounded-4">
            <div class="card-body">
                <h6 class="card-title"><i class="fa-regular fa-clock me-2"></i>En attente</h6>
                <p class="card-text display-6 fw-bold"><?= number_format($totalEnAttente, 0, ',', ' ') ?> FCFA</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger shadow-sm border-0 rounded-4">
            <div class="card-body">
                <h6 class="card-title"><i class="fa-regular fa-circle-xmark me-2"></i>Annulé</h6>
                <p class="card-text display-6 fw-bold"><?= number_format($totalAnnule, 0, ',', ' ') ?> FCFA</p>
            </div>
        </div>
    </div>
</div>

<!-- Liste des paiements -->
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-light border-0 rounded-top-4">
        <h5 class="mb-0">Historique des paiements</h5>
    </div>
    <div class="card-body p-0">
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
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($paiements)): ?>
                        <?php foreach ($paiements as $p): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($p['description'])): ?>
                                        <span class="badge bg-light text-dark rounded-pill px-3">
                                            <i class="fa-regular fa-calendar me-1"></i>
                                            <?= esc($p['description']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold text-primary"><?= number_format($p['montant'], 0, ',', ' ') ?> FCFA</td>
                                <td><?= date('d/m/Y', strtotime($p['date_paiement'])) ?></td>
                                <td>
                                    <?php
                                    $modeClass = match($p['mode']) {
                                        'especes'   => 'secondary',
                                        'cheque'    => 'primary',
                                        'virement'  => 'info',
                                        'en_ligne'  => 'success',
                                        default     => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $modeClass ?> bg-opacity-10 text-<?= $modeClass ?> rounded-pill px-3">
                                        <?= ucfirst($p['mode']) ?>
                                    </span>
                                </td>
                                <td><code class="bg-light px-2 py-1 rounded text-dark"><?= esc($p['reference']) ?></code></td>
                                <td>
                                    <?php
                                    $statutClass = match($p['statut']) {
                                        'payé'      => 'success',
                                        'en_attente'=> 'warning',
                                        'annulé'    => 'danger',
                                        default     => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $statutClass ?> bg-opacity-10 text-<?= $statutClass ?> rounded-pill px-3">
                                        <?= ucfirst($p['statut']) ?>
                                    </span>
                                </td>
                                <td><?= $p['echeance'] ? date('d/m/Y', strtotime($p['echeance'])) : '<span class="text-muted">-</span>' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fa-regular fa-circle-info fa-2x d-block mb-2"></i>
                                Aucun paiement enregistré.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="/student/dashboard" class="btn btn-secondary">
        <i class="fa-regular fa-arrow-left me-2"></i> Retour au tableau de bord
    </a>
</div>

<?= $this->endSection() ?>