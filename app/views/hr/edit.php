<div class="fade-in">
    <!-- Header -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--text-primary); font-family: 'Poppins', sans-serif;">Edit Data Karyawan</h2>
                    <p class="mb-0" style="color: var(--text-tertiary); font-size: 0.9rem;">Update informasi karyawan</p>
                </div>
                <a href="<?= base_url('hr') ?>" class="btn-custom btn-secondary-custom">
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
                    <h5>Informasi Karyawan</h5>
                    <span class="badge-custom badge-primary"><?= $employee['employee_id'] ?></span>
                </div>
                <div class="p-4">
                    <form action="<?= base_url('hr/employee/update/' . $employee['id']) ?>" method="POST" enctype="multipart/form-data">
                        <div class="row g-4">
                            <!-- Current Photo -->
                            <?php if ($employee['photo']): ?>
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-3 p-3" style="background: var(--surface-2); border-radius: 12px;">
                                    <img src="<?= base_url('storage/uploads/' . $employee['photo']) ?>" 
                                         class="rounded-circle" 
                                         width="80" 
                                         height="80" 
                                         style="object-fit: cover; border: 3px solid var(--border-color);" 
                                         alt="Foto">
                                    <div>
                                        <p class="mb-1 fw-semibold" style="color: var(--text-primary);">Foto Saat Ini</p>
                                        <p class="mb-0 small" style="color: var(--text-tertiary);">Upload foto baru untuk mengganti</p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Personal Information -->
                            <div class="col-12">
                                <h6 class="mb-3 fw-semibold" style="color: var(--text-primary);">
                                    <i class="fas fa-user me-2" style="color: var(--primary-color);"></i>Data Pribadi
                                </h6>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control-custom" value="<?= htmlspecialchars($employee['full_name']) ?>" required>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom">Email</label>
                                <input type="email" name="email" class="form-control-custom" value="<?= htmlspecialchars($employee['email'] ?? '') ?>">
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom">Telepon</label>
                                <input type="text" name="phone" class="form-control-custom" value="<?= htmlspecialchars($employee['phone'] ?? '') ?>">
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom">Foto</label>
                                <input type="file" name="photo" class="form-control-custom" accept="image/*">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label-custom">Alamat</label>
                                <textarea name="address" class="form-control-custom" rows="3"><?= htmlspecialchars($employee['address'] ?? '') ?></textarea>
                            </div>
                            
                            <!-- Work Information -->
                            <div class="col-12 mt-4">
                                <h6 class="mb-3 fw-semibold" style="color: var(--text-primary);">
                                    <i class="fas fa-briefcase me-2" style="color: var(--primary-color);"></i>Data Pekerjaan
                                </h6>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom">Posisi <span class="text-danger">*</span></label>
                                <input type="text" name="position" class="form-control-custom" value="<?= htmlspecialchars($employee['position'] ?? '') ?>" required>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom">Departemen</label>
                                <select name="department" class="form-control-custom">
                                    <option value="">Pilih Departemen</option>
                                    <option value="Produksi" <?= ($employee['department'] ?? '') === 'Produksi' ? 'selected' : '' ?>>Produksi</option>
                                    <option value="Admin" <?= ($employee['department'] ?? '') === 'Admin' ? 'selected' : '' ?>>Admin</option>
                                    <option value="Keuangan" <?= ($employee['department'] ?? '') === 'Keuangan' ? 'selected' : '' ?>>Keuangan</option>
                                    <option value="Gudang" <?= ($employee['department'] ?? '') === 'Gudang' ? 'selected' : '' ?>>Gudang</option>
                                    <option value="Marketing" <?= ($employee['department'] ?? '') === 'Marketing' ? 'selected' : '' ?>>Marketing</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom">Tanggal Masuk</label>
                                <input type="date" name="hire_date" class="form-control-custom" value="<?= $employee['hire_date'] ?? '' ?>">
                            </div>
                            
                            
                            <div class="col-12 col-md-6">
                                <label class="form-label-custom">Status</label>
                                <select name="status" class="form-control-custom">
                                    <option value="active" <?= ($employee['status'] ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="inactive" <?= ($employee['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                                    <option value="resigned" <?= ($employee['status'] ?? '') === 'resigned' ? 'selected' : '' ?>>Resign</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end mt-4 pt-4" style="border-top: 1px solid var(--border-color);">
                            <a href="<?= base_url('hr') ?>" class="btn-custom btn-secondary-custom">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn-custom btn-primary-custom">
                                <i class="fas fa-save me-2"></i>Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
