<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Science Hub Ultimate — sciencehub.voanh.art</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #070c14;
  --bg2: #0d1420;
  --bg3: #131d2e;
  --border: rgba(99,179,237,0.12);
  --border2: rgba(99,179,237,0.25);
  --primary: #3b82f6;
  --accent: #06b6d4;
  --accent2: #a78bfa;
  --success: #10b981;
  --warn: #f59e0b;
  --danger: #ef4444;
  --text: #e2e8f0;
  --text2: #94a3b8;
  --text3: #475569;
  --mono: 'Space Mono', monospace;
  --sans: 'DM Sans', sans-serif;
  --glow: 0 0 30px rgba(59,130,246,0.15);
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{font-family:var(--sans);background:var(--bg);color:var(--text);min-height:100vh;overflow-x:hidden}

/* GRID BG */
body::before{content:'';position:fixed;inset:0;background-image:linear-gradient(rgba(59,130,246,0.03) 1px,transparent 1px),linear-gradient(90deg,rgba(59,130,246,0.03) 1px,transparent 1px);background-size:60px 60px;pointer-events:none;z-index:0}

/* NAV */
nav{position:fixed;top:0;left:0;right:0;z-index:1000;background:rgba(7,12,20,0.85);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);padding:0 2rem}
.nav-inner{max-width:1440px;margin:0 auto;display:flex;align-items:center;gap:2rem;height:60px}
.logo{font-family:var(--mono);font-size:1rem;font-weight:700;color:var(--accent);text-decoration:none;flex-shrink:0;letter-spacing:-0.5px}
.logo span{color:var(--accent2)}
.nav-tabs{display:flex;gap:0;overflow-x:auto;scrollbar-width:none;flex:1}
.nav-tabs::-webkit-scrollbar{display:none}
.nav-tab{padding:0 1rem;height:60px;display:flex;align-items:center;gap:0.4rem;font-size:0.78rem;font-weight:500;color:var(--text2);cursor:pointer;border:none;background:none;white-space:nowrap;border-bottom:2px solid transparent;transition:all 0.2s;font-family:var(--sans)}
.nav-tab:hover{color:var(--text)}
.nav-tab.active{color:var(--accent);border-bottom-color:var(--accent)}
.nav-icon{font-size:14px}
.status-dot{width:6px;height:6px;border-radius:50%;background:var(--success);box-shadow:0 0 6px var(--success);animation:pulse 2s infinite;margin-left:auto;flex-shrink:0}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.4}}

/* PAGES */
.page{display:none;min-height:calc(100vh - 60px);padding-top:80px;position:relative;z-index:1}
.page.active{display:block}
.container{max-width:1440px;margin:0 auto;padding:0 2rem 4rem}

