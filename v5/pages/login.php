<?php
$pageTitle = 'Connexion';
require_once __DIR__ . '/../includes/header.php';

$error = $_GET['error'] ?? '';
$registered = $_GET['registered'] ?? '';
?>

<div class="auth-container">
    <div class="auth-card">
        <h1>Connexion</h1>
        
        <?php if ($registered): ?>
            <div class="alert alert-success">
                Inscription réussie ! Vous pouvez maintenant vous connecter.
            </div>
        <?php endif; ?>
        
        <?php if ($error === 'missing_fields'): ?>
            <div class="alert alert-error">Veuillez remplir tous les champs.</div>
        <?php elseif ($error === 'invalid_credentials'): ?>
            <div class="alert alert-error">Email ou mot de passe incorrect.</div>
        <?php endif; ?>
        
        <form method="POST" action="?action=login" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autocomplete="email">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
        </form>
        
        <p class="auth-link">
            Pas encore de compte ? <a href="?action=register">S'inscrire</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
