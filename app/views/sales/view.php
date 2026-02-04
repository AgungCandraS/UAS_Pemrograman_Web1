<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 style="color: var(--text-primary); font-weight: 700; margin: 0;">
                <i class="fas fa-file-invoice me-2" style="color: var(--primary);"></i>Detail Penjualan
            </h4>
            <p style="color: var(--text-secondary); margin: 0; margin-top: 0.5rem;">
                <?= $sale['sale_number'] ?>
            </p>
        </div>
        <div>
            <a href="<?= base_url('sales') ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <a href="<?= base_url('sales/print/' . $sale['id']) ?>" class="btn btn-primary" target="_blank">
                <i class="fas fa-print me-2"></i>Cetak
            </a>
        </div>
    </div>

    <!-- Alert for Validation Errors -->
    <?php if (has_flash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= get_flash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (has_flash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= get_flash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Sale Info -->
            <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.5rem; margin-bottom: 1.5rem;">
                <h6 style="color: var(--text-primary); font-weight: 600; margin-bottom: 1rem;">
                    <i class="fas fa-info-circle me-2"></i>Informasi Penjualan
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <small style="color: var(--text-secondary); display: block;">Nomor Penjualan</small>
                        <strong style="color: var(--text-primary);"><?= $sale['sale_number'] ?></strong>
                    </div>
                    <div class="col-md-6">
                        <small style="color: var(--text-secondary); display: block;">Tipe Penjualan</small>
                        <?php if ($sale['sale_type'] === 'online'): ?>
                            <span class="badge" style="background: var(--info); color: white;">
                                <i class="fas fa-globe me-1"></i>Online
                            </span>
                        <?php else: ?>
                            <span class="badge" style="background: var(--secondary); color: white;">
                                <i class="fas fa-store me-1"></i>Offline
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php if ($sale['sale_type'] === 'online' && !empty($sale['platform_name'])): ?>
                    <div class="col-md-6">
                        <small style="color: var(--text-secondary); display: block;">Platform</small>
                        <strong style="color: var(--text-primary);"><?= htmlspecialchars($sale['platform_name']) ?></strong>
                        <small style="color: var(--text-muted);">(Fee: <?= $sale['platform_fee_percentage'] ?>%)</small>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <small style="color: var(--text-secondary); display: block;">Tanggal</small>
                        <strong style="color: var(--text-primary);"><?= date('d M Y H:i', strtotime($sale['created_at'])) ?></strong>
                    </div>
                    <div class="col-md-6">
                        <small style="color: var(--text-secondary); display: block;">Dibuat Oleh</small>
                        <strong style="color: var(--text-primary);"><?= htmlspecialchars($sale['created_by_name'] ?? 'System') ?></strong>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.5rem; margin-bottom: 1.5rem;">
                <h6 style="color: var(--text-primary); font-weight: 600; margin-bottom: 1rem;">
                    <i class="fas fa-user me-2"></i>Informasi Customer
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <small style="color: var(--text-secondary); display: block;">Nama</small>
                        <strong style="color: var(--text-primary);"><?= htmlspecialchars($sale['customer_name']) ?></strong>
                    </div>
                    <?php if (!empty($sale['customer_phone'])): ?>
                    <div class="col-md-6">
                        <small style="color: var(--text-secondary); display: block;">No. Telepon</small>
                        <strong style="color: var(--text-primary);"><?= htmlspecialchars($sale['customer_phone']) ?></strong>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($sale['customer_address'])): ?>
                    <div class="col-12">
                        <small style="color: var(--text-secondary); display: block;">Alamat</small>
                        <strong style="color: var(--text-primary);"><?= nl2br(htmlspecialchars($sale['customer_address'])) ?></strong>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Products Table -->
            <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.5rem; margin-bottom: 1.5rem;">
                <h6 style="color: var(--text-primary); font-weight: 600; margin-bottom: 1rem;">
                    <i class="fas fa-box me-2"></i>Produk
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered" style="color: var(--text-primary);">
                        <thead style="background: var(--surface-2);">
                            <tr>
                                <th style="width: 40%;">Produk</th>
                                <th style="width: 20%;" class="text-end">Harga</th>
                                <th style="width: 15%;" class="text-center">Qty</th>
                                <th style="width: 25%;" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                    <?php if (!empty($item['product_sku'])): ?>
                                    <br><small style="color: var(--text-muted);">SKU: <?= htmlspecialchars($item['product_sku']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end"><?= format_currency($item['price']) ?></td>
                                <td class="text-center"><?= $item['quantity'] ?></td>
                                <td class="text-end"><strong><?= format_currency($item['subtotal']) ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes -->
            <?php if (!empty($sale['notes'])): ?>
            <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.5rem;">
                <h6 style="color: var(--text-primary); font-weight: 600; margin-bottom: 1rem;">
                    <i class="fas fa-sticky-note me-2"></i>Catatan
                </h6>
                <p style="color: var(--text-primary); margin: 0;"><?= nl2br(htmlspecialchars($sale['notes'])) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right Column - Summary -->
        <div class="col-lg-4">
            <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.5rem; position: sticky; top: 20px;">
                <h6 style="color: var(--text-primary); font-weight: 600; margin-bottom: 1rem;">
                    <i class="fas fa-calculator me-2"></i>Ringkasan Pembayaran
                </h6>

                <!-- Subtotal -->
                <div class="d-flex justify-content-between mb-2">
                    <span style="color: var(--text-secondary);">Subtotal:</span>
                    <strong style="color: var(--text-primary);"><?= format_currency($sale['subtotal']) ?></strong>
                </div>

                <!-- Tax -->
                <?php if ($sale['tax'] > 0): ?>
                <div class="d-flex justify-content-between mb-2">
                    <span style="color: var(--text-secondary);">Pajak:</span>
                    <strong style="color: var(--text-primary);"><?= format_currency($sale['tax']) ?></strong>
                </div>
                <?php endif; ?>

                <!-- Discount -->
                <?php if ($sale['discount'] > 0): ?>
                <div class="d-flex justify-content-between mb-2">
                    <span style="color: var(--text-secondary);">Diskon:</span>
                    <strong style="color: var(--danger);">- <?= format_currency($sale['discount']) ?></strong>
                </div>
                <?php endif; ?>

                <hr style="border-color: var(--border-color);">

                <!-- Total before admin fee -->
                <div class="d-flex justify-content-between mb-2">
                    <span style="color: var(--text-secondary);">Total:</span>
                    <strong style="color: var(--text-primary);"><?= format_currency($sale['total']) ?></strong>
                </div>

                <!-- Admin Fee (for online) -->
                <?php if ($sale['sale_type'] === 'online' && isset($sale['admin_fee']) && $sale['admin_fee'] > 0): ?>
                <div class="d-flex justify-content-between mb-2">
                    <span style="color: var(--danger);">
                        Admin Fee (<?= $sale['platform_fee_percentage'] ?>%):
                    </span>
                    <strong style="color: var(--danger);">- <?= format_currency($sale['admin_fee']) ?></strong>
                </div>

                <hr style="border-color: var(--border-color);">
                <?php endif; ?>

                <!-- Final Total -->
                <div class="d-flex justify-content-between mb-3">
                    <strong style="color: var(--text-primary); font-size: 1.1rem;">Total Akhir:</strong>
                    <strong style="color: var(--success); font-size: 1.25rem;"><?= format_currency($sale['final_total']) ?></strong>
                </div>

                <!-- Status Badge -->
                <div class="text-center mt-3 p-3" style="background: var(--surface-2); border-radius: 8px;">
                    <small style="color: var(--text-secondary); display: block; margin-bottom: 0.5rem;">Status Penjualan</small>
                    <span class="badge badge-lg" style="background: var(--success); color: white; font-size: 1rem; padding: 0.5rem 1rem;">
                        <i class="fas fa-check-circle me-1"></i>Selesai
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
