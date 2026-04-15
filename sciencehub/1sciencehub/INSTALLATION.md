# Guide d'Installation GENESIS-ULTRA V3

## 📋 Prérequis

- **PHP**: Version 7.4 ou supérieure
- **SQLite3**: Extension PHP sqlite3 activée
- **Mistral AI**: Accès à l'API Mistral avec clés API
- **Serveur Web**: Apache, Nginx, ou autre serveur compatible PHP
- **Espace disque**: Minimum 100 MB

## 🔍 Vérification des Prérequis

### Vérifier la version de PHP
```bash
php -v
```

### Vérifier les extensions PHP
```bash
php -m | grep sqlite
```

Si sqlite n'apparaît pas, installer :
```bash
# Ubuntu/Debian
sudo apt-get install php-sqlite3

# CentOS/RHEL
sudo yum install php-sqlite

# macOS avec Homebrew
brew install php@7.4 --with-sqlite
```

## 📥 Installation

### Étape 1 : Télécharger et Extraire

```bash
# Télécharger le ZIP
wget https://votre-serveur.com/GENESIS-ULTRA-V3.zip

# Extraire
unzip GENESIS-ULTRA-V3.zip
cd GENESIS-ULTRA-V3
```

### Étape 2 : Initialiser la Base de Données

```bash
# Initialiser le schéma V2
php init_db_v2.php

# Migrer vers V3
php migrate_db_v3.php
```

Vous devriez voir :
```
Initialisation de la base de données V2...
Table 'articles' créée ou déjà existante.
Table 'sessions' créée ou déjà existante.
Table 'queries' créée ou déjà existante.
Table 'findings' créée ou déjà existante.
Initialisation de la base de données V2 terminée avec succès.

Migration de la base de données vers la V3...
Ajout des colonnes à la table 'queries'...
Colonnes ajoutées à 'queries'.
Création de la table 'query_strategies'...
Table 'query_strategies' créée.
Ajout des colonnes à la table 'articles'...
Colonnes ajoutées à 'articles'.
Création de la table 'article_sections'...
Table 'article_sections' créée.
Création de la table 'reports'...
Table 'reports' créée.
Migration de la base de données V3 terminée avec succès.
```

### Étape 3 : Configurer les Clés API Mistral

Éditer `config.php` et localiser :

```php
define('MISTRAL_KEYS', [
    'ENTER YOUR API KEY MISTRAL',
    'ENTER YOUR API KEY MISTRAL',
    'ENTER YOUR API KEY MISTRAL',
]);
```

Remplacer par vos clés réelles :

```php
define('MISTRAL_KEYS', [
    'sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxx',
    'sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxx',
    'sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxx',
]);
```

### Étape 4 : Configurer les Permissions

```bash
# Permissions pour les répertoires
chmod 755 .
chmod 755 logs

# Permissions pour les fichiers
chmod 644 *.php
chmod 644 *.md
chmod 666 genesis.sqlite

# Vérifier
ls -la
```

### Étape 5 : Configurer le Serveur Web

#### Apache

Créer `.htaccess` à la racine du projet :

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
</IfModule>

# Sécurité
<FilesMatch "\.sqlite$">
    Deny from all
</FilesMatch>

<FilesMatch "\.log$">
    Deny from all
</FilesMatch>
```

#### Nginx

Ajouter à la configuration du serveur :

```nginx
location ~ \.sqlite$ {
    deny all;
}

