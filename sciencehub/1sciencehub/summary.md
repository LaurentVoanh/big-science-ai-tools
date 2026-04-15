# Résumé du dépôt GitHub LaurentVoanh/bioapi-science-source/V2

Ce document présente une analyse des fichiers clés trouvés dans le dossier `V2` du dépôt GitHub `LaurentVoanh/bioapi-science-source`. Le projet, nommé **GENESIS-ULTRA v4.0**, est une plateforme de recherche scientifique automatisée qui interroge 36 sources en parallèle pour générer des articles scientifiques.

## Vue d'ensemble du projet GENESIS-ULTRA v4.0

GENESIS-ULTRA v4.0 est une application web PHP conçue pour automatiser le processus de recherche scientifique. Elle utilise l'IA (Mistral) pour sélectionner des sujets, générer des termes de recherche adaptés à diverses bases de données scientifiques, interroger ces sources, puis synthétiser les résultats en articles structurés. La plateforme est optimisée pour l'hébergement sur Hostinger, utilisant `file_get_contents` pour les requêtes HTTP et SQLite pour la base de données.

### Fonctionnalités principales:

*   **Sélection de sujet**: L'IA peut choisir un sujet de recherche scientifique ou médicale précis et récent, ou l'utilisateur peut en fournir un.
*   **Génération de requêtes**: Mistral génère des termes de recherche optimaux pour 36 sources scientifiques différentes, en respectant les formats spécifiques de chaque API.
*   **Interrogation parallèle**: Les 36 sources sont interrogées simultanément.
*   **Synthèse d'articles**: Mistral Large synthétise les informations collectées en un article d'au moins 3000 mots, avec des liens vers les sources originales.
*   **Analyse approfondie**: Un bouton "APPROFONDIR" permet une analyse ciblée sur les sources les plus pertinentes.

## Analyse des fichiers

### `README.md`

Le fichier `README.md` fournit une description concise du projet, de ses fonctionnalités et des sources de données utilisées. Il liste les fichiers principaux (`index.php`, `api.php`, `config.php`) et explique le flux de travail de la plateforme. Il détaille également les 36 sources catégorisées (Littérature, Génomique, Chimie, Réseaux, Clinique, Encyclopédies, IA, Structures, Écologie, Santé publique, Physique, Interactions) et mentionne les optimisations pour l'hébergement Hostinger (PHP `file_get_contents` uniquement, SQLite, clés Mistral en rotation automatique).

### `config.php`

Ce fichier PHP centralise la configuration de l'application. Il définit les constantes globales telles que la version de l'application (`APP_VERSION`), les chemins de la base de données SQLite (`DB_PATH`) et des logs (`LOG_PATH`). Il contient également les clés API de Mistral (avec rotation automatique) et l'URL de l'API Mistral. La partie la plus substantielle du fichier est la définition de `SOURCES_CONFIG`, un tableau associatif qui configure les 36 sources de données. Pour chaque source, il spécifie:

*   `url`: Le template d'URL pour l'API de la source, avec un placeholder `{TERM}` pour le terme de recherche.
*   `desc`: Une brève description de la source.
*   `query`: Des instructions détaillées pour l'IA sur la manière de formuler la requête pour cette source, y compris des exemples et des contraintes spécifiques.
*   `type`: La catégorie de la source (e.g., `literature`, `genomics`, `chemistry`).

Le fichier contient également la logique de `parse_response` qui gère la structuration des données retournées par chaque API en un format uniforme pour l'application.

### `api.php`

Le fichier `api.php` est le backend de l'application, gérant toutes les interactions côté serveur via une API RESTful. Il inclut des en-têtes pour permettre le Cross-Origin Resource Sharing (CORS) et initialise la connexion à la base de données SQLite. Les actions principales gérées par ce fichier sont:

