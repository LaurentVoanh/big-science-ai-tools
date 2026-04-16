<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Accueil' ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= $config['site_url'] ?>assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <nav class="navbar">
            <div class="container">
                <a href="<?= $config['site_url'] ?>" class="logo"><?= APP_NAME ?></a>
                <ul class="nav-menu">
                    <li><a href="?action=home">Accueil</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li><a href="?action=dashboard">Tableau de bord</a></li>
                        <li><a href="?action=profile">Profil</a></li>
                        <li><a href="?action=settings">Paramètres</a></li>
                        <li><a href="?action=logout">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="?action=login">Connexion</a></li>
                        <li><a href="?action=register">Inscription</a></li>
                    <?php endif; ?>
                    <li><a href="?action=contact">Contact</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="content">
        <div class="container">
