<div class="fade-in">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            <div>
                <h1 class="fw-800 mb-2" style="color: var(--text-primary); font-family: 'Poppins', sans-serif; font-size: 2.2rem;">
                    <i class="fas fa-shopping-cart me-2"></i>Manajemen Penjualan
                </h1>
                <p class="mb-0" style="color: var(--text-secondary); font-size: 1rem;">
                    Kelola penjualan offline dan online dengan sistem admin fee otomatis
                </p>
            </div>
            <a href="<?= base_url('sales/create') ?>" class="btn btn-primary" style="padding: 0.875rem 1.5rem; font-weight: 600;">
                <i class="fas fa-plus me-2"></i>Tambah Penjualan
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <!-- Total Sales -->
        <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-shopping-cart" style="font-size: 1.5rem; color: var(--primary); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Total Penjualan</p>
            <p style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin: 0;"><?= $stats['total_sales'] ?? 0 ?></p>
        </div>

        <!-- Offline Sales -->
        <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-store" style="font-size: 1.5rem; color: var(--success); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Penjualan Offline</p>
            <p style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin: 0;"><?= $stats['offline_sales'] ?? 0 ?></p>
            <p style="color: var(--success); font-size: 0.75rem; margin-top: 0.5rem; margin-bottom: 0;">
                <?= format_currency($stats['offline_revenue'] ?? 0) ?>
            </p>
        </div>

        <!-- Online Sales -->
        <div style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(6, 182, 212, 0.15) 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-globe" style="font-size: 1.5rem; color: var(--info); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Penjualan Online</p>
            <p style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin: 0;"><?= $stats['online_sales'] ?? 0 ?></p>
            <p style="color: var(--info); font-size: 0.75rem; margin-top: 0.5rem; margin-bottom: 0;">
                <?= format_currency($stats['online_revenue'] ?? 0) ?>
            </p>
        </div>

        <!-- Total Revenue -->
        <div style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(249, 115, 22, 0.15) 100%); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-coins" style="font-size: 1.5rem; color: var(--warning); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Total Pendapatan</p>
            <p style="color: var(--text-primary); font-size: 1.5rem; font-weight: 700; margin: 0;"><?= format_currency($stats['total_revenue'] ?? 0) ?></p>
            <p style="color: var(--danger); font-size: 0.75rem; margin-top: 0.5rem; margin-bottom: 0;">
                Fee: <?= format_currency($stats['total_admin_fees'] ?? 0) ?>
            </p>
        </div>
    </div>

    <!-- Filter & Search -->
    <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.5rem; margin-bottom: 2rem;">
        <form method="GET" action="<?= base_url('sales') ?>" class="row g-3">
            <div class="col-md-4">
                <label class="form-label" style="color: var(--text-secondary); font-weight: 500;">Pencarian</label>
                <input type="text" name="search" class="form-control" placeholder="Nomor / Customer..." 
                       value="<?= htmlspecialchars($search ?? '') ?>" style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
            </div>
            <div class="col-md-3">
                <label class="form-label" style="color: var(--text-secondary); font-weight: 500;">Tipe</label>
                <select name="sale_type" class="form-select" style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                    <option value="">Semua Tipe</option>
                    <option value="offline" <?= ($saleType ?? '') === 'offline' ? 'selected' : '' ?>>Offline</option>
                    <option value="online" <?= ($saleType ?? '') === 'online' ? 'selected' : '' ?>>Online</option>
                </select>
            </div>
            <div class="col-md-5 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fas fa-search me-2"></i>Filter
                </button>
                <a href="<?= base_url('sales') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-redo"></i>
                </a>
                <a href="<?= base_url('sales/admin-fee-settings') ?>" class="btn btn-outline-warning" title="Pengaturan Admin Fee">
                    <i class="fas fa-cog"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Sales Table -->
    <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden;">
        <div style="background: linear-gradient(90deg, var(--surface-2) 0%, var(--surface-3) 100%); padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color);">
            <h5 style="color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1rem;">
                <i class="fas fa-list me-2"></i>Daftar Penjualan
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" style="color: var(--text-primary);">
                <thead style="background: var(--surface-2); border-bottom: 2px solid var(--border-light);">
                    <tr>
                        <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem;">No. Penjualan</th>
                        <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem;">Tipe</th>
                        <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem;">Customer</th>
                        <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem;">Platform</th>
                        <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem; text-align: right;">Total</th>
                        <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem; text-align: right;">Admin Fee</th>
                        <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem; text-align: right;">Final Total</th>
                        <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem; text-align: right;">Keuntungan</th>
                        <th style="padding: 1rem; font-weight: 600; font-size: 0.85rem; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sales)): ?>
                        <tr>
                            <td colspan="9" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                                <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.3;"></i>
                                <p class="mb-0">Belum ada data penjualan</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sales as $sale): ?>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 1rem;">
                                    <a href="<?= base_url('sales/view/' . $sale['id']) ?>" style="color: var(--primary); font-weight: 600; text-decoration: none;">
                                        <?= htmlspecialchars($sale['sale_number']) ?>
                                    </a>
                                    <br>
                                    <small style="color: var(--text-muted);"><?= date('d/m/Y H:i', strtotime($sale['created_at'])) ?></small>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php if ($sale['sale_type'] === 'online'): ?>
                                        <span class="badge" style="background: linear-gradient(135deg, #3b82f6, #06b6d4); color: white; padding: 0.4rem 0.8rem; border-radius: 6px;">
                                            <i class="fas fa-globe me-1"></i>Online
                                        </span>
                                    <?php else: ?>
                                        <span class="badge" style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 0.4rem 0.8rem; border-radius: 6px;">
                                            <i class="fas fa-store me-1"></i>Offline
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <div style="font-weight: 600;"><?= htmlspecialchars($sale['customer_name']) ?></div>
                                    <?php if ($sale['customer_phone']): ?>
                                        <small style="color: var(--text-muted);"><?= htmlspecialchars($sale['customer_phone']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php if ($sale['platform_name']): ?>
                                        <span class="badge bg-info" style="padding: 0.4rem 0.8rem; border-radius: 6px;">
                                            <?= htmlspecialchars($sale['platform_name']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted);">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem; text-align: right; font-weight: 600;">
                                    <?= format_currency($sale['total']) ?>
                                </td>
                                <td style="padding: 1rem; text-align: right; color: var(--danger);">
                                    <?php if ($sale['admin_fee_amount'] > 0): ?>
                                        -<?= format_currency($sale['admin_fee_amount']) ?>
                                        <br><small style="color: var(--text-muted);">(<?= number_format($sale['admin_fee_percentage'], 1) ?>%)</small>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted);">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem; text-align: right; font-weight: 700; color: var(--success); font-size: 1.1rem;">
                                    <?= format_currency($sale['final_total']) ?>
                                </td>
                                <td style="padding: 1rem; text-align: right; font-weight: 700; color: #10b981; font-size: 1.05rem;">
                                    <?= format_currency($sale['profit'] ?? 0) ?>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <div class="btn-group">
                                        <a href="<?= base_url('sales/view/' . $sale['id']) ?>" class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('sales/edit/' . $sale['id']) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('sales/print/' . $sale['id']) ?>" class="btn btn-sm btn-outline-info" title="Print" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div style="padding: 1.5rem; border-top: 1px solid var(--border-color); display: flex; justify-content: center;">
                <nav>
                    <ul class="pagination mb-0">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>&sale_type=<?= urlencode($saleType ?? '') ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>
