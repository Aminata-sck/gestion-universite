<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-file-lines text-primary me-2"></i> Mon relevé de notes</h1>
    <?php if (!empty($total_credits)): ?>
        <span class="badge bg-info rounded-pill px-3 py-2 fs-6">
            <i class="fa-regular fa-certificate me-1"></i> Total crédits : <?= $total_credits ?> / 60
        </span>
    <?php endif; ?>
</div>

<?php if (!empty($semestres)): ?>
    <?php foreach ($semestres as $semestre => $sem): ?>
        <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
            <!-- En-tête du semestre -->
            <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <i class="fa-regular fa-layer-group me-2"></i>
                    <strong>Semestre <?= $semestre ?></strong>
                </div>
                <div>
                    <span class="badge bg-light text-dark me-2">
                        <i class="fa-regular fa-flag me-1"></i> <?= $sem['mention'] ?>
                    </span>
                    <span class="badge bg-light text-dark">
                        <i class="fa-regular fa-calculator me-1"></i> Moyenne : <?= $sem['moyenne_generale'] ?>/20
                    </span>
                </div>
            </div>
            <div class="card-body">
                <!-- Barre de progression des crédits -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between small">
                        <span><i class="fa-regular fa-credit-card me-1"></i> Crédits obtenus</span>
                        <span><?= $sem['credits_obtenus'] ?> / 30</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: <?= ($sem['credits_obtenus'] / 30) * 100 ?>%;" 
                             aria-valuenow="<?= $sem['credits_obtenus'] ?>" 
                             aria-valuemin="0" aria-valuemax="30">
                        </div>
                    </div>
                </div>

                <!-- Tableau des UE -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>UE</th>
                                <th class="text-center">Crédits</th>
                                <th class="text-center">Coeff</th>
                                <th class="text-center">Moyenne UE</th>
                                <th class="text-center">Validée</th>
                                <th class="text-center">Détails</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sem['ues'] as $ue): ?>
                                <tr>
                                    <td><?= esc($ue['libelle']) ?></td>
                                    <td class="text-center"><?= $ue['credits'] ?></td>
                                    <td class="text-center"><?= $ue['ue_coeff'] ?></td>
                                    <td class="text-center">
                                        <?php if ($ue['moyenne'] !== null): ?>
                                            <span class="badge <?= $ue['moyenne'] >= 10 ? 'bg-success' : 'bg-danger' ?> rounded-pill fs-6 px-3">
                                                <?= $ue['moyenne'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($ue['valide']): ?>
                                            <span class="badge bg-success"><i class="fa-regular fa-circle-check me-1"></i> Oui</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><i class="fa-regular fa-circle-xmark me-1"></i> Non</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-outline-info btn-sm" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#details_<?= $ue['id'] ?>"
                                                aria-expanded="false">
                                            <i class="fa-regular fa-eye me-1"></i> Détails
                                        </button>
                                        <div class="collapse mt-2" id="details_<?= $ue['id'] ?>">
                                            <div class="card card-body bg-light p-2">
                                                <table class="table table-sm table-bordered mb-0">
                                                    <thead class="table-secondary">
                                                        <tr>
                                                            <th>EC</th>
                                                            <th>Note</th>
                                                            <th>Coeff</th>
                                                            <th>Type</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($ue['notes'] as $note): ?>
                                                            <tr>
                                                                <td><?= esc($note['ec_libelle']) ?></td>
                                                                <td><?= $note['note'] ?></td>
                                                                <td><?= $note['ec_coeff'] ?></td>
                                                                <td><?= $note['type'] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Récapitulatif final -->
    <div class="alert alert-info border-0 rounded-4 d-flex align-items-center gap-3">
        <i class="fa-regular fa-certificate fa-2x"></i>
        <div>
            <strong>Total des crédits acquis : <?= $total_credits ?></strong> (sur 60 pour l'année)
            <?php if ($total_credits >= 60): ?>
                <span class="badge bg-success ms-2"><i class="fa-regular fa-trophy me-1"></i> Félicitations, année validée !</span>
            <?php elseif ($total_credits >= 30): ?>
                <span class="badge bg-warning text-dark ms-2">En bonne voie</span>
            <?php else: ?>
                <span class="badge bg-danger ms-2">Attention, risque de non-validation</span>
            <?php endif; ?>
        </div>
    </div>

<?php else: ?>
    <div class="text-center text-muted py-5">
        <i class="fa-regular fa-circle-info fa-3x d-block mb-3"></i>
        <p class="fs-5">Aucune note disponible pour le moment.</p>
        <p class="small">Les notes apparaîtront dès qu'elles seront saisies par vos enseignants.</p>
    </div>
<?php endif; ?>

<div class="mt-4">
    <a href="/student/dashboard" class="btn btn-secondary">
        <i class="fa-regular fa-arrow-left me-2"></i> Retour au tableau de bord
    </a>
</div>

<!-- Style personnalisé -->
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
    }
    .card {
        transition: box-shadow 0.2s ease;
    }
    .card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
    }
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
    }
    .progress-bar {
        border-radius: 10px;
        transition: width 0.6s ease;
    }
    .btn-outline-info:hover {
        color: white;
    }
</style>

<?= $this->endSection() ?>