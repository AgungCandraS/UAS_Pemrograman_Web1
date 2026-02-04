<div class="fade-in">
    <!-- Header Section -->
    <div class="mb-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            <div>
                <h1 class="fw-800 mb-2" style="color: var(--text-primary); font-family: 'Poppins', sans-serif; font-size: 2.2rem;">✏️ Edit Produk</h1>
                <p class="mb-0" style="color: var(--text-secondary); font-size: 1rem;">Perbarui informasi produk "<strong><?= $product['name'] ?></strong>"</p>
            </div>
            <a href="<?= base_url('inventory') ?>" class="btn-custom btn-secondary-custom" style="padding: 0.875rem 1.5rem;">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Inventory
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);">
        <!-- Form Header -->
        <div style="background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%); padding: 1.5rem; color: white;">
            <h5 style="margin: 0; font-weight: 600; font-size: 1.1rem;">📋 Update Data Produk</h5>
        </div>

        <!-- Form Content -->
        <div style="padding: 2rem;">
            <form action="<?= base_url('inventory/update/' . $product['id']) ?>" method="POST" enctype="multipart/form-data" id="productForm">
                <div class="row g-4">
                    <!-- Left Column: Main Info -->
                    <div class="col-12 col-lg-8">
                        <!-- Product Name Section -->
                        <div style="background: var(--surface-2); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h6 style="color: var(--primary); font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; font-size: 0.85rem;">Informasi Dasar</h6>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label-modern">Nama Produk <span style="color: var(--danger);">*</span></label>
                                    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required placeholder="Contoh: Laptop Dell XPS 15" class="form-input-modern">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label-modern">SKU (Tidak Dapat Diubah)</label>
                                    <input type="text" value="<?= htmlspecialchars($product['sku']) ?>" readonly class="form-input-modern" style="background: var(--surface-3); cursor: not-allowed; opacity: 0.7;">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label-modern">Kategori <span style="color: var(--danger);">*</span></label>
                                    <select name="category_id" required class="form-input-modern">
                                        <option value="">Pilih Kategori</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label-modern">Deskripsi Produk</label>
                                    <textarea name="description" rows="4" placeholder="Deskripsi detail tentang produk..." class="form-input-modern"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Section -->
                        <div style="background: var(--surface-2); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h6 style="color: var(--success); font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; font-size: 0.85rem;">💰 Harga & Biaya</h6>
                            
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label-modern">Harga Modal (Cost Price) <span style="color: var(--danger);">*</span></label>
                                    <div style="position: relative;">
                                        <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-secondary);">Rp</span>
                                        <input type="number" name="cost_price" value="<?= $product['cost_price'] ?>" required placeholder="0" min="0" step="0.01" class="form-input-modern" style="padding-left: 2.5rem;">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label-modern">Harga Jual (Selling Price) <span style="color: var(--danger);">*</span></label>
                                    <div style="position: relative;">
                                        <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-secondary);">Rp</span>
                                        <input type="number" name="price" value="<?= $product['price'] ?>" required placeholder="0" min="0" step="0.01" class="form-input-modern" style="padding-left: 2.5rem;">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 8px; padding: 1rem; margin-top: 0.5rem;">
                                        <p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem;">Margin Keuntungan: <strong id="marginDisplay" style="color: var(--success);"><?php $margin = ($product['price'] > 0 && $product['cost_price'] > 0) ? (($product['price'] - $product['cost_price']) / $product['cost_price'] * 100) : 0; echo number_format($margin, 1); ?>%</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Section -->
                        <div style="background: var(--surface-2); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem;">
                            <h6 style="color: var(--warning); font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; font-size: 0.85rem;">📦 Stok & Unit</h6>
                            
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label-modern">Stok <span style="color: var(--danger);">*</span></label>
                                    <input type="number" name="stock" value="<?= $product['stock'] ?>" required placeholder="0" min="0" class="form-input-modern">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label-modern">Stok Minimum (Alert) <span style="color: var(--danger);">*</span></label>
                                    <input type="number" name="min_stock" value="<?= $product['min_stock'] ?>" required placeholder="10" min="0" class="form-input-modern">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label-modern">Satuan <span style="color: var(--danger);">*</span></label>
                                    <select name="unit" required class="form-input-modern">
                                        <option value="">Pilih Satuan</option>
                                        <option value="pcs" <?= $product['unit'] == 'pcs' ? 'selected' : '' ?>>Pcs (Pieces)</option>
                                        <option value="box" <?= $product['unit'] == 'box' ? 'selected' : '' ?>>Box</option>
                                        <option value="kg" <?= $product['unit'] == 'kg' ? 'selected' : '' ?>>Kg (Kilogram)</option>
                                        <option value="liter" <?= $product['unit'] == 'liter' ? 'selected' : '' ?>>Liter</option>
                                        <option value="meter" <?= $product['unit'] == 'meter' ? 'selected' : '' ?>>Meter</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label-modern">Status Produk <span style="color: var(--danger);">*</span></label>
                                    <select name="status" required class="form-input-modern">
                                        <option value="active" <?= $product['status'] == 'active' ? 'selected' : '' ?>>✓ Aktif (Dapat Dijual)</option>
                                        <option value="inactive" <?= $product['status'] == 'inactive' ? 'selected' : '' ?>>✗ Nonaktif (Arsip)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Image Section -->
                    <div class="col-12 col-lg-4">
                        <!-- Current Image -->
                        <?php if (!empty($product['image'])): ?>
                            <div style="background: var(--surface-2); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
                                <h6 style="color: var(--info); font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; font-size: 0.85rem;">Gambar Saat Ini</h6>
                                <div style="position: relative; margin-bottom: 1rem;">
                                    <img src="<?= base_url('storage/uploads/' . htmlspecialchars($product['image'])) ?>" 
                                         alt="Product Image" 
                                         style="width: 100%; border-radius: 8px; object-fit: cover; border: 2px solid var(--border-color);">
                                    <div style="position: absolute; top: 0.5rem; right: 0.5rem; background: var(--success); color: white; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                                        CURRENT
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Image Upload Section -->
                        <div style="background: var(--surface-2); border: 2px dashed var(--border-color); border-radius: 12px; padding: 2rem; text-align: center; transition: all 0.3s ease;" id="dropZone">
                            <div id="imagePreviewContainer">
                                <i class="fas fa-image" style="font-size: 2.5rem; color: var(--text-muted); margin-bottom: 1rem; display: block;"></i>
                                <p style="color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Pilih Gambar Baru</p>
                                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">Drag & Drop atau Klik</p>
                                <input type="file" name="image" accept="image/*" id="imageInput" style="display: none;">
                                <button type="button" class="btn-custom btn-primary-custom" style="padding: 0.625rem 1.25rem;" onclick="document.getElementById('imageInput').click();">
                                    Pilih File
                                </button>
                            </div>
                            <div id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" style="max-width: 100%; max-height: 250px; border-radius: 8px; margin-bottom: 1rem;">
                                <button type="button" class="btn-custom btn-danger-custom" style="padding: 0.5rem 1rem;" onclick="resetImage();">
                                    Ubah Gambar
                                </button>
                            </div>
                        </div>

                        <!-- Summary Card -->
                        <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); border-radius: 12px; padding: 1.5rem; color: white; margin-top: 1.5rem;">
                            <h6 style="margin: 0 0 1rem 0; font-weight: 600;">📊 Ringkasan Produk</h6>
                            <div style="font-size: 0.9rem; line-height: 1.8;">
                                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.2);">
                                    <span>Nama:</span>
                                    <span id="summaryName" style="font-weight: 600;"><?= htmlspecialchars(substr($product['name'], 0, 15)) ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.2);">
                                    <span>SKU:</span>
                                    <span id="summarySKU" style="font-weight: 600;"><?= htmlspecialchars($product['sku']) ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.2);">
                                    <span>Harga:</span>
                                    <span id="summaryPrice" style="font-weight: 600;">Rp<?= number_format($product['price'], 0, ',', '.') ?></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
                                    <span>Stok:</span>
                                    <span id="summaryStock" style="font-weight: 600;"><?= $product['stock'] ?> <?= htmlspecialchars($product['unit']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="<?= base_url('inventory') ?>" class="btn-custom btn-secondary-custom" style="padding: 0.875rem 1.75rem;">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn-custom btn-primary-custom" style="padding: 0.875rem 2rem; font-weight: 600;">
                        <i class="fas fa-check me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-label-modern {
    display: block;
    margin-bottom: 0.625rem;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.form-input-modern {
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: var(--surface-1);
    color: var(--text-primary);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-family: 'Inter', sans-serif;
}

.form-input-modern::placeholder {
    color: var(--text-muted);
}

.form-input-modern:hover {
    border-color: var(--border-light);
    background: var(--surface-2);
}

.form-input-modern:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    background: var(--surface-1);
}

