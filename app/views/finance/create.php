<div class="fade-in">
    <!-- Header -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--text-primary); font-family: 'Poppins', sans-serif;">Tambah Transaksi</h2>
                    <p class="mb-0" style="color: var(--text-tertiary); font-size: 0.9rem;">Lengkapi form di bawah untuk menambah transaksi keuangan</p>
                </div>
                <a href="<?= base_url('finance') ?>" class="btn-custom btn-secondary-custom">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row g-4">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="table-card">
                <div class="table-card-header">
                    <h5>Formulir Transaksi Baru</h5>
                </div>
                <div class="p-4">
                    <form action="<?= base_url('finance/transaction/store') ?>" method="POST">
                        <div class="row g-4">
                            <!-- Tipe Transaksi -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Tipe Transaksi <span class="text-danger">*</span></label>
                                <select name="type" class="form-control-custom" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="income">💰 Pemasukan</option>
                                    <option value="expense">💸 Pengeluaran</option>
                                </select>
                            </div>

                            <!-- Metode Pembayaran -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-control-custom" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="cash">💵 Cash</option>
                                    <option value="transfer">🏦 Transfer Bank</option>
                                    <option value="credit_card">💳 Kartu Kredit</option>
                                    <option value="e-wallet">📱 E-Wallet</option>
                                </select>
                            </div>

                            <!-- Kategori -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="category" class="form-control-custom" required placeholder="Contoh: Penjualan, Gaji, Utilitas">
                            </div>

                            <!-- Tanggal Transaksi -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Tanggal Transaksi <span class="text-danger">*</span></label>
                                <input type="date" name="transaction_date" class="form-control-custom" required value="<?= date('Y-m-d') ?>">
                            </div>

                            <!-- Jumlah -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Jumlah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-secondary); font-weight: 600;">Rp</span>
                                    <input type="number" name="amount" class="form-control-custom" required placeholder="0" min="0" step="0.01" style="border-left: none;">
                                </div>
                                <small class="text-muted">Masukkan nominal tanpa titik atau koma</small>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Deskripsi</label>
                                <textarea name="description" class="form-control-custom" rows="3" placeholder="Deskripsi detail transaksi..."></textarea>
                                <small class="text-muted">Opsional - Tambahkan catatan atau detail transaksi</small>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12">
                                <hr style="border-color: var(--border-color);">
                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <a href="<?= base_url('finance') ?>" class="btn-custom btn-secondary-custom">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn-custom btn-primary-custom">
                                        <i class="fas fa-save me-2"></i>Simpan Transaksi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-label {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

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

.input-group-text {
    border-radius: 0.5rem 0 0 0.5rem;
}

.input-group .form-control-custom {
    border-radius: 0 0.5rem 0.5rem 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .col-lg-8 {
        max-width: 100%;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .btn-custom {
        width: 100%;
    }
    
    .p-4 {
        padding: 1.5rem !important;
    }
}

@media (max-width: 576px) {
    .p-4 {
        padding: 1rem !important;
    }
}
</style>
