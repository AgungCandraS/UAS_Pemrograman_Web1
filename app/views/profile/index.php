<div class="profile-container">
    <!-- Header Section -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar-section">
                <div class="profile-avatar-wrapper">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?= base_url('storage/uploads/' . $user['avatar']) ?>" alt="Avatar" class="profile-avatar" id="avatarPreview">
                    <?php else: ?>
                        <div class="profile-avatar-placeholder" id="avatarPreview">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                    <label for="avatarInput" class="avatar-upload-btn" title="Upload Avatar">
                        <i class="fas fa-camera"></i>
                    </label>
                </div>
            </div>
            <div class="profile-info">
                <h1><?= htmlspecialchars($user['full_name']) ?></h1>
                <p class="profile-role">
                    <i class="fas fa-shield-alt"></i> 
                    <?= ucfirst($user['role']) ?>
                </p>
                <p class="profile-email">
                    <i class="fas fa-envelope"></i> 
                    <?= htmlspecialchars($user['email']) ?>
                </p>
                <?php if (!empty($user['phone'])): ?>
                <p class="profile-phone">
                    <i class="fas fa-phone"></i> 
                    <?= htmlspecialchars($user['phone']) ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
        <div class="profile-stats">
            <div class="stat-card">
                <div class="stat-icon stat-icon-primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3>Bergabung</h3>
                    <p><?= date('d M Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-success">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>Login Terakhir</h3>
                    <p><?= !empty($user['last_login']) ? date('d M Y H:i', strtotime($user['last_login'])) : 'Belum ada' ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="profile-content">
        <div class="profile-row">
            <!-- Update Profile Section -->
            <div class="profile-card">
                <div class="card-header">
                    <h2><i class="fas fa-user-edit"></i> Update Profil</h2>
                    <p>Perbarui informasi profil Anda</p>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('profile/update') ?>" method="POST" enctype="multipart/form-data" id="profileForm">
                        <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;">
                        
                        <div class="form-group">
                            <label for="full_name">
                                <i class="fas fa-user"></i> Nama Lengkap
                            </label>
                            <input type="text" id="full_name" name="full_name" class="form-control" 
                                   value="<?= htmlspecialchars($user['full_name']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="email" class="form-control" 
                                   value="<?= htmlspecialchars($user['email']) ?>" disabled>
                            <small class="form-text">Email tidak dapat diubah</small>
                        </div>

                        <div class="form-group">
                            <label for="phone">
                                <i class="fas fa-phone"></i> No. Telepon
                            </label>
                            <input type="text" id="phone" name="phone" class="form-control" 
                                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                                   placeholder="Contoh: 08123456789">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="profile-card">
                <div class="card-header">
                    <h2><i class="fas fa-lock"></i> Ganti Password</h2>
                    <p>Ubah password untuk keamanan akun</p>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('profile/change-password') ?>" method="POST" id="passwordForm">
                        <div class="form-group">
                            <label for="old_password">
                                <i class="fas fa-key"></i> Password Lama
                            </label>
                            <div class="password-input-wrapper">
                                <input type="password" id="old_password" name="old_password" 
                                       class="form-control" required>
                                <button type="button" class="password-toggle" onclick="togglePassword('old_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new_password">
                                <i class="fas fa-lock"></i> Password Baru
                            </label>
                            <div class="password-input-wrapper">
                                <input type="password" id="new_password" name="new_password" 
                                       class="form-control" required minlength="6">
                                <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-text">Minimal 6 karakter</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">
                                <i class="fas fa-check-circle"></i> Konfirmasi Password Baru
                            </label>
                            <div class="password-input-wrapper">
                                <input type="password" id="confirm_password" name="confirm_password" 
                                       class="form-control" required minlength="6">
                                <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="password-strength" id="passwordStrength"></div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-shield-alt"></i> Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Information Section -->
        <div class="profile-card">
            <div class="card-header">
                <h2><i class="fas fa-info-circle"></i> Informasi Akun</h2>
                <p>Detail informasi akun Anda</p>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-id-badge"></i> User ID
                        </div>
                        <div class="info-value"><?= $user['id'] ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-user-tag"></i> Role
                        </div>
                        <div class="info-value">
                            <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-plus"></i> Tanggal Bergabung
                        </div>
                        <div class="info-value"><?= date('d F Y, H:i', strtotime($user['created_at'])) ?> WIB</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-sync-alt"></i> Terakhir Update
                        </div>
                        <div class="info-value"><?= date('d F Y, H:i', strtotime($user['updated_at'])) ?> WIB</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-container {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 40px;
    color: white;
    margin-bottom: 30px;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
}

.profile-header-content {
    display: flex;
    align-items: center;
    gap: 30px;
    margin-bottom: 30px;
}

.profile-avatar-section {
    position: relative;
}

.profile-avatar-wrapper {
    position: relative;
    width: 140px;
    height: 140px;
}

.profile-avatar,
.profile-avatar-placeholder {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    color: white;
}

.avatar-upload-btn {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #4CAF50;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.avatar-upload-btn:hover {
    background: #45a049;
    transform: scale(1.1);
}

.avatar-upload-btn i {
    color: white;
    font-size: 16px;
}

.profile-info h1 {
    margin: 0 0 10px 0;
    font-size: 32px;
    font-weight: 700;
}

.profile-info p {
    margin: 5px 0;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    opacity: 0.95;
}

.profile-info i {
    width: 20px;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.stat-card {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-icon-primary {
    background: rgba(59, 130, 246, 0.3);
    color: #60a5fa;
}

.stat-icon-success {
    background: rgba(34, 197, 94, 0.3);
    color: #4ade80;
}

.stat-info h3 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    opacity: 0.9;
}

.stat-info p {
    margin: 5px 0 0 0;
    font-size: 18px;
    font-weight: 700;
}

.profile-content {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.profile-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
    gap: 30px;
}

.profile-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.profile-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

.card-header {
    padding: 25px 30px;
    background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);
    border-bottom: 2px solid #f0f0f0;
}

.card-header h2 {
    margin: 0 0 5px 0;
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header h2 i {
    color: #667eea;
}

.card-header p {
    margin: 0;
    font-size: 14px;
    color: #64748b;
}

.card-body {
    padding: 30px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #334155;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-group label i {
    color: #667eea;
    width: 16px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control:disabled {
    background-color: #f8fafc;
    color: #94a3b8;
    cursor: not-allowed;
}

.form-text {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #64748b;
}

.password-input-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 8px;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: #667eea;
}

.password-strength {
    margin-top: 15px;
    height: 6px;
    background: #e2e8f0;
    border-radius: 3px;
    overflow: hidden;
    display: none;
}

.password-strength.active {
    display: block;
}

.password-strength-bar {
    height: 100%;
    transition: all 0.3s ease;
    border-radius: 3px;
}

.strength-weak { 
    width: 33%; 
    background: #ef4444; 
}

.strength-medium { 
    width: 66%; 
    background: #f59e0b; 
}

.strength-strong { 
    width: 100%; 
    background: #10b981; 
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    padding: 20px;
    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
}

.info-item:hover {
    border-color: #667eea;
    transform: translateY(-2px);
}

.info-label {
    font-size: 13px;
    color: #64748b;
    font-weight: 600;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-label i {
    color: #667eea;
}

.info-value {
    font-size: 15px;
    color: #1e293b;
    font-weight: 600;
}

.badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
}

.badge-primary {
    background: #dbeafe;
    color: #1e40af;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-container {
        padding: 15px;
    }
    
    .profile-header {
        padding: 25px;
    }
    
    .profile-header-content {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .profile-info h1 {
        font-size: 24px;
    }
    
    .profile-info p {
        justify-content: center;
    }
    
    .profile-row {
        grid-template-columns: 1fr;
    }
    
    .profile-stats {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .card-body {
        padding: 20px;
    }
}

/* Loading Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.profile-card {
    animation: fadeIn 0.5s ease-out;
}

.profile-card:nth-child(1) {
    animation-delay: 0.1s;
}

.profile-card:nth-child(2) {
    animation-delay: 0.2s;
}
</style>

<script>
// Avatar Preview
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'profile-avatar';
                img.id = 'avatarPreview';
                preview.parentNode.replaceChild(img, preview);
            }
        };
        reader.readAsDataURL(file);
        
        // Auto submit form
        document.getElementById('profileForm').submit();
    }
});

// Password Toggle
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password Strength Indicator
document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('passwordStrength');
    
    if (password.length === 0) {
        strengthDiv.classList.remove('active');
        return;
    }
    
    strengthDiv.classList.add('active');
    
    let strength = 0;
    if (password.length >= 6) strength++;
    if (password.length >= 10) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    let className = 'strength-weak';
    let width = '33%';
    let label = 'Lemah';
    
    if (strength >= 3) {
        className = 'strength-medium';
        width = '66%';
        label = 'Sedang';
    }
    if (strength >= 4) {
        className = 'strength-strong';
        width = '100%';
        label = 'Kuat';
    }
    
    strengthDiv.innerHTML = `
        <div class="password-strength-bar ${className}"></div>
    `;
});

// Form Validation
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('Password baru dan konfirmasi password tidak cocok!');
        return false;
    }
    
    if (newPassword.length < 6) {
        e.preventDefault();
        alert('Password baru minimal 6 karakter!');
        return false;
    }
});

// Auto-hide flash messages
setTimeout(function() {
    const flashMessages = document.querySelectorAll('.alert');
    flashMessages.forEach(function(msg) {
        msg.style.transition = 'opacity 0.5s ease';
        msg.style.opacity = '0';
        setTimeout(() => msg.remove(), 500);
    });
}, 5000);
</script>