select.form-input-modern {
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1.25rem;
    padding-right: 2.5rem;
    color: var(--text-primary);
}

#dropZone {
    cursor: pointer;
}

#dropZone:hover {
    border-color: var(--primary);
    background: rgba(99, 102, 241, 0.05);
}

#dropZone.dragover {
    border-color: var(--primary);
    background: rgba(99, 102, 241, 0.1);
}

@media (max-width: 768px) {
    .form-input-modern {
        font-size: 16px;
    }
}
</style>

<?php
$additionalScripts = '
<script>
const imageInput = document.getElementById("imageInput");
const dropZone = document.getElementById("dropZone");
const imagePreviewContainer = document.getElementById("imagePreviewContainer");
const imagePreview = document.getElementById("imagePreview");
const previewImg = document.getElementById("previewImg");

// Drag and drop
dropZone.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZone.classList.add("dragover");
});

dropZone.addEventListener("dragleave", () => {
    dropZone.classList.remove("dragover");
});

dropZone.addEventListener("drop", (e) => {
    e.preventDefault();
    dropZone.classList.remove("dragover");
    const files = e.dataTransfer.files;
    if (files.length) {
        imageInput.files = files;
        handleImageSelect();
    }
});

// Click to upload
dropZone.addEventListener("click", () => {
    imageInput.click();
});