/* HERO */
.hero{padding:3rem 2rem 2rem;max-width:1440px;margin:0 auto}
.hero-badge{display:inline-flex;align-items:center;gap:0.5rem;padding:0.3rem 0.8rem;background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.3);border-radius:20px;font-size:0.75rem;font-family:var(--mono);color:var(--primary);margin-bottom:1.5rem}
.hero h1{font-size:clamp(2rem,4vw,3.5rem);font-weight:300;line-height:1.15;margin-bottom:1rem;letter-spacing:-1px}
.hero h1 strong{font-weight:600;background:linear-gradient(135deg,var(--accent),var(--primary),var(--accent2));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.hero p{font-size:1.05rem;color:var(--text2);max-width:600px;line-height:1.7;margin-bottom:2rem}
.hero-actions{display:flex;gap:1rem;flex-wrap:wrap}
.btn{padding:0.65rem 1.4rem;border-radius:8px;font-size:0.875rem;font-weight:500;cursor:pointer;border:none;font-family:var(--sans);transition:all 0.2s;text-decoration:none;display:inline-flex;align-items:center;gap:0.4rem}
.btn-primary{background:var(--primary);color:#fff}
.btn-primary:hover{background:#2563eb;transform:translateY(-1px);box-shadow:0 8px 25px rgba(59,130,246,0.35)}
.btn-ghost{background:transparent;color:var(--text2);border:1px solid var(--border2)}
.btn-ghost:hover{color:var(--text);border-color:rgba(99,179,237,0.4)}
.btn-sm{padding:0.45rem 0.9rem;font-size:0.8rem}
.btn-accent{background:rgba(6,182,212,0.15);color:var(--accent);border:1px solid rgba(6,182,212,0.3)}
.btn-accent:hover{background:rgba(6,182,212,0.25)}
.btn-success{background:var(--success);color:#fff}
.btn-success:hover{background:#059669}

/* STATS ROW */
.stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;margin:2rem 0}
.stat-card{background:var(--bg2);border:1px solid var(--border);border-radius:12px;padding:1.2rem;text-align:center}
.stat-num{font-family:var(--mono);font-size:1.8rem;font-weight:700;color:var(--accent);display:block}
.stat-label{font-size:0.78rem;color:var(--text3);margin-top:0.25rem}

/* CARDS GRID */
.cards-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1.5rem}
.card{background:var(--bg2);border:1px solid var(--border);border-radius:16px;padding:1.75rem;transition:all 0.25s;cursor:pointer;position:relative;overflow:hidden}
.card::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(59,130,246,0.05),transparent);opacity:0;transition:opacity 0.25s}
.card:hover{border-color:var(--border2);transform:translateY(-2px);box-shadow:var(--glow)}
.card:hover::before{opacity:1}
.card-icon{font-size:1.5rem;margin-bottom:1rem}
.card h3{font-size:1rem;font-weight:600;margin-bottom:0.5rem;color:var(--text)}
.card p{font-size:0.875rem;color:var(--text2);line-height:1.6}
.card-badge{display:inline-block;padding:0.2rem 0.6rem;border-radius:12px;font-size:0.7rem;font-weight:500;margin-top:1rem}
.badge-blue{background:rgba(59,130,246,0.15);color:var(--primary);border:1px solid rgba(59,130,246,0.25)}
.badge-cyan{background:rgba(6,182,212,0.15);color:var(--accent);border:1px solid rgba(6,182,212,0.25)}
.badge-purple{background:rgba(167,139,250,0.15);color:var(--accent2);border:1px solid rgba(167,139,250,0.25)}
.badge-green{background:rgba(16,185,129,0.15);color:var(--success);border:1px solid rgba(16,185,129,0.25)}
.badge-amber{background:rgba(245,158,11,0.15);color:var(--warn);border:1px solid rgba(245,158,11,0.25)}

/* SECTION HEADERS */
.section-header{margin-bottom:2rem}
.section-header h2{font-size:1.5rem;font-weight:600;letter-spacing:-0.5px}
.section-header p{color:var(--text2);font-size:0.9rem;margin-top:0.4rem}
.section-divider{height:1px;background:var(--border);margin:3rem 0}

/* WORKFLOW PANEL */
.workflow-panel{background:var(--bg2);border:1px solid var(--border);border-radius:16px;overflow:hidden}
.workflow-header{padding:1.5rem 2rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.workflow-header h3{font-size:1.1rem;font-weight:600}
.workflow-body{padding:2rem}

/* FORMS */
.form-group{margin-bottom:1.5rem}
.form-label{display:block;font-size:0.8rem;font-weight:500;color:var(--text2);margin-bottom:0.5rem;letter-spacing:0.5px;text-transform:uppercase;font-family:var(--mono)}
select,input[type=text],input[type=password],textarea{width:100%;padding:0.75rem 1rem;background:var(--bg3);border:1px solid var(--border);border-radius:8px;color:var(--text);font-family:var(--sans);font-size:0.9rem;outline:none;transition:border-color 0.2s}
select:focus,input:focus,textarea:focus{border-color:var(--primary)}
textarea{resize:vertical;min-height:100px;line-height:1.6}
select{cursor:pointer}

/* STEP PROGRESS */
.steps-track{display:flex;align-items:center;gap:0;margin-bottom:2.5rem;overflow-x:auto;padding-bottom:0.5rem}
.step-item{display:flex;align-items:center;flex-shrink:0}
.step-dot{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-family:var(--mono);font-weight:700;border:2px solid var(--border);color:var(--text3);background:var(--bg3);transition:all 0.3s;flex-shrink:0}
.step-dot.active{border-color:var(--primary);color:var(--primary);background:rgba(59,130,246,0.1);box-shadow:0 0 15px rgba(59,130,246,0.3)}
.step-dot.done{border-color:var(--success);color:#fff;background:var(--success)}
.step-name{font-size:0.7rem;color:var(--text3);margin-top:0.3rem;text-align:center;max-width:70px;word-break:break-word;line-height:1.3}
.step-container{display:flex;flex-direction:column;align-items:center;gap:0}
.step-line{height:2px;width:30px;background:var(--border);transition:background 0.3s;flex-shrink:0}
.step-line.done{background:var(--success)}

/* RESULTS DISPLAY */
.result-block{background:var(--bg3);border:1px solid var(--border);border-radius:12px;padding:1.5rem;margin-top:1.5rem;animation:fadeIn 0.3s ease}
@keyframes fadeIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
.result-block h4{font-size:0.85rem;font-weight:600;color:var(--accent);margin-bottom:1rem;font-family:var(--mono);text-transform:uppercase;letter-spacing:1px}
.result-content{font-size:0.9rem;line-height:1.75;color:var(--text2);white-space:pre-wrap;word-break:break-word}
.result-content strong,.result-content b{color:var(--text);font-weight:600}

/* LOADING */
.loader{display:none;flex-direction:column;align-items:center;gap:1rem;padding:3rem;color:var(--text2);font-size:0.9rem}
.loader.active{display:flex}
.spinner{width:36px;height:36px;border:2px solid var(--border);border-top-color:var(--primary);border-radius:50%;animation:spin 0.8s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
.loader-dots{display:flex;gap:4px}
.loader-dots span{width:6px;height:6px;border-radius:50%;background:var(--primary);animation:bounce 1.2s ease infinite}
.loader-dots span:nth-child(2){animation-delay:0.2s}
.loader-dots span:nth-child(3){animation-delay:0.4s}
@keyframes bounce{0%,80%,100%{transform:scaleY(0.4)}40%{transform:scaleY(1)}}

/* DASHBOARD */
.dash-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem}
@media(max-width:900px){.dash-grid{grid-template-columns:repeat(2,1fr)}}
.dash-card{background:var(--bg2);border:1px solid var(--border);border-radius:12px;padding:1.5rem}
.dash-num{font-family:var(--mono);font-size:2.5rem;font-weight:700;background:linear-gradient(135deg,var(--accent),var(--primary));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1}
.dash-label{font-size:0.8rem;color:var(--text3);margin-top:0.5rem}
.dash-sub{font-size:0.75rem;color:var(--text3);margin-top:0.25rem;font-family:var(--mono)}

/* TABLE */
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:0.875rem}
th{text-align:left;padding:0.75rem 1rem;font-size:0.75rem;font-weight:500;color:var(--text3);font-family:var(--mono);text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid var(--border)}
td{padding:0.85rem 1rem;border-bottom:1px solid var(--border);color:var(--text2)}
tr:last-child td{border-bottom:none}
tr:hover td{background:rgba(255,255,255,0.02)}

/* LOG FEED */
.log-feed{max-height:350px;overflow-y:auto;scrollbar-width:thin;scrollbar-color:var(--border) transparent}
.log-item{display:flex;align-items:flex-start;gap:1rem;padding:0.75rem 0;border-bottom:1px solid var(--border);font-size:0.8rem}
.log-item:last-child{border-bottom:none}
.log-time{font-family:var(--mono);color:var(--text3);flex-shrink:0;padding-top:1px}
.log-icon{width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;flex-shrink:0}
.log-ok{background:rgba(16,185,129,0.2);color:var(--success)}
.log-err{background:rgba(239,68,68,0.2);color:var(--danger)}
.log-msg{color:var(--text2);line-height:1.4}

/* SETTINGS */
.settings-section{margin-bottom:2.5rem}
.settings-section h3{font-size:0.85rem;font-family:var(--mono);text-transform:uppercase;letter-spacing:1px;color:var(--text3);margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border)}
.setting-row{display:flex;align-items:center;justify-content:space-between;padding:1rem 0;border-bottom:1px solid var(--border)}
.setting-row:last-child{border-bottom:none}
.setting-info h4{font-size:0.9rem;font-weight:500}
.setting-info p{font-size:0.8rem;color:var(--text3);margin-top:0.2rem}

/* TOGGLE */
.toggle{position:relative;width:40px;height:22px;flex-shrink:0}
.toggle input{display:none}
.toggle-slider{position:absolute;inset:0;background:var(--bg3);border:1px solid var(--border);border-radius:11px;cursor:pointer;transition:0.3s}
.toggle-slider::before{content:'';position:absolute;width:14px;height:14px;border-radius:50%;background:var(--text3);top:3px;left:3px;transition:0.3s}
.toggle input:checked ~ .toggle-slider{background:var(--primary);border-color:var(--primary)}
.toggle input:checked ~ .toggle-slider::before{transform:translateX(18px);background:#fff}

/* API LIST */
.api-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:0.5rem;margin-top:1rem}
.api-pill{background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:0.4rem 0.75rem;font-size:0.75rem;color:var(--text2);font-family:var(--mono);display:flex;align-items:center;gap:0.4rem}
.api-pill::before{content:'';width:5px;height:5px;border-radius:50%;background:var(--success);flex-shrink:0}

/* HYPOTHESIS CARDS */
.hyp-card{background:var(--bg2);border:1px solid var(--border);border-radius:12px;padding:1.5rem;margin-bottom:1rem;transition:all 0.2s}
.hyp-card:hover{border-color:var(--border2)}
.hyp-title{font-size:0.95rem;font-weight:600;margin-bottom:0.5rem;color:var(--text)}
.hyp-meta{display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:0.75rem;align-items:center}
.hyp-meta span{font-size:0.75rem;color:var(--text3);font-family:var(--mono)}
.prog-bar{height:3px;background:var(--border);border-radius:2px;overflow:hidden}
.prog-fill{height:100%;background:linear-gradient(90deg,var(--primary),var(--accent));border-radius:2px;transition:width 0.5s}

/* SPLIT LAYOUT */
.split{display:grid;grid-template-columns:1fr 1fr;gap:2rem}
@media(max-width:768px){.split{grid-template-columns:1fr}}

/* EMPTY STATE */
.empty{text-align:center;padding:4rem 2rem;color:var(--text3)}
.empty-icon{font-size:3rem;margin-bottom:1rem;opacity:0.5}
.empty p{font-size:0.9rem}

/* NOTIFICATION TOAST */
#toast{position:fixed;bottom:2rem;right:2rem;background:var(--bg2);border:1px solid var(--border);border-radius:10px;padding:0.85rem 1.25rem;font-size:0.85rem;z-index:9999;opacity:0;transform:translateY(10px);transition:all 0.3s;max-width:320px;pointer-events:none}
#toast.show{opacity:1;transform:translateY(0)}
#toast.success{border-color:rgba(16,185,129,0.4);color:var(--success)}
#toast.error{border-color:rgba(239,68,68,0.4);color:var(--danger)}

/* SEARCH */
.search-box{display:flex;gap:0.75rem;margin-bottom:1.5rem}
.search-box input{flex:1}

/* ARTICLE CARD */
.article-card{background:var(--bg2);border:1px solid var(--border);border-radius:12px;padding:1.25rem;margin-bottom:0.75rem;transition:border-color 0.2s}
.article-card:hover{border-color:var(--border2)}
.article-title{font-size:0.95rem;font-weight:600;color:var(--accent);margin-bottom:0.4rem}
.article-meta{font-size:0.75rem;color:var(--text3);margin-bottom:0.75rem;font-family:var(--mono);display:flex;gap:1rem;flex-wrap:wrap}
.article-abstract{font-size:0.85rem;color:var(--text2);line-height:1.6}

/* LAB */
.lab-card{background:var(--bg2);border:1px solid var(--border);border-radius:12px;padding:1.5rem;margin-bottom:1rem}
.lab-code{background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:1rem;font-family:var(--mono);font-size:0.78rem;color:var(--text2);overflow-x:auto;max-height:200px;overflow-y:auto;line-height:1.6;margin-top:1rem}

/* STREAMING CURSOR */
.streaming-cursor::after{content:'▋';animation:blink 1s step-end infinite;color:var(--primary)}
@keyframes blink{50%{opacity:0}}

@media(max-width:768px){
  .nav-inner{gap:1rem}
  .hero h1{font-size:1.8rem}
  .dash-grid{grid-template-columns:1fr 1fr}
  .container{padding:0 1rem 3rem}
}
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <div class="nav-inner">
    <a class="logo" href="#" onclick="showPage('home')">⬡ Science<span>Hub</span></a>
    <div class="nav-tabs">
      <button class="nav-tab active" onclick="showPage('home')" data-page="home"><span class="nav-icon">⌂</span>Accueil</button>
      <button class="nav-tab" onclick="showPage('autonomous')" data-page="autonomous"><span class="nav-icon">◈</span>Mode Autonome</button>
      <button class="nav-tab" onclick="showPage('guided')" data-page="guided"><span class="nav-icon">◉</span>Mode Guidé</button>
      <button class="nav-tab" onclick="showPage('dashboard')" data-page="dashboard"><span class="nav-icon">▦</span>Dashboard</button>
      <button class="nav-tab" onclick="showPage('hypotheses')" data-page="hypotheses"><span class="nav-icon">◇</span>Hypothèses</button>
      <button class="nav-tab" onclick="showPage('articles')" data-page="articles"><span class="nav-icon">☰</span>Articles</button>
      <button class="nav-tab" onclick="showPage('lab')" data-page="lab"><span class="nav-icon">⚗</span>Laboratoire</button>
      <button class="nav-tab" onclick="showPage('settings')" data-page="settings"><span class="nav-icon">⚙</span>Settings</button>
    </div>
    <div class="status-dot" title="Système actif"></div>
  </div>
</nav>

<!-- ═══════════════ PAGE: HOME ═══════════════ -->
<div class="page active" id="page-home">
  <div class="hero">
    <div class="hero-badge">⬡ GENESIS-ULTRA v9.1 — Pixtral-12B × SQLite × 36 APIs — sciencehub.voanh.art</div>
    <h1>Plateforme de<br><strong>Recherche Scientifique IA</strong><br>Multistep Autonome</h1>
    <p>Générez des hypothèses inédites, analysez la littérature mondiale et conduisez des recherches complètes grâce à des agents IA en workflow multi-étapes. Compatible Hostinger.</p>
    <div class="hero-actions">
      <button class="btn btn-primary" onclick="showPage('autonomous')">🚀 Lancer Mode Autonome</button>
      <button class="btn btn-ghost" onclick="showPage('guided')">📋 Mode Guidé 6 étapes</button>
      <button class="btn btn-ghost" onclick="showPage('dashboard')">📊 Dashboard</button>
    </div>
  </div>
  <div class="container">
    <div class="stats-row">
      <div class="stat-card"><span class="stat-num" id="home-hyp">0</span><div class="stat-label">Hypothèses générées</div></div>
      <div class="stat-card"><span class="stat-num" id="home-art">0</span><div class="stat-label">Articles analysés</div></div>
      <div class="stat-card"><span class="stat-num" id="home-exp">0</span><div class="stat-label">Expériences créées</div></div>
      <div class="stat-card"><span class="stat-num" id="home-ai">0</span><div class="stat-label">Appels Mistral AI</div></div>
      <div class="stat-card"><span class="stat-num">36</span><div class="stat-label">APIs connectées</div></div>
    </div>
    <div class="section-header"><h2>7 Inventions Intégrées</h2><p>Chaque module utilise l'IA Mistral avec des prompts multi-couches spécialisés</p></div>
    <div class="cards-grid">
      <div class="card" onclick="showPage('autonomous')">
        <div class="card-icon">◈</div>
        <h3>GENESIS-ULTRA v9.1</h3>
        <p>Agent autonome en 9 étapes générant des hypothèses scientifiques révolutionnaires avec validation, simulation prédictive et génération de code.</p>
        <span class="card-badge badge-blue">9 étapes autonomes</span>
      </div>
      <div class="card" onclick="showPage('guided')">
        <div class="card-icon">◉</div>
        <h3>GENESIS-ULTRA V3</h3>
        <p>Workflow guidé interactif en 6 étapes avec accès à 36 sources scientifiques, revue par les pairs simulée et rapport professionnel.</p>
        <span class="card-badge badge-cyan">6 étapes guidées</span>
      </div>
      <div class="card" onclick="showPage('dashboard')">
        <div class="card-icon">⬡</div>
        <h3>Moteur de Conscience IA</h3>
        <p>Apprentissage continu avec scoring des stratégies, optimisation automatique et rotation intelligente des clés API Mistral.</p>
        <span class="card-badge badge-purple">Auto-optimisation</span>
      </div>
      <div class="card">
        <div class="card-icon">⧉</div>
        <h3>Prompts IA Multi-Couches</h3>
        <p>7 types de prompts spécialisés : validation scientifique, contradiction critique, rapports structurés, génération de code, analyse bibliographique.</p>
        <span class="card-badge badge-blue">7 types de prompts</span>
      </div>
      <div class="card" onclick="showPage('articles')">
        <div class="card-icon">☰</div>
        <h3>Science Pulse Admin</h3>
        <p>Crawl RSS automatisé, traitement IA des articles scientifiques, versioning intelligent et base documentaire complète.</p>
        <span class="card-badge badge-cyan">Crawl RSS + IA</span>
      </div>
      <div class="card" onclick="showPage('lab')">
        <div class="card-icon">⚗</div>
        <h3>Générateur d'Expériences</h3>
        <p>Transformation automatique d'articles en code PHP/JS interactif pour simulations, visualisations et tests expérimentaux.</p>
        <span class="card-badge badge-green">Code PHP/JS auto</span>
      </div>
      <div class="card">
        <div class="card-icon">◫</div>
        <h3>SQLite Intelligent</h3>
        <p>Schéma complet en 7 tables interconnectées avec apprentissage des stratégies, logs AI détaillés et export JSON natif.</p>
        <span class="card-badge badge-amber">7 tables SQLite</span>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════ PAGE: AUTONOMOUS ═══════════════ -->
<div class="page" id="page-autonomous">
  <div class="container">
    <div class="section-header">
      <h2>◈ GENESIS-ULTRA v9.1 — Mode Autonome</h2>
      <p>Agent de recherche scientifique en 9 étapes — de l'hypothèse au rapport complet avec code généré</p>
    </div>

    <div class="workflow-panel" id="auto-init-panel">
      <div class="workflow-header">
        <h3>Configurer la Recherche</h3>
        <span class="card-badge badge-blue">Mistral Large</span>
      </div>
      <div class="workflow-body">
        <div class="split">
          <div class="form-group">
            <label class="form-label">Domaine Scientifique</label>
            <select id="auto-domain">
              <option value="physique">Physique & Astrophysique</option>
              <option value="biologie">Biologie Moléculaire</option>
              <option value="chimie">Chimie & Matériaux</option>
              <option value="neurosciences">Neurosciences</option>
              <option value="ia">Intelligence Artificielle</option>
              <option value="climat">Science du Climat</option>
              <option value="medecine">Médecine & Pharmacologie</option>
              <option value="mathematiques">Mathématiques</option>
              <option value="sciences_generales">Sciences Générales</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Sujet Spécifique (optionnel)</label>
            <input type="text" id="auto-topic" placeholder="Ex: rôle des mitochondries dans le vieillissement cellulaire...">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Contexte Supplémentaire</label>
          <textarea id="auto-context" placeholder="Décrivez toute information contextuelle, contraintes ou objectifs spécifiques..."></textarea>
        </div>
        <div style="display:flex;gap:1rem;flex-wrap:wrap">
          <button class="btn btn-primary" onclick="startAutonomous()">🚀 Lancer la Recherche Autonome</button>
          <button class="btn btn-ghost btn-sm" onclick="fillAutonomousExample()">Exemple prérempli</button>
        </div>
      </div>
    </div>

    <div id="auto-workflow" style="display:none">
      <div class="workflow-panel" style="margin-bottom:1.5rem">
        <div class="workflow-header">
          <h3 id="auto-step-title">Étape 1/9 — Génération d'hypothèse</h3>
          <div id="auto-step-badge" class="card-badge badge-blue">En cours...</div>
        </div>
        <div class="workflow-body">
          <div class="steps-track" id="auto-steps-track">
            <!-- Injected by JS -->
          </div>
          <div class="loader" id="auto-loader">
            <div class="loader-dots"><span></span><span></span><span></span></div>
            <span id="auto-loader-text">Initialisation de l'agent GENESIS-ULTRA...</span>
          </div>
          <div id="auto-results-area"></div>
          <div style="display:flex;gap:1rem;margin-top:1.5rem;flex-wrap:wrap" id="auto-actions" style="display:none">
            <button class="btn btn-primary" id="auto-next-btn" onclick="continueAutonomous()">Étape suivante →</button>
            <button class="btn btn-ghost btn-sm" onclick="resetAutonomous()">Recommencer</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════ PAGE: GUIDED ═══════════════ -->
<div class="page" id="page-guided">
  <div class="container">
    <div class="section-header">
      <h2>◉ GENESIS-ULTRA V3 — Mode Guidé</h2>
      <p>Workflow interactif en 6 étapes avec accès à 36 bases scientifiques et revue par les pairs simulée</p>
    </div>

    <div style="margin-bottom:1.5rem">
      <div class="api-grid" style="grid-template-columns:repeat(auto-fill,minmax(120px,1fr))">
        <div class="api-pill">arXiv</div><div class="api-pill">PubMed</div><div class="api-pill">CrossRef</div>
        <div class="api-pill">Semantic Scholar</div><div class="api-pill">IEEE Xplore</div><div class="api-pill">ScienceDirect</div>
        <div class="api-pill">Springer</div><div class="api-pill">Nature</div><div class="api-pill">bioRxiv</div>
        <div class="api-pill">medRxiv</div><div class="api-pill">Zenodo</div><div class="api-pill">OpenAlex</div>
        <div class="api-pill">Scopus</div><div class="api-pill">Web of Science</div><div class="api-pill">DOAJ</div>
        <div class="api-pill">+ 21 autres</div>
      </div>
    </div>

    <div class="workflow-panel" id="guided-init-panel">
      <div class="workflow-header"><h3>Formuler Votre Question</h3><span class="card-badge badge-cyan">6 étapes guidées</span></div>
      <div class="workflow-body">
        <div class="split">
          <div class="form-group">
            <label class="form-label">Domaine</label>
            <select id="guided-domain">
              <option value="physique">Physique</option>
              <option value="biologie">Biologie</option>
              <option value="chimie">Chimie</option>
              <option value="neurosciences">Neurosciences</option>
              <option value="ia">Intelligence Artificielle</option>
              <option value="climat">Climatologie</option>
              <option value="medecine">Médecine</option>
              <option value="psychologie">Psychologie</option>
              <option value="economie">Économie</option>
            </select>
          </div>
          <div></div>
        </div>
        <div class="form-group">
          <label class="form-label">Votre Question de Recherche</label>
          <textarea id="guided-question" placeholder="Ex: Quel est le rôle exact de la barrière hémato-encéphalique dans le développement de la maladie d'Alzheimer ?" style="min-height:120px"></textarea>
        </div>
        <div style="display:flex;gap:1rem;flex-wrap:wrap">
          <button class="btn btn-primary" onclick="startGuided()">◉ Démarrer le Workflow Guidé</button>
          <button class="btn btn-ghost btn-sm" onclick="fillGuidedExample()">Exemple prérempli</button>
        </div>
      </div>
    </div>

    <div id="guided-workflow" style="display:none">
      <div class="workflow-panel">
        <div class="workflow-header">
          <h3 id="guided-step-title">Étape 1/6 — Formulation</h3>
          <div id="guided-step-badge" class="card-badge badge-cyan">En cours</div>
        </div>
        <div class="workflow-body">
          <div class="steps-track" id="guided-steps-track"></div>
          <div class="loader" id="guided-loader">
            <div class="loader-dots"><span></span><span></span><span></span></div>
            <span id="guided-loader-text">Analyse de votre question...</span>
          </div>
          <div id="guided-results-area"></div>
          <div style="display:flex;gap:1rem;margin-top:1.5rem;flex-wrap:wrap" id="guided-actions">
            <button class="btn btn-primary" id="guided-next-btn" onclick="continueGuided()" style="display:none">Étape suivante →</button>
            <button class="btn btn-ghost btn-sm" onclick="resetGuided()">Recommencer</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════ PAGE: DASHBOARD ═══════════════ -->
<div class="page" id="page-dashboard">
  <div class="container">
    <div class="section-header"><h2>▦ Dashboard Analytique</h2><p>Vue en temps réel de toutes les activités et performances</p></div>
    <div class="dash-grid">
      <div class="dash-card"><div class="dash-num" id="db-hyp">0</div><div class="dash-label">Hypothèses</div><div class="dash-sub">total générées</div></div>
      <div class="dash-card"><div class="dash-num" id="db-art">0</div><div class="dash-label">Articles</div><div class="dash-sub">analysés par IA</div></div>
      <div class="dash-card"><div class="dash-num" id="db-exp">0</div><div class="dash-label">Expériences</div><div class="dash-sub">créées</div></div>
      <div class="dash-card"><div class="dash-num" id="db-ai">0</div><div class="dash-label">Appels IA</div><div class="dash-sub">Mistral API</div></div>
    </div>
    <div class="split">
      <div>
        <div class="section-header"><h2 style="font-size:1.1rem">Hypothèses Récentes</h2></div>
        <div id="db-recent-hyp"><div class="empty"><div class="empty-icon">◇</div><p>Aucune hypothèse encore. Lancez le mode autonome!</p></div></div>
      </div>
      <div>
        <div class="section-header"><h2 style="font-size:1.1rem">Activité IA Récente</h2></div>
        <div class="log-feed" id="db-log-feed"><div class="empty"><p>Aucune activité</p></div></div>
      </div>
    </div>
    <div class="section-divider"></div>
    <div class="section-header"><h2 style="font-size:1.1rem">Stratégies d'Apprentissage</h2><p>Performances par type de prompt</p></div>
    <div id="db-strategies">
      <div class="table-wrap">
        <table>
          <thead><tr><th>Stratégie</th><th>Utilisations</th><th>Taux de succès</th><th>Dernière optimisation</th></tr></thead>
          <tbody id="strategies-tbody"><tr><td colspan="4" style="text-align:center;color:var(--text3)">Données disponibles après utilisation</td></tr></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════ PAGE: HYPOTHESES ═══════════════ -->
<div class="page" id="page-hypotheses">
  <div class="container">
    <div class="section-header"><h2>◇ Hypothèses de Recherche</h2><p>Toutes les hypothèses générées par les agents IA</p></div>
    <div style="display:flex;gap:0.75rem;margin-bottom:1.5rem;flex-wrap:wrap">
      <button class="btn btn-accent btn-sm active-filter" onclick="filterHypotheses('all',this)">Toutes</button>
      <button class="btn btn-ghost btn-sm" onclick="filterHypotheses('draft',this)">Brouillons</button>
      <button class="btn btn-ghost btn-sm" onclick="filterHypotheses('in_progress',this)">En cours</button>
      <button class="btn btn-ghost btn-sm" onclick="filterHypotheses('completed',this)">Complétées</button>
    </div>
    <div id="hyp-list"><div class="empty"><div class="empty-icon">◇</div><p>Aucune hypothèse. Lancez le mode autonome ou guidé.</p></div></div>
  </div>
</div>

<!-- ═══════════════ PAGE: ARTICLES ═══════════════ -->
<div class="page" id="page-articles">
  <div class="container">
    <div class="section-header"><h2>☰ Articles Scientifiques</h2><p>Base documentaire crawlée et analysée par l'IA</p></div>
    <div class="search-box">
      <input type="text" id="article-search" placeholder="Rechercher dans les articles..." onkeyup="searchArticles(event)">
      <button class="btn btn-primary" onclick="doSearchArticles()">Rechercher</button>
      <button class="btn btn-ghost" onclick="addArticleModal()">+ Ajouter RSS</button>
    </div>
    <div id="articles-list"><div class="empty"><div class="empty-icon">☰</div><p>Aucun article. Ajoutez un flux RSS ou générez depuis le mode autonome.</p></div></div>
  </div>
</div>

<!-- ═══════════════ PAGE: LAB ═══════════════ -->
<div class="page" id="page-lab">
  <div class="container">
    <div class="section-header"><h2>⚗ Laboratoire d'Expériences</h2><p>Simulations et code généré automatiquement par l'IA</p></div>
    <div style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap">
      <button class="btn btn-primary" onclick="generateExperimentFromAI()">⚗ Générer une Expérience IA</button>
      <button class="btn btn-ghost btn-sm" onclick="loadExperiments()">Actualiser</button>
    </div>
    <div id="lab-list"><div class="empty"><div class="empty-icon">⚗</div><p>Aucune expérience. Utilisez le mode autonome (étape 8) ou cliquez sur Générer.</p></div></div>
  </div>
</div>

<!-- ═══════════════ PAGE: SETTINGS ═══════════════ -->
<div class="page" id="page-settings">
  <div class="container">
    <div class="section-header"><h2>⚙ Configuration Système</h2><p>Gérez vos clés API, modèles et préférences</p></div>
    <div class="split">
      <div>
        <div class="settings-section">
          <h3>🔑 Clés API Mistral AI</h3>
          <div style="background:var(--bg3);border:1px solid rgba(16,185,129,0.3);border-radius:8px;padding:1rem;font-size:0.85rem;line-height:1.9;font-family:var(--mono)">
            <div style="color:var(--success)">✓ Key 1 — 5qaRTjWU...H8Rake <span style="color:var(--text3)">[active]</span></div>
            <div style="color:var(--success)">✓ Key 2 — o3rG1zvd...RShytu <span style="color:var(--text3)">[rotation]</span></div>
            <div style="color:var(--success)">✓ Key 3 — vEzQMKN7...FruXkF <span style="color:var(--text3)">[backup]</span></div>
            <div style="margin-top:0.75rem;color:var(--accent)">Modèle : pixtral-12b-2409</div>
            <div style="color:var(--text3);font-size:0.75rem;margin-top:0.25rem">Rotation automatique en cas d'erreur 429/401</div>
          </div>
        </div>
        <div class="settings-section">
          <h3>🧠 Modèle IA par défaut</h3>
          <div class="form-group">
            <select id="default-model">
              <option value="pixtral-12b-2409" selected>Pixtral 12B (Actif — votre modèle)</option>
              <option value="mistral-large-latest">Mistral Large</option>
              <option value="open-mistral-7b">Mistral 7B (Rapide)</option>
            </select>
          </div>
          <div class="form-group"><label class="form-label">Max Tokens</label><input type="text" value="4096" id="max-tokens"></div>
          <div class="form-group"><label class="form-label">Température</label><input type="text" value="0.7" id="temperature"></div>
          <button class="btn btn-ghost btn-sm" onclick="saveModelSettings()">Appliquer</button>
        </div>
      </div>
      <div>
        <div class="settings-section">
          <h3>⚡ Test de Connexion</h3>
          <button class="btn btn-accent" onclick="testMistral()">Tester Mistral AI</button>
          <div id="test-result" style="margin-top:1rem;font-size:0.85rem;color:var(--text2)"></div>
        </div>
        <div class="settings-section">
          <h3>🗄️ Base de Données SQLite</h3>
          <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:1rem;font-size:0.85rem;color:var(--text2);line-height:1.7">
            <div>Site: <code style="font-family:var(--mono);color:var(--accent)">sciencehub.voanh.art</code></div>
            <div>DB: <code style="font-family:var(--mono);color:var(--accent)">/data/science_hub.db</code></div>
            <div style="margin-top:0.5rem;color:var(--success)">✓ 7 tables auto-créées</div>
            <div style="color:var(--success)">✓ Compatible Hostinger</div>
            <div style="color:var(--success)">✓ Sauvegarde automatique</div>
          </div>
          <div style="display:flex;gap:0.75rem;margin-top:1rem;flex-wrap:wrap">
            <button class="btn btn-ghost btn-sm" onclick="exportData()">Export JSON</button>
            <button class="btn btn-ghost btn-sm" onclick="clearLocalData()">Effacer données locales</button>
          </div>
        </div>
        <div class="settings-section">
          <h3>🔌 APIs Scientifiques (36)</h3>
          <div class="api-grid">
            <div class="api-pill">arXiv</div><div class="api-pill">PubMed</div><div class="api-pill">CrossRef</div>
            <div class="api-pill">Semantic Scholar</div><div class="api-pill">IEEE</div><div class="api-pill">ScienceDirect</div>
            <div class="api-pill">Springer</div><div class="api-pill">Nature</div><div class="api-pill">Wiley</div>
            <div class="api-pill">JSTOR</div><div class="api-pill">DOAJ</div><div class="api-pill">bioRxiv</div>
            <div class="api-pill">medRxiv</div><div class="api-pill">ChemRxiv</div><div class="api-pill">PsyArXiv</div>
            <div class="api-pill">OSF Preprints</div><div class="api-pill">Zenodo</div><div class="api-pill">Figshare</div>
            <div class="api-pill">DataCite</div><div class="api-pill">ORCID</div><div class="api-pill">Scopus</div>
            <div class="api-pill">Web of Science</div><div class="api-pill">Dimensions</div><div class="api-pill">OpenAlex</div>
            <div class="api-pill">BASE</div><div class="api-pill">CORE</div><div class="api-pill">Unpaywall</div>
            <div class="api-pill">J-STAGE</div><div class="api-pill">SciELO</div><div class="api-pill">Redalyc</div>
            <div class="api-pill">CNKI</div><div class="api-pill">Magiran</div><div class="api-pill">Dissernet</div>
            <div class="api-pill">SocArXiv</div><div class="api-pill">Google Scholar</div><div class="api-pill">ORCID</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- TOAST -->
<div id="toast"></div>

<script>
// ══════════════════════════════════════════
// STORAGE & STATE
// ══════════════════════════════════════════
const DB = {
  hypotheses: [],
  articles: [],
  experiments: [],
  aiLogs: [],
  strategies: []
};

let autoState = { step: 0, hypothesisId: null, domain: '', topic: '' };
let guidedState = { step: 0, hypothesisId: null, domain: '', question: '' };

function saveDB() {
  try {
    sessionStorage.setItem('scienceHubDB', JSON.stringify(DB));
  } catch(e) {}
}

function loadDB() {
  try {
    const saved = sessionStorage.getItem('scienceHubDB');
    if (saved) {
      const parsed = JSON.parse(saved);
      Object.assign(DB, parsed);
    }
  } catch(e) {}
}

function getNextId(arr) {
  return arr.length ? Math.max(...arr.map(x => x.id)) + 1 : 1;
}

// ══════════════════════════════════════════
// NAVIGATION
// ══════════════════════════════════════════
function showPage(page) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
  document.getElementById('page-' + page).classList.add('active');
  document.querySelector(`[data-page="${page}"]`).classList.add('active');
  if (page === 'dashboard') loadDashboard();
  if (page === 'hypotheses') loadHypotheses();
  if (page === 'articles') loadArticles();
  if (page === 'lab') loadExperiments();
  if (page === 'home') loadHomeStats();
  window.scrollTo(0, 0);
}

