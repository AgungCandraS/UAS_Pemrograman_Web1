<?php
/**
 * Transaction Model - Finance Management
 */

class Transaction {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll($type = null, $startDate = null, $endDate = null, $limit = null, $offset = 0) {
        $sql = "SELECT * FROM transactions WHERE 1=1";
        $params = [];
        
        if ($type) {
            $sql .= " AND type = ?";
            $params[] = $type;
        }
        
        if ($startDate && $endDate) {
            $sql .= " AND transaction_date BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }
        
        $sql .= " ORDER BY transaction_date DESC, created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function findById($id) {
        return $this->db->fetchOne("SELECT * FROM transactions WHERE id = ?", [$id]);
    }
    
    public function getById($id) {
        return $this->db->fetchOne("SELECT * FROM transactions WHERE id = ?", [$id]);
    }
    
    public function create($data) {
        return $this->db->insert('transactions', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('transactions', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('transactions', 'id = ?', [$id]);
    }
    
    public function getSummary($startDate = null, $endDate = null) {
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as income,
                    COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as expense,
                    COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END), 0) as net_profit
                FROM transactions WHERE 1=1";
        
        $params = [];
        if ($startDate && $endDate) {
            $sql .= " AND transaction_date BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return [
            'income' => $result['income'] ?? 0,
            'expense' => $result['expense'] ?? 0,
            'net_profit' => $result['net_profit'] ?? 0
        ];
    }
    
    public function getMonthlyData($months = 6) {
        $sql = "SELECT 
                    DATE_FORMAT(transaction_date, '%Y-%m') as month,
                    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
                    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
                FROM transactions 
                WHERE transaction_date >= DATE_SUB(NOW(), INTERVAL ? MONTH)
                GROUP BY DATE_FORMAT(transaction_date, '%Y-%m')
                ORDER BY month ASC";
        
        return $this->db->fetchAll($sql, [$months]);
    }
}
