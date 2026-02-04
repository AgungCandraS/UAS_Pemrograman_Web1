<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - Bisnisku</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    
    <?= $additionalHead ?? '' ?>
</head>
<body>
    <!-- Sidebar Toggle Button (Fixed for Mobile/Tablet) -->
    <button class="sidebar-toggle" id="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar Overlay (for Mobile/Tablet) -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    
    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <h2><i class="fas fa-store me-2"></i>Bisnisku</h2>
                <p>Management System</p>
            </div>
            
            <nav class="sidebar-menu">
                <a href="<?= base_url('dashboard') ?>" class="sidebar-item <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="<?= base_url('inventory') ?>" class="sidebar-item <?= ($activePage ?? '') === 'inventory' ? 'active' : '' ?>">
                    <i class="fas fa-boxes"></i>
                    <span>Inventory</span>
                </a>
                
                <a href="<?= base_url('finance') ?>" class="sidebar-item <?= ($activePage ?? '') === 'finance' ? 'active' : '' ?>">
                    <i class="fas fa-wallet"></i>
                    <span>Keuangan</span>
                </a>
                
                <a href="<?= base_url('hr') ?>" class="sidebar-item <?= ($activePage ?? '') === 'hr' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>HR & Karyawan</span>
                </a>
                
                <a href="<?= base_url('sales') ?>" class="sidebar-item <?= ($activePage ?? '') === 'sales' ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Penjualan</span>
                </a>
                
                <a href="<?= base_url('ai-assistant') ?>" class="sidebar-item <?= ($activePage ?? '') === 'ai' ? 'active' : '' ?>">
                    <i class="fas fa-robot"></i>
                    <span>AI Assistant</span>
                </a>
                
                <div class="sidebar-divider"></div>
                
                <a href="<?= base_url('profile') ?>" class="sidebar-item">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile</span>
                </a>
                
                <a href="<?= base_url('settings') ?>" class="sidebar-item">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
                
                <a href="<?= base_url('logout') ?>" class="sidebar-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="main-content content">
            <!-- Top Bar -->
            <header class="topbar">
                <div class="topbar-title">
                    <h3><?= $pageTitle ?? 'Dashboard' ?></h3>
                </div>
                
                <div class="topbar-actions">
                    <!-- Notifications -->
                    <button class="topbar-notification">
                        <i class="fas fa-bell fs-5"></i>
                        <span class="badge"></span>
                    </button>
                    
                    <!-- User Menu -->
                    <div class="topbar-user">
                        <div class="topbar-user-avatar">
                            <?= strtoupper(substr(auth_user()['full_name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <div class="topbar-user-info d-none d-md-block">
                            <h6><?= auth_user()['full_name'] ?? 'User' ?></h6>
                            <p><?= ucfirst(auth_user()['role'] ?? 'user') ?></p>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="content-area">
                <?php if ($msg = flash('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= $msg ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($msg = flash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-times-circle me-2"></i><?= $msg ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($msg = flash('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i><?= $msg ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($msg = flash('info')): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i><?= $msg ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?= $content ?>
            </main>

            <!-- Footer -->
            <footer class="text-center py-3 mt-4" style="color: var(--text-tertiary); border-top: 1px solid var(--border-color); font-size: 0.875rem;">
                @Copyright by 23552011272_Agung Candra Saputra_TIF-RP223 CNS B_UAS-WEB1
            </footer>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= asset('js/script.js') ?>"></script>
    
    <?= $additionalScripts ?? '' ?>
</body>
</html>
