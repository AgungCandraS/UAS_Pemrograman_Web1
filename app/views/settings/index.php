<div class="settings-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-wrapper">
            <div class="header-icon-box">
                <i class="fas fa-cog fa-spin-slow"></i>
            </div>
            <div class="header-info">
                <h1>Pengaturan Aplikasi</h1>
                <p>Kelola informasi bisnis dan konfigurasi sistem</p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert-box success">
        <i class="fas fa-check-circle"></i>
        <span><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert-box error">
        <i class="fas fa-exclamation-circle"></i>
        <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

    <!-- Settings Form -->
            <form method="POST" action="<?= base_url('settings/update') ?>" class="settings-form">
                
                <!-- Company Information Card -->
        <div class="settings-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-building"></i>
                    <span>Informasi Perusahaan</span>
                </div>
                <p class="card-subtitle">Data identitas bisnis Anda</p>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="company_name">
                            <i class="fas fa-store"></i>
                            Nama Perusahaan
                        </label>
                        <input 
                            type="text" 
                            id="company_name" 
                            name="company_name" 
                            class="form-input" 
                            value="<?= htmlspecialchars($settingsArray['company_name'] ?? 'Bisnisku') ?>"
                            placeholder="Nama bisnis Anda"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="company_email">
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="company_email" 
                            name="company_email" 
                            class="form-input" 
                            value="<?= htmlspecialchars($settingsArray['company_email'] ?? '') ?>"
                            placeholder="email@perusahaan.com"
                            required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="company_phone">
                            <i class="fas fa-phone"></i>
                            Telepon
                        </label>
                        <input 
                            type="tel" 
                            id="company_phone" 
                            name="company_phone" 
                            class="form-input" 
                            value="<?= htmlspecialchars($settingsArray['company_phone'] ?? '') ?>"
                            placeholder="08123456789"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="currency">
                            <i class="fas fa-money-bill"></i>
                            Mata Uang
                        </label>
                        <select id="currency" name="currency" class="form-input">
                            <option value="IDR" <?= ($settingsArray['currency'] ?? 'IDR') == 'IDR' ? 'selected' : '' ?>>IDR - Rupiah</option>
                            <option value="USD" <?= ($settingsArray['currency'] ?? '') == 'USD' ? 'selected' : '' ?>>USD - Dollar</option>
                            <option value="EUR" <?= ($settingsArray['currency'] ?? '') == 'EUR' ? 'selected' : '' ?>>EUR - Euro</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="company_address">
                        <i class="fas fa-map-marker-alt"></i>
                        Alamat
                    </label>
                    <textarea 
                        id="company_address" 
                        name="company_address" 
                        class="form-input" 
                        rows="3"
                        placeholder="Alamat lengkap perusahaan"
                        required><?= htmlspecialchars($settingsArray['company_address'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- System Settings Card -->
        <div class="settings-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-sliders-h"></i>
                    <span>Pengaturan Sistem</span>
                </div>
                <p class="card-subtitle">Preferensi aplikasi dan keamanan</p>
            </div>
            <div class="card-body">
                <div class="toggle-list">
                    <div class="toggle-item">
                        <div class="toggle-content">
                            <div class="toggle-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="toggle-info">
                                <h4>Notifikasi Email</h4>
                                <p>Kirim notifikasi penting via email</p>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="hidden" name="email_notification" value="0">
                            <input 
                                type="checkbox" 
                                name="email_notification" 
                                value="1"
                                <?= ($settingsArray['email_notification'] ?? '1') == '1' ? 'checked' : '' ?>>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="toggle-item">
                        <div class="toggle-content">
                            <div class="toggle-icon">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div class="toggle-info">
                                <h4>Alert Stok Rendah</h4>
                                <p>Notifikasi saat stok produk hampir habis</p>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="hidden" name="low_stock_alert" value="0">
                            <input 
                                type="checkbox" 
                                name="low_stock_alert" 
                                value="1"
                                <?= ($settingsArray['low_stock_alert'] ?? '1') == '1' ? 'checked' : '' ?>>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="toggle-item">
                        <div class="toggle-content">
                            <div class="toggle-icon">
                                <i class="fas fa-print"></i>
                            </div>
                            <div class="toggle-info">
                                <h4>Auto Print Invoice</h4>
                                <p>Cetak invoice otomatis setelah transaksi</p>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="hidden" name="auto_print_invoice" value="0">
                            <input 
                                type="checkbox" 
                                name="auto_print_invoice" 
                                value="1"
                                <?= ($settingsArray['auto_print_invoice'] ?? '0') == '1' ? 'checked' : '' ?>>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="toggle-item">
                        <div class="toggle-content">
                            <div class="toggle-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="toggle-info">
                                <h4>Mode Maintenance</h4>
                                <p>Tutup akses sementara untuk maintenance</p>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="hidden" name="maintenance_mode" value="0">
                            <input 
                                type="checkbox" 
                                name="maintenance_mode" 
                                value="1"
                                <?= ($settingsArray['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' ?>>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.location.reload()">
                <i class="fas fa-undo"></i>
                <span>Reset</span>
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                <span>Simpan Pengaturan</span>
            </button>
        </div>
    </form>
    
    <!-- Info Card -->
    <div class="info-card">
        <div class="info-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div class="info-content">
            <h4>Informasi Penting</h4>
            <p>Perubahan pengaturan akan langsung diterapkan setelah disimpan. Pastikan data yang diinput sudah benar sebelum menyimpan.</p>
        </div>
    </div>

<!-- Modals -->

</div>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.settings-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px 20px;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
}

.header-wrapper {
    display: flex;
    align-items: center;
    gap: 20px;
}

.header-icon-box {
    width: 65px;
    height: 65px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
}

.fa-spin-slow {
    animation: spin 3s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.header-info h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: white;
}

.header-info p {
    margin: 5px 0 0 0;
    font-size: 15px;
    color: rgba(255, 255, 255, 0.9);
}

/* Tab Navigation */
.tabs-navigation {
    background: white;
    border-radius: 16px;
    padding: 10px;
    margin-bottom: 25px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    display: flex;
    gap: 8px;
    overflow-x: auto;
}

.tab-nav-btn {
    flex: 1;
    min-width: 120px;
    padding: 14px 20px;
    background: transparent;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
}

.tab-nav-btn i {
    font-size: 16px;
}

.tab-nav-btn:hover {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.tab-nav-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

/* Tabs Content */
.tabs-content-wrapper {
    min-height: 400px;
}

.tab-content {
    display: none;
    animation: fadeIn 0.4s ease-out;
}

.tab-content.active {
    display: block;
}

/* Alert Box */
.alert-box {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideIn 0.3s ease-out;
}

.alert-box.success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border-left: 4px solid #10b981;
}

.alert-box.error {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

.alert-box i {
    font-size: 20px;
}

.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    color: inherit;
    cursor: pointer;
    font-size: 18px;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.alert-close:hover {
    opacity: 1;
}

/* Settings Form */
.settings-form {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

/* Settings Card */
.settings-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.settings-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    padding: 25px 30px;
    border-bottom: 2px solid #f1f5f9;
}

.card-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 5px;
}

.card-title i {
    color: #667eea;
    font-size: 22px;
}

.card-subtitle {
    margin: 0;
    font-size: 14px;
    color: #64748b;
}

.card-body {
    padding: 30px;
}

/* Action Bar */
.action-bar {
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Table */
.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.data-table thead {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.data-table th {
    padding: 14px 16px;
    text-align: left;
    font-size: 13px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    border-bottom: 2px solid #e2e8f0;
}

.data-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
    color: #334155;
}

.data-table tbody tr {
    transition: all 0.2s ease;
}

.data-table tbody tr:hover {
    background: #f8fafc;
}

/* Badge */
.badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
}

.badge-primary {
    background: #dbeafe;
    color: #1e40af;
}

.badge-success {
    background: #d1fae5;
    color: #065f46;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
}

.badge-info {
    background: #e0f2fe;
    color: #075985;
}

.badge-secondary {
    background: #f1f5f9;
    color: #475569;
}

/* Icon Preview */
.icon-preview {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.user-avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

/* Button Icon */
.btn-icon {
    padding: 8px 12px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
    margin: 0 2px;
}

.btn-icon:hover {
    transform: translateY(-2px);
}

.btn-icon.btn-warning {
    background: #fef3c7;
    color: #d97706;
}

.btn-icon.btn-warning:hover {
    background: #fde68a;
}

.btn-icon.btn-danger {
    background: #fee2e2;
    color: #dc2626;
}

.btn-icon.btn-danger:hover {
    background: #fecaca;
}

/* Form Elements */
.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #334155;
}

.form-group label i {
    color: #667eea;
    font-size: 14px;
    width: 16px;
}

.form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    transition: all 0.3s ease;
    background: white;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-input::placeholder {
    color: #94a3b8;
}

.form-hint {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    color: #64748b;
}

textarea.form-input {
    resize: vertical;
    min-height: 80px;
}

.required {
    color: #dc2626;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

/* Toggle List */
.toggle-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.toggle-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.toggle-item:hover {
    border-color: #667eea;
    transform: translateX(5px);
}

.toggle-content {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 1;
}

.toggle-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

.toggle-info h4 {
    margin: 0 0 4px 0;
    font-size: 15px;
    font-weight: 600;
    color: #1e293b;
}

.toggle-info p {
    margin: 0;
    font-size: 13px;
    color: #64748b;
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 52px;
    height: 28px;
    flex-shrink: 0;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: 0.3s;
    border-radius: 14px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.toggle-switch input:checked + .slider {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.toggle-switch input:checked + .slider:before {
    transform: translateX(24px);
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    padding: 25px 30px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.btn {
    padding: 13px 30px;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
}

.btn-secondary {
    background: white;
    color: #64748b;
    border: 2px solid #e2e8f0;
}

.btn-secondary:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

/* Info Card */
.info-card {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-left: 4px solid #f59e0b;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    gap: 15px;
    margin-top: 25px;
}

.info-icon {
    width: 40px;
    height: 40px;
    background: rgba(245, 158, 11, 0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #d97706;
    font-size: 20px;
    flex-shrink: 0;
}

.info-content h4 {
    margin: 0 0 6px 0;
    font-size: 16px;
    font-weight: 700;
    color: #92400e;
}

.info-content p {
    margin: 0;
    font-size: 14px;
    color: #78350f;
    line-height: 1.6;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    animation: fadeIn 0.3s ease-out;
}

.modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 16px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease-out;
}

.modal-content.modal-large {
    max-width: 700px;
}

.modal-header {
    padding: 25px 30px;
    border-bottom: 2px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
}

.modal-close {
    background: none;
    border: none;
    font-size: 28px;
    color: #64748b;
    cursor: pointer;
    line-height: 1;
    transition: color 0.2s;
}

.modal-close:hover {
    color: #dc2626;
}

.modal-body {
    padding: 30px;
}

.modal-footer {
    padding: 20px 30px;
    border-top: 2px solid #f1f5f9;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .settings-page {
        padding: 20px 15px;
    }

    .page-header {
        padding: 20px;
    }

    .header-wrapper {
        flex-direction: column;
        text-align: center;
    }

    .header-info h1 {
        font-size: 24px;
    }

    .tabs-navigation {
        padding: 8px;
        gap: 5px;
    }

    .tab-nav-btn {
        min-width: 80px;
        padding: 12px 15px;
        font-size: 12px;
    }

    .tab-nav-btn span {
        display: none;
    }

    .tab-nav-btn i {
        font-size: 18px;
    }

    .card-body {
        padding: 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
        padding: 20px;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .toggle-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .action-bar {
        flex-direction: column;
        gap: 15px;
    }

    .data-table {
        font-size: 12px;
    }

    .data-table th,
    .data-table td {
        padding: 10px 8px;
    }

    .modal-content {
        width: 95%;
        max-height: 95vh;
    }
}
</style>
<script>
// Tab Switching
document.querySelectorAll('.tab-nav-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const targetTab = this.dataset.tab;
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab-nav-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(p => p.classList.remove('active'));
        
        // Add active class to clicked tab
        this.classList.add('active');
        document.getElementById('tab-' + targetTab).classList.add('active');
    });
});

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
    }
});

// Form validation
document.querySelector('.settings-form')?.addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.style.borderColor = '#ef4444';
            setTimeout(() => {
                field.style.borderColor = '#e2e8f0';
            }, 3000);
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi!');
        return false;
    }
});

// Unsaved changes warning
let formChanged = false;
const forms = document.querySelectorAll('form');

forms.forEach(form => {
    form.addEventListener('change', () => {
        formChanged = true;
    });
    
    form.addEventListener('submit', () => {
        formChanged = false;
    });
});

window.addEventListener('beforeunload', (e) => {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin keluar?';
    }
});

// Auto-hide alerts
setTimeout(() => {
    document.querySelectorAll('.alert-box').forEach(alert => {
        alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-20px)';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);

// Prevent multiple form submissions
forms.forEach(form => {
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn && submitBtn.disabled) {
            e.preventDefault();
            return false;
        }
        if (submitBtn) {
            submitBtn.disabled = true;
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Menyimpan...</span>';
            
            // Re-enable after 3 seconds as fallback
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHTML;
            }, 3000);
        }
    });
});
</script>
