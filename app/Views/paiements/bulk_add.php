<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fa-regular fa-credit-card me-2"></i> Paiement progressif</h1>
    <a href="/paiements" class="btn btn-secondary"><i class="fa-regular fa-arrow-left me-2"></i>Retour</a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <!-- Sélection de l'étudiant et de l'année -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Étudiant</label>
                <select id="etudiantSelect" class="form-select" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($etudiants as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= esc($e['nom'] . ' ' . $e['prenom']) ?> (<?= esc($e['email']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Année universitaire</label>
                <select id="anneeSelect" class="form-select" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($annees as $a): ?>
                        <option value="<?= $a['id'] ?>"><?= esc($a['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Zone d'ajout -->
        <div id="zoneAjout" style="display:none;">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Période</label>
                    <select id="periodeSelect" class="form-select">
                        <?php foreach ($mois as $key => $label): ?>
                            <option value="<?= $key ?>"><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Montant (FCFA)</label>
                    <input type="number" step="0.01" id="montantInput" class="form-control" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Mode</label>
                    <select id="modeSelect" class="form-select">
                        <option value="especes">Espèces</option>
                        <option value="cheque">Chèque</option>
                        <option value="virement">Virement</option>
                        <option value="en_ligne">En ligne</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button id="ajouterBtn" class="btn btn-primary w-100"><i class="fa-regular fa-plus me-2"></i>Ajouter</button>
                </div>
            </div>
        </div>

        <!-- Liste des paiements déjà enregistrés -->
        <div id="listePaiements" class="mt-4">
            <p class="text-muted">Sélectionnez un étudiant et une année pour voir ses paiements.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const etudiantSelect = document.getElementById('etudiantSelect');
    const anneeSelect = document.getElementById('anneeSelect');
    const zoneAjout = document.getElementById('zoneAjout');
    const listeDiv = document.getElementById('listePaiements');

    function chargerPaiements() {
        const etudiantId = etudiantSelect.value;
        const anneeId = anneeSelect.value;
        if (!etudiantId || !anneeId) {
            zoneAjout.style.display = 'none';
            listeDiv.innerHTML = '<p class="text-muted">Sélectionnez un étudiant et une année.</p>';
            return;
        }

        zoneAjout.style.display = 'block';
        listeDiv.innerHTML = '<p>Chargement...</p>';

        fetch('/paiements/get-paiements/' + etudiantId + '/' + anneeId)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    listeDiv.innerHTML = '<p class="text-muted">Aucun paiement enregistré pour cet étudiant/année.</p>';
                } else {
                    let html = '<table class="table table-bordered table-hover"><thead class="table-light"><tr><th>Période</th><th>Montant</th><th>Référence</th><th>Statut</th></tr></thead><tbody>';
                    data.forEach(p => {
                        html += `<tr><td>${p.description || p.reference}</td><td>${p.montant}</td><td>${p.reference}</td><td>${p.statut}</td></tr>`;
                    });
                    html += '</tbody></table>';
                    listeDiv.innerHTML = html;
                }
            })
            .catch(() => listeDiv.innerHTML = '<p class="text-danger">Erreur de chargement.</p>');
    }

    // Ajouter un paiement
    document.getElementById('ajouterBtn').addEventListener('click', function() {
        const etudiantId = etudiantSelect.value;
        const anneeId = anneeSelect.value;
        const periode = document.getElementById('periodeSelect').value;
        const montant = document.getElementById('montantInput').value;
        const mode = document.getElementById('modeSelect').value;

        if (!etudiantId || !anneeId || !montant || montant <= 0) {
            alert('Veuillez remplir tous les champs.');
            return;
        }

        fetch('/paiements/ajouter-progressif', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                etudiant_id: etudiantId,
                annee_universitaire_id: anneeId,
                periode: periode,
                montant: montant,
                mode: mode,
                statut: 'payé'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('montantInput').value = '';
                chargerPaiements(); // rafraîchir la liste
                alert('Paiement ajouté avec succès.');
            } else {
                alert('Erreur : ' + data.message);
            }
        })
        .catch(() => alert('Erreur réseau.'));
    });

    etudiantSelect.addEventListener('change', chargerPaiements);
    anneeSelect.addEventListener('change', chargerPaiements);
});
</script>

<?= $this->endSection() ?>