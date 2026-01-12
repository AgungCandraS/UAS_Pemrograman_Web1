<?php
/**
 * Inventory Controller
 */

class InventoryController {
    private $productModel;
    
    public function __construct() {
        require_auth();
        $this->productModel = new Product();
    }
    
    public function index() {
        $page = get('page', 1);
        $search = get('search', '');
        $perPage = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $perPage;
        
        $products = $this->productModel->getAll($perPage, $offset, $search);
        $total = $this->productModel->count($search);
        $totalPages = ceil($total / $perPage);
        
        ob_start();
        include APP_PATH . '/views/inventory/index.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Inventory Management';
        $activePage = 'inventory';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function create() {
        $categories = $this->productModel->getCategories();
        
        ob_start();
        include APP_PATH . '/views/inventory/create.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Tambah Produk';
        $activePage = 'inventory';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('inventory'));
        }
        
        $data = [
            'category_id' => post('category_id'),
            'sku' => post('sku'),
            'name' => post('name'),
            'description' => post('description'),
            'price' => post('price'),
            'cost_price' => post('cost_price'),
            'stock' => post('stock'),
            'min_stock' => post('min_stock'),
            'unit' => post('unit'),
            'status' => post('status', 'active')
        ];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $data['image'] = upload_file($_FILES['image']);
        }
        
        if ($this->productModel->create($data)) {
            flash('success', 'Produk berhasil ditambahkan');
        } else {
            flash('error', 'Gagal menambahkan produk');
        }
        
        redirect(base_url('inventory'));
    }
    
    public function edit($id) {
        $product = $this->productModel->findById($id);
        if (!$product) {
            flash('error', 'Produk tidak ditemukan');
            redirect(base_url('inventory'));
        }
        
        $categories = $this->productModel->getCategories();
        
        ob_start();
        include APP_PATH . '/views/inventory/edit.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Edit Produk';
        $activePage = 'inventory';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('inventory'));
        }
        
        $data = [
            'category_id' => post('category_id'),
            'name' => post('name'),
            'description' => post('description'),
            'price' => post('price'),
            'cost_price' => post('cost_price'),
            'stock' => post('stock'),
            'min_stock' => post('min_stock'),
            'unit' => post('unit'),
            'status' => post('status')
        ];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $data['image'] = upload_file($_FILES['image']);
        }
        
        if ($this->productModel->update($id, $data)) {
            flash('success', 'Produk berhasil diupdate');
        } else {
            flash('error', 'Gagal mengupdate produk');
        }
        
        redirect(base_url('inventory'));
    }
    
    public function delete($id) {
        if ($this->productModel->delete($id)) {
            flash('success', 'Produk berhasil dihapus');
        } else {
            flash('error', 'Gagal menghapus produk');
        }
        
        redirect(base_url('inventory'));
    }
    
    public function lowStock() {
        $products = $this->productModel->getLowStock();
        
        ob_start();
        include APP_PATH . '/views/inventory/low_stock.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Stok Rendah';
        $activePage = 'inventory';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function export() {
        $format = strtolower(get('format', 'excel'));
        $search = get('search', '');
        $products = $this->productModel->getAll(null, 0, $search);
        $escape = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

        require_once ROOT_PATH . '/vendor/autoload.php';

        try {
            if ($format === 'pdf') {
                $pdf = new \TCPDF();
                $pdf->SetCreator(APP_NAME);
                $pdf->SetAuthor(APP_NAME);
                $pdf->SetTitle('Inventory Report');
                $pdf->AddPage();

                $html = '<h2>Laporan Inventory</h2><p>Tanggal: ' . date('d/m/Y H:i') . '</p>';
                $html .= '<table border="1" cellpadding="5"><thead><tr>';
                $html .= '<th>SKU</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th>';
                $html .= '</tr></thead><tbody>';
                
                foreach ($products as $product) {
                    $html .= '<tr>';
                    $html .= '<td>' . $escape($product['sku']) . '</td>';
                    $html .= '<td>' . $escape($product['name']) . '</td>';
                    $html .= '<td>' . $escape($product['category_name'] ?? '-') . '</td>';
                    $html .= '<td>' . $escape(format_currency($product['price'])) . '</td>';
                    $html .= '<td>' . $escape($product['stock'] . ' ' . $product['unit']) . '</td>';
                    $html .= '<td>' . $escape(ucfirst($product['status'])) . '</td>';
                    $html .= '</tr>';
                }
                
                $html .= '</tbody></table>';
                $pdf->writeHTML($html);
                $pdf->Output('inventory_' . date('Ymd_His') . '.pdf', 'D');
                exit();
            }

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Inventory');
            $sheet->fromArray(['SKU', 'Nama', 'Kategori', 'Harga', 'Stok', 'Status'], null, 'A1');

            $row = 2;
            foreach ($products as $product) {
                $sheet->setCellValue("A{$row}", $product['sku']);
                $sheet->setCellValue("B{$row}", $product['name']);
                $sheet->setCellValue("C{$row}", $product['category_name'] ?? '-');
                $sheet->setCellValue("D{$row}", $product['price']);
                $sheet->setCellValue("E{$row}", $product['stock'] . ' ' . $product['unit']);
                $sheet->setCellValue("F{$row}", ucfirst($product['status']));
                $row++;
            }

            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'inventory_' . date('Ymd_His') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit();
        } catch (Exception $e) {
            flash('error', 'Gagal mengekspor data: ' . $e->getMessage());
            redirect(base_url('inventory'));
        }
    }
}
