<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Gestion Université' ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: #2c3e50;
            color: #fff;
            overflow-y: auto;
            padding-top: 20px;
            z-index: 1000;
            transition: all 0.3s;
        }
        .sidebar .brand {
            text-align: center;
            padding: 20px 0;
            font-size: 1.5rem;
            font-weight: bold;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }
        .sidebar .brand a {
            color: #fff;
            text-decoration: none;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            border-radius: 0;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #34495e;
            color: #fff;
        }
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }
        .sidebar .dropdown-menu {
            background: #2c3e50;
            border: none;
            border-radius: 0;
            padding: 0;
            margin: 0;
        }
        .sidebar .dropdown-item {
            color: #ecf0f1;
            padding: 10px 20px 10px 40px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar .dropdown-item:hover {
            background: #34495e;
            color: #fff;
        }
        .sidebar .dropdown-toggle::after {
            float: right;
            margin-top: 8px;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
            min-height: 100vh;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="brand">
            <a href="/dashboard">Gestion Université</a>
        </div>
        <nav class="nav flex-column">
            <?php if (session()->get('isLoggedIn')): ?>
                <!-- Lien Dashboard -->
                <a class="nav-link" href="/dashboard">
                    <i class="fa-solid fa-gauge-high"></i> Dashboard
                </a>

                <!-- Lien Admin réservé aux administrateurs -->
                <?php if (session()->get('role') === 'admin'): ?>
                    <a class="nav-link" href="/admin/dashboard">
                        <i class="fa-solid fa-chart-simple"></i> Admin
                    </a>
                <?php endif; ?>

                <?php
                $sousMenuModel = new \App\Models\SousMenuModel();
                $role = session()->get('role');
                // Récupère tous les menus avec leur hiérarchie (arborescence)
                $menus = $sousMenuModel->getMenus($role);
                $studentPersonalUrls = [
                    '/notes'     => '/student/grades',
                    '/emplois'   => '/student/schedule',
                    '/paiements' => '/student/payments',
                ];
                $menuUrlForRole = static function (string $url) use ($role, $studentPersonalUrls): string {
                    return $role === 'etudiant' ? ($studentPersonalUrls[$url] ?? $url) : $url;
                };
                ?>

                <!-- Menu dynamique -->
                <?php foreach ($menus as $menu): ?>
                    <!-- Ignorer le lien "Inscription groupée" -->
                    <?php if ($menu['nom'] === 'Inscription groupée') continue; ?>

                    <?php if (empty($menu['children'])): ?>
                        <!-- Lien simple (pas de sous-menus) -->
                        <a class="nav-link" href="<?= $menuUrlForRole($menu['url'] ?? '#') ?>">
                            <?php if (!empty($menu['icon'])): ?>
                                <i class="<?= $menu['icon'] ?>"></i>
                            <?php endif; ?>
                            <?= $menu['nom'] ?>
                        </a>
                    <?php else: ?>
                        <!-- Menu déroulant (Paramètres) -->
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown_<?= $menu['id'] ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php if (!empty($menu['icon'])): ?>
                                    <i class="<?= $menu['icon'] ?>"></i>
                                <?php endif; ?>
                                <?= $menu['nom'] ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdown_<?= $menu['id'] ?>">
                                <?php foreach ($menu['children'] as $child): ?>
                                    <?php if ($child['nom'] === 'Inscription groupée') continue; ?>
                                    <li>
                                        <a class="dropdown-item" href="<?= $menuUrlForRole($child['url'] ?? '#') ?>">
                                            <?php if (!empty($child['icon'])): ?>
                                                <i class="<?= $child['icon'] ?>"></i>
                                            <?php endif; ?>
                                            <?= $child['nom'] ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Menu ENSEIGNANT spécifique -->
                <?php if (session()->get('role') === 'enseignant'): ?>
                    <a class="nav-link" href="/teacher/dashboard"><i class="fa-solid fa-chalkboard-user"></i> Enseignant</a>
                    <a class="nav-link" href="/teacher/schedule"><i class="fa-solid fa-calendar"></i> Emploi du temps</a>
                <?php endif; ?>

                <!-- Menu ÉTUDIANT spécifique -->
                <?php if (session()->get('role') === 'etudiant'): ?>
                    <a class="nav-link" href="/student/dashboard"><i class="fa-solid fa-user-graduate"></i> Mon espace</a>
                <?php endif; ?>

                <!-- Déconnexion -->
                <a class="nav-link" href="/logout"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
            <?php else: ?>
                <a class="nav-link" href="/login"><i class="fa-solid fa-arrow-right-to-bracket"></i> Connexion</a>
            <?php endif; ?>
        </nav>
    </div>

    <!-- CONTENU PRINCIPAL -->
    <div class="content">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
