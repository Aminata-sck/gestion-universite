<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fa-regular fa-layer-group me-2"></i> Choisir un EC</h1>
    <a href="/notes" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <?php if (!empty($ecs)): ?>
            <p class="text-muted">Sélectionnez un EC pour saisir les notes de tous les étudiants inscrits :</p>
            <div class="row g-3">
                <?php foreach ($ecs as $ec): ?>
                    <div class="col-md-6 col-lg-4">
                        <a href="/notes/bulk-add?ec_id=<?= $ec['id'] ?>" class="text-decoration-none">
                            <div class="card h-100 shadow-sm border-0 rounded-4 hover-card">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-primary"><?= esc($ec['libelle']) ?></h5>
                                    <p class="card-text small text-muted">UE : <?= esc($ec['ue_libelle']) ?></p>
                                    <p class="card-text small text-muted">Type : <?= $ec['type'] ?> | Coeff : <?= $ec['coefficient'] ?></p>
                                    <span class="mt-auto btn btn-outline-primary btn-sm align-self-start">
                                        <i class="fa-regular fa-arrow-right me-2"></i>Saisir
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">Aucun EC disponible. Veuillez d'abord créer des EC.</p>
        <?php endif; ?>
    </div>
</div>

<style>
    .hover-card {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
    }
</style>

<?= $this->endSection() ?>