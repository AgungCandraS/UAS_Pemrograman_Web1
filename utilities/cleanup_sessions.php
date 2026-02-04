<?php
/**
 * Session Cleanup Cron Job
 * Clean up expired sessions from database
 * 
 * Run this script periodically (e.g., every hour) via cron:
 * 0 * * * * php /path/to/cleanup_sessions.php
 */

// Define paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CORE_PATH', ROOT_PATH . '/core');

// Load environment and database
require_once CONFIG_PATH . '/env.php';
require_once CORE_PATH . '/Database.php';

// Autoload models
spl_autoload_register(function ($class) {
    $modelPath = APP_PATH . '/models/' . $class . '.php';
    if (file_exists($modelPath)) {
        require_once $modelPath;
    }
});

try {
    $sessionModel = new Session();
    
    // Clean expired sessions (older than 24 hours)
    $deleted = $sessionModel->cleanExpiredSessions(24);
    
    echo "[" . date('Y-m-d H:i:s') . "] Cleaned {$deleted} expired sessions\n";
    
    // Also clean expired remember tokens
    $db = Database::getInstance();
    $sql = "UPDATE users 
            SET remember_token = NULL, remember_token_expires = NULL 
            WHERE remember_token_expires IS NOT NULL 
            AND remember_token_expires < NOW()";
    $db->execute($sql);
    
    $expiredTokens = $db->affectedRows();
    echo "[" . date('Y-m-d H:i:s') . "] Cleaned {$expiredTokens} expired remember tokens\n";
    
} catch (Exception $e) {
    echo "[" . date('Y-m-d H:i:s') . "] Error: " . $e->getMessage() . "\n";
}
