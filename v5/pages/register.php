<?php
$pageTitle = 'Inscription';
require_once __DIR__ . '/../includes/header.php';

$error = $_GET['error'] ?? '';
?>

<div class="auth-container">
    <div class="auth-card">
        <h1>Inscription</h1>
        
        <?php if ($error === 'missing_fields'): ?>
            <div class="alert alert-error">Veuillez remplir tous les champs.</div>
        <?php elseif ($error === 'passwords_mismatch'): ?>
            <div class="alert alert-error">Les mots de passe ne correspondent pas.</div>
        <?php elseif ($error === 'email_exists'): ?>
            <div class="alert alert-error">Cet email est déjà utilisé.</div>
        <?php elseif ($error === 'creation_failed'): ?>
            <div class="alert alert-error">Une erreur est survenue lors de la création du compte.</div>
        <?php endif; ?>
        
        <form method="POST" action="?action=register" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            
            <div class="form-group">
                <label for="name">Nom complet</label>
                <input type="text" id="name" name="name" required autocomplete="name">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autocomplete="email">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required autocomplete="new-password" minlength="8">
                <small>Minimum 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password">
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
        </form>
        
        <p class="auth-link">
            Déjà un compte ? <a href="?action=login">Se connecter</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
