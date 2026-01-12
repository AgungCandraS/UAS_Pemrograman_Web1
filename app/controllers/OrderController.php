<?php
/**
 * Order Controller
 */

class OrderController {
    private $orderModel;
    private $productModel;
    
    public function __construct() {
        require_auth();
        $this->orderModel = new Order();
        $this->productModel = new Product();
    }
    
    public function index() {
        $status = get('status');
        $paymentStatus = get('payment_status');
        $search = get('search', '');
        $page = get('page', 1);
        $perPage = 20;
        
        $orders = $this->orderModel->getAll($status, $perPage, ($page - 1) * $perPage, $search, $paymentStatus);
        $totalOrders = $this->orderModel->count($status, $search, $paymentStatus);
        $totalPages = ceil($totalOrders / $perPage);
        
        $stats = [
            'total_orders' => $this->orderModel->count(),
            'pending' => $this->orderModel->count('pending'),
            'processing' => $this->orderModel->count('processing'),
            'delivered' => $this->orderModel->count('delivered')
        ];
        
        ob_start();
        include APP_PATH . '/views/orders/index.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Manajemen Pesanan';
        $activePage = 'orders';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function create() {
        $products = $this->productModel->getAll();
        
        ob_start();
        include APP_PATH . '/views/orders/create.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Buat Pesanan Baru';
        $activePage = 'orders';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('orders'));
        }
        
        $orderData = [
            'customer_name' => post('customer_name'),
            'customer_phone' => post('customer_phone'),
            'customer_address' => post('customer_address'),
            'subtotal' => post('subtotal'),
            'tax' => post('tax', 0),
            'discount' => post('discount', 0),
            'total' => post('total'),
            'payment_method' => post('payment_method'),
            'payment_status' => post('payment_status', 'pending'),
            'order_status' => 'pending',
            'notes' => post('notes'),
            'created_by' => auth_user()['id']
        ];
        
        $items = json_decode(post('items'), true);
        
        if ($this->orderModel->create($orderData, $items)) {
            flash('success', 'Pesanan berhasil dibuat');
        } else {
            flash('error', 'Gagal membuat pesanan');
        }
        
        redirect(base_url('orders'));
    }
    
    public function detail($id) {
        $order = $this->orderModel->findById($id);
        if (!$order) {
            flash('error', 'Pesanan tidak ditemukan');
            redirect(base_url('orders'));
        }
        
        $items = $this->orderModel->getOrderItems($id);
        
        ob_start();
        include APP_PATH . '/views/orders/detail.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Detail Pesanan';
        $activePage = 'orders';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('orders'));
        }
        
        $data = [
            'order_status' => post('order_status'),
            'payment_status' => post('payment_status')
        ];
        
        if (post('notes')) {
            $data['notes'] = post('notes');
        }
        
        if ($this->orderModel->update($id, $data)) {
            flash('success', 'Status pesanan berhasil diupdate');
        } else {
            flash('error', 'Gagal mengupdate status pesanan');
        }
        
        redirect(base_url('orders'));
    }
    
    public function delete($id) {
        if ($this->orderModel->delete($id)) {
            flash('success', 'Pesanan berhasil dihapus');
        } else {
            flash('error', 'Gagal menghapus pesanan');
        }
        
        redirect(base_url('orders'));
    }
    
    public function export() {
        $format = strtolower(get('format', 'excel'));
        $status = get('status', '');
        $paymentStatus = get('payment_status', '');
        $search = get('search', '');
        $orders = $this->orderModel->getAll($status, null, 0, $search, $paymentStatus);
        $escape = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

        require_once ROOT_PATH . '/vendor/autoload.php';

        try {
            if ($format === 'pdf') {
                $pdf = new \TCPDF();
                $pdf->SetCreator(APP_NAME);
                $pdf->SetAuthor(APP_NAME);
                $pdf->SetTitle('Orders Report');
                $pdf->AddPage();

                $html = '<h2>Laporan Pesanan</h2><p>Tanggal: ' . date('d/m/Y H:i') . '</p>';
                $html .= '<table border="1" cellpadding="5"><thead><tr>';
                $html .= '<th>No Pesanan</th><th>Pelanggan</th><th>Total</th><th>Status Bayar</th><th>Status Pesanan</th>';
                $html .= '</tr></thead><tbody>';
                
                foreach ($orders as $order) {
                    $html .= '<tr>';
                    $html .= '<td>' . $escape($order['order_number']) . '</td>';
                    $html .= '<td>' . $escape($order['customer_name']) . '</td>';
                    $html .= '<td>' . $escape(format_currency($order['total'])) . '</td>';
                    $html .= '<td>' . $escape(ucfirst($order['payment_status'])) . '</td>';
                    $html .= '<td>' . $escape(ucfirst($order['order_status'])) . '</td>';
                    $html .= '</tr>';
                }
                
                $html .= '</tbody></table>';
                $pdf->writeHTML($html);
                $pdf->Output('orders_' . date('Ymd_His') . '.pdf', 'D');
                exit();
            }

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Orders');
            $sheet->fromArray(['No Pesanan', 'Pelanggan', 'Total', 'Status Bayar', 'Status Pesanan'], null, 'A1');

            $row = 2;
            foreach ($orders as $order) {
                $sheet->setCellValue("A{$row}", $order['order_number']);
                $sheet->setCellValue("B{$row}", $order['customer_name']);
                $sheet->setCellValue("C{$row}", $order['total']);
                $sheet->setCellValue("D{$row}", $order['payment_status']);
                $sheet->setCellValue("E{$row}", $order['order_status']);
                $row++;
            }

            foreach (range('A', 'E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'orders_' . date('Ymd_His') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit();
        } catch (Exception $e) {
            flash('error', 'Gagal mengekspor data: ' . $e->getMessage());
            redirect(base_url('orders'));
        }
    }
}
