<div class="fade-in">
    <!-- Header -->
    <div class="mb-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            <div>
                <h1 class="fw-800 mb-2" style="color: var(--text-primary); font-family: 'Poppins', sans-serif; font-size: 2.2rem;">👥 HR & Manajemen Karyawan</h1>
                <p class="mb-0" style="color: var(--text-secondary); font-size: 1rem;">Kelola karyawan, absensi, produksi, dan payroll secara rapi</p>
            </div>
            <a href="<?= base_url('hr/employee/create') ?>" class="inv-btn inv-btn-primary" style="padding: 0.875rem 1.5rem; font-weight: 600; text-decoration: none;">
                <i class="fas fa-user-plus me-2"></i>Tambah Karyawan
            </a>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="hr-stats-grid" style="margin-bottom: 2rem;">
        <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.15) 100%); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-users" style="font-size: 1.5rem; color: var(--primary); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Total Karyawan</p>
            <p style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin: 0;"><?= $stats['total_employees'] ?? 0 ?></p>
        </div>
        
        <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.15) 100%); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-user-check" style="font-size: 1.5rem; color: var(--success); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Karyawan Aktif</p>
            <p style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin: 0;"><?= $stats['active_employees'] ?? 0 ?></p>
        </div>
        
        <div style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(6, 182, 212, 0.15) 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-calendar-check" style="font-size: 1.5rem; color: var(--info); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Hadir Hari Ini</p>
            <p style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin: 0;"><?= $stats['present_today'] ?? 0 ?></p>
        </div>
        
        <div style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(249, 115, 22, 0.15) 100%); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center;">
            <i class="fas fa-money-bill-wave" style="font-size: 1.5rem; color: var(--warning); margin-bottom: 0.5rem; display: block;"></i>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.5rem; font-weight: 500;">Total Gaji</p>
            <p style="color: var(--text-primary); font-size: 1.6rem; font-weight: 700; margin: 0;"><?= format_currency($stats['total_payroll'] ?? 0) ?></p>
        </div>
    </div>
    
    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <button class="nav-link <?= ($activeTab ?? 'employees') === 'employees' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#employees">
                <i class="fas fa-users me-2"></i>Karyawan
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link <?= ($activeTab ?? 'employees') === 'attendance' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#attendance">
                <i class="fas fa-calendar-check me-2"></i>Absensi
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link <?= ($activeTab ?? 'employees') === 'workrecords' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#workrecords">
                <i class="fas fa-clipboard-list me-2"></i>Catatan Produksi
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link <?= ($activeTab ?? 'employees') === 'payroll' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#payroll">
                <i class="fas fa-money-bill-wave me-2"></i>Payroll
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link <?= ($activeTab ?? 'employees') === 'master-product' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#master-product">
                <i class="fas fa-tags me-2"></i>Master Produk
            </button>
        </li>
    </ul>
    
    <!-- Tab Contents -->
    <div class="tab-content">
        <!-- Employees Tab -->
        <div class="tab-pane fade <?= ($activeTab ?? 'employees') === 'employees' ? 'show active' : '' ?>" id="employees">
            <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">
                <div style="background: linear-gradient(90deg, var(--surface-2) 0%, var(--surface-3) 100%); padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                    <h5 style="color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1rem;"><i class="fas fa-users me-2"></i>Daftar Karyawan</h5>
                    <div style="min-width: 240px;">
                        <input type="text" id="searchEmployee" class="form-control-modern" placeholder="Cari karyawan...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>ID Karyawan</th>
                                <th>Nama</th>
                                <th>Posisi</th>
                                <th>Departemen</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="employeeTableBody">
                            <?php if (empty($employees)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5" style="color: var(--text-tertiary);">
                                        <i class="fas fa-users fa-3x mb-3" style="opacity: 0.3;"></i>
                                        <p class="mb-0">Tidak ada data karyawan</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($employees as $employee): ?>
                                    <tr>
                                        <td data-label="ID" class="fw-semibold" style="color: var(--primary-color);"><?= $employee['employee_id'] ?></td>
                                        <td data-label="Nama">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <?php if ($employee['photo']): ?>
                                                        <img src="<?= base_url('storage/uploads/' . $employee['photo']) ?>" class="rounded-circle" width="40" height="40" style="object-fit: cover; border: 2px solid var(--border-color);" alt="">
                                                    <?php else: ?>
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: var(--surface-2); border: 2px solid var(--border-color);">
                                                            <i class="fas fa-user" style="color: var(--text-tertiary);"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold" style="color: var(--text-primary);"><?= htmlspecialchars($employee['full_name']) ?></div>
                                                    <div class="small" style="color: var(--text-tertiary);"><?= htmlspecialchars($employee['email'] ?? '-') ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="Posisi"><?= htmlspecialchars($employee['position'] ?? '-') ?></td>
                                        <td data-label="Departemen"><?= htmlspecialchars($employee['department'] ?? '-') ?></td>
                                        <td data-label="Status">
                                            <span class="badge-custom <?= $employee['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>">
                                                <?= ucfirst($employee['status']) ?>
                                            </span>
                                        </td>
                                        <td data-label="Aksi">
                                            <div style="display: flex; gap: 0.5rem;">
                                                <a href="<?= base_url('hr/employee/edit/' . $employee['id']) ?>" class="action-btn action-btn-edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= base_url('hr/employee/delete/' . $employee['id']) ?>" class="action-btn action-btn-delete" onclick="return confirm('Yakin ingin menghapus karyawan ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
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
        
        <!-- Attendance Tab -->
        <div class="tab-pane fade <?= ($activeTab ?? 'employees') === 'attendance' ? 'show active' : '' ?>" id="attendance">
            <div class="table-card">
                <div class="table-card-header">
                    <h5><i class="fas fa-calendar-check me-2"></i>Absensi Karyawan - <?= date('d F Y', strtotime($attendanceDate ?? date('Y-m-d'))) ?></h5>
                    <div class="d-flex gap-2">
                        <input type="date" id="attendanceDate" class="form-control-modern" value="<?= $attendanceDate ?? date('Y-m-d') ?>" style="width: 180px;">
                        <button class="inv-btn inv-btn-primary" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
                            <i class="fas fa-plus me-2"></i>Catat Absensi
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($attendance)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5" style="color: var(--text-tertiary);">
                                        <i class="fas fa-calendar-times fa-3x mb-3" style="opacity: 0.3;"></i>
                                        <p class="mb-0">Tidak ada data absensi</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($attendance as $record): ?>
                                    <tr>
                                        <td data-label="Karyawan">
                                            <div class="fw-semibold" style="color: var(--text-primary);"><?= htmlspecialchars($record['full_name']) ?></div>
                                            <div class="small" style="color: var(--text-tertiary);"><?= htmlspecialchars($record['emp_code']) ?></div>
                                        </td>
                                        <td data-label="Check In">
                                            <span class="badge-custom badge-info"><?= $record['check_in'] ?? '-' ?></span>
                                        </td>
                                        <td data-label="Check Out">
                                            <span class="badge-custom badge-success"><?= $record['check_out'] ?? '-' ?></span>
                                        </td>
                                        <td data-label="Status">
                                            <span class="badge-custom <?php
                                                echo match($record['status']) {
                                                    'present' => 'badge-success',
                                                    'late' => 'badge-warning',
                                                    'absent' => 'badge-danger',
                                                    'leave' => 'badge-info',
                                                    'sick' => 'badge-secondary',
                                                    default => 'badge-primary'
                                                };
                                            ?>">
                                                <?= ucfirst($record['status']) ?>
                                            </span>
                                        </td>
                                        <td data-label="Keterangan"><?= htmlspecialchars($record['notes'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Work Records Tab -->
        <div class="tab-pane fade <?= ($activeTab ?? 'employees') === 'workrecords' ? 'show active' : '' ?>" id="workrecords">
            <div class="table-card">
                <div class="table-card-header">
                    <h5><i class="fas fa-clipboard-list me-2"></i>Catatan Produksi Borongan</h5>
                    <div class="d-flex gap-2">
                        <button class="inv-btn inv-btn-success" data-bs-toggle="modal" data-bs-target="#addWorkRecordModal">
                            <i class="fas fa-plus me-2"></i>Tambah Catatan
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Karyawan</th>
                                <th>Produk</th>
                                <th>Jenis Kerja</th>
                                <th>Lusin</th>
                                <th>Pcs</th>
                                <th>Tarif/pcs</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($workRecords)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5" style="color: var(--text-tertiary);">
                                        <i class="fas fa-clipboard fa-3x mb-3" style="opacity: 0.3;"></i>
                                        <p class="mb-0">Tidak ada catatan produksi</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($workRecords as $record): ?>
                                    <tr>
                                        <td data-label="Tanggal"><?= date('d/m/Y', strtotime($record['date'])) ?></td>
                                        <td data-label="Karyawan">
                                            <div class="fw-semibold" style="color: var(--text-primary);"><?= htmlspecialchars($record['full_name']) ?></div>
                                            <div class="small" style="color: var(--text-tertiary);"><?= htmlspecialchars($record['emp_code']) ?></div>
                                        </td>
                                        <td data-label="Produk"><?= htmlspecialchars($record['product_name']) ?></td>
                                        <td data-label="Jenis">
                                            <span class="badge-custom <?= $record['work_type'] === 'rajut' ? 'badge-primary' : 'badge-info' ?>">
                                                <?= ucfirst($record['work_type']) ?>
                                            </span>
                                        </td>
                                        <td data-label="Lusin"><?= number_format($record['dozens'] ?? 0, 2) ?></td>
                                        <td data-label="Pcs" class="fw-semibold"><?= $record['pcs'] ?></td>
                                        <td data-label="Tarif"><?= format_currency($record['rate_per_pcs']) ?></td>
                                        <td data-label="Total" class="fw-bold text-success"><?= format_currency($record['subtotal']) ?></td>
                                        <td data-label="Aksi">
                                            <a href="<?= base_url('hr/workrecord/delete/' . $record['id']) ?>" class="action-btn action-btn-delete" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Payroll Tab -->
        <div class="tab-pane fade <?= ($activeTab ?? 'employees') === 'payroll' ? 'show active' : '' ?>" id="payroll">
            <div class="table-card">
                <div class="table-card-header">
                    <h5><i class="fas fa-money-bill-wave me-2"></i>Payroll - <?= date('F Y') ?></h5>
                    <div class="d-flex gap-2">
                        <button class="inv-btn inv-btn-primary" data-bs-toggle="modal" data-bs-target="#generatePayrollModal">
                            <i class="fas fa-calculator me-2"></i>Generate Payroll
                        </button>
                        <button class="inv-btn inv-btn-success" onclick="exportPayroll()">
                            <i class="fas fa-file-excel me-2"></i>Export
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Karyawan</th>
                                <th>Total Pcs</th>
                                <th>Gaji Total</th>
                                <th>Tgl Bayar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($payroll)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5" style="color: var(--text-tertiary);">
                                        <i class="fas fa-receipt fa-3x mb-3" style="opacity: 0.3;"></i>
                                        <p class="mb-0">Tidak ada data payroll</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($payroll as $record): ?>
                                    <tr>
                                        <td data-label="Periode">
                                            <div class="fw-semibold"><?= date('d/m/Y', strtotime($record['period_start'])) ?></div>
                                            <div class="small text-muted">s/d <?= date('d/m/Y', strtotime($record['period_end'])) ?></div>
                                        </td>
                                        <td data-label="Karyawan">
                                            <div class="fw-semibold" style="color: var(--text-primary);"><?= htmlspecialchars($record['full_name']) ?></div>
                                            <div class="small" style="color: var(--text-tertiary);"><?= htmlspecialchars($record['emp_code']) ?></div>
                                        </td>
                                        <td data-label="Total Pcs" class="fw-semibold"><?= number_format($record['total_pcs']) ?> pcs</td>
                                        <td data-label="Gaji" class="fw-bold text-success"><?= format_currency($record['total_salary']) ?></td>
                                        <td data-label="Tgl Bayar"><?= $record['payment_date'] ? date('d/m/Y', strtotime($record['payment_date'])) : '-' ?></td>
                                        <td data-label="Status">
                                            <span class="badge-custom <?= $record['payment_status'] === 'paid' ? 'badge-success' : 'badge-warning' ?>">
                                                <?= ucfirst($record['payment_status']) ?>
                                            </span>
                                        </td>
                                        <td data-label="Aksi">
                                            <div style="display: flex; gap: 0.5rem;">
                                                <?php if ($record['payment_status'] === 'pending'): ?>
                                                    <a href="<?= base_url('hr/payroll/pay/' . $record['id']) ?>" class="action-btn action-btn-success" onclick="return confirm('Konfirmasi pembayaran?')" title="Bayar">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?= base_url('hr/payroll/delete/' . $record['id']) ?>" class="action-btn action-btn-delete" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
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

        <!-- Master Product Tab -->
        <div class="tab-pane fade <?= ($activeTab ?? 'employees') === 'master-product' ? 'show active' : '' ?>" id="master-product">
            <div style="background: var(--surface-1); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">
                <div style="background: linear-gradient(90deg, var(--surface-2) 0%, var(--surface-3) 100%); padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                    <h5 style="color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1rem;"><i class="fas fa-tags me-2"></i>Master Produk (Tarif Borongan)</h5>
                    <button class="inv-btn inv-btn-primary" data-bs-toggle="modal" data-bs-target="#addProductTypeModal">
                        <i class="fas fa-plus me-2"></i>Tambah Produk
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Tarif Rajut</th>
                                <th>Tarif Linking</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($productTypes)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5" style="color: var(--text-tertiary);">
                                        <i class="fas fa-tags fa-3x mb-3" style="opacity: 0.3;"></i>
                                        <p class="mb-0">Tidak ada master produk</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($productTypes as $pt): ?>
                                    <tr>
                                        <td data-label="Produk">
                                            <div class="fw-semibold" style="color: var(--text-primary);">
                                                <?= htmlspecialchars($pt['name']) ?>
                                            </div>
                                            <div class="small" style="color: var(--text-tertiary);">
                                                <?= htmlspecialchars($pt['description'] ?? '-') ?>
                                            </div>
                                        </td>
                                        <td data-label="Tarif Rajut" class="fw-semibold">
                                            <?= format_currency($pt['rajut_rate']) ?>/pcs
                                        </td>
                                        <td data-label="Tarif Linking" class="fw-semibold">
                                            <?= format_currency($pt['linking_rate']) ?>/pcs
                                        </td>
                                        <td data-label="Status">
                                            <span class="badge-custom <?= ($pt['is_active'] ?? 1) ? 'badge-success' : 'badge-danger' ?>">
                                                <?= ($pt['is_active'] ?? 1) ? 'Aktif' : 'Nonaktif' ?>
                                            </span>
                                        </td>
                                        <td data-label="Aksi">
                                            <div style="display: flex; gap: 0.5rem;">
                                                <button
                                                    class="action-btn action-btn-edit"
                                                    data-id="<?= $pt['id'] ?>"
                                                    data-name="<?= htmlspecialchars($pt['name'], ENT_QUOTES) ?>"
                                                    data-rajut="<?= $pt['rajut_rate'] ?>"
                                                    data-linking="<?= $pt['linking_rate'] ?>"
                                                    data-desc="<?= htmlspecialchars($pt['description'] ?? '', ENT_QUOTES) ?>"
                                                    data-active="<?= $pt['is_active'] ?? 1 ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editProductTypeModal"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="confirmAction('Yakin ingin menghapus produk ini?', '<?= base_url('hr/product-type/delete/' . $pt['id']) ?>')" class="action-btn action-btn-delete" title="Hapus">
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

<!-- Add Product Type Modal -->
<div class="modal fade" id="addProductTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('hr/product-type/store') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-modern">Nama Produk</label>
                        <input type="text" name="name" class="form-control-modern" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Tarif Rajut (per lusin)</label>
                        <input type="number" name="rajut_rate_dozen" id="addRajutDozen" class="form-control-modern" step="0.01" min="0" required>
                        <small class="text-muted" id="addRajutPcsPreview">≈ Rp0 / pcs</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Tarif Linking (per lusin)</label>
                        <input type="number" name="linking_rate_dozen" id="addLinkingDozen" class="form-control-modern" step="0.01" min="0" required>
                        <small class="text-muted" id="addLinkingPcsPreview">≈ Rp0 / pcs</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Deskripsi</label>
                        <textarea name="description" class="form-control-modern" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Status</label>
                        <select name="is_active" class="form-control-modern">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="inv-btn inv-btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="inv-btn inv-btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Type Modal -->
<div class="modal fade" id="editProductTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProductTypeForm" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-modern">Nama Produk</label>
                        <input type="text" name="name" id="editName" class="form-control-modern" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Tarif Rajut (per lusin)</label>
                        <input type="number" name="rajut_rate_dozen" id="editRajut" class="form-control-modern" step="0.01" min="0" required>
                        <small class="text-muted" id="editRajutPcsPreview">≈ Rp0 / pcs</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Tarif Linking (per lusin)</label>
                        <input type="number" name="linking_rate_dozen" id="editLinking" class="form-control-modern" step="0.01" min="0" required>
                        <small class="text-muted" id="editLinkingPcsPreview">≈ Rp0 / pcs</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Deskripsi</label>
                        <textarea name="description" id="editDesc" class="form-control-modern" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Status</label>
                        <select name="is_active" id="editActive" class="form-control-modern">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="inv-btn inv-btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="inv-btn inv-btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Attendance Modal -->
<div class="modal fade" id="addAttendanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catat Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('hr/attendance/record') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-modern">Karyawan</label>
                        <select name="employee_id" class="form-control-modern" required>
                            <option value="">Pilih Karyawan</option>
                            <?php foreach ($employees as $emp): ?>
                                <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['full_name']) ?> (<?= $emp['employee_id'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Tanggal</label>
                        <input type="date" name="date" class="form-control-modern" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Check In</label>
                            <input type="time" name="check_in" class="form-control-modern">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Check Out</label>
                            <input type="time" name="check_out" class="form-control-modern">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Status</label>
                        <select name="status" class="form-control-modern" required>
                            <option value="present">Hadir</option>
                            <option value="late">Terlambat</option>
                            <option value="absent">Tidak Hadir</option>
                            <option value="leave">Izin</option>
                            <option value="sick">Sakit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Keterangan</label>
                        <textarea name="notes" class="form-control-modern" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="inv-btn inv-btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="inv-btn inv-btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Work Record Modal -->
<div class="modal fade" id="addWorkRecordModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Catatan Produksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('hr/workrecord/store') ?>" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Karyawan</label>
                            <select name="employee_id" class="form-control-modern" required>
                                <option value="">Pilih Karyawan</option>
                                <?php foreach ($employees as $emp): ?>
                                    <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['full_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Tanggal</label>
                            <input type="date" name="date" class="form-control-modern" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Produk</label>
                            <select name="product_type_id" id="productType" class="form-control-modern" required>
                                <option value="">Pilih Produk</option>
                                <?php 
                                $productTypes = (new Employee())->getProductTypes();
                                foreach ($productTypes as $pt): 
                                ?>
                                    <option value="<?= $pt['id'] ?>" 
                                            data-rajut="<?= $pt['rajut_rate'] ?>" 
                                            data-linking="<?= $pt['linking_rate'] ?>">
                                        <?= htmlspecialchars($pt['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Jenis Pekerjaan</label>
                            <select name="work_type" id="workType" class="form-control-modern" required>
                                <option value="">Pilih Jenis</option>
                                <option value="rajut">Rajut</option>
                                <option value="linking">Linking</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Jumlah (Lusin) - Optional</label>
                            <input type="number" name="dozens" id="dozens" class="form-control-modern" min="0" step="0.01" placeholder="Otomatis dihitung ke pcs">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-modern">Jumlah (Pcs)</label>
                            <input type="number" name="pcs" id="pcs" class="form-control-modern" min="1" required>
                            <small class="text-muted" id="totalPcsPreview">Total pcs: 0</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label-modern">Keterangan</label>
                            <textarea name="notes" class="form-control-modern" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="inv-btn inv-btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="inv-btn inv-btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generate Payroll Modal -->
<div class="modal fade" id="generatePayrollModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Payroll</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('hr/payroll/generate') ?>" method="POST" id="generatePayrollForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-modern">Karyawan <span class="text-danger">*</span></label>
                        <select name="employee_id" id="payroll_employee_id" class="form-control-modern" required>
                            <option value="">-- Pilih Karyawan --</option>
                            <?php foreach ($employees as $emp): ?>
                                <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['full_name']) ?> (<?= $emp['employee_id'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Pilih karyawan yang akan digenerate payroll</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Periode Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="period_start" id="payroll_period_start" class="form-control-modern" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Periode Akhir <span class="text-danger">*</span></label>
                        <input type="date" name="period_end" id="payroll_period_end" class="form-control-modern" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-modern">Catatan</label>
                        <textarea name="notes" id="payroll_notes" class="form-control-modern" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="inv-btn inv-btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="inv-btn inv-btn-primary" id="btnGeneratePayroll">
                        <i class="fas fa-calculator me-1"></i> Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Stats grid */
.hr-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
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

.inv-btn-secondary {
    background: var(--surface-3);
    color: var(--text-primary);
}

.inv-btn-secondary:hover {
    background: var(--surface-2);
}

/* Inputs */
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

.form-label-modern {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--text-secondary);
}

/* Modern date/time inputs */
.form-control-modern[type="date"],
.form-control-modern[type="time"],
.form-control-modern[type="month"] {
    color-scheme: dark;
    padding-right: 2.25rem;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23cbd5e1' stroke-width='2'%3e%3crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3e%3cline x1='16' y1='2' x2='16' y2='6'/%3e%3cline x1='8' y1='2' x2='8' y2='6'/%3e%3cline x1='3' y1='10' x2='21' y2='10'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1.1rem;
}

.form-control-modern[type="time"] {
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23cbd5e1' stroke-width='2'%3e%3ccircle cx='12' cy='12' r='9'/%3e%3cpolyline points='12 7 12 12 15 15'/%3e%3c/svg%3e");
}

/* Modal styling */
.modal-content {
    background: var(--surface-1);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.45);
}

.modal-header {
    border-bottom: 1px solid var(--border-color);
    padding: 1.25rem 1.5rem;
}

.modal-title {
    color: var(--text-primary);
    font-weight: 700;
}

.modal-body {
    padding: 1.25rem 1.5rem 1.5rem;
}

.modal-footer {
    border-top: 1px solid var(--border-color);
    padding: 1rem 1.5rem 1.25rem;
}

.btn-close {
    filter: invert(1);
    opacity: 0.8;
}

.btn-close:hover {
    opacity: 1;
}

/* Cleaner date/time icons (avoid double icon on some browsers) */
input[type="date"].form-control-modern::-webkit-calendar-picker-indicator,
input[type="time"].form-control-modern::-webkit-calendar-picker-indicator {
    opacity: 0.7;
    cursor: pointer;
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

.action-btn-success {
    background: rgba(16, 185, 129, 0.18);
    color: var(--success);
}

.action-btn-success:hover {
    background: rgba(16, 185, 129, 0.28);
    transform: scale(1.08);
}

@media (max-width: 1024px) {
    .hr-stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 768px) {
    .hr-stats-grid {
        grid-template-columns: 1fr;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
}
</style>

<script>
// Search employee
document.getElementById('searchEmployee')?.addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#employeeTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});

// Auto filter attendance by date
document.getElementById('attendanceDate')?.addEventListener('change', function() {
    const url = new URL(window.location.href);
    url.searchParams.set('attendance_date', this.value);
    url.searchParams.set('tab', 'attendance');
    window.location.href = url.toString();
});

// Master product edit modal (input per lusin)
const addRajutDozen = document.getElementById('addRajutDozen');
const addLinkingDozen = document.getElementById('addLinkingDozen');
const addRajutPcsPreview = document.getElementById('addRajutPcsPreview');
const addLinkingPcsPreview = document.getElementById('addLinkingPcsPreview');

const editRajutDozen = document.getElementById('editRajut');
const editLinkingDozen = document.getElementById('editLinking');
const editRajutPcsPreview = document.getElementById('editRajutPcsPreview');
const editLinkingPcsPreview = document.getElementById('editLinkingPcsPreview');

function formatCurrency(num) {
    const val = isNaN(num) ? 0 : num;
    return 'Rp' + new Intl.NumberFormat('id-ID').format(val);
}

function updateDozenPreview(inputEl, previewEl) {
    if (!inputEl || !previewEl) return;
    const dozenValue = parseFloat(inputEl.value) || 0;
    const perPcs = dozenValue / 12;
    previewEl.textContent = `≈ ${formatCurrency(perPcs)} / pcs`;
}

addRajutDozen?.addEventListener('input', () => updateDozenPreview(addRajutDozen, addRajutPcsPreview));
addLinkingDozen?.addEventListener('input', () => updateDozenPreview(addLinkingDozen, addLinkingPcsPreview));
editRajutDozen?.addEventListener('input', () => updateDozenPreview(editRajutDozen, editRajutPcsPreview));
editLinkingDozen?.addEventListener('input', () => updateDozenPreview(editLinkingDozen, editLinkingPcsPreview));

// Master product edit modal (prefill per lusin)
document.querySelectorAll('#master-product .action-btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const rajut = parseFloat(this.getAttribute('data-rajut')) || 0; // per pcs
        const linking = parseFloat(this.getAttribute('data-linking')) || 0; // per pcs
        const desc = this.getAttribute('data-desc');
        const active = this.getAttribute('data-active');

        const form = document.getElementById('editProductTypeForm');
        if (form) {
            form.action = `<?= base_url('hr/product-type/update/') ?>${id}`;
        }
        document.getElementById('editName').value = name || '';
        document.getElementById('editRajut').value = (rajut * 12).toFixed(2);
        document.getElementById('editLinking').value = (linking * 12).toFixed(2);
        document.getElementById('editDesc').value = desc || '';
        document.getElementById('editActive').value = active || 1;

        updateDozenPreview(editRajutDozen, editRajutPcsPreview);
        updateDozenPreview(editLinkingDozen, editLinkingPcsPreview);
    });
});

updateDozenPreview(addRajutDozen, addRajutPcsPreview);
updateDozenPreview(addLinkingDozen, addLinkingPcsPreview);

// Preview total pcs = (dozens * 12) + extra pcs
const dozensInput = document.getElementById('dozens');
const pcsInput = document.getElementById('pcs');
const totalPcsPreview = document.getElementById('totalPcsPreview');

function updateTotalPcs() {
    const dozens = parseFloat(dozensInput?.value) || 0;
    const extraPcs = parseInt(pcsInput?.value) || 0;
    const total = Math.floor(dozens * 12) + extraPcs;
    if (totalPcsPreview) {
        totalPcsPreview.textContent = `Total pcs: ${total}`;
    }
}

dozensInput?.addEventListener('input', updateTotalPcs);
pcsInput?.addEventListener('input', updateTotalPcs);
updateTotalPcs();

// Reset dan validasi Generate Payroll Modal
const generatePayrollModal = document.getElementById('generatePayrollModal');
const generatePayrollForm = document.getElementById('generatePayrollForm');

if (generatePayrollModal && generatePayrollForm) {
    // Reset form saat modal dibuka
    generatePayrollModal.addEventListener('show.bs.modal', function () {
        generatePayrollForm.reset();
        
        // Set default dates (last 30 days)
        const today = new Date();
        const lastMonth = new Date(today);
        lastMonth.setDate(today.getDate() - 30);
        
        document.getElementById('payroll_period_start').value = lastMonth.toISOString().split('T')[0];
        document.getElementById('payroll_period_end').value = today.toISOString().split('T')[0];
        
        console.log('Generate Payroll Modal opened - form reset with default dates');
    });
    
    // Validasi sebelum submit
    generatePayrollForm.addEventListener('submit', function(e) {
        const employeeSelect = document.getElementById('payroll_employee_id');
        const employeeId = employeeSelect?.value;
        
        console.log('Form submit - employee_id:', employeeId, 'type:', typeof employeeId);
        
        if (!employeeId || employeeId === '' || employeeId === '0') {
            e.preventDefault();
            alert('Pilih karyawan terlebih dahulu!');
            employeeSelect?.focus();
            return false;
        }
        
        const periodStart = document.getElementById('payroll_period_start')?.value;
        const periodEnd = document.getElementById('payroll_period_end')?.value;
        
        if (!periodStart || !periodEnd) {
            e.preventDefault();
            alert('Tentukan periode payroll!');
            return false;
        }
        
        if (periodStart > periodEnd) {
            e.preventDefault();
            alert('Periode mulai tidak boleh lebih besar dari periode akhir!');
            return false;
        }
        
        console.log('Form validation passed - submitting with employee_id:', employeeId);
        
        // Disable button to prevent double submit
        const btnSubmit = document.getElementById('btnGeneratePayroll');
        if (btnSubmit) {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
        }
    });
}

function exportPayroll() {
    window.location.href = '<?= base_url('hr/export?format=excel') ?>';
}
</script>
