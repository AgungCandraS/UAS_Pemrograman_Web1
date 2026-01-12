<?php
/**
 * AI Controller - AI Assistant
 */

class AIController {
    private $db;
    
    public function __construct() {
        require_auth();
        $this->db = Database::getInstance();
    }
    
    public function index() {
        // Get chat history
        $conversations = $this->db->fetchAll(
            "SELECT * FROM ai_conversations WHERE user_id = ? ORDER BY created_at DESC LIMIT 20",
            [auth_user()['id']]
        );
        
        ob_start();
        include APP_PATH . '/views/ai/index.php';
        $content = ob_get_clean();
        
        $pageTitle = 'AI Assistant';
        $activePage = 'ai';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function chat() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('ai-assistant'));
        }
        
        $message = post('message');
        
        // Simple AI responses (in real app, integrate with OpenAI/Gemini API)
        $response = $this->generateResponse($message);
        
        // Save conversation
        $this->db->insert('ai_conversations', [
            'user_id' => auth_user()['id'],
            'message' => $message,
            'response' => $response
        ]);
        
        json_response([
            'success' => true,
            'response' => $response
        ]);
    }
    
    public function insights() {
        // Generate business insights
        $insights = $this->generateInsights();
        
        ob_start();
        include APP_PATH . '/views/ai/insights.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Business Insights';
        $activePage = 'ai';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function recommendations() {
        // Generate recommendations
        $recommendations = $this->generateRecommendations();
        
        json_response([
            'success' => true,
            'recommendations' => $recommendations
        ]);
    }
    
    private function generateResponse($message) {
        $message = strtolower($message);
        
        // Simple keyword-based responses
        if (strpos($message, 'penjualan') !== false || strpos($message, 'sales') !== false) {
            return 'Berikut analisis penjualan Anda: Penjualan bulan ini menunjukkan tren positif dengan peningkatan 15% dibanding bulan lalu. Produk terlaris adalah kategori Elektronik. Saya sarankan untuk meningkatkan stok produk populer.';
        }
        
        if (strpos($message, 'stok') !== false || strpos($message, 'inventory') !== false) {
            $lowStock = $this->db->fetchAll(
                "SELECT name, stock FROM products WHERE stock <= min_stock LIMIT 3"
            );
            $response = 'Analisis inventory: Terdapat ' . count($lowStock) . ' produk dengan stok rendah. ';
            foreach ($lowStock as $product) {
                $response .= $product['name'] . ' (sisa ' . $product['stock'] . '), ';
            }
            return rtrim($response, ', ') . '. Segera lakukan restok.';
        }
        
        if (strpos($message, 'karyawan') !== false || strpos($message, 'hr') !== false) {
            $totalEmp = $this->db->fetchOne("SELECT COUNT(*) as total FROM employees WHERE status = 'active'");
            return 'Data HR: Anda memiliki ' . $totalEmp['total'] . ' karyawan aktif. Tingkat kehadiran bulan ini mencapai 95%. Performa tim sangat baik!';
        }
        
        if (strpos($message, 'keuangan') !== false || strpos($message, 'finance') !== false) {
            $finance = $this->db->fetchOne(
                "SELECT 
                    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
                    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
                FROM transactions 
                WHERE MONTH(transaction_date) = MONTH(CURRENT_DATE())"
            );
            $profit = $finance['income'] - $finance['expense'];
            return 'Laporan Keuangan bulan ini: Pemasukan Rp ' . number_format($finance['income'], 0, ',', '.') . 
                   ', Pengeluaran Rp ' . number_format($finance['expense'], 0, ',', '.') . 
                   ', Profit Rp ' . number_format($profit, 0, ',', '.') . '. ' .
                   ($profit > 0 ? 'Bisnis Anda berkembang dengan baik!' : 'Perlu evaluasi pengeluaran.');
        }
        
        // Default response
        return 'Saya adalah AI Assistant Bisnisku. Saya dapat membantu Anda menganalisis penjualan, inventory, keuangan, dan HR. Silakan tanyakan tentang aspek bisnis yang ingin Anda ketahui!';
    }
    
    private function generateInsights() {
        return [
            [
                'title' => 'Penjualan Meningkat',
                'description' => 'Penjualan Anda meningkat 15% bulan ini',
                'type' => 'positive',
                'action' => 'Pertahankan strategi marketing saat ini'
            ],
            [
                'title' => 'Stok Menipis',
                'description' => '5 produk memerlukan restok segera',
                'type' => 'warning',
                'action' => 'Lakukan pemesanan ulang'
            ],
            [
                'title' => 'Profit Margin Baik',
                'description' => 'Profit margin rata-rata 35%',
                'type' => 'positive',
                'action' => 'Ekspansi produk baru'
            ]
        ];
    }
    
    private function generateRecommendations() {
        return [
            'Tingkatkan stok produk elektronik yang sedang trending',
            'Pertimbangkan memberikan diskon untuk produk slow-moving',
            'Rekrut 2 karyawan baru untuk sales department',
            'Optimalkan pengeluaran operasional dengan efisiensi 10%'
        ];
    }
}
