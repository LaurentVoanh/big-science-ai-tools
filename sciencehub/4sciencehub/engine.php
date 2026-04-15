<?php
/**
 * DISCOVERY ENGINE — engine.php
 * Pipeline de découverte en 7 phases :
 *   0. Scout       → Détection de signaux faibles (anomalies dans la littérature)
 *   1. Harvest     → Collecte multi-sources (PubMed, ArXiv, OpenAlex, etc.)
 *   2. Collide     → Collision inter-domaines (trouver la connexion inédite)
 *   3. Hypothesize → Formuler la découverte précise et testable
 *   4. Critique    → Auto-critique rigoureuse (force le modèle à se contredire)
 *   5. Protocol    → Sketch du protocole expérimental
 *   6. Publish     → Rédaction article scientifique complet
 */

require_once __DIR__ . '/config.php';

set_time_limit(MAX_EXEC);

// ─── PHASE 0 : SCOUT — Détection anomalies / zones inconnues ─────────────────
function phase_scout(array &$state): void {
    $sid = $state['session_id'];

    $system = <<<'PROMPT'
Tu es un détecteur d'anomalies scientifiques. Ton rôle est de trouver des ZONES D'IGNORANCE FERTILE dans la littérature biomédicale et physique : des régions où les données existent mais ne sont pas reliées, où des patterns anormaux ont été signalés mais ignorés, où deux domaines font des observations contradictoires sur le même phénomène.

RÈGLES DE SCOUTING :
1. Cherche des CONTRADICTIONS entre domaines (ex: la neurologie dit X, la microbiologie dit ¬X sur le même mécanisme)
2. Cherche des PATTERNS ORPHELINS : observations publiées mais jamais suivies (< 5 citations, date > 2015)
3. Cherche des ANALOGIES INTER-DOMAINES inexploitées (même mécanisme physique/chimique dans deux contextes biologiques distincts)
4. JAMAIS de cibles mainstream (Alzheimer, cancer du poumon, diabète T2 générique)
5. Priorité aux signaux de 2020-2025 sous-cités (< 20 citations)

DOMAINES FERTILES pour collision :
- Biophysique ↔ Oncologie
- Chronobiologie ↔ Microbiome
- Mécanique des fluides ↔ Biologie cellulaire
- Physique quantique ↔ Neurosciences (controversé = fertile)
- Écologie microbienne ↔ Maladies auto-immunes
- Ingénierie matériaux ↔ Médecine régénérative

Réponds UNIQUEMENT en JSON valide, sans texte avant ni après :
{
  "anomaly_type": "<contradiction|orphan_signal|cross_domain_analogy|prediction_failure>",
  "domain_a": "<premier domaine>",
  "domain_b": "<deuxième domaine>",
  "anomaly_title": "<titre court de l'anomalie détectée>",
  "anomaly_description": "<description précise en 3-5 phrases de ce qui est anormal ou inexpliqué>",
  "why_ignored": "<hypothèse sur pourquoi la communauté a ignoré ce signal>",
  "fertility_score": <0.0-1.0, 1.0=zone hautement fertile pour découverte>,
  "pubmed_query": "<requête PubMed optimale pour collecter les signaux, max 60 chars>",
  "arxiv_query": "<requête ArXiv, anglais, max 50 chars>",
  "openalex_query": "<requête OpenAlex, max 50 chars>",
  "key_proteins_or_genes": ["<entité 1>", "<entité 2>"],
  "predicted_mechanism": "<mécanisme hypothétique non publié en 2 phrases>"
}
PROMPT;

    $already = implode(', ', array_slice($state['explored_anomalies'] ?? [], -8));
    $user = "Anomalies déjà explorées (ne pas répéter) : [{$already}]\nTrouve une nouvelle zone d'ignorance fertile, imprévisible et concrète.";

    op_log($sid, 'scout', 'info', '🔍 Scout lancé...');

    $r = mistral_call(
        [['role' => 'system', 'content' => $system], ['role' => 'user', 'content' => $user]],
        'discover', 1400, 0.85
    );

    if (!$r['ok'] || empty($r['data']['anomaly_title'])) {
        // Fallback vers anomalies connues mais sous-exploitées
        $fallbacks = [
            ['anomaly_type'=>'contradiction','domain_a'=>'chronobiologie','domain_b'=>'microbiome intestinal',
             'anomaly_title'=>'Désynchronisation circadienne et dysbiose','anomaly_description'=>'Les horloges biologiques périphériques des cellules épithéliales intestinales synchronisent la composition du microbiome, mais ce mécanisme est inversé chez les travailleurs postés sans explication publiée.','why_ignored'=>'Domaines séparés institutionnellement','fertility_score'=>0.82,
             'pubmed_query'=>'circadian clock intestinal microbiome synchronization','arxiv_query'=>'circadian microbiome epithelial','openalex_query'=>'circadian dysbiosis shift workers','key_proteins_or_genes'=>['BMAL1','PER2','TLR4'],'predicted_mechanism'=>'BMAL1 régule directement la sécrétion de mucus IgA via un mécanisme ROR-γt dépendant non décrit.'],
            ['anomaly_type'=>'orphan_signal','domain_a'=>'biophysique','domain_b'=>'oncologie',
             'anomaly_title'=>'Rigidité mécanique et dormance tumorale','anomaly_description'=>'Des études isolées signalent que la rigidité de la matrice extracellulaire inhibe la réémergence des cellules tumorales dormantes, mais le mécanisme moléculaire reliant les forces mécaniques à l\'épigénome tumoral est absent de la littérature.','why_ignored'=>'Biophysique sous-représentée dans les journaux oncologiques','fertility_score'=>0.79,
             'pubmed_query'=>'matrix stiffness tumor dormancy epigenetic','arxiv_query'=>'mechanosensing cancer dormancy molecular','openalex_query'=>'ECM rigidity tumor reactivation','key_proteins_or_genes'=>['YAP1','PIEZO1','H3K27me3'],'predicted_mechanism'=>'PIEZO1 active YAP via un mécanisme indépendant de Hippo sous contrainte mécanique élevée, bloquant la réémergence.'],
        ];
        $fb = $fallbacks[array_rand($fallbacks)];
        $state['anomaly'] = $fb;
        op_log($sid, 'scout', 'warning', '⚠️ Fallback anomalie', ['error' => $r['error'] ?? 'unknown']);
    } else {
        $state['anomaly'] = $r['data'];
        $state['explored_anomalies'][] = $r['data']['anomaly_title'];
    }

    op_log($sid, 'scout', 'success', '✅ Anomalie détectée: ' . $state['anomaly']['anomaly_title'], $state['anomaly']);
    $state['step'] = 1;
}

