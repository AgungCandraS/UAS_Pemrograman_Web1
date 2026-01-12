<?php
/**
 * Order Model - Order Management
 */

class Order {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll($status = null, $limit = null, $offset = 0, $search = '', $paymentStatus = null) {
        $sql = "SELECT * FROM orders WHERE 1=1";
        $params = [];
        
        if ($status) {
            $sql .= " AND order_status = ?";
            $params[] = $status;
        }
        
        if ($paymentStatus) {
            $sql .= " AND payment_status = ?";
            $params[] = $paymentStatus;
        }
        
        if ($search) {
            $sql .= " AND (order_number LIKE ? OR customer_name LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function findById($id) {
        return $this->db->fetchOne("SELECT * FROM orders WHERE id = ?", [$id]);
    }
    
    public function getOrderItems($orderId) {
        return $this->db->fetchAll(
            "SELECT * FROM order_items WHERE order_id = ?",
            [$orderId]
        );
    }
    
    public function create($orderData, $items) {
        $this->db->beginTransaction();
        
        try {
            // Generate order number
            $orderNumber = $this->generateOrderNumber();
            $orderData['order_number'] = $orderNumber;
            
            // Insert order
            $orderId = $this->db->insert('orders', $orderData);
            
            // Insert order items
            foreach ($items as $item) {
                $item['order_id'] = $orderId;
                $this->db->insert('order_items', $item);
            }
            
            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    public function updateStatus($id, $status) {
        return $this->db->update('orders', ['order_status' => $status], 'id = ?', [$id]);
    }
    
    public function update($id, $data) {
        return $this->db->update('orders', $data, 'id = ?', [$id]);
    }
    
    public function delete($id) {
        return $this->db->delete('orders', 'id = ?', [$id]);
    }
    
    public function count($status = null, $search = '', $paymentStatus = null) {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE 1=1";
        $params = [];
        
        if ($status) {
            $sql .= " AND order_status = ?";
            $params[] = $status;
        }
        
        if ($paymentStatus) {
            $sql .= " AND payment_status = ?";
            $params[] = $paymentStatus;
        }
        
        if ($search) {
            $sql .= " AND (order_number LIKE ? OR customer_name LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }
    
    private function generateOrderNumber() {
        $date = date('Ymd');
        $sql = "SELECT COUNT(*) as total FROM orders WHERE order_number LIKE ?";
        $result = $this->db->fetchOne($sql, ["ORD-{$date}-%"]);
        $num = ($result['total'] ?? 0) + 1;
        
        return sprintf('ORD-%s-%04d', $date, $num);
    }
}