imageInput.addEventListener("change", handleImageSelect);

function handleImageSelect() {
    const file = imageInput.files[0];
    if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            imagePreviewContainer.style.display = "none";
            imagePreview.style.display = "block";
        };
        reader.readAsDataURL(file);
    }
}

function resetImage() {
    imageInput.value = "";
    imagePreviewContainer.style.display = "block";
    imagePreview.style.display = "none";
}

// Real-time form summary
const form = document.getElementById("productForm");
const inputs = form.querySelectorAll("input, select, textarea");

inputs.forEach(input => {
    input.addEventListener("input", updateSummary);
    input.addEventListener("change", updateSummary);
});

function updateSummary() {
    const name = form.querySelector("[name=\"name\"]").value || "-";
    const sku = form.querySelector("[name=\"sku\"]").value || "-";
    const price = parseFloat(form.querySelector("[name=\"price\"]").value) || 0;
    const costPrice = parseFloat(form.querySelector("[name=\"cost_price\"]").value) || 0;
    const stock = form.querySelector("[name=\"stock\"]").value || "-";
    const unit = form.querySelector("[name=\"unit\"]").value || "";

    document.getElementById("summaryName").textContent = name.substring(0, 15);
    document.getElementById("summarySKU").textContent = sku;
    document.getElementById("summaryPrice").textContent = "Rp" + (price ? price.toLocaleString("id-ID") : "0");
    document.getElementById("summaryStock").textContent = stock + " " + unit;

    if (price > 0 && costPrice > 0) {
        const margin = ((price - costPrice) / costPrice * 100).toFixed(1);
        document.getElementById("marginDisplay").textContent = margin + "%";
    } else {
        document.getElementById("marginDisplay").textContent = "-";
    }
}

updateSummary();
</script>
';
?>

