# Application V5

Application web PHP moderne et complète.

## Structure du projet

```
v5/
├── process.php          # Point d'entrée principal (routeur)
├── config.php           # Configuration de l'application
├── functions.php        # Fonctions utilitaires
├── pages/               # Pages de l'application
│   ├── home.php         # Page d'accueil
│   ├── login.php        # Connexion
│   ├── register.php     # Inscription
│   ├── dashboard.php    # Tableau de bord
│   ├── profile.php      # Profil utilisateur
│   ├── settings.php     # Paramètres
│   └── contact.php      # Contact
├── includes/            # Fichiers inclus
│   ├── header.php       # En-tête commun
│   └── footer.php       # Pied de page commun
└── assets/              # Ressources statiques
    ├── css/
    │   └── style.css    # Feuille de style principale
    └── js/
        └── main.js      # Script JavaScript principal
```

## Installation

1. Copiez les fichiers dans votre serveur web
2. Configurez la base de données dans `config.php`
3. Accédez à `process.php` via votre navigateur

## Fonctionnalités

- ✅ Système de routage simple
- ✅ Authentification (connexion/inscription)
- ✅ Gestion des sessions
- ✅ Protection CSRF
- ✅ Pages responsive
- ✅ Formulaire de contact
- ✅ Tableau de bord utilisateur
- ✅ Gestion du profil
- ✅ Paramètres personnalisables
- ✅ API REST basique

## Configuration requise

- PHP 7.4 ou supérieur
- MySQL/MariaDB (optionnel pour la persistance des données)
- Serveur web (Apache, Nginx, etc.)

## Sécurité

- Tokens CSRF sur tous les formulaires
- Hachage des mots de passe avec bcrypt
- Protection contre les injections SQL (PDO)
- Validation des entrées utilisateur
- Sessions sécurisées

## Licence

Libre utilisation