// ─── PHASE 1 : HARVEST — Collecte signaux depuis APIs ────────────────────────
function phase_harvest(array &$state): void {
    $sid     = $state['session_id'];
    $anomaly = $state['anomaly'];
    $db      = get_db();
    $signals = [];

    // PubMed
    $q_pub = urlencode(substr($anomaly['pubmed_query'] ?? $anomaly['anomaly_title'], 0, 200));
    $r = de_curl("https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term={$q_pub}&retmax=12&sort=relevance&retmode=json");
    if ($r['ok']) {
        $j = json_decode($r['body'], true);
        $ids = $j['esearchresult']['idlist'] ?? [];
        if (!empty($ids)) {
            $id_str = implode(',', array_slice($ids, 0, 8));
            $r2 = de_curl("https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&id={$id_str}&retmode=json");
            if ($r2['ok']) {
                $j2 = json_decode($r2['body'], true);
                foreach ($j2['result'] ?? [] as $pmid => $art) {
                    if ($pmid === 'uids') continue;
                    $abstract = ''; // Fetch abstract
                    $r3 = de_curl("https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id={$pmid}&retmode=text&rettype=abstract", null, [], 20);
                    if ($r3['ok']) $abstract = substr(strip_tags($r3['body']), 0, 600);

                    $signals[] = [
                        'source'   => 'pubmed',
                        'domain'   => $anomaly['domain_a'],
                        'title'    => $art['title'] ?? '',
                        'abstract' => $abstract,
                        'doi'      => $art['elocationid'] ?? '',
                        'year'     => (int) substr($art['pubdate'] ?? '0', 0, 4),
                    ];
                }
                op_log($sid, 'harvest', 'success', '📗 PubMed: ' . count($ids) . ' résultats');
            }
        }
    }
    sleep(1); // Rate limit

    // ArXiv
    $q_ax = urlencode(substr($anomaly['arxiv_query'] ?? $anomaly['anomaly_title'], 0, 100));
    $r = de_curl("https://export.arxiv.org/api/query?search_query=all:{$q_ax}&start=0&max_results=8&sortBy=relevance", null, ['Accept: application/xml'], 30);
    if ($r['ok']) {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($r['body']);
        if ($xml) {
            foreach ($xml->entry ?? [] as $entry) {
                $signals[] = [
                    'source'   => 'arxiv',
                    'domain'   => $anomaly['domain_b'],
                    'title'    => (string)($entry->title ?? ''),
                    'abstract' => substr((string)($entry->summary ?? ''), 0, 600),
                    'doi'      => (string)($entry->id ?? ''),
                    'year'     => (int) substr((string)($entry->published ?? ''), 0, 4),
                ];
            }
            op_log($sid, 'harvest', 'success', '📐 ArXiv: ' . count($xml->entry ?? []) . ' résultats');
        }
    }
    sleep(1);

    // OpenAlex
    $q_oa = urlencode(substr($anomaly['openalex_query'] ?? $anomaly['anomaly_title'], 0, 100));
    $r = de_curl("https://api.openalex.org/works?search={$q_oa}&per-page=8&sort=relevance_score:desc&select=title,abstract_inverted_index,doi,publication_year,concepts", null, [], 30);
    if ($r['ok']) {
        $j = json_decode($r['body'], true);
        foreach ($j['results'] ?? [] as $w) {
            // Reconstruct abstract from inverted index
            $abs = '';
            if (!empty($w['abstract_inverted_index'])) {
                $pos_word = [];
                foreach ($w['abstract_inverted_index'] as $word => $positions) {
                    foreach ($positions as $pos) {
                        $pos_word[$pos] = $word;
                    }
                }
                ksort($pos_word);
                $abs = substr(implode(' ', $pos_word), 0, 500);
            }
            $signals[] = [
                'source'   => 'openalex',
                'domain'   => $anomaly['domain_a'] . '/' . $anomaly['domain_b'],
                'title'    => $w['title'] ?? '',
                'abstract' => $abs,
                'doi'      => $w['doi'] ?? '',
                'year'     => (int)($w['publication_year'] ?? 0),
            ];
        }
        op_log($sid, 'harvest', 'success', '🌐 OpenAlex: ' . count($j['results'] ?? []) . ' résultats');
    }
    sleep(1);

    // EuropePMC (domain_b)
    $q_ep = urlencode(substr($anomaly['anomaly_title'], 0, 100));
    $r = de_curl("https://www.ebi.ac.uk/europepmc/webservices/rest/search?query={$q_ep}&format=json&pageSize=6&sort=CITED");
    if ($r['ok']) {
        $j = json_decode($r['body'], true);
        foreach ($j['resultList']['result'] ?? [] as $art) {
            $signals[] = [
                'source'   => 'europepmc',
                'domain'   => $anomaly['domain_b'],
                'title'    => $art['title'] ?? '',
                'abstract' => substr($art['abstractText'] ?? '', 0, 500),
                'doi'      => $art['doi'] ?? '',
                'year'     => (int)($art['pubYear'] ?? 0),
            ];
        }
        op_log($sid, 'harvest', 'success', '🌍 EuropePMC: ' . count($j['resultList']['result'] ?? []) . ' résultats');
    }

    // Sauvegarde signaux en DB
    if ($db && !empty($signals)) {
        $stmt = $db->prepare("INSERT INTO signals (source, domain, title, abstract, doi, year) VALUES (?,?,?,?,?,?)");
        foreach ($signals as $s) {
            if (!empty(trim($s['title']))) {
                $stmt->execute([$s['source'], $s['domain'], $s['title'], $s['abstract'] ?? '', $s['doi'] ?? '', $s['year'] ?? 0]);
            }
        }
    }

    $state['signals']  = $signals;
    $state['sig_count'] = count($signals);
    op_log($sid, 'harvest', 'success', '✅ Harvest: ' . count($signals) . ' signaux collectés');
    $state['step'] = 2;
}

