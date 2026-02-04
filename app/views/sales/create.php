<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 style="color: var(--text-primary); font-weight: 700; margin: 0;">
                <i class="fas fa-plus-circle me-2" style="color: var(--primary);"></i>Tambah Penjualan
            </h4>
            <p style="color: var(--text-secondary); margin: 0; margin-top: 0.5rem;">
                Buat transaksi penjualan baru (offline/online)
            </p>
        </div>
        <a href="<?= base_url('sales') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <form method="POST" action="<?= base_url('sales/store') ?>" id="saleForm">
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Sale Type & Platform -->
                <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.5rem; margin-bottom: 1.5rem;">
                    <h6 style="color: var(--text-primary); font-weight: 600; margin-bottom: 1rem;">
                        <i class="fas fa-info-circle me-2"></i>Tipe Penjualan
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="color: var(--text-secondary); font-weight: 500;">
                                Tipe Penjualan <span style="color: var(--danger);">*</span>
                            </label>
                            <select name="sale_type" id="saleType" class="form-select" required style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                                <option value="offline">Offline (Tanpa Admin Fee)</option>
                                <option value="online">Online (Dengan Admin Fee)</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="platformWrapper" style="display: none;">
                            <label class="form-label" style="color: var(--text-secondary); font-weight: 500;">
                                Platform <span style="color: var(--danger);">*</span>
                            </label>
                            <select name="admin_fee_setting_id" id="platformSelect" class="form-select" style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                                <option value="">- Pilih Platform -</option>
                                <?php foreach ($adminFeeSettings as $platform): ?>
                                    <option value="<?= $platform['id'] ?>" 
                                            data-percentage="<?= $platform['fee_percentage'] ?>"
                                            data-fixed="<?= $platform['fee_fixed'] ?? 0 ?>">
                                        <?= htmlspecialchars($platform['name']) ?>
                                        <?php
                                        $feeText = [];
                                        if ($platform['fee_percentage'] > 0) {
                                            $feeText[] = $platform['fee_percentage'] . '%';
                                        }
                                        if (isset($platform['fee_fixed']) && $platform['fee_fixed'] > 0) {
                                            $feeText[] = 'Rp ' . number_format($platform['fee_fixed'], 0, ',', '.');
                                        }
                                        if (!empty($feeText)) {
                                            echo ' (' . implode(' + ', $feeText) . ')';
                                        }
                                        ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
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
                            <label class="form-label" style="color: var(--text-secondary); font-weight: 500;">
                                Nama Customer <span style="color: var(--danger);">*</span>
                            </label>
                            <input type="text" name="customer_name" class="form-control" required 
                                   placeholder="Masukkan nama customer"
                                   style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="color: var(--text-secondary); font-weight: 500;">
                                No. Telepon
                            </label>
                            <input type="text" name="customer_phone" class="form-control" 
                                   placeholder="08xxxxxxxxxx"
                                   style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                        </div>
                        <div class="col-12">
                            <label class="form-label" style="color: var(--text-secondary); font-weight: 500;">
                                Alamat
                            </label>
                            <textarea name="customer_address" class="form-control" rows="2" 
                                      placeholder="Alamat lengkap customer"
                                      style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 style="color: var(--text-primary); font-weight: 600; margin: 0;">
                            <i class="fas fa-box me-2"></i>Produk
                        </h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addProductRow()">
                            <i class="fas fa-plus me-1"></i>Tambah Produk
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="productsTable" style="color: var(--text-primary);">
                            <thead style="background: var(--surface-2);">
                                <tr>
                                    <th style="width: 40%;">Produk</th>
                                    <th style="width: 15%;">Harga</th>
                                    <th style="width: 15%;">Qty</th>
                                    <th style="width: 20%;">Subtotal</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="productRows">
                                <!-- Product rows will be added here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column - Summary -->
            <div class="col-lg-4">
                <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.5rem; position: sticky; top: 20px;">
                    <h6 style="color: var(--text-primary); font-weight: 600; margin-bottom: 1rem;">
                        <i class="fas fa-calculator me-2"></i>Ringkasan
                    </h6>

                    <!-- Subtotal -->
                    <div class="d-flex justify-content-between mb-3">
                        <span style="color: var(--text-secondary); font-size: 1.1rem;">Subtotal Produk:</span>
                        <strong style="color: var(--text-primary); font-size: 1.1rem;" id="displaySubtotal">Rp 0</strong>
                    </div>

                    <!-- Admin Fee (for online) -->
                    <div id="adminFeeSection" style="display: none;">
                        <hr style="border-color: var(--border-color); margin: 1rem 0;">
                        <div class="mb-2" style="padding: 0.75rem; background: var(--surface-2); border-radius: 8px;">
                            <div class="d-flex justify-content-between mb-1">
                                <small style="color: var(--text-muted);">Platform:</small>
                                <small style="color: var(--text-muted);" id="platformName">-</small>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span style="color: var(--danger); font-size: 0.9rem;">
                                    Potongan Admin (<span id="adminFeePercentage">0</span>)
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span style="color: var(--text-secondary);">Total Potongan:</span>
                                <strong style="color: var(--danger);" id="displayAdminFee">- Rp 0</strong>
                            </div>
                        </div>
                        <hr style="border-color: var(--border-color); margin: 1rem 0;">
                    </div>

                    <!-- Final Total -->
                    <div class="d-flex justify-content-between mb-3" style="padding: 1rem; background: var(--surface-2); border-radius: 8px;">
                        <strong style="color: var(--text-primary); font-size: 1.1rem;">Total Diterima:</strong>
                        <strong style="color: var(--success); font-size: 1.5rem;" id="displayFinalTotal">Rp 0</strong>
                    </div>

                    <!-- Hidden inputs for calculation -->
                    <input type="hidden" name="subtotal" id="subtotalInput" value="0">
                    <input type="hidden" name="tax" value="0">
                    <input type="hidden" name="discount" value="0">
                    <input type="hidden" name="total" id="totalInput" value="0">

                    <!-- Notes -->
                    <div class="mb-3">
                        <label class="form-label" style="color: var(--text-secondary); font-weight: 500;">Catatan:</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="Catatan tambahan..."
                                  style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100" style="padding: 0.75rem;">
                        <i class="fas fa-save me-2"></i>Simpan Penjualan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Product Row Template -->
