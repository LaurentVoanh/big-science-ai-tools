<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DISCOVERY ENGINE — Anomalies & Collisions</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Unbounded:wght@300;700;900&display=swap');

:root {
  --ink:   #06060a;
  --paper: #f0ede6;
  --acid:  #c8ff00;
  --cyan:  #00e5ff;
  --mag:   #ff2d6b;
  --dim:   #3a3a4a;
  --grid:  rgba(200,255,0,0.06);
}

*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'Space Mono', monospace;
  background: var(--ink);
  color: var(--paper);
  min-height: 100vh;
  overflow-x: hidden;
}

/* ── GRID BACKGROUND ── */
body::before {
  content: '';
  position: fixed; inset: 0;
  background-image:
    linear-gradient(var(--grid) 1px, transparent 1px),
    linear-gradient(90deg, var(--grid) 1px, transparent 1px);
  background-size: 40px 40px;
  pointer-events: none;
  z-index: 0;
}

/* ── NAV ── */
nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 2rem;
  height: 64px;
  background: rgba(6,6,10,0.92);
  border-bottom: 1px solid var(--acid);
  backdrop-filter: blur(8px);
}

.nav-logo {
  font-family: 'Unbounded', sans-serif;
  font-weight: 900;
  font-size: 1.1rem;
  color: var(--acid);
  letter-spacing: 0.05em;
  text-decoration: none;
}

.nav-links { display: flex; gap: 2rem; align-items: center; }
.nav-links a {
  color: var(--paper);
  text-decoration: none;
  font-size: 0.75rem;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  opacity: 0.7;
  transition: opacity 0.2s, color 0.2s;
}
.nav-links a:hover, .nav-links a.active { opacity: 1; color: var(--acid); }

.nav-status {
  font-size: 0.7rem;
  color: var(--cyan);
  letter-spacing: 0.08em;
  display: flex; align-items: center; gap: 0.5rem;
}
.pulse {
  width: 8px; height: 8px;
  border-radius: 50%;
  background: var(--acid);
  animation: pulse 2s infinite;
}
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(0.8)} }

/* ── LAYOUT ── */
.app { padding-top: 64px; min-height: 100vh; position: relative; z-index: 1; }

/* ── HERO ── */
.hero {
  padding: 4rem 2rem 2rem;
  max-width: 1400px; margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr 360px;
  gap: 3rem;
  align-items: start;
}

.hero-title {
  font-family: 'Unbounded', sans-serif;
  font-weight: 900;
  font-size: clamp(2.5rem, 5vw, 4.5rem);
  line-height: 1.0;
  letter-spacing: -0.02em;
}
.hero-title span.acid  { color: var(--acid); }
.hero-title span.cyan  { color: var(--cyan); }
.hero-title span.mag   { color: var(--mag); }

.hero-sub {
  margin-top: 1.5rem;
  font-size: 0.85rem;
  line-height: 1.7;
  opacity: 0.6;
  max-width: 540px;
}

.pipeline-visual {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-top: 2.5rem;
}
.pipe-step {
  display: flex; align-items: center; gap: 1rem;
  padding: 0.75rem 1rem;
  border: 1px solid rgba(200,255,0,0.15);
  border-radius: 4px;
  font-size: 0.75rem;
  letter-spacing: 0.05em;
  background: rgba(6,6,10,0.8);
  transition: all 0.3s;
  cursor: default;
}
.pipe-step.active {
  border-color: var(--acid);
  background: rgba(200,255,0,0.06);
  color: var(--acid);
}
.pipe-step.done {
  border-color: rgba(0,229,255,0.3);
  opacity: 0.6;
}
.pipe-step .step-emoji { font-size: 1.1rem; width: 24px; text-align: center; }
.pipe-step .step-name { font-family: 'Unbounded', sans-serif; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; }
.pipe-step .step-desc { opacity: 0.5; margin-left: auto; }
.pipe-connector { width: 1px; height: 12px; background: rgba(200,255,0,0.2); margin-left: 22px; }

/* ── CONTROL PANEL ── */
.control-panel {
  background: rgba(6,6,10,0.9);
  border: 1px solid rgba(200,255,0,0.2);
  border-radius: 8px;
  padding: 1.5rem;
  position: sticky;
  top: 80px;
}
.cp-title {
  font-family: 'Unbounded', sans-serif;
  font-size: 0.7rem;
  font-weight: 700;
  letter-spacing: 0.15em;
  color: var(--acid);
  margin-bottom: 1.5rem;
  text-transform: uppercase;
}

