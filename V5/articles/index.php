<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles — NEXUS AI</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .articles-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .articles-header {
            margin-bottom: 2rem;
        }
        
        .articles-header h1 {
            font-family: 'Unbounded', sans-serif;
            color: var(--acid);
            margin-bottom: 1rem;
        }
        
        .article-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }
        
        .article-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--dim);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .article-card:hover {
            border-color: var(--acid);
            background: rgba(200,255,0,0.05);
            transform: translateY(-4px);
        }
        
        .article-category {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: rgba(0,229,255,0.2);
            color: var(--cyan);
            border-radius: 4px;
            font-size: 0.7rem;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        
        .article-title {
            font-family: 'Unbounded', sans-serif;
            font-size: 1rem;
            color: var(--paper);
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }
        
        .article-excerpt {
            font-size: 0.8rem;
            color: var(--dim);
            line-height: 1.6;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .article-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.7rem;
            color: var(--dim);
            padding-top: 1rem;
            border-top: 1px solid var(--dim);
        }
        
        .article-score {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .score-bar {
            width: 60px;
            height: 6px;
            background: var(--dim);
            border-radius: 3px;
            overflow: hidden;
        }
        
        .score-fill {
            height: 100%;
            background: var(--acid);
            transition: width 0.3s ease;
        }
        
        .no-articles {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--dim);
        }
        
        .category-filter {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .category-btn {
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
        
        .category-btn:hover, .category-btn.active {
            border-color: var(--acid);
            color: var(--acid);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <a href="../index.php" class="nav-logo">🧬 NEXUS AI</a>
        <div class="nav-links">
            <a href="../index.php">Accueil</a>
            <a href="../results.php">Résultats</a>
            <a href="index.php" class="active">Articles</a>
        </div>
        <div class="nav-status">
            <span class="pulse"></span>
            <span>Système en ligne</span>
        </div>
    </nav>
    
    <!-- Articles Content -->
    <div class="app">
        <div class="articles-container">
            <div class="articles-header">
                <h1>📚 Bibliothèque d'Articles</h1>
                <p>Tous les articles générés par NEXUS AI</p>
            </div>
            
            <!-- Category Filter -->
            <div class="category-filter">
                <button class="category-btn active" data-category="all">Tous</button>
                <button class="category-btn" data-category="medical">Médical</button>
                <button class="category-btn" data-category="system">Système</button>
            </div>
            
            <!-- Articles Grid -->
            <div class="article-grid">
                <?php 
                try {
                    // Scan medical articles
                    $medicalDir = __DIR__ . '/medical/';
                    $systemDir = __DIR__ . '/system/';
                    
                    $articles = [];
                    
                    // Get medical articles
                    if (is_dir($medicalDir)) {
                        $files = scandir($medicalDir);
                        foreach ($files as $file) {
                            if (preg_match('/^(\d{4}-\d{2}-\d{2})_(.+)\.md$/', $file, $matches)) {
                                $date = $matches[1];
                                $slug = $matches[2];
                                $content = file_get_contents($medicalDir . $file);
                                $title = trim(explode("\n", $content)[0] ?? 'Sans titre');
                                $title = preg_replace('/^#+\s*/', '', $title);
                                
                                $articles[] = [
                                    'category' => 'medical',
                                    'date' => $date,
                                    'slug' => $slug,
                                    'title' => $title,
                                    'excerpt' => substr(strip_tags($content), 0, 200) . '...',
                                    'path' => 'medical/' . $file
                                ];
                            }
                        }
                    }
                    
                    // Get system articles
                    if (is_dir($systemDir)) {
                        $files = scandir($systemDir);
                        foreach ($files as $file) {
                            if (preg_match('/^(\d{4}-\d{2}-\d{2})_(.+)\.md$/', $file, $matches)) {
                                $date = $matches[1];
                                $slug = $matches[2];
                                $content = file_get_contents($systemDir . $file);
                                $title = trim(explode("\n", $content)[0] ?? 'Sans titre');
                                $title = preg_replace('/^#+\s*/', '', $title);
                                
                                $articles[] = [
                                    'category' => 'system',
                                    'date' => $date,
                                    'slug' => $slug,
                                    'title' => $title,
                                    'excerpt' => substr(strip_tags($content), 0, 200) . '...',
                                    'path' => 'system/' . $file
                                ];
                            }
                        }
                    }
                    
                    // Sort by date descending
                    usort($articles, function($a, $b) {
                        return strcmp($b['date'], $a['date']);
                    });
                    
                    if (empty($articles)) {
                        echo '<div class="no-articles" style="grid-column: 1/-1;">';
                        echo '<h3>Aucun article pour le moment</h3>';
                        echo '<p>Les articles générés apparaîtront ici automatiquement</p>';
                        echo '</div>';
                    } else {
                        foreach ($articles as $article) {
                            echo '<a href="' . htmlspecialchars($article['path']) . '" class="article-card" data-category="' . $article['category'] . '">';
                            echo '<span class="article-category">' . $article['category'] . '</span>';
                            echo '<h3 class="article-title">' . htmlspecialchars($article['title']) . '</h3>';
                            echo '<p class="article-excerpt">' . htmlspecialchars($article['excerpt']) . '</p>';
                            echo '<div class="article-meta">';
                            echo '<span>' . date('d/m/Y', strtotime($article['date'])) . '</span>';
                            echo '<span>📄 Lire →</span>';
                            echo '</div>';
                            echo '</a>';
                        }
                    }
                } catch (Exception $e) {
                    echo '<div class="no-articles" style="grid-column: 1/-1;">';
                    echo '<h3>Erreur de chargement</h3>';
                    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <script>
        // Category filter functionality
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const category = this.dataset.category;
                
                // Update active button
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Filter articles
                document.querySelectorAll('.article-card').forEach(card => {
                    if (category === 'all' || card.dataset.category === category) {
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
