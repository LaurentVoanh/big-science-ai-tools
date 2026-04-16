<?php
/**
 * functions.php - Fonctions utilitaires
 */

/**
 * Nettoie et sécurise les données utilisateur
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Génère un token CSRF
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie le token CSRF
 */
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Redirige vers une URL
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Vérifie si l'utilisateur est connecté
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Obtient l'utilisateur connecté
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['user_email'],
        'name' => $_SESSION['user_name']
    ];
}

/**
 * Formate une date
 */
function formatDate($date, $format = 'd/m/Y H:i') {
    if (is_string($date)) {
        $date = strtotime($date);
    }
    return date($format, $date);
}

/**
 * Tronque un texte
 */
function truncate($text, $length = 100) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

/**
 * Génère un slug à partir d'une chaîne
 */
function createSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

/**
 * Envoie un email
 */
function sendEmail($to, $subject, $message, $headers = []) {
    $default_headers = [
        'From: noreply@example.com',
        'Reply-To: noreply@example.com',
        'X-Mailer: PHP/' . phpversion()
    ];
    
    $all_headers = array_merge($default_headers, $headers);
    
    return mail($to, $subject, $message, implode("\r\n", $all_headers));
}

/**
 * Log une action
 */
function logAction($action, $details = []) {
    $log_file = __DIR__ . '/logs/actions.log';
    
    if (!is_dir(__DIR__ . '/logs')) {
        mkdir(__DIR__ . '/logs', 0755, true);
    }
    
    $log_entry = sprintf(
        "[%s] %s - User: %s - Details: %s\n",
        date('Y-m-d H:i:s'),
        $action,
        $_SESSION['user_id'] ?? 'guest',
        json_encode($details)
    );
    
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

/**
 * Valide une adresse email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valide un mot de passe (minimum 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre)
 */
function isValidPassword($password) {
    return strlen($password) >= 8 
        && preg_match('/[A-Z]/', $password) 
        && preg_match('/[a-z]/', $password) 
        && preg_match('/[0-9]/', $password);
}

/**
 * Hash un mot de passe
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Vérifie un mot de passe hashé
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Obtient l'IP du visiteur
 */
function getClientIp() {
    $ip_keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
    
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

/**
 * Pagination
 */
function paginate($total_items, $items_per_page, $current_page) {
    $total_pages = ceil($total_items / $items_per_page);
    $current_page = max(1, min($current_page, $total_pages));
    $offset = ($current_page - 1) * $items_per_page;
    
    return [
        'current_page' => $current_page,
        'total_pages' => $total_pages,
        'offset' => $offset,
        'limit' => $items_per_page,
        'has_prev' => $current_page > 1,
        'has_next' => $current_page < $total_pages
    ];
}
