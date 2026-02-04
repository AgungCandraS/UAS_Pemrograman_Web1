<?php
/**
 * Environment Configuration (Smart Switch)
 * 
 * DEV  : localhost (XAMPP)
 * PROD : Rumahweb (acas.my.id)
 * 
 * CARA PAKAI:
 * 1. Saat upload ke hosting: set $isProd = true;
 * 2. Saat lokal development: set $isProd = false;
 */

// ==================== BASE ====================
define('APP_NAME', 'Bisnisku');

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// ==================== ENV SWITCH ====================
// ⚠️ UBAH INI SAAT UPLOAD KE HOSTING
$isProd = true; // false = localhost, true = hosting Rumahweb

if ($isProd) {
    // ==================== PRODUCTION SETTINGS ====================
    define('APP_ENV', 'production');
    define('APP_DEBUG', false);
    define('APP_URL', 'https://acas.my.id');

    // Database Production (Rumahweb)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'acax9288_bisnisku');
    define('DB_USER', 'acax9288_users');
    define('DB_PASS', 'Acas080106#'); // ⚠️ GANTI dengan password database dari cPanel
    
} else {
    // ==================== DEVELOPMENT SETTINGS ====================
    define('APP_ENV', 'development');
    define('APP_DEBUG', true);
    define('APP_URL', 'http://localhost/bisnisku-web-app');

    // Database Local (XAMPP)
    define('DB_HOST', '127.0.0.1');
    define('DB_NAME', 'bisnisku_db');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}

// ==================== COMMON SETTINGS ====================
define('DB_CHARSET', 'utf8mb4');

// Session
define('SESSION_LIFETIME', 7200); // 2 hours

// Upload settings
define('MAX_UPLOAD_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx']);

// Pagination
define('ITEMS_PER_PAGE', 10);

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Security
define('HASH_ALGO', PASSWORD_BCRYPT);
define('HASH_COST', 12);
