<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1>Gestion des profils</h1>
    <?php if (hasPermission('profils', 'add')): ?><a href="/profils/new" class="btn btn-success"><i class="fa-solid fa-plus"></i> Ajouter un profil</a><?php endif; ?>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Statut</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($profils)): ?>
                <?php foreach ($profils as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= esc($p['nom']) ?></td>
                        <td>
                            <?php if ($p['statut']): ?>
                                <span class="badge bg-success"><i class="fa-solid fa-circle-check"></i> Actif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><i class="fa-solid fa-circle-minus"></i> Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if (hasPermission('profils', 'edit')): ?><a href="/profils/edit/<?= $p['id'] ?>" class="btn btn-sm btn-primary" title="Modifier"><i class="fa-solid fa-pen-to-square"></i></a><?php endif; ?>
                            <?php if (hasPermission('profils', 'delete')): ?><a href="/profils/delete/<?= $p['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Confirmer la suppression de ce profil ?')"><i class="fa-solid fa-trash-can"></i></a><?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="fa-solid fa-circle-info fa-2x d-block mb-2"></i>
                        Aucun profil enregistré pour le moment.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