*   `health`: Vérifie l'état de l'API et retourne des informations de base (version PHP, nombre de sources).
*   `get_articles`: Récupère une liste des articles de recherche générés précédemment.
*   `get_article`: Récupère un article spécifique avec ses sources et les liens associés.
*   `step_pick_topic`: Gère la sélection du sujet de recherche, soit par l'utilisateur, soit automatiquement par Mistral. Crée une nouvelle session dans la base de données.
*   `step_prepare_queries`: Demande à Mistral de générer des termes de recherche pour chaque source en fonction du sujet, en utilisant les formats définis dans `config.php`. Stocke les requêtes préparées dans la base de données.
*   `step_exec_query`: Exécute une requête spécifique vers une source externe, traite la réponse et stocke les "findings" (résultats) dans la base de données. Gère les codes HTTP et les erreurs.
*   `step_write_article`: Demande à Mistral Large de synthétiser les "findings" collectés en un article complet et le stocke dans la base de données.
*   `step_deep_research`: Permet une analyse approfondie sur des sources spécifiques pour un article donné.

Le fichier utilise des fonctions utilitaires comme `app_log` pour la journalisation et `mistral` pour interagir avec l'API de Mistral AI.

### `index.php`

Ce fichier est l'interface utilisateur principale (frontend) de l'application. Il s'agit d'une application monopage (SPA) construite avec HTML, CSS et JavaScript. Il inclut le fichier `config.php` pour accéder aux constantes de configuration. L'interface est divisée en plusieurs sections:

*   **Barre supérieure**: Affiche la version de l'application et le nombre de sources.
*   **Panneau gauche**: Contient une zone de texte pour la question de l'utilisateur, un bouton de lancement, une barre de progression, une grille d'état pour chaque source (indiquant si elle est en cours, OK ou en erreur) et un journal d'activité (terminal).
*   **Panneau droit**: Liste les articles de recherche générés. Chaque article est présenté sous forme de carte avec un titre, des métadonnées et un résumé.
*   **Modale**: S'affiche lors de la sélection d'un article, permettant de visualiser le contenu complet, de lancer une recherche approfondie et de consulter les liens vers les sources originales.

Le JavaScript embarqué gère l'état de l'application côté client, les interactions avec l'API (`api.php`) et la mise à jour dynamique de l'interface utilisateur.

### `logs/app.log`

Ce fichier contient les journaux d'exécution de l'application. Il enregistre les différentes étapes du workflow, y compris la sélection des sujets (`PICK_TOPIC`), la préparation des requêtes (`PREPARE`), l'exécution des requêtes (`EXEC`), la rédaction des articles (`WRITE`) et la recherche approfondie (`DEEP`). Les logs montrent des informations précieuses telles que les sujets de recherche, le nombre de requêtes préparées, les codes de réponse HTTP des sources externes, la durée des requêtes et le nombre de résultats ("hits"). Il révèle également des cas où l'analyse JSON de Mistral a échoué, entraînant l'utilisation de termes génériques, et des problèmes avec certaines sources (e.g., `Reactome` 404, `PDB` 400, `NASA_ADS` 404, `SemanticScholar` 429).

### `default.php`

Ce fichier est une page d'atterrissage statique par défaut de Hostinger. Il ne fait pas partie de l'application GENESIS-ULTRA v4.0 et contient du HTML/CSS générique de Hostinger, sans logique PHP ni lien avec les fonctionnalités de la plateforme de recherche. Il s'agit d'un fichier de remplacement fourni par l'hébergeur.

## Conclusion

Le dépôt `LaurentVoanh/bioapi-science-source/V2` contient une application PHP fonctionnelle et bien structurée pour la recherche scientifique automatisée. L'intégration de Mistral AI pour la sélection des sujets et la génération des requêtes, combinée à l'interrogation de nombreuses sources scientifiques, en fait un outil puissant pour la synthèse d'informations. L'architecture est modulaire, avec une séparation claire entre la configuration (`config.php`), le backend API (`api.php`) et le frontend (`index.php`). Les logs (`logs/app.log`) fournissent des informations précieuses sur le comportement réel de l'application et les défis rencontrés lors de l'interrogation de sources externes.
