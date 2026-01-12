<?php
/**
 * Bisnisku Web Application
 * Entry Point - Front Controller
 */

// Start session
session_start();

// Define constants
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CORE_PATH', ROOT_PATH . '/core');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('ASSETS_PATH', '/assets');

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

// Check remember me cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $userModel = new User();
    $user = $userModel->findByRememberToken($_COOKIE['remember_token']);
    
    if ($user) {
        // Auto login from cookie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'avatar' => $user['avatar']
        ];
    }
}

// Initialize router
$router = new Router();

// Include routes
require_once ROOT_PATH . '/routes.php';

// Run the router
$router->run();
