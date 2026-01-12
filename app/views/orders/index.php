<div class="fade-in">
    <!-- Header -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--text-primary); font-family: 'Poppins', sans-serif;">Manajemen Pesanan</h2>
                    <p class="mb-0" style="color: var(--text-tertiary); font-size: 0.9rem;">Kelola pesanan dan transaksi penjualan</p>
                </div>
                <a href="<?= base_url('orders/create') ?>" class="btn-custom btn-primary-custom">
                    <i class="fas fa-plus me-2"></i>Buat Pesanan Baru
                </a>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card hover-lift">
                <div class="stat-card-icon primary mb-3">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <p class="stat-card-title">Total Pesanan</p>
                <h2 class="stat-card-value"><?= $stats['total_orders'] ?? 0 ?></h2>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card hover-lift">
                <div class="stat-card-icon warning mb-3">
                    <i class="fas fa-clock"></i>
                </div>
                <p class="stat-card-title">Pending</p>
                <h2 class="stat-card-value"><?= $stats['pending'] ?? 0 ?></h2>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card hover-lift">
                <div class="stat-card-icon info mb-3">
                    <i class="fas fa-truck"></i>
                </div>
                <p class="stat-card-title">Processing</p>
                <h2 class="stat-card-value"><?= $stats['processing'] ?? 0 ?></h2>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card hover-lift">
                <div class="stat-card-icon success mb-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <p class="stat-card-title">Delivered</p>
                <h2 class="stat-card-value"><?= $stats['delivered'] ?? 0 ?></h2>
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
                                <label class="form-label-custom small">Status Pesanan</label>
                                <select name="status" class="form-control-custom">
                                    <option value="">Semua Status</option>
                                    <option value="pending" <?= get('status') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="processing" <?= get('status') === 'processing' ? 'selected' : '' ?>>Processing</option>
                                    <option value="shipped" <?= get('status') === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                    <option value="delivered" <?= get('status') === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                    <option value="cancelled" <?= get('status') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label-custom small">Status Pembayaran</label>
                                <select name="payment_status" class="form-control-custom">
                                    <option value="">Semua</option>
                                    <option value="pending" <?= get('payment_status') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="paid" <?= get('payment_status') === 'paid' ? 'selected' : '' ?>>Paid</option>
                                    <option value="partial" <?= get('payment_status') === 'partial' ? 'selected' : '' ?>>Partial</option>
                                    <option value="refunded" <?= get('payment_status') === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-sm-6 col-lg-4">
                                <label class="form-label-custom small">Pencarian</label>
                                <input type="text" name="search" class="form-control-custom" placeholder="Nomor pesanan / Nama customer..." value="<?= get('search', '') ?>">
                            </div>
                            
                            <div class="col-12 col-sm-6 col-lg-2">
                                <label class="form-label-custom small d-none d-lg-block">&nbsp;</label>
                                <button type="submit" class="btn-custom btn-primary-custom w-100">
                                    <i class="fas fa-search me-1"></i>Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="row g-4">
        <div class="col-12">
            <div class="table-card">
        <div class="table-card-header">
            <h5>Daftar Pesanan</h5>
            <div>
                <button class="btn btn-sm btn-outline-success" onclick="exportOrders()">
                    <i class="fas fa-file-excel me-1"></i>Export
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="custom-table" id="ordersTable">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status Pesanan</th>
                        <th>Status Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada data pesanan</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url('orders/detail/' . $order['id']) ?>" class="fw-semibold text-primary text-decoration-none">
                                        <?= $order['order_number'] ?>
                                    </a>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold"><?= $order['customer_name'] ?></div>
                                        <div class="small text-muted"><?= $order['customer_phone'] ?? '-' ?></div>
                                    </div>
                                </td>
                                <td><?= format_datetime($order['created_at']) ?></td>
                                <td class="fw-bold"><?= format_currency($order['total']) ?></td>
                                <td>
                                    <span class="badge-custom <?php
                                        echo match($order['order_status']) {
                                            'pending' => 'badge-warning',
                                            'processing' => 'badge-info',
                                            'shipped' => 'badge-primary',
                                            'delivered' => 'badge-success',
                                            'cancelled' => 'badge-danger',
                                            default => 'badge-secondary'
                                        };
                                    ?>">
                                        <?= ucfirst($order['order_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-custom <?php
                                        echo match($order['payment_status']) {
                                            'pending' => 'badge-warning',
                                            'paid' => 'badge-success',
                                            'partial' => 'badge-info',
                                            'refunded' => 'badge-danger',
                                            default => 'badge-secondary'
                                        };
                                    ?>">
                                        <?= ucfirst($order['payment_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="<?= base_url('orders/detail/' . $order['id']) ?>" class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="updateStatus(<?= $order['id'] ?>)" class="btn btn-sm btn-outline-warning" title="Update Status">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="printInvoice(<?= $order['id'] ?>)" class="btn btn-sm btn-outline-info" title="Print">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <?php if ($order['order_status'] === 'pending'): ?>
                                            <button onclick="deleteOrder(<?= $order['id'] ?>)" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
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
            <div class="p-3 border-top">
                <nav>
                    <ul class="pagination pagination-sm justify-content-center mb-0">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == ($page ?? 1) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&status=<?= get('status', '') ?>&search=<?= get('search', '') ?>">
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

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateStatusForm" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-custom">Status Pesanan</label>
                        <select name="order_status" class="form-control-custom" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Status Pembayaran</label>
                        <select name="payment_status" class="form-control-custom" required>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="partial">Partial</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Catatan</label>
                        <textarea name="notes" class="form-control-custom" rows="3" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-custom btn-primary-custom">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$additionalScripts = '
<script>
const updateStatusModal = new bootstrap.Modal(document.getElementById("updateStatusModal"));

function updateStatus(orderId) {
    const form = document.getElementById("updateStatusForm");
    form.action = "' . base_url('orders/update-status/') . '" + orderId;
    updateStatusModal.show();
}

function deleteOrder(orderId) {
    if (confirm("Yakin ingin menghapus pesanan ini?")) {
        window.location.href = "' . base_url('orders/delete/') . '" + orderId;
    }
}

function printInvoice(orderId) {
    window.open("' . base_url('orders/print/') . '" + orderId, "_blank");
}

function exportOrders() {
    const table = document.getElementById("ordersTable");
    const html = table.outerHTML;
    const url = "data:application/vnd.ms-excel," + encodeURIComponent(html);
    const link = document.createElement("a");
    link.href = url;
    link.download = "pesanan_" + new Date().toISOString().split("T")[0] + ".xls";
    link.click();
}
</script>
';
?>