<template id="productRowTemplate">
    <tr class="product-row" data-row-index="0">
        <td>
            <select class="form-select form-select-sm product-select" required onchange="updateProductInfo(this)" style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                <option value="">- Pilih Produk -</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= $product['id'] ?>" 
                            data-name="<?= htmlspecialchars($product['name']) ?>"
                            data-sku="<?= htmlspecialchars($product['sku']) ?>"
                            data-price="<?= $product['price'] ?>"
                            data-stock="<?= $product['stock'] ?>">
                        <?= htmlspecialchars($product['name']) ?> (Stok: <?= $product['stock'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" class="product-id-input">
            <input type="hidden" class="product-name">
            <input type="hidden" class="product-sku">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm price-input" readonly 
                   style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm quantity-input" min="1" value="1" required onchange="calculateRowTotal(this)" 
                   style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm subtotal-input" readonly 
                   style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeProductRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>
// Sale type change handler
document.getElementById('saleType').addEventListener('change', function() {
    const platformWrapper = document.getElementById('platformWrapper');
    const platformSelect = document.getElementById('platformSelect');
    const adminFeeSection = document.getElementById('adminFeeSection');
    
    if (this.value === 'online') {
        platformWrapper.style.display = 'block';
        platformSelect.required = true;
        adminFeeSection.style.display = 'block';
    } else {
        platformWrapper.style.display = 'none';
        platformSelect.required = false;
        platformSelect.value = '';
        adminFeeSection.style.display = 'none';
    }
    calculateTotal();
});

// Platform change handler
document.getElementById('platformSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const percentage = parseFloat(selectedOption?.dataset.percentage) || 0;
    const fixed = parseFloat(selectedOption?.dataset.fixed) || 0;
    const platformName = selectedOption?.text || '-';
    
    // Build admin fee label
    let feeLabel = '';
    if (percentage > 0 && fixed > 0) {
        feeLabel = percentage + '% + ' + formatCurrency(fixed);
    } else if (percentage > 0) {
        feeLabel = percentage + '%';
    } else if (fixed > 0) {
        feeLabel = formatCurrency(fixed);
    } else {
        feeLabel = '0';
    }
    
    document.getElementById('adminFeePercentage').textContent = feeLabel;
    document.getElementById('platformName').textContent = platformName.split('(')[0].trim();
    calculateTotal();
});

// Add first product row on load
window.addEventListener('DOMContentLoaded', function() {
    addProductRow();
});

let rowCounter = 0;

function addProductRow() {
    const template = document.getElementById('productRowTemplate');
    const clone = template.content.cloneNode(true);
    const row = clone.querySelector('.product-row');
    row.dataset.rowIndex = rowCounter;
    
    // Set proper name attributes with index
    row.querySelector('.product-select').name = `items[${rowCounter}][product_id]`;
    row.querySelector('.product-id-input').name = `items[${rowCounter}][product_id]`;
    row.querySelector('.product-name').name = `items[${rowCounter}][product_name]`;
    row.querySelector('.product-sku').name = `items[${rowCounter}][product_sku]`;
    row.querySelector('.price-input').name = `items[${rowCounter}][price]`;
    row.querySelector('.quantity-input').name = `items[${rowCounter}][quantity]`;
    row.querySelector('.subtotal-input').name = `items[${rowCounter}][subtotal]`;
    
    document.getElementById('productRows').appendChild(clone);
    rowCounter++;
}

function removeProductRow(button) {
    const row = button.closest('.product-row');
    row.remove();
    calculateTotal();
}

function updateProductInfo(select) {
    const row = select.closest('.product-row');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        row.querySelector('.product-id-input').value = option.value;
        row.querySelector('.product-name').value = option.dataset.name;
        row.querySelector('.product-sku').value = option.dataset.sku;
        row.querySelector('.price-input').value = option.dataset.price;
        row.querySelector('.quantity-input').value = 1;
        calculateRowTotal(row.querySelector('.quantity-input'));
    } else {
        row.querySelector('.product-id-input').value = '';
        row.querySelector('.product-name').value = '';
        row.querySelector('.product-sku').value = '';
        row.querySelector('.price-input').value = '';
        row.querySelector('.quantity-input').value = 1;
        row.querySelector('.subtotal-input').value = '';
        calculateTotal();
    }
}

