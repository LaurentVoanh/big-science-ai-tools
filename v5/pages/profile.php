<?php
$pageTitle = 'Mon Profil';
require_once __DIR__ . '/../includes/header.php';

$user = getCurrentUser();
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<div class="profile-container">
    <h1>Mon Profil</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success">Profil mis à jour avec succès !</div>
    <?php endif; ?>
    
    <?php if ($error === 'update_failed'): ?>
        <div class="alert alert-error">Une erreur est survenue lors de la mise à jour.</div>
    <?php endif; ?>
    
    <div class="profile-card">
        <form method="POST" action="?action=profile" class="profile-form">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            
            <div class="form-group">
                <label for="name">Nom complet</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="?action=dashboard" class="btn btn-secondary">Retour</a>
            </div>
        </form>
    </div>
    
    <div class="profile-info">
        <h3>Informations du compte</h3>
        <p><strong>ID:</strong> <?= $user['id'] ?></p>
        <p><strong>Membre depuis:</strong> <?= date('d/m/Y') ?></p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
