<?php
/**
 * Employee Model - HR Management
 */

class Employee {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
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
    
    public function createWorkRecord($data) {
        // Auto-calculate pcs from dozens if needed
        if (!empty($data['dozens']) && empty($data['pcs'])) {
            $data['pcs'] = $data['dozens'] * 12;
        }
        
        // Calculate subtotal
        $data['subtotal'] = $data['pcs'] * $data['rate_per_pcs'];
        
        return $this->db->insert('work_records', $data);
    }
    
    public function updateWorkRecord($id, $data) {
        // Auto-calculate pcs from dozens if needed
        if (!empty($data['dozens']) && empty($data['pcs'])) {
            $data['pcs'] = $data['dozens'] * 12;
        }
        
        // Calculate subtotal
        if (isset($data['pcs']) && isset($data['rate_per_pcs'])) {
            $data['subtotal'] = $data['pcs'] * $data['rate_per_pcs'];
        }
        
        return $this->db->update('work_records', $data, 'id = ?', [$id]);
    }
    
    public function deleteWorkRecord($id) {
        return $this->db->delete('work_records', 'id = ?', [$id]);
    }
    
    // ========== PAYROLL (Penggajian) ==========
    public function calculatePayroll($employeeId, $periodStart, $periodEnd) {
        // Get all work records in period
        $records = $this->getWorkRecords([
            'employee_id' => $employeeId,
            'date_from' => $periodStart,
            'date_to' => $periodEnd
        ]);
        
        $details = [];
        $totalPcs = 0;
        $totalSalary = 0;
        
        // Group by product_type and work_type
        foreach ($records as $record) {
            $key = $record['product_type_id'] . '_' . $record['work_type'];
            
            if (!isset($details[$key])) {
                $details[$key] = [
                    'product_type_id' => $record['product_type_id'],
                    'product_name' => $record['product_name'],
                    'work_type' => $record['work_type'],
                    'rate_per_pcs' => $record['rate_per_pcs'],
                    'pcs' => 0,
                    'subtotal' => 0
                ];
            }
            
            $details[$key]['pcs'] += $record['pcs'];
            $details[$key]['subtotal'] += $record['subtotal'];
            $totalPcs += $record['pcs'];
            $totalSalary += $record['subtotal'];
        }
        
        return [
            'details' => array_values($details),
            'total_pcs' => $totalPcs,
            'total_salary' => $totalSalary
        ];
    }
    
    public function createPayroll($data, $details) {
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Insert payroll
            $payrollId = $this->db->insert('payroll', $data);
            
            if (!$payrollId) {
                throw new Exception('Failed to create payroll');
            }
            
            // Insert payroll details
            foreach ($details as $detail) {
                $detailData = [
                    'payroll_id' => $payrollId,
                    'product_type_id' => $detail['product_type_id'],
                    'product_name' => $detail['product_name'],
                    'work_type' => $detail['work_type'],
                    'pcs' => $detail['pcs'],
                    'rate_per_pcs' => $detail['rate_per_pcs'],
                    'subtotal' => $detail['subtotal']
                ];
                
                $this->db->insert('payroll_details', $detailData);
            }
            
            $this->db->commit();
            return $payrollId;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
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
        
        if (!empty($filters['payment_status'])) {
            $sql .= " AND p.payment_status = ?";
            $params[] = $filters['payment_status'];
        }
        
        if (!empty($filters['period_start'])) {
            $sql .= " AND p.period_start >= ?";
            $params[] = $filters['period_start'];
        }
        
        $sql .= " ORDER BY p.period_start DESC";
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getPayrollById($id) {
        $payroll = $this->db->fetchOne("SELECT p.*, e.full_name, e.employee_id as emp_code
                                        FROM payroll p
                                        JOIN employees e ON p.employee_id = e.id
                                        WHERE p.id = ?", [$id]);
        
        if ($payroll) {
            // Get details
            $details = $this->db->fetchAll(
                "SELECT pd.*, pt.name as product_type_name
                 FROM payroll_details pd
                 JOIN product_types pt ON pd.product_type_id = pt.id
                 WHERE pd.payroll_id = ?
                 ORDER BY pt.name, pd.work_type",
                [$id]
            );
            
            $payroll['details'] = $details;
        }
        
        return $payroll;
    }
    
    public function updatePayrollStatus($id, $status, $paymentDate = null) {
        $data = ['payment_status' => $status];
        
        if ($paymentDate) {
            $data['payment_date'] = $paymentDate;
        }
        
        return $this->db->update('payroll', $data, 'id = ?', [$id]);
    }
    
    public function deletePayroll($id) {
        // Will cascade delete payroll_details
        return $this->db->delete('payroll', 'id = ?', [$id]);
    }
}
