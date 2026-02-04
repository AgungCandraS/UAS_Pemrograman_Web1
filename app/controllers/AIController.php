<?php
/**
 * AI Controller - Intelligent Business Assistant
 * Provides AI-powered insights, analytics, and recommendations
 */

class AIController {
    private $db;
    private $userId;
    
    public function __construct() {
        require_auth();
        $this->db = Database::getInstance();
        $this->userId = auth_user()['id'];
    }
    
    public function index() {
        // Get recent chat history (last 20 messages)
        $conversations = $this->db->fetchAll(
            "SELECT * FROM ai_conversations 
             WHERE user_id = ? 
             ORDER BY created_at DESC LIMIT 20",
            [$this->userId]
        );
        
        // Get quick stats for dashboard
        $stats = $this->getQuickStats();
        
        // Get business insights
        $insights = $this->generateInsights();
        
        ob_start();
        include APP_PATH . '/views/ai/index.php';
        $content = ob_get_clean();
        
        $pageTitle = 'AI Business Assistant';
        $activePage = 'ai';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function chat() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['success' => false, 'message' => 'Invalid request method'], 405);
        }
        
        $message = trim(post('message'));
        
        if (empty($message)) {
            json_response(['success' => false, 'message' => 'Message cannot be empty'], 400);
        }
        
        try {
            // Generate intelligent response
            $response = $this->generateResponse($message);
            
            // Analyze sentiment and context
            $sentiment = $this->analyzeSentiment($message);
            $context = $this->extractContext($message);
            
            // Try to save conversation with context (optional, don't fail if table doesn't exist)
            try {
                $this->db->insert('ai_conversations', [
                    'user_id' => $this->userId,
                    'message' => $message,
                    'response' => $response,
                    'context' => json_encode([
                        'sentiment' => $sentiment,
                        'keywords' => $context,
                        'timestamp' => date('Y-m-d H:i:s')
                    ])
                ]);
            } catch (Exception $e) {
                // Ignore if table doesn't exist, just continue
                error_log('AI conversation save failed: ' . $e->getMessage());
            }
            
            json_response([
                'success' => true,
                'response' => $response,
                'sentiment' => $sentiment,
                'suggestions' => $this->getSuggestions($context)
            ]);
        } catch (Exception $e) {
            json_response([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function insights() {
        // Generate comprehensive business insights
        $insights = $this->generateInsights();
        
        ob_start();
        include APP_PATH . '/views/ai/insights.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Business Insights';
        $activePage = 'ai';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function recommendations() {
        // Generate AI recommendations based on business data
        $recommendations = $this->generateRecommendations();
        
        json_response([
            'success' => true,
            'recommendations' => $recommendations
        ]);
    }
    
    public function analytics() {
        // Get comprehensive analytics
        $analytics = [
            'sales' => $this->getSalesAnalytics(),
            'inventory' => $this->getInventoryAnalytics(),
            'finance' => $this->getFinanceAnalytics(),
            'hr' => $this->getHRAnalytics()
        ];
        
        json_response([
            'success' => true,
            'analytics' => $analytics
        ]);
    }
    
    public function clearHistory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            json_response(['success' => false, 'message' => 'Invalid request method'], 405);
        }
        
        $this->db->query(
            "DELETE FROM ai_conversations WHERE user_id = ?",
            [$this->userId]
        );
        
        json_response([
            'success' => true,
            'message' => 'Chat history cleared successfully'
        ]);
    }
    
    // ==================== PRIVATE HELPER METHODS ====================
    
    private function getQuickStats() {
        // Sales today
        $salesToday = $this->db->fetchOne(
            "SELECT COALESCE(SUM(total), 0) as total 
             FROM sales 
             WHERE DATE(created_at) = CURDATE()"
        );
        
        // Low stock count
        $lowStock = $this->db->fetchOne(
            "SELECT COUNT(*) as count 
             FROM products 
             WHERE stock <= min_stock AND status = 'active'"
        );
        
        // Active employees
        $employees = $this->db->fetchOne(
            "SELECT COUNT(*) as count 
             FROM employees 
             WHERE status = 'active'"
        );
        
        // Recent orders/sales (today)
        $orders = $this->db->fetchOne(
            "SELECT COUNT(*) as count 
             FROM sales 
             WHERE DATE(created_at) = CURDATE()"
        );
        
        return [
            'sales_today' => $salesToday['total'] ?? 0,
            'low_stock' => $lowStock['count'] ?? 0,
            'employees' => $employees['count'] ?? 0,
            'pending_orders' => $orders['count'] ?? 0
        ];
    }
    
    private function generateResponse($message) {
        $message = strtolower($message);
        
        // Analyze keywords
        $keywords = $this->extractContext($message);
        
        // Sales/Penjualan Analysis
        if (in_array('sales', $keywords) || in_array('penjualan', $keywords)) {
            return $this->getSalesResponse();
        }
        
        // Inventory/Stock Analysis - also check for "rendah" (low stock)
        if (in_array('inventory', $keywords) || in_array('stok', $keywords) || 
            in_array('produk', $keywords) || in_array('rendah', $keywords) || 
            in_array('low', $keywords)) {
            return $this->getInventoryResponse();
        }
        
        // HR/Employee Analysis
        if (in_array('hr', $keywords) || in_array('karyawan', $keywords) || in_array('employee', $keywords)) {
            return $this->getHRResponse();
        }
        
        // Finance Analysis
        if (in_array('finance', $keywords) || in_array('keuangan', $keywords) || in_array('transaksi', $keywords)) {
            return $this->getFinanceResponse();
        }
        
        // Orders Analysis
        if (in_array('order', $keywords) || in_array('pesanan', $keywords)) {
            return $this->getOrdersResponse();
        }
        
        // Performance/Analytics
        if (in_array('performa', $keywords) || in_array('analytics', $keywords) || in_array('analisis', $keywords)) {
            return $this->getPerformanceResponse();
        }
        
        // Recommendations - also check for "bisnis"
        if (in_array('rekomendasi', $keywords) || in_array('saran', $keywords) || 
            in_array('recommendation', $keywords) || in_array('bisnis', $keywords) || 
            in_array('business', $keywords)) {
            return $this->getRecommendationsText();
        }
        
        // Default helpful response
        return $this->getDefaultResponse();
    }
    
    private function getSalesResponse() {
        try {
            // Get sales data
            $today = $this->db->fetchOne(
                "SELECT COALESCE(SUM(total), 0) as total, COUNT(*) as count 
                 FROM sales 
                 WHERE DATE(created_at) = CURDATE()"
            );
            
            $thisMonth = $this->db->fetchOne(
                "SELECT COALESCE(SUM(total), 0) as total, COUNT(*) as count 
                 FROM sales 
                 WHERE MONTH(created_at) = MONTH(CURDATE()) 
                 AND YEAR(created_at) = YEAR(CURDATE())"
            );
            
            $lastMonth = $this->db->fetchOne(
                "SELECT COALESCE(SUM(total), 0) as total 
                 FROM sales 
                 WHERE MONTH(created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) 
                 AND YEAR(created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))"
            );
            
            $growth = $lastMonth['total'] > 0 
                ? (($thisMonth['total'] - $lastMonth['total']) / $lastMonth['total']) * 100 
                : 0;
            
            $topProduct = $this->db->fetchOne(
                "SELECT p.name, SUM(si.quantity) as qty 
                 FROM sale_items si 
                 JOIN products p ON si.product_id = p.id 
                 JOIN sales s ON si.sale_id = s.id
                 WHERE MONTH(s.created_at) = MONTH(CURDATE())
                 GROUP BY si.product_id 
                 ORDER BY qty DESC 
                 LIMIT 1"
            );
            
            $response = "📊 <strong>Analisis Penjualan</strong><br><br>";
            $response .= "• Hari ini: Rp " . number_format($today['total'], 0, ',', '.') . " dari " . $today['count'] . " transaksi<br>";
            $response .= "• Bulan ini: Rp " . number_format($thisMonth['total'], 0, ',', '.') . " dari " . $thisMonth['count'] . " transaksi<br>";
            $response .= "• Pertumbuhan: " . ($growth >= 0 ? '+' : '') . number_format($growth, 1) . "% vs bulan lalu<br>";
            
            if ($topProduct) {
                $response .= "• Produk terlaris: <strong>" . htmlspecialchars($topProduct['name']) . "</strong> (" . $topProduct['qty'] . " terjual)<br>";
            }
            
            $response .= "<br>💡 ";
            if ($growth > 10) {
                $response .= "Performa sangat baik! Pertahankan strategi marketing saat ini.";
            } elseif ($growth > 0) {
                $response .= "Penjualan menunjukkan tren positif. Coba tingkatkan promosi untuk hasil lebih optimal.";
            } else {
                $response .= "Penjualan perlu ditingkatkan. Pertimbangkan strategi promosi baru atau diskon menarik.";
            }
            
            return $response;
        } catch (Exception $e) {
            return "📊 <strong>Analisis Penjualan</strong><br><br>Maaf, terjadi kesalahan saat mengambil data penjualan. Silakan coba lagi nanti.";
        }
    }
    
    private function getInventoryResponse() {
        try {
            $lowStock = $this->db->fetchAll(
                "SELECT name, stock, min_stock 
                 FROM products 
                 WHERE stock <= min_stock AND status = 'active' 
                 ORDER BY (stock / NULLIF(min_stock, 1)) ASC 
                 LIMIT 5"
            );
            
            $totalProducts = $this->db->fetchOne(
                "SELECT COUNT(*) as count 
                 FROM products 
                 WHERE status = 'active'"
            );
            
            $totalValue = $this->db->fetchOne(
                "SELECT SUM(stock * purchase_price) as value 
                 FROM products 
                 WHERE status = 'active'"
            );
            
            $response = "📦 <strong>Analisis Inventory</strong><br><br>";
            $response .= "• Total Produk Aktif: " . $totalProducts['count'] . " items<br>";
            $response .= "• Nilai Inventory: Rp " . number_format($totalValue['value'] ?? 0, 0, ',', '.') . "<br>";
            $response .= "• Produk Stok Rendah: <strong>" . count($lowStock) . " items</strong><br><br>";
            
            if (!empty($lowStock)) {
                $response .= "⚠️ <strong>Perlu Restok Segera:</strong><br>";
                foreach ($lowStock as $i => $product) {
                    $urgency = $product['stock'] == 0 ? '🔴' : ($product['stock'] <= $product['min_stock'] / 2 ? '🟠' : '🟡');
                    $response .= $urgency . " " . ($i + 1) . ". " . htmlspecialchars($product['name']) . 
                               " (Sisa: " . $product['stock'] . ", Min: " . $product['min_stock'] . ")<br>";
                }
                $response .= "<br>💡 Segera lakukan pemesanan untuk menghindari kehabisan stok.";
            } else {
                $response .= "✅ Semua produk memiliki stok yang cukup. Inventory dalam kondisi baik!";
            }
            
            return $response;
        } catch (Exception $e) {
            return "📦 <strong>Analisis Inventory</strong><br><br>Maaf, terjadi kesalahan saat mengambil data inventory. Silakan coba lagi nanti.";
        }
    }
    
    private function getHRResponse() {
        $activeEmployees = $this->db->fetchOne(
            "SELECT COUNT(*) as count 
             FROM employees 
             WHERE status = 'active'"
        );
        
        $departments = $this->db->fetchAll(
            "SELECT department, COUNT(*) as count 
             FROM employees 
             WHERE status = 'active' 
             GROUP BY department 
             ORDER BY count DESC"
        );
        
        $avgSalary = $this->db->fetchOne(
            "SELECT AVG(salary) as avg 
             FROM employees 
             WHERE status = 'active'"
        );
        
        $attendance = $this->db->fetchOne(
            "SELECT 
                COUNT(DISTINCT CASE WHEN status = 'present' THEN employee_id END) * 100.0 / 
                COUNT(DISTINCT employee_id) as rate 
             FROM attendance 
             WHERE MONTH(date) = MONTH(CURDATE())"
        );
        
        $response = "👥 <strong>Analisis HR & Karyawan</strong><br><br>";
        $response .= "• Karyawan Aktif: <strong>" . $activeEmployees['count'] . " orang</strong><br>";
        $response .= "• Rata-rata Gaji: Rp " . number_format($avgSalary['avg'], 0, ',', '.') . "<br>";
        
        if ($attendance) {
            $response .= "• Tingkat Kehadiran: " . number_format($attendance['rate'], 1) . "%<br>";
        }
        
        $response .= "<br>📊 <strong>Distribusi Departemen:</strong><br>";
        foreach ($departments as $dept) {
            $response .= "• " . htmlspecialchars($dept['department']) . ": " . $dept['count'] . " karyawan<br>";
        }
        
        $response .= "<br>💡 ";
        if ($attendance && $attendance['rate'] >= 95) {
            $response .= "Tingkat kehadiran sangat baik! Tim Anda sangat disiplin.";
        } elseif ($attendance && $attendance['rate'] >= 85) {
            $response .= "Kehadiran baik, tapi masih bisa ditingkatkan dengan sistem insentif.";
        } else {
            $response .= "Perlu perhatian khusus untuk meningkatkan kedisiplinan kehadiran.";
        }
        
        return $response;
    }
    
    private function getFinanceResponse() {
        $thisMonth = $this->db->fetchOne(
            "SELECT 
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense,
                COUNT(*) as count
             FROM transactions 
             WHERE MONTH(transaction_date) = MONTH(CURDATE()) 
             AND YEAR(transaction_date) = YEAR(CURDATE())"
        );
        
        $lastMonth = $this->db->fetchOne(
            "SELECT 
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
             FROM transactions 
             WHERE MONTH(transaction_date) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) 
             AND YEAR(transaction_date) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))"
        );
        
        $profit = $thisMonth['income'] - $thisMonth['expense'];
        $profitMargin = $thisMonth['income'] > 0 ? ($profit / $thisMonth['income']) * 100 : 0;
        
        $topExpenses = $this->db->fetchAll(
            "SELECT category, SUM(amount) as total 
             FROM transactions 
             WHERE type = 'expense' 
             AND MONTH(transaction_date) = MONTH(CURDATE())
             GROUP BY category 
             ORDER BY total DESC 
             LIMIT 3"
        );
        
        $response = "💰 <strong>Analisis Keuangan</strong><br><br>";
        $response .= "• Pemasukan: Rp " . number_format($thisMonth['income'], 0, ',', '.') . "<br>";
        $response .= "• Pengeluaran: Rp " . number_format($thisMonth['expense'], 0, ',', '.') . "<br>";
        $response .= "• <strong>Profit: Rp " . number_format($profit, 0, ',', '.') . "</strong><br>";
        $response .= "• Profit Margin: " . number_format($profitMargin, 1) . "%<br>";
        $response .= "• Total Transaksi: " . $thisMonth['count'] . " transaksi<br>";
        
        if (!empty($topExpenses)) {
            $response .= "<br>📉 <strong>Pengeluaran Terbesar:</strong><br>";
            foreach ($topExpenses as $i => $exp) {
                $response .= "• " . ($i + 1) . ". " . htmlspecialchars($exp['category']) . 
                           ": Rp " . number_format($exp['total'], 0, ',', '.') . "<br>";
            }
        }
        
        $response .= "<br>💡 ";
        if ($profitMargin >= 30) {
            $response .= "Profit margin sangat sehat! Bisnis Anda berkembang dengan baik.";
        } elseif ($profitMargin >= 15) {
            $response .= "Profit margin baik. Pertimbangkan untuk ekspansi atau investasi.";
        } elseif ($profitMargin > 0) {
            $response .= "Profit margin tipis. Evaluasi pengeluaran dan optimalkan efisiensi.";
        } else {
            $response .= "Perlu perhatian serius! Segera evaluasi strategi bisnis dan kurangi pengeluaran.";
        }
        
        return $response;
    }
    
    private function getOrdersResponse() {
        // Total sales/orders statistics
        $totalOrders = $this->db->fetchOne(
            "SELECT COUNT(*) as count, SUM(total) as amount 
             FROM sales 
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)"
        );
        
        $todayOrders = $this->db->fetchOne(
            "SELECT COUNT(*) as count 
             FROM sales 
             WHERE DATE(created_at) = CURDATE()"
        );
        
        $thisMonth = $this->db->fetchOne(
            "SELECT COUNT(*) as count 
             FROM sales 
             WHERE MONTH(created_at) = MONTH(CURDATE())"
        );
        
        $response = "🛒 <strong>Analisis Orders/Penjualan (30 Hari Terakhir)</strong><br><br>";
        $response .= "• Total Orders: <strong>" . $totalOrders['count'] . " transaksi</strong><br>";
        $response .= "• Total Nilai: Rp " . number_format($totalOrders['amount'], 0, ',', '.') . "<br>";
        $response .= "• Orders Hari Ini: " . $todayOrders['count'] . " transaksi<br>";
        $response .= "• Orders Bulan Ini: " . $thisMonth['count'] . " transaksi<br>";
        
        $avgOrderValue = $totalOrders['count'] > 0 ? $totalOrders['amount'] / $totalOrders['count'] : 0;
        $response .= "• Rata-rata Nilai Order: Rp " . number_format($avgOrderValue, 0, ',', '.') . "<br>";
        
        $response .= "<br>💡 ";
        if ($todayOrders['count'] >= 10) {
            $response .= "Volume order hari ini sangat baik! Pertahankan layanan yang responsif.";
        } elseif ($todayOrders['count'] >= 5) {
            $response .= "Volume order stabil. Tingkatkan promosi untuk hasil lebih optimal.";
        } else {
            $response .= "Volume order masih bisa ditingkatkan. Pertimbangkan strategi marketing baru.";
        }
        
        return $response;
    }
    
    private function getPerformanceResponse() {
        $stats = $this->getQuickStats();
        
        $response = "📈 <strong>Performance Overview</strong><br><br>";
        $response .= "• Penjualan Hari Ini: Rp " . number_format($stats['sales_today'], 0, ',', '.') . "<br>";
        $response .= "• Karyawan Aktif: " . $stats['employees'] . " orang<br>";
        $response .= "• Orders Hari Ini: " . $stats['pending_orders'] . " transaksi<br>";
        $response .= "• Produk Low Stock: " . $stats['low_stock'] . " items<br>";
        
        $response .= "<br>💡 Dashboard menunjukkan performa bisnis Anda secara real-time. ";
        $response .= "Gunakan menu Analytics untuk insight lebih detail.";
        
        return $response;
    }
    
    private function getRecommendationsText() {
        try {
            $recommendations = $this->generateRecommendations();
            
            $response = "💡 <strong>Rekomendasi AI untuk Bisnis Anda:</strong><br><br>";
            
            if (empty($recommendations)) {
                $response .= "Saat ini tidak ada rekomendasi khusus. Bisnis Anda berjalan dengan baik! 🎉<br><br>";
                $response .= "Tetap pantau performa secara berkala untuk hasil optimal.";
            } else {
                foreach ($recommendations as $i => $rec) {
                    $icon = ['🎯', '📊', '💼', '🚀', '⚡'];
                    $response .= $icon[$i % 5] . " " . ($i + 1) . ". " . $rec . "<br>";
                }
                $response .= "<br>Implementasikan rekomendasi ini untuk meningkatkan performa bisnis Anda.";
            }
            
            return $response;
        } catch (Exception $e) {
            return "💡 <strong>Rekomendasi AI</strong><br><br>Maaf, terjadi kesalahan saat menghasilkan rekomendasi. Silakan coba lagi nanti.";
        }
    }
    
    private function getDefaultResponse() {
        return "👋 <strong>Halo! Saya AI Assistant Bisnisku</strong><br><br>" .
               "Saya dapat membantu Anda dengan:<br>" .
               "📊 Analisis Penjualan & Performance<br>" .
               "📦 Monitoring Inventory & Stock<br>" .
               "💰 Laporan Keuangan & Profit<br>" .
               "👥 Manajemen HR & Karyawan<br>" .
               "🛒 Tracking Orders & Fulfillment<br>" .
               "💡 Business Insights & Recommendations<br><br>" .
               "Silakan tanyakan tentang aspek bisnis yang ingin Anda ketahui!";
    }
    
    private function generateInsights() {
        $insights = [];
        
        // Sales Insight
        $salesGrowth = $this->db->fetchOne(
            "SELECT 
                (SELECT COALESCE(SUM(total), 0) FROM sales WHERE MONTH(created_at) = MONTH(CURDATE())) as current,
                (SELECT COALESCE(SUM(total), 0) FROM sales WHERE MONTH(created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))) as previous"
        );
        
        if ($salesGrowth['previous'] > 0) {
            $growth = (($salesGrowth['current'] - $salesGrowth['previous']) / $salesGrowth['previous']) * 100;
            $insights[] = [
                'icon' => 'fa-chart-line',
                'title' => 'Penjualan ' . ($growth >= 0 ? 'Meningkat' : 'Menurun'),
                'description' => 'Penjualan bulan ini ' . ($growth >= 0 ? 'naik' : 'turun') . ' ' . abs(round($growth, 1)) . '% dibanding bulan lalu',
                'type' => $growth >= 10 ? 'success' : ($growth >= 0 ? 'info' : 'warning'),
                'action' => $growth >= 0 ? 'Pertahankan momentum' : 'Tingkatkan promosi',
                'value' => 'Rp ' . number_format($salesGrowth['current'], 0, ',', '.')
            ];
        }
        
        // Inventory Insight
        $lowStock = $this->db->fetchOne(
            "SELECT COUNT(*) as count FROM products WHERE stock <= min_stock AND status = 'active'"
        );
        
        if ($lowStock['count'] > 0) {
            $insights[] = [
                'icon' => 'fa-boxes',
                'title' => 'Stok Menipis',
                'description' => $lowStock['count'] . ' produk memerlukan restok segera',
                'type' => $lowStock['count'] > 5 ? 'danger' : 'warning',
                'action' => 'Lakukan pemesanan ulang',
                'value' => $lowStock['count'] . ' items'
            ];
        } else {
            $insights[] = [
                'icon' => 'fa-boxes',
                'title' => 'Inventory Sehat',
                'description' => 'Semua produk memiliki stok yang mencukupi',
                'type' => 'success',
                'action' => 'Pertahankan monitoring rutin',
                'value' => 'Optimal'
            ];
        }
        
        // Finance Insight
        $profit = $this->db->fetchOne(
            "SELECT 
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
             FROM transactions 
             WHERE MONTH(transaction_date) = MONTH(CURDATE())"
        );
        
        $profitAmount = $profit['income'] - $profit['expense'];
        $profitMargin = $profit['income'] > 0 ? ($profitAmount / $profit['income']) * 100 : 0;
        
        $insights[] = [
            'icon' => 'fa-wallet',
            'title' => 'Profit Margin ' . ($profitMargin >= 25 ? 'Excellent' : ($profitMargin >= 15 ? 'Baik' : 'Perlu Perhatian')),
            'description' => 'Profit margin bulan ini mencapai ' . round($profitMargin, 1) . '%',
            'type' => $profitMargin >= 25 ? 'success' : ($profitMargin >= 15 ? 'info' : 'warning'),
            'action' => $profitMargin >= 25 ? 'Ekspansi produk baru' : 'Optimalkan efisiensi',
            'value' => round($profitMargin, 1) . '%'
        ];
        
        // HR Insight
        $attendance = $this->db->fetchOne(
            "SELECT 
                COUNT(DISTINCT CASE WHEN status = 'present' THEN employee_id END) * 100.0 / 
                NULLIF(COUNT(DISTINCT employee_id), 0) as rate 
             FROM attendance 
             WHERE MONTH(date) = MONTH(CURDATE()) 
             AND YEAR(date) = YEAR(CURDATE())"
        );
        
        if ($attendance && $attendance['rate'] !== null) {
            $insights[] = [
                'icon' => 'fa-users',
                'title' => 'Kehadiran ' . ($attendance['rate'] >= 95 ? 'Excellent' : ($attendance['rate'] >= 85 ? 'Baik' : 'Perlu Ditingkatkan')),
                'description' => 'Tingkat kehadiran karyawan ' . round($attendance['rate'], 1) . '%',
                'type' => $attendance['rate'] >= 95 ? 'success' : ($attendance['rate'] >= 85 ? 'info' : 'warning'),
                'action' => $attendance['rate'] >= 95 ? 'Berikan apresiasi' : 'Tingkatkan disiplin',
                'value' => round($attendance['rate'], 1) . '%'
            ];
        }
        
        // Orders Insight
        $todayOrders = $this->db->fetchOne(
            "SELECT COUNT(*) as count FROM sales WHERE DATE(created_at) = CURDATE()"
        );
        
        if ($todayOrders['count'] > 0) {
            $insights[] = [
                'icon' => 'fa-shopping-cart',
                'title' => 'Orders Hari Ini',
                'description' => $todayOrders['count'] . ' transaksi penjualan hari ini',
                'type' => $todayOrders['count'] >= 10 ? 'success' : ($todayOrders['count'] >= 5 ? 'info' : 'warning'),
                'action' => $todayOrders['count'] >= 10 ? 'Pertahankan performa' : 'Tingkatkan promosi',
                'value' => $todayOrders['count'] . ' orders'
            ];
        }
        
        return $insights;
    }
    
    private function generateRecommendations() {
        $recommendations = [];
        
        try {
            // Sales recommendations
            $topSellingCategory = $this->db->fetchOne(
                "SELECT c.name as category, COUNT(*) as count 
                 FROM sale_items si 
                 JOIN products p ON si.product_id = p.id 
                 JOIN categories c ON p.category_id = c.id
                 JOIN sales s ON si.sale_id = s.id
                 WHERE s.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                 GROUP BY p.category_id 
                 ORDER BY count DESC 
                 LIMIT 1"
            );
            
            if ($topSellingCategory) {
                $recommendations[] = "Tingkatkan stok kategori <strong>" . htmlspecialchars($topSellingCategory['category']) . "</strong> yang sedang trending";
            }
        } catch (Exception $e) {
            // Ignore error, continue with other recommendations
        }
        
        try {
            // Inventory recommendations
            $slowMoving = $this->db->fetchOne(
                "SELECT COUNT(*) as count 
                 FROM products p 
                 WHERE NOT EXISTS (
                     SELECT 1 FROM sale_items si 
                     WHERE si.product_id = p.id 
                     AND si.created_at >= DATE_SUB(NOW(), INTERVAL 60 DAY)
                 ) AND p.status = 'active' AND p.stock > 0"
            );
            
            if ($slowMoving['count'] > 0) {
                $recommendations[] = "Pertimbangkan memberikan <strong>diskon</strong> untuk " . $slowMoving['count'] . " produk slow-moving";
            }
        } catch (Exception $e) {
            // Ignore error
        }
        
        try {
            // HR recommendations
            $employeeCount = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM employees WHERE status = 'active'"
            );
            
            $orderLoad = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM sales WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
            );
            
            if ($employeeCount['count'] > 0 && $orderLoad['count'] / $employeeCount['count'] > 10) {
                $recommendations[] = "Volume order tinggi. Pertimbangkan <strong>rekrut karyawan baru</strong> untuk meningkatkan efisiensi";
            }
        } catch (Exception $e) {
            // Ignore error
        }
        
        try {
            // Finance recommendations
            $expenses = $this->db->fetchOne(
                "SELECT SUM(amount) as total 
                 FROM transactions 
                 WHERE type = 'expense' 
                 AND MONTH(transaction_date) = MONTH(CURDATE())"
            );
            
            $income = $this->db->fetchOne(
                "SELECT SUM(amount) as total 
                 FROM transactions 
                 WHERE type = 'income' 
                 AND MONTH(transaction_date) = MONTH(CURDATE())"
            );
            
            if ($expenses['total'] && $income['total']) {
                $expenseRatio = ($expenses['total'] / $income['total']) * 100;
                if ($expenseRatio > 70) {
                    $recommendations[] = "Rasio pengeluaran tinggi (" . round($expenseRatio, 1) . "%). <strong>Optimalkan efisiensi operasional</strong>";
                }
            }
        } catch (Exception $e) {
            // Ignore error
        }
        
        // General recommendations (always added)
        $recommendations[] = "Lakukan <strong>review bulanan</strong> terhadap performa semua departemen";
        $recommendations[] = "Gunakan <strong>data analytics</strong> untuk decision making yang lebih akurat";
        $recommendations[] = "Tingkatkan <strong>customer satisfaction</strong> dengan layanan yang lebih responsif";
        
        return $recommendations;
    }
    
    private function getSalesAnalytics() {
        return [
            'total_today' => $this->db->fetchOne("SELECT COALESCE(SUM(total), 0) as amount FROM sales WHERE DATE(created_at) = CURDATE()"),
            'total_month' => $this->db->fetchOne("SELECT COALESCE(SUM(total), 0) as amount FROM sales WHERE MONTH(created_at) = MONTH(CURDATE())"),
            'top_products' => $this->db->fetchAll("SELECT p.name, SUM(si.quantity) as qty FROM sale_items si JOIN products p ON si.product_id = p.id GROUP BY si.product_id ORDER BY qty DESC LIMIT 5")
        ];
    }
    
    private function getInventoryAnalytics() {
        return [
            'total_products' => $this->db->fetchOne("SELECT COUNT(*) as count FROM products WHERE status = 'active'"),
            'low_stock' => $this->db->fetchAll("SELECT name, stock, min_stock FROM products WHERE stock <= min_stock ORDER BY stock ASC LIMIT 10"),
            'total_value' => $this->db->fetchOne("SELECT SUM(stock * purchase_price) as value FROM products WHERE status = 'active'")
        ];
    }
    
    private function getFinanceAnalytics() {
        return [
            'income' => $this->db->fetchOne("SELECT SUM(amount) as total FROM transactions WHERE type = 'income' AND MONTH(transaction_date) = MONTH(CURDATE())"),
            'expense' => $this->db->fetchOne("SELECT SUM(amount) as total FROM transactions WHERE type = 'expense' AND MONTH(transaction_date) = MONTH(CURDATE())"),
            'by_category' => $this->db->fetchAll("SELECT category, SUM(amount) as total FROM transactions WHERE MONTH(transaction_date) = MONTH(CURDATE()) GROUP BY category ORDER BY total DESC")
        ];
    }
    
    private function getHRAnalytics() {
        return [
            'total_employees' => $this->db->fetchOne("SELECT COUNT(*) as count FROM employees WHERE status = 'active'"),
            'by_department' => $this->db->fetchAll("SELECT department, COUNT(*) as count FROM employees WHERE status = 'active' GROUP BY department"),
            'attendance_rate' => $this->db->fetchOne("SELECT COUNT(DISTINCT CASE WHEN status = 'present' THEN employee_id END) * 100.0 / NULLIF(COUNT(DISTINCT employee_id), 0) as rate FROM attendance WHERE MONTH(date) = MONTH(CURDATE())")
        ];
    }
    
    private function analyzeSentiment($message) {
        $positive = ['bagus', 'baik', 'senang', 'terima kasih', 'mantap', 'excellent', 'good', 'thanks', 'great'];
        $negative = ['buruk', 'jelek', 'kecewa', 'masalah', 'error', 'bad', 'poor', 'problem', 'issue'];
        
        $message = strtolower($message);
        
        foreach ($positive as $word) {
            if (strpos($message, $word) !== false) {
                return 'positive';
            }
        }
        
        foreach ($negative as $word) {
            if (strpos($message, $word) !== false) {
                return 'negative';
            }
        }
        
        return 'neutral';
    }
    
    private function extractContext($message) {
        $keywords = [
            'sales', 'penjualan', 'jual', 'revenue', 
            'inventory', 'stok', 'produk', 'barang', 'stock', 'rendah', 'low',
            'hr', 'karyawan', 'employee', 'staff', 'pegawai',
            'finance', 'keuangan', 'transaksi', 'uang', 'money', 'payment',
            'order', 'pesanan', 'pemesanan',
            'performa', 'performance', 'analytics', 'analisis',
            'rekomendasi', 'saran', 'recommendation', 'suggest', 'bisnis', 'business'
        ];
        
        $message = strtolower($message);
        $found = [];
        
        foreach ($keywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                $found[] = $keyword;
            }
        }
        
        return $found;
    }
    
    private function getSuggestions($keywords) {
        $suggestions = [
            'Analisis penjualan bulan ini',
            'Tampilkan produk stok rendah',
            'Berikan laporan keuangan',
            'Status karyawan dan kehadiran',
            'Rekomendasi untuk bisnis saya'
        ];
        
        if (in_array('sales', $keywords) || in_array('penjualan', $keywords)) {
            return [
                'Tampilkan produk terlaris',
                'Bandingkan penjualan bulan ini vs bulan lalu',
                'Analisis penjualan per kategori'
            ];
        }
        
        if (in_array('inventory', $keywords) || in_array('stok', $keywords)) {
            return [
                'Produk mana yang perlu direstok?',
                'Berapa nilai total inventory?',
                'Tampilkan produk slow-moving'
            ];
        }
        
        return $suggestions;
    }
}
