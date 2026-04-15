# Modifications de la Base de Données pour GENESIS-ULTRA V3

Pour implémenter les fonctionnalités d'auto-amélioration, de scoring, de validation et de sessions complexes pour la V3 de GENESIS-ULTRA, des modifications et ajouts à la base de données SQLite existante sont nécessaires. Voici une proposition de schéma de base de données mis à jour.

## Schéma Actuel (simplifié)

La base de données V2 contient au moins les tables suivantes (déduites de `api.php`):

*   **`articles`**: `id`, `topic`, `title`, `summary`, `sources_ok`, `total_hits`, `word_count`, `created_at`, `session_id`.
*   **`sessions`**: `id`, `topic`, `status`, `mode`.
*   **`queries`**: `id`, `session_id`, `source`, `url`, `term`, `status`, `http_code`, `duration_ms`, `hits`.
*   **`findings`**: `session_id`, `source`, `title`, `abstract`, `year`, `url`, `source_url`.

## Modifications et Nouvelles Tables Proposées pour V3

### 1. Table `queries` (Modifications)

Pour le scoring et l'auto-amélioration, la table `queries` doit stocker plus d'informations sur la performance de chaque requête.

| Champ           | Type      | Description                                                                                                                               |
| :-------------- | :-------- | :---------------------------------------------------------------------------------------------------------------------------------------- |
| `id`            | INTEGER   | Clé primaire auto-incrémentée.                                                                                                            |
| `session_id`    | TEXT      | ID de la session à laquelle la requête appartient.                                                                                        |
| `source`        | TEXT      | Nom de la source interrogée (ex: PubMed).                                                                                                 |
| `url`           | TEXT      | URL complète de la requête envoyée.                                                                                                       |
| `term`          | TEXT      | Terme de recherche utilisé pour cette requête.                                                                                            |
| `status`        | TEXT      | Statut de la requête (e.g., `pending`, `ok`, `fail`, `skipped`).                                                                          |
| `http_code`     | INTEGER   | Code de réponse HTTP de la source.                                                                                                        |
| `duration_ms`   | INTEGER   | Durée de la requête en millisecondes.                                                                                                     |
| `hits`          | INTEGER   | Nombre de résultats pertinents trouvés par la source.                                                                                     |
| **`score`**     | REAL      | **Nouveau**: Score de performance de la requête (0.0 à 1.0), basé sur `hits`, `http_code`, `duration_ms`, et la pertinence perçue par l'IA. |
| **`feedback_ai`** | TEXT      | **Nouveau**: Retour de l'IA sur la qualité de la requête et suggestions d'amélioration si `score` est bas.                               |
| **`is_optimized`**| BOOLEAN   | **Nouveau**: Indique si cette requête a été générée via un processus d'optimisation par l'IA.                                            |
| **`parent_query_id`**| INTEGER   | **Nouveau**: Référence à l'ID de la requête originale si celle-ci est une version optimisée.                                               |

### 2. Nouvelle Table `query_strategies`

Cette table stockera les stratégies de requêtes optimisées générées par l'IA pour chaque source et type de sujet, permettant l'auto-amélioration.

| Champ           | Type      | Description                                                                                                                               |
| :-------------- | :-------- | :---------------------------------------------------------------------------------------------------------------------------------------- |
| `id`            | INTEGER   | Clé primaire auto-incrémentée.                                                                                                            |
| `source`        | TEXT      | Nom de la source (ex: PubMed).                                                                                                            |
| `topic_type`    | TEXT      | Type de sujet pour lequel cette stratégie est pertinente (ex: `genomics`, `chemistry`, `general`).                                        |
| `optimized_term_template`| TEXT      | Modèle de terme de recherche optimisé pour cette source et ce type de sujet.                                                              |
| `effectiveness_score`| REAL      | Score moyen d'efficacité de cette stratégie (0.0 à 1.0).                                                                                  |
| `last_used_at`  | DATETIME  | Date de la dernière utilisation de cette stratégie.                                                                                        |
| `created_at`    | DATETIME  | Date de création de la stratégie.                                                                                                         |

