<?php
/**
 * Auth Middleware
 * Handles authentication checks and session management
 */

class AuthMiddleware {
    /**
     * Session timeout in seconds (2 hours)
     */
    const SESSION_TIMEOUT = 7200;
    
    /**
     * Check if user is authenticated and session is valid
     */
    public static function check() {
        if (!is_authenticated()) {
            flash('error', 'Silakan login terlebih dahulu');
            redirect(base_url('login'));
        }
        
        // Check session timeout
        if (isset($_SESSION['last_activity'])) {
            $inactiveTime = time() - $_SESSION['last_activity'];
            
            if ($inactiveTime > self::SESSION_TIMEOUT) {
                // Session expired
                session_destroy();
                
                if (isset($_COOKIE['remember_token'])) {
                    setcookie('remember_token', '', time() - 3600, '/', '', false, true);
                }
                
                flash('error', 'Sesi Anda telah berakhir. Silakan login kembali');
                redirect(base_url('login'));
            }
        }
        
        // Update last activity time
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Check if user is guest (not authenticated)
     */
    public static function guest() {
        if (is_authenticated()) {
            redirect(base_url('dashboard'));
        }
    }
}
