<div class="fade-in">
    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <!-- Revenue Card -->
        <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s ease;">
            <i class="fas fa-wallet" style="font-size: 1.5rem; color: var(--primary); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Total Pendapatan</p>
            <p style="color: var(--text-primary); font-size: 1.6rem; font-weight: 700; margin: 0;"><?= format_currency($stats['revenue']) ?></p>
            <p style="color: var(--success); font-size: 0.75rem; margin-top: 0.5rem; margin-bottom: 0;">
                <i class="fas fa-arrow-up me-1"></i>+12.5% dari bulan lalu
            </p>
        </div>
        
        <!-- Sales Card -->
        <div style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(6, 182, 212, 0.15) 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s ease;">
            <i class="fas fa-shopping-cart" style="font-size: 1.5rem; color: var(--info); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Total Penjualan</p>
            <p style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin: 0;"><?= $stats['orders'] ?></p>
            <p style="color: var(--success); font-size: 0.75rem; margin-top: 0.5rem; margin-bottom: 0;">
                <i class="fas fa-check-circle me-1"></i>Semua selesai
            </p>
        </div>
        
        <!-- Products Card -->
        <div style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(249, 115, 22, 0.15) 100%); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s ease;">
            <i class="fas fa-boxes" style="font-size: 1.5rem; color: var(--warning); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Total Produk</p>
            <p style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin: 0;"><?= $stats['products'] ?></p>
            <p style="color: var(--warning); font-size: 0.75rem; margin-top: 0.5rem; margin-bottom: 0;">
                <i class="fas fa-exclamation-triangle me-1"></i><?= count($lowStockProducts) ?> stok rendah
            </p>
        </div>
        
        <!-- Employees Card -->
        <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center; transition: all 0.3s ease;">
            <i class="fas fa-users" style="font-size: 1.5rem; color: var(--success); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Total Karyawan</p>
            <p style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin: 0;"><?= $stats['employees'] ?></p>
            <p style="color: var(--success); font-size: 0.75rem; margin-top: 0.5rem; margin-bottom: 0;">
                <i class="fas fa-check-circle me-1"></i>Semua aktif
            </p>
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
        
        <!-- Quick Actions -->
        <div class="col-12 col-lg-4">
            <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">
                <div style="background: linear-gradient(90deg, var(--surface-2) 0%, var(--surface-3) 100%); padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color);">
                    <h5 style="color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1rem;">
                        <i class="fas fa-bolt text-primary me-2"></i>Quick Actions
                    </h5>
                </div>
                <div style="padding: 1.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                        <a href="<?= base_url('sales/create') ?>" style="text-decoration: none;">
                            <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 12px; padding: 1.75rem 1rem; text-align: center; transition: all 0.3s ease; cursor: pointer;">
                                <i class="fas fa-plus-circle" style="font-size: 2rem; color: var(--primary); margin-bottom: 0.75rem; display: block;"></i>
                                <p style="color: var(--text-primary); font-size: 0.9rem; font-weight: 600; margin: 0;">Penjualan Baru</p>
                            </div>
                        </a>
                        
                        <a href="<?= base_url('inventory/create') ?>" style="text-decoration: none;">
                            <div style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(6, 182, 212, 0.15) 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 12px; padding: 1.75rem 1rem; text-align: center; transition: all 0.3s ease; cursor: pointer;">
                                <i class="fas fa-box" style="font-size: 2rem; color: var(--info); margin-bottom: 0.75rem; display: block;"></i>
                                <p style="color: var(--text-primary); font-size: 0.9rem; font-weight: 600; margin: 0;">Tambah Produk</p>
                            </div>
                        </a>
                        
                        <a href="<?= base_url('finance') ?>" style="text-decoration: none;">
                            <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 1.75rem 1rem; text-align: center; transition: all 0.3s ease; cursor: pointer;">
                                <i class="fas fa-money-bill-wave" style="font-size: 2rem; color: var(--success); margin-bottom: 0.75rem; display: block;"></i>
                                <p style="color: var(--text-primary); font-size: 0.9rem; font-weight: 600; margin: 0;">Keuangan</p>
                            </div>
                        </a>
                        
                        <a href="<?= base_url('hr/employees') ?>" style="text-decoration: none;">
                            <div style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(249, 115, 22, 0.15) 100%); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 12px; padding: 1.75rem 1rem; text-align: center; transition: all 0.3s ease; cursor: pointer;">
                                <i class="fas fa-user-plus" style="font-size: 2rem; color: var(--warning); margin-bottom: 0.75rem; display: block;"></i>
                                <p style="color: var(--text-primary); font-size: 0.9rem; font-weight: 600; margin: 0;">Tambah Karyawan</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$additionalScripts = '
<script>
    // Modern Sales Chart
    const ctx = document.getElementById("salesChart").getContext("2d");
    const salesData = ' . json_encode($monthlySales) . ';
    
    const labels = salesData.map(item => {
        const [year, month] = item.month.split("-");
        const date = new Date(year, month - 1);
        return date.toLocaleDateString("id-ID", { month: "short", year: "numeric" });
    });
    
    const data = salesData.map(item => parseFloat(item.total));
    
    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, "rgba(99, 102, 241, 0.4)");
    gradient.addColorStop(0.5, "rgba(99, 102, 241, 0.15)");
    gradient.addColorStop(1, "rgba(99, 102, 241, 0.02)");
    
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
                pointHoverRadius: 9,
                pointHoverBackgroundColor: "#6366f1",
                pointHoverBorderWidth: 4,
                pointHoverBorderColor: "#fff"
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
                    backgroundColor: "rgba(15, 23, 42, 0.95)",
                    titleColor: "#fff",
                    bodyColor: "#e2e8f0",
                    titleFont: {
                        size: 14,
                        weight: "bold"
                    },
                    bodyFont: {
                        size: 13
                    },
                    borderColor: "rgba(99, 102, 241, 0.3)",
                    borderWidth: 1,
                    padding: 16,
                    cornerRadius: 12,
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
                            return "Rp " + (value / 1000000).toFixed(1) + "jt";
                        },
                        font: {
                            size: 12,
                            weight: "500"
                        },
                        color: "#94a3b8"
                    },
                    grid: {
                        color: "rgba(148, 163, 184, 0.1)",
                        drawBorder: false,
                        lineWidth: 1
                    },
                    border: {
                        display: false,
                        dash: [5, 5]
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: "500"
                        },
                        color: "#94a3b8"
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
