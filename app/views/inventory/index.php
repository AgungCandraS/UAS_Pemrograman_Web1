<div class="fade-in">
    <!-- Header Section -->
    <div class="mb-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            <div>
                <h1 class="fw-800 mb-2" style="color: var(--text-primary); font-family: 'Poppins', sans-serif; font-size: 2.2rem;">📦 Inventory Management</h1>
                <p class="mb-0" style="color: var(--text-secondary); font-size: 1rem;">Kelola dan pantau stok produk Anda secara real-time</p>
            </div>
            <a href="<?= base_url('inventory/create') ?>" class="inv-btn inv-btn-primary" style="padding: 0.875rem 1.5rem; font-weight: 600; text-decoration: none;">
                <i class="fas fa-plus me-2"></i>Tambah Produk Baru
            </a>
        </div>
    </div>
    
    <!-- Quick Stats Section -->
    <div class="stats-grid" style="margin-bottom: 2rem;">
        <?php
        $totalProducts = count($products);
        $lowStockCount = count(array_filter($products, function($p) { return $p['stock'] <= $p['min_stock']; }));
        $totalValue = array_sum(array_map(function($p) { return $p['price']; }, $products));
        $activeCount = count(array_filter($products, function($p) { return $p['status'] === 'active'; }));
        ?>
        <!-- Total Produk Card -->
        <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-box" style="font-size: 1.5rem; color: var(--primary); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Total Produk</p>
            <p style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin: 0;"><?= $totalProducts ?></p>
        </div>
        
        <!-- Stok Rendah Card -->
        <div style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(249, 115, 22, 0.15) 100%); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem; color: var(--warning); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Stok Rendah</p>
            <p style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin: 0;"><?= $lowStockCount ?></p>
        </div>
        
        <!-- Total Nilai Card -->
        <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-dollar-sign" style="font-size: 1.5rem; color: var(--success); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Total Nilai</p>
            <p style="color: var(--text-primary); font-size: 1.6rem; font-weight: 700; margin: 0;"><?= format_currency($totalValue) ?></p>
        </div>
        
        <!-- Item Aktif Card -->
        <div style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(6, 182, 212, 0.15) 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-chart-pie" style="font-size: 1.5rem; color: var(--info); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Item Aktif</p>
            <p style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin: 0;"><?= $activeCount ?></p>
        </div>
    </div>
    
    <!-- Search & Filter Section -->
    <div class="mb-4">
        <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">
            <form method="GET" class="inventory-search-form">
                <div style="display: grid; grid-template-columns: 1fr auto auto; gap: 1rem; align-items: end;">
                    <!-- Search Input -->
                    <div>
                        <label style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem; display: block; font-weight: 500;">🔍 Cari Produk</label>
                        <input type="text" name="search" value="<?= get('search', '') ?>" placeholder="Ketikkan nama atau SKU produk..." class="form-control-modern" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; background: var(--surface-2); color: var(--text-primary); transition: all 0.3s ease;">
                    </div>
                    
                    <!-- Search Button -->
                    <button type="submit" class="inv-btn inv-btn-primary" style="padding: 0.75rem 1.5rem; white-space: nowrap;">
                        <i class="fas fa-search me-1"></i>Cari
                    </button>
                    
                    <!-- Export Dropdown -->
                    <div class="dropdown">
                        <button class="inv-btn inv-btn-success dropdown-toggle" type="button" id="dropdownExport" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0.75rem 1.5rem; white-space: nowrap;">
                            <i class="fas fa-file-export me-1"></i>Export
                        </button>
                        <ul class="dropdown-menu dropdown-menu-modern" aria-labelledby="dropdownExport">
                            <li><a class="dropdown-item" href="<?= base_url('inventory/export?format=excel') ?>" style="text-decoration: none;">
                                <i class="fas fa-file-excel text-success me-2"></i>Export Excel
                            </a></li>
                            <li><a class="dropdown-item" href="<?= base_url('inventory/export?format=pdf') ?>" style="text-decoration: none;">
                                <i class="fas fa-file-pdf text-danger me-2"></i>Export PDF
                            </a></li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Product Table Section -->
    <div>
        <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">
            <!-- Table Header -->
            <div style="background: linear-gradient(90deg, var(--surface-2) 0%, var(--surface-3) 100%); padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color);">
                <h5 style="color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1rem;">Daftar Produk Inventory</h5>
            </div>
            
            <!-- Table Content -->
            <div class="table-responsive">
                <table class="inventory-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--surface-2); border-bottom: 2px solid var(--border-color);">
                            <th style="padding: 1rem 1.5rem; color: var(--text-secondary); font-weight: 600; text-align: left; font-size: 0.85rem;">SKU</th>
                            <th style="padding: 1rem 1.5rem; color: var(--text-secondary); font-weight: 600; text-align: left; font-size: 0.85rem;">Produk</th>
                            <th style="padding: 1rem 1.5rem; color: var(--text-secondary); font-weight: 600; text-align: left; font-size: 0.85rem;">Kategori</th>
                            <th style="padding: 1rem 1.5rem; color: var(--text-secondary); font-weight: 600; text-align: center; font-size: 0.85rem;">Harga</th>
                            <th style="padding: 1rem 1.5rem; color: var(--text-secondary); font-weight: 600; text-align: center; font-size: 0.85rem;">Stok</th>
                            <th style="padding: 1rem 1.5rem; color: var(--text-secondary); font-weight: 600; text-align: center; font-size: 0.85rem;">Status</th>
                            <th style="padding: 1rem 1.5rem; color: var(--text-secondary); font-weight: 600; text-align: center; font-size: 0.85rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="7" style="padding: 3rem 1.5rem; text-align: center;">
                                    <div style="color: var(--text-tertiary);">
                                        <i class="fas fa-box-open fa-3x mb-3" style="opacity: 0.3; display: block;"></i>
                                        <p style="margin: 0.5rem 0 0 0; font-size: 1rem;">Tidak ada data produk</p>
                                        <p style="margin: 0.25rem 0 0 0; color: var(--text-muted); font-size: 0.9rem;">Mulai dengan menambahkan produk pertama Anda</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $idx => $product): ?>
                                <tr style="border-bottom: 1px solid var(--border-color); transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='var(--surface-2)'" onmouseout="this.style.backgroundColor='transparent'">
                                    <td style="padding: 1.25rem 1.5rem; color: var(--text-secondary); font-size: 0.85rem; font-weight: 500;"><?= $product['sku'] ?></td>
                                    <td style="padding: 1.25rem 1.5rem;">
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <?php if ($product['image']): ?>
                                                <img src="<?= base_url('storage/uploads/' . $product['image']) ?>" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 2px solid var(--border-color);" alt="">
                                            <?php else: ?>
                                                <div style="width: 48px; height: 48px; border-radius: 8px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-box" style="color: white; font-size: 1.25rem;"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <p style="margin: 0; color: var(--text-primary); font-weight: 600; font-size: 0.95rem;"><?= $product['name'] ?></p>
                                                <p style="margin: 0.25rem 0 0 0; color: var(--text-muted); font-size: 0.8rem;"><?= substr($product['description'] ?? '', 0, 30) ?><?= strlen($product['description'] ?? '') > 30 ? '...' : '' ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 1.25rem 1.5rem; color: var(--text-secondary); font-size: 0.9rem;"><?= $product['category_name'] ?? '-' ?></td>
                                    <td style="padding: 1.25rem 1.5rem; color: var(--text-primary); font-weight: 600; text-align: center; font-size: 0.95rem;"><?= format_currency($product['price']) ?></td>
                                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                                        <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600; background: <?= $product['stock'] <= $product['min_stock'] ? 'rgba(239, 68, 68, 0.15); color: var(--danger)' : 'rgba(16, 185, 129, 0.15); color: var(--success)' ?>;">
                                            <?= $product['stock'] ?> <?= $product['unit'] ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                                        <span style="display: inline-block; padding: 0.5rem 0.875rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; background: <?= $product['status'] === 'active' ? 'rgba(16, 185, 129, 0.2); color: var(--success)' : 'rgba(239, 68, 68, 0.2); color: var(--danger)' ?>;">
                                            <?= $product['status'] === 'active' ? '✓ Aktif' : '✗ Nonaktif' ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                                        <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                            <a href="<?= base_url('inventory/edit/' . $product['id']) ?>" class="action-btn action-btn-edit" title="Edit Produk">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="confirmAction('Yakin ingin menghapus produk ini?', '<?= base_url('inventory/delete/' . $product['id']) ?>')" class="action-btn action-btn-delete" title="Hapus Produk">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div style="padding: 1.5rem; border-top: 1px solid var(--border-color); display: flex; justify-content: center;">
                    <nav>
                        <ul style="display: flex; list-style: none; gap: 0.5rem; margin: 0; padding: 0;">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li>
                                    <a href="?page=<?= $i ?>&search=<?= urlencode(get('search', '')) ?>" style="display: block; padding: 0.5rem 0.75rem; border-radius: 6px; border: 1px solid var(--border-color); color: <?= $i == ($page ?? 1) ? 'white' : 'var(--text-secondary)' ?>; background: <?= $i == ($page ?? 1) ? 'var(--primary)' : 'var(--surface-2)' ?>; text-decoration: none; font-weight: 500; transition: all 0.2s ease;">
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
</div>

