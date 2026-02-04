<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Bisnisku</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card scale-in" style="max-width: 540px;">
            <!-- Logo -->
            <div class="auth-logo">
                <div class="logo-icon mb-3">
                    <i class="fas fa-store"></i>
                </div>
                <h1 class="mb-2">Bisnisku</h1>
                <p style="color: var(--text-secondary);">Daftar dan mulai kelola bisnis Anda</p>
            </div>
            
            <!-- Flash Messages -->
            <?php if ($msg = flash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle me-2"></i><?= $msg ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form action="<?= base_url('register') ?>" method="POST">
                <!-- Full Name -->
                <div class="mb-3">
                    <label for="full_name" class="form-label-custom">
                        <i class="fas fa-user text-primary me-2"></i>Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        id="full_name" 
                        name="full_name" 
                        class="form-control-custom"
                        placeholder="Masukkan nama lengkap"
                        required
                        autofocus
                    >
                </div>
                
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
                    >
                </div>
                
                <!-- Phone -->
                <div class="mb-3">
                    <label for="phone" class="form-label-custom">
                        <i class="fas fa-phone text-primary me-2"></i>Nomor Telepon
                    </label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone"
                        class="form-control-custom"
                        placeholder="08123456789"
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
                            placeholder="Minimal 6 karakter"
                            required
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password', 'icon1')"
                            class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-decoration-none"
                            style="margin-right: 10px;"
                        >
                            <i id="icon1" class="fas fa-eye text-secondary"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="confirm_password" class="form-label-custom">
                        <i class="fas fa-lock text-primary me-2"></i>Konfirmasi Password
                    </label>
                    <div class="position-relative">
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            class="form-control-custom"
                            placeholder="Ulangi password"
                            required
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('confirm_password', 'icon2')"
                            class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-decoration-none"
                            style="margin-right: 10px;"
                        >
                            <i id="icon2" class="fas fa-eye text-secondary"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="btn-custom btn-primary-custom w-100 mb-4"
                >
                    <i class="fas fa-user-plus me-2"></i>Buat Akun Sekarang
                </button>
            </form>
                
            <!-- Login Link -->
            <div class="text-center mt-4">
                <p class="mb-0" style="color: var(--text-secondary);">
                    Sudah punya akun? 
                    <a href="<?= base_url('login') ?>" class="text-primary fw-semibold text-decoration-none">
                        Login di sini <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </p>
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
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const passwordIcon = document.getElementById(iconId);
        
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
$title = 'Daftar';
include APP_PATH . '/views/layouts/base.php';
?>

<?php
$content = ob_get_clean();
$title = 'Register';
include APP_PATH . '/views/layouts/base.php';
?>