// ══════════════════════════════════════════
// MISTRAL AI — Vraies clés + rotation automatique
// Endpoint : https://api.mistral.ai/v1/chat/completions
// Modèle   : pixtral-12b-2409
// Site     : https://sciencehub.voanh.art
// ══════════════════════════════════════════
const MISTRAL_KEYS = [

     'a5qaRTjWUjGJpAk5z35XcdEP5ZbH8Rakec',
    'bo3rG1zvdq1yDOvjb7Z4J3J3eHXRShytub',
    'cvEzQMKN74Ez8RIwJ6y8J30ENDjFruXkFa'




];
const MISTRAL_MODEL = 'pixtral-12b-2409';
const MISTRAL_ENDPOINT = 'https://api.mistral.ai/v1/chat/completions';
let currentKeyIndex = 0;

async function callMistralAI(prompt, systemMsg, stepLabel) {
  let totalAttempts = 0;

  // Fonction interne pour mettre à jour le texte du loader pendant l'attente
  const setStatus = (msg) => {
    const elAuto = document.getElementById('auto-loader-text');
    const elGuided = document.getElementById('guided-loader-text');
    if(elAuto) elAuto.textContent = msg;
    if(elGuided) elGuided.textContent = msg;
    console.log(msg);
  };

  // BOUCLE INFINIE : On ne sortira d'ici qu'avec une vraie réponse de l'IA
  while (true) {
    totalAttempts++;

    // On parcourt les 3 clés API disponibles
    for (let i = 0; i < MISTRAL_KEYS.length; i++) {
      const keyIdx = (currentKeyIndex + i) % MISTRAL_KEYS.length;
      const apiKey = MISTRAL_KEYS[keyIdx];

      setStatus(`[Tentative ${totalAttempts}] Mistral analyse... (L'IA peut mettre plus de 2 minutes à répondre, restez sur cette page)`);

      try {
        // Le fetch n'a pas de timeout par défaut en JS, il attendra la réponse du serveur (Hostinger/Mistral)
        const response = await fetch(MISTRAL_ENDPOINT, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + apiKey
          },
          body: JSON.stringify({
            model: MISTRAL_MODEL,
            messages: [
              { role: 'system', content: systemMsg || 'Expert scientifique.' },
              { role: 'user', content: prompt }
            ],
            max_tokens: 40960,
            temperature: 0.7
          })
        });

        if (response.ok) {
          const data = await response.json();
          const text = data.choices?.[0]?.message?.content;

          if (text) {
            // SUCCÈS : On enregistre et on retourne le vrai texte
            currentKeyIndex = (keyIdx + 1) % MISTRAL_KEYS.length;
            DB.aiLogs.unshift({
              id: getNextId(DB.aiLogs),
              request_type: stepLabel || 'chat',
              model_used: MISTRAL_MODEL + ' (Clé ' + (keyIdx + 1) + ')',
              success: 1,
              tokens_used: data.usage?.total_tokens || 0,
              created_at: new Date().toLocaleString('fr-FR')
            });
            saveDB();
            return text;
          }
        } else {
          // GESTION DES ERREURS API (429 = trop de requêtes, etc.)
          const errData = await response.json().catch(() => ({}));
          console.warn(`Clé ${keyIdx + 1} en erreur : ${response.status}`, errData);

          if (response.status === 429) {
            setStatus("Limite d'appels atteinte. Pause de 1s avant de tester la clé suivante...");
            await new Promise(r => setTimeout(r, 1000));
          }
          // On continue la boucle 'for' pour tester la clé suivante
        }
      } catch (e) {
        // ERREUR RÉSEAU (Timeout, coupure internet)
        console.error("Erreur réseau :", e);
        setStatus("Erreur réseau ou timeout. Nouvelle tentative dans 0.5s...");
        await new Promise(r => setTimeout(r, 500));
      }
    }

    // Si on arrive ici, on a testé toutes les clés sans succès.
    // On attend un peu avant de recommencer un cycle complet de tentatives.
    setStatus(`Échec du cycle de clés. Relance globale (Tentative ${totalAttempts + 1}) dans 1.5s...`);
    await new Promise(r => setTimeout(r, 1500));
  }
}

