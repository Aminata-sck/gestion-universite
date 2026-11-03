<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h2>Modifier un paiement</h2>
<form method="post" action="/paiements/update/<?= $paiement['id'] ?>">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label>Étudiant</label>
        <select name="etudiant_id" class="form-control" required>
            <?php foreach ($etudiants as $e): ?>
                <option value="<?= $e['id'] ?>" <?= $e['id'] == $paiement['etudiant_id'] ? 'selected' : '' ?>><?= esc($e['nom'] . ' ' . $e['prenom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3"><label>Montant</label><input type="number" step="0.01" name="montant" class="form-control" value="<?= $paiement['montant'] ?>" required></div>
    <div class="mb-3"><label>Date paiement</label><input type="date" name="date_paiement" class="form-control" value="<?= $paiement['date_paiement'] ?>" required></div>
    <div class="mb-3">
        <label>Mode</label>
        <select name="mode" class="form-control">
            <option value="especes" <?= $paiement['mode'] == 'especes' ? 'selected' : '' ?>>Espèces</option>
            <option value="cheque" <?= $paiement['mode'] == 'cheque' ? 'selected' : '' ?>>Chèque</option>
            <option value="virement" <?= $paiement['mode'] == 'virement' ? 'selected' : '' ?>>Virement</option>
            <option value="en_ligne" <?= $paiement['mode'] == 'en_ligne' ? 'selected' : '' ?>>En ligne</option>
        </select>
    </div>
    <div class="mb-3"><label>Référence</label><input type="text" name="reference" class="form-control" value="<?= esc($paiement['reference']) ?>"></div>
    <div class="mb-3">
        <label>Statut</label>
        <select name="statut" class="form-control">
            <option value="en_attente" <?= $paiement['statut'] == 'en_attente' ? 'selected' : '' ?>>En attente</option>
            <option value="payé" <?= $paiement['statut'] == 'payé' ? 'selected' : '' ?>>Payé</option>
            <option value="annulé" <?= $paiement['statut'] == 'annulé' ? 'selected' : '' ?>>Annulé</option>
        </select>
    </div>
    <div class="mb-3"><label>Échéance</label><input type="date" name="echeance" class="form-control" value="<?= $paiement['echeance'] ?>"></div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/paiements" class="btn btn-secondary">Annuler</a>
</form>
<?= $this->endSection() ?>