function calculateRowTotal(input) {
    const row = input.closest('.product-row');
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
    const subtotal = price * quantity;
    
    row.querySelector('.subtotal-input').value = subtotal;
    calculateTotal();
}

function calculateTotal() {
    // Calculate subtotal from all product rows
    let subtotal = 0;
    document.querySelectorAll('.subtotal-input').forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });
    
    // Total sama dengan subtotal (tidak ada pajak dan diskon manual)
    const total = subtotal;
    
    // Calculate admin fee if online
    let adminFeeAmount = 0;
    const saleType = document.getElementById('saleType').value;
    if (saleType === 'online') {
        const platformSelect = document.getElementById('platformSelect');
        const selectedOption = platformSelect.options[platformSelect.selectedIndex];
        const feePercentage = parseFloat(selectedOption?.dataset.percentage) || 0;
        const feeFixed = parseFloat(selectedOption?.dataset.fixed) || 0;
        
        // Calculate percentage fee
        adminFeeAmount = (total * feePercentage) / 100;
        
        // Add fixed fee
        adminFeeAmount += feeFixed;
    }
    
    // Calculate final total
    const finalTotal = total - adminFeeAmount;
    
    // Update display
    document.getElementById('displaySubtotal').textContent = formatCurrency(subtotal);
    document.getElementById('displayAdminFee').textContent = '- ' + formatCurrency(adminFeeAmount);
    document.getElementById('displayFinalTotal').textContent = formatCurrency(finalTotal);
    
    // Update hidden inputs
    document.getElementById('subtotalInput').value = subtotal;
    document.getElementById('totalInput').value = total;
}

function formatCurrency(amount) {
    return 'Rp ' + amount.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Form validation
document.getElementById('saleForm').addEventListener('submit', function(e) {
    const saleType = document.getElementById('saleType').value;
    
    // Check if platform is selected for online sales
    if (saleType === 'online') {
        const platformSelect = document.getElementById('platformSelect');
        if (!platformSelect.value) {
            e.preventDefault();
            alert('Pilih platform untuk penjualan online!');
            return false;
        }
    }
    
    const productRows = document.querySelectorAll('.product-row');
    if (productRows.length === 0) {
        e.preventDefault();
        alert('Tambahkan minimal 1 produk!');
        return false;
    }
    
    let hasProduct = false;
    productRows.forEach(row => {
        const select = row.querySelector('.product-select');
        if (select.value) {
            hasProduct = true;
        }
    });
    
    if (!hasProduct) {
        e.preventDefault();
        alert('Pilih minimal 1 produk!');
        return false;
    }
});
</script>
