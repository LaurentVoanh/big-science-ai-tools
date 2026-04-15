<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Découvertes — Discovery Engine</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Unbounded:wght@300;700;900&display=swap');
:root { --ink:#06060a; --paper:#f0ede6; --acid:#c8ff00; --cyan:#00e5ff; --mag:#ff2d6b; --dim:#3a3a4a; --grid:rgba(200,255,0,0.06); }
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
body { font-family:'Space Mono',monospace; background:var(--ink); color:var(--paper); min-height:100vh; overflow-x:hidden; }
body::before { content:''; position:fixed; inset:0; background-image:linear-gradient(var(--grid) 1px,transparent 1px),linear-gradient(90deg,var(--grid) 1px,transparent 1px); background-size:40px 40px; pointer-events:none; z-index:0; }
nav { position:fixed;top:0;left:0;right:0;z-index:1000; display:flex;align-items:center;justify-content:space-between; padding:0 2rem;height:64px; background:rgba(6,6,10,0.92); border-bottom:1px solid var(--acid); backdrop-filter:blur(8px); }
.nav-logo { font-family:'Unbounded',sans-serif;font-weight:900;font-size:1.1rem;color:var(--acid);letter-spacing:0.05em;text-decoration:none; }
.nav-links { display:flex;gap:2rem;align-items:center; }
.nav-links a { color:var(--paper);text-decoration:none;font-size:0.75rem;letter-spacing:0.1em;text-transform:uppercase;opacity:0.7;transition:opacity 0.2s,color 0.2s; }
.nav-links a:hover,.nav-links a.active { opacity:1;color:var(--acid); }
.app { padding-top:64px; min-height:100vh; position:relative; z-index:1; }
.container { max-width:1400px; margin:0 auto; padding:3rem 2rem; }
.page-hdr { margin-bottom:3rem; }
.page-hdr h1 { font-family:'Unbounded',sans-serif;font-size:2.5rem;font-weight:900; }
.page-hdr h1 span { color:var(--acid); }
.page-hdr p { margin-top:0.75rem;font-size:0.82rem;opacity:0.55; }

.filters { display:flex;gap:0.75rem;margin-bottom:2rem;flex-wrap:wrap; }
.filter-btn { padding:0.5rem 1.25rem;border:1px solid rgba(255,255,255,0.12);background:transparent;color:var(--paper);cursor:pointer;font-family:'Space Mono',monospace;font-size:0.72rem;letter-spacing:0.08em;text-transform:uppercase;transition:all 0.2s;border-radius:2px; }
.filter-btn:hover,.filter-btn.active { background:rgba(200,255,0,0.1);border-color:var(--acid);color:var(--acid); }

.search-bar { display:flex;gap:1rem;margin-bottom:2.5rem; }
.search-input { flex:1;padding:0.85rem 1.25rem;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);color:var(--paper);font-family:'Space Mono',monospace;font-size:0.82rem;border-radius:4px;outline:none;transition:border-color 0.2s; }
.search-input:focus { border-color:rgba(200,255,0,0.4); }
.search-input::placeholder { opacity:0.4; }

.discoveries-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(380px,1fr));gap:1.5rem; }
.disc-card { background:rgba(10,10,20,0.9);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:1.75rem;cursor:pointer;transition:border-color 0.25s,transform 0.2s;position:relative;overflow:hidden; }
.disc-card::before { content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--mag),var(--acid));transform:scaleX(0);transform-origin:left;transition:transform 0.3s; }
.disc-card:hover { border-color:rgba(200,255,0,0.3);transform:translateY(-3px); }
.disc-card:hover::before { transform:scaleX(1); }

.dc-header { display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1rem; }
.dc-type { font-size:0.6rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--cyan);background:rgba(0,229,255,0.1);border:1px solid rgba(0,229,255,0.2);padding:0.25rem 0.6rem;border-radius:2px; }
.dc-novelty { font-family:'Unbounded',sans-serif;font-size:0.9rem;font-weight:900;color:var(--acid); }

