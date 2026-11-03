<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fa-regular fa-credit-card me-2"></i> Ajouter un paiement</h1>
    <a href="/paiements" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <form method="post" action="/paiements">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">Étudiant</label>
                <select name="etudiant_id" class="form-select" required>
                    <?php foreach ($etudiants as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= esc($e['nom'] . ' ' . $e['prenom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Période</label>
                <select name="periode" class="form-select" required>
                    <option value="inscription">Frais d'inscription</option>
                    <option value="octobre">Octobre</option>
                    <option value="novembre">Novembre</option>
                    <option value="decembre">Décembre</option>
                    <option value="janvier">Janvier</option>
                    <option value="fevrier">Février</option>
                    <option value="mars">Mars</option>
                    <option value="avril">Avril</option>
                    <option value="mai">Mai</option>
                    <option value="juin">Juin</option>
                    <option value="juillet">Juillet</option>
                </select>
                <small class="text-muted">La description sera générée automatiquement.</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Montant</label>
                <input type="number" step="0.01" name="montant" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Date paiement</label>
                <input type="date" name="date_paiement" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Mode</label>
                <select name="mode" class="form-select">
                    <option value="especes">Espèces</option>
                    <option value="cheque">Chèque</option>
                    <option value="virement">Virement</option>
                    <option value="en_ligne">En ligne</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Statut</label>
                <select name="statut" class="form-select">
                    <option value="en_attente">En attente</option>
                    <option value="payé">Payé</option>
                    <option value="annulé">Annulé</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Échéance</label>
                <input type="date" name="echeance" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Référence (générée)</label>
                <input type="text" name="reference" class="form-control" value="<?= $reference ?? '' ?>" readonly>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk me-2"></i>Enregistrer</button>
            <a href="/paiements" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>