// ─── PHASE 2 : COLLIDE — Collision inter-domaines ────────────────────────────
function phase_collide(array &$state): void {
    $sid     = $state['session_id'];
    $anomaly = $state['anomaly'];
    $signals = $state['signals'] ?? [];

    // Construire contexte compact
    $ctx = "ANOMALIE DÉTECTÉE:\n";
    $ctx .= "Type: {$anomaly['anomaly_type']}\n";
    $ctx .= "Domaines: {$anomaly['domain_a']} ↔ {$anomaly['domain_b']}\n";
    $ctx .= "Description: {$anomaly['anomaly_description']}\n";
    $ctx .= "Mécanisme prédit: " . ($anomaly['predicted_mechanism'] ?? 'non défini') . "\n\n";
    $ctx .= "SIGNAUX COLLECTÉS (" . count($signals) . ") :\n";

    $by_source = [];
    foreach ($signals as $s) {
        $src = $s['source'];
        if (!isset($by_source[$src])) $by_source[$src] = [];
        if (count($by_source[$src]) < 3) {
            $by_source[$src][] = "- [{$src}] " . substr($s['title'], 0, 100) . ($s['abstract'] ? " :: " . substr($s['abstract'], 0, 200) : '');
        }
    }
    foreach ($by_source as $src => $lines) {
        $ctx .= implode("\n", $lines) . "\n";
    }
    $ctx = substr($ctx, 0, 6000); // Limiter taille contexte

    $system = <<<'PROMPT'
Tu es un moteur de collision scientifique. Ton travail est de prendre des données de DEUX domaines distincts et de trouver la CONNEXION CACHÉE qui génère une découverte réelle.

PROCESSUS DE COLLISION :
1. LIS tous les signaux des deux domaines
2. CHERCHE une observation dans le domaine A qui, combinée à une observation du domaine B, révèle un mécanisme NOUVEAU non décrit dans la littérature
3. La collision doit être SPÉCIFIQUE : pas "il pourrait y avoir un lien", mais "la protéine X du domaine A interagit avec le pathway Y du domaine B via le mécanisme Z"
4. La collision doit être FALSIFIABLE : une expérience peut la confirmer ou l'infirmer

TYPES DE COLLISION RECHERCHÉS :
- Même molécule, rôle opposé dans deux contextes (contradiction productive)
- Mécanisme inconnu dans domaine A, déjà décrit dans domaine B (transfert de connaissance)
- Deux demi-puzzles de domaines différents qui s'assemblent en mécanisme complet
- Signal faible répété indépendamment = preuve de causalité

Réponds UNIQUEMENT en JSON valide :
{
  "collision_type": "<transfer|contradiction|assembly|repeated_signal>",
  "entity_a": "<molécule/gène/mécanisme du domaine A>",
  "entity_b": "<molécule/gène/mécanisme du domaine B>",
  "collision_description": "<description précise de la connexion inédite en 4-6 phrases>",
  "hidden_link": "<la connexion cachée en 1 phrase percutante>",
  "evidence_a": "<2-3 signaux du domaine A qui soutiennent la collision>",
  "evidence_b": "<2-3 signaux du domaine B qui soutiennent la collision>",
  "novelty_rationale": "<pourquoi cette connexion n'a pas été décrite, en 2 phrases>",
  "predicted_experiment": "<expérience la plus simple et rapide pour tester la collision>",
  "confidence": <0.0-1.0>,
  "impact": "<low|medium|high|paradigm_shift>"
}
PROMPT;

    op_log($sid, 'collide', 'info', '⚡ Collision inter-domaines en cours...');

    $r = mistral_call(
        [['role' => 'system', 'content' => $system], ['role' => 'user', 'content' => $ctx]],
        'discover', 2000, 0.7
    );

    if (!$r['ok'] || empty($r['data']['collision_description'])) {
        $state['collision'] = [
            'collision_type' => 'assembly',
            'entity_a' => $anomaly['key_proteins_or_genes'][0] ?? 'Entité A',
            'entity_b' => $anomaly['key_proteins_or_genes'][1] ?? 'Entité B',
            'collision_description' => 'Connexion inter-domaines détectée entre ' . $anomaly['domain_a'] . ' et ' . $anomaly['domain_b'] . ' via le mécanisme proposé dans l\'anomalie.',
            'hidden_link' => $anomaly['predicted_mechanism'] ?? 'Mécanisme à préciser',
            'evidence_a' => 'Basé sur ' . count(array_filter($signals, fn($s) => $s['source'] === 'pubmed')) . ' publications PubMed',
            'evidence_b' => 'Basé sur ' . count(array_filter($signals, fn($s) => $s['source'] === 'arxiv')) . ' préprints ArXiv',
            'novelty_rationale' => 'Domaines séparés institutionnellement, aucun groupe de recherche interdisciplinaire actif.',
            'predicted_experiment' => 'Knockdown du gène candidat in vitro + mesure du phenotype cross-domaine.',
            'confidence' => 0.55,
            'impact' => 'medium',
        ];
        op_log($sid, 'collide', 'warning', '⚠️ Fallback collision', ['error' => $r['error'] ?? 'JSON fail']);
    } else {
        $state['collision'] = $r['data'];
        op_log($sid, 'collide', 'success', '✅ Collision: ' . $r['data']['hidden_link'], $r['data']);
    }

    $state['step'] = 3;
}

