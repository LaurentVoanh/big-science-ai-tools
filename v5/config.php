<?php
/**
 * config.php - Fichier de configuration
 */

// Paramètres de la base de données
$db_host = 'localhost';
$db_name = 'v5_app';
$db_user = 'root';
$db_pass = '';

// Connexion à la base de données (PDO)
try {
    $db = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    // En production, logger l'erreur au lieu de l'afficher
    error_log($e->getMessage());
    $db = null;
}

// Paramètres de l'application
$config = [
    'app_name' => APP_NAME ?? 'Application V5',
    'app_version' => APP_VERSION ?? '5.0',
    'site_url' => 'http://localhost/v5/',
    'admin_email' => 'admin@example.com',
    'timezone' => 'Europe/Paris',
    'locale' => 'fr_FR'
];

// Configuration des sessions
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.gc_maxlifetime', 3600);

// Fuseau horaire
date_default_timezone_set($config['timezone']);
