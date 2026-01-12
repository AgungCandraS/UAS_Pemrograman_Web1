<div class="fade-in">
    <!-- Header -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--text-primary); font-family: 'Poppins', sans-serif;">Inventory Management</h2>
                    <p class="mb-0" style="color: var(--text-tertiary); font-size: 0.9rem;">Kelola produk dan stok inventory Anda</p>
                </div>
                <a href="<?= base_url('inventory/create') ?>" class="btn-custom btn-primary-custom">
                    <i class="fas fa-plus me-2"></i>Tambah Produk
                </a>
            </div>
        </div>
    </div>
    
    <!-- Search & Filter -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="table-card">
                <div class="p-3">
                    <form method="GET">
                        <div class="row g-3">
                            <div class="col-12 col-md-6 col-lg-5">
                                <input type="text" name="search" value="<?= get('search', '') ?>" placeholder="Cari produk..." class="form-control-custom">
                            </div>
                            <div class="col-12 col-md-6 col-lg-7">
                                <div class="row g-2">
                                    <div class="col-12 col-sm-4">
                                        <button type="submit" class="btn-custom btn-primary-custom w-100">
                                            <i class="fas fa-search me-2"></i>Cari
                                        </button>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <a href="<?= base_url('inventory/low-stock') ?>" class="btn-custom btn-warning-custom w-100">
                                            <i class="fas fa-exclamation-triangle me-2"></i>Stok Rendah
                                        </a>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="dropdown w-100">
                                            <button class="btn-custom btn-success-custom w-100 dropdown-toggle" type="button" id="dropdownExport" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-file-export me-2"></i>Export
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownExport">
                                                <li><a class="dropdown-item" href="<?= base_url('inventory/export?format=excel') ?>">
                                                    <i class="fas fa-file-excel text-success me-2"></i>Export Excel
                                                </a></li>
                                                <li><a class="dropdown-item" href="<?= base_url('inventory/export?format=pdf') ?>">
                                                    <i class="fas fa-file-pdf text-danger me-2"></i>Export PDF
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Table -->
    <div class="row g-4">
        <div class="col-12">
            <div class="table-card">
                <div class="table-card-header">
                    <h5>Daftar Produk</h5>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5" style="color: var(--text-tertiary);">
                                        <i class="fas fa-box-open fa-3x mb-3" style="opacity: 0.3;"></i>
                                        <p class="mb-0">Tidak ada data produk</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td data-label="SKU" style="color: var(--text-tertiary); font-size: 0.85rem;" class="d-none d-lg-table-cell"><?= $product['sku'] ?></td>
                                        <td data-label="Produk">
                                            <div class="d-flex align-items-center">
                                                <?php if ($product['image']): ?>
                                                    <img src="<?= base_url('storage/uploads/' . $product['image']) ?>" class="rounded me-3" width="40" height="40" style="object-fit: cover; border: 2px solid var(--border-color);" alt="">
                                                <?php else: ?>
                                                    <div class="rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: var(--surface-2); border: 2px solid var(--border-color);">
                                                        <i class="fas fa-box" style="color: var(--text-muted);"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="fw-semibold" style="color: var(--text-primary);"><?= $product['name'] ?></div>
                                            </div>
                                        </td>
                                        <td data-label="Kategori" style="color: var(--text-secondary);" class="d-none d-md-table-cell"><?= $product['category_name'] ?? '-' ?></td>
                                        <td data-label="Harga" class="fw-semibold" style="color: var(--text-primary);"><?= format_currency($product['price']) ?></td>
                                        <td data-label="Stok">
                                            <span class="badge-custom <?= $product['stock'] <= $product['min_stock'] ? 'badge-danger' : 'badge-success' ?>">
                                                <?= $product['stock'] ?> <?= $product['unit'] ?>
                                            </span>
                                        </td>
                                        <td data-label="Status" class="d-none d-lg-table-cell">
                                            <span class="badge-custom <?= $product['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>">
                                                <?= ucfirst($product['status']) ?>
                                            </span>
                                        </td>
                                        <td data-label="Aksi">
                                            <div class="d-flex gap-2">
                                                <a href="<?= base_url('inventory/edit/' . $product['id']) ?>" class="btn btn-sm btn-outline-custom" style="padding: 0.375rem 0.75rem; border: 1px solid var(--primary); color: var(--primary);" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="confirmAction('Yakin hapus produk ini?', '<?= base_url('inventory/delete/' . $product['id']) ?>')" class="btn btn-sm btn-outline-custom" style="padding: 0.375rem 0.75rem; border: 1px solid var(--danger); color: var(--danger);" title="Hapus">
                                                    <i class="fas fa-trash"></i>
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
                    <div class="p-3" style="border-top: 1px solid var(--border-color);">
                        <nav>
                            <ul class="pagination pagination-sm justify-content-center mb-0">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == ($page ?? 1) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>&search=<?= get('search', '') ?>" style="background: <?= $i == ($page ?? 1) ? 'var(--primary)' : 'var(--surface-2)' ?>; color: <?= $i == ($page ?? 1) ? 'white' : 'var(--text-secondary)' ?>; border-color: var(--border-color);">
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
</div>

<style>
.btn-outline-custom {
    background: transparent;
    transition: all 0.3s ease;
}

.btn-outline-custom:hover {
    background: var(--surface-3);
    transform: translateY(-2px);
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