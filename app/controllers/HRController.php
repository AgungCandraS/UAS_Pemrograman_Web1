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
        $employees = $this->employeeModel->getAll('', 50);
        $attendance = $this->employeeModel->getAttendance(null, date('Y-m-d'));
        $payroll = []; // TODO: Implement payroll
        
        $stats = [
            'total_employees' => $this->employeeModel->count(''),
            'active_employees' => $this->employeeModel->count('active'),
            'present_today' => count($attendance),
            'total_salary' => array_sum(array_column($employees, 'salary'))
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
        
        ob_start();
        include APP_PATH . '/views/hr/employees.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Daftar Karyawan';
        $activePage = 'hr';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function createEmployee() {
        ob_start();
        include APP_PATH . '/views/hr/create_employee.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Tambah Karyawan';
        $activePage = 'hr';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function storeEmployee() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('hr/employees'));
        }
        
        $data = [
            'employee_id' => post('employee_id'),
            'full_name' => post('full_name'),
            'email' => post('email'),
            'phone' => post('phone'),
            'address' => post('address'),
            'position' => post('position'),
            'department' => post('department'),
            'hire_date' => post('hire_date'),
            'salary' => post('salary'),
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
        
        redirect(base_url('hr/employees'));
    }
    
    public function editEmployee($id) {
        $employee = $this->employeeModel->findById($id);
        if (!$employee) {
            flash('error', 'Karyawan tidak ditemukan');
            redirect(base_url('hr/employees'));
        }
        
        ob_start();
        include APP_PATH . '/views/hr/edit_employee.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Edit Karyawan';
        $activePage = 'hr';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function updateEmployee($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('hr/employees'));
        }
        
        $data = [
            'full_name' => post('full_name'),
            'email' => post('email'),
            'phone' => post('phone'),
            'address' => post('address'),
            'position' => post('position'),
            'department' => post('department'),
            'salary' => post('salary'),
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
        
        redirect(base_url('hr/employees'));
    }
    
    public function deleteEmployee($id) {
        if ($this->employeeModel->delete($id)) {
            flash('success', 'Karyawan berhasil dihapus');
        } else {
            flash('error', 'Gagal menghapus karyawan');
        }
        
        redirect(base_url('hr/employees'));
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
            redirect(base_url('hr/attendance'));
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
        
        redirect(base_url('hr/attendance'));
    }
    
    public function payroll() {
        // TODO: Implement payroll functionality
        ob_start();
        echo '<div class="text-center py-20">
                <i class="fas fa-money-bill-wave text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600">Fitur Payroll</h3>
                <p class="text-gray-500">Sedang dalam pengembangan</p>
              </div>';
        $content = ob_get_clean();
        
        $pageTitle = 'Payroll';
        $activePage = 'hr';
        include APP_PATH . '/views/layouts/app.php';
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
