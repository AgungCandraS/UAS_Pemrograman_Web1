<?php
/**
 * Dashboard Controller
 */

class DashboardController {
    private $db;
    
    public function __construct() {
        require_auth();
        $this->db = Database::getInstance();
    }
    
    public function index() {
        // Get statistics
        $stats = $this->getStatistics();
        
        // Get recent orders
        $recentOrders = $this->db->fetchAll(
            "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5"
        );
        
        // Get low stock products
        $lowStockProducts = $this->db->fetchAll(
            "SELECT * FROM products WHERE stock <= min_stock AND status = 'active' LIMIT 5"
        );
        
        // Get monthly sales data
        $monthlySales = $this->getMonthlySales();
        
        ob_start();
        include APP_PATH . '/views/dashboard/index.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Dashboard';
        $activePage = 'dashboard';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function getStats() {
        $stats = $this->getStatistics();
        json_response($stats);
    }
    
    private function getStatistics() {
        // Total Revenue (paid orders)
        $revenue = $this->db->fetchOne(
            "SELECT COALESCE(SUM(total), 0) as total FROM orders WHERE payment_status = 'paid'"
        );
        
        // Total Orders
        $orders = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM orders"
        );
        
        // Total Products
        $products = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM products WHERE status = 'active'"
        );
        
        // Total Employees
        $employees = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM employees WHERE status = 'active'"
        );
        
        // Today's Sales
        $todaySales = $this->db->fetchOne(
            "SELECT COALESCE(SUM(total), 0) as total FROM orders 
             WHERE DATE(created_at) = CURDATE() AND payment_status = 'paid'"
        );
        
        // Pending Orders
        $pendingOrders = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM orders WHERE order_status = 'pending'"
        );
        
        return [
            'revenue' => $revenue['total'],
            'orders' => $orders['total'],
            'products' => $products['total'],
            'employees' => $employees['total'],
            'today_sales' => $todaySales['total'],
            'pending_orders' => $pendingOrders['total']
        ];
    }
    
    private function getMonthlySales() {
        return $this->db->fetchAll(
            "SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COALESCE(SUM(total), 0) as total
             FROM orders 
             WHERE payment_status = 'paid'
             AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
             GROUP BY DATE_FORMAT(created_at, '%Y-%m')
             ORDER BY month ASC"
        );
    }
}
