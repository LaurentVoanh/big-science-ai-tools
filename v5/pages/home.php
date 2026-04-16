<?php
$pageTitle = 'Accueil';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="hero">
    <h1>Bienvenue sur <?= APP_NAME ?></h1>
    <p class="lead">Votre application moderne et performante</p>
    
    <?php if (!isLoggedIn()): ?>
        <div class="cta-buttons">
            <a href="?action=register" class="btn btn-primary">Commencer maintenant</a>
            <a href="?action=login" class="btn btn-secondary">Se connecter</a>
        </div>
    <?php else: ?>
        <div class="welcome-message">
            <h2>Bonjour, <?= htmlspecialchars($_SESSION['user_name']) ?> !</h2>
            <p>Ravi de vous revoir.</p>
            <a href="?action=dashboard" class="btn btn-primary">Accéder au tableau de bord</a>
        </div>
    <?php endif; ?>
</div>

<section class="features">
    <h2>Fonctionnalités</h2>
    <div class="feature-grid">
        <div class="feature-card">
            <h3>🚀 Rapide</h3>
            <p>Performance optimisée pour une expérience utilisateur fluide.</p>
        </div>
        <div class="feature-card">
            <h3>🔒 Sécurisé</h3>
            <p>Vos données sont protégées avec les meilleures pratiques de sécurité.</p>
        </div>
        <div class="feature-card">
            <h3>📱 Responsive</h3>
            <p>Compatible avec tous les appareils : desktop, tablette et mobile.</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
