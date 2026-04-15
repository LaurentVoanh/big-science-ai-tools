# NEXUS AI V5 — Documentation du Projet

## 📋 Vue d'ensemble

NEXUS AI est un système de recherche médicale autonome qui combine 36 sources scientifiques, génère des hypothèses et les teste via des mini-applications.

## 🏗️ Architecture du Projet

```
V5/
├── config.php              # Configuration centrale (DB, APIs, sessions)
├── index.php               # Page d'accueil avec interface utilisateur
├── results.php             # Liste des sessions de recherche
├── mini_apps.php           # Galerie des mini-applications
├── assets/
│   └── style.css           # Styles CSS globaux
├── articles/
│   ├── index.php           # Bibliothèque d'articles
│   ├── view.php            # Visualisation d'article
│   ├── medical/            # Articles médicaux générés
│   └── system/             # Articles sur l'évolution du système
├── mini_apps/              # Mini-applications générées
└── prompts/                # Prompts pour l'IA
    ├── selection_sujet.txt
    ├── generation_requetes.txt
    ├── generation_miniapp.txt
    ├── synthese_article.txt
    ├── validation_article.txt
    ├── analyse_patterns.txt
    └── auto_evaluation.txt
```

## 📄 Pages Implementées

### 1. **index.php** — Page d'accueil
- Interface de lancement des recherches
- 4 modes : Recherche automatique, Question précise, Contexte, Évolution
- Statistiques en temps réel
- Sessions récentes

### 2. **results.php** — Résultats de recherche
- Liste de toutes les sessions
- Filtres par statut (toutes, terminées, en cours, échouées)
- Liens vers les articles générés

### 3. **articles/index.php** — Bibliothèque d'articles
- Grille d'articles avec catégories (medical, system)
- Filtre par catégorie
- Affichage des extraits

### 4. **articles/view.php** — Visualisation d'article
- Lecture d'article au format Markdown
- Conversion basique Markdown → HTML
- Navigation retour

### 5. **mini_apps.php** — Galerie de mini-apps
- Liste des applications de test générées
- Hypothèses associées
- Lancement direct

## 🔧 Configuration

### Base de données (SQLite)
Tables créées automatiquement :
- `sessions` : Sessions de recherche
- `articles` : Articles générés
- `query_strategies` : Stratégies de requête
- `findings` : Découvertes par source
- `mini_apps` : Applications générées

### APIs Scientifiques (36 sources)
Catégories :
- Littérature biomédicale (PubMed, EuropePMC, arXiv...)
- Génétique & Protéines (UniProt, Ensembl, ClinVar...)
- Pathways & Interactions (Reactome, KEGG, STRING...)
- Composés & Pharmacologie (ChEMBL, PubChem, DrugBank...)
- Maladies & Clinique (DisGeNET, ClinicalTrials, Orphanet...)
- Préprints & Conférences (bioRxiv, medRxiv, HAL...)
- Physique & Chimie (NASA ADS, RCSB PDB, NIST...)
- Sciences de l'environnement (GBIF, NOAA, IPCC...)
- Sciences sociales (RePEc, SSRN, PsycINFO...)

### IA Mistral
- 3 clés API avec rotation automatique
- Modèles : pixtral-12b-2409, mistral-large-latest

## 🚀 Workflow de Recherche

1. **Sélection du sujet** — Auto ou question utilisateur
2. **Choix des sources** — Sélection intelligente parmi 36 APIs
3. **Génération de requêtes** — Optimisées par source
4. **Exécution parallèle** — Recherche simultanée
5. **Synthèse** — Création d'un article médical complet
6. **Validation** — Vérification et correction
7. **Analyse de patterns** — Détection d'hypothèses
8. **Mini-apps** — Génération d'outils de test
9. **Auto-évaluation** — Apprentissage continu

## 🎨 Design System

### Couleurs
- `--ink`: #06060a (fond principal)
- `--paper`: #f0ede6 (texte)
- `--acid`: #c8ff00 (accent)
- `--cyan`: #00e5ff (secondaire)
- `--mag`: #ff2d6b (erreur/alerte)
- `--dim`: #3a3a4a (texte secondaire)

### Polices
- `Unbounded` : Titres (Google Fonts)
- `Space Mono` : Corps de texte (Google Fonts)

## 📝 Formats d'Articles

### Articles Médicaux (`articles/medical/`)
Format : `YYYY-MM-DD_slug.md`
Contient : Frontmatter YAML + Markdown

### Articles Système (`articles/system/`)
Format : `YYYY-MM-DD_slug.md`
Décrit l'évolution et l'apprentissage de l'IA

## 🔒 Sécurité

- Protection contre le directory traversal dans view.php
- Validation des catégories
- Échappement HTML (htmlspecialchars)
- Session PHP sécurisée

## 📊 Statistiques

Le tableau de bord affiche :
- Nombre de sources disponibles (36)
- Sessions réalisées
- Articles générés
- Sessions récentes

## 🔄 Auto-Renforcement

Le système s'améliore grâce à :
- L'analyse des succès/échecs de requêtes
- La mise à jour des stratégies
- L'auto-évaluation après chaque session
- L'apprentissage des patterns efficaces

## 🛠️ Prochaines Étapes

Pour compléter le projet :
1. Implémenter le moteur de recherche (process.php)
2. Créer les scripts d'appel aux APIs
3. Développer le générateur de mini-apps
4. Ajouter un système de logs détaillés
5. Créer une interface d'administration

## 📞 Contact

Projet NEXUS AI V5 — Système de recherche autonome