<style>
.btn-outline-custom {
    background: transparent;
    transition: all 0.3s ease;
}

.form-control-modern {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.form-control-modern:focus {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
    outline: none !important;
}

.dropdown-menu-modern {
    background: var(--surface-1) !important;
    border: 1px solid var(--border-color) !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
}

.dropdown-menu-modern .dropdown-item {
    color: var(--text-secondary) !important;
    transition: all 0.2s ease !important;
}

.dropdown-menu-modern .dropdown-item:hover {
    background: var(--surface-2) !important;
    color: var(--text-primary) !important;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.action-btn-edit {
    background: rgba(99, 102, 241, 0.15);
    color: var(--primary);
}

.action-btn-edit:hover {
    background: rgba(99, 102, 241, 0.25);
    transform: scale(1.1);
}

.action-btn-delete {
    background: rgba(239, 68, 68, 0.15);
    color: var(--danger);
}

.action-btn-delete:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: scale(1.1);
}

.inventory-table tbody tr {
    transition: background-color 0.2s ease !important;
}

/* Stats grid: 4 columns in one row */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
}

/* NEW: Button Styling */
.inv-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none !important;
    white-space: nowrap;
    font-family: 'Inter', sans-serif;
}

.inv-btn:focus,
.inv-btn:active {
    outline: none;
    text-decoration: none;
}

