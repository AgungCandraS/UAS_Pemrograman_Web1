<?php
ob_start();
?>

<div class="min-vh-100 gradient-bg-3 d-flex align-items-center justify-content-center p-4">
    <div class="w-100 fade-in" style="max-width: 500px;">
        <!-- Logo/Brand -->
        <div class="text-center mb-4">
            <h1 class="display-4 fw-bold text-white mb-2">
                <i class="fas fa-store"></i> Bisnisku
            </h1>
            <p class="text-white opacity-90">Daftar dan mulai kelola bisnis Anda</p>
        </div>
        
        <!-- Register Card -->
        <div class="auth-card">
            <h2 class="fs-3 fw-bold text-gray-800 mb-4 text-center">Buat Akun Baru</h2>
            
            <form action="<?= base_url('register') ?>" method="POST">
                <!-- Full Name -->
                <div class="mb-3">
                    <label for="full_name" class="form-label fw-medium">
                        <i class="fas fa-user text-primary me-2"></i>Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        id="full_name" 
                        name="full_name" 
                        required
                        placeholder="John Doe"
                        class="form-control form-control-lg"
                    >
                </div>
                
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-medium">
                        <i class="fas fa-envelope text-primary me-2"></i>Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        placeholder="nama@email.com"
                        class="form-control form-control-lg"
                    >
                </div>
                
                <!-- Phone -->
                <div class="mb-3">
                    <label for="phone" class="form-label fw-medium">
                        <i class="fas fa-phone text-primary me-2"></i>Nomor Telepon
                    </label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone"
                        placeholder="08123456789"
                        class="form-control form-control-lg"
                    >
                </div>
                
                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-medium">
                        <i class="fas fa-lock text-primary me-2"></i>Password
                    </label>
                    <div class="position-relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            placeholder="Minimal 6 karakter"
                            class="form-control form-control-lg pe-5"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password', 'icon1')"
                            class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted"
                            style="z-index: 10;"
                        >
                            <i id="icon1" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="confirm_password" class="form-label fw-medium">
                        <i class="fas fa-lock text-primary me-2"></i>Konfirmasi Password
                    </label>
                    <div class="position-relative">
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            required
                            placeholder="Ulangi password"
                            class="form-control form-control-lg pe-5"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('confirm_password', 'icon2')"
                            class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted"
                            style="z-index: 10;"
                        >
                            <i id="icon2" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="btn-custom btn-primary-custom w-100 py-3 fs-5"
                >
                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                </button>
                
                <!-- Login Link -->
                <p class="text-center text-muted mt-4 mb-0">
                    Sudah punya akun? 
                    <a href="<?= base_url('login') ?>" class="text-primary fw-semibold text-decoration-none">
                        Login di sini
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

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
$title = 'Register';
include APP_PATH . '/views/layouts/base.php';
?>
