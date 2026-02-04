<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 style="color: var(--text-primary); font-weight: 700; margin: 0;">
                <i class="fas fa-cog me-2" style="color: var(--primary);"></i>Pengaturan Potongan Admin
            </h4>
            <p style="color: var(--text-secondary); margin: 0; margin-top: 0.5rem;">
                Kelola platform online dan potongan admin fee
            </p>
        </div>
        <div>
            <a href="<?= base_url('sales') ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-2"></i>Tambah Platform
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
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

    <!-- Settings Table -->
    <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; padding: 1.5rem;">
        <div class="table-responsive">
            <table class="table table-hover" style="color: var(--text-primary);">
                <thead style="background: var(--surface-2);">
                    <tr>
                        <th style="width: 25%;">Platform</th>
                        <th style="width: 30%;">Deskripsi</th>
                        <th style="width: 20%;">Potongan</th>
                        <th style="width: 10%;" class="text-center">Status</th>
                        <th style="width: 15%;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($settings)): ?>
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 2rem;">
                                <i class="fas fa-inbox fa-3x mb-3" style="color: var(--text-muted);"></i>
                                <p style="color: var(--text-secondary);">Belum ada pengaturan platform</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($settings as $setting): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($setting['name']) ?></strong>
                                </td>
                                <td>
                                    <small style="color: var(--text-secondary);">
                                        <?= htmlspecialchars($setting['description'] ?? '-') ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if (!empty($setting['fee_percentage'])): ?>
                                        <span class="badge" style="background: var(--warning); color: white;">
                                            <i class="fas fa-percent me-1"></i><?= $setting['fee_percentage'] ?>%
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($setting['fee_fixed'])): ?>
                                        <span class="badge" style="background: var(--info); color: white;">
                                            <i class="fas fa-coins me-1"></i><?= format_currency($setting['fee_fixed']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="<?= base_url('sales/admin-fee-settings/toggle/' . $setting['id']) ?>" style="display: inline;">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" 
                                                   <?= $setting['is_active'] ? 'checked' : '' ?>
                                                   onchange="this.form.submit()">
                                        </div>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-warning me-1" 
                                            onclick="editSetting(<?= htmlspecialchars(json_encode($setting)) ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="<?= base_url('sales/admin-fee-settings/delete/' . $setting['id']) ?>" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Yakin ingin menghapus platform ini?')">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Box -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="alert" style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                <h6 style="color: var(--primary);"><i class="fas fa-info-circle me-2"></i>Tentang Potongan Admin</h6>
                <ul style="margin: 0; padding-left: 1.5rem;">
                    <li><strong>Persentase:</strong> Potongan berdasarkan % dari total penjualan</li>
                    <li><strong>Nominal Tetap:</strong> Potongan dengan nilai tetap per transaksi</li>
                    <li>Kedua jenis potongan dapat digabungkan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--surface-1); color: var(--text-primary);">
            <form method="POST" action="<?= base_url('sales/admin-fee-settings/store') ?>">
                <div class="modal-header" style="border-color: var(--border-color);">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Tambah Platform</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Platform <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="name" class="form-control" required 
                               placeholder="Contoh: Tokopedia, Shopee"
                               style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2" 
                                  placeholder="Deskripsi platform"
                                  style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Potongan Persentase (%)</label>
                                <input type="number" name="fee_percentage" class="form-control" step="0.01" min="0" max="100" value="0"
                                       placeholder="0.00"
                                       style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                                <small style="color: var(--text-muted);">Contoh: 2.5 untuk 2.5%</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Potongan Tetap (Rp)</label>
                                <input type="number" name="fee_fixed" class="form-control" min="0" value="0"
                                       placeholder="0"
                                       style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                                <small style="color: var(--text-muted);">Nominal tetap per transaksi</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="addIsActive">
                        <label class="form-check-label" for="addIsActive">
                            Aktifkan platform ini
                        </label>
                    </div>
                </div>
                <div class="modal-footer" style="border-color: var(--border-color);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--surface-1); color: var(--text-primary);">
            <form method="POST" id="editForm">
                <div class="modal-header" style="border-color: var(--border-color);">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Platform</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Platform <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="name" id="editName" class="form-control" required 
                               style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" id="editDescription" class="form-control" rows="2" 
                                  style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Potongan Persentase (%)</label>
                                <input type="number" name="fee_percentage" id="editFeePercentage" class="form-control" step="0.01" min="0" max="100"
                                       style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                                <small style="color: var(--text-muted);">Contoh: 2.5 untuk 2.5%</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Potongan Tetap (Rp)</label>
                                <input type="number" name="fee_fixed" id="editFeeFixed" class="form-control" min="0"
                                       style="background: var(--surface-2); border: 1px solid var(--border-color); color: var(--text-primary);">
                                <small style="color: var(--text-muted);">Nominal tetap per transaksi</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="editIsActive">
                        <label class="form-check-label" for="editIsActive">
                            Aktifkan platform ini
                        </label>
                    </div>
                </div>
                <div class="modal-footer" style="border-color: var(--border-color);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editSetting(data) {
    document.getElementById('editForm').action = '<?= base_url('sales/admin-fee-settings/update/') ?>' + data.id;
    document.getElementById('editName').value = data.name;
    document.getElementById('editDescription').value = data.description || '';
    document.getElementById('editFeePercentage').value = data.fee_percentage || 0;
    document.getElementById('editFeeFixed').value = data.fee_fixed || 0;
    document.getElementById('editIsActive').checked = data.is_active == 1;
    
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
