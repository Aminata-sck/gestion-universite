<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1><i class="fa-regular fa-book-open text-primary me-2"></i> Mes cours</h1>
    <span class="badge bg-primary rounded-pill px-3 py-2">
        <i class="fa-regular fa-layer-group me-1"></i> <?= count($ecs) ?> EC
    </span>
</div>

<?php if (!empty($ecs)): ?>
    <?php foreach ($ecs as $ec): ?>
        <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <strong>
                    <i class="fa-regular fa-cubes me-2"></i>
                    <?= esc($ec['libelle']) ?>
                    <span class="badge bg-secondary ms-2"><?= esc($ec['type']) ?></span>
                    <span class="badge bg-info ms-1">Coeff <?= $ec['coefficient'] ?></span>
                </strong>
                <span class="badge bg-secondary">UE : <?= esc($ec['ue_libelle'] ?? '') ?></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th style="width: 18%;">Actions</th>
                                <th style="width: 30%;">Emploi du temps</th>
                                <th style="width: 30%;">Notes</th>
                                <th style="width: 22%;">Absences</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <!-- Colonne Actions -->
                                <td>
                                    <a href="/teacher/schedule/create/<?= $ec['id'] ?>" class="btn btn-sm btn-outline-success mb-1 w-100">
                                        <i class="fa-regular fa-plus me-1"></i> Ajouter créneau
                                    </a>
                                    <a href="/teacher/grades/<?= $ec['id'] ?>" class="btn btn-sm btn-outline-primary mb-1 w-100">
                                        <i class="fa-regular fa-plus me-1"></i> Saisir notes
                                    </a>
                                    <a href="/teacher/absences/<?= $ec['id'] ?>" class="btn btn-sm btn-outline-danger w-100">
                                        <i class="fa-regular fa-plus me-1"></i> Saisir absences
                                    </a>
                                </td>

                                <!-- Colonne Emploi du temps -->
                                <td>
                                    <?php if (!empty($ec['emplois'])): ?>
                                        <?php foreach ($ec['emplois'] as $emp): ?>
                                            <div class="d-flex align-items-center gap-1 mb-1 flex-wrap">
                                                <span class="badge bg-light text-dark">
                                                    <?= $emp['jour'] ?> <?= substr($emp['heure_debut'], 0, 5) ?>-<?= substr($emp['heure_fin'], 0, 5) ?>
                                                    (<?= esc($emp['salle']) ?>)
                                                </span>
                                                <a href="/teacher/schedule/edit/<?= $emp['id'] ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="/teacher/schedule/delete/<?= $emp['id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer ce créneau ?')">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Aucun créneau</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Colonne Notes -->
                                <td>
                                    <?php if (!empty($ec['notes'])): ?>
                                        <?php foreach ($ec['notes'] as $note): ?>
                                            <div class="d-flex align-items-center gap-1 mb-1 flex-wrap">
                                                <span class="badge bg-light text-dark">
                                                    <?= esc($note['etudiant_nom']) ?> : <?= $note['note'] ?>
                                                </span>
                                                <a href="/teacher/grades/edit/<?= $note['id'] ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="/teacher/grades/delete/<?= $note['id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer cette note ?')">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Aucune note</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Colonne Absences -->
                                <td>
                                    <?php if (!empty($ec['absences'])): ?>
                                        <?php foreach ($ec['absences'] as $abs): ?>
                                            <div class="d-flex align-items-center gap-1 mb-1 flex-wrap">
                                                <span class="badge bg-light text-dark">
                                                    <?= esc($abs['etudiant_nom']) ?> - <?= $abs['date_absence'] ?>
                                                </span>
                                                <a href="/teacher/absences/edit/<?= $abs['id'] ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="/teacher/absences/delete/<?= $abs['id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer cette absence ?')">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Aucune absence</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="text-center text-muted py-5">
        <i class="fa-regular fa-circle-info fa-3x d-block mb-3 text-primary"></i>
        <p class="fs-5">Vous n'êtes affecté à aucun EC pour le moment.</p>
        <p class="small">Contactez l'administration pour être rattaché à des cours.</p>
    </div>
<?php endif; ?>

<div class="mt-4">
    <a href="/teacher/dashboard" class="btn btn-secondary">
        <i class="fa-regular fa-arrow-left me-2"></i> Retour au tableau de bord
    </a>
</div>

<?= $this->endSection() ?>