.inv-btn-primary {
    background: var(--primary);
    color: white;
}

.inv-btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.inv-btn-primary:active,
.inv-btn-primary:focus {
    background: var(--primary-dark);
    text-decoration: none;
}

.inv-btn-warning {
    background: var(--warning);
    color: white;
}

.inv-btn-warning:hover {
    background: var(--warning-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.inv-btn-warning:active,
.inv-btn-warning:focus {
    background: var(--warning-dark);
    text-decoration: none;
}

.inv-btn-success {
    background: var(--success);
    color: white;
}

.inv-btn-success:hover {
    background: var(--success-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.inv-btn-success:active,
.inv-btn-success:focus {
    background: var(--success-dark);
    text-decoration: none;
}

.inv-btn.dropdown-toggle::after {
    margin-left: 0.5rem;
}

/* Dropdown Menu Modern Styling */
.dropdown-menu-modern {
    background: var(--surface-1) !important;
    border: 1px solid var(--border-color) !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
    padding: 0.5rem 0 !important;
    min-width: 150px;
}

.dropdown-menu-modern .dropdown-item {
    color: var(--text-secondary) !important;
    transition: all 0.2s ease !important;
    padding: 0.75rem 1rem !important;
    font-size: 0.9rem;
    text-decoration: none !important;
}

.dropdown-menu-modern .dropdown-item:hover {
    background: var(--surface-2) !important;
    color: var(--text-primary) !important;
    text-decoration: none;
    padding-left: 1.25rem;
}

@media (max-width: 768px) {
    .action-btn {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
    
    .inv-btn {
        padding: 0.625rem 1rem;
        font-size: 0.85rem;
    }

    .stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
$additionalScripts = '
<script>
function confirmAction(message, url) {
    if (confirm(message)) {
        const form = document.createElement("form");
        form.method = "POST";
        form.action = url;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
';
?>