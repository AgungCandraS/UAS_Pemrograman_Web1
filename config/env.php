<?php
/**
 * Environment Configuration
 * Copy this file to .env.php and update with your settings
 */

// Application
define('APP_NAME', 'Bisnisku');
define('APP_ENV', 'development'); // development, production
define('APP_DEBUG', true);
define('APP_URL', 'http://localhost/bisnisku-web-app');

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'bisnisku_db');
define('DB_USER', 'root');
define('DB_PASS', '');
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
