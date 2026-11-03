<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h1>Liste des utilisateurs</h1>
    <?php if (hasPermission('utilisateurs', 'add')): ?><a href="/users/new" class="btn btn-success"><i class="fa-solid fa-plus"></i> Ajouter</a><?php endif; ?>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Profil RBAC</th>
                <th>Statut</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= esc($u['nom']) ?></td>
                        <td><?= esc($u['prenom']) ?></td>
                        <td><?= esc($u['email']) ?></td>
                        <td><span class="badge bg-info text-dark"><?= esc($u['profil_nom'] ?? 'Administrateur système') ?></span></td>
                        <td>
                            <?php if ($u['statut']): ?>
                                <span class="badge bg-success"><i class="fa-solid fa-circle-check"></i> Actif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><i class="fa-solid fa-circle-minus"></i> Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if (hasPermission('utilisateurs', 'edit')): ?><a href="/users/edit/<?= $u['id'] ?>" class="btn btn-sm btn-primary" title="Modifier"><i class="fa-solid fa-pen-to-square"></i></a><?php endif; ?>
                            <?php if (hasPermission('utilisateurs', 'delete')): ?><a href="/users/delete/<?= $u['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Confirmer la suppression de cet utilisateur ?')"><i class="fa-solid fa-trash-can"></i></a><?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fa-solid fa-circle-info fa-2x d-block mb-2"></i>
                        Aucun utilisateur enregistré pour le moment.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
