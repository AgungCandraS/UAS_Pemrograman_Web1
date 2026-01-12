<div class="fade-in">
    <!-- Header -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--text-primary); font-family: 'Poppins', sans-serif;">Tambah Produk Baru</h2>
                    <p class="mb-0" style="color: var(--text-tertiary); font-size: 0.9rem;">Lengkapi form di bawah untuk menambah produk</p>
                </div>
                <a href="<?= base_url('inventory') ?>" class="btn-custom btn-secondary-custom">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row g-4">
        <div class="col-12">
            <div class="table-card">
                <div class="table-card-header">
                    <h5>Formulir Produk Baru</h5>
                </div>
                <div class="p-4">
                    <form action="<?= base_url('inventory/store') ?>" method="POST" enctype="multipart/form-data">
                        <div class="row g-4">
                            <!-- Category -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" required class="form-control-custom">
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- SKU -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">SKU <span class="text-danger">*</span></label>
                                <input type="text" name="sku" required placeholder="e.g., PROD-001" class="form-control-custom">
                            </div>

                            <!-- Product Name -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="name" required placeholder="Masukkan nama produk" class="form-control-custom">
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Deskripsi</label>
                                <textarea name="description" rows="3" placeholder="Deskripsi produk..." class="form-control-custom"></textarea>
                            </div>

                            <!-- Cost Price -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Harga Modal <span class="text-danger">*</span></label>
                                <input type="number" name="cost_price" required placeholder="0" min="0" step="0.01" class="form-control-custom">
                            </div>

                            <!-- Selling Price -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Harga Jual <span class="text-danger">*</span></label>
                                <input type="number" name="price" required placeholder="0" min="0" step="0.01" class="form-control-custom">
                            </div>

                            <!-- Stock -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Stok Awal <span class="text-danger">*</span></label>
                                <input type="number" name="stock" required placeholder="0" min="0" class="form-control-custom">
                            </div>

                            <!-- Min Stock -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Stok Minimum <span class="text-danger">*</span></label>
                                <input type="number" name="min_stock" required placeholder="10" min="0" value="10" class="form-control-custom">
                            </div>

                            <!-- Unit -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Satuan <span class="text-danger">*</span></label>
                                <select name="unit" required class="form-control-custom">
                                    <option value="pcs">Pcs</option>
                                    <option value="box">Box</option>
                                    <option value="kg">Kg</option>
                                    <option value="liter">Liter</option>
                                    <option value="meter">Meter</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Status <span class="text-danger">*</span></label>
                                <select name="status" required class="form-control-custom">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <!-- Image -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Gambar Produk</label>
                                <input type="file" name="image" accept="image/*" class="form-control-custom">
                                <small class="text-muted">Max 5MB, format: JPG, PNG, GIF</small>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12">
                                <hr style="border-color: var(--border-color);">
                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <a href="<?= base_url('inventory') ?>" class="btn-custom btn-secondary-custom">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn-custom btn-primary-custom">
                                        <i class="fas fa-save me-2"></i>Simpan Produk
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
</style>
