/**
 * main.js - Script JavaScript principal pour V5
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Application V5 chargée');
    
    // Initialisation des composants
    initFormValidation();
    initNavigation();
    initNotifications();
});

/**
 * Validation des formulaires
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
                
                // Validation email
                if (field.type === 'email' && field.value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(field.value)) {
                        isValid = false;
                        field.classList.add('error');
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Veuillez remplir tous les champs correctement', 'error');
            }
        });
    });
}

/**
 * Navigation fluide
 */
function initNavigation() {
    const navLinks = document.querySelectorAll('.nav-menu a');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Animation de transition
            this.style.opacity = '0.7';
            setTimeout(() => {
                this.style.opacity = '';
            }, 300);
        });
    });
}

/**
 * Système de notifications
 */
function initNotifications() {
    // Auto-dismiss des alertes après 5 secondes
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
}

/**
 * Affiche une notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '1000';
    notification.style.minWidth = '300px';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transition = 'opacity 0.5s';
        notification.style.opacity = '0';
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 3000);
}

/**
 * Confirmation avant action destructive
 */
function confirmAction(message) {
    return confirm(message || 'Êtes-vous sûr de vouloir continuer ?');
}

/**
 * Chargement AJAX
 */
async function fetchAPI(endpoint, options = {}) {
    try {
        const response = await fetch(`?action=api&endpoint=${endpoint}`, {
            method: options.method || 'GET',
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            body: options.body ? JSON.stringify(options.body) : null
        });
        
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Erreur API:', error);
        showNotification('Une erreur est survenue', 'error');
        throw error;
    }
}

/**
 * Gestion du thème sombre/clair
 */
function toggleTheme() {
    const body = document.body;
    const currentTheme = body.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    
    showNotification(`Thème ${newTheme === 'dark' ? 'sombre' : 'clair'} activé`, 'success');
}

// Charger le thème sauvegardé
const savedTheme = localStorage.getItem('theme');
if (savedTheme) {
    document.body.setAttribute('data-theme', savedTheme);
}
