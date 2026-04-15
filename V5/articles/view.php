<?php require_once __DIR__ . '/../../config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article — NEXUS AI</title>
    <link rel="stylesheet" href="../../assets/style.css">
    <style>
        .article-viewer {
            padding: 2rem;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .article-header {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--dim);
        }
        
        .article-back {
            display: inline-block;
            margin-bottom: 1rem;
            color: var(--cyan);
            text-decoration: none;
            font-size: 0.85rem;
        }
        
        .article-back:hover {
            color: var(--acid);
        }
        
        .article-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
            font-size: 0.75rem;
            color: var(--dim);
        }
        
        .article-category {
            padding: 0.25rem 0.75rem;
            background: rgba(0,229,255,0.2);
            color: var(--cyan);
            border-radius: 4px;
            text-transform: uppercase;
        }
        
        .article-title {
            font-family: 'Unbounded', sans-serif;
            font-size: 2rem;
            color: var(--paper);
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        
        .article-content {
            font-size: 0.95rem;
            line-height: 1.8;
            color: var(--paper);
        }
        
        .article-content h1,
        .article-content h2,
        .article-content h3 {
            font-family: 'Unbounded', sans-serif;
            color: var(--acid);
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        
        .article-content h1 { font-size: 1.8rem; }
        .article-content h2 { font-size: 1.5rem; }
        .article-content h3 { font-size: 1.2rem; }
        
        .article-content p {
            margin-bottom: 1.5rem;
        }
        
        .article-content ul,
        .article-content ol {
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }
        
        .article-content li {
            margin-bottom: 0.5rem;
        }
        
        .article-content code {
            background: rgba(200,255,0,0.1);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-family: 'Space Mono', monospace;
            font-size: 0.85rem;
            color: var(--acid);
        }
        
        .article-content pre {
            background: rgba(0,0,0,0.3);
            border: 1px solid var(--dim);
            border-radius: 8px;
            padding: 1.5rem;
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }
        
        .article-content pre code {
            background: transparent;
            padding: 0;
            color: var(--paper);
        }
        
        .article-content blockquote {
            border-left: 3px solid var(--cyan);
            padding-left: 1.5rem;
            margin: 1.5rem 0;
            color: var(--dim);
            font-style: italic;
        }
        
        .article-content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }
        
        .article-content th,
        .article-content td {
            border: 1px solid var(--dim);
            padding: 0.75rem;
            text-align: left;
        }
        
        .article-content th {
            background: rgba(200,255,0,0.1);
            color: var(--acid);
        }
        
        .validation-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(200,255,0,0.1);
            border: 1px solid var(--acid);
            border-radius: 6px;
            margin-top: 1rem;
        }
        
        .validation-score {
            font-family: 'Unbounded', sans-serif;
            font-weight: 700;
            color: var(--acid);
        }
        
        .not-found {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--dim);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <a href="../../index.php" class="nav-logo">🧬 NEXUS AI</a>
        <div class="nav-links">
            <a href="../../index.php">Accueil</a>
            <a href="../../results.php">Résultats</a>
            <a href="../index.php" class="active">Articles</a>
        </div>
        <div class="nav-status">
            <span class="pulse"></span>
            <span>Système en ligne</span>
        </div>
    </nav>
    
    <!-- Article Viewer -->
    <div class="app">
        <div class="article-viewer">
            <?php 
            $file = $_GET['file'] ?? '';
            
            // Security: prevent directory traversal
            $file = basename($file);
            $category = $_GET['cat'] ?? 'medical';
            
            if (!in_array($category, ['medical', 'system'])) {
                $category = 'medical';
            }
            
            $filePath = __DIR__ . '/' . $category . '/' . $file;
            
            if (empty($file) || !file_exists($filePath)) {
                echo '<div class="not-found">';
                echo '<h2>Article non trouvé</h2>';
                echo '<p>L\'article demandé n\'existe pas ou a été supprimé.</p>';
                echo '<a href="../index.php" class="article-back">← Retour aux articles</a>';
                echo '</div>';
            } else {
                $content = file_get_contents($filePath);
                
                // Extract metadata from frontmatter if present
                $title = 'Article';
                $date = '';
                $body = $content;
                
                if (preg_match('/^---\n(.*?)\n---\n(.*)$/s', $content, $matches)) {
                    $frontmatter = $matches[1];
                    $body = $matches[2];
                    
                    if (preg_match('/title:\s*(.+)/', $frontmatter, $m)) {
                        $title = trim($m[1]);
                    }
                    if (preg_match('/date:\s*(.+)/', $frontmatter, $m)) {
                        $date = trim($m[1]);
                    }
                } elseif (preg_match('/^#\s+(.+)/', $content, $matches)) {
                    $title = trim($matches[1]);
                }
                
                // Convert Markdown to HTML (basic conversion)
                $html = $body;
                
                // Headers
                $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $html);
                $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
                $html = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $html);
                
                // Bold and italic
                $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);
                $html = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $html);
                
                // Code blocks
                $html = preg_replace_callback('/```(\w+)?\n(.*?)```/s', function($m) {
                    return '<pre><code class="language-' . ($m[1] ?? '') . '">' . htmlspecialchars($m[2]) . '</code></pre>';
                }, $html);
                
                // Inline code
                $html = preg_replace('/`([^`]+)`/', '<code>$1</code>', $html);
                
                // Lists
                $html = preg_replace('/^\- (.+)$/m', '<li>$1</li>', $html);
                $html = preg_replace('/^\d+\. (.+)$/m', '<li>$1</li>', $html);
                $html = preg_replace('/(<li>.+<\/li>\n?)+/', '<ul>$0</ul>', $html);
                
                // Paragraphs
                $html = preg_replace('/^(?!<[huplo]|<li|<ul)(.+)$/m', '<p>$1</p>', $html);
                
                // Links
                $html = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2">$1</a>', $html);
                
                // Blockquotes
                $html = preg_replace('/^> (.+)$/m', '<blockquote>$1</blockquote>', $html);
                
                echo '<div class="article-header">';
                echo '<a href="../index.php" class="article-back">← Retour aux articles</a>';
                echo '<div class="article-meta">';
                echo '<span class="article-category">' . htmlspecialchars($category) . '</span>';
                if (!empty($date)) {
                    echo '<span>' . date('d/m/Y', strtotime($date)) . '</span>';
                }
                echo '</div>';
                echo '<h1 class="article-title">' . htmlspecialchars($title) . '</h1>';
                echo '</div>';
                
                echo '<div class="article-content">';
                echo $html;
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
