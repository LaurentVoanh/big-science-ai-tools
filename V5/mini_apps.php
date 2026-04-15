<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini-Apps — NEXUS AI</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .miniapps-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .miniapps-header {
            margin-bottom: 2rem;
        }
        
        .miniapps-header h1 {
            font-family: 'Unbounded', sans-serif;
            color: var(--acid);
            margin-bottom: 1rem;
        }
        
        .miniapp-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 2rem;
        }
        
        .miniapp-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--dim);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .miniapp-card:hover {
            border-color: var(--cyan);
            background: rgba(0,229,255,0.05);
        }
        
        .miniapp-title {
            font-family: 'Unbounded', sans-serif;
            font-size: 1.1rem;
            color: var(--paper);
            margin-bottom: 0.75rem;
        }
        
        .miniapp-hypothesis {
            font-size: 0.8rem;
            color: var(--dim);
            line-height: 1.6;
            margin-bottom: 1rem;
            font-style: italic;
        }
        
        .miniapp-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.7rem;
            color: var(--dim);
            padding-top: 1rem;
            border-top: 1px solid var(--dim);
        }
        
        .btn-launch {
            padding: 0.5rem 1rem;
            background: var(--acid);
            color: var(--ink);
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-launch:hover {
            background: #d4ff33;
            transform: translateY(-2px);
        }
        
        .no-miniapps {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--dim);
        }
        
        .info-box {
            background: rgba(0,229,255,0.05);
            border-left: 3px solid var(--cyan);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-radius: 6px;
        }
        
        .info-box p {
            font-size: 0.85rem;
            line-height: 1.6;
            color: var(--paper);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <a href="index.php" class="nav-logo">🧬 NEXUS AI</a>
        <div class="nav-links">
            <a href="index.php">Accueil</a>
            <a href="results.php">Résultats</a>
            <a href="articles/">Articles</a>
            <a href="mini_apps.php" class="active">Mini-Apps</a>
        </div>
        <div class="nav-status">
            <span class="pulse"></span>
            <span>Système en ligne</span>
        </div>
    </nav>
    
    <!-- Mini-Apps Content -->
    <div class="app">
        <div class="miniapps-container">
            <div class="miniapps-header">
                <h1>🧪 Mini-Applications de Test</h1>
                <p>Outils interactifs pour valider les hypothèses scientifiques</p>
            </div>
            
            <div class="info-box">
                <p><strong>💡 Comment ça marche ?</strong></p>
                <p>NEXUS AI génère automatiquement des mini-applications pour tester les hypothèses découvertes pendant la recherche. Ces outils permettent de visualiser les données, simuler des scénarios, et valider les prédictions.</p>
            </div>
            
            <!-- Mini-Apps Grid -->
            <div class="miniapp-grid">
                <?php 
                try {
                    $stmt = $pdo->query("SELECT * FROM mini_apps ORDER BY created_at DESC");
                    $miniApps = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (empty($miniApps)) {
                        echo '<div class="no-miniapps" style="grid-column: 1/-1;">';
                        echo '<h3>Aucune mini-application pour le moment</h3>';
                        echo '<p>Les mini-apps seront générées automatiquement lors des sessions de recherche</p>';
                        echo '</div>';
                    } else {
                        foreach ($miniApps as $app) {
                            $hypothesis = json_decode($app['hypothesis'] ?? '{}', true);
                            $hypothesisText = is_array($hypothesis) ? ($hypothesis['text'] ?? 'Hypothèse non disponible') : 'Hypothèse non disponible';
                            
                            echo '<div class="miniapp-card">';
                            echo '<h3 class="miniapp-title">🔬 ' . htmlspecialchars(basename($app['app_path'] ?? 'App')) . '</h3>';
                            echo '<p class="miniapp-hypothesis">' . htmlspecialchars(substr($hypothesisText, 0, 150)) . '...</p>';
                            echo '<div class="miniapp-meta">';
                            echo '<span>' . date('d/m/Y H:i', strtotime($app['created_at'])) . '</span>';
                            
                            if (file_exists(__DIR__ . '/' . $app['app_path'])) {
                                echo '<a href="' . htmlspecialchars($app['app_path']) . '" class="btn-launch">🚀 Lancer</a>';
                            } else {
                                echo '<span style="color: var(--dim);">Fichier non trouvé</span>';
                            }
                            
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                } catch (Exception $e) {
                    echo '<div class="no-miniapps" style="grid-column: 1/-1;">';
                    echo '<h3>Erreur de chargement</h3>';
                    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