// ─── PHASE 3 : HYPOTHESIZE — Formuler la découverte ─────────────────────────
function phase_hypothesize(array &$state): void {
    $sid       = $state['session_id'];
    $anomaly   = $state['anomaly'];
    $collision = $state['collision'];

    $ctx  = "ANOMALIE: {$anomaly['anomaly_title']}\n";
    $ctx .= "DOMAINES: {$anomaly['domain_a']} ↔ {$anomaly['domain_b']}\n";
    $ctx .= "COLLISION DÉTECTÉE: {$collision['hidden_link']}\n";
    $ctx .= "MÉCANISME: {$collision['collision_description']}\n";
    $ctx .= "PREUVES A: {$collision['evidence_a']}\n";
    $ctx .= "PREUVES B: {$collision['evidence_b']}\n";
    $ctx .= "TYPE: {$collision['collision_type']} | Impact prédit: {$collision['impact']}\n";

    $system = <<<'PROMPT'
Tu es un scientifique de découverte. A partir d'une collision inter-domaines, tu formules la DÉCOUVERTE précise, testable et révolutionnaire.

UNE VRAIE DÉCOUVERTE doit :
1. NOMMER précisément le mécanisme nouveau (acteurs moléculaires, signaux, cibles)
2. EXPLIQUER pourquoi elle change la compréhension actuelle (quel dogme elle modifie)
3. PRÉDIRE des conséquences vérifiables expérimentalement
4. AVOIR un potentiel thérapeutique ou technologique clair
5. ÊTRE rédigée comme une affirmation forte, pas une suggestion

Réponds UNIQUEMENT en JSON valide :
{
  "discovery_type": "<mechanism|pathway|target|drug_repurposing|biomarker|technology>",
  "title": "<titre scientifique accrocheur, 10-15 mots>",
  "core_claim": "<L'affirmation centrale de la découverte en 1-2 phrases techniques précises>",
  "mechanism": "<Description détaillée du mécanisme proposé, 5-8 phrases, nommer toutes les molécules clés>",
  "paradigm_challenged": "<Quel dogme actuel cette découverte remet en question>",
  "novelty_score": <0.0-1.0>,
  "impact_score": <0.0-1.0>,
  "testability": "<Comment la tester en 6 mois avec un budget de 50k€>",
  "therapeutic_potential": "<Application clinique directe si applicable>",
  "predicted_outcomes": ["<résultat 1>", "<résultat 2>", "<résultat 3>"],
  "analogies": "<Analogie simple pour expliquer la découverte à un non-spécialiste>",
  "vulgarized": "<Explication grand public en 3 phrases maximum, style magazine>",
  "keywords": ["<kw1>", "<kw2>", "<kw3>", "<kw4>", "<kw5>"]
}
PROMPT;

    op_log($sid, 'hypothesize', 'info', '🧬 Formulation découverte...');

    $r = mistral_call(
        [['role' => 'system', 'content' => $system], ['role' => 'user', 'content' => $ctx]],
        'discover', 2500, 0.6
    );

    if (!$r['ok'] || empty($r['data']['core_claim'])) {
        $state['discovery_raw'] = [
            'discovery_type' => 'mechanism',
            'title' => 'Connexion inédite: ' . $anomaly['domain_a'] . ' ↔ ' . $anomaly['domain_b'],
            'core_claim' => $collision['hidden_link'],
            'mechanism' => $collision['collision_description'],
            'paradigm_challenged' => 'La séparation disciplinaire entre ' . $anomaly['domain_a'] . ' et ' . $anomaly['domain_b'],
            'novelty_score' => $collision['confidence'],
            'impact_score' => $collision['impact'] === 'paradigm_shift' ? 0.95 : ($collision['impact'] === 'high' ? 0.8 : 0.6),
            'testability' => $collision['predicted_experiment'],
            'therapeutic_potential' => 'À évaluer selon les résultats expérimentaux.',
            'predicted_outcomes' => ['Confirmation de la collision', 'Identification du mécanisme précis', 'Validation in vivo'],
            'analogies' => 'Comme deux pièces de puzzles provenant de boîtes différentes qui s\'assemblent parfaitement.',
            'vulgarized' => $anomaly['anomaly_description'],
            'keywords' => [$anomaly['domain_a'], $anomaly['domain_b'], $anomaly['anomaly_type']],
        ];
        op_log($sid, 'hypothesize', 'warning', '⚠️ Fallback découverte');
    } else {
        $state['discovery_raw'] = $r['data'];
        op_log($sid, 'hypothesize', 'success', '✅ Découverte formulée: ' . $r['data']['title']);
    }

    $state['step'] = 4;
}

