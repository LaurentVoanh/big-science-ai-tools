<?php
$pageTitle = 'Contact';
require_once __DIR__ . '/../includes/header.php';

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<div class="contact-container">
    <h1>Contactez-nous</h1>
    
    <?php if ($success): ?>
        <div class="alert alert-success">Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.</div>
    <?php endif; ?>
    
    <?php if ($error === 'missing_fields'): ?>
        <div class="alert alert-error">Veuillez remplir tous les champs.</div>
    <?php elseif ($error === 'send_failed'): ?>
        <div class="alert alert-error">Une erreur est survenue lors de l'envoi. Veuillez réessayer.</div>
    <?php endif; ?>
    
    <div class="contact-grid">
        <div class="contact-form-wrapper">
            <form method="POST" action="?action=contact" class="contact-form">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" id="name" name="name" required autocomplete="name">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required autocomplete="email">
                </div>
                
                <div class="form-group">
                    <label for="subject">Sujet</label>
                    <input type="text" id="subject" name="subject" placeholder="Sujet de votre message">
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="6" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Envoyer le message</button>
            </form>
        </div>
        
        <div class="contact-info">
            <h2>Nos coordonnées</h2>
            
            <div class="info-item">
                <h3>📧 Email</h3>
                <p>contact@example.com</p>
            </div>
            
            <div class="info-item">
                <h3>📞 Téléphone</h3>
                <p>+33 1 23 45 67 89</p>
            </div>
            
            <div class="info-item">
                <h3>📍 Adresse</h3>
                <p>123 Rue de l'Exemple<br>75000 Paris, France</p>
            </div>
            
            <div class="info-item">
                <h3>🕐 Horaires</h3>
                <p>Lundi - Vendredi: 9h00 - 18h00</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
