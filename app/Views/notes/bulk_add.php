<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-layer-group me-2"></i> Saisie groupée - <?= esc($ec['libelle']) ?></h1>
    <a href="/notes" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/notes/bulk-store">
            <?= csrf_field() ?>
            <input type="hidden" name="ec_id" value="<?= $ec['id'] ?>">

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="cc">Contrôle continu</option>
                        <option value="examen" selected>Examen</option>
                        <option value="rattrapage">Rattrapage</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Coefficient</label>
                    <input type="number" step="0.1" name="coefficient" class="form-control" value="<?= $ec['coefficient'] ?? 1 ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Session</label>
                    <input type="text" name="session" class="form-control" placeholder="Ex: 2025-2026" value="<?= date('Y') . '-' . (date('Y')+1) ?>">
                </div>
            </div>

            <?php if (!empty($etudiants)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Étudiant</th>
                                <th class="text-center">Note / 20</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($etudiants as $e): ?>
                                <tr>
                                    <td>
                                        <span class="fw-semibold"><?= esc($e['prenom'] . ' ' . $e['nom']) ?></span>
                                        <small class="text-muted ms-2"><?= esc($e['email']) ?></small>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="notes[<?= $e['user_id'] ?>]" class="form-control text-center" placeholder="–" min="0" max="20">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk me-2"></i>Enregistrer les notes</button>
            <?php else: ?>
                <p class="text-muted">Aucun étudiant inscrit à cet EC.</p>
            <?php endif; ?>
        </form>
    </div>
</div>

<?= $this->endSection() ?>