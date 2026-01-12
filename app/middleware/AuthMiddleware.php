<?php
/**
 * Auth Middleware
 * Handles authentication checks
 */

class AuthMiddleware {
    /**
     * Check if user is authenticated
     */
    public static function check() {
        if (!is_authenticated()) {
            flash('error', 'Silakan login terlebih dahulu');
            redirect(base_url('login'));
        }
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