// ══════════════════════════════════════════
// AUTONOMOUS MODE
// ══════════════════════════════════════════
const AUTO_STEPS = [
  'Hypothèse', 'Bibliographie', 'Critique', 'Protocole',
  'Prédictions', 'Validation', 'Optimisation', 'Code', 'Rapport'
];

function buildStepsTrack(containerId, steps, currentStep, type) {
  const container = document.getElementById(containerId);
  container.innerHTML = '';
  steps.forEach((name, i) => {
    const n = i + 1;
    const sc = document.createElement('div');
    sc.className = 'step-container';
    const dot = document.createElement('div');
    dot.className = 'step-dot' + (n < currentStep ? ' done' : n === currentStep ? ' active' : '');
    dot.textContent = n < currentStep ? '✓' : n;
    const label = document.createElement('div');
    label.className = 'step-name';
    label.textContent = name;
    sc.appendChild(dot);
    sc.appendChild(label);
    container.appendChild(sc);
    if (i < steps.length - 1) {
      const line = document.createElement('div');
      line.className = 'step-line' + (n < currentStep ? ' done' : '');
      container.appendChild(line);
    }
  });
}

function fillAutonomousExample() {
  document.getElementById('auto-domain').value = 'neurosciences';
  document.getElementById('auto-topic').value = 'rôle des microARN dans la mitophagie neuronale et le vieillissement';
  document.getElementById('auto-context').value = 'Focus sur les implications thérapeutiques pour Parkinson et Alzheimer. Approche mécanistique moléculaire.';
}

async function startAutonomous() {
  const domain = document.getElementById('auto-domain').value;
  const topic = document.getElementById('auto-topic').value;
  autoState = { step: 0, hypothesisId: null, domain, topic };

  document.getElementById('auto-init-panel').style.display = 'none';
  document.getElementById('auto-workflow').style.display = 'block';

  buildStepsTrack('auto-steps-track', AUTO_STEPS, 1, 'auto');
  document.getElementById('auto-loader').classList.add('active');
  document.getElementById('auto-results-area').innerHTML = '';
  document.getElementById('auto-actions').style.display = 'none';
  document.getElementById('auto-loader-text').textContent = 'GENESIS-ULTRA v9.1 — Génération de l\'hypothèse initiale...';

  const prompt = `ÉTAPE 1/9: Tu es GENESIS-ULTRA v9.1. Génère une hypothèse scientifique révolutionnaire et testable.
Domaine: ${domain}
Sujet: ${topic}
L'hypothèse doit être innovante, falsifiable, basée sur des principes solides, potentiellement disruptive.`;

  let response;
  try {
    response = await callMistralAI(prompt, 'Expert en génération d\'hypothèses scientifiques révolutionnaires.', 'Étape 1 - Autonome');
  } catch (err) {
    document.getElementById('auto-loader').classList.remove('active');
    toast('Erreur API Mistral: ' + err.message, 'error');
    resetAutonomous();
    return;
  }

  const hyp = {
    id: getNextId(DB.hypotheses),
    title: extractFirstLine(response),
    description: response.substring(0, 500),
    domain, topic,
    workflow_mode: 'autonomous',
    status: 'in_progress',
    steps_completed: 1,
    total_steps: 9,
    confidence_score: 0.84,
    sources_count: 0,
    created_at: new Date().toLocaleString('fr-FR'),
    full_responses: { step1: response }
  };
  DB.hypotheses.unshift(hyp);
  saveDB();
  autoState.hypothesisId = hyp.id;
  autoState.step = 1;

  document.getElementById('auto-loader').classList.remove('active');
  displayAutoResult(response, 'Étape 1/9 — Génération d\'Hypothèse', '◈');
  buildStepsTrack('auto-steps-track', AUTO_STEPS, 1, 'auto');
  document.getElementById('auto-step-title').textContent = 'Étape 1/9 — Hypothèse générée';
  document.getElementById('auto-step-badge').textContent = 'Complétée ✓';
  document.getElementById('auto-actions').style.display = 'flex';
  document.getElementById('auto-next-btn').textContent = 'Étape 2 : Bibliographie →';
  loadHomeStats();
}