.btn-launch {
  width: 100%;
  padding: 1.1rem;
  background: var(--acid);
  color: var(--ink);
  border: none;
  border-radius: 4px;
  font-family: 'Unbounded', sans-serif;
  font-size: 0.8rem;
  font-weight: 900;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  cursor: pointer;
  transition: transform 0.15s, opacity 0.15s;
}
.btn-launch:hover { transform: translateY(-2px); }
.btn-launch:active { transform: translateY(0); opacity: 0.8; }
.btn-launch:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

.btn-auto {
  width: 100%;
  margin-top: 0.75rem;
  padding: 0.9rem;
  background: transparent;
  color: var(--cyan);
  border: 1px solid var(--cyan);
  border-radius: 4px;
  font-family: 'Unbounded', sans-serif;
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  cursor: pointer;
  transition: all 0.2s;
}
.btn-auto:hover { background: rgba(0,229,255,0.1); }
.btn-auto.running { background: rgba(255,45,107,0.1); border-color: var(--mag); color: var(--mag); }
.btn-auto:disabled { opacity: 0.4; cursor: not-allowed; }

.btn-reset {
  width: 100%;
  margin-top: 0.5rem;
  padding: 0.6rem;
  background: transparent;
  color: rgba(255,255,255,0.3);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 4px;
  font-size: 0.7rem;
  letter-spacing: 0.08em;
  cursor: pointer;
  transition: all 0.2s;
}
.btn-reset:hover { color: var(--mag); border-color: var(--mag); }

.cp-stats {
  margin-top: 1.5rem;
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 0.75rem;
}
.cp-stat {
  background: rgba(255,255,255,0.03);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 4px;
  padding: 0.75rem;
  text-align: center;
}
.cp-stat-val {
  font-family: 'Unbounded', sans-serif;
  font-size: 1.4rem;
  font-weight: 900;
  color: var(--acid);
}
.cp-stat-lbl { font-size: 0.6rem; opacity: 0.5; letter-spacing: 0.08em; margin-top: 0.2rem; }

.progress-ring {
  margin-top: 1.5rem;
  display: flex; justify-content: center; align-items: center; gap: 1rem;
}
.step-dot {
  width: 10px; height: 10px;
  border-radius: 50%;
  border: 1px solid rgba(255,255,255,0.2);
  background: transparent;
  transition: all 0.3s;
}
.step-dot.done  { background: var(--cyan);  border-color: var(--cyan); }
.step-dot.active { background: var(--acid); border-color: var(--acid); box-shadow: 0 0 8px var(--acid); animation: pulse 1s infinite; }

/* ── TERMINAL LOG ── */
.section {
  max-width: 1400px; margin: 2rem auto; padding: 0 2rem;
}
.section-hdr {
  display: flex; align-items: center; gap: 1rem;
  margin-bottom: 1rem;
  border-bottom: 1px solid rgba(200,255,0,0.1);
  padding-bottom: 0.75rem;
}
.section-hdr h2 {
  font-family: 'Unbounded', sans-serif;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.15em;
  color: var(--acid);
  text-transform: uppercase;
}
.section-hdr .count {
  margin-left: auto;
  font-size: 0.7rem;
  opacity: 0.4;
}

.terminal {
  background: #0a0a10;
  border: 1px solid rgba(200,255,0,0.15);
  border-radius: 6px;
  padding: 1rem;
  height: 220px;
  overflow-y: auto;
  font-size: 0.72rem;
  line-height: 1.6;
  scroll-behavior: smooth;
}
.terminal::-webkit-scrollbar { width: 4px; }
.terminal::-webkit-scrollbar-track { background: transparent; }
.terminal::-webkit-scrollbar-thumb { background: rgba(200,255,0,0.2); border-radius: 2px; }

.log-line { display: flex; gap: 0.75rem; }
.log-time { opacity: 0.3; min-width: 80px; }
.log-phase { color: var(--acid); min-width: 90px; }
.log-msg.success { color: var(--acid); }
.log-msg.warning { color: #ffaa00; }
.log-msg.error   { color: var(--mag); }
.log-msg.info    { color: var(--paper); }

/* ── ANOMALY CARD ── */
.anomaly-live {
  background: rgba(200,255,0,0.04);
  border: 1px solid rgba(200,255,0,0.25);
  border-radius: 8px;
  padding: 1.5rem;
  display: none;
  animation: slideIn 0.4s ease;
}
.anomaly-live.visible { display: block; }
@keyframes slideIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

.anomaly-tag {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border: 1px solid var(--acid);
  color: var(--acid);
  font-size: 0.65rem;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  border-radius: 2px;
  margin-bottom: 1rem;
}
.anomaly-domains {
  display: flex; align-items: center; gap: 1rem;
  margin-bottom: 1rem;
}
.domain-pill {
  padding: 0.4rem 1rem;
  border-radius: 100px;
  font-size: 0.75rem;
  font-weight: 700;
  font-family: 'Unbounded', sans-serif;
}
.dom-a { background: rgba(255,45,107,0.15); border: 1px solid var(--mag); color: var(--mag); }
.dom-b { background: rgba(0,229,255,0.12); border: 1px solid var(--cyan); color: var(--cyan); }
.dom-arrow { color: var(--acid); font-size: 1.2rem; }

.anomaly-title {
  font-family: 'Unbounded', sans-serif;
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--paper);
  margin-bottom: 0.75rem;
}
.anomaly-desc { font-size: 0.8rem; line-height: 1.7; opacity: 0.75; }

