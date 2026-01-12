<?php
/**
 * Auth Controller
 * Handles authentication (login, register, logout)
 */

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
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
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'avatar' => $user['avatar']
        ];
        
        // Set remember me cookie (30 days)
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
            
            // Save token to database
            $this->userModel->updateRememberToken($user['id'], $token);
        }
        
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
        // Clear remember token from database
        if (isset($_SESSION['user_id'])) {
            $this->userModel->updateRememberToken($_SESSION['user_id'], null);
        }
        
        // Clear session
        session_destroy();
        
        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }
        
        flash('success', 'Logout berhasil');
        redirect(base_url('login'));
    }
}
