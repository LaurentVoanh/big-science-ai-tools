# 📑 Index du Projet GENESIS-ULTRA V3

Bienvenue dans GENESIS-ULTRA V3 ! Ce fichier vous guide à travers la structure du projet.

## 🚀 Commencer Rapidement

1. **Lire d'abord** : `README_V3.md` - Vue d'ensemble complète
2. **Installer** : `INSTALLATION.md` - Guide d'installation étape par étape
3. **Lancer** : Exécuter `init_db_v2.php` puis `migrate_db_v3.php`
4. **Accéder** : Ouvrir `index.php` dans votre navigateur

## 📁 Structure des Fichiers

### 📖 Documentation

| Fichier | Description |
|---------|-------------|
| `README_V3.md` | **👈 LIRE EN PREMIER** - Vue d'ensemble complète de V3 |
| `INSTALLATION.md` | Guide d'installation détaillé avec dépannage |
| `README.md` | Documentation V2 (conservée pour référence) |
| `db_v3_changes.md` | Détails techniques des modifications de BDD |
| `summary.md` | Résumé du projet V2 |
| `INDEX.md` | Ce fichier |

### 🔧 Fichiers Principaux (V2)

| Fichier | Description |
|---------|-------------|
| `index.php` | **Interface utilisateur principale** - Ouvrir dans le navigateur |
| `api.php` | Backend API V2 - Gère les requêtes de recherche |
| `config.php` | Configuration V2 - **À éditer pour ajouter vos clés Mistral** |
| `default.php` | Page par défaut Hostinger (peut être ignorée) |

### ✨ Nouveaux Fichiers V3

| Fichier | Description |
|---------|-------------|
| `config_v3_ai.php` | **Prompts d'IA pour la conscience** - Validation, optimisation, rapports |
| `api_v3_extensions.php` | **Nouveaux endpoints API V3** - Validation, contradictions, rapports |
| `consciousness_engine.php` | **Moteur de conscience** - Scoring, auto-amélioration, recommandations |

### 🗄️ Scripts de Migration

| Fichier | Description |
|---------|-------------|
| `init_db_v2.php` | **Exécuter en premier** - Crée le schéma V2 |
| `migrate_db_v3.php` | **Exécuter en deuxième** - Ajoute les nouvelles tables V3 |

### 📊 Base de Données et Logs

| Fichier | Description |
|---------|-------------|
| `genesis.sqlite` | Base de données SQLite (créée après migration) |
| `logs/app.log` | Journal d'exécution - Consulter pour déboguer |

## 🎯 Workflow d'Installation

```
1. Extraire le ZIP
   ↓
2. Lire README_V3.md
   ↓
3. Lire INSTALLATION.md
   ↓
4. Exécuter: php init_db_v2.php
   ↓
5. Exécuter: php migrate_db_v3.php
   ↓
6. Éditer config.php (ajouter clés Mistral)
   ↓
7. Configurer permissions (chmod)
   ↓
8. Ouvrir index.php dans le navigateur
   ↓
9. Tester une recherche
```

## 🔑 Points Clés à Retenir

### Configuration Requise

```php
// Dans config.php, remplacer par vos clés :
define('MISTRAL_KEYS', [
    'votre_clé_1',
    'votre_clé_2',
    'votre_clé_3',
]);
```

### Permissions Importantes

```bash
chmod 755 .                    # Répertoire principal
chmod 755 logs                 # Répertoire logs
chmod 644 *.php               # Fichiers PHP
chmod 666 genesis.sqlite      # Base de données
```

### Vérifier l'Installation

```bash
# Test de santé
curl "http://votre-domaine.com/api.php?action=health"

# Devrait retourner :
# {"success": true, "data": {"status": "ok", ...}}
```

## 📚 Documentation Détaillée

### Pour Comprendre V3

1. **Architecture** → Lire `db_v3_changes.md`
2. **Conscience IA** → Consulter `consciousness_engine.php`
3. **Nouveaux Endpoints** → Voir `api_v3_extensions.php`
4. **Prompts IA** → Examiner `config_v3_ai.php`

### Pour Utiliser V3

