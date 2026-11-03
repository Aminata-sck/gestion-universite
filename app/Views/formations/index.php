<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1>Liste des formations</h1>
    <div>
        <?php if (hasPermission('formations', 'add')): ?>
            <a href="/formations/new" class="btn btn-success">
                <i class="fa-solid fa-plus"></i> Ajouter
            </a>
        <?php endif; ?>
        <a href="/formations/archived" class="btn btn-secondary">
            <i class="fa-solid fa-archive"></i> Voir les archivées
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Libellé</th>
                <th>Type</th>
                <th>Niveau</th>      <!-- NOUVEAU -->
                <th>Durée</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($formations)): ?>
                <?php foreach ($formations as $item): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= esc($item['libelle']) ?></td>
                        <td><span class="badge bg-secondary"><?= esc($item['type']) ?></span></td>
                        <td>
                            <?php if (!empty($item['annee'])): ?>
                                <span class="badge bg-info text-dark"><?= esc($item['annee']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?= $item['duree'] ?> ans</td>
                        <td class="text-center">
                            <?php if (hasPermission('formations', 'edit')): ?><a href="/formations/edit/<?= $item['id'] ?>" class="btn btn-sm btn-primary" title="Modifier"><i class="fa-solid fa-pen-to-square"></i></a><?php endif; ?>
                            <?php if (hasPermission('formations', 'delete')): ?><a href="/formations/archive/<?= $item['id'] ?>" class="btn btn-sm btn-warning" title="Archiver" onclick="return confirm('Archiver cette formation ?')"><i class="fa-solid fa-archive"></i></a><?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4"> <!-- 6 colonnes maintenant -->
                        <i class="fa-solid fa-circle-info fa-2x d-block mb-2"></i>
                        Aucune formation enregistrée pour le moment.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
