<div class="fade-in">
    <!-- Header -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--text-primary); font-family: 'Poppins', sans-serif;">HR & Manajemen Karyawan</h2>
                    <p class="mb-0" style="color: var(--text-tertiary); font-size: 0.9rem;">Kelola data karyawan, absensi, dan payroll</p>
                </div>
                <button class="btn-custom btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="fas fa-user-plus me-2"></i>Tambah Karyawan
                </button>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card hover-lift">
                <div class="stat-card-icon primary mb-3">
                    <i class="fas fa-users"></i>
                </div>
                <p class="stat-card-title">Total Karyawan</p>
                <h2 class="stat-card-value"><?= $stats['total_employees'] ?? 0 ?></h2>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card hover-lift">
                <div class="stat-card-icon success mb-3">
                    <i class="fas fa-user-check"></i>
                </div>
                <p class="stat-card-title">Karyawan Aktif</p>
                <h2 class="stat-card-value"><?= $stats['active_employees'] ?? 0 ?></h2>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card hover-lift">
                <div class="stat-card-icon info mb-3">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <p class="stat-card-title">Hadir Hari Ini</p>
                <h2 class="stat-card-value"><?= $stats['present_today'] ?? 0 ?></h2>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card hover-lift">
                <div class="stat-card-icon warning mb-3">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <p class="stat-card-title">Total Payroll</p>
                <h2 class="stat-card-value"><?= format_currency($stats['total_salary'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
    
    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#employees">
                <i class="fas fa-users me-2"></i>Karyawan
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#attendance">
                <i class="fas fa-calendar-check me-2"></i>Absensi
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#payroll">
                <i class="fas fa-money-bill-wave me-2"></i>Payroll
            </button>
        </li>
    </ul>
    
    <!-- Tab Contents -->
    <div class="tab-content">
        <!-- Employees Tab -->
        <div class="tab-pane fade show active" id="employees">
            <div class="table-card">
                <div class="table-card-header">
                    <h5>Daftar Karyawan</h5>
                    <div>
                        <input type="text" class="form-control form-control-sm" placeholder="Cari karyawan..." style="width: 250px;">
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
                                <th>Gaji</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($employees)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Tidak ada data karyawan</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($employees as $employee): ?>
                                    <tr>
                                        <td><?= $employee['employee_id'] ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <?php if ($employee['photo']): ?>
                                                        <img src="<?= base_url('storage/uploads/' . $employee['photo']) ?>" class="rounded-circle" width="40" height="40" alt="">
                                                    <?php else: ?>
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold"><?= $employee['full_name'] ?></div>
                                                    <div class="small text-muted"><?= $employee['email'] ?? '-' ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= $employee['position'] ?? '-' ?></td>
                                        <td><?= $employee['department'] ?? '-' ?></td>
                                        <td class="fw-semibold"><?= format_currency($employee['salary']) ?></td>
                                        <td>
                                            <span class="badge-custom <?= $employee['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>">
                                                <?= ucfirst($employee['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button onclick="viewEmployee(<?= $employee['id'] ?>)" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button onclick="editEmployee(<?= $employee['id'] ?>)" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="deleteEmployee(<?= $employee['id'] ?>)" class="btn btn-sm btn-outline-danger">
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
        
        <!-- Attendance Tab -->
        <div class="tab-pane fade" id="attendance">
            <div class="table-card">
                <div class="table-card-header">
                    <h5>Absensi Karyawan - <?= date('d F Y') ?></h5>
                    <div>
                        <input type="date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Nama Karyawan</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($attendance)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Tidak ada data absensi</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($attendance as $record): ?>
                                    <tr>
                                        <td><?= $record['employee_name'] ?></td>
                                        <td><?= $record['check_in'] ?? '-' ?></td>
                                        <td><?= $record['check_out'] ?? '-' ?></td>
                                        <td>
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
                                        <td><?= $record['notes'] ?? '-' ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAttendanceModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
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
        <div class="tab-pane fade" id="payroll">
            <div class="table-card">
                <div class="table-card-header">
                    <h5>Payroll - <?= date('F Y') ?></h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: 150px;">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= $i == date('n') ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        <button class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel me-1"></i>Export
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>Gaji Pokok</th>
                                <th>Tunjangan</th>
                                <th>Potongan</th>
                                <th>Gaji Bersih</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($payroll)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Tidak ada data payroll</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($payroll as $record): ?>
                                    <tr>
                                        <td><?= $record['employee_name'] ?></td>
                                        <td><?= format_currency($record['basic_salary']) ?></td>
                                        <td><?= format_currency($record['allowances']) ?></td>
                                        <td><?= format_currency($record['deductions']) ?></td>
                                        <td class="fw-bold"><?= format_currency($record['net_salary']) ?></td>
                                        <td>
                                            <span class="badge-custom <?= $record['status'] === 'paid' ? 'badge-success' : 'badge-warning' ?>">
                                                <?= ucfirst($record['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($record['status'] === 'pending'): ?>
                                                <button class="btn btn-sm btn-success">
                                                    <i class="fas fa-check me-1"></i>Bayar
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            <?php endif; ?>
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

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Karyawan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('hr/employee/store') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control-custom" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-custom">Email</label>
                            <input type="email" name="email" class="form-control-custom">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-custom">Telepon</label>
                            <input type="text" name="phone" class="form-control-custom">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-custom">Posisi</label>
                            <input type="text" name="position" class="form-control-custom" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-custom">Departemen</label>
                            <input type="text" name="department" class="form-control-custom">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Masuk</label>
                            <input type="date" name="hire_date" class="form-control-custom" value="<?= date('Y-m-d') ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-custom">Gaji</label>
                            <input type="number" name="salary" class="form-control-custom" min="0" step="0.01">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label-custom">Status</label>
                            <select name="status" class="form-control-custom">
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label-custom">Alamat</label>
                            <textarea name="address" class="form-control-custom" rows="2"></textarea>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label-custom">Foto</label>
                            <input type="file" name="photo" class="form-control-custom" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-custom btn-primary-custom">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$additionalScripts = '
<script>
function viewEmployee(id) {
    window.location.href = "' . base_url('hr/employee/view/') . '" + id;
}

function editEmployee(id) {
    window.location.href = "' . base_url('hr/employee/edit/') . '" + id;
}

function deleteEmployee(id) {
    if (confirm("Yakin ingin menghapus karyawan ini?")) {
        window.location.href = "' . base_url('hr/employee/delete/') . '" + id;
    }
}
</script>
';
?>
