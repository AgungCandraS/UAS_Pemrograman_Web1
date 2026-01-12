<?php
/**
 * Product Model - Inventory Management
 */

class Product {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll($limit = null, $offset = 0, $search = '') {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE 1=1";
        
        $params = [];
        if ($search) {
            $sql .= " AND (p.name LIKE ? OR p.sku LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function findById($id) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function create($data) {
        return $this->db->insert('products', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('products', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('products', 'id = ?', [$id]);
    }
    
    public function getLowStock() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.stock <= p.min_stock AND p.status = 'active'
                ORDER BY p.stock ASC";
        return $this->db->fetchAll($sql);
    }
    
    public function count($search = '') {
        $sql = "SELECT COUNT(*) as total FROM products WHERE 1=1";
        $params = [];
        
        if ($search) {
            $sql .= " AND (name LIKE ? OR sku LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }
    
    public function getCategories() {
        return $this->db->fetchAll("SELECT * FROM categories ORDER BY name ASC");
    }
}