### 3. Table `articles` (Modifications)

Pour supporter les recherches approfondies par section et les différents types de rapports.

| Champ           | Type      | Description                                                                                                                               |
| :-------------- | :-------- | :---------------------------------------------------------------------------------------------------------------------------------------- |
| `id`            | INTEGER   | Clé primaire auto-incrémentée.                                                                                                            |
| `topic`         | TEXT      | Sujet de l'article.                                                                                                                       |
| `title`         | TEXT      | Titre de l'article.                                                                                                                       |
| `summary`       | TEXT      | Résumé vulgarisé de l'article.                                                                                                            |
| `full_content_scientific`| TEXT      | **Nouveau**: Contenu complet de l'article en mode scientifique (Markdown).                                                                |
| `full_content_vulgarized`| TEXT      | **Nouveau**: Contenu complet de l'article en mode vulgarisé (Markdown).                                                                   |
| `sources_ok`    | INTEGER   | Nombre de sources ayant répondu avec succès.                                                                                              |
| `total_hits`    | INTEGER   | Nombre total de résultats trouvés.                                                                                                        |
| `word_count`    | INTEGER   | Nombre de mots dans l'article.                                                                                                            |
| `created_at`    | DATETIME  | Date de création de l'article.                                                                                                            |
| `session_id`    | TEXT      | ID de la session à laquelle l'article appartient.                                                                                         |
| **`validation_score`**| REAL      | **Nouveau**: Score de validation global de l'article par l'IA (cohérence, complétude, absence de contradiction).                            |
| **`contradiction_feedback`**| TEXT      | **Nouveau**: Détails des contradictions ou incohérences détectées par l'IA.                                                               |

### 4. Nouvelle Table `article_sections`

Pour permettre la recherche approfondie par section.

| Champ           | Type      | Description                                                                                                                               |
| :-------------- | :-------- | :---------------------------------------------------------------------------------------------------------------------------------------- |
| `id`            | INTEGER   | Clé primaire auto-incrémentée.                                                                                                            |
| `article_id`    | INTEGER   | Clé étrangère vers la table `articles`.                                                                                                   |
| `section_title` | TEXT      | Titre de la section (ex: "Mécanismes moléculaires").                                                                                    |
| `content_scientific`| TEXT      | Contenu scientifique de la section.                                                                                                       |
| `content_vulgarized`| TEXT      | Contenu vulgarisé de la section.                                                                                                          |
| `deep_research_status`| TEXT      | Statut de la recherche approfondie pour cette section (e.g., `pending`, `running`, `completed`).                                          |
| `last_updated_at`| DATETIME  | Date de la dernière mise à jour de cette section.                                                                                         |

### 5. Nouvelle Table `reports`

Pour stocker les rapports générés pour différents publics.

| Champ           | Type      | Description                                                                                                                               |
| :-------------- | :-------- | :---------------------------------------------------------------------------------------------------------------------------------------- |
| `id`            | INTEGER   | Clé primaire auto-incrémentée.                                                                                                            |
| `article_id`    | INTEGER   | Clé étrangère vers la table `articles`.                                                                                                   |
| `report_type`   | TEXT      | Type de rapport (e.g., `medecin_traitant`, `labo_recherche`, `article_scientifique`).                                                     |
| `content`       | TEXT      | Contenu du rapport (Markdown ou format spécifique).                                                                                       |
| `generated_at`  | DATETIME  | Date de génération du rapport.                                                                                                            |

## Implications pour `config.php` et `api.php`

Ces changements de base de données nécessiteront des mises à jour significatives dans `config.php` (pour la configuration des nouvelles tables et les fonctions d'accès à la BDD) et `api.php` (pour toutes les nouvelles logiques d'insertion, de mise à jour et de récupération des données liées à ces nouvelles fonctionnalités). Les prompts Mistral devront également être adaptés pour interagir avec ces nouvelles structures de données et pour générer les informations requises pour les champs `score`, `feedback_ai`, `validation_score`, `contradiction_feedback`, ainsi que les contenus spécifiques aux sections et aux rapports.
