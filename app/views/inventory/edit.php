<div class="fade-in">
    <!-- Header -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--text-primary); font-family: 'Poppins', sans-serif;">Edit Produk</h2>
                    <p class="mb-0" style="color: var(--text-tertiary); font-size: 0.9rem;">Perbarui informasi produk</p>
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
                    <h5>Formulir Edit Produk</h5>
                </div>
                <div class="p-4">
                    <form action="<?= base_url('inventory/update/' . $product['id']) ?>" method="POST" enctype="multipart/form-data">
                        <div class="row g-4">
                            <!-- Category -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" required class="form-control-custom">
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                            <?= $cat['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- SKU (Read Only) -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">SKU</label>
                                <input type="text" value="<?= $product['sku'] ?>" readonly class="form-control-custom" style="background: var(--surface-2);">
                            </div>

                            <!-- Product Name -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="<?= $product['name'] ?>" required placeholder="Masukkan nama produk" class="form-control-custom">
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Deskripsi</label>
                                <textarea name="description" rows="3" placeholder="Deskripsi produk..." class="form-control-custom"><?= $product['description'] ?? '' ?></textarea>
                            </div>

                            <!-- Cost Price -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Harga Modal <span class="text-danger">*</span></label>
                                <input type="number" name="cost_price" value="<?= $product['cost_price'] ?>" required placeholder="0" min="0" step="0.01" class="form-control-custom">
                            </div>

                            <!-- Selling Price -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Harga Jual <span class="text-danger">*</span></label>
                                <input type="number" name="price" value="<?= $product['price'] ?>" required placeholder="0" min="0" step="0.01" class="form-control-custom">
                            </div>

                            <!-- Stock -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stock" value="<?= $product['stock'] ?>" required placeholder="0" min="0" class="form-control-custom">
                            </div>

                            <!-- Min Stock -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Stok Minimum <span class="text-danger">*</span></label>
                                <input type="number" name="min_stock" value="<?= $product['min_stock'] ?>" required placeholder="10" min="0" class="form-control-custom">
                            </div>

                            <!-- Unit -->
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Satuan <span class="text-danger">*</span></label>
                                <select name="unit" required class="form-control-custom">
                                    <option value="pcs" <?= $product['unit'] == 'pcs' ? 'selected' : '' ?>>Pcs</option>
                                    <option value="box" <?= $product['unit'] == 'box' ? 'selected' : '' ?>>Box</option>
                                    <option value="kg" <?= $product['unit'] == 'kg' ? 'selected' : '' ?>>Kg</option>
                                    <option value="liter" <?= $product['unit'] == 'liter' ? 'selected' : '' ?>>Liter</option>
                                    <option value="meter" <?= $product['unit'] == 'meter' ? 'selected' : '' ?>>Meter</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">Status <span class="text-danger">*</span></label>
                                <select name="status" required class="form-control-custom">
                                    <option value="active" <?= $product['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= $product['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>

                            <!-- Current Image -->
                            <?php if (!empty($product['image'])): ?>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold" style="color: var(--text-primary);">Gambar Saat Ini</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= base_url('storage/uploads/' . $product['image']) ?>" 
                                             alt="Product Image" 
                                             class="rounded" 
                                             style="width: 80px; height: 80px; object-fit: cover; border: 2px solid var(--border-color);">
                                        <span class="text-muted small">Unggah gambar baru untuk mengganti</span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Image Upload -->
                            <div class="col-12 <?= !empty($product['image']) ? 'col-md-6' : '' ?>">
                                <label class="form-label fw-semibold" style="color: var(--text-primary);">
                                    <?= !empty($product['image']) ? 'Gambar Baru' : 'Gambar Produk' ?>
                                </label>
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
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
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
