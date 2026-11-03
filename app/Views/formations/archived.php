<?= $this->extend('layout/app_layout') ?>
<?= $this->section('content') ?>
<h1>Formations archivées</h1>
<a href="/formations" class="btn btn-secondary mb-3">Retour à la liste active</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Libellé</th>
            <th>Type</th>
            <th>Durée</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($formations as $item): ?>
        <tr>
            <td><?= $item['id'] ?></td>
            <td><?= esc($item['libelle']) ?></td>
            <td><?= $item['type'] ?></td>
            <td><?= $item['duree'] ?> ans</td>
            <td>
                <a href="/formations/restore/<?= $item['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Restaurer cette formation ?')">Restaurer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>