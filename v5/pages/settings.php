<?php
$pageTitle = 'Paramètres';
require_once __DIR__ . '/../includes/header.php';

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Récupérer les paramètres actuels (à implémenter avec la BDD)
$current_theme = 'light';
current_language = 'fr';
$current_notifications = 1;
?>

<div class="settings-container">
    <h1>Paramètres</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success">Paramètres enregistrés avec succès !</div>
    <?php endif; ?>
    
    <?php if ($error === 'update_failed'): ?>
        <div class="alert alert-error">Une erreur est survenue lors de l'enregistrement.</div>
    <?php endif; ?>
    
    <form method="POST" action="?action=settings" class="settings-form">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        
        <section class="settings-section">
            <h2>Apparence</h2>
            
            <div class="form-group">
                <label for="theme">Thème</label>
                <select id="theme" name="theme">
                    <option value="light" <?= $current_theme === 'light' ? 'selected' : '' ?>>Clair</option>
                    <option value="dark" <?= $current_theme === 'dark' ? 'selected' : '' ?>>Sombre</option>
                    <option value="auto" <?= $current_theme === 'auto' ? 'selected' : '' ?>>Automatique</option>
                </select>
            </div>
        </section>
        
        <section class="settings-section">
            <h2>Langue</h2>
            
            <div class="form-group">
                <label for="language">Langue de l'interface</label>
                <select id="language" name="language">
                    <option value="fr" <?= current_language === 'fr' ? 'selected' : '' ?>>Français</option>
                    <option value="en" <?= current_language === 'en' ? 'selected' : '' ?>>English</option>
                    <option value="es" <?= current_language === 'es' ? 'selected' : '' ?>>Español</option>
                    <option value="de" <?= current_language === 'de' ? 'selected' : '' ?>>Deutsch</option>
                </select>
            </div>
        </section>
        
        <section class="settings-section">
            <h2>Notifications</h2>
            
            <div class="form-group checkbox-group">
                <label>
                    <input type="checkbox" name="notifications" <?= $current_notifications ? 'checked' : '' ?>>
                    Activer les notifications par email
                </label>
            </div>
        </section>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="?action=dashboard" class="btn btn-secondary">Retour</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
