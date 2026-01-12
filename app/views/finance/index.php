<div class="fade-in">
    <!-- Header -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--text-primary); font-family: 'Poppins', sans-serif;">Keuangan</h2>
                    <p class="mb-0" style="color: var(--text-tertiary); font-size: 0.9rem;">Kelola pemasukan dan pengeluaran bisnis Anda</p>
                </div>
                <a href="<?= base_url('finance/create') ?>" class="btn-custom btn-primary-custom">
                    <i class="fas fa-plus me-2"></i>Tambah Transaksi
                </a>
            </div>
        </div>
    </div>
    
    <!-- Financial Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="stat-card hover-lift">
                <div class="stat-card-icon success mb-3">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <p class="stat-card-title">Total Pemasukan</p>
                <h2 class="stat-card-value text-success"><?= format_currency($summary['income'] ?? 0) ?></h2>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="stat-card hover-lift">
                <div class="stat-card-icon warning mb-3">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <p class="stat-card-title">Total Pengeluaran</p>
                <h2 class="stat-card-value text-danger"><?= format_currency($summary['expense'] ?? 0) ?></h2>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="stat-card hover-lift">
                <div class="stat-card-icon info mb-3">
                    <i class="fas fa-chart-line"></i>
                </div>
                <p class="stat-card-title">Keuntungan Bersih</p>
                <h2 class="stat-card-value text-primary"><?= format_currency(($summary['income'] ?? 0) - ($summary['expense'] ?? 0)) ?></h2>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="table-card">
                <div class="p-3">
                    <form method="GET">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6 col-lg-3">
                                <select name="type" class="form-control-custom">
                                    <option value="">Semua Tipe</option>
                                    <option value="income" <?= get('type') === 'income' ? 'selected' : '' ?>>Pemasukan</option>
                                    <option value="expense" <?= get('type') === 'expense' ? 'selected' : '' ?>>Pengeluaran</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <input type="date" name="date_from" class="form-control-custom" value="<?= get('date_from', '') ?>" placeholder="Dari Tanggal">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <input type="date" name="date_to" class="form-control-custom" value="<?= get('date_to', '') ?>" placeholder="Sampai Tanggal">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <button type="submit" class="btn-custom btn-primary-custom w-100">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Transactions Table -->
    <div class="row g-4">
        <div class="col-12">
            <div class="table-card">
                <div class="table-card-header">
                    <h5>Riwayat Transaksi</h5>
                    <div class="dropdown">
                        <button class="btn-custom btn-success-custom dropdown-toggle" type="button" id="dropdownExportFinance" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-export me-2"></i>Export
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownExportFinance">
                            <li><a class="dropdown-item" href="<?= base_url('finance/export?format=excel&type=' . get('type', '') . '&date_from=' . get('date_from', '') . '&date_to=' . get('date_to', '')) ?>">
                                <i class="fas fa-file-excel text-success me-2"></i>Export Excel
                            </a></li>
                            <li><a class="dropdown-item" href="<?= base_url('finance/export?format=pdf&type=' . get('type', '') . '&date_from=' . get('date_from', '') . '&date_to=' . get('date_to', '')) ?>">
                                <i class="fas fa-file-pdf text-danger me-2"></i>Export PDF
                            </a></li>
                        </ul>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="custom-table" id="transactionsTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Metode Bayar</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($transactions)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5" style="color: var(--text-tertiary);">
                                        <i class="fas fa-receipt fa-3x mb-3" style="opacity: 0.3;"></i>
                                        <p class="mb-0">Tidak ada transaksi</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td data-label="Tanggal"><?= format_date($transaction['transaction_date']) ?></td>
                                        <td data-label="Tipe">
                                            <span class="badge-custom <?= $transaction['type'] === 'income' ? 'badge-success' : 'badge-danger' ?>">
                                                <i class="fas fa-<?= $transaction['type'] === 'income' ? 'arrow-up' : 'arrow-down' ?> me-1"></i>
                                                <?= $transaction['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran' ?>
                                            </span>
                                        </td>
                                        <td data-label="Kategori" style="color: var(--text-secondary);"><?= $transaction['category'] ?></td>
                                        <td data-label="Deskripsi" class="d-none d-lg-table-cell" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= $transaction['description'] ?>">
                                            <?= $transaction['description'] ?>
                                        </td>
                                        <td data-label="Metode" class="d-none d-md-table-cell">
                                            <?php
                                            $icons = [
                                                'cash' => 'fa-money-bill-wave',
                                                'transfer' => 'fa-exchange-alt',
                                                'credit_card' => 'fa-credit-card',
                                                'e-wallet' => 'fa-wallet'
                                            ];
                                            ?>
                                            <i class="fas <?= $icons[$transaction['payment_method']] ?? 'fa-money-bill' ?> me-1" style="color: var(--primary);"></i>
                                            <span style="color: var(--text-secondary);"><?= ucfirst(str_replace('_', ' ', $transaction['payment_method'])) ?></span>
                                        </td>
                                        <td data-label="Jumlah" class="fw-bold <?= $transaction['type'] === 'income' ? 'text-success' : 'text-danger' ?>">
                                            <?= $transaction['type'] === 'income' ? '+' : '-' ?><?= format_currency($transaction['amount']) ?>
                                        </td>
                                        <td data-label="Aksi">
                                            <div class="d-flex gap-2">
                                                <a href="<?= base_url('finance/edit/' . $transaction['id']) ?>" class="btn btn-sm btn-outline-custom" style="padding: 0.375rem 0.75rem; border: 1px solid var(--primary); color: var(--primary);" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="confirmAction('Yakin hapus transaksi ini?', '<?= base_url('finance/delete/' . $transaction['id']) ?>')" class="btn btn-sm btn-outline-custom" style="padding: 0.375rem 0.75rem; border: 1px solid var(--danger); color: var(--danger);" title="Hapus">
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
            </div>
        </div>
    </div>
</div>

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

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
';
?>

<style>
.form-control-custom {
    width: 100%;
    padding: 0.625rem 0.875rem;
    font-size: 0.9375rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    background: var(--surface-1);
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.form-control-custom:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control-custom::placeholder {
    color: var(--text-muted);
}

.form-label {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.btn-outline-custom {
    background: transparent;
    transition: all 0.3s ease;
}

.btn-outline-custom:hover {
    background: var(--surface-3);
    transform: translateY(-2px);
}

.input-group-text {
    font-weight: 600;
}

@media (max-width: 768px) {
    .custom-table thead {
        display: none;
    }
    
    .custom-table tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        padding: 1rem;
    }
    
    .custom-table tbody td {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border: none;
    }
    
    .custom-table tbody td:before {
        content: attr(data-label);
        font-weight: bold;
        color: var(--text-secondary);
    }
}
</style>
