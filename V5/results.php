<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats — NEXUS AI</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .results-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .results-header {
            margin-bottom: 2rem;
        }
        
        .results-header h1 {
            font-family: 'Unbounded', sans-serif;
            color: var(--acid);
            margin-bottom: 1rem;
        }
        
        .session-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--dim);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .session-card:hover {
            border-color: var(--cyan);
            background: rgba(0,229,255,0.05);
        }
        
        .session-title {
            font-family: 'Unbounded', sans-serif;
            font-size: 1.1rem;
            color: var(--paper);
            margin-bottom: 0.5rem;
        }
        
        .session-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
            font-size: 0.75rem;
            color: var(--dim);
        }
        
        .session-status {
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.7rem;
            text-transform: uppercase;
        }
        
        .status-running {
            background: rgba(0,229,255,0.2);
            color: var(--cyan);
        }
        
        .status-completed {
            background: rgba(200,255,0,0.2);
            color: var(--acid);
        }
        
        .status-failed {
            background: rgba(255,45,107,0.2);
            color: var(--mag);
        }
        
        .session-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .btn-view {
            padding: 0.5rem 1rem;
            background: transparent;
            border: 1px solid var(--acid);
            color: var(--acid);
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn-view:hover {
            background: var(--acid);
            color: var(--ink);
        }
        
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--dim);
        }
        
        .filter-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 0.5rem 1rem;
            background: transparent;
            border: 1px solid var(--dim);
            color: var(--paper);
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .filter-btn:hover, .filter-btn.active {
            border-color: var(--acid);
            color: var(--acid);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <a href="index.php" class="nav-logo">🧬 NEXUS AI</a>
        <div class="nav-links">
            <a href="index.php">Accueil</a>
            <a href="results.php" class="active">Résultats</a>
            <a href="articles/">Articles</a>
        </div>
        <div class="nav-status">
            <span class="pulse"></span>
            <span>Système en ligne</span>
        </div>
    </nav>
    
    <!-- Results Content -->
    <div class="app">
        <div class="results-container">
            <div class="results-header">
                <h1>📊 Résultats de Recherche</h1>
                <p>Toutes les sessions de recherche et leurs résultats</p>
            </div>
            
            <!-- Filter Bar -->
            <div class="filter-bar">
                <button class="filter-btn active" data-filter="all">Toutes</button>
                <button class="filter-btn" data-filter="completed">Terminées</button>
                <button class="filter-btn" data-filter="running">En cours</button>
                <button class="filter-btn" data-filter="failed">Échouées</button>
            </div>
            
            <!-- Sessions List -->
            <div id="sessionsList">
                <?php 
                try {
                    $stmt = $pdo->query("SELECT * FROM sessions ORDER BY created_at DESC");
                    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (empty($sessions)) {
                        echo '<div class="no-results">';
                        echo '<h3>Aucune session trouvée</h3>';
                        echo '<p>Lancez une première recherche depuis la page d\'accueil</p>';
                        echo '<a href="index.php" class="btn-view" style="display:inline-block; margin-top:1rem;">Retour à l\'accueil</a>';
                        echo '</div>';
                    } else {
                        foreach ($sessions as $session) {
                            $statusClass = 'status-' . ($session['status'] ?? 'running');
                            $statusLabel = $session['status'] ?? 'En cours';
                            
                            echo '<div class="session-card" data-status="' . htmlspecialchars($session['status'] ?? 'running') . '">';
                            echo '<h3 class="session-title">' . htmlspecialchars($session['topic'] ?? 'Sujet inconnu') . '</h3>';
                            echo '<div class="session-meta">';
                            echo '<span>ID: ' . htmlspecialchars(substr($session['id'], 0, 8)) . '...</span>';
                            echo '<span>Date: ' . date('d/m/Y H:i', strtotime($session['created_at'])) . '</span>';
                            echo '<span class="session-status ' . $statusClass . '">' . htmlspecialchars($statusLabel) . '</span>';
                            echo '</div>';
                            
                            // Count articles for this session
                            try {
                                $artStmt = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE session_id = ?");
                                $artStmt->execute([$session['id']]);
                                $articleCount = $artStmt->fetchColumn();
                                
                                if ($articleCount > 0) {
                                    echo '<div class="session-actions">';
                                    echo '<a href="articles/view.php?id=' . urlencode($session['id']) . '" class="btn-view">📄 Voir l\'article</a>';
                                    echo '</div>';
                                }
                            } catch (Exception $e) {
                                // Silent fail
                            }
                            
                            echo '</div>';
                        }
                    }
                } catch (Exception $e) {
                    echo '<div class="no-results">';
                    echo '<h3>Erreur de chargement</h3>';
                    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <script>
        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.dataset.filter;
                
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Filter sessions
                document.querySelectorAll('.session-card').forEach(card => {
                    if (filter === 'all' || card.dataset.status === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