// ─── PHASE 4 : CRITIQUE — Auto-critique rigoureuse ───────────────────────────
function phase_critique(array &$state): void {
    $sid    = $state['session_id'];
    $disc   = $state['discovery_raw'];

    $ctx  = "DÉCOUVERTE À CRITIQUER:\n";
    $ctx .= "Titre: {$disc['title']}\n";
    $ctx .= "Affirmation: {$disc['core_claim']}\n";
    $ctx .= "Mécanisme: {$disc['mechanism']}\n";
    $ctx .= "Paradigme challengé: " . ($disc['paradigm_challenged'] ?? 'N/A') . "\n";

    $system = <<<'PROMPT'
Tu es un reviewer senior de Nature, connu pour tes rejets impitoyables mais justes. Critique cette découverte sans complaisance.

CHERCHE ACTIVEMENT :
1. Les FAILLES LOGIQUES (corrélation présentée comme causalité, biais de sélection)
2. Les DONNÉES MANQUANTES critiques pour valider
3. Les EXPLICATIONS ALTERNATIVES plus simples (rasoir d'Occam)
4. Les SURINTERPRÉTATIONS (affirmations non supportées par les preuves)
5. La REPRODUCTIBILITÉ (le mécanisme est-il suffisamment précis pour être testé?)

Réponds UNIQUEMENT en JSON valide :
{
  "verdict": "<reject|major_revision|minor_revision|accept>",
  "validity_score": <0.0-1.0>,
  "major_flaws": [{"flaw": "<description>", "severity": "<critical|major|minor>", "fix": "<correction>"}],
  "missing_evidence": ["<donnée manquante 1>", "<donnée manquante 2>"],
  "simpler_explanation": "<Explication alternative plus simple de Occam>",
  "overinterpretations": ["<affirmation exagérée>"],
  "reproducibility": "<low|medium|high>",
  "strengths": ["<force 1>", "<force 2>"],
  "recommended_revision": "<Ce qu'il faudrait ajouter pour que cette découverte soit publiable>",
  "summary": "<Évaluation globale en 2 phrases>"
}
PROMPT;

    op_log($sid, 'critique', 'info', '🔎 Auto-critique...');

    $r = mistral_call(
        [['role' => 'system', 'content' => $system], ['role' => 'user', 'content' => $ctx]],
        'discover', 1800, 0.4
    );

    $state['critique'] = $r['ok'] && !empty($r['data']['verdict']) ? $r['data'] : [
        'verdict' => 'major_revision',
        'validity_score' => 0.55,
        'major_flaws' => [['flaw' => 'Preuves indirectes uniquement', 'severity' => 'major', 'fix' => 'Expérience de validation directe requise']],
        'missing_evidence' => ['Données expérimentales directes', 'Réplication indépendante'],
        'simpler_explanation' => 'Coïncidence entre deux observations non reliées causalement.',
        'overinterpretations' => [],
        'reproducibility' => 'medium',
        'strengths' => ['Approche inter-disciplinaire originale', 'Hypothèse falsifiable'],
        'recommended_revision' => 'Ajouter données expérimentales préliminaires.',
        'summary' => 'Découverte intéressante mais nécessite validation expérimentale.',
    ];

    op_log($sid, 'critique', 'success', '✅ Critique: ' . $state['critique']['verdict'] . ' (score: ' . $state['critique']['validity_score'] . ')');
    $state['step'] = 5;
}

// ─── PHASE 5 : PROTOCOL — Sketch expérimental ────────────────────────────────
function phase_protocol(array &$state): void {
    $sid    = $state['session_id'];
    $disc   = $state['discovery_raw'];
    $crit   = $state['critique'];

    $ctx  = "DÉCOUVERTE: {$disc['core_claim']}\n";
    $ctx .= "MÉCANISME: {$disc['mechanism']}\n";
    $ctx .= "TESTABILITÉ: " . ($disc['testability'] ?? '') . "\n";
    $ctx .= "FAILLES IDENTIFIÉES: " . implode('; ', array_map(fn($f) => $f['flaw'], $crit['major_flaws'] ?? [])) . "\n";
    $ctx .= "DONNÉES MANQUANTES: " . implode('; ', $crit['missing_evidence'] ?? []) . "\n";

    $system = <<<'PROMPT'
Tu es un chef de laboratoire expert. Conçois le protocole expérimental minimal, réaliste et décisif pour valider cette découverte en 6 mois avec un budget limité.

Le protocole doit être DÉCISIF (go/no-go clair), RÉALISTE (matériel standard disponible), RAPIDE (< 6 mois), ÉCONOMIQUE (< 50k€).

Réponds UNIQUEMENT en JSON valide :
{
  "protocol_title": "<titre court>",
  "model_system": "<modèle expérimental choisi: cellule/organisme et pourquoi>",
  "key_readouts": ["<mesure 1>", "<mesure 2>", "<mesure 3>"],
  "steps": [
    {"week": "<semaine(s)>", "action": "<action précise>", "expected": "<résultat attendu>"}
  ],
  "go_criteria": "<critère quantitatif go: ex p<0.05 avec fold-change>2>",
  "nogo_criteria": "<critère no-go qui invalide la découverte>",
  "budget_estimate": "<estimation €>",
  "major_risk": "<risque technique principal>",
  "contingency": "<plan B si le modèle primaire échoue>"
}
PROMPT;

    op_log($sid, 'protocol', 'info', '🔬 Génération protocole...');

    $r = mistral_call(
        [['role' => 'system', 'content' => $system], ['role' => 'user', 'content' => $ctx]],
        'code', 1500, 0.4
    );

    $state['protocol'] = $r['ok'] && !empty($r['data']['steps']) ? $r['data'] : [
        'protocol_title' => 'Protocole de validation - ' . ($disc['title'] ?? 'Découverte'),
        'model_system' => 'Lignée cellulaire humaine primaire ou modèle murin',
        'key_readouts' => ['Western Blot', 'qRT-PCR', 'Microscopie confocale'],
        'steps' => [
            ['week' => '1-2', 'action' => 'Clonage et expression du construct d\'intérêt', 'expected' => 'Confirmation expression protéine'],
            ['week' => '3-6', 'action' => 'Knockdown/Knockout du gène candidat', 'expected' => 'Phénotype observé selon hypothèse'],
            ['week' => '7-16', 'action' => 'Rescue experiment et validation mécanistique', 'expected' => 'Restauration phénotype = confirmation causalité'],
        ],
        'go_criteria' => 'p < 0.01, n ≥ 3, effet ≥ 2x sur readout principal',
        'nogo_criteria' => 'Absence d\'effet ou effet contraire au prédit',
        'budget_estimate' => '25 000 - 45 000 €',
        'major_risk' => 'Modèle cellulaire non représentatif du contexte in vivo',
        'contingency' => 'Switch vers organoides ou modèle murin KO disponible',
    ];

    op_log($sid, 'protocol', 'success', '✅ Protocole généré');
    $state['step'] = 6;
}

// ─── PHASE 6 : PUBLISH — Article complet ─────────────────────────────────────
function phase_publish(array &$state): int {
    $sid      = $state['session_id'];
    $disc     = $state['discovery_raw'];
    $crit     = $state['critique'];
    $protocol = $state['protocol'];
    $anomaly  = $state['anomaly'];
    $collision= $state['collision'];
    $db       = get_db();

    // Sauvegarder la découverte en DB
    $novelty  = (float)($disc['novelty_score'] ?? 0.5);
    $impact   = (float)($disc['impact_score'] ?? 0.5);
    $keywords = is_array($disc['keywords'] ?? null) ? json_encode($disc['keywords']) : '[]';
    $domains  = json_encode([$anomaly['domain_a'], $anomaly['domain_b']]);
    $sources  = json_encode(array_unique(array_column($state['signals'] ?? [], 'source')));

    $stmt = $db->prepare("INSERT INTO discoveries
        (session_id, status, discovery_type, title, core_claim, mechanism, analogy, novelty_score, impact_score,
         testability, protocol_sketch, domains_crossed, anomaly_desc, gap_analysis,
         critique_score, critique_notes, vulgarized, keywords, sources_used)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $sid, 'published',
        $disc['discovery_type'] ?? 'mechanism',
        $disc['title'] ?? 'Découverte sans titre',
        $disc['core_claim'] ?? '',
        $disc['mechanism'] ?? '',
        $disc['analogies'] ?? '',
        $novelty, $impact,
        $disc['testability'] ?? '',
        json_encode($protocol),
        $domains,
        $anomaly['anomaly_description'] ?? '',
        $anomaly['why_ignored'] ?? '',
        (float)($crit['validity_score'] ?? 0.5),
        $crit['summary'] ?? '',
        $disc['vulgarized'] ?? '',
        $keywords,
        $sources,
    ]);
    $discovery_id = (int)$db->lastInsertId();

    // Générer l'article avec le modèle synthesize (large context)
    $ctx  = "DÉCOUVERTE:\nTitre: {$disc['title']}\nAffirmation: {$disc['core_claim']}\n";
    $ctx .= "Mécanisme: {$disc['mechanism']}\n";
    $ctx .= "Domaines: {$anomaly['domain_a']} ↔ {$anomaly['domain_b']}\n";
    $ctx .= "Analogie: " . ($disc['analogies'] ?? '') . "\n";
    $ctx .= "Protocole: " . ($protocol['protocol_title'] ?? '') . " — " . ($protocol['go_criteria'] ?? '') . "\n";
    $ctx .= "Critique: " . ($crit['summary'] ?? '') . "\n";
    $ctx .= "Vulgarisé: " . ($disc['vulgarized'] ?? '') . "\n";

    $system = <<<'PROMPT'
Tu es un rédacteur pour Nature Reviews. Rédige un article complet de revue scientifique en français, format IMRaD étendu, sur la découverte fournie.

Structure obligatoire : Introduction (contexte, gap), Mécanisme proposé (détail technique), Données support (synthèse des signaux), Prédictions testables, Implications (cliniques + scientifiques), Limites, Conclusion.

Réponds UNIQUEMENT en JSON valide :
{
  "title": "<titre définitif>",
  "abstract": "<résumé 180-220 mots, 4 parties: contexte/lacune/découverte/implications>",
  "introduction": "<300-400 mots, contexte + état de l'art + gap identifié>",
  "mechanism": "<300-400 mots, description mécanisme détaillé>",
  "evidence": "<250-350 mots, synthèse des signaux collectés par source>",
  "predictions": "<200-250 mots, 3-5 prédictions expérimentales avec readouts>",
  "implications": "<200-250 mots, cliniques + scientifiques + sociétaux>",
  "limitations": "<150-200 mots, honnêteté intellectuelle sur les limites>",
  "conclusion": "<150-180 mots, synthèse + ouverture>",
  "word_count": <total estimé>
}
PROMPT;

    op_log($sid, 'publish', 'info', '📝 Rédaction article...', null, $discovery_id);

    $r = mistral_call(
        [['role' => 'system', 'content' => $system], ['role' => 'user', 'content' => $ctx]],
        'synthesize', 4000, 0.5
    );

    if ($r['ok'] && !empty($r['data']['abstract'])) {
        $ad = $r['data'];
        $stmt2 = $db->prepare("INSERT INTO articles (discovery_id, title, abstract, body_json, word_count) VALUES (?,?,?,?,?)");
        $stmt2->execute([
            $discovery_id,
            $ad['title'] ?? $disc['title'],
            $ad['abstract'] ?? '',
            json_encode($ad, JSON_UNESCAPED_UNICODE),
            (int)($ad['word_count'] ?? 0),
        ]);
        op_log($sid, 'publish', 'success', '✅ Article généré', ['word_count' => $ad['word_count'] ?? 0], $discovery_id);
    } else {
        op_log($sid, 'publish', 'warning', '⚠️ Article partiel', ['error' => $r['error'] ?? ''], $discovery_id);
    }

    // Mise à jour domain_map
    try {
        $db->exec("INSERT INTO domain_map (domain_a, domain_b, bridge_count, last_bridge, fertility)
                   VALUES ('" . SQLite3::escapeString($anomaly['domain_a']) . "', '" . SQLite3::escapeString($anomaly['domain_b']) . "', 1, CURRENT_TIMESTAMP, $novelty)
                   ON CONFLICT(domain_a, domain_b) DO UPDATE SET bridge_count = bridge_count+1, last_bridge=CURRENT_TIMESTAMP, fertility=($novelty + fertility)/2");
    } catch (Exception $e) {}

    $state['discovery_id'] = $discovery_id;
    $state['step'] = 7; // Done
    op_log($sid, 'publish', 'success', '🎉 Découverte #' . $discovery_id . ' complète!', null, $discovery_id);
    return $discovery_id;
}

// ─── ORCHESTRATEUR ────────────────────────────────────────────────────────────
function run_pipeline_step(array &$state): array {
    $step = (int)($state['step'] ?? 0);
    switch ($step) {
        case 0: phase_scout($state);      break;
        case 1: phase_harvest($state);    break;
        case 2: phase_collide($state);    break;
        case 3: phase_hypothesize($state); break;
        case 4: phase_critique($state);   break;
        case 5: phase_protocol($state);   break;
        case 6:
            $did = phase_publish($state);
            $state['last_discovery_id'] = $did;
            $state['step'] = 0; // Reset pour prochaine découverte
            break;
        default:
            $state['step'] = 0;
    }

    save_session_state($state['session_id'], $state);
    return $state;
}

function get_phase_label(int $step): array {
    $phases = [
        0 => ['name' => 'Scout',       'emoji' => '🔍', 'desc' => 'Détection anomalies'],
        1 => ['name' => 'Harvest',     'emoji' => '📡', 'desc' => 'Collecte signaux'],
        2 => ['name' => 'Collide',     'emoji' => '⚡', 'desc' => 'Collision inter-domaines'],
        3 => ['name' => 'Hypothesize', 'emoji' => '🧬', 'desc' => 'Formulation découverte'],
        4 => ['name' => 'Critique',    'emoji' => '🔎', 'desc' => 'Auto-critique'],
        5 => ['name' => 'Protocol',    'emoji' => '🔬', 'desc' => 'Protocole expérimental'],
        6 => ['name' => 'Publish',     'emoji' => '📝', 'desc' => 'Rédaction article'],
    ];
    return $phases[$step] ?? ['name' => 'Unknown', 'emoji' => '⏳', 'desc' => ''];
}
