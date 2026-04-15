<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS AI — Auto-Research System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <a href="index.php" class="nav-logo">🧬 NEXUS AI</a>
        <div class="nav-links">
            <a href="index.php" class="active">Accueil</a>
            <a href="results.php">Résultats</a>
            <a href="articles/">Articles</a>
        </div>
        <div class="nav-status">
            <span class="pulse"></span>
            <span>Système en ligne</span>
        </div>
    </nav>

    <!-- Main App -->
    <div class="app">
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>👋 Bonjour ! Je suis <span class="highlight">NEXUS AI</span></h1>
                <p class="hero-subtitle">Votre assistant de recherche médicale autonome</p>
                
                <div class="intro-box">
                    <p><strong>Mon objectif aujourd'hui :</strong> Trouver des découvertes réelles en combinant 36 sources scientifiques, en générant des hypothèses, et en les testant via des mini-apps.</p>
                </div>

                <div class="action-cards">
                    <div class="card action-card" data-action="auto">
                        <div class="card-icon">🔍</div>
                        <h3>Recherche Automatique</h3>
                        <p>Je choisis un sujet à haut potentiel de découverte</p>
                    </div>
                    
                    <div class="card action-card" data-action="question">
                        <div class="card-icon">📝</div>
                        <h3>Question Précise</h3>
                        <p>Posez une question spécifique sur un sujet médical</p>
                    </div>
                    
                    <div class="card action-card" data-action="context">
                        <div class="card-icon">⚙️</div>
                        <h3>Choisir un Contexte</h3>
                        <p>Focus oncologie, éviter les sources peu fiables...</p>
                    </div>
                    
                    <div class="card action-card" data-action="evolution">
                        <div class="card-icon">📖</div>
                        <h3>Mon Évolution</h3>
                        <p>Lire mes derniers articles sur mon apprentissage</p>
                    </div>
                </div>

                <div class="input-section" id="inputSection" style="display: none;">
                    <form id="researchForm" method="POST" action="process.php">
                        <input type="hidden" name="action_type" id="actionType" value="">
                        
                        <div id="questionInput" class="input-group" style="display: none;">
                            <label for="userQuestion">Votre question :</label>
                            <textarea name="question" id="userQuestion" placeholder="Ex: Mécanismes de résistance aux inhibiteurs de PARP dans BRCA1..." rows="3"></textarea>
                        </div>
                        
                        <div id="contextInput" class="input-group" style="display: none;">
                            <label for="userContext">Contexte (optionnel) :</label>
                            <input type="text" name="context" id="userContext" placeholder="Ex: Focus oncologie, Éviter les sources peu fiables...">
                        </div>
                        
                        <button type="submit" class="btn-submit" id="submitBtn">
                            🚀 Lancer la recherche
                        </button>
                    </form>
                </div>

                <div class="ai-message" id="aiMessage">
                    <p>💬 <strong>Dites-moi simplement ce que vous voulez, et je m'occupe de tout !</strong></p>
                    <p class="hint"><em>(Exemple : "Recherche auto" ou "Focus oncologie + BRCA1")</em></p>
                </div>
            </div>

            <!-- Sidebar Info -->
            <aside class="hero-sidebar">
                <div class="info-card">
                    <h4>🎯 Comment ça marche ?</h4>
                    <ol>
                        <li>Je sélectionne un sujet ou utilise votre question</li>
                        <li>Je choisis les meilleures sources (36 APIs)</li>
                        <li>Je génère des requêtes optimisées</li>
                        <li>J'exécute les recherches en parallèle</li>
                        <li>Je synthétise un article médical complet</li>
                        <li>Je valide et corrige l'article</li>
                        <li>J'analyse les patterns pour générer des hypothèses</li>
                        <li>Je crée des mini-apps pour tester les hypothèses</li>
                        <li>J'intègre les résultats dans l'article</li>
                        <li>Je m'auto-évalue et j'apprends</li>
                    </ol>
                </div>

                <div class="stats-card">
                    <h4>📊 Statistiques</h4>
                    <div class="stat-item">
                        <span class="stat-label">Sources disponibles</span>
                        <span class="stat-value">36</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Sessions réalisées</span>
                        <span class="stat-value"><?php 
                            try {
                                $stmt = $pdo->query("SELECT COUNT(*) FROM sessions");
                                echo $stmt->fetchColumn();
                            } catch (Exception $e) { echo "0"; }
                        ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Articles générés</span>
                        <span class="stat-value"><?php 
                            try {
                                $stmt = $pdo->query("SELECT COUNT(*) FROM articles");
                                echo $stmt->fetchColumn();
                            } catch (Exception $e) { echo "0"; }
                        ?></span>
                    </div>
                </div>

                <div class="recent-sessions">
                    <h4>🕐 Sessions récentes</h4>
                    <?php 
                    try {
                        $stmt = $pdo->query("SELECT id, topic, created_at FROM sessions ORDER BY created_at DESC LIMIT 5");
                        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (empty($sessions)) {
                            echo '<p class="no-data">Aucune session récente</p>';
                        } else {
                            foreach ($sessions as $session) {
                                echo '<div class="session-item">';
                                echo '<strong>' . htmlspecialchars(substr($session['topic'] ?? 'N/A', 0, 40)) . '...</strong>';
                                echo '<span class="session-date">' . date('d/m H:i', strtotime($session['created_at'])) . '</span>';
                                echo '</div>';
                            }
                        }
                    } catch (Exception $e) {
                        echo '<p class="no-data">Erreur de chargement</p>';
                    }
                    ?>
                </div>
            </aside>
        </section>

        <!-- Features Section -->
        <section class="features">
            <h2>✨ Fonctionnalités clés</h2>
            <div class="feature-grid">
                <div class="feature-item">
                    <div class="feature-icon">🤖</div>
                    <h3>100% Autonome</h3>
                    <p>Pas besoin d'intervention humaine après le lancement</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📈</div>
                    <h3>Auto-Renforcement</h3>
                    <p>L'IA s'améliore avec le temps (moins d'erreurs, meilleures stratégies)</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">💬</div>
                    <h3>Transparence</h3>
                    <p>L'IA explique ce qu'elle fait et pourquoi à chaque étape</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🧪</div>
                    <h3>Découvertes Réelles</h3>
                    <p>Les mini-apps permettent de valider les hypothèses</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📚</div>
                    <h3>Auto-Documentation</h3>
                    <p>L'IA rédige des articles sur son évolution</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🎯</div>
                    <h3>Adaptabilité</h3>
                    <p>L'utilisateur peut guider l'IA avec des contextes</p>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Gestion des interactions utilisateur
        document.querySelectorAll('.action-card').forEach(card => {
            card.addEventListener('click', function() {
                const action = this.dataset.action;
                const inputSection = document.getElementById('inputSection');
                const questionInput = document.getElementById('questionInput');
                const contextInput = document.getElementById('contextInput');
                const actionType = document.getElementById('actionType');
                const submitBtn = document.getElementById('submitBtn');
                
                inputSection.style.display = 'block';
                
                // Reset
                questionInput.style.display = 'none';
                contextInput.style.display = 'none';
                
                switch(action) {
                    case 'auto':
                        actionType.value = 'auto';
                        contextInput.style.display = 'block';
                        submitBtn.textContent = '🚀 Lancer la recherche automatique';
                        break;
                    case 'question':
                        actionType.value = 'question';
                        questionInput.style.display = 'block';
                        contextInput.style.display = 'block';
                        submitBtn.textContent = '🚀 Rechercher';
                        break;
                    case 'context':
                        actionType.value = 'context';
                        contextInput.style.display = 'block';
                        submitBtn.textContent = '💾 Enregistrer le contexte';
                        break;
                    case 'evolution':
                        window.location.href = 'articles/system/';
                        return;
                }
                
                // Scroll to form
                inputSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });
    </script>
</body>
</html>
