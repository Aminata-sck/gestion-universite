<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📊 Tableau de bord administrateur</h1>
</div>

<!-- Statistiques -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">👨‍🎓 Étudiants</h5>
                <p class="card-text display-6"><?= $total_etudiants ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">👨‍🏫 Enseignants</h5>
                <p class="card-text display-6"><?= $total_enseignants ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">📚 Formations</h5>
                <p class="card-text display-6"><?= $total_formations ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">🧩 EC</h5>
                <p class="card-text display-6"><?= $total_ec ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">📋 Inscriptions de l’année</div>
            <div class="card-body">
                <p><strong>Inscriptions validées :</strong> <?= $inscriptions_annee ?></p>
                <p><strong>Absences :</strong> <?= $absences_annee ?></p>
                <p><strong>Paiements :</strong> <?= $paiements_annee ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">📝 Dernières inscriptions</div>
            <div class="card-body">
                <?php if (!empty($dernieres_inscriptions)): ?>
                    <ul class="list-group">
                        <?php foreach ($dernieres_inscriptions as $ins): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= esc($ins['etudiant_nom']) ?> - <?= esc($ins['formation_libelle']) ?>
                                <span class="badge bg-primary"><?= $ins['statut'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">Aucune inscription récente.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">⚡ Actions rapides</div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <?php if (hasPermission('formations', 'add')): ?>
                        <a href="/formations/new" class="btn btn-outline-primary">➕ Ajouter une formation</a>
                    <?php endif; ?>
                    <?php if (hasPermission('users', 'add')): ?>
                        <a href="/users/new" class="btn btn-outline-primary">👤 Ajouter un utilisateur</a>
                    <?php endif; ?>
                    <?php if (hasPermission('inscriptions_admin', 'add')): ?>
                        <a href="/inscriptions-admin/new" class="btn btn-outline-primary">📋 Inscription admin</a>
                    <?php endif; ?>
                    <?php if (hasPermission('paiements', 'add')): ?>
                        <a href="/paiements/new" class="btn btn-outline-primary">💰 Ajouter un paiement</a>
                    <?php endif; ?>
                    <a href="/admin/dashboard" class="btn btn-outline-secondary">🔄 Actualiser</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>