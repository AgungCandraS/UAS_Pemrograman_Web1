<?php
/**
 * HR Controller
 */

class HRController {
    private $employeeModel;
    
    public function __construct() {
        require_auth();
        $this->employeeModel = new Employee();
    }
    
    public function index() {
        $filters = [
            'month' => get('month', date('n')),
            'year' => get('year', date('Y'))
        ];
        
        // Load SEMUA employees (active dan inactive) tanpa limit untuk dropdown
        $employees = $this->employeeModel->getAll('', 0);
        $attendanceDate = get('attendance_date', date('Y-m-d'));
        $attendance = $this->employeeModel->getAttendance(null, $attendanceDate);
        $payroll = $this->employeeModel->getPayroll($filters);
        $activeTab = get('tab', 'employees');
        
            $workRecords = $this->employeeModel->getWorkRecords();
            $productTypes = $this->employeeModel->getProductTypes(false);
        
        $totalPayroll = array_sum(array_column($payroll, 'total_salary'));

        $stats = [
            'total_employees' => $this->employeeModel->count(''),
            'active_employees' => $this->employeeModel->count('active'),
            'present_today' => count($attendance),
            'total_payroll' => $totalPayroll
        ];
        
        ob_start();
        include APP_PATH . '/views/hr/index.php';
        $content = ob_get_clean();
        
        $pageTitle = 'HR Management';
        $activePage = 'hr';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function employees() {
        $employees = $this->employeeModel->getAll('active');
        $activeTab = 'employees';
        
        ob_start();
        include APP_PATH . '/views/hr/index.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Daftar Karyawan';
        $activePage = 'hr';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function createEmployee() {
        ob_start();
        include APP_PATH . '/views/hr/create.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Tambah Karyawan';
        $activePage = 'hr';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function storeEmployee() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('hr'));
        }
        
        // Generate employee_id automatically
        $lastEmployee = $this->employeeModel->getAll('', 1);
        $nextId = $lastEmployee ? (int)substr($lastEmployee[0]['employee_id'], 3) + 1 : 1;
        $employeeId = 'EMP' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        
        $data = [
            'employee_id' => $employeeId,
            'full_name' => post('full_name'),
            'email' => post('email'),
            'phone' => post('phone'),
            'address' => post('address'),
            'position' => post('position'),
            'department' => post('department'),
            'hire_date' => post('hire_date'),
            'salary' => post('salary', 0),
            'status' => post('status', 'active')
        ];
        
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $data['photo'] = upload_file($_FILES['photo']);
        }
        
        if ($this->employeeModel->create($data)) {
            flash('success', 'Karyawan berhasil ditambahkan');
        } else {
            flash('error', 'Gagal menambahkan karyawan');
        }
        
        redirect(base_url('hr'));
    }
    
    public function editEmployee($id) {
        $employee = $this->employeeModel->findById($id);
        if (!$employee) {
            flash('error', 'Karyawan tidak ditemukan');
            redirect(base_url('hr'));
        }
        
        ob_start();
        include APP_PATH . '/views/hr/edit.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Edit Karyawan';
        $activePage = 'hr';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function updateEmployee($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('hr'));
        }
        
        $data = [
            'full_name' => post('full_name'),
            'email' => post('email'),
            'phone' => post('phone'),
            'address' => post('address'),
            'position' => post('position'),
            'department' => post('department'),
            'hire_date' => post('hire_date'),
            'salary' => post('salary', 0),
            'status' => post('status')
        ];
        
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $data['photo'] = upload_file($_FILES['photo']);
        }
        
        if ($this->employeeModel->update($id, $data)) {
            flash('success', 'Data karyawan berhasil diupdate');
        } else {
            flash('error', 'Gagal mengupdate data karyawan');
        }
        
        redirect(base_url('hr'));
    }
    
    public function deleteEmployee($id) {
        if ($this->employeeModel->delete($id)) {
            flash('success', 'Karyawan berhasil dihapus');
        } else {
            flash('error', 'Gagal menghapus karyawan');
        }
        
        redirect(base_url('hr'));
    }
    
    public function attendance() {
        $date = get('date', date('Y-m-d'));
        $attendanceList = $this->employeeModel->getAttendance(null, $date);
        $employees = $this->employeeModel->getAll('active');
        
        ob_start();
        include APP_PATH . '/views/hr/attendance.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Absensi Karyawan';
        $activePage = 'hr';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function recordAttendance() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('hr'));
        }
        
        $data = [
            'employee_id' => post('employee_id'),
            'date' => post('date'),
            'check_in' => post('check_in'),
            'check_out' => post('check_out'),
            'status' => post('status'),
            'notes' => post('notes')
        ];
        
        if ($this->employeeModel->recordAttendance($data)) {
            flash('success', 'Absensi berhasil dicatat');
        } else {
            flash('error', 'Gagal mencatat absensi');
        }
        
        // Redirect ke tab absensi dengan tanggal yang sama
        $attendanceDate = post('date', date('Y-m-d'));
        redirect(base_url('hr?tab=attendance&attendance_date=' . $attendanceDate));
    }
    
    public function storeWorkRecord() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('hr'));
        }

        $employeeId = (int)post('employee_id');
        $productTypeId = (int)post('product_type_id');
        $workType = post('work_type');
        
        // Validasi employee_id
        if (empty($employeeId) || $employeeId <= 0) {
            flash('error', 'Pilih karyawan terlebih dahulu');
            redirect(base_url('hr?tab=workrecords'));
            return;
        }
        
        $productType = $this->employeeModel->getProductTypeById($productTypeId);

        $ratePerPcs = 0.0;
        if ($productType) {
            $ratePerPcs = (float)($workType === 'linking' ? ($productType['linking_rate'] ?? 0) : ($productType['rajut_rate'] ?? 0));
        }

        $dozens = post('dozens');
        $pcsInput = post('pcs');
        $dozensPcs = 0;
        $extraPcs = 0;

        if ($dozens !== null && $dozens !== '') {
            $dozensPcs = (int)floor((float)$dozens * 12);
        }

        if ($pcsInput !== null && $pcsInput !== '') {
            $extraPcs = (int)$pcsInput;
        }

        $pcs = $dozensPcs + $extraPcs;

        if ($pcs <= 0) {
            flash('error', 'Jumlah pcs tidak valid');
            redirect(base_url('hr?tab=workrecords'));
            return;
        }
        
        $data = [
            'employee_id' => $employeeId,
            'date' => post('date'),
            'product_type_id' => $productTypeId,
            'work_type' => $workType,
            'dozens' => $dozens,
            'pcs' => $pcs,
            'rate_per_pcs' => $ratePerPcs,
            'notes' => post('notes')
        ];

        if ($this->employeeModel->createWorkRecord($data)) {
            flash('success', 'Catatan produksi berhasil ditambahkan');
        } else {
            flash('error', 'Gagal menambahkan catatan produksi');
        }
        
        $workDate = post('date');
        redirect(base_url('hr?tab=workrecords&work_date=' . $workDate));
    }
    
    public function updateWorkRecord($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('hr'));
        }

        $employeeId = (int)post('employee_id');
        $productTypeId = (int)post('product_type_id');
        $workType = post('work_type');
        
        // Validasi employee_id
        if (empty($employeeId) || $employeeId <= 0) {
            flash('error', 'Pilih karyawan terlebih dahulu');
            redirect(base_url('hr?tab=workrecords'));
            return;
        }
        
        $productType = $this->employeeModel->getProductTypeById($productTypeId);

        $ratePerPcs = 0;
        if ($productType) {
            $ratePerPcs = $workType === 'linking' ? ($productType['linking_rate'] ?? 0) : ($productType['rajut_rate'] ?? 0);
        }

        $dozens = post('dozens');
        $pcsInput = post('pcs');
        $dozensPcs = 0;
        $extraPcs = 0;

        if ($dozens !== null && $dozens !== '') {
            $dozensPcs = (int)floor(((float)$dozens) * 12);
        }

        if ($pcsInput !== null && $pcsInput !== '') {
            $extraPcs = (int)$pcsInput;
        }

        $pcs = $dozensPcs + $extraPcs;

        if ($pcs <= 0) {
            flash('error', 'Jumlah pcs tidak valid');
            redirect(base_url('hr?tab=workrecords'));
            return;
        }
        
        $data = [
            'employee_id' => $employeeId,
            'date' => post('date'),
            'product_type_id' => $productTypeId,
            'work_type' => $workType,
            'dozens' => $dozens,
            'pcs' => $pcs,
            'rate_per_pcs' => $ratePerPcs,
            'notes' => post('notes')
        ];
        
        if ($this->employeeModel->updateWorkRecord($id, $data)) {
            flash('success', 'Catatan produksi berhasil diupdate');
        } else {
            flash('error', 'Gagal mengupdate catatan produksi');
        }
        
        $workDate = post('date');
        redirect(base_url('hr?tab=workrecords&work_date=' . $workDate));
    }
    
    public function deleteWorkRecord($id) {
        if ($this->employeeModel->deleteWorkRecord($id)) {
            flash('success', 'Catatan produksi berhasil dihapus');
        } else {
            flash('error', 'Gagal menghapus catatan produksi');
        }
        
        redirect(base_url('hr?tab=workrecords'));
    }
    
    // ========== PAYROLL ==========
    public function generatePayroll() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('hr'));
        }
        
        $employeeId = (int)post('employee_id');
        $periodStart = post('period_start');
        $periodEnd = post('period_end');
        
        // Debug logging
        error_log('Generate Payroll - employee_id: ' . $employeeId);
        error_log('Generate Payroll - Period: ' . $periodStart . ' to ' . $periodEnd);
        
        // Validasi employee_id
        if (empty($employeeId) || $employeeId <= 0) {
            error_log('Generate Payroll FAILED - invalid employee_id');
            flash('error', 'Pilih karyawan terlebih dahulu');
            redirect(base_url('hr?tab=payroll'));
            return;
        }
        
        // Validasi periode
        if (empty($periodStart) || empty($periodEnd)) {
            flash('error', 'Tentukan periode payroll');
            redirect(base_url('hr?tab=payroll'));
            return;
        }

        $calculation = $this->employeeModel->calculatePayroll($employeeId, $periodStart, $periodEnd);

        if (empty($calculation['total_pcs'])) {
            flash('error', 'Tidak ada data produksi pada periode tersebut');
            redirect(base_url('hr?tab=payroll'));
            return;
        }

        $data = [
            'employee_id' => $employeeId,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'total_pcs' => $calculation['total_pcs'],
            'total_salary' => $calculation['total_salary'],
            'payment_status' => 'pending',
            'notes' => post('notes')
        ];

        $result = $this->employeeModel->createPayrollWithDetails($data, $calculation['details']);

        if ($result && $result !== false) {
            flash('success', 'Payroll berhasil dibuat untuk periode ' . date('d/m/Y', strtotime($periodStart)) . ' - ' . date('d/m/Y', strtotime($periodEnd)));
            error_log('Payroll SUCCESS - Created with ID: ' . $result);
        } else {
            $errorMsg = $this->employeeModel->getLastError() ?? 'Gagal membuat payroll';
            flash('error', $errorMsg);
            error_log('Payroll FAILED - Error: ' . $errorMsg);
        }
        
        redirect(base_url('hr?tab=payroll'));
    }
    
    public function payPayroll($id) {
        if ($this->employeeModel->updatePayrollStatus($id, 'paid', date('Y-m-d'))) {
            flash('success', 'Payroll berhasil dibayar');
        } else {
            flash('error', 'Gagal membayar payroll');
        }
        
        redirect(base_url('hr?tab=payroll'));
    }
    
    public function deletePayroll($id) {
        if ($this->employeeModel->deletePayroll($id)) {
            flash('success', 'Payroll berhasil dihapus');
        } else {
            flash('error', 'Gagal menghapus payroll');
        }
        
        redirect(base_url('hr?tab=payroll'));
    }
    
    public function payroll() {
        $filters = [
            'month' => get('month', date('n')),
            'year' => get('year', date('Y')),
            'status' => get('status', '')
        ];
        
        $payroll = $this->employeeModel->getPayroll($filters);
        
        ob_start();
        include APP_PATH . '/views/hr/payroll.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Payroll Management';
        $activePage = 'hr';
        include APP_PATH . '/views/layouts/app.php';
    }

    // ========== MASTER PRODUCT (Tarif Borongan) ==========
    public function storeProductType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('hr?tab=master-product'));
        }

        $rajutDozen = (float)post('rajut_rate_dozen');
        $linkingDozen = (float)post('linking_rate_dozen');

        $data = [
            'name' => post('name'),
            'rajut_rate' => $rajutDozen / 12,
            'linking_rate' => $linkingDozen / 12,
            'description' => post('description'),
            'is_active' => post('is_active', 1)
        ];

        if ($this->employeeModel->createProductType($data)) {
            flash('success', 'Produk berhasil ditambahkan');
        } else {
            flash('error', 'Gagal menambahkan produk');
        }

        redirect(base_url('hr?tab=master-product'));
    }

    public function updateProductType($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('hr?tab=master-product'));
        }

        $rajutDozen = (float)post('rajut_rate_dozen');
        $linkingDozen = (float)post('linking_rate_dozen');

        $data = [
            'name' => post('name'),
            'rajut_rate' => $rajutDozen / 12,
            'linking_rate' => $linkingDozen / 12,
            'description' => post('description'),
            'is_active' => post('is_active', 1)
        ];

        if ($this->employeeModel->updateProductType($id, $data)) {
            flash('success', 'Produk berhasil diupdate');
        } else {
            flash('error', 'Gagal mengupdate produk');
        }

        redirect(base_url('hr?tab=master-product'));
    }

    public function deleteProductType($id) {
        if ($this->employeeModel->deleteProductType($id)) {
            flash('success', 'Produk berhasil dihapus');
        } else {
            flash('error', 'Gagal menghapus produk');
        }

        redirect(base_url('hr?tab=master-product'));
    }
    
    public function export() {
        $format = strtolower(get('format', 'excel'));
        $status = get('status', '');
        $employees = $this->employeeModel->getAll($status ?: '', null, 0);
        $escape = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

        require_once ROOT_PATH . '/vendor/autoload.php';

        try {
            if ($format === 'pdf') {
                $pdf = new \TCPDF();
                $pdf->SetCreator(APP_NAME);
                $pdf->SetAuthor(APP_NAME);
                $pdf->SetTitle('Employee Report');
                $pdf->AddPage();

                $html = '<h2>Laporan Karyawan</h2><p>Tanggal: ' . date('d/m/Y H:i') . '</p>';
                $html .= '<table border="1" cellpadding="5"><thead><tr>';
                $html .= '<th>ID</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Jabatan</th><th>Departemen</th><th>Gaji</th><th>Status</th>';
                $html .= '</tr></thead><tbody>';
                
                foreach ($employees as $emp) {
                    $html .= '<tr>';
                    $html .= '<td>' . $escape($emp['employee_id']) . '</td>';
                    $html .= '<td>' . $escape($emp['full_name']) . '</td>';
                    $html .= '<td>' . $escape($emp['email']) . '</td>';
                    $html .= '<td>' . $escape($emp['phone']) . '</td>';
                    $html .= '<td>' . $escape($emp['position']) . '</td>';
                    $html .= '<td>' . $escape($emp['department']) . '</td>';
                    $html .= '<td>' . $escape(format_currency($emp['salary'])) . '</td>';
                    $html .= '<td>' . $escape(ucfirst($emp['status'])) . '</td>';
                    $html .= '</tr>';
                }
                
                $html .= '</tbody></table>';
                $pdf->writeHTML($html);
                $pdf->Output('employees_' . date('Ymd_His') . '.pdf', 'D');
                exit();
            }

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Employees');
            $sheet->fromArray(['ID', 'Nama', 'Email', 'Telepon', 'Jabatan', 'Departemen', 'Gaji', 'Status'], null, 'A1');

            $row = 2;
            foreach ($employees as $emp) {
                $sheet->setCellValue("A{$row}", $emp['employee_id']);
                $sheet->setCellValue("B{$row}", $emp['full_name']);
                $sheet->setCellValue("C{$row}", $emp['email']);
                $sheet->setCellValue("D{$row}", $emp['phone']);
                $sheet->setCellValue("E{$row}", $emp['position']);
                $sheet->setCellValue("F{$row}", $emp['department']);
                $sheet->setCellValue("G{$row}", $emp['salary']);
                $sheet->setCellValue("H{$row}", $emp['status']);
                $row++;
            }

            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'employees_' . date('Ymd_His') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit();
        } catch (Exception $e) {
            flash('error', 'Gagal mengekspor data: ' . $e->getMessage());
            redirect(base_url('hr'));
        }
    }
}