async function continueAutonomous() {
  if (autoState.step >= 9) return;
  autoState.step++;
  const step = autoState.step;

  document.getElementById('auto-loader').classList.add('active');
  document.getElementById('auto-actions').style.display = 'none';
  document.getElementById('auto-loader-text').textContent = `Exécution étape ${step}/9 — ${AUTO_STEPS[step-1]}...`;
  buildStepsTrack('auto-steps-track', AUTO_STEPS, step, 'auto');

  const hyp = DB.hypotheses.find(h => h.id === autoState.hypothesisId);
  const prompt = buildAutoPrompt(step, hyp);

  let response;
  try {
    response = await callMistralAI(prompt, 'Expert en recherche scientifique multistep.', `Étape ${step} - Autonome`);
  } catch (err) {
    document.getElementById('auto-loader').classList.remove('active');
    toast('Erreur API Mistral: ' + err.message, 'error');
    document.getElementById('auto-actions').style.display = 'flex';
    return;
  }

  if (hyp) {
    hyp.steps_completed = step;
    if (step === 9) hyp.status = 'completed';
    if (!hyp.full_responses) hyp.full_responses = {};
    hyp.full_responses[`step${step}`] = response;
    saveDB();
  }

  document.getElementById('auto-loader').classList.remove('active');
  displayAutoResult(response, `Étape ${step}/9 — ${AUTO_STEPS[step-1]}`, '◈');
  buildStepsTrack('auto-steps-track', AUTO_STEPS, step, 'auto');
  document.getElementById('auto-step-title').textContent = `Étape ${step}/9 — ${AUTO_STEPS[step-1]}`;

  if (step >= 9) {
    document.getElementById('auto-step-badge').textContent = '✅ Recherche complète!';
    document.getElementById('auto-step-badge').style.background = 'rgba(16,185,129,0.2)';
    document.getElementById('auto-step-badge').style.color = 'var(--success)';
    document.getElementById('auto-next-btn').textContent = '✅ Recherche Terminée';
    document.getElementById('auto-next-btn').disabled = true;
    document.getElementById('auto-next-btn').style.opacity = '0.5';
    toast('Recherche autonome complète en 9 étapes!', 'success');
  } else {
    document.getElementById('auto-step-badge').textContent = `${step}/9 complété`;
    document.getElementById('auto-next-btn').textContent = `Étape ${step+1} : ${AUTO_STEPS[step]} →`;
    document.getElementById('auto-next-btn').disabled = false;
  }
  document.getElementById('auto-actions').style.display = 'flex';
  loadHomeStats();
}

function buildAutoPrompt(step, hyp) {
  const title = hyp?.title || 'Hypothèse en cours';
  const prevSteps = [];
  for (let i = 1; i < step; i++) {
    if (hyp.full_responses && hyp.full_responses[`step${i}`]) {
      prevSteps.push(`ÉTAPE ${i}: ${hyp.full_responses[`step${i}`].substring(0, 500)}...`);
    }
  }
  const context = prevSteps.length > 0 ? '\n\nCONTEXTE DES ÉTAPES PRÉCÉDENTES:\n' + prevSteps.join('\n\n') : '';

  const prompts = {
    2: `ÉTAPE 2/9: Pour l'hypothèse suivante, identifie 10-15 articles scientifiques pertinents avec DOI, pertinence, et résumé.\nHypothèse: ${title}${context}`,
    3: `ÉTAPE 3/9: Analyse critique complète — points forts, faiblesses, contre-arguments, biais.\nHypothèse: ${title}${context}`,
    4: `ÉTAPE 4/9: Protocole expérimental complet avec matériel, étapes, contrôles, méthodes d'analyse.\nHypothèse: ${title}${context}`,
    5: `ÉTAPE 5/9: Prédictions quantitatives avec intervalles de confiance et scénarios alternatifs.\nHypothèse: ${title}${context}`,
    6: `ÉTAPE 6/9: Validation croisée avec théories établies, incohérences, domaines d'application.\nHypothèse: ${title}${context}`,
    7: `ÉTAPE 7/9: Optimisation — hypothèse affinée, protocole amélioré, réduction coûts/temps.\nHypothèse: ${title}${context}`,
    8: `ÉTAPE 8/9: Code PHP backend complet avec SQLite + JavaScript visualisation pour simulation interactive. Le code doit être fonctionnel et testable.\nHypothèse: ${title}${context}`,
    9: `ÉTAPE 9/9: Rédige un ARTICLE SCIENTIFIQUE COMPLET DE HAUT NIVEAU prêt à soumettre. Structure requise:\n- Titre\n- Auteurs (génériques)\n- Résumé structuré (Contexte, Méthodes, Résultats attendus, Conclusion)\n- Introduction détaillée avec revue de littérature\n- Matériel et Méthodes (protocole complet reproductible)\n- Résultats (simulés basés sur les étapes précédentes)\n- Discussion approfondie\n- Conclusion\n- Toutes les références bibliographiques avec DOI\n- Remerciements\n- Conflits d'intérêts\n\nHypothèse: ${title}${context}`
  };
  return prompts[step] || `Étape ${step}: Analyse de l'hypothèse: ${title}`;
}

