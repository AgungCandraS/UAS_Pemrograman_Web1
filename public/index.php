<?php
/**
 * Bisnisku Web Application
 * Entry Point - Front Controller
 */

// Auto-detect BASE_PATH based on script location
if (!defined('BASE_PATH')) {
    // Get the directory where this file is located relative to document root
    $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
    define('BASE_PATH', $scriptPath);
}

// Define paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CORE_PATH', ROOT_PATH . '/core');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');

// URL assets harus ikut BASE_PATH, dan assets HARUS ada di folder public/assets
define('ASSETS_PATH', BASE_PATH . '/assets');


// Load environment variables
require_once CONFIG_PATH . '/env.php';

// Autoload core classes
spl_autoload_register(function ($class) {
    $paths = [
        CORE_PATH . '/' . $class . '.php',
        APP_PATH . '/controllers/' . $class . '.php',
        APP_PATH . '/models/' . $class . '.php',
        APP_PATH . '/middleware/' . $class . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Load helpers
require_once CORE_PATH . '/helpers.php';

// Configure session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', 7200); // 2 hours
ini_set('session.cookie_lifetime', 0); // Browser session by default

// Set up database session handler
$sessionHandler = new DatabaseSessionHandler();
session_set_save_handler($sessionHandler, true);

// Start session
session_start();

// Check remember me cookie and auto-login if valid
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $userModel = new User();
    $token = $_COOKIE['remember_token'];
    $hashedToken = hash('sha256', $token);
    
    // Find user by hashed token (includes expiration check)
    $user = $userModel->findByRememberToken($hashedToken);
    
    if ($user && $user['status'] === 'active') {
        // Auto login from cookie
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'avatar' => $user['avatar']
        ];
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();
        $_SESSION['auto_login'] = true;
        
        // Log the auto-login
        $sessionModel = new Session();
        $sessionModel->logLogin($user['id'], 'success');
    } else {
        // Invalid or expired token, clear cookie
        setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        unset($_COOKIE['remember_token']);
    }
}

// Initialize router
$router = new Router();

// Include routes
require_once ROOT_PATH . '/routes.php';

// Run the router
$router->run();
