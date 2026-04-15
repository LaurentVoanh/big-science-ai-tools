<?php
require_once __DIR__ . '/config.php';
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: discoveries.php'); exit; }
$db   = get_db();
$disc = $db->query("SELECT * FROM discoveries WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
if (!$disc) { header('Location: discoveries.php'); exit; }
$article = $db->query("SELECT * FROM articles WHERE discovery_id = $id ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$protocol = $disc['protocol_sketch'] ? json_decode($disc['protocol_sketch'], true) : null;
$keywords = $disc['keywords'] ? json_decode($disc['keywords'], true) : [];
$domains  = $disc['domains_crossed'] ? json_decode($disc['domains_crossed'], true) : [];
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($disc['title'] ?? 'Découverte') ?> — Discovery Engine</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Unbounded:wght@300;700;900&family=Lora:ital,wght@0,400;0,600;1,400&display=swap');
:root { --ink:#06060a; --paper:#f0ede6; --acid:#c8ff00; --cyan:#00e5ff; --mag:#ff2d6b; --grid:rgba(200,255,0,0.06); }
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
body { font-family:'Space Mono',monospace; background:var(--ink); color:var(--paper); min-height:100vh; }
body::before { content:''; position:fixed; inset:0; background-image:linear-gradient(var(--grid) 1px,transparent 1px),linear-gradient(90deg,var(--grid) 1px,transparent 1px); background-size:40px 40px; pointer-events:none; z-index:0; }
nav { position:fixed;top:0;left:0;right:0;z-index:1000; display:flex;align-items:center;justify-content:space-between; padding:0 2rem;height:64px; background:rgba(6,6,10,0.92); border-bottom:1px solid var(--acid); backdrop-filter:blur(8px); }
.nav-logo { font-family:'Unbounded',sans-serif;font-weight:900;font-size:1.1rem;color:var(--acid);letter-spacing:0.05em;text-decoration:none; }
.nav-back { color:var(--paper);text-decoration:none;font-size:0.75rem;letter-spacing:0.1em;text-transform:uppercase;opacity:0.6;transition:opacity 0.2s; }
.nav-back:hover { opacity:1;color:var(--acid); }
.app { padding-top:64px; min-height:100vh; position:relative; z-index:1; }
.container { max-width:960px; margin:0 auto; padding:3rem 2rem; }

/* Header */
.disc-header { margin-bottom:3rem; padding-bottom:2rem; border-bottom:1px solid rgba(255,255,255,0.08); }
.disc-meta-top { display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap; }
.meta-type { font-size:0.6rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--cyan);background:rgba(0,229,255,0.1);border:1px solid rgba(0,229,255,0.2);padding:0.3rem 0.75rem;border-radius:2px; }
.meta-domain { font-size:0.6rem;padding:0.3rem 0.75rem;border-radius:2px; }
.meta-da { border:1px solid rgba(255,45,107,0.4);color:rgba(255,45,107,0.9); }
.meta-db { border:1px solid rgba(0,229,255,0.4);color:rgba(0,229,255,0.9); }
.meta-arrow { color:rgba(200,255,0,0.4); }
.disc-title { font-family:'Unbounded',sans-serif;font-size:clamp(1.4rem,3vw,2.2rem);font-weight:900;line-height:1.25;margin-bottom:1.5rem; }

.score-row { display:flex;gap:2rem;flex-wrap:wrap; }
.score-item { text-align:center;min-width:80px; }
.score-num { font-family:'Unbounded',sans-serif;font-size:2rem;font-weight:900; }
.score-item.nov .score-num { color:var(--acid); }
.score-item.imp .score-num { color:var(--mag); }
.score-item.crit .score-num { color:var(--cyan); }
.score-label { font-size:0.6rem;opacity:0.45;letter-spacing:0.1em;text-transform:uppercase;margin-top:0.3rem; }

.claim-box { background:rgba(200,255,0,0.04);border-left:3px solid var(--acid);padding:1.5rem;margin-top:2rem;border-radius:0 6px 6px 0; }
.claim-box p { font-family:'Lora',serif;font-size:1rem;line-height:1.8;color:var(--paper); }

/* Sections */
.section { margin-bottom:3rem; }
.section h2 { font-family:'Unbounded',sans-serif;font-size:0.7rem;font-weight:700;letter-spacing:0.15em;color:var(--acid);text-transform:uppercase;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid rgba(200,255,0,0.1); }
.section p, .section .body-text { font-family:'Lora',serif;font-size:0.92rem;line-height:1.9;opacity:0.82; }

.keywords { display:flex;gap:0.5rem;flex-wrap:wrap;margin-top:1rem; }
.keyword { font-size:0.65rem;padding:0.25rem 0.7rem;border:1px solid rgba(255,255,255,0.12);border-radius:2px;opacity:0.6; }

/* Protocol */
.proto-grid { display:flex;flex-direction:column;gap:0.75rem; }
.proto-step { display:flex;gap:1.25rem;align-items:flex-start;padding:1rem;border-left:2px solid rgba(200,255,0,0.2); }
.proto-week { min-width:80px;font-size:0.65rem;color:var(--acid);font-weight:700;text-transform:uppercase;letter-spacing:0.08em;padding-top:0.15rem; }
.proto-body .action { font-size:0.82rem;line-height:1.6; }
.proto-body .expected { font-size:0.72rem;opacity:0.5;margin-top:0.3rem; }

.go-nogo { display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1.5rem; }
.go-box { padding:1rem;border-radius:4px; }
.go-box.go { border:1px solid rgba(200,255,0,0.3);background:rgba(200,255,0,0.04); }
.go-box.nogo { border:1px solid rgba(255,45,107,0.3);background:rgba(255,45,107,0.04); }
.go-label { font-size:0.6rem;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.5rem; }
.go-box.go .go-label { color:var(--acid); }
.go-box.nogo .go-label { color:var(--mag); }
.go-text { font-size:0.78rem;line-height:1.5; }

.budget-row { display:flex;gap:2rem;margin-top:1.5rem;padding:1rem;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:4px; }
.budget-item .bl { font-size:0.6rem;opacity:0.4;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.25rem; }
.budget-item .bv { font-size:0.85rem; }

/* Article sections */
.article-body .art-section { margin-bottom:2rem; }
.article-body .art-section h3 { font-family:'Unbounded',sans-serif;font-size:0.65rem;font-weight:700;letter-spacing:0.12em;color:var(--cyan);text-transform:uppercase;margin-bottom:0.75rem;padding-bottom:0.5rem;border-bottom:1px solid rgba(0,229,255,0.1); }
.article-body .art-section p { font-family:'Lora',serif;font-size:0.9rem;line-height:1.9;opacity:0.8; }

/* Critique */
.critique-notes { font-family:'Lora',serif;font-size:0.9rem;line-height:1.8;opacity:0.75; }
.gap-box { margin-top:1.5rem;padding:1rem;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:4px; }
.gap-lbl { font-size:0.6rem;opacity:0.4;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem; }
</style>
</head>
<body>
<nav>
  <a href="index.php" class="nav-logo">⬡ DISCOVERY ENGINE</a>
  <a href="discoveries.php" class="nav-back">← Retour</a>
</nav>
<div class="app">
<div class="container">

  <!-- HEADER -->
  <div class="disc-header">
    <div class="disc-meta-top">
      <span class="meta-type"><?= htmlspecialchars($disc['discovery_type'] ?? 'mechanism') ?></span>
      <?php if (!empty($domains)): ?>
        <span class="meta-domain meta-da"><?= htmlspecialchars($domains[0] ?? '') ?></span>
        <span class="meta-arrow">↔</span>
        <span class="meta-domain meta-db"><?= htmlspecialchars($domains[1] ?? '') ?></span>
      <?php endif; ?>
    </div>

    <h1 class="disc-title"><?= htmlspecialchars($disc['title'] ?? 'Découverte sans titre') ?></h1>

    <div class="score-row">
      <div class="score-item nov">
        <div class="score-num"><?= round(($disc['novelty_score'] ?? 0) * 100) ?>%</div>
        <div class="score-label">Novelty</div>
      </div>
      <div class="score-item imp">
        <div class="score-num"><?= round(($disc['impact_score'] ?? 0) * 100) ?>%</div>
        <div class="score-label">Impact</div>
      </div>
      <div class="score-item crit">
        <div class="score-num"><?= round(($disc['critique_score'] ?? 0) * 100) ?>%</div>
        <div class="score-label">Critique</div>
      </div>
    </div>

    <div class="claim-box">
      <p><?= htmlspecialchars($disc['core_claim'] ?? '') ?></p>
    </div>
  </div>

  <!-- VULGARISÉ -->
  <?php if (!empty($disc['vulgarized'])): ?>
  <div class="section">
    <h2>// En clair</h2>
    <p><?= htmlspecialchars($disc['vulgarized']) ?></p>
  </div>
  <?php endif; ?>

  <!-- MÉCANISME -->
  <?php if (!empty($disc['mechanism'])): ?>
  <div class="section">
    <h2>// Mécanisme proposé</h2>
    <p><?= htmlspecialchars($disc['mechanism']) ?></p>
  </div>
  <?php endif; ?>

  <!-- ARTICLE -->
  <?php if ($article && $article['body_json']): ?>
  <?php $body = json_decode($article['body_json'], true) ?: []; ?>
  <div class="section">
    <h2>// Article de revue</h2>
    <?php if (!empty($body['abstract'])): ?>
    <div style="background:rgba(0,229,255,0.04);border-left:3px solid var(--cyan);padding:1.25rem;margin-bottom:2rem;border-radius:0 6px 6px 0;">
      <div style="font-size:0.6rem;color:var(--cyan);letter-spacing:0.15em;text-transform:uppercase;margin-bottom:0.5rem">ABSTRACT</div>
      <p style="font-family:'Lora',serif;font-size:0.9rem;line-height:1.8;opacity:0.85"><?= htmlspecialchars($body['abstract']) ?></p>
    </div>
    <?php endif; ?>
    <div class="article-body">
      <?php
      $sects = [
        'introduction' => 'Introduction',
        'mechanism'    => 'Mécanisme',
        'evidence'     => 'Données support',
        'predictions'  => 'Prédictions',
        'implications' => 'Implications',
        'limitations'  => 'Limites',
        'conclusion'   => 'Conclusion',
      ];
      foreach ($sects as $key => $label): if (empty($body[$key])) continue; ?>
      <div class="art-section">
        <h3><?= $label ?></h3>
        <p><?= htmlspecialchars($body[$key]) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- PROTOCOLE -->
  <?php if ($protocol): ?>
  <div class="section">
    <h2>// Protocole expérimental</h2>
    <?php if (!empty($protocol['model_system'])): ?>
    <p style="margin-bottom:1.5rem"><strong>Modèle :</strong> <?= htmlspecialchars($protocol['model_system']) ?></p>
    <?php endif; ?>
    <?php if (!empty($protocol['steps'])): ?>
    <div class="proto-grid">
      <?php foreach ($protocol['steps'] as $step): ?>
      <div class="proto-step">
        <div class="proto-week"><?= htmlspecialchars($step['week'] ?? '') ?></div>
        <div class="proto-body">
          <div class="action"><?= htmlspecialchars($step['action'] ?? '') ?></div>
          <?php if (!empty($step['expected'])): ?><div class="expected">→ <?= htmlspecialchars($step['expected']) ?></div><?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div class="go-nogo">
      <div class="go-box go"><div class="go-label">✅ Critère GO</div><div class="go-text"><?= htmlspecialchars($protocol['go_criteria'] ?? '') ?></div></div>
      <div class="go-box nogo"><div class="go-label">❌ Critère NO-GO</div><div class="go-text"><?= htmlspecialchars($protocol['nogo_criteria'] ?? '') ?></div></div>
    </div>
    <?php if (!empty($protocol['budget_estimate']) || !empty($protocol['major_risk'])): ?>
    <div class="budget-row">
      <?php if (!empty($protocol['budget_estimate'])): ?><div class="budget-item"><div class="bl">Budget</div><div class="bv"><?= htmlspecialchars($protocol['budget_estimate']) ?></div></div><?php endif; ?>
      <?php if (!empty($protocol['major_risk'])): ?><div class="budget-item"><div class="bl">Risque principal</div><div class="bv"><?= htmlspecialchars($protocol['major_risk']) ?></div></div><?php endif; ?>
      <?php if (!empty($protocol['contingency'])): ?><div class="budget-item"><div class="bl">Plan B</div><div class="bv"><?= htmlspecialchars($protocol['contingency']) ?></div></div><?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- TESTABILITÉ -->
  <?php if (!empty($disc['testability'])): ?>
  <div class="section">
    <h2>// Testabilité</h2>
    <p><?= htmlspecialchars($disc['testability']) ?></p>
  </div>
  <?php endif; ?>

  <!-- CRITIQUE -->
  <div class="section">
    <h2>// Critique interne</h2>
    <?php if (!empty($disc['critique_notes'])): ?>
    <p class="critique-notes"><?= htmlspecialchars($disc['critique_notes']) ?></p>
    <?php endif; ?>
    <?php if (!empty($disc['gap_analysis'])): ?>
    <div class="gap-box">
      <div class="gap-lbl">Analyse des lacunes (pourquoi c'était ignoré)</div>
      <p style="font-size:0.82rem;opacity:0.7;line-height:1.7"><?= htmlspecialchars($disc['gap_analysis']) ?></p>
    </div>
    <?php endif; ?>
  </div>

  <!-- KEYWORDS -->
  <?php if (!empty($keywords)): ?>
  <div class="section">
    <h2>// Mots-clés</h2>
    <div class="keywords">
      <?php foreach ($keywords as $kw): ?>
      <span class="keyword"><?= htmlspecialchars($kw) ?></span>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- DATE -->
  <div style="font-size:0.65rem;opacity:0.25;margin-top:3rem;text-align:center;letter-spacing:0.1em">
    DÉCOUVERTE #<?= $id ?> — <?= htmlspecialchars($disc['created_at'] ?? '') ?>
  </div>

</div>
</div>
</body>
</html>
