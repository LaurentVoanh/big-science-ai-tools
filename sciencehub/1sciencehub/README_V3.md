# GENESIS-ULTRA V3 - Plateforme de Recherche Scientifique avec Conscience IA

## 🚀 Vue d'ensemble

GENESIS-ULTRA V3 est une évolution majeure de la plateforme de recherche scientifique automatisée. Cette version introduit une **"conscience" de l'IA** capable d'auto-amélioration, de validation rigoureuse, et de génération de rapports spécialisés.

### Nouvelles fonctionnalités V3

#### 1. **Conscience et Auto-amélioration de l'IA**
- **Moteur de Conscience** (`consciousness_engine.php`) : Analyse les performances des requêtes et génère des stratégies optimisées
- **Scoring de Requêtes** : Évalue chaque requête sur une échelle 0-1 basée sur :
  - Code HTTP de réponse
  - Nombre de résultats pertinents
  - Durée de la requête
  - Pertinence pour la source spécialisée
- **Apprentissage Progressif** : Stocke les stratégies optimisées dans `query_strategies` pour amélioration continue
- **Recommandations Intelligentes** : Propose des améliorations basées sur l'analyse des sessions précédentes

#### 2. **Validation Rigoureuse et Détection de Contradictions**
- **Validation d'Articles** : L'IA valide la cohérence interne et la complétude
- **Détection de Contradictions** : Identifie les affirmations conflictuelles entre sources
- **Scoring de Validation** : Évalue chaque article sur sa qualité scientifique
- **Feedback Détaillé** : Fournit des recommandations pour améliorer les articles

#### 3. **Recherche Approfondie par Section**
- **Sections Interactives** : Cliquez sur une section pour lancer une recherche approfondie
- **Mises à jour en AJAX** : Les sections se mettent à jour en temps réel
- **Contenu Dual** : Chaque section a une version scientifique et vulgarisée
- **Historique des Améliorations** : Suivi des modifications apportées à chaque section

#### 4. **Génération de Rapports Spécialisés**
- **Rapport Médecin Traitant** : Format clinique avec recommandations pratiques
- **Rapport Labo Recherche** : Format technique avec méthodologies détaillées
- **Rapport Article Scientifique** : Format IMRAD prêt pour publication
- **Génération à la Demande** : Créez les rapports quand vous en avez besoin

#### 5. **Expérience Utilisateur Améliorée**
- **Pas de Vide** : Indicateurs de progression continus pendant les attentes
- **Mises à Jour en Temps Réel** : AJAX pour les mises à jour sans rechargement
- **Interface Intuitive** : Navigation claire entre les différents modes

## 📁 Structure du Projet

```
GENESIS-ULTRA-V3/
├── README_V3.md                    # Ce fichier
├── README.md                       # Documentation V2 (conservée)
├── 
├── # Fichiers principaux (V2)
├── index.php                       # Interface utilisateur principale
├── api.php                         # API backend V2
├── config.php                      # Configuration V2 et sources
├── default.php                     # Page par défaut Hostinger
├── 
├── # Nouveaux fichiers V3
├── config_v3_ai.php                # Prompts d'IA pour la conscience
├── api_v3_extensions.php           # Nouveaux endpoints API V3
├── consciousness_engine.php        # Moteur de conscience et auto-amélioration
├── 
├── # Scripts de migration
├── init_db_v2.php                  # Initialisation BDD V2
├── migrate_db_v3.php               # Migration vers V3 (à exécuter)
├── 
├── # Documentation
├── db_v3_changes.md                # Détails des modifications BDD
├── summary.md                      # Résumé du projet
├── 
├── # Base de données
├── genesis.sqlite                  # Base de données SQLite (créée après migration)
├── 
└── logs/
    └── app.log                     # Journal d'exécution
```

## 🔧 Installation et Configuration

### Prérequis
- PHP 7.4+
- SQLite3
- Clés API Mistral AI (3 clés en rotation)
- Serveur web (Apache, Nginx, etc.)

### Étapes d'installation

1. **Télécharger et extraire le projet**
   ```bash
   unzip GENESIS-ULTRA-V3.zip
   cd GENESIS-ULTRA-V3
   ```

2. **Initialiser la base de données V2**
   ```bash
   php init_db_v2.php
   ```

3. **Migrer vers la V3**
   ```bash
   php migrate_db_v3.php
   ```

4. **Configurer les clés API Mistral**
   Éditer `config.php` et remplacer les clés placeholder :
   ```php
   define('MISTRAL_KEYS', [
       'votre_clé_1',
       'votre_clé_2',
       'votre_clé_3',
   ]);
   ```

5. **Vérifier les permissions**
   ```bash
   chmod 755 .
   chmod 644 *.php *.md
   chmod 755 logs
   chmod 666 genesis.sqlite
   ```

6. **Accéder à l'application**
   - Ouvrir `http://votre-domaine.com/index.php` dans le navigateur

## 📚 Utilisation

### Workflow de Recherche Standard

1. **Poser une question** (optionnel) ou laisser l'IA choisir un sujet
2. **Lancer la recherche** en cliquant sur le bouton de lancement
3. **Suivre la progression** via les indicateurs de source
4. **Consulter l'article** généré dans le panneau droit
5. **Valider et explorer** les sections pour des recherches approfondies

### Recherche Approfondie par Section

1. **Ouvrir un article** en cliquant sur sa carte
2. **Cliquer sur une section** pour lancer une recherche approfondie
3. **Attendre la mise à jour** en AJAX
4. **Consulter le contenu enrichi** dans la section

### Génération de Rapports

