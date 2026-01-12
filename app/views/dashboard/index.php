<div class="fade-in">
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Revenue Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card hover-lift">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="flex-grow-1">
                        <p class="stat-card-title mb-2">Total Pendapatan</p>
                        <h2 class="stat-card-value"><?= format_currency($stats['revenue']) ?></h2>
                        <p class="stat-card-trend up mb-0">
                            <i class="fas fa-arrow-up me-1"></i>+12.5% dari bulan lalu
                        </p>
                    </div>
                    <div class="stat-card-icon primary">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Orders Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card hover-lift">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="flex-grow-1">
                        <p class="stat-card-title mb-2">Total Pesanan</p>
                        <h2 class="stat-card-value"><?= $stats['orders'] ?></h2>
                        <p class="stat-card-trend mb-0 text-info">
                            <i class="fas fa-info-circle me-1"></i><?= $stats['pending_orders'] ?> pending
                        </p>
                    </div>
                    <div class="stat-card-icon info">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Products Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card hover-lift">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="flex-grow-1">
                        <p class="stat-card-title mb-2">Total Produk</p>
                        <h2 class="stat-card-value"><?= $stats['products'] ?></h2>
                        <p class="stat-card-trend mb-0 text-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i><?= count($lowStockProducts) ?> stok rendah
                        </p>
                    </div>
                    <div class="stat-card-icon warning">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Employees Card -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card hover-lift">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="flex-grow-1">
                        <p class="stat-card-title mb-2">Total Karyawan</p>
                        <h2 class="stat-card-value"><?= $stats['employees'] ?></h2>
                        <p class="stat-card-trend up mb-0">
                            <i class="fas fa-check-circle me-1"></i>Semua aktif
                        </p>
                    </div>
                    <div class="stat-card-icon success">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4 mb-4">
        <!-- Sales Chart -->
        <div class="col-12 col-lg-8">
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line text-primary me-2"></i>Penjualan 6 Bulan Terakhir
                    </h5>
                    <select class="form-select form-select-sm" style="width: auto;">
                        <option>6 Bulan</option>
                        <option>3 Bulan</option>
                        <option>1 Tahun</option>
                    </select>
                </div>
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="col-12 col-lg-4">
            <div class="table-card">
                <div class="table-card-header">
                    <h5>
                        <i class="fas fa-shopping-bag text-info me-2"></i>Pesanan Terbaru
                    </h5>
                </div>
                <div class="p-3">
                    <?php if (empty($recentOrders)): ?>
                        <p class="text-muted text-center py-4 mb-0">Tidak ada pesanan</p>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($recentOrders as $order): ?>
                                <div class="p-3 bg-light rounded hover-lift">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <p class="fw-semibold mb-1"><?= $order['order_number'] ?></p>
                                            <p class="text-muted small mb-0"><?= $order['customer_name'] ?></p>
                                        </div>
                                        <span class="badge-custom <?= 
                                            $order['order_status'] === 'pending' ? 'badge-warning' : 
                                            ($order['order_status'] === 'delivered' ? 'badge-success' : 'badge-info') 
                                        ?>">
                                            <?= ucfirst($order['order_status']) ?>
                                        </span>
                                    </div>
                                    <p class="fw-bold mb-0 text-primary"><?= format_currency($order['total']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <a href="<?= base_url('orders') ?>" class="btn btn-sm btn-outline-primary w-100 mt-3">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Low Stock Alert -->
        <div class="col-12 col-lg-6">
            <div class="table-card">
                <div class="table-card-header">
                    <h5>
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Stok Menipis
                    </h5>
                </div>
                <div class="p-3">
                    <?php if (empty($lowStockProducts)): ?>
                        <p class="text-muted text-center py-4 mb-0">Tidak ada produk dengan stok rendah</p>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($lowStockProducts as $product): ?>
                                <div class="d-flex justify-content-between align-items-center p-3 border-start border-4 border-warning bg-light rounded">
                                    <div>
                                        <p class="fw-semibold mb-1"><?= $product['name'] ?></p>
                                        <p class="text-muted small mb-0">SKU: <?= $product['sku'] ?></p>
                                    </div>
                                    <div class="text-end">
                                        <p class="fs-4 fw-bold text-warning mb-0"><?= $product['stock'] ?></p>
                                        <p class="text-muted small mb-0">Min: <?= $product['min_stock'] ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <a href="<?= base_url('inventory') ?>" class="btn btn-sm btn-outline-warning w-100 mt-3">
                            Kelola Inventory <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-12 col-lg-6">
            <div class="table-card">
                <div class="table-card-header">
                    <h5>
                        <i class="fas fa-bolt text-primary me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="p-3">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="<?= base_url('orders/create') ?>" class="text-decoration-none">
                                <div class="p-4 text-center bg-primary bg-opacity-10 rounded hover-lift">
                                    <i class="fas fa-plus-circle fs-1 text-primary mb-2"></i>
                                    <p class="fw-semibold mb-0">Pesanan Baru</p>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-6">
                            <a href="<?= base_url('inventory/create') ?>" class="text-decoration-none">
                                <div class="p-4 text-center bg-info bg-opacity-10 rounded hover-lift">
                                    <i class="fas fa-box fs-1 text-info mb-2"></i>
                                    <p class="fw-semibold mb-0">Tambah Produk</p>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-6">
                            <a href="<?= base_url('finance') ?>" class="text-decoration-none">
                                <div class="p-4 text-center bg-success bg-opacity-10 rounded hover-lift">
                                    <i class="fas fa-money-bill-wave fs-1 text-success mb-2"></i>
                                    <p class="fw-semibold mb-0">Keuangan</p>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-6">
                            <a href="<?= base_url('hr/employees') ?>" class="text-decoration-none">
                                <div class="p-4 text-center bg-warning bg-opacity-10 rounded hover-lift">
                                    <i class="fas fa-user-plus fs-1 text-warning mb-2"></i>
                                    <p class="fw-semibold mb-0">Tambah Karyawan</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$additionalScripts = '
<script>
    // Sales Chart with modern styling
    const ctx = document.getElementById("salesChart").getContext("2d");
    const salesData = ' . json_encode($monthlySales) . ';
    
    const labels = salesData.map(item => {
        const [year, month] = item.month.split("-");
        const date = new Date(year, month - 1);
        return date.toLocaleDateString("id-ID", { month: "short", year: "numeric" });
    });
    
    const data = salesData.map(item => parseFloat(item.total));
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, "rgba(99, 102, 241, 0.3)");
    gradient.addColorStop(1, "rgba(99, 102, 241, 0.01)");
    
    new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: [{
                label: "Penjualan (Rp)",
                data: data,
                borderColor: "#6366f1",
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: "#6366f1",
                pointBorderColor: "#fff",
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: "#6366f1",
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                intersect: false,
                mode: "index"
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: "rgba(0, 0, 0, 0.8)",
                    titleColor: "#fff",
                    bodyColor: "#fff",
                    borderColor: "rgba(255, 255, 255, 0.1)",
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return "Rp " + new Intl.NumberFormat("id-ID").format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return "Rp " + (value / 1000000).toFixed(0) + "jt";
                        },
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)",
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    },
                    border: {
                        display: false
                    }
                }
            }
        }
    });
</script>
';
?>

<?php
$additionalScripts = '
<script>
    // Sales Chart
    const ctx = document.getElementById("salesChart").getContext("2d");
    const salesData = ' . json_encode($monthlySales) . ';
    
    const labels = salesData.map(item => {
        const [year, month] = item.month.split("-");
        const date = new Date(year, month - 1);
        return date.toLocaleDateString("id-ID", { month: "short", year: "numeric" });
    });
    
    const data = salesData.map(item => parseFloat(item.total));
    
    new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: [{
                label: "Penjualan (Rp)",
                data: data,
                borderColor: "#667eea",
                backgroundColor: "rgba(102, 126, 234, 0.1)",
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: "#667eea",
                pointBorderColor: "#fff",
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: "#fff",
                    titleColor: "#333",
                    bodyColor: "#666",
                    borderColor: "#ddd",
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return "Rp " + new Intl.NumberFormat("id-ID").format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return "Rp " + (value / 1000000).toFixed(0) + "jt";
                        }
                    },
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)"
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
';
?>
