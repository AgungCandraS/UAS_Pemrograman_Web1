<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bisnisku</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card scale-in">
            <!-- Logo -->
            <div class="auth-logo">
                <h1><i class="fas fa-store"></i> Bisnisku</h1>
                <p>Kelola bisnis Anda dengan mudah dan profesional</p>
            </div>
            
            <!-- Flash Messages -->
            <?php if ($msg = flash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle me-2"></i><?= $msg ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($msg = flash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= $msg ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form action="<?= base_url('login') ?>" method="POST">
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label-custom">
                        <i class="fas fa-envelope text-primary me-2"></i>Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control-custom" 
                        placeholder="nama@email.com"
                        required
                        autofocus
                    >
                </div>
                
                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label-custom">
                        <i class="fas fa-lock text-primary me-2"></i>Password
                    </label>
                    <div class="position-relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control-custom" 
                            placeholder="••••••••"
                            required
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password', 'password-icon')"
                            class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-decoration-none"
                            style="margin-right: 10px;"
                        >
                            <i id="password-icon" class="fas fa-eye text-secondary"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember">
                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>
                    <a href="#" class="text-primary text-decoration-none">Lupa password?</a>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-custom btn-primary-custom w-100 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
                
                <!-- Register Link -->
                <p class="text-center mb-0">
                    Belum punya akun? 
                    <a href="<?= base_url('register') ?>" class="text-primary text-decoration-none fw-semibold">
                        Daftar sekarang
                    </a>
                </p>
            </form>
            
            <!-- Demo Credentials -->
            <div class="mt-4 p-3 bg-light rounded">
                <p class="mb-2 fw-semibold"><i class="fas fa-info-circle text-primary me-2"></i>Demo Login:</p>
                <p class="mb-1 small">Email: <strong>admin@bisnisku.com</strong></p>
                <p class="mb-0 small">Password: <strong>admin123</strong></p>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= asset('js/script.js') ?>"></script>
</body>
</html>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('password-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    }
</script>

<?php
$content = ob_get_clean();
$title = 'Login';
include APP_PATH . '/views/layouts/base.php';
?>
