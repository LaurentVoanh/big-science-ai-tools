<?php
/**
 * DISCOVERY ENGINE — config.php
 * Moteur de découverte scientifique par collision inter-domaines
 * Architecture: SQLite tout-en-un, Mistral AI, cURL Hostinger-compatible
 */

// ─── INIT ────────────────────────────────────────────────────────────────────
@error_reporting(0);
@ini_set('display_errors', 0);
@ini_set('log_errors', 1);
while (@ob_get_level() > 0) @ob_end_clean();

if (session_status() === PHP_SESSION_NONE) @session_start();

// ─── CONSTANTES ──────────────────────────────────────────────────────────────
define('DE_VERSION',  '1.0');
define('DB_PATH',     __DIR__ . '/discovery.sqlite');
define('STORE_PATH',  __DIR__ . '/storage/');
define('MAX_EXEC',    290);
define('MISTRAL_EP',  'https://api.mistral.ai/v1/chat/completions');

// ─── CLÉS API ────────────────────────────────────────────────────────────────
$MISTRAL_KEYS = [
    'a5qaRTjWUjGJpAk5z35XcdEP5ZbH8Rakec',
    'bo3rG1zvdq1yDOvjb7Z4J3J3eHXRShytub',
    'cvEzQMKN74Ez8RIwJ6y8J30ENDjFruXkFa'
];
$MISTRAL_KEY_IDX = 0;

// Modèles par tâche — FREE TIER
$MODELS = [
    'discover'   => 'mistral-large-2512',    // raisonnement complexe pour trouver anomalies
    'synthesize' => 'mistral-medium-2505',   // synthèse large contexte
    'code'       => 'codestral-2508',        // génération code protocole
    'fast'       => 'mistral-small-2603',    // tâches rapides + large ctx
];

// ─── STORAGE ─────────────────────────────────────────────────────────────────
if (!is_dir(STORE_PATH)) @mkdir(STORE_PATH, 0755, true);

// ─── BASE DE DONNÉES ─────────────────────────────────────────────────────────
function get_db(): ?PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;
    try {
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA journal_mode=WAL');
        $pdo->exec('PRAGMA synchronous=NORMAL');
        $pdo->exec('PRAGMA foreign_keys=ON');
        init_db_schema($pdo);
        return $pdo;
    } catch (Exception $e) {
        error_log('DB error: ' . $e->getMessage());
        return null;
    }
}

