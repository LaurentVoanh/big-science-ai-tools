<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stats — Discovery Engine</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Unbounded:wght@300;700;900&display=swap');
:root { --ink:#06060a; --paper:#f0ede6; --acid:#c8ff00; --cyan:#00e5ff; --mag:#ff2d6b; --grid:rgba(200,255,0,0.06); }
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
body { font-family:'Space Mono',monospace; background:var(--ink); color:var(--paper); min-height:100vh; overflow-x:hidden; }
body::before { content:''; position:fixed; inset:0; background-image:linear-gradient(var(--grid) 1px,transparent 1px),linear-gradient(90deg,var(--grid) 1px,transparent 1px); background-size:40px 40px; pointer-events:none; z-index:0; }
nav { position:fixed;top:0;left:0;right:0;z-index:1000; display:flex;align-items:center;justify-content:space-between; padding:0 2rem;height:64px; background:rgba(6,6,10,0.92); border-bottom:1px solid var(--acid); backdrop-filter:blur(8px); }
.nav-logo { font-family:'Unbounded',sans-serif;font-weight:900;font-size:1.1rem;color:var(--acid);letter-spacing:0.05em;text-decoration:none; }
.nav-links { display:flex;gap:2rem; }
.nav-links a { color:var(--paper);text-decoration:none;font-size:0.75rem;letter-spacing:0.1em;text-transform:uppercase;opacity:0.7;transition:opacity 0.2s,color 0.2s; }
.nav-links a:hover,.nav-links a.active { opacity:1;color:var(--acid); }
.app { padding-top:64px; min-height:100vh; position:relative; z-index:1; }
.container { max-width:1400px; margin:0 auto; padding:3rem 2rem; }
.page-hdr { margin-bottom:3rem; }
.page-hdr h1 { font-family:'Unbounded',sans-serif;font-size:2.5rem;font-weight:900; }
.page-hdr h1 span { color:var(--acid); }

.stats-grid { display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;margin-bottom:3rem; }
.stat-card { background:rgba(10,10,20,0.9);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:1.75rem;text-align:center; }
.stat-val { font-family:'Unbounded',sans-serif;font-size:2.8rem;font-weight:900;color:var(--acid); }
.stat-lbl { font-size:0.65rem;opacity:0.5;letter-spacing:0.12em;text-transform:uppercase;margin-top:0.5rem; }
.stat-card.mag .stat-val { color:var(--mag); }
.stat-card.cyan .stat-val { color:var(--cyan); }
.stat-card.white .stat-val { color:var(--paper); }

.section { margin-bottom:3rem; }
.section-title { font-family:'Unbounded',sans-serif;font-size:0.75rem;font-weight:700;letter-spacing:0.15em;color:var(--acid);text-transform:uppercase;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(200,255,0,0.1); }

.top-disc { display:flex;flex-direction:column;gap:0.75rem; }
.top-disc-item { background:rgba(10,10,20,0.9);border:1px solid rgba(255,255,255,0.07);border-radius:6px;padding:1.25rem;display:flex;align-items:center;gap:1.5rem;cursor:pointer;transition:border-color 0.2s; }
.top-disc-item:hover { border-color:rgba(200,255,0,0.25); }
.top-rank { font-family:'Unbounded',sans-serif;font-size:2rem;font-weight:900;color:rgba(200,255,0,0.2);min-width:50px;text-align:center; }
.top-info { flex:1; }
.top-title { font-family:'Unbounded',sans-serif;font-size:0.82rem;font-weight:700;margin-bottom:0.3rem; }
.top-scores { display:flex;gap:1.5rem;font-size:0.65rem; }
.ts-n { color:var(--acid); } .ts-i { color:var(--mag); }

.domain-map { display:flex;flex-direction:column;gap:0.5rem; }
.domain-row { display:flex;align-items:center;gap:1rem;padding:0.75rem;background:rgba(10,10,20,0.9);border:1px solid rgba(255,255,255,0.07);border-radius:4px; }
.dom-pair { display:flex;align-items:center;gap:0.5rem;min-width:350px; }
.dom-tag { font-size:0.65rem;padding:0.2rem 0.6rem;border-radius:2px; }
.dom-a-tag { border:1px solid rgba(255,45,107,0.4);color:rgba(255,45,107,0.9); }
.dom-b-tag { border:1px solid rgba(0,229,255,0.4);color:rgba(0,229,255,0.9); }
.dom-arrow { color:var(--acid);opacity:0.5; }
.dom-bridges { margin-left:auto;font-size:0.65rem;opacity:0.5; }
.dom-fert { width:80px;height:3px;background:rgba(255,255,255,0.1);border-radius:2px;overflow:hidden; }
.dom-fert-fill { height:100%;background:linear-gradient(90deg,var(--mag),var(--acid));border-radius:2px; }

@media(max-width:900px){.stats-grid{grid-template-columns:1fr 1fr}}
@media(max-width:600px){.stats-grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<nav>
  <a href="index.php" class="nav-logo">⬡ DISCOVERY ENGINE</a>
  <div class="nav-links">
    <a href="index.php">PIPELINE</a>
    <a href="discoveries.php">DÉCOUVERTES</a>
    <a href="dashboard.php" class="active">STATS</a>
  </div>
</nav>
<div class="app">
<div class="container">
  <div class="page-hdr">
    <h1>ANALYTICS <span>& CARTE</span></h1>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-val" id="sDisc">-</div>
      <div class="stat-lbl">Découvertes totales</div>
    </div>
    <div class="stat-card mag">
      <div class="stat-val" id="sSig">-</div>
      <div class="stat-lbl">Signaux collectés</div>
    </div>
    <div class="stat-card cyan">
      <div class="stat-val" id="sNov">-</div>
      <div class="stat-lbl">Novelty moyenne</div>
    </div>
    <div class="stat-card white">
      <div class="stat-val" id="sImp">-</div>
      <div class="stat-lbl">Impact moyen</div>
    </div>
  </div>

  <div class="section">
    <div class="section-title">// Top découvertes</div>
    <div class="top-disc" id="topDisc">
      <p style="opacity:0.4">Chargement...</p>
    </div>
  </div>

  <div class="section">
    <div class="section-title">// Carte des domaines traversés</div>
    <div class="domain-map" id="domainMap">
      <p style="opacity:0.4">Chargement...</p>
    </div>
  </div>
</div>
</div>
<script>
const esc = s => { if(!s)return''; const d=document.createElement('div');d.textContent=String(s);return d.innerHTML; };
const fmtPct = v => (parseFloat(v||0)*100).toFixed(0)+'%';

async function loadStats() {
  try {
    const res = await fetch('api.php?action=stats');
    const j   = await res.json();
    if (!j.ok) return;

    document.getElementById('sDisc').textContent = j.discoveries || 0;
    document.getElementById('sSig').textContent  = j.signals || 0;
    document.getElementById('sNov').textContent  = fmtPct(j.avg_novelty);
    document.getElementById('sImp').textContent  = fmtPct(j.avg_impact);

    // Top découvertes
    const topDiv = document.getElementById('topDisc');
    if (j.top_discoveries?.length) {
      topDiv.innerHTML = j.top_discoveries.map((d,i) => `
        <div class="top-disc-item" onclick="location.href='view.php?id=${d.id}'">
          <div class="top-rank">#${i+1}</div>
          <div class="top-info">
            <div class="top-title">${esc(d.title||'Sans titre')}</div>
            <div class="top-scores">
              <span class="ts-n">Novelty ${fmtPct(d.novelty_score)}</span>
              <span class="ts-i">Impact ${fmtPct(d.impact_score)}</span>
            </div>
          </div>
        </div>`
      ).join('');
    } else {
      topDiv.innerHTML = '<p style="opacity:0.4">Aucune découverte pour le moment</p>';
    }

    // Domain map
    const mapDiv = document.getElementById('domainMap');
    if (j.domain_map?.length) {
      mapDiv.innerHTML = j.domain_map.map(dm => `
        <div class="domain-row">
          <div class="dom-pair">
            <span class="dom-tag dom-a-tag">${esc(dm.domain_a)}</span>
            <span class="dom-arrow">↔</span>
            <span class="dom-tag dom-b-tag">${esc(dm.domain_b)}</span>
          </div>
          <div class="dom-fert"><div class="dom-fert-fill" style="width:${parseFloat(dm.fertility||0)*100}%"></div></div>
          <span class="dom-bridges">${dm.bridge_count} pont${dm.bridge_count>1?'s':''}</span>
        </div>`
      ).join('');
    } else {
      mapDiv.innerHTML = '<p style="opacity:0.4">La carte se construit au fil des découvertes</p>';
    }

  } catch(e) {}
}

loadStats();
setInterval(loadStats, 30000);
</script>
</body>
</html>