1. **Recherche Standard** → `README_V3.md` section "Utilisation"
2. **Recherche Approfondie** → `README_V3.md` section "Recherche Approfondie par Section"
3. **Génération de Rapports** → `README_V3.md` section "Génération de Rapports"

### Pour Déployer

1. **Installation Locale** → `INSTALLATION.md`
2. **Déploiement Hostinger** → `INSTALLATION.md` section "Déploiement sur Hostinger"
3. **Dépannage** → `INSTALLATION.md` section "Dépannage"

## 🆘 Aide Rapide

### "Comment démarrer ?"
→ Lire `README_V3.md` puis `INSTALLATION.md`

### "Où ajouter mes clés Mistral ?"
→ Éditer `config.php`, ligne ~10

### "Comment tester si ça marche ?"
→ Exécuter `php init_db_v2.php` et `php migrate_db_v3.php`

### "Où voir les erreurs ?"
→ Consulter `logs/app.log`

### "Comment générer un rapport ?"
→ Voir `README_V3.md` section "Génération de Rapports"

### "Qu'est-ce que le moteur de conscience ?"
→ Lire `README_V3.md` section "Moteur de Conscience"

## 📊 Fichiers par Taille

| Fichier | Taille | Importance |
|---------|--------|-----------|
| `config.php` | 38 KB | ⭐⭐⭐ Critique |
| `api.php` | 25 KB | ⭐⭐⭐ Critique |
| `index.php` | 31 KB | ⭐⭐⭐ Critique |
| `api_v3_extensions.php` | 14 KB | ⭐⭐ Important |
| `consciousness_engine.php` | 11 KB | ⭐⭐ Important |
| `config_v3_ai.php` | 8 KB | ⭐⭐ Important |
| `db_v3_changes.md` | 12 KB | ⭐ Référence |
| `README_V3.md` | 11 KB | ⭐⭐⭐ À Lire |
| `INSTALLATION.md` | 8 KB | ⭐⭐⭐ À Lire |

## ✅ Checklist Post-Installation

- [ ] ZIP extrait
- [ ] `README_V3.md` lu
- [ ] `INSTALLATION.md` lu
- [ ] `init_db_v2.php` exécuté ✓
- [ ] `migrate_db_v3.php` exécuté ✓
- [ ] Clés Mistral ajoutées dans `config.php`
- [ ] Permissions configurées (chmod)
- [ ] `index.php` accessible dans le navigateur
- [ ] Test de santé API réussi
- [ ] Première recherche lancée avec succès

## 🎓 Concepts Clés de V3

### Conscience de l'IA
L'IA apprend de ses requêtes précédentes et s'améliore automatiquement.

### Scoring de Requêtes
Chaque requête reçoit un score basé sur son efficacité (0.0 à 1.0).

### Validation Rigoureuse
Les articles sont validés pour la cohérence et les contradictions.

### Recherche Approfondie
Cliquez sur une section pour lancer une recherche approfondie en AJAX.

### Rapports Spécialisés
Générez des rapports adaptés à différents publics (médecin, labo, scientifique).

## 🚀 Prochaines Étapes

1. **Installation** → Suivre `INSTALLATION.md`
2. **Première Recherche** → Ouvrir `index.php` et tester
3. **Exploration** → Essayer les différentes fonctionnalités
4. **Génération de Rapports** → Créer des rapports spécialisés
5. **Optimisation** → Consulter les logs et améliorer

## 📞 Support

- **Problèmes d'installation** → Voir `INSTALLATION.md` section "Dépannage"
- **Questions sur les fonctionnalités** → Consulter `README_V3.md`
- **Erreurs techniques** → Vérifier `logs/app.log`
- **Configuration** → Éditer `config.php`

---

## 📝 Résumé Rapide

**GENESIS-ULTRA V3** est une plateforme de recherche scientifique automatisée avec :

✅ Conscience IA auto-améliorante  
✅ Validation rigoureuse des articles  
✅ Détection de contradictions  
✅ Recherche approfondie par section  
✅ Génération de rapports spécialisés  
✅ Interface utilisateur fluide avec AJAX  

**Pour commencer** : Lisez `README_V3.md` → Suivez `INSTALLATION.md` → Lancez `index.php`

---

**Version**: 3.0.0  
**Date**: 31 Mars 2026  
**Prêt à explorer ?** 🚀