.dc-title { font-family:'Unbounded',sans-serif;font-size:0.9rem;font-weight:700;line-height:1.4;margin-bottom:0.75rem; }
.dc-claim { font-size:0.76rem;line-height:1.7;opacity:0.6;margin-bottom:1.25rem;display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden; }

.dc-meta { display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;margin-bottom:0.75rem; }
.dc-domain { font-size:0.6rem;padding:0.2rem 0.6rem;border:1px solid rgba(255,45,107,0.3);color:rgba(255,45,107,0.8);border-radius:2px; }
.dc-domain.b { border-color:rgba(0,229,255,0.3);color:rgba(0,229,255,0.8); }

.dc-scores { display:flex;gap:1.25rem; }
.dc-score { display:flex;align-items:center;gap:0.4rem;font-size:0.65rem; }
.dc-score-bar { width:50px;height:3px;background:rgba(255,255,255,0.1);border-radius:2px;overflow:hidden; }
.dc-score-fill { height:100%;border-radius:2px; }
.dc-score.nov .dc-score-fill { background:var(--acid); }
.dc-score.imp .dc-score-fill { background:var(--mag); }
.dc-score.crit .dc-score-fill { background:var(--cyan); }

.dc-date { font-size:0.6rem;opacity:0.3;margin-top:0.75rem; }

.pagination { display:flex;justify-content:center;gap:0.5rem;margin-top:3rem; }
.page-btn { padding:0.6rem 1rem;border:1px solid rgba(255,255,255,0.1);background:transparent;color:var(--paper);cursor:pointer;font-family:'Space Mono',monospace;font-size:0.72rem;border-radius:2px;transition:all 0.2s; }
.page-btn:hover,.page-btn.active { border-color:var(--acid);color:var(--acid);background:rgba(200,255,0,0.06); }
.page-btn:disabled { opacity:0.2;cursor:not-allowed; }

.empty-state { grid-column:1/-1;text-align:center;padding:4rem;opacity:0.4; }
.empty-icon { font-size:3rem;margin-bottom:1rem; }
.empty-msg { font-size:0.85rem; }

.loader { display:flex;justify-content:center;padding:4rem; }
.spin { width:40px;height:40px;border:2px solid rgba(200,255,0,0.2);border-top-color:var(--acid);border-radius:50%;animation:spin 0.8s linear infinite; }
@keyframes spin { to{transform:rotate(360deg)} }
</style>
</head>
<body>
<nav>
  <a href="index.php" class="nav-logo">⬡ DISCOVERY ENGINE</a>
  <div class="nav-links">
    <a href="index.php">PIPELINE</a>
    <a href="discoveries.php" class="active">DÉCOUVERTES</a>
    <a href="dashboard.php">STATS</a>
  </div>
</nav>
<div class="app">
<div class="container">
  <div class="page-hdr">
    <h1>BIBLIOTHÈQUE DES <span>DÉCOUVERTES</span></h1>
    <p>Toutes les découvertes générées par collision inter-domaines — avec critique, protocole et article complet.</p>
  </div>

  <div class="search-bar">
    <input type="text" class="search-input" id="searchInput" placeholder="Rechercher dans les découvertes..." oninput="filterDiscs()">
  </div>

  <div class="filters" id="typeFilters">
    <button class="filter-btn active" onclick="setFilter('all',this)">TOUTES</button>
    <button class="filter-btn" onclick="setFilter('mechanism',this)">MÉCANISME</button>
    <button class="filter-btn" onclick="setFilter('pathway',this)">PATHWAY</button>
    <button class="filter-btn" onclick="setFilter('target',this)">CIBLE</button>
    <button class="filter-btn" onclick="setFilter('biomarker',this)">BIOMARQUEUR</button>
    <button class="filter-btn" onclick="setFilter('drug_repurposing',this)">REPURPOSING</button>
  </div>

  <div class="discoveries-grid" id="discGrid">
    <div class="loader"><div class="spin"></div></div>
  </div>

  <div class="pagination" id="pagination"></div>
