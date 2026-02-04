<div class="fade-in">
    <!-- Header -->
    <div class="mb-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            <div>
                <h1 class="fw-800 mb-2" style="color: var(--text-primary); font-family: 'Poppins', sans-serif; font-size: 2.2rem;">💰 Keuangan</h1>
                <p class="mb-0" style="color: var(--text-secondary); font-size: 1rem;">Ringkas, pantau, dan kelola arus kas bisnis Anda</p>
            </div>
            <a href="<?= base_url('finance/create') ?>" class="inv-btn inv-btn-primary" style="padding: 0.875rem 1.5rem; font-weight: 600; text-decoration: none;">
                <i class="fas fa-plus me-2"></i>Tambah Transaksi
            </a>
        </div>
    </div>
    
    <!-- Financial Summary Cards -->
    <div class="finance-stats-grid" style="margin-bottom: 2rem;">
        <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-arrow-up" style="font-size: 1.5rem; color: var(--success); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Total Pemasukan</p>
            <p style="color: var(--text-primary); font-size: 1.8rem; font-weight: 700; margin: 0;">
                <?= format_currency($summary['income'] ?? 0) ?>
            </p>
        </div>
        
        <div style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.15) 100%); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-arrow-down" style="font-size: 1.5rem; color: var(--danger); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Total Pengeluaran</p>
            <p style="color: var(--text-primary); font-size: 1.8rem; font-weight: 700; margin: 0;">
                <?= format_currency($summary['expense'] ?? 0) ?>
            </p>
        </div>
        
        <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-chart-line" style="font-size: 1.5rem; color: var(--primary); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Total Saldo</p>
            <p style="color: var(--text-primary); font-size: 1.8rem; font-weight: 700; margin: 0;">
                <?= format_currency(($summary['income'] ?? 0) - ($summary['expense'] ?? 0)) ?>
            </p>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="mb-4">
        <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">
            <form method="GET" class="finance-filter-form">
                <div class="finance-filter-grid">
                    <div>
                        <label class="form-label-modern">Tipe Transaksi</label>
                        <select name="type" class="form-control-modern">
                            <option value="">Semua Tipe</option>
                            <option value="income" <?= get('type') === 'income' ? 'selected' : '' ?>>Pemasukan</option>
                            <option value="expense" <?= get('type') === 'expense' ? 'selected' : '' ?>>Pengeluaran</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label-modern">Dari Tanggal</label>
                        <input type="date" name="date_from" class="form-control-modern" value="<?= get('date_from', '') ?>">
                    </div>
                    <div>
                        <label class="form-label-modern">Sampai Tanggal</label>
                        <input type="date" name="date_to" class="form-control-modern" value="<?= get('date_to', '') ?>">
                    </div>
                    <div style="align-self: end;">
                        <button type="submit" class="inv-btn inv-btn-primary" style="width: 100%;">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Transactions Table -->
    <div class="row g-4">
        <div class="col-12">
            <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">
                <div style="background: linear-gradient(90deg, var(--surface-2) 0%, var(--surface-3) 100%); padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
                    <h5 style="color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1rem;">Riwayat Transaksi</h5>
                    <div class="dropdown">
                        <button class="inv-btn inv-btn-success dropdown-toggle" type="button" id="dropdownExportFinance" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-export me-2"></i>Export
                        </button>
                        <ul class="dropdown-menu dropdown-menu-modern" aria-labelledby="dropdownExportFinance">
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
                                            <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                                <a href="<?= base_url('finance/edit/' . $transaction['id']) ?>" class="action-btn action-btn-edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="confirmAction('Yakin hapus transaksi ini?', '<?= base_url('finance/delete/' . $transaction['id']) ?>')" class="action-btn action-btn-delete" title="Hapus">
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
/* Finance stats grid */
.finance-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
}

/* Filter layout */
.finance-filter-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
    align-items: end;
}

.form-label-modern {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--text-secondary);
}

.form-control-modern {
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: var(--surface-2);
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.form-control-modern:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
}

.form-control-modern::placeholder {
    color: var(--text-muted);
}

/* Buttons */
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

.inv-btn-primary {
    background: var(--primary);
    color: white;
}

.inv-btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
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

/* Dropdown Menu Modern */
.dropdown-menu-modern {
    background: var(--surface-1) !important;
    border: 1px solid var(--border-color) !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
    padding: 0.5rem 0 !important;
    min-width: 160px;
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
    padding-left: 1.25rem;
}

/* Action buttons */
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
    transform: scale(1.08);
}

.action-btn-delete {
    background: rgba(239, 68, 68, 0.15);
    color: var(--danger);
}

.action-btn-delete:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: scale(1.08);
}

@media (max-width: 1024px) {
    .finance-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 768px) {
    .finance-stats-grid {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    .finance-filter-grid {
        grid-template-columns: 1fr;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
}
</style>
