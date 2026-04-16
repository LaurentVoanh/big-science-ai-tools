<?php
$pageTitle = 'Tableau de bord';
require_once __DIR__ . '/../includes/header.php';

$user = getCurrentUser();
?>

<div class="dashboard">
    <h1>Tableau de bord</h1>
    
    <div class="welcome-banner">
        <h2>Bonjour, <?= htmlspecialchars($user['name']) ?> !</h2>
        <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>📊 Statistiques</h3>
            <p class="stat-number">0</p>
            <p>Visites ce mois</p>
        </div>
        
        <div class="dashboard-card">
            <h3>👤 Profil</h3>
            <p>Gérez vos informations personnelles</p>
            <a href="?action=profile" class="btn btn-secondary">Modifier</a>
        </div>
        
        <div class="dashboard-card">
            <h3>⚙️ Paramètres</h3>
            <p>Personnalisez votre expérience</p>
            <a href="?action=settings" class="btn btn-secondary">Accéder</a>
        </div>
        
        <div class="dashboard-card">
            <h3>📧 Contact</h3>
            <p>Besoin d'aide ? Contactez-nous</p>
            <a href="?action=contact" class="btn btn-secondary">Contacter</a>
        </div>
    </div>
    
    <section class="recent-activity">
        <h3>Activité récente</h3>
        <p>Aucune activité récente à afficher.</p>
    </section>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