function init_db_schema(PDO $pdo): void {
    // Signaux faibles collectés depuis les APIs
    $pdo->exec("CREATE TABLE IF NOT EXISTS signals (
        id          INTEGER PRIMARY KEY AUTOINCREMENT,
        source      TEXT NOT NULL,
        domain      TEXT NOT NULL,
        title       TEXT NOT NULL,
        abstract    TEXT,
        doi         TEXT,
        url         TEXT,
        year        INTEGER,
        keywords    TEXT,
        raw_json    TEXT,
        embedding_hint TEXT,
        discovery_id INTEGER,
        created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Découvertes = collision entre signaux de domaines différents
    $pdo->exec("CREATE TABLE IF NOT EXISTS discoveries (
        id              INTEGER PRIMARY KEY AUTOINCREMENT,
        session_id      TEXT NOT NULL,
        status          TEXT DEFAULT 'draft',
        discovery_type  TEXT NOT NULL,
        title           TEXT NOT NULL,
        core_claim      TEXT NOT NULL,
        mechanism       TEXT,
        analogy         TEXT,
        novelty_score   REAL DEFAULT 0.5,
        impact_score    REAL DEFAULT 0.5,
        testability     TEXT,
        protocol_sketch TEXT,
        domains_crossed TEXT,
        anomaly_desc    TEXT,
        gap_analysis    TEXT,
        critique_score  REAL DEFAULT 0.5,
        critique_notes  TEXT,
        vulgarized      TEXT,
        keywords        TEXT,
        sources_used    TEXT,
        iteration       INTEGER DEFAULT 1,
        created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Articles générés
    $pdo->exec("CREATE TABLE IF NOT EXISTS articles (
        id           INTEGER PRIMARY KEY AUTOINCREMENT,
        discovery_id INTEGER NOT NULL,
        title        TEXT,
        abstract     TEXT,
        body_json    TEXT,
        word_count   INTEGER DEFAULT 0,
        created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (discovery_id) REFERENCES discoveries(id)
    )");

    // Journal de bord des opérations
    $pdo->exec("CREATE TABLE IF NOT EXISTS ops_log (
        id          INTEGER PRIMARY KEY AUTOINCREMENT,
        session_id  TEXT,
        discovery_id INTEGER,
        phase       TEXT,
        level       TEXT DEFAULT 'info',
        message     TEXT,
        payload     TEXT,
        created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Carte des domaines scientifiques et connexions connues/inconnues
    $pdo->exec("CREATE TABLE IF NOT EXISTS domain_map (
        id          INTEGER PRIMARY KEY AUTOINCREMENT,
        domain_a    TEXT NOT NULL,
        domain_b    TEXT NOT NULL,
        bridge_count INTEGER DEFAULT 0,
        last_bridge  DATETIME,
        fertility    REAL DEFAULT 0.5,
        UNIQUE(domain_a, domain_b)
    )");

    // Sessions de travail
    $pdo->exec("CREATE TABLE IF NOT EXISTS sessions (
        id         TEXT PRIMARY KEY,
        step       INTEGER DEFAULT 0,
        state_json TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
}

// ─── CURL ROBUSTE ────────────────────────────────────────────────────────────
function de_curl(string $url, ?array $post = null, array $headers = [], int $timeout = 45): array {
    $attempt = 0;
    do {
        $attempt++;
        $ch = curl_init($url);
        $h  = array_merge(['Accept: application/json', 'Content-Type: application/json'], $headers);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 3,
            CURLOPT_USERAGENT      => 'DiscoveryEngine/' . DE_VERSION,
            CURLOPT_HTTPHEADER     => $h,
            CURLOPT_ENCODING       => 'gzip,deflate',
        ]);
        if ($post !== null) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        }
        $body      = curl_exec($ch);
        $err       = curl_error($ch);
        $code      = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($body && !$err && $code >= 200 && $code < 300) {
            return ['ok' => true, 'body' => $body, 'code' => $code];
        }
        if ($code === 429 && $attempt < 3) { sleep(2); continue; }
        if ($attempt < 3 && $code >= 500) { sleep(1); continue; }
        break;
    } while (true);

    return ['ok' => false, 'body' => null, 'code' => $code ?? 0, 'error' => $err ?? "HTTP $code"];
}

// ─── MISTRAL AI ──────────────────────────────────────────────────────────────
function mistral_call(array $messages, string $model_key = 'fast', int $max_tokens = 2500, float $temperature = 0.6, bool $json_mode = true): array {
    global $MISTRAL_KEYS, $MISTRAL_KEY_IDX, $MODELS;

    $model = $MODELS[$model_key] ?? $MODELS['fast'];
    $payload = [
        'model'       => $model,
        'messages'    => $messages,
        'temperature' => $temperature,
        'max_tokens'  => $max_tokens,
    ];
    if ($json_mode) {
        $payload['response_format'] = ['type' => 'json_object'];
    }

    // Rotation sur 3 clés
    for ($i = 0; $i < count($MISTRAL_KEYS); $i++) {
        $key = $MISTRAL_KEYS[($MISTRAL_KEY_IDX + $i) % count($MISTRAL_KEYS)];
        $r   = de_curl(MISTRAL_EP, $payload, ['Authorization: Bearer ' . $key], 120);

        if (!$r['ok']) {
            if (in_array($r['code'], [401, 403, 429])) { sleep(1); continue; }
            continue;
        }

        $j = json_decode($r['body'], true);
        if (!isset($j['choices'][0]['message']['content'])) continue;

        $MISTRAL_KEY_IDX = ($MISTRAL_KEY_IDX + $i + 1) % count($MISTRAL_KEYS);
        $content = trim($j['choices'][0]['message']['content']);

        // Nettoyage backticks
        $content = preg_replace('/^```(?:json)?\s*/i', '', $content);
        $content = preg_replace('/\s*```$/i', '', $content);
        $content = trim($content);

        if (!$json_mode) {
            return ['ok' => true, 'text' => $content, 'model' => $model, 'tokens' => $j['usage']['total_tokens'] ?? 0];
        }

        // Extraction JSON robuste
        $data = json_decode($content, true);
        if (!is_array($data)) {
            // Tentative extraction bloc JSON
            if (preg_match('/\{[\s\S]*\}/m', $content, $m)) {
                $data = json_decode($m[0], true);
            }
        }
        if (!is_array($data)) {
            // Réparation trailing comma
            $fixed = preg_replace('/,\s*([\}\]])/', '$1', $content);
            $data  = json_decode($fixed, true);
        }
        if (!is_array($data)) {
            continue; // Essai clé suivante
        }

        return ['ok' => true, 'data' => $data, 'model' => $model, 'tokens' => $j['usage']['total_tokens'] ?? 0];
    }

    return ['ok' => false, 'error' => 'All Mistral keys failed or JSON unparseable'];
}

// ─── LOG ─────────────────────────────────────────────────────────────────────
function op_log(string $session_id, string $phase, string $level, string $message, ?array $payload = null, ?int $discovery_id = null): void {
    $db = get_db();
    if (!$db) return;
    $stmt = $db->prepare("INSERT INTO ops_log (session_id, discovery_id, phase, level, message, payload) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$session_id, $discovery_id, $phase, $level, $message, $payload ? json_encode($payload, JSON_UNESCAPED_UNICODE) : null]);
}

// ─── SESSION STATE ────────────────────────────────────────────────────────────
function get_session_state(string $sid): array {
    $db = get_db();
    if (!$db) return [];
    $r = $db->prepare("SELECT state_json FROM sessions WHERE id = ?");
    $r->execute([$sid]);
    $row = $r->fetch(PDO::FETCH_ASSOC);
    if (!$row) return [];
    return json_decode($row['state_json'] ?? '{}', true) ?: [];
}

function save_session_state(string $sid, array $state): void {
    $db = get_db();
    if (!$db) return;
    $stmt = $db->prepare("INSERT INTO sessions (id, step, state_json, updated_at)
                          VALUES (?,?,?,CURRENT_TIMESTAMP)
                          ON CONFLICT(id) DO UPDATE SET step=excluded.step, state_json=excluded.state_json, updated_at=excluded.updated_at");
    $stmt->execute([$sid, $state['step'] ?? 0, json_encode($state, JSON_UNESCAPED_UNICODE)]);
}

// ─── SESSION ID ───────────────────────────────────────────────────────────────
if (!isset($_SESSION['de_sid'])) {
    $_SESSION['de_sid'] = bin2hex(random_bytes(12));
}
define('SESSION_ID', $_SESSION['de_sid']);
get_db(); // init schema
