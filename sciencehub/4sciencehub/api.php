<?php
/**
 * DISCOVERY ENGINE — api.php
 * API JSON unifiée — ZERO texte parasite, JSON pur
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/engine.php';

header('Content-Type: application/json; charset=utf-8');
// Bloquer tout output parasite
while (@ob_get_level() > 0) @ob_end_clean();

set_time_limit(MAX_EXEC);

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$db     = get_db();

try {

    switch ($action) {

        // ── Exécuter une étape du pipeline ──────────────────────────────────
        case 'step':
            $sid   = SESSION_ID;
            $state = get_session_state($sid);
            if (empty($state)) {
                $state = ['session_id' => $sid, 'step' => 0, 'explored_anomalies' => [], 'signals' => []];
            }
            $state['session_id'] = $sid;

            $before_step = (int)($state['step'] ?? 0);
            $phase_info  = get_phase_label($before_step);

            $state = run_pipeline_step($state);

            $after_step = (int)($state['step'] ?? 0);
            $completed  = ($before_step === 6); // Phase publish = découverte complète

            echo json_encode([
                'ok'          => true,
                'step_done'   => $before_step,
                'step_next'   => $after_step,
                'phase'       => $phase_info,
                'discovery_id'=> $state['last_discovery_id'] ?? null,
                'completed'   => $completed,
                'anomaly'     => $state['anomaly'] ?? null,
                'signals_count' => $state['sig_count'] ?? 0,
            ], JSON_UNESCAPED_UNICODE);
            break;

        // ── Statut pipeline ─────────────────────────────────────────────────
        case 'status':
            $sid   = SESSION_ID;
            $state = get_session_state($sid);
            $step  = (int)($state['step'] ?? 0);
            echo json_encode([
                'ok'      => true,
                'step'    => $step,
                'phase'   => get_phase_label($step),
                'anomaly' => $state['anomaly'] ?? null,
                'running' => !empty($state),
            ], JSON_UNESCAPED_UNICODE);
            break;

        // ── Reset session ────────────────────────────────────────────────────
        case 'reset':
            $sid  = SESSION_ID;
            $db->prepare("DELETE FROM sessions WHERE id = ?")->execute([$sid]);
            echo json_encode(['ok' => true, 'message' => 'Session réinitialisée']);
            break;

        // ── Liste des découvertes ────────────────────────────────────────────
        case 'discoveries':
            $limit  = min((int)($_GET['limit'] ?? 20), 100);
            $offset = (int)($_GET['offset'] ?? 0);
            $rows   = $db->query("SELECT id, discovery_type, title, core_claim, novelty_score, impact_score,
                                        domains_crossed, critique_score, vulgarized, keywords, sources_used, created_at
                                   FROM discoveries ORDER BY created_at DESC LIMIT $limit OFFSET $offset")->fetchAll(PDO::FETCH_ASSOC);
            $total  = (int)$db->query("SELECT COUNT(*) FROM discoveries")->fetchColumn();
            echo json_encode(['ok' => true, 'discoveries' => $rows, 'total' => $total], JSON_UNESCAPED_UNICODE);
            break;

        // ── Détail une découverte ────────────────────────────────────────────
        case 'discovery':
            $id   = (int)($_GET['id'] ?? 0);
            if (!$id) throw new Exception('ID requis');
            $disc = $db->query("SELECT * FROM discoveries WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
            if (!$disc) throw new Exception('Découverte introuvable');
            $article = $db->query("SELECT * FROM articles WHERE discovery_id = $id ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['ok' => true, 'discovery' => $disc, 'article' => $article ?: null], JSON_UNESCAPED_UNICODE);
            break;

        // ── Logs récents ─────────────────────────────────────────────────────
        case 'logs':
            $sid    = SESSION_ID;
            $limit  = min((int)($_GET['limit'] ?? 50), 200);
            $did    = (int)($_GET['discovery_id'] ?? 0);
            if ($did) {
                $stmt = $db->prepare("SELECT * FROM ops_log WHERE discovery_id = ? ORDER BY id DESC LIMIT ?");
                $stmt->execute([$did, $limit]);
            } else {
                $stmt = $db->prepare("SELECT * FROM ops_log ORDER BY id DESC LIMIT ?");
                $stmt->execute([$limit]);
            }
            $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['ok' => true, 'logs' => array_reverse($logs)], JSON_UNESCAPED_UNICODE);
            break;

        // ── Stats dashboard ──────────────────────────────────────────────────
        case 'stats':
            $d_count    = (int)$db->query("SELECT COUNT(*) FROM discoveries")->fetchColumn();
            $s_count    = (int)$db->query("SELECT COUNT(*) FROM signals")->fetchColumn();
            $avg_nov    = (float)$db->query("SELECT AVG(novelty_score) FROM discoveries")->fetchColumn();
            $avg_imp    = (float)$db->query("SELECT AVG(impact_score) FROM discoveries")->fetchColumn();
            $domains    = $db->query("SELECT domain_a, domain_b, bridge_count, fertility FROM domain_map ORDER BY bridge_count DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
            $best       = $db->query("SELECT id, title, novelty_score, impact_score FROM discoveries ORDER BY (novelty_score+impact_score) DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'ok'               => true,
                'discoveries'      => $d_count,
                'signals'          => $s_count,
                'avg_novelty'      => round($avg_nov, 2),
                'avg_impact'       => round($avg_imp, 2),
                'domain_map'       => $domains,
                'top_discoveries'  => $best,
            ], JSON_UNESCAPED_UNICODE);
            break;

        default:
            echo json_encode(['ok' => false, 'error' => 'Action inconnue: ' . htmlspecialchars($action)]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
