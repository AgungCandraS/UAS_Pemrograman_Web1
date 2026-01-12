<?php
/**
 * Helper Functions
 * Global utility functions
 */

/**
 * Redirect to a URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Get base URL
 */
function base_url($path = '') {
    return APP_URL . '/' . ltrim($path, '/');
}

/**
 * Get asset URL
 */
function asset($path) {
    return APP_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Sanitize input
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Get POST data
 */
function post($key = null, $default = null) {
    if ($key === null) {
        return $_POST;
    }
    return isset($_POST[$key]) ? sanitize($_POST[$key]) : $default;
}

/**
 * Get GET data
 */
function get($key = null, $default = null) {
    if ($key === null) {
        return $_GET;
    }
    return isset($_GET[$key]) ? sanitize($_GET[$key]) : $default;
}

/**
 * Flash message
 */
function flash($key, $message = null) {
    if ($message === null) {
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }
    $_SESSION['flash'][$key] = $message;
}

/**
 * Check if user is authenticated
 */
function is_authenticated() {
    return isset($_SESSION['user_id']);
}

/**
 * Get authenticated user
 */
function auth_user() {
    return $_SESSION['user'] ?? null;
}

/**
 * Require authentication
 */
function require_auth() {
    if (!is_authenticated()) {
        flash('error', 'Please login to continue');
        redirect(base_url('login'));
    }
}

/**
 * JSON response
 */
function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

/**
 * Format currency (IDR)
 */
function format_currency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Format date
 */
function format_date($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

/**
 * Format datetime
 */
function format_datetime($datetime, $format = 'd M Y H:i') {
    return date($format, strtotime($datetime));
}

/**
 * Generate CSRF token
 */
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Upload file
 */
function upload_file($file, $directory = 'uploads') {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return false;
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        return false;
    }
    
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $destination = STORAGE_PATH . '/' . $directory . '/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $filename;
    }
    
    return false;
}

/**
 * Delete file
 */
function delete_file($filename, $directory = 'uploads') {
    $path = STORAGE_PATH . '/' . $directory . '/' . $filename;
    if (file_exists($path)) {
        return unlink($path);
    }
    return false;
}

/**
 * Paginate array
 */
function paginate($items, $page = 1, $perPage = ITEMS_PER_PAGE) {
    $total = count($items);
    $totalPages = ceil($total / $perPage);
    $page = max(1, min($page, $totalPages));
    $offset = ($page - 1) * $perPage;
    
    return [
        'items' => array_slice($items, $offset, $perPage),
        'current_page' => $page,
        'total_pages' => $totalPages,
        'total_items' => $total,
        'per_page' => $perPage
    ];
}

/**
 * View helper - load view file
 */
function view($viewName, $data = []) {
    extract($data);
    $viewFile = APP_PATH . '/views/' . str_replace('.', '/', $viewName) . '.php';
    
    if (file_exists($viewFile)) {
        require_once $viewFile;
    } else {
        die("View not found: {$viewName}");
    }
}