/* ── DISCOVERIES GRID ── */
.discoveries-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
  gap: 1.25rem;
}
.disc-card {
  background: rgba(10,10,20,0.9);
  border: 1px solid rgba(255,255,255,0.07);
  border-radius: 8px;
  padding: 1.5rem;
  cursor: pointer;
  transition: border-color 0.25s, transform 0.2s;
  position: relative;
  overflow: hidden;
}
.disc-card::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0;
  height: 2px;
  background: linear-gradient(90deg, var(--mag), var(--acid));
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.3s;
}
.disc-card:hover { border-color: rgba(200,255,0,0.25); transform: translateY(-2px); }
.disc-card:hover::before { transform: scaleX(1); }

.dc-type {
  font-size: 0.6rem;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: var(--cyan);
  margin-bottom: 0.75rem;
  opacity: 0.8;
}
.dc-title {
  font-family: 'Unbounded', sans-serif;
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--paper);
  line-height: 1.4;
  margin-bottom: 0.75rem;
}
.dc-claim {
  font-size: 0.75rem;
  line-height: 1.6;
  opacity: 0.6;
  margin-bottom: 1rem;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.dc-footer {
  display: flex; align-items: center; justify-content: space-between;
  gap: 0.5rem;
}
.score-bar {
  display: flex; align-items: center; gap: 0.5rem;
  font-size: 0.65rem;
}
.score-track {
  width: 60px; height: 3px;
  background: rgba(255,255,255,0.1);
  border-radius: 2px; overflow: hidden;
}
.score-fill { height: 100%; border-radius: 2px; }
.score-nov .score-fill { background: var(--acid); }
.score-imp .score-fill { background: var(--mag); }
.dc-domains {
  display: flex; gap: 0.4rem; flex-wrap: wrap;
}
.dc-domain {
  font-size: 0.6rem;
  padding: 0.2rem 0.5rem;
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 2px;
  opacity: 0.5;
}
.dc-date { font-size: 0.6rem; opacity: 0.3; margin-top: 0.5rem; }

/* ── MODAL ── */
.modal-overlay {
  display: none;
  position: fixed; inset: 0; z-index: 2000;
  background: rgba(6,6,10,0.92);
  backdrop-filter: blur(4px);
  overflow-y: auto;
  padding: 2rem;
}
.modal-overlay.open { display: flex; align-items: flex-start; justify-content: center; }
.modal {
  background: #0d0d18;
  border: 1px solid rgba(200,255,0,0.2);
  border-radius: 12px;
  padding: 2.5rem;
  max-width: 860px;
  width: 100%;
  position: relative;
  animation: slideIn 0.3s ease;
}
.modal-close {
  position: absolute; top: 1.5rem; right: 1.5rem;
  background: transparent; border: none;
  color: var(--paper); font-size: 1.5rem; cursor: pointer;
  opacity: 0.5; transition: opacity 0.2s;
}
.modal-close:hover { opacity: 1; }

.modal-type { font-size: 0.6rem; letter-spacing: 0.15em; color: var(--cyan); text-transform: uppercase; margin-bottom: 0.75rem; }
.modal-title { font-family: 'Unbounded', sans-serif; font-size: 1.4rem; font-weight: 900; line-height: 1.3; margin-bottom: 1.5rem; }

.modal-tabs { display: flex; gap: 0; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 2rem; }
.tab-btn {
  padding: 0.75rem 1.25rem;
  background: transparent; border: none;
  font-family: 'Space Mono', monospace;
  font-size: 0.7rem; letter-spacing: 0.08em; text-transform: uppercase;
  color: var(--paper); cursor: pointer; opacity: 0.4;
  border-bottom: 2px solid transparent; margin-bottom: -1px;
  transition: all 0.2s;
}
.tab-btn.active { opacity: 1; color: var(--acid); border-bottom-color: var(--acid); }

.tab-content { display: none; }
.tab-content.active { display: block; }

.article-section { margin-bottom: 2rem; }
.article-section h3 {
  font-family: 'Unbounded', sans-serif;
  font-size: 0.65rem; font-weight: 700;
  letter-spacing: 0.15em; color: var(--acid);
  text-transform: uppercase; margin-bottom: 0.75rem;
  padding-bottom: 0.5rem; border-bottom: 1px solid rgba(200,255,0,0.1);
}
.article-section p { font-size: 0.82rem; line-height: 1.8; opacity: 0.8; }

.scores-display { display: flex; gap: 2rem; margin-bottom: 1.5rem; }
.score-big { text-align: center; }
.score-big .val {
  font-family: 'Unbounded', sans-serif;
  font-size: 2.5rem; font-weight: 900;
}
.score-big.nov .val { color: var(--acid); }
.score-big.imp .val { color: var(--mag); }
.score-big.crit .val { color: var(--cyan); }
.score-big .lbl { font-size: 0.65rem; opacity: 0.5; letter-spacing: 0.1em; }

.protocol-step {
  display: flex; gap: 1rem; align-items: flex-start;
  padding: 0.75rem; border-left: 2px solid rgba(200,255,0,0.2);
  margin-bottom: 0.75rem;
}
.proto-week {
  min-width: 80px;
  font-size: 0.65rem; color: var(--acid); font-weight: 700;
  text-transform: uppercase; letter-spacing: 0.08em;
}
.proto-action { font-size: 0.78rem; line-height: 1.6; }
.proto-expected { font-size: 0.7rem; opacity: 0.5; margin-top: 0.25rem; }

.critique-verdict {
  display: inline-block;
  padding: 0.4rem 1rem;
  border-radius: 100px;
  font-family: 'Unbounded', sans-serif;
  font-size: 0.65rem; font-weight: 700;
  letter-spacing: 0.1em; text-transform: uppercase;
  margin-bottom: 1.5rem;
}
.verdict-reject { background: rgba(255,45,107,0.2); border: 1px solid var(--mag); color: var(--mag); }
.verdict-major  { background: rgba(255,170,0,0.2); border: 1px solid #ffaa00; color: #ffaa00; }
.verdict-minor  { background: rgba(0,229,255,0.15); border: 1px solid var(--cyan); color: var(--cyan); }
.verdict-accept { background: rgba(200,255,0,0.15); border: 1px solid var(--acid); color: var(--acid); }

.flaw-item { margin-bottom: 0.75rem; padding: 0.75rem; border: 1px solid rgba(255,255,255,0.06); border-radius: 4px; }
.flaw-sev { font-size: 0.6rem; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.3rem; }
.flaw-sev.critical { color: var(--mag); }
.flaw-sev.major    { color: #ffaa00; }
.flaw-sev.minor    { color: var(--cyan); }
.flaw-text  { font-size: 0.78rem; }
.flaw-fix   { font-size: 0.72rem; opacity: 0.6; margin-top: 0.25rem; }

.empty-state {
  text-align: center; padding: 4rem 2rem;
  opacity: 0.4;
}
.empty-icon { font-size: 3rem; margin-bottom: 1rem; }
.empty-msg  { font-size: 0.85rem; }

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
  .hero { grid-template-columns: 1fr; }
  .control-panel { position: static; }
}
</style>
</head>
<body>

<nav>
  <a href="index.php" class="nav-logo">⬡ DISCOVERY ENGINE</a>
  <div class="nav-links">
    <a href="index.php" class="active">PIPELINE</a>
    <a href="discoveries.php">DÉCOUVERTES</a>
    <a href="dashboard.php">STATS</a>
  </div>
  <div class="nav-status">
    <div class="pulse"></div>
    <span id="navStatus">PRÊT</span>
  </div>
</nav>

<div class="app">

  <!-- HERO -->
  <div class="hero">
    <div>
      <h1 class="hero-title">
        <span class="acid">COLLISION</span><br>
        INTER<span class="cyan">DOMAINES</span><br>
        <span class="mag">→ DÉCOUVERTE</span>
      </h1>
      <p class="hero-sub">
        Le moteur détecte les anomalies dans la littérature scientifique, collecte les signaux faibles depuis 4+ sources, et force une collision entre deux domaines distincts pour générer une hypothèse réellement inédite — avec critique intégrée et protocole expérimental.
      </p>

      <div class="pipeline-visual">
        <?php
        $phases = [
          ['emoji'=>'🔍','name'=>'SCOUT','desc'=>'Anomalie / zone inconnue'],
          ['emoji'=>'📡','name'=>'HARVEST','desc'=>'Collecte multi-sources'],
          ['emoji'=>'⚡','name'=>'COLLIDE','desc'=>'Connexion inter-domaines'],
          ['emoji'=>'🧬','name'=>'HYPOTHESIZE','desc'=>'Formulation découverte'],
          ['emoji'=>'🔎','name'=>'CRITIQUE','desc'=>'Auto-critique rigoureuse'],
          ['emoji'=>'🔬','name'=>'PROTOCOL','desc'=>'Protocole expérimental'],
          ['emoji'=>'📝','name'=>'PUBLISH','desc'=>'Article complet'],
        ];
        foreach ($phases as $i => $p): ?>
        <?php if ($i > 0): ?><div class="pipe-connector"></div><?php endif; ?>
        <div class="pipe-step" id="pipeStep<?= $i ?>">
          <span class="step-emoji"><?= $p['emoji'] ?></span>
          <span class="step-name"><?= $p['name'] ?></span>
          <span class="step-desc"><?= $p['desc'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- CONTROL PANEL -->
    <div class="control-panel">
      <div class="cp-title">// Control Panel</div>

      <button class="btn-launch" id="btnStep" onclick="runStep()">
        ▶ LANCER ÉTAPE
      </button>
      <button class="btn-auto" id="btnAuto" onclick="toggleAuto()">
        ⚡ AUTO-DÉCOUVERTE
      </button>
      <button class="btn-reset" onclick="resetSession()">↺ Réinitialiser session</button>

      <div class="progress-ring" id="progressRing">
        <?php for ($i = 0; $i < 7; $i++): ?>
        <div class="step-dot" id="dot<?= $i ?>"></div>
        <?php endfor; ?>
      </div>

      <div class="cp-stats">
        <div class="cp-stat">
          <div class="cp-stat-val" id="statDisc">-</div>
          <div class="cp-stat-lbl">DÉCOUVERTES</div>
        </div>
        <div class="cp-stat">
          <div class="cp-stat-val" id="statSig">-</div>
          <div class="cp-stat-lbl">SIGNAUX</div>
        </div>
        <div class="cp-stat">
          <div class="cp-stat-val" id="statNov">-</div>
          <div class="cp-stat-lbl">MOY. NOVELTY</div>
        </div>
        <div class="cp-stat">
          <div class="cp-stat-val" id="statImp">-</div>
          <div class="cp-stat-lbl">MOY. IMPACT</div>
        </div>
      </div>
    </div>
  </div>

  <!-- ANOMALIE EN COURS -->
  <div class="section">
    <div id="anomalyLive" class="anomaly-live">
      <div class="anomaly-tag" id="anomalyType">-</div>
      <div class="anomaly-domains">
        <span class="domain-pill dom-a" id="anomalyDomA">-</span>
        <span class="dom-arrow">↔</span>
        <span class="domain-pill dom-b" id="anomalyDomB">-</span>
      </div>
      <div class="anomaly-title" id="anomalyTitle">-</div>
      <div class="anomaly-desc" id="anomalyDesc">-</div>
    </div>
  </div>

  <!-- TERMINAL LOG -->
  <div class="section">
    <div class="section-hdr">
      <h2>// Journal de bord</h2>
      <span class="count" id="logCount">0 entrées</span>
    </div>
    <div class="terminal" id="terminal">
      <div class="log-line">
        <span class="log-time">--:--:--</span>
        <span class="log-phase">SYSTEM</span>
        <span class="log-msg info">Discovery Engine prêt. Cliquez sur LANCER ÉTAPE pour démarrer.</span>
      </div>
    </div>
  </div>

  <!-- DÉCOUVERTES RÉCENTES -->
  <div class="section" id="discSection">
    <div class="section-hdr">
      <h2>// Découvertes récentes</h2>
      <span class="count" id="discCount">-</span>
    </div>
    <div class="discoveries-grid" id="discGrid">
      <div class="empty-state">
        <div class="empty-icon">⬡</div>
        <div class="empty-msg">Aucune découverte. Lancez le pipeline.</div>
      </div>
    </div>
  </div>

</div>

<!-- MODAL DÉCOUVERTE -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
  <div class="modal">
    <button class="modal-close" onclick="closeModal()">×</button>
    <div class="modal-type" id="mType"></div>
    <div class="modal-title" id="mTitle"></div>

    <div class="scores-display">
      <div class="score-big nov"><div class="val" id="mNov">-</div><div class="lbl">NOVELTY</div></div>
      <div class="score-big imp"><div class="val" id="mImp">-</div><div class="lbl">IMPACT</div></div>
      <div class="score-big crit"><div class="val" id="mCrit">-</div><div class="lbl">CRITIQUE</div></div>
    </div>

    <div class="modal-tabs">
      <button class="tab-btn active" onclick="switchTab('discovery')">DÉCOUVERTE</button>
      <button class="tab-btn" onclick="switchTab('article')">ARTICLE</button>
      <button class="tab-btn" onclick="switchTab('protocol')">PROTOCOLE</button>
      <button class="tab-btn" onclick="switchTab('critique')">CRITIQUE</button>
    </div>

    <div class="tab-content active" id="tab-discovery">
      <div class="article-section">
        <h3>Affirmation centrale</h3>
        <p id="mClaim"></p>
      </div>
      <div class="article-section">
        <h3>Mécanisme</h3>
        <p id="mMechanism"></p>
      </div>
      <div class="article-section">
        <h3>Vulgarisé</h3>
        <p id="mVulg"></p>
      </div>
      <div class="article-section">
        <h3>Testabilité</h3>
        <p id="mTest"></p>
      </div>
    </div>

    <div class="tab-content" id="tab-article">
      <div id="articleContent"><p style="opacity:0.5">Chargement article...</p></div>
    </div>

    <div class="tab-content" id="tab-protocol">
      <div id="protocolContent"><p style="opacity:0.5">Chargement protocole...</p></div>
    </div>

    <div class="tab-content" id="tab-critique">
      <div id="critiqueContent"><p style="opacity:0.5">Chargement critique...</p></div>
    </div>
  </div>
</div>

<script>
// ── STATE ──────────────────────────────────────────────────────────────────────
let isRunning  = false;
let autoMode   = false;
let autoTimer  = null;
let currentStep = 0;

// ── UTILS ─────────────────────────────────────────────────────────────────────
const esc = (s) => {
  if (!s) return '';
  const d = document.createElement('div');
  d.textContent = String(s);
  return d.innerHTML;
};

function fmtScore(v) {
  const n = parseFloat(v) || 0;
  return (n * 100).toFixed(0) + '%';
}

function appendLog(phase, level, msg, time) {
  const t = document.getElementById('terminal');
  const now = time || new Date().toTimeString().slice(0,8);
  const div = document.createElement('div');
  div.className = 'log-line';
  div.innerHTML = `<span class="log-time">${now}</span><span class="log-phase">${esc(phase.toUpperCase())}</span><span class="log-msg ${level}">${esc(msg)}</span>`;
  t.appendChild(div);
  t.scrollTop = t.scrollHeight;
  document.getElementById('logCount').textContent = t.children.length + ' entrées';
}

// ── PIPELINE UI ───────────────────────────────────────────────────────────────
function updatePipelineUI(step) {
  currentStep = step;
  for (let i = 0; i < 7; i++) {
    const el = document.getElementById('pipeStep' + i);
    const dot = document.getElementById('dot' + i);
    el.className = 'pipe-step' + (i === step ? ' active' : i < step ? ' done' : '');
    dot.className = 'step-dot' + (i === step ? ' active' : i < step ? ' done' : '');
  }
}

function showAnomaly(anomaly) {
  if (!anomaly || !anomaly.anomaly_title) return;
  const el = document.getElementById('anomalyLive');
  el.classList.add('visible');
  document.getElementById('anomalyType').textContent = anomaly.anomaly_type || 'signal';
  document.getElementById('anomalyDomA').textContent = anomaly.domain_a || '?';
  document.getElementById('anomalyDomB').textContent = anomaly.domain_b || '?';
  document.getElementById('anomalyTitle').textContent = anomaly.anomaly_title || '';
  document.getElementById('anomalyDesc').textContent = anomaly.anomaly_description || '';
}

// ── RUN STEP ──────────────────────────────────────────────────────────────────
async function runStep() {
  if (isRunning) return;
  isRunning = true;
  document.getElementById('btnStep').disabled = true;
  document.getElementById('navStatus').textContent = 'EN COURS...';

  try {
    const res = await fetch('api.php?action=step', {method:'POST'});
    const j   = await res.json();

    if (j.ok) {
      updatePipelineUI(j.step_next);
      appendLog(j.phase?.name || 'step', 'success', j.phase?.emoji + ' ' + j.phase?.name + ' — ' + j.phase?.desc);

      if (j.anomaly) showAnomaly(j.anomaly);
      if (j.signals_count) appendLog('harvest', 'info', j.signals_count + ' signaux collectés');
      if (j.completed) {
        appendLog('publish', 'success', '🎉 Découverte #' + j.discovery_id + ' complète!');
        await loadDiscoveries();
      }

      // Poll logs
      await pollLogs();
      await loadStats();
    } else {
      appendLog('error', 'error', j.error || 'Erreur inconnue');
    }
  } catch (e) {
    appendLog('system', 'error', 'Erreur réseau: ' + e.message);
  }

  isRunning = false;
  document.getElementById('btnStep').disabled = false;
  document.getElementById('navStatus').textContent = 'PRÊT';
}

// ── AUTO MODE ─────────────────────────────────────────────────────────────────
function toggleAuto() {
  autoMode = !autoMode;
  const btn = document.getElementById('btnAuto');
  if (autoMode) {
    btn.textContent = '⏹ ARRÊTER AUTO';
    btn.classList.add('running');
    appendLog('system', 'info', '⚡ Mode auto activé — pipeline continu');
    runAutoLoop();
  } else {
    btn.textContent = '⚡ AUTO-DÉCOUVERTE';
    btn.classList.remove('running');
    appendLog('system', 'info', '⏹ Mode auto désactivé');
    clearTimeout(autoTimer);
  }
}

async function runAutoLoop() {
  if (!autoMode) return;
  await runStep();
  if (autoMode) {
    autoTimer = setTimeout(runAutoLoop, 1500); // Délai entre étapes
  }
}

// ── RESET ─────────────────────────────────────────────────────────────────────
async function resetSession() {
  if (!confirm('Réinitialiser la session courante ?')) return;
  autoMode = false;
  clearTimeout(autoTimer);
  document.getElementById('btnAuto').textContent = '⚡ AUTO-DÉCOUVERTE';
  document.getElementById('btnAuto').classList.remove('running');
  await fetch('api.php?action=reset', {method:'POST'});
  document.getElementById('anomalyLive').classList.remove('visible');
  document.getElementById('terminal').innerHTML = '';
  updatePipelineUI(0);
  appendLog('system', 'info', 'Session réinitialisée');
}

// ── POLL LOGS ─────────────────────────────────────────────────────────────────
let lastLogId = 0;
async function pollLogs() {
  try {
    const res = await fetch('api.php?action=logs&limit=30');
    const j   = await res.json();
    if (j.ok && j.logs) {
      const newLogs = j.logs.filter(l => parseInt(l.id) > lastLogId);
      newLogs.forEach(l => {
        appendLog(l.phase, l.level, l.message, l.created_at?.slice(11,19));
        lastLogId = Math.max(lastLogId, parseInt(l.id));
      });
    }
  } catch(e) {}
}

// ── STATS ─────────────────────────────────────────────────────────────────────
async function loadStats() {
  try {
    const res = await fetch('api.php?action=stats');
    const j   = await res.json();
    if (j.ok) {
      document.getElementById('statDisc').textContent = j.discoveries || 0;
      document.getElementById('statSig').textContent  = j.signals || 0;
      document.getElementById('statNov').textContent  = fmtScore(j.avg_novelty);
      document.getElementById('statImp').textContent  = fmtScore(j.avg_impact);
    }
  } catch(e) {}
}

// ── DISCOVERIES ───────────────────────────────────────────────────────────────
async function loadDiscoveries() {
  try {
    const res = await fetch('api.php?action=discoveries&limit=12');
    const j   = await res.json();
    if (!j.ok || !j.discoveries?.length) return;

    document.getElementById('discCount').textContent = j.total + ' découvertes';
    const grid = document.getElementById('discGrid');
    grid.innerHTML = j.discoveries.map(d => {
      const domains = (() => { try { return JSON.parse(d.domains_crossed || '[]'); } catch(e) { return []; } })();
      const nov = parseFloat(d.novelty_score || 0);
      const imp = parseFloat(d.impact_score || 0);
      return `<div class="disc-card" onclick="openDiscovery(${d.id})">
        <div class="dc-type">${esc(d.discovery_type || 'mechanism')}</div>
        <div class="dc-title">${esc(d.title || 'Sans titre')}</div>
        <div class="dc-claim">${esc(d.core_claim || d.vulgarized || '')}</div>
        <div class="dc-footer">
          <div>
            <div class="score-bar score-nov"><span>N</span><div class="score-track"><div class="score-fill" style="width:${nov*100}%"></div></div><span>${(nov*100).toFixed(0)}%</span></div>
            <div class="score-bar score-imp" style="margin-top:4px"><span>I</span><div class="score-track"><div class="score-fill" style="width:${imp*100}%"></div></div><span>${(imp*100).toFixed(0)}%</span></div>
          </div>
          <div class="dc-domains">${domains.map(d2 => `<span class="dc-domain">${esc(d2)}</span>`).join('')}</div>
        </div>
        <div class="dc-date">${d.created_at?.slice(0,16) || ''}</div>
      </div>`;
    }).join('');
  } catch(e) {}
}

// ── MODAL ─────────────────────────────────────────────────────────────────────
async function openDiscovery(id) {
  const res = await fetch('api.php?action=discovery&id=' + id);
  const j   = await res.json();
  if (!j.ok) return;

  const d = j.discovery;
  const a = j.article;

  document.getElementById('mType').textContent     = d.discovery_type || 'mechanism';
  document.getElementById('mTitle').textContent    = d.title || 'Sans titre';
  document.getElementById('mNov').textContent      = fmtScore(d.novelty_score);
  document.getElementById('mImp').textContent      = fmtScore(d.impact_score);
  document.getElementById('mCrit').textContent     = fmtScore(d.critique_score);
  document.getElementById('mClaim').textContent    = d.core_claim || '';
  document.getElementById('mMechanism').textContent= d.mechanism || '';
  document.getElementById('mVulg').textContent     = d.vulgarized || '';
  document.getElementById('mTest').textContent     = d.testability || '';

  // Article
  const artDiv = document.getElementById('articleContent');
  if (a && a.body_json) {
    try {
      const body = JSON.parse(a.body_json);
      const sections = [
        ['Résumé', body.abstract],
        ['Introduction', body.introduction],
        ['Mécanisme proposé', body.mechanism],
        ['Données support', body.evidence],
        ['Prédictions testables', body.predictions],
        ['Implications', body.implications],
        ['Limites', body.limitations],
        ['Conclusion', body.conclusion],
      ];
      artDiv.innerHTML = sections.filter(s=>s[1]).map(s =>
        `<div class="article-section"><h3>${esc(s[0])}</h3><p>${esc(s[1])}</p></div>`
      ).join('');
    } catch(e) {
      artDiv.innerHTML = `<p>${esc(a.abstract || '')}</p>`;
    }
  } else {
    artDiv.innerHTML = '<p style="opacity:0.4">Article non disponible</p>';
  }

  // Protocol
  const protoDiv = document.getElementById('protocolContent');
  if (d.protocol_sketch) {
    try {
      const p = JSON.parse(d.protocol_sketch);
      let html = `<div class="article-section"><h3>Modèle expérimental</h3><p>${esc(p.model_system || '')}</p></div>`;
      if (p.steps?.length) {
        html += '<div class="article-section"><h3>Étapes</h3>';
        p.steps.forEach(s => {
          html += `<div class="protocol-step">
            <div class="proto-week">${esc(s.week || '')}</div>
            <div><div class="proto-action">${esc(s.action || '')}</div><div class="proto-expected">→ ${esc(s.expected || '')}</div></div>
          </div>`;
        });
        html += '</div>';
      }
      html += `<div class="article-section"><h3>Critères GO / NO-GO</h3><p>✅ ${esc(p.go_criteria || '')} <br>❌ ${esc(p.nogo_criteria || '')}</p></div>`;
      html += `<div class="article-section"><h3>Budget & risque</h3><p>${esc(p.budget_estimate || '')} — ${esc(p.major_risk || '')}</p></div>`;
      protoDiv.innerHTML = html;
    } catch(e) { protoDiv.innerHTML = '<p style="opacity:0.4">Protocole non disponible</p>'; }
  } else {
    protoDiv.innerHTML = '<p style="opacity:0.4">Protocole non disponible</p>';
  }

  // Critique
  const critDiv = document.getElementById('critiqueContent');
  if (d.critique_notes) {
    const verdictClass = {
      reject: 'verdict-reject', major_revision: 'verdict-major',
      minor_revision: 'verdict-minor', accept: 'verdict-accept'
    };
    const vclass = verdictClass['major_revision'] || 'verdict-major';
    critDiv.innerHTML = `<div class="critique-verdict ${vclass}">${esc(d.critique_notes?.split('.')[0] || 'Évaluation')}</div>
      <div class="article-section"><h3>Résumé critique</h3><p>${esc(d.critique_notes || '')}</p></div>
      <div class="article-section"><h3>Analyse anomalie</h3><p>${esc(d.gap_analysis || '')}</p></div>`;
  } else {
    critDiv.innerHTML = '<p style="opacity:0.4">Critique non disponible</p>';
  }

  document.getElementById('modalOverlay').classList.add('open');
  switchTab('discovery');
}

function closeModal(e) {
  if (!e || e.target === document.getElementById('modalOverlay') || e.type === 'click') {
    document.getElementById('modalOverlay').classList.remove('open');
  }
}

function switchTab(tab) {
  document.querySelectorAll('.tab-btn').forEach((b,i) => {
    const tabs = ['discovery','article','protocol','critique'];
    b.classList.toggle('active', tabs[i] === tab);
  });
  document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
  document.getElementById('tab-' + tab)?.classList.add('active');
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

// ── INIT ──────────────────────────────────────────────────────────────────────
(async () => {
  await loadStats();
  await loadDiscoveries();

  // Check session state
  const res = await fetch('api.php?action=status');
  const j   = await res.json();
  if (j.ok) {
    updatePipelineUI(j.step || 0);
    if (j.anomaly) showAnomaly(j.anomaly);
  }

  // Poll logs pour session active
  await pollLogs();
})();
</script>
</body>
</html>