</div>
</div>

<script>
const esc = s => { if(!s) return ''; const d=document.createElement('div');d.textContent=String(s);return d.innerHTML; };

let allDiscs = [];
let filtered = [];
let currentFilter = 'all';
let currentPage = 1;
const PER_PAGE = 12;

async function loadAll() {
  try {
    const res = await fetch('api.php?action=discoveries&limit=200');
    const j   = await res.json();
    if (j.ok) { allDiscs = j.discoveries || []; applyFilter(); }
    else document.getElementById('discGrid').innerHTML = '<div class="empty-state"><div class="empty-icon">⬡</div><div class="empty-msg">Aucune découverte disponible</div></div>';
  } catch(e) {}
}

function setFilter(type, btn) {
  currentFilter = type;
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  currentPage = 1;
  applyFilter();
}

function applyFilter() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  filtered = allDiscs.filter(d => {
    const matchType = currentFilter === 'all' || d.discovery_type === currentFilter;
    const matchSearch = !q || [d.title, d.core_claim, d.vulgarized].some(s => s?.toLowerCase().includes(q));
    return matchType && matchSearch;
  });
  renderPage();
}

function filterDiscs() { applyFilter(); }

function renderPage() {
  const start = (currentPage - 1) * PER_PAGE;
  const page  = filtered.slice(start, start + PER_PAGE);
  const grid  = document.getElementById('discGrid');

  if (!page.length) {
    grid.innerHTML = '<div class="empty-state"><div class="empty-icon">⬡</div><div class="empty-msg">Aucun résultat</div></div>';
    document.getElementById('pagination').innerHTML = '';
    return;
  }

  grid.innerHTML = page.map(d => {
    const domains = (() => { try { return JSON.parse(d.domains_crossed||'[]'); } catch(e){return [];} })();
    const nov  = parseFloat(d.novelty_score||0);
    const imp  = parseFloat(d.impact_score||0);
    const crit = parseFloat(d.critique_score||0);
    return `<div class="disc-card" onclick="location.href='view.php?id=${d.id}'">
      <div class="dc-header">
        <span class="dc-type">${esc(d.discovery_type||'mechanism')}</span>
        <span class="dc-novelty">${(nov*100).toFixed(0)}%</span>
      </div>
      <div class="dc-title">${esc(d.title||'Sans titre')}</div>
      <div class="dc-claim">${esc(d.core_claim||d.vulgarized||'')}</div>
      <div class="dc-meta">
        ${domains.map((dom,i) => `<span class="dc-domain ${i>0?'b':''}">${esc(dom)}</span>`).join('')}
      </div>
      <div class="dc-scores">
        <div class="dc-score nov"><span>N</span><div class="dc-score-bar"><div class="dc-score-fill" style="width:${nov*100}%"></div></div><span>${(nov*100).toFixed(0)}%</span></div>
        <div class="dc-score imp"><span>I</span><div class="dc-score-bar"><div class="dc-score-fill" style="width:${imp*100}%"></div></div><span>${(imp*100).toFixed(0)}%</span></div>
        <div class="dc-score crit"><span>C</span><div class="dc-score-bar"><div class="dc-score-fill" style="width:${crit*100}%"></div></div><span>${(crit*100).toFixed(0)}%</span></div>
      </div>
      <div class="dc-date">${d.created_at?.slice(0,16)||''}</div>
    </div>`;
  }).join('');

  // Pagination
  const pages = Math.ceil(filtered.length / PER_PAGE);
  const pag   = document.getElementById('pagination');
  if (pages <= 1) { pag.innerHTML = ''; return; }
  let ph = '';
  for (let i = 1; i <= pages; i++) {
    ph += `<button class="page-btn${i===currentPage?' active':''}" onclick="goPage(${i})">${i}</button>`;
  }
  pag.innerHTML = ph;
}

function goPage(p) { currentPage = p; renderPage(); window.scrollTo(0,0); }

loadAll();
</script>
</body>
</html>
