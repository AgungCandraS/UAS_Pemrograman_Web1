<?php
/**
 * Employee Model - HR Management
 */

class Employee {
    private $db;
    private $lastError = null;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getLastError() {
        return $this->lastError;
    }
    
    public function getAll($status = 'active', $limit = null, $offset = 0) {
        $sql = "SELECT * FROM employees WHERE 1=1";
        $params = [];
        
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY full_name ASC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function findById($id) {
        return $this->db->fetchOne("SELECT * FROM employees WHERE id = ?", [$id]);
    }

    public function findByCode($employeeCode) {
        return $this->db->fetchOne("SELECT * FROM employees WHERE employee_id = ?", [$employeeCode]);
    }
    
    public function create($data) {
        return $this->db->insert('employees', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('employees', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('employees', 'id = ?', [$id]);
    }
    
    public function getAttendance($employeeId = null, $date = null) {
        $sql = "SELECT a.*, e.full_name, e.employee_id as emp_code
                FROM attendance a
                JOIN employees e ON a.employee_id = e.id
                WHERE 1=1";
        
        $params = [];
        if ($employeeId) {
            $sql .= " AND a.employee_id = ?";
            $params[] = $employeeId;
        }
        
        if ($date) {
            $sql .= " AND a.date = ?";
            $params[] = $date;
        } else {
            $sql .= " AND a.date = CURDATE()";
        }
        
        $sql .= " ORDER BY e.full_name ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function recordAttendance($data) {
        // Check if attendance already exists
        $existing = $this->db->fetchOne(
            "SELECT id FROM attendance WHERE employee_id = ? AND date = ?",
            [$data['employee_id'], $data['date']]
        );
        
        if ($existing) {
            return $this->db->update('attendance', $data, 'id = ?', [$existing['id']]);
        }
        
        return $this->db->insert('attendance', $data);
    }
    
    public function count($status = '') {
        $sql = "SELECT COUNT(*) as total FROM employees WHERE 1=1";
        $params = [];
        
        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }
    
    // ========== PAYROLL ==========
    public function getPayroll($filters = []) {
        $sql = "SELECT p.*, e.full_name, e.employee_id as emp_code
                FROM payroll p
                JOIN employees e ON p.employee_id = e.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['employee_id'])) {
            $sql .= " AND p.employee_id = ?";
            $params[] = $filters['employee_id'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND p.payment_status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['month']) && !empty($filters['year'])) {
            // Filter berdasarkan bulan dan tahun dari period_start
            $sql .= " AND MONTH(p.period_start) = ? AND YEAR(p.period_start) = ?";
            $params[] = $filters['month'];
            $params[] = $filters['year'];
        } elseif (!empty($filters['month'])) {
            $sql .= " AND MONTH(p.period_start) = ?";
            $params[] = $filters['month'];
        } elseif (!empty($filters['year'])) {
            $sql .= " AND YEAR(p.period_start) = ?";
            $params[] = $filters['year'];
        }
        
        if (!empty($filters['period_start'])) {
            $sql .= " AND p.period_start >= ?";
            $params[] = $filters['period_start'];
        }
        
        if (!empty($filters['period_end'])) {
            $sql .= " AND p.period_end <= ?";
            $params[] = $filters['period_end'];
        }
        
        $sql .= " ORDER BY p.period_start DESC, p.created_at DESC";
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getPayrollById($id) {
        $payroll = $this->db->fetchOne("SELECT p.*, e.full_name, e.employee_id as emp_code
                                         FROM payroll p
                                         JOIN employees e ON p.employee_id = e.id
                                         WHERE p.id = ?", [$id]);
        
        if ($payroll) {
            // Load details
            $payroll['details'] = $this->db->fetchAll(
                "SELECT * FROM payroll_details WHERE payroll_id = ? ORDER BY product_name, work_type",
                [$id]
            );
        }
        
        return $payroll;
    }
    
    public function getPayrollDetails($payrollId) {
        return $this->db->fetchAll(
            "SELECT * FROM payroll_details WHERE payroll_id = ? ORDER BY product_name, work_type",
            [$payrollId]
        );
    }
    
    public function createPayroll($data) {
        return $this->db->insert('payroll', $data);
    }
    
    public function updatePayroll($id, $data) {
        return $this->db->update('payroll', $data, 'id = ?', [$id]);
    }
    
    public function deletePayroll($id) {
        return $this->db->delete('payroll', 'id = ?', [$id]);
    }
    
    // ========== PRODUCT TYPES (Jenis Produk: Belle, K5, K4, Kulot) ==========
    public function getProductTypes($activeOnly = true) {
        $sql = "SELECT * FROM product_types";
        $params = [];
        
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }
        
        $sql .= " ORDER BY name ASC";
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getProductTypeById($id) {
        return $this->db->fetchOne("SELECT * FROM product_types WHERE id = ?", [$id]);
    }
    
    public function createProductType($data) {
        return $this->db->insert('product_types', $data);
    }
    
    public function updateProductType($id, $data) {
        return $this->db->update('product_types', $data, 'id = ?', [$id]);
    }
    
    public function deleteProductType($id) {
        return $this->db->delete('product_types', 'id = ?', [$id]);
    }
    
    // ========== WORK RECORDS (Catatan Produksi) ==========
    public function getWorkRecords($filters = []) {
        $sql = "SELECT wr.*, e.full_name, e.employee_id as emp_code, 
                       pt.name as product_name, pt.rajut_rate, pt.linking_rate,
                       wr.subtotal as earning
                FROM work_records wr
                JOIN employees e ON wr.employee_id = e.id
                JOIN product_types pt ON wr.product_type_id = pt.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['employee_id'])) {
            $sql .= " AND wr.employee_id = ?";
            $params[] = $filters['employee_id'];
        }
        
        if (!empty($filters['product_type_id'])) {
            $sql .= " AND wr.product_type_id = ?";
            $params[] = $filters['product_type_id'];
        }
        
        if (!empty($filters['work_type'])) {
            $sql .= " AND wr.work_type = ?";
            $params[] = $filters['work_type'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND wr.date >= ?";
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND wr.date <= ?";
            $params[] = $filters['date_to'];
        }
        
        $sql .= " ORDER BY wr.date DESC, e.full_name ASC";
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getWorkRecordById($id) {
        return $this->db->fetchOne("SELECT * FROM work_records WHERE id = ?", [$id]);
    }
    
    public function createWorkRecord($data) {
        // Auto-calculate pcs from dozens if needed
        if (!empty($data['dozens']) && empty($data['pcs'])) {
            $data['pcs'] = (int)floor((float)$data['dozens'] * 12);
        }
        
        // Calculate subtotal with proper decimal handling
        $data['subtotal'] = (float)$data['pcs'] * (float)$data['rate_per_pcs'];
        
        return $this->db->insert('work_records', $data);
    }
    
    public function updateWorkRecord($id, $data) {
        // Auto-calculate pcs from dozens if needed
        if (!empty($data['dozens']) && empty($data['pcs'])) {
            $data['pcs'] = (int)floor((float)$data['dozens'] * 12);
        }
        
        // Calculate subtotal with proper decimal handling
        if (isset($data['pcs']) && isset($data['rate_per_pcs'])) {
            $data['subtotal'] = (float)$data['pcs'] * (float)$data['rate_per_pcs'];
        }
        
        return $this->db->update('work_records', $data, 'id = ?', [$id]);
    }
    
    public function deleteWorkRecord($id) {
        return $this->db->delete('work_records', 'id = ?', [$id]);
    }
    
    // ========== PAYROLL CALCULATION ==========
    public function calculatePayroll($employeeId, $periodStart, $periodEnd) {
        $records = $this->getWorkRecords([
            'employee_id' => $employeeId,
            'date_from' => $periodStart,
            'date_to' => $periodEnd
        ]);
        
        $details = [];
        $totalPcs = 0;
        $totalSalary = 0.0;
        
        foreach ($records as $record) {
            $key = $record['product_type_id'] . '_' . $record['work_type'];
            
            if (!isset($details[$key])) {
                $details[$key] = [
                    'product_type_id' => (int)$record['product_type_id'],
                    'product_name' => $record['product_name'],
                    'work_type' => $record['work_type'],
                    'rate_per_pcs' => (float)$record['rate_per_pcs'],
                    'pcs' => 0,
                    'subtotal' => 0.0
                ];
            }
            
            $details[$key]['pcs'] += (int)$record['pcs'];
            $details[$key]['subtotal'] += (float)$record['subtotal'];
            $totalPcs += (int)$record['pcs'];
            $totalSalary += (float)$record['subtotal'];
        }
        
        return [
            'details' => array_values($details),
            'total_pcs' => $totalPcs,
            'total_salary' => $totalSalary
        ];
    }
    
    public function createPayrollWithDetails($data, $details) {
        try {
            $this->lastError = null;
            
            // Validasi data
            if (empty($data['employee_id']) || empty($data['period_start']) || empty($data['period_end'])) {
                $this->lastError = 'Data tidak lengkap';
                error_log('Payroll Error: Data tidak lengkap - ' . json_encode($data));
                return false;
            }

            // Cek apakah tabel payroll ada
            $checkTable = $this->db->fetchOne("SHOW TABLES LIKE 'payroll'");
            if (!$checkTable) {
                $this->lastError = 'Tabel payroll tidak ditemukan. Harap jalankan database migration';
                error_log('Payroll Error: Tabel payroll tidak ada');
                return false;
            }

            // Debug log
            error_log('Creating payroll with data: ' . json_encode($data));
            
            // Insert payroll utama
            $fields = array_keys($data);
            $placeholders = array_fill(0, count($fields), '?');
            $sql = "INSERT INTO payroll (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
            
            $stmt = $this->db->query($sql, array_values($data));
            
            if ($stmt === false) {
                $this->lastError = 'Gagal insert payroll ke database';
                error_log('Payroll Error: Query returned false');
                return false;
            }
            
            // Ambil insert_id langsung dari connection
            $payrollId = $this->db->getConnection()->insert_id;
            $stmt->close();
            
            error_log('Payroll insert_id: ' . $payrollId);
            
            if (!$payrollId || $payrollId <= 0) {
                $this->lastError = 'Tidak dapat mendapatkan payroll ID yang valid (ID: ' . $payrollId . ')';
                error_log('Payroll CRITICAL: Invalid insert_id: ' . $payrollId);
                return false;
            }
            
            error_log('Payroll created successfully with ID: ' . $payrollId);
            
            // Insert detail untuk setiap produk
            if (!empty($details)) {
                foreach ($details as $index => $detail) {
                    $detailData = [
                        'payroll_id' => (int)$payrollId,
                        'product_type_id' => (int)$detail['product_type_id'],
                        'product_name' => $detail['product_name'],
                        'work_type' => $detail['work_type'],
                        'pcs' => (int)$detail['pcs'],
                        'rate_per_pcs' => (float)$detail['rate_per_pcs'],
                        'subtotal' => (float)$detail['subtotal']
                    ];
                    
                    error_log('Inserting detail #' . ($index + 1) . ': ' . json_encode($detailData));
                    
                    $inserted = $this->db->insert('payroll_details', $detailData);
                    if ($inserted === false) {
                        $this->lastError = 'Gagal insert detail untuk ' . $detail['product_name'];
                        error_log('Payroll Detail Error: Gagal insert detail - ' . json_encode($detailData));
                    }
                }
            }
            
            return $payrollId;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Payroll Exception: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }
    
    public function updatePayrollStatus($id, $status, $paymentDate = null) {
        $data = ['payment_status' => $status];
        
        if ($paymentDate) {
            $data['payment_date'] = $paymentDate;
        }
        
        return $this->db->update('payroll', $data, 'id = ?', [$id]);
    }
}