1. **Ouvrir un article**
2. **Cliquer sur le type de rapport** souhaité :
   - 🏥 Rapport Médecin
   - 🔬 Rapport Labo
   - 📄 Rapport Scientifique
3. **Télécharger ou copier** le rapport généré

## 🧠 Moteur de Conscience - Détails Techniques

### Scoring de Requêtes

Chaque requête reçoit un score de 0.0 à 1.0 basé sur :

| Critère | Poids | Description |
|---------|-------|-------------|
| Code HTTP | 30% | 200-299 = 0.3, 400-499 = 0.05, autres = 0.0 |
| Résultats | 40% | Normalisé sur 50 hits max |
| Durée | 20% | < 2s = 0.2, 2-5s = 0.1, > 5s = 0.0 |
| Bonus spécialisé | 10% | +0.1 pour sources spécialisées avec résultats |

### Stratégies Optimisées

Le système stocke les stratégies de requête optimisées pour chaque source :

```sql
SELECT * FROM query_strategies 
WHERE source = 'PubMed' 
ORDER BY effectiveness_score DESC;
```

### Recommandations Automatiques

Après chaque session, le système recommande :
- Optimisation des termes pour sources défaillantes
- Élargissement des critères si peu de résultats
- Optimisation des sources lentes

## 🔗 Nouveaux Endpoints API V3

### Optimisation de Requête
```
POST /api.php?action=optimize_query
Paramètres:
  - source: nom de la source
  - topic: sujet de recherche
  - previous_term: terme précédent
  - previous_hits: nombre de résultats précédents
  - success_rate: taux de réussite précédent
```

### Validation d'Article
```
POST /api.php?action=validate_article
Paramètres:
  - article_id: ID de l'article à valider
```

### Détection de Contradictions
```
POST /api.php?action=detect_contradictions
Paramètres:
  - article_id: ID de l'article
```

### Recherche Approfondie de Section
```
POST /api.php?action=deep_research_section
Paramètres:
  - article_id: ID de l'article
  - section_title: titre de la section
```

### Génération de Rapports
```
POST /api.php?action=generate_report_medecin
POST /api.php?action=generate_report_labo
POST /api.php?action=generate_report_scientifique
Paramètres:
  - article_id: ID de l'article
```

## 📊 Structure de la Base de Données V3

### Nouvelles Tables

#### `query_strategies`
Stocke les stratégies de requête optimisées pour amélioration continue.

#### `article_sections`
Permet la recherche approfondie par section avec historique des améliorations.

#### `reports`
Stocke les rapports générés pour différents publics.

### Colonnes Ajoutées

#### Table `queries`
- `score`: Score de performance (0.0-1.0)
- `feedback_ai`: Retour de l'IA sur la qualité
- `is_optimized`: Indique si optimisée
- `parent_query_id`: Référence à la requête originale

#### Table `articles`
- `full_content_scientific`: Contenu scientifique complet
- `full_content_vulgarized`: Contenu vulgarisé complet
- `validation_score`: Score de validation (0.0-1.0)
- `contradiction_feedback`: Détails des contradictions

## 🎯 Cas d'Usage

### Chercheur Académique
1. Poser une question de recherche
2. Lancer la recherche automatisée
3. Générer un rapport scientifique prêt pour publication
4. Approfondir les sections pertinentes

### Médecin Généraliste
1. Rechercher un sujet médical
2. Consulter l'article vulgarisé
3. Générer un rapport médecin avec recommandations cliniques
4. Partager avec les patients

### Laboratoire de Recherche
1. Lancer une recherche approfondie
2. Générer un rapport labo avec méthodologies
3. Identifier les lacunes de recherche
4. Planifier les prochaines études

## 🚀 Déploiement sur Hostinger

### Configuration Hostinger
- Utilise `file_get_contents` pour les requêtes HTTP (pas de cURL)
- SQLite pour la base de données (pas de MySQL requis)
- Clés Mistral en rotation automatique
- Logs centralisés dans `logs/app.log`

### Optimisations pour Hostinger
1. Limiter les requêtes parallèles (max 10 simultanées)
2. Implémenter un cache pour les résultats fréquents
3. Nettoyer régulièrement les logs
4. Monitorer l'utilisation de la base de données

## 📝 Logs et Débogage

Les logs sont disponibles dans `logs/app.log` avec les sections :
- `PICK_TOPIC`: Sélection du sujet
- `PREPARE`: Préparation des requêtes
- `EXEC`: Exécution des requêtes
- `WRITE`: Rédaction d'articles
- `DEEP`: Recherche approfondie
- `VALIDATE`: Validation d'articles
- `OPTIMIZE`: Optimisation de requêtes
- `CONSCIOUSNESS`: Messages du moteur de conscience

## 🔐 Sécurité

- Validation des entrées utilisateur
- Préparation des requêtes SQL (PDO)
- Limitation des tailles de réponse
- Gestion des erreurs sans exposition d'informations sensibles
- Clés API en rotation

## 📞 Support et Maintenance

Pour les problèmes :
1. Vérifier les logs dans `logs/app.log`
2. Tester la connexion à l'API Mistral
3. Vérifier les permissions des fichiers
4. S'assurer que SQLite est accessible

## 📄 Licence

Voir le fichier LICENSE (si applicable)

## 🎉 Conclusion

GENESIS-ULTRA V3 représente une avancée majeure dans la recherche scientifique automatisée. Avec sa "conscience" IA capable d'auto-amélioration, sa validation rigoureuse et ses rapports spécialisés, elle offre une solution complète pour la synthèse d'informations scientifiques.

---

**Version**: 3.0.0  
**Date**: 31 Mars 2026  
**Auteur**: Manus AI  
**Dernière mise à jour**: 31 Mars 2026