location ~ \.log$ {
    deny all;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```

### Étape 6 : Tester l'Installation

#### Test de Santé de l'API

```bash
curl "http://votre-domaine.com/api.php?action=health"
```

Réponse attendue :
```json
{
  "success": true,
  "data": {
    "status": "ok",
    "php": "7.4.x",
    "sources": 37,
    "version": "4.0.0"
  }
}
```

#### Test dans le Navigateur

1. Ouvrir `http://votre-domaine.com/index.php`
2. Vérifier que l'interface se charge correctement
3. Tester une recherche simple

## 🔧 Configuration Avancée

### Limiter les Requêtes Parallèles

Éditer `config.php` et ajouter :

```php
define('MAX_PARALLEL_QUERIES', 10);
define('QUERY_TIMEOUT_MS', 5000);
```

### Activer le Cache

Créer un fichier `cache.php` :

```php
<?php
define('CACHE_ENABLED', true);
define('CACHE_DIR', __DIR__ . '/cache');
define('CACHE_TTL', 3600); // 1 heure
?>
```

### Configurer les Logs

Éditer `config.php` :

```php
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR
define('LOG_MAX_SIZE', 10485760); // 10 MB
```

## 🚀 Déploiement sur Hostinger

### Configuration Hostinger Spécifique

1. **Accéder au File Manager**
   - Connexion au cPanel Hostinger
   - Naviguer vers File Manager

2. **Uploader les Fichiers**
   - Créer un dossier `genesis-ultra-v3`
   - Uploader tous les fichiers

3. **Exécuter les Scripts de Migration**
   - Via SSH (si disponible) :
   ```bash
   ssh user@hostinger.com
   cd public_html/genesis-ultra-v3
   php init_db_v2.php
   php migrate_db_v3.php
   ```
   - Ou via le Terminal Hostinger

4. **Configurer les Permissions**
   - Via File Manager, clic droit sur les fichiers
   - Permissions : 644 pour les fichiers, 755 pour les dossiers

5. **Vérifier l'Installation**
   - Ouvrir `https://votre-domaine.com/genesis-ultra-v3/index.php`

## 🐛 Dépannage

### Problème : "No such table: queries"

**Solution** : Exécuter les scripts de migration :
```bash
php init_db_v2.php
php migrate_db_v3.php
```

### Problème : "Permission denied" pour genesis.sqlite

**Solution** : Corriger les permissions :
```bash
chmod 666 genesis.sqlite
chmod 755 .
```

### Problème : "SQLSTATE[HY000]: General error"

**Solution** : Vérifier que SQLite3 est installé :
```bash
php -m | grep sqlite
```

### Problème : API Mistral ne répond pas

**Solution** : 
1. Vérifier les clés API dans `config.php`
2. Tester la connexion :
```bash
curl -X POST https://api.mistral.ai/v1/chat/completions \
  -H "Authorization: Bearer YOUR_KEY" \
  -H "Content-Type: application/json" \
  -d '{"model": "pixtral-12b-2409", "messages": [{"role": "user", "content": "test"}]}'
```

### Problème : Logs vides

**Solution** : Vérifier les permissions du dossier logs :
```bash
chmod 755 logs
chmod 666 logs/app.log
```

## 📊 Vérification Post-Installation

### Checklist

- [ ] PHP 7.4+ installé
- [ ] SQLite3 activé
- [ ] Base de données initialisée
- [ ] Clés Mistral configurées
- [ ] Permissions correctes
- [ ] API répond au test de santé
- [ ] Interface se charge dans le navigateur
- [ ] Logs créés et accessibles

### Commandes de Vérification

```bash
# Vérifier PHP
php -v

# Vérifier SQLite
php -r "echo sqlite_libversion();"

# Vérifier la BDD
sqlite3 genesis.sqlite ".tables"

# Vérifier les permissions
ls -la

# Vérifier les logs
tail -f logs/app.log
```

## 🔐 Sécurité Post-Installation

### Recommandations de Sécurité

1. **Protéger les Fichiers Sensibles**
```bash
# Empêcher l'accès direct à la BDD
chmod 600 genesis.sqlite

# Empêcher l'accès aux logs
chmod 600 logs/app.log
```

2. **Configurer HTTPS**
   - Obtenir un certificat SSL (Let's Encrypt)
   - Rediriger HTTP vers HTTPS

3. **Limiter l'Accès à l'API**
   - Ajouter une authentification si nécessaire
   - Implémenter un rate limiting

4. **Sauvegarder Régulièrement**
```bash
# Sauvegarder la BDD
cp genesis.sqlite genesis.sqlite.backup

# Sauvegarder les logs
cp logs/app.log logs/app.log.backup
```

## 📞 Support

Pour les problèmes d'installation :
1. Consulter les logs : `logs/app.log`
2. Vérifier les prérequis
3. Tester chaque étape individuellement
4. Consulter la documentation

## ✅ Installation Complète

Une fois toutes les étapes complétées, votre installation GENESIS-ULTRA V3 est prête !

Pour commencer :
1. Ouvrir `http://votre-domaine.com/index.php`
2. Poser une question ou laisser l'IA choisir
3. Lancer la recherche
4. Consulter les résultats et générer des rapports

---

**Besoin d'aide ?** Consultez README_V3.md pour plus de détails.