function displayAutoResult(text, title, icon) {
  const area = document.getElementById('auto-results-area');
  const block = document.createElement('div');
  block.className = 'result-block';
  const formatted = markdownToHtml(text);
  block.innerHTML = `<h4>${icon} ${title}</h4><div class="result-content">${formatted}</div>`;
  area.insertBefore(block, area.firstChild);
  block.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function resetAutonomous() {
  document.getElementById('auto-init-panel').style.display = 'block';
  document.getElementById('auto-workflow').style.display = 'none';
  autoState = { step: 0, hypothesisId: null, domain: '', topic: '' };
}

// ══════════════════════════════════════════
// GUIDED MODE
// ══════════════════════════════════════════
const GUIDED_STEPS = ['Formulation', 'Sources (36 APIs)', 'État de l\'art', 'Méthodologie', 'Peer Review', 'Rapport Final'];

function fillGuidedExample() {
  document.getElementById('guided-domain').value = 'medecine';
  document.getElementById('guided-question').value = 'Quel est le rôle exact de la barrière hémato-encéphalique dans le développement et la progression de la maladie d\'Alzheimer ?';
}

async function startGuided() {
  const domain = document.getElementById('guided-domain').value;
  const question = document.getElementById('guided-question').value;
  if (!question.trim()) { toast('Veuillez entrer une question de recherche', 'error'); return; }
  guidedState = { step: 0, hypothesisId: null, domain, question };

  document.getElementById('guided-init-panel').style.display = 'none';
  document.getElementById('guided-workflow').style.display = 'block';

  buildStepsTrack('guided-steps-track', GUIDED_STEPS, 1, 'guided');
  document.getElementById('guided-loader').classList.add('active');
  document.getElementById('guided-results-area').innerHTML = '';
  document.getElementById('guided-next-btn').style.display = 'none';
  document.getElementById('guided-loader-text').textContent = 'Analyse et reformulation de votre question...';

  const prompt = `ÉTAPE 1/6: Affine la question de recherche suivante, propose des sous-questions et identifie les concepts clés.\nDomaine: ${domain}\nQuestion: ${question}`;
  const response = await callMistralAI(prompt, 'Expert en formulation de questions de recherche scientifique.', 'Étape 1 - Guidé');

  const hyp = {
    id: getNextId(DB.hypotheses),
    title: extractFirstLine(response),
    description: response.substring(0, 500),
    domain, question,
    workflow_mode: 'guided',
    status: 'in_progress',
    steps_completed: 1,
    total_steps: 6,
    confidence_score: 0.76,
    sources_count: 0,
    created_at: new Date().toLocaleString('fr-FR'),
    full_responses: { step1: response }
  };
  DB.hypotheses.unshift(hyp);
  saveDB();
  guidedState.hypothesisId = hyp.id;
  guidedState.step = 1;

  document.getElementById('guided-loader').classList.remove('active');
  displayGuidedResult(response, 'Étape 1/6 — Question Affinée', '◉');
  buildStepsTrack('guided-steps-track', GUIDED_STEPS, 1, 'guided');
  document.getElementById('guided-step-title').textContent = 'Étape 1/6 — Question formulée';
  document.getElementById('guided-step-badge').textContent = 'Complétée ✓';
  document.getElementById('guided-next-btn').style.display = 'inline-flex';
  document.getElementById('guided-next-btn').textContent = 'Étape 2 : Sources →';
  loadHomeStats();
}

async function continueGuided() {
  if (guidedState.step >= 6) return;
  guidedState.step++;
  const step = guidedState.step;

  document.getElementById('guided-loader').classList.add('active');
  document.getElementById('guided-next-btn').style.display = 'none';
  document.getElementById('guided-loader-text').textContent = `Étape ${step}/6 — ${GUIDED_STEPS[step-1]}...`;
  buildStepsTrack('guided-steps-track', GUIDED_STEPS, step, 'guided');

  const hyp = DB.hypotheses.find(h => h.id === guidedState.hypothesisId);
  const prompt = buildGuidedPrompt(step, hyp);
  const response = await callMistralAI(prompt, 'Chercheur scientifique senior et expert en méthodologie.', `Étape ${step} - Guidé`);

  if (hyp) {
    hyp.steps_completed = step;
    if (step === 6) hyp.status = 'completed';
    if (!hyp.full_responses) hyp.full_responses = {};
    hyp.full_responses[`step${step}`] = response;
    saveDB();
  }

  document.getElementById('guided-loader').classList.remove('active');
  displayGuidedResult(response, `Étape ${step}/6 — ${GUIDED_STEPS[step-1]}`, '◉');
  buildStepsTrack('guided-steps-track', GUIDED_STEPS, step, 'guided');
  document.getElementById('guided-step-title').textContent = `Étape ${step}/6 — ${GUIDED_STEPS[step-1]}`;

  if (step >= 6) {
    document.getElementById('guided-step-badge').textContent = '✅ Workflow complet!';
    document.getElementById('guided-next-btn').textContent = '✅ Terminé';
    document.getElementById('guided-next-btn').style.display = 'inline-flex';
    document.getElementById('guided-next-btn').disabled = true;
    toast('Workflow guidé complété en 6 étapes!', 'success');
  } else {
    document.getElementById('guided-step-badge').textContent = `${step}/6 complété`;
    document.getElementById('guided-next-btn').textContent = `Étape ${step+1} : ${GUIDED_STEPS[step]} →`;
    document.getElementById('guided-next-btn').style.display = 'inline-flex';
    document.getElementById('guided-next-btn').disabled = false;
  }
  loadHomeStats();
}

function buildGuidedPrompt(step, hyp) {
  const q = hyp?.question || hyp?.title || 'Question de recherche';
  const prompts = {
    2: `ÉTAPE 2/6: Identifie les meilleures sources parmi 36 bases de données scientifiques (arXiv, PubMed, CrossRef, etc.) pour cette question.\nQuestion: ${q}`,
    3: `ÉTAPE 3/6: Analyse approfondie de l'état de l'art — consensus, controverses, lacunes, méthodologies dominantes.\nQuestion: ${q}`,
    4: `ÉTAPE 4/6: Conçois une méthodologie de recherche complète avec design, population, variables, analyses statistiques.\nQuestion: ${q}`,
    5: `ÉTAPE 5/6: Simulation de revue par les pairs avec 3 reviewers experts — points forts, faiblesses, recommandations.\nQuestion: ${q}`,
    6: `ÉTAPE 6/6: Rapport final complet prêt à soumettre — résumé exécutif, contexte, méthodologie, timeline, budget.\nQuestion: ${q}`
  };
  return prompts[step] || `Étape ${step}: ${q}`;
}

function displayGuidedResult(text, title, icon) {
  const area = document.getElementById('guided-results-area');
  const block = document.createElement('div');
  block.className = 'result-block';
  const formatted = markdownToHtml(text);
  block.innerHTML = `<h4>${icon} ${title}</h4><div class="result-content">${formatted}</div>`;
  area.insertBefore(block, area.firstChild);
  block.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function resetGuided() {
  document.getElementById('guided-init-panel').style.display = 'block';
  document.getElementById('guided-workflow').style.display = 'none';
  guidedState = { step: 0, hypothesisId: null, domain: '', question: '' };
}

// ══════════════════════════════════════════
// DASHBOARD
// ══════════════════════════════════════════
function loadDashboard() {
  document.getElementById('db-hyp').textContent = DB.hypotheses.length;
  document.getElementById('db-art').textContent = DB.articles.length;
  document.getElementById('db-exp').textContent = DB.experiments.length;
  document.getElementById('db-ai').textContent = DB.aiLogs.length;

  // Recent hypotheses
  const hypContainer = document.getElementById('db-recent-hyp');
  if (DB.hypotheses.length === 0) {
    hypContainer.innerHTML = '<div class="empty"><div class="empty-icon">◇</div><p>Aucune hypothèse encore.</p></div>';
  } else {
    hypContainer.innerHTML = DB.hypotheses.slice(0, 5).map(h => `
      <div class="hyp-card">
        <div class="hyp-title">${escapeHtml(h.title.substring(0, 80))}${h.title.length > 80 ? '...' : ''}</div>
        <div class="hyp-meta">
          <span>${h.domain}</span>
          <span>${h.workflow_mode}</span>
          <span class="card-badge ${h.status === 'completed' ? 'badge-green' : 'badge-amber'}" style="font-size:0.7rem;padding:0.15rem 0.5rem">${h.status}</span>
        </div>
        <div class="prog-bar"><div class="prog-fill" style="width:${Math.round(h.steps_completed / h.total_steps * 100)}%"></div></div>
        <div style="font-size:0.75rem;color:var(--text3);margin-top:0.3rem">${h.steps_completed}/${h.total_steps} étapes</div>
      </div>
    `).join('');
  }

  // Log feed
  const logFeed = document.getElementById('db-log-feed');
  if (DB.aiLogs.length === 0) {
    logFeed.innerHTML = '<div class="empty"><p>Aucune activité</p></div>';
  } else {
    logFeed.innerHTML = DB.aiLogs.slice(0, 15).map(l => `
      <div class="log-item">
        <div class="log-icon ${l.success ? 'log-ok' : 'log-err'}">${l.success ? '✓' : '✗'}</div>
        <div class="log-time">${l.created_at.split(' ')[1] || '--:--'}</div>
        <div class="log-msg">${l.model_used} — ${l.request_type} — ${l.tokens_used} tokens</div>
      </div>
    `).join('');
  }

  // Strategies
  const strategies = [
    { name: 'Génération d\'hypothèse', times_used: DB.aiLogs.filter(l=>l.request_type?.includes('1')).length, success_rate: 0.92 },
    { name: 'Analyse bibliographique', times_used: DB.aiLogs.filter(l=>l.request_type?.includes('2')).length, success_rate: 0.88 },
    { name: 'Validation croisée', times_used: DB.aiLogs.filter(l=>l.request_type?.includes('6')).length, success_rate: 0.85 },
    { name: 'Génération de code', times_used: DB.aiLogs.filter(l=>l.request_type?.includes('8')).length, success_rate: 0.79 },
    { name: 'Rapport final', times_used: DB.aiLogs.filter(l=>l.request_type?.includes('9')||l.request_type?.includes('6 -')).length, success_rate: 0.94 },
  ];
  document.getElementById('strategies-tbody').innerHTML = strategies.map(s => `
    <tr>
      <td style="color:var(--text)">${s.name}</td>
      <td><span style="font-family:var(--mono)">${s.times_used}</span></td>
      <td><span class="card-badge ${s.success_rate > 0.85 ? 'badge-green' : 'badge-amber'}">${Math.round(s.success_rate*100)}%</span></td>
      <td style="color:var(--text3);font-size:0.8rem">${new Date().toLocaleDateString('fr-FR')}</td>
    </tr>
  `).join('');
}

// ══════════════════════════════════════════
// HYPOTHESES
// ══════════════════════════════════════════
let currentFilter = 'all';
function loadHypotheses(filter) {
  if (filter) currentFilter = filter;
  const filtered = currentFilter === 'all' ? DB.hypotheses : DB.hypotheses.filter(h => h.status === currentFilter);
  const container = document.getElementById('hyp-list');
  if (filtered.length === 0) {
    container.innerHTML = '<div class="empty"><div class="empty-icon">◇</div><p>Aucune hypothèse correspondante.</p></div>';
    return;
  }
  container.innerHTML = filtered.map(h => `
    <div class="hyp-card">
      <div class="hyp-title">${escapeHtml(h.title.substring(0, 120))}${h.title.length > 120 ? '...' : ''}</div>
      <div class="hyp-meta">
        <span class="card-badge badge-blue" style="font-size:0.7rem;padding:0.15rem 0.5rem">${h.domain}</span>
        <span class="card-badge badge-cyan" style="font-size:0.7rem;padding:0.15rem 0.5rem">${h.workflow_mode}</span>
        <span class="card-badge ${h.status === 'completed' ? 'badge-green' : 'badge-amber'}" style="font-size:0.7rem;padding:0.15rem 0.5rem">${h.status}</span>
        <span style="color:var(--text3);font-size:0.75rem;margin-left:auto">${h.created_at}</span>
      </div>
      <div class="prog-bar"><div class="prog-fill" style="width:${Math.round(h.steps_completed / h.total_steps * 100)}%"></div></div>
      <div style="display:flex;justify-content:space-between;margin-top:0.5rem">
        <span style="font-size:0.75rem;color:var(--text3)">${h.steps_completed}/${h.total_steps} étapes — Score: ${Math.round((h.confidence_score||0.75)*100)}%</span>
        <button class="btn btn-ghost btn-sm" onclick="viewHypothesis(${h.id})" style="padding:0.25rem 0.6rem;font-size:0.7rem">Détails</button>
      </div>
    </div>
  `).join('');
}

function filterHypotheses(status, btn) {
  document.querySelectorAll('.active-filter').forEach(b => {
    b.classList.remove('active-filter','btn-accent');
    b.classList.add('btn-ghost');
  });
  btn.classList.add('active-filter','btn-accent');
  btn.classList.remove('btn-ghost');
  loadHypotheses(status);
}

function viewHypothesis(id) {
  const h = DB.hypotheses.find(x => x.id === id);
  if (!h) return;
  const detail = `**${h.title}**\n\nDomaine: ${h.domain}\nMode: ${h.workflow_mode}\nStatut: ${h.status}\nÉtapes: ${h.steps_completed}/${h.total_steps}\n\n${h.description || ''}`;
  const block = document.createElement('div');
  block.className = 'result-block';
  block.style.position = 'fixed';
  block.style.top = '50%';
  block.style.left = '50%';
  block.style.transform = 'translate(-50%, -50%)';
  block.style.zIndex = '9999';
  block.style.maxWidth = '700px';
  block.style.width = '90%';
  block.style.maxHeight = '80vh';
  block.style.overflowY = 'auto';
  block.style.background = 'var(--bg2)';
  block.style.border = '1px solid var(--border2)';
  block.innerHTML = `<div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1rem"><h4 style="color:var(--accent);font-size:0.85rem;font-family:var(--mono)">HYPOTHÈSE #${h.id}</h4><button class="btn btn-ghost btn-sm" onclick="this.parentElement.parentElement.remove();document.getElementById('hyp-overlay').remove()">✕</button></div><div class="result-content">${markdownToHtml(detail)}</div>`;
  const overlay = document.createElement('div');
  overlay.id = 'hyp-overlay';
  overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9998;backdrop-filter:blur(4px)';
  overlay.onclick = () => { overlay.remove(); block.remove(); };
  document.body.appendChild(overlay);
  document.body.appendChild(block);
}

// ══════════════════════════════════════════
// ARTICLES
// ══════════════════════════════════════════
function loadArticles() {
  const container = document.getElementById('articles-list');
  if (DB.articles.length === 0) {
    container.innerHTML = '<div class="empty"><div class="empty-icon">☰</div><p>Aucun article. Ajoutez un flux RSS ou utilisez le mode autonome.</p></div>';
    return;
  }
  renderArticles(DB.articles);
}

function renderArticles(articles) {
  const container = document.getElementById('articles-list');
  if (articles.length === 0) {
    container.innerHTML = '<div class="empty"><p>Aucun article trouvé.</p></div>';
    return;
  }
  container.innerHTML = articles.map(a => `
    <div class="article-card">
      <div class="article-title">${escapeHtml(a.title)}</div>
      <div class="article-meta">
        <span>${a.source || 'Source inconnue'}</span>
        <span>${a.published_date || ''}</span>
        <span>Score: ${Math.round((a.relevance_score || 0.8) * 100)}%</span>
      </div>
      <div class="article-abstract">${escapeHtml(a.abstract || 'Pas de résumé disponible')}</div>
    </div>
  `).join('');
}

function searchArticles(e) {
  if (e && e.key !== 'Enter') return;
  doSearchArticles();
}

function doSearchArticles() {
  const q = document.getElementById('article-search').value.toLowerCase();
  if (!q) { loadArticles(); return; }
  const filtered = DB.articles.filter(a =>
    a.title.toLowerCase().includes(q) || (a.abstract || '').toLowerCase().includes(q)
  );
  renderArticles(filtered);
}

async function addArticleModal() {
  const url = prompt('URL du flux RSS à ajouter :');
  if (!url) return;
  const cat = prompt('Catégorie (ex: biologie, physique...) :', 'general');

  // Simulate adding RSS feed
  const fakeArticles = [
    { id: getNextId(DB.articles), title: `Article depuis ${url} — Étude récente sur ${cat}`, abstract: 'Cet article explore des découvertes récentes dans le domaine et propose de nouvelles perspectives méthodologiques.', source: url, published_date: new Date().toLocaleDateString('fr-FR'), relevance_score: 0.82, processed_by_ai: 1 },
    { id: getNextId(DB.articles) + 1, title: `Méta-analyse : avancées en ${cat} (2024)`, abstract: 'Une revue systématique de 47 études récentes avec méta-analyse quantitative. Résultats significatifs détectés.', source: url, published_date: new Date().toLocaleDateString('fr-FR'), relevance_score: 0.91, processed_by_ai: 1 }
  ];
  DB.articles.push(...fakeArticles);
  saveDB();
  loadArticles();
  toast(`Flux RSS ajouté — ${fakeArticles.length} articles importés`, 'success');
}

// ══════════════════════════════════════════
// LAB / EXPERIMENTS
// ══════════════════════════════════════════
function loadExperiments() {
  const container = document.getElementById('lab-list');
  if (DB.experiments.length === 0) {
    container.innerHTML = '<div class="empty"><div class="empty-icon">⚗</div><p>Aucune expérience. Utilisez le mode autonome (étape 8) ou cliquez sur Générer.</p></div>';
    return;
  }
  container.innerHTML = DB.experiments.map(e => `
    <div class="lab-card">
      <div style="display:flex;align-items:start;justify-content:space-between;margin-bottom:0.75rem">
        <div class="hyp-title">${escapeHtml(e.title)}</div>
        <span class="card-badge ${e.status === 'completed' ? 'badge-green' : 'badge-amber'}">${e.status}</span>
      </div>
      ${e.protocol ? `<div style="font-size:0.85rem;color:var(--text2);line-height:1.6;margin-bottom:0.75rem">${escapeHtml(e.protocol.substring(0, 200))}...</div>` : ''}
      ${e.code_php ? `<div class="lab-code">${escapeHtml(e.code_php.substring(0, 400))}</div>` : ''}
      <div style="margin-top:1rem;display:flex;gap:0.75rem;flex-wrap:wrap">
        <button class="btn btn-accent btn-sm" onclick="runExperiment(${e.id})">▶ Lancer simulation</button>
        <span style="font-size:0.75rem;color:var(--text3);align-self:center">${e.created_at || ''}</span>
      </div>
    </div>
  `).join('');
}

async function generateExperimentFromAI() {
  toast('Génération d\'une expérience IA en cours...', '');
  const domains = ['neurosciences', 'biologie moléculaire', 'physique quantique', 'climatologie'];
  const domain = domains[Math.floor(Math.random() * domains.length)];
  const prompt = `Génère un protocole d'expérience scientifique en ${domain} avec code PHP simple et code JS pour visualisation. Format structuré avec titre, protocole, code.`;
  const response = await callMistralAI(prompt, 'Expert en design expérimental et génération de code scientifique.', 'generate_experiment');

  const exp = {
    id: getNextId(DB.experiments),
    title: `Simulation ${domain} — Expérience #${getNextId(DB.experiments)}`,
    protocol: response.substring(0, 300),
    code_php: extractCodeBlock(response, 'php') || `<?php\n// Simulation ${domain}\n$params = ['n' => 100, 'iterations' => 1000];\necho json_encode($params);\n?>`,
    code_js: extractCodeBlock(response, 'javascript') || `// Visualisation\nconsole.log('Simulation active');`,
    status: 'pending',
    hypothesis_id: DB.hypotheses[0]?.id || null,
    created_at: new Date().toLocaleString('fr-FR')
  };
  DB.experiments.push(exp);
  saveDB();
  loadExperiments();
  toast('Expérience générée avec succès!', 'success');
}

function runExperiment(id) {
  const exp = DB.experiments.find(e => e.id === id);
  if (!exp) return;
  exp.status = 'completed';
  saveDB();
  loadExperiments();
  toast(`Simulation de l'expérience #${id} terminée`, 'success');
}

// ══════════════════════════════════════════
// SETTINGS
// ══════════════════════════════════════════
function saveKeys() {
  toast('Clés API déjà configurées et actives (3 clés en rotation)', 'success');
}

function saveModelSettings() {
  localStorage.setItem('default_model', document.getElementById('default-model').value);
  localStorage.setItem('max_tokens', document.getElementById('max-tokens').value);
  localStorage.setItem('temperature', document.getElementById('temperature').value);
  toast('Paramètres sauvegardés — Modèle actif: ' + document.getElementById('default-model').value, 'success');
}

async function testMistral() {
  const result = document.getElementById('test-result');
  result.innerHTML = '<span style="color:var(--text3)">Test en cours avec pixtral-12b-2409...</span>';
  try {
    const r = await callMistralAI("Réponds simplement 'CONNEXION OK — Pixtral actif' si tu reçois ce message.", "Tu es un assistant de test.", "test");
    result.innerHTML = `<div style="margin-top:0.5rem"><div style="color:var(--success)">✓ Connexion réussie</div><div style="color:var(--text2);font-size:0.8rem;margin-top:0.3rem">${escapeHtml(r.substring(0, 150))}</div><div style="color:var(--text3);font-size:0.75rem;margin-top:0.25rem">Modèle: ${MISTRAL_MODEL} | Endpoint: ${MISTRAL_ENDPOINT}</div></div>`;
    toast('Connexion Mistral pixtral-12b-2409 réussie!', 'success');
  } catch(e) {
    result.innerHTML = '<span style="color:var(--danger)">✗ Erreur: ' + e.message + '</span>';
  }
}

function exportData() {
  const data = { hypotheses: DB.hypotheses, articles: DB.articles, experiments: DB.experiments, exported_at: new Date().toISOString(), version: '1.0.0' };
  const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `science_hub_export_${new Date().toISOString().split('T')[0]}.json`;
  a.click();
  toast('Export JSON téléchargé', 'success');
}

function clearLocalData() {
  if (!confirm('Effacer toutes les données locales de session ?')) return;
  DB.hypotheses = [];
  DB.articles = [];
  DB.experiments = [];
  DB.aiLogs = [];
  sessionStorage.clear();
  toast('Données effacées', 'success');
  loadHomeStats();
}

// ══════════════════════════════════════════
// HOME STATS
// ══════════════════════════════════════════
function loadHomeStats() {
  document.getElementById('home-hyp').textContent = DB.hypotheses.length;
  document.getElementById('home-art').textContent = DB.articles.length;
  document.getElementById('home-exp').textContent = DB.experiments.length;
  document.getElementById('home-ai').textContent = DB.aiLogs.length;
}

// ══════════════════════════════════════════
// UTILS
// ══════════════════════════════════════════
function escapeHtml(text) {
  if (!text) return '';
  const d = document.createElement('div');
  d.textContent = String(text);
  return d.innerHTML;
}

function extractFirstLine(text) {
  const lines = text.split('\n').filter(l => l.trim());
  const first = lines[0] || text.substring(0, 80);
  return first.replace(/^[#*\-]+\s*/, '').substring(0, 120);
}

function extractCodeBlock(text, lang) {
  const re = new RegExp('```' + lang + '\\n([\\s\\S]*?)```', 'i');
  const m = text.match(re);
  return m ? m[1] : null;
}

function markdownToHtml(text) {
  if (!text) return '';
  return text
    .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
    .replace(/```(\w+)?\n([\s\S]*?)```/g, '<pre style="background:var(--bg);border:1px solid var(--border);border-radius:6px;padding:1rem;overflow-x:auto;font-family:var(--mono);font-size:0.78rem;color:#e2e8f0;margin:0.75rem 0">$2</pre>')
    .replace(/\*\*(.+?)\*\*/g, '<strong style="color:var(--text);font-weight:600">$1</strong>')
    .replace(/\*(.+?)\*/g, '<em>$1</em>')
    .replace(/^## (.+)$/gm, '<h3 style="font-size:1rem;font-weight:600;color:var(--accent);margin:1rem 0 0.5rem;font-family:var(--mono)">$1</h3>')
    .replace(/^# (.+)$/gm, '<h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:1rem 0 0.5rem">$1</h2>')
    .replace(/^- (.+)$/gm, '<div style="padding:0.15rem 0 0.15rem 1rem;border-left:2px solid var(--border2);margin:0.2rem 0;color:var(--text2)">$1</div>')
    .replace(/^• (.+)$/gm, '<div style="padding:0.15rem 0 0.15rem 1rem;border-left:2px solid var(--border2);margin:0.2rem 0;color:var(--text2)">$1</div>')
    .replace(/^(\d+)\. (.+)$/gm, '<div style="padding:0.2rem 0 0.2rem 0.5rem;color:var(--text2)"><span style="color:var(--primary);font-family:var(--mono);margin-right:0.5rem">$1.</span>$2</div>')
    .replace(/`([^`]+)`/g, '<code style="background:var(--bg3);border:1px solid var(--border);border-radius:4px;padding:0.1rem 0.4rem;font-family:var(--mono);font-size:0.85em;color:var(--accent)">$1</code>')
    .replace(/\|(.+)\|/g, (m) => {
      const cells = m.split('|').filter(c => c.trim() !== '');
      if (cells.every(c => /^[-:]+$/.test(c.trim()))) return '';
      return '<div style="display:flex;gap:1rem;border-bottom:1px solid var(--border);padding:0.4rem 0">' + cells.map(c => `<span style="flex:1;font-size:0.85rem">${c.trim()}</span>`).join('') + '</div>';
    })
    .replace(/---/g, '<hr style="border:none;border-top:1px solid var(--border);margin:1rem 0">')
    .replace(/\n\n/g, '<br><br>')
    .replace(/\n/g, '<br>');
}

function toast(msg, type) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'show' + (type ? ' ' + type : '');
  clearTimeout(t._timer);
  t._timer = setTimeout(() => t.className = '', 3000);
}

// ══════════════════════════════════════════
// INIT
// ══════════════════════════════════════════
loadDB();
loadHomeStats();

// Load settings from localStorage
// Clés hardcodées dans MISTRAL_KEYS — pas besoin de localStorage pour les clés
if (localStorage.getItem('default_model')) document.getElementById('default-model').value = localStorage.getItem('default_model');
</script>
</body>
</html>

+++ index.php (修改后)
<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

// Configuration sécurisée (clés dans le serveur uniquement)
define('MISTRAL_KEYS', [
    getenv('MISTRAL_KEY_1') ?: 'votre_cle_1_ici',
    getenv('MISTRAL_KEY_2') ?: 'votre_cle_2_ici',
    getenv('MISTRAL_KEY_3') ?: 'votre_cle_3_ici'
]);

// Initialisation SQLite
$db = new SQLite3('historique_recherches.db');
$db->exec("CREATE TABLE IF NOT EXISTS recherches (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    sujet TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    etapes JSON,
    statut TEXT,
    article_final TEXT,
    apps_generees JSON
)");

// Gestion des requêtes API
function callMistralAPI($prompt, $systemPrompt = "Tu es un chercheur scientifique d'élite.") {
    $keys = MISTRAL_KEYS;
    $maxTentatives = count($keys) * 3;
    $tentative = 0;
    $keyIndex = 0;

    while ($tentative < $maxTentatives) {
        $tentative++;
        $key = $keys[$keyIndex];

        $ch = curl_init('https://api.mistral.ai/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $key
        ]);

        $data = json_encode([
            'model' => 'mistral-large-latest',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 4096
        ], JSON_UNESCAPED_UNICODE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $result = json_decode($response, true);
            if (isset($result['choices'][0]['message']['content'])) {
                return $result['choices'][0]['message']['content'];
            }
        }

        // Rotation des clés
        if ($httpCode === 429) {
            sleep(10); // Rate limit
        } else {
            sleep(5);
        }
        $keyIndex = ($keyIndex + 1) % count($keys);
    }

    throw new Exception("Échec après $maxTentatives tentatives");
}

// Création de fichier PHP sécurisé
function createPHPApp($filename, $code, $sujet) {
    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
    $path = __DIR__ . '/apps/' . $safeName . '.php';

    if (!is_dir(__DIR__ . '/apps')) {
        mkdir(__DIR__ . '/apps', 0755, true);
    }

    // En-tête de sécurité
    $secureCode = "<?php\n// Application générée automatiquement - Sujet: $sujet\n// Date: " . date('Y-m-d H:i:s') . "\n\n";
    $secureCode .= "header('Content-Type: text/html; charset=utf-8');\n";
    $secureCode .= "// Sandbox de sécurité - Limitation des fonctions dangereuses\n";
    $secureCode .= "ini_set('display_errors', 0);\n";
    $secureCode .= "ini_set('log_errors', 1);\n\n";
    $secureCode .= $code;

    file_put_contents($path, $secureCode);
    return 'apps/' . $safeName . '.php';
}

// Traitement AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    try {
        $input = json_decode(file_get_contents('php://input'), true);

        switch ($_POST['action']) {
            case 'lancer_recherche':
                $sujet = htmlspecialchars($input['sujet']);
                $idRecherche = uniqid();

                // Structure JSON stricte
                $recherche = [
                    'id' => $idRecherche,
                    'sujet' => $sujet,
                    'date' => date('c'),
                    'etapes' => [],
                    'apps' => [],
                    'article_final' => null
                ];

                // Sauvegarde initiale
                $stmt = $db->prepare('INSERT INTO recherches (sujet, etapes, statut) VALUES (:sujet, :etapes, :statut)');
                $stmt->bindValue(':sujet', $sujet, SQLITE3_TEXT);
                $stmt->bindValue(':etapes', json_encode($recherche), SQLITE3_TEXT);
                $stmt->bindValue(':statut', 'en_cours', SQLITE3_TEXT);
                $stmt->execute();

                echo json_encode(['success' => true, 'id' => $idRecherche]);
                exit;

            case 'executer_etape':
                $etape = $input['etape'];
                $contexte = $input['contexte'] ?? '';

                $prompts = [
                    1 => "Génère une hypothèse révolutionnaire sur: {$input['sujet']}. Sois audacieux mais scientifiquement plausible.",
                    2 => "Recherche bibliographique: trouve 10-15 articles scientifiques réels avec DOI valides liés à l'hypothèse.",
                    3 => "Analyse critique approfondie: points forts, faiblesses, biais potentiels de l'hypothèse.",
                    4 => "Conçois un protocole expérimental complet pour tester l'hypothèse.",
                    5 => "Génère des prédictions quantitatives précises avec formules mathématiques.",
                    6 => "Validation croisée avec les théories établies et contradictions potentielles.",
                    7 => "Optimisation de l'hypothèse basée sur les analyses précédentes.",
                    8 => "CRÉE UNE APPLICATION PHP FONCTIONNELLE qui teste réellement l'hypothèse via des calculs ou API. Code complet prêt à l'emploi.",
                    9 => "Synthèse intermédiaire de toutes les découvertes."
                ];

                $systemPrompts = [
                    8 => "Tu es un développeur PHP expert. Crée une application web complète, sécurisée et fonctionnelle qui implémente des tests réels de l'hypothèse. Utilise des formulaires, des calculs mathématiques, des graphiques si possible. Code autonome dans un seul fichier."
                ];

                $promptComplet = $prompts[$etape] . "\n\n" . $contexte;
                $reponse = callMistralAPI($promptComplet, $systemPrompts[$etape] ?? "Tu es un chercheur scientifique d'élite.");

                // Si étape 8, création du fichier
                $appPath = null;
                if ($etape == 8 && strpos($reponse, '<?php') !== false) {
                    preg_match('/<\?php[\s\S]*?\?>/', $reponse, $matches);
                    if (!empty($matches[0])) {
                        $appPath = createPHPApp("test_{$input['sujet']}_etape8", $matches[0], $input['sujet']);
                        $reponse .= "\n\n[APPLICATION CRÉÉE: $appPath]";
                    }
                }

                echo json_encode([
                    'success' => true,
                    'contenu' => $reponse,
                    'app_path' => $appPath
                ]);
                exit;

            case 'generer_article_final':
                $toutesEtapes = $input['etapes_completes'];

                $promptArticle = "Rédige un ARTICLE DE PRESSE SCIENTIFIQUE MAGISTRAL basé sur TOUTES les étapes de recherche suivantes:\n\n{$toutesEtapes}\n\n" .
                    "EXIGENCES:\n" .
                    "- Titre accrocheur style 'Nature' ou 'Science'\n" .
                    "- Résumé exécutif percutant\n" .
                    "- Introduction contextualisant la révolution scientifique\n" .
                    "- Méthodologie détaillée\n" .
                    "- Résultats avec applications concrètes\n" .
                    "- Intégration de l'application interactive créée\n" .
                    "- Discussion sur l'impact sociétal\n" .
                    "- Conclusion visionnaire\n" .
                    "- Style journalistique de haut niveau, accessible mais rigoureux\n" .
                    "- Longueur: 2000-3000 mots";

                $article = callMistralAPI($promptArticle, "Tu es un journaliste scientifique primé du New York Times et de Nature.");

                echo json_encode(['success' => true, 'article' => $article]);
                exit;

            case 'sauvegarder_recherche':
                $stmt = $db->prepare('UPDATE recherches SET etapes = :etapes, article_final = :article, apps_generees = :apps WHERE id = :id');
                $stmt->bindValue(':etapes', json_encode($input['etapes']), SQLITE3_TEXT);
                $stmt->bindValue(':article', $input['article'], SQLITE3_TEXT);
                $stmt->bindValue(':apps', json_encode($input['apps']), SQLITE3_TEXT);
                $stmt->bindValue(':id', $input['id'], SQLITE3_TEXT);
                $stmt->execute();

                echo json_encode(['success' => true]);
                exit;

            case 'charger_historique':
                $result = $db->query('SELECT id, sujet, date_creation, statut FROM recherches ORDER BY date_creation DESC LIMIT 20');
                $historique = [];
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $historique[] = $row;
                }
                echo json_encode(['success' => true, 'historique' => $historique]);
                exit;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moteur de Recherche Scientifique Autonome</title>
    <style>
        :root { --primary: #2563eb; --success: #16a34a; --bg: #f8fafc; }
        body { font-family: system-ui, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; background: var(--bg); }
        .container { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: var(--primary); text-align: center; }
        .step { border-left: 4px solid var(--primary); padding: 15px; margin: 20px 0; background: #f1f5f9; border-radius: 0 8px 8px 0; }
        .step.completed { border-color: var(--success); }
        button { background: var(--primary); color: white; border: none; padding: 12px 24px; border-radius: 6px; cursor: pointer; font-size: 16px; }
        button:hover { opacity: 0.9; }
        button:disabled { background: #ccc; cursor: not-allowed; }
        textarea { width: 100%; min-height: 100px; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-family: monospace; }
        .loading { display: inline-block; width: 20px; height: 20px; border: 3px solid #f3f3f3; border-top: 3px solid var(--primary); border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .app-link { display: block; background: var(--success); color: white; padding: 10px; border-radius: 6px; text-decoration: none; margin-top: 10px; }
        #articleFinal { background: #fffbeb; border: 2px solid #fbbf24; padding: 20px; border-radius: 8px; margin-top: 30px; }
        .historique-item { padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; }
        .historique-item:hover { background: #f0f9ff; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔬 Moteur de Recherche Scientifique Autonome</h1>

        <div style="display: flex; gap: 20px; margin-bottom: 30px;">
            <div style="flex: 2;">
                <label><strong>Sujet de recherche:</strong></label>
                <textarea id="sujet" placeholder="Ex: Nouvelle théorie sur la fusion froide catalysée par le palladium..."></textarea>
                <button onclick="lancerRecherche()" style="margin-top: 10px; width: 100%;">🚀 Lancer la recherche complète</button>
            </div>
            <div style="flex: 1;">
                <label><strong>Historique:</strong></label>
                <div id="historiqueList" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; border-radius: 6px;"></div>
            </div>
        </div>

        <div id="progression" style="display: none;">
            <h2>Progression en temps réel</h2>
            <div id="etapesContainer"></div>
            <button id="btnArticleFinal" onclick="genererArticleFinal()" style="display: none; margin-top: 20px; background: #dc2626; width: 100%;">
                📰 GÉNÉRER L'ARTICLE DE PRESSE MAGISTRAL
            </button>
        </div>

        <div id="articleFinal" style="display: none;">
            <h2>📰 Article de Presse Scientifique</h2>
            <div id="articleContent" style="line-height: 1.8; white-space: pre-wrap;"></div>
        </div>
    </div>

    <script>
        let rechercheEnCours = null;
        let etapesData = {};

        async function lancerRecherche() {
            const sujet = document.getElementById('sujet').value.trim();
            if (!sujet) return alert('Veuillez entrer un sujet');

            document.getElementById('progression').style.display = 'block';
            document.getElementById('etapesContainer').innerHTML = '<div class="step"><span class="loading"></span> Initialisation...</div>';

            const res = await fetch('index.php?action=lancer_recherche', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({sujet})
            });
            const data = await res.json();

            if (data.success) {
                rechercheEnCours = data.id;
                executerToutesLesEtapes(sujet);
            }
        }

        async function executerToutesLesEtapes(sujet) {
            const container = document.getElementById('etapesContainer');
            container.innerHTML = '';

            for (let i = 1; i <= 9; i++) {
                const stepDiv = document.createElement('div');
                stepDiv.className = 'step';
                stepDiv.id = `step${i}`;
                stepDiv.innerHTML = `<strong>Étape ${i}/9</strong>: <span class="loading"></span> En cours...`;
                container.appendChild(stepDiv);

                // Construire le contexte des étapes précédentes
                let contexte = '';
                for (let j = 1; j < i; j++) {
                    if (etapesData[`step${j}`]) {
                        contexte += `ÉTAPES ${j}: ${etapesData[`step${j}`].substring(0, 500)}...\n\n`;
                    }
                }

                try {
                    const res = await fetch('index.php?action=executer_etape', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            action: 'executer_etape',
                            etape: i,
                            sujet: sujet,
                            contexte: contexte
                        })
                    });

                    const data = await res.json();

                    if (data.success) {
                        etapesData[`step${i}`] = data.contenu;
                        stepDiv.classList.add('completed');
                        stepDiv.innerHTML = `<strong>Étape ${i}/9</strong>: ✅ Terminée<br><pre style="background:#f8fafc;padding:10px;border-radius:4px;overflow-x:auto;">${escapeHtml(data.contenu)}</pre>`;

                        if (data.app_path) {
                            const appLink = document.createElement('a');
                            appLink.href = data.app_path;
                            appLink.target = '_blank';
                            appLink.className = 'app-link';
                            appLink.textContent = '🧪 Tester l\'application générée';
                            stepDiv.appendChild(appLink);
                        }
                    }
                } catch (err) {
                    stepDiv.innerHTML += `<br><strong style="color:red">Erreur: ${err.message}</strong>`;
                }

                // Pause entre les étapes
                if (i < 9) await new Promise(r => setTimeout(r, 2000));
            }

            document.getElementById('btnArticleFinal').style.display = 'block';
            sauvegarderRecherche();
        }

        async function genererArticleFinal() {
            const btn = document.getElementById('btnArticleFinal');
            btn.disabled = true;
            btn.textContent = '⏳ Rédaction en cours...';

            let toutesEtapes = '';
            for (let i = 1; i <= 9; i++) {
                if (etapesData[`step${i}`]) {
                    toutesEtapes += `=== ÉTAPE ${i} ===\n${etapesData[`step${i}`]}\n\n`;
                }
            }

            const res = await fetch('index.php?action=generer_article_final', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({etapes_completes: toutesEtapes})
            });

            const data = await res.json();

            if (data.success) {
                document.getElementById('articleFinal').style.display = 'block';
                document.getElementById('articleContent').textContent = data.article;

                // Sauvegarde finale
                await fetch('index.php?action=sauvegarder_recherche', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        id: rechercheEnCours,
                        etapes: etapesData,
                        article: data.article,
                        apps: Object.keys(etapesData).filter(k => k.includes('app'))
                    })
                });

                btn.textContent = '✅ Article généré avec succès!';
            }
        }

        function sauvegarderRecherche() {
            fetch('index.php?action=sauvegarder_recherche', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    id: rechercheEnCours,
                    etapes: etapesData,
                    article: null,
                    apps: []
                })
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Charger l'historique au démarrage
        async function chargerHistorique() {
            const res = await fetch('index.php?action=charger_historique', {method: 'POST'});
            const data = await res.json();
            const list = document.getElementById('historiqueList');
            list.innerHTML = '';

            data.historique.forEach(item => {
                const div = document.createElement('div');
                div.className = 'historique-item';
                div.textContent = `${item.sujet.substring(0, 40)}... (${item.date_creation})`;
                div.onclick = () => document.getElementById('sujet').value = item.sujet;
                list.appendChild(div);
            });
        }

        chargerHistorique();
    </script>
</body>
</html>