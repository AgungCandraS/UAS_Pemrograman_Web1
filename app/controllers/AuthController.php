<?php
/**
 * Auth Controller
 * Handles authentication (login, register, logout)
 */

class AuthController {
    private $userModel;
    private $sessionModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->sessionModel = new Session();
    }
    
    /**
     * Show login page
     */
    public function showLogin() {
        AuthMiddleware::guest();
        view('auth.login');
    }
    
    /**
     * Handle login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('login'));
        }
        
        $email = post('email');
        $password = post('password');
        $remember = post('remember');
        
        // Validation
        if (empty($email) || empty($password)) {
            flash('error', 'Email dan password harus diisi');
            redirect(base_url('login'));
        }
        
        // Find user
        $user = $this->userModel->findByEmail($email);
        
        if (!$user) {
            flash('error', 'Email atau password salah');
            redirect(base_url('login'));
        }
        
        // Verify password
        if (!$this->userModel->verifyPassword($password, $user['password'])) {
            flash('error', 'Email atau password salah');
            redirect(base_url('login'));
        }
        
        // Check status
        if ($user['status'] !== 'active') {
            flash('error', 'Akun Anda tidak aktif. Hubungi administrator');
            redirect(base_url('login'));
        }
        
        // Configure session cookie settings for security
        $sessionLifetime = $remember ? (30 * 24 * 60 * 60) : 0; // 30 days or browser session
        
        session_set_cookie_params([
            'lifetime' => $sessionLifetime,
            'path' => '/',
            'domain' => '',
            'secure' => false, // Set to true when using HTTPS
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        
        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);
        
        // Set session data
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
        
        // Set remember me cookie if requested
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $hashedToken = hash('sha256', $token);
            $expiresAt = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));
            
            // Set cookie (30 days)
            setcookie(
                'remember_token', 
                $token, 
                time() + (30 * 24 * 60 * 60), 
                '/', 
                '', 
                false, // secure: set to true for HTTPS
                true    // httponly
            );
            
            // Save hashed token and expiration to database
            $this->userModel->updateRememberToken($user['id'], $hashedToken, $expiresAt);
        }
        
        // Log successful login
        $this->sessionModel->logLogin($user['id'], 'success');
        
        flash('success', 'Login berhasil! Selamat datang, ' . $user['full_name']);
        redirect(base_url('dashboard'));
    }
    
    /**
     * Show register page
     */
    public function showRegister() {
        AuthMiddleware::guest();
        view('auth.register');
    }
    
    /**
     * Handle registration
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('register'));
        }
        
        $fullName = post('full_name');
        $email = post('email');
        $password = post('password');
        $confirmPassword = post('confirm_password');
        $phone = post('phone');
        
        // Validation
        if (empty($fullName) || empty($email) || empty($password)) {
            flash('error', 'Semua field harus diisi');
            redirect(base_url('register'));
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Format email tidak valid');
            redirect(base_url('register'));
        }
        
        if (strlen($password) < 6) {
            flash('error', 'Password minimal 6 karakter');
            redirect(base_url('register'));
        }
        
        if ($password !== $confirmPassword) {
            flash('error', 'Password dan konfirmasi password tidak cocok');
            redirect(base_url('register'));
        }
        
        // Check if email exists
        if ($this->userModel->findByEmail($email)) {
            flash('error', 'Email sudah terdaftar');
            redirect(base_url('register'));
        }
        
        // Create user
        $data = [
            'full_name' => $fullName,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'role' => 'employee',
            'status' => 'active'
        ];
        
        $userId = $this->userModel->create($data);
        
        if ($userId) {
            flash('success', 'Registrasi berhasil! Silakan login');
            redirect(base_url('login'));
        } else {
            flash('error', 'Terjadi kesalahan. Silakan coba lagi');
            redirect(base_url('register'));
        }
    }
    
    /**
     * Handle logout
     */
    public function logout() {
        // Log logout time
        if (isset($_SESSION['user_id'])) {
            $this->sessionModel->logLogout($_SESSION['user_id']);
            
            // Clear remember token from database
            $this->userModel->updateRememberToken($_SESSION['user_id'], null, null);
        }
        
        // Clear all session data
        $_SESSION = [];
        
        // Delete session cookie
        if (isset($_COOKIE[session_name()])) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        // Destroy session
        session_destroy();
        
        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
            unset($_COOKIE['remember_token']);
        }
        
        flash('success', 'Logout berhasil');
        redirect(base_url('login'));
    }
}
