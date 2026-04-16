<?php
/**
 * process.php - Point d'entrée principal pour le traitement des requêtes
 * Version 5.0
 */

// Désactiver l'affichage des erreurs en production
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Démarrer la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuration de base
define('APP_VERSION', '5.0');
define('APP_NAME', 'Application V5');

// Inclusion des fichiers de configuration et utilitaires
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Routage simple
$action = $_GET['action'] ?? $_POST['action'] ?? 'home';

// Tableau des actions autorisées
$allowed_actions = [
    'home',
    'login',
    'logout',
    'register',
    'dashboard',
    'profile',
    'settings',
    'contact',
    'api'
];

// Vérification de l'action
if (!in_array($action, $allowed_actions)) {
    http_response_code(400);
    die(json_encode(['error' => 'Action non autorisée']));
}

// Traitement selon l'action
switch ($action) {
    case 'home':
        require_once __DIR__ . '/pages/home.php';
        break;
    
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            handleLogin();
        } else {
            require_once __DIR__ . '/pages/login.php';
        }
        break;
    
    case 'logout':
        handleLogout();
        break;
    
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            handleRegister();
        } else {
            require_once __DIR__ . '/pages/register.php';
        }
        break;
    
    case 'dashboard':
        requireAuth();
        require_once __DIR__ . '/pages/dashboard.php';
        break;
    
    case 'profile':
        requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            handleProfileUpdate();
        } else {
            require_once __DIR__ . '/pages/profile.php';
        }
        break;
    
    case 'settings':
        requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            handleSettingsUpdate();
        } else {
            require_once __DIR__ . '/pages/settings.php';
        }
        break;
    
    case 'contact':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            handleContact();
        } else {
            require_once __DIR__ . '/pages/contact.php';
        }
        break;
    
    case 'api':
        handleApiRequest();
        break;
    
    default:
        http_response_code(404);
        die(json_encode(['error' => 'Page non trouvée']));
}

/**
 * Fonctions de gestion
 */

function handleLogin() {
    global $db;
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        redirect('?action=login&error=missing_fields');
    }
    
    // Simulation de vérification (à adapter avec votre base de données)
    $user = findUserByEmail($email);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        redirect('?action=dashboard');
    } else {
        redirect('?action=login&error=invalid_credentials');
    }
}

function handleLogout() {
    session_destroy();
    redirect('?action=home');
}

function handleRegister() {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($name) || empty($email) || empty($password)) {
        redirect('?action=register&error=missing_fields');
    }
    
    if ($password !== $confirm_password) {
        redirect('?action=register&error=passwords_mismatch');
    }
    
    // Vérifier si l'email existe déjà
    if (findUserByEmail($email)) {
        redirect('?action=register&error=email_exists');
    }
    
    // Créer l'utilisateur
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $user_id = createUser($name, $email, $hashed_password);
    
    if ($user_id) {
        redirect('?action=login&registered=1');
    } else {
        redirect('?action=register&error=creation_failed');
    }
}

function handleProfileUpdate() {
    global $db;
    $user_id = $_SESSION['user_id'] ?? null;
    
    if (!$user_id) {
        redirect('?action=login');
    }
    
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    
    if (updateUser($user_id, $name, $email)) {
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        redirect('?action=profile&success=1');
    } else {
        redirect('?action=profile&error=update_failed');
    }
}

function handleSettingsUpdate() {
    $user_id = $_SESSION['user_id'] ?? null;
    
    if (!$user_id) {
        redirect('?action=login');
    }
    
    $theme = $_POST['theme'] ?? 'light';
    $language = $_POST['language'] ?? 'fr';
    $notifications = isset($_POST['notifications']) ? 1 : 0;
    
    if (updateUserSettings($user_id, $theme, $language, $notifications)) {
        redirect('?action=settings&success=1');
    } else {
        redirect('?action=settings&error=update_failed');
    }
}

function handleContact() {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    
    if (empty($name) || empty($email) || empty($message)) {
        redirect('?action=contact&error=missing_fields');
    }
    
    // Envoyer l'email (à implémenter)
    if (sendContactEmail($name, $email, $message)) {
        redirect('?action=contact&success=1');
    } else {
        redirect('?action=contact&error=send_failed');
    }
}

function handleApiRequest() {
    header('Content-Type: application/json');
    
    $endpoint = $_GET['endpoint'] ?? '';
    
    switch ($endpoint) {
        case 'users':
            echo json_encode(getAllUsers());
            break;
        case 'stats':
            echo json_encode(getStats());
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint non trouvé']);
    }
}

function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        redirect('?action=login');
    }
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// Fonctions stub à implémenter avec votre base de données
function findUserByEmail($email) {
    // Retourner les données utilisateur depuis la BDD
    return null;
}

function createUser($name, $email, $password) {
    // Créer un utilisateur dans la BDD
    return false;
}

function updateUser($id, $name, $email) {
    // Mettre à jour l'utilisateur dans la BDD
    return false;
}

function updateUserSettings($id, $theme, $language, $notifications) {
    // Mettre à jour les paramètres utilisateur
    return false;
}

function sendContactEmail($name, $email, $message) {
    // Envoyer un email de contact
    return true;
}

function getAllUsers() {
    // Retourner tous les utilisateurs
    return [];
}

function getStats() {
    // Retourner les statistiques
    return ['users' => 0, 'visits' => 0];
}
