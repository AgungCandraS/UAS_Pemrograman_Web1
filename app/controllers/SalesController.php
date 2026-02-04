<?php
/**
 * Sales Controller
 * Menggantikan OrderController dengan fitur offline/online dan admin fee
 */

class SalesController {
    private $saleModel;
    private $productModel;
    private $adminFeeModel;
    
    public function __construct() {
        require_auth();
        $this->saleModel = new Sale();
        $this->productModel = new Product();
        $this->adminFeeModel = new AdminFeeSetting();
    }
    
    public function index() {
        $saleType = get('sale_type');
        $search = get('search', '');
        $page = get('page', 1);
        $perPage = 20;
        
        $sales = $this->saleModel->getAll(null, $perPage, ($page - 1) * $perPage, $search, null, $saleType);
        $totalSales = $this->saleModel->count(null, $search, null, $saleType);
        $totalPages = ceil($totalSales / $perPage);
        
        $stats = $this->saleModel->getStats();
        
        ob_start();
        include APP_PATH . '/views/sales/index.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Manajemen Penjualan';
        $activePage = 'sales';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function create() {
        $products = $this->productModel->getAll();
        $adminFeeSettings = $this->adminFeeModel->getAll(true);
        
        ob_start();
        include APP_PATH . '/views/sales/create.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Buat Penjualan Baru';
        $activePage = 'sales';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function store() {
        // Debug logging
        if (APP_DEBUG) {
            error_log("SalesController::store() called - Method: " . $_SERVER['REQUEST_METHOD']);
            error_log("POST data: " . print_r($_POST, true));
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('sales');
            return;
        }
        
        try {
            $saleType = post('sale_type', 'offline');
            $items = post('items', []);
            
            error_log("SalesController::store - sale_type: {$saleType}, items count: " . count($items));
            
            if (empty($items)) {
                flash('error', 'Tambahkan minimal 1 produk');
                redirect('sales/create');
                return;
            }
            
            // Calculate subtotal and validate items
            $subtotal = 0;
            $validItems = [];
            
            foreach ($items as $index => $item) {
                error_log("Processing item #{$index}: " . json_encode($item));
                
                if (isset($item['product_id']) && !empty($item['product_id'])) {
                    // Calculate subtotal from price * quantity
                    $price = floatval($item['price'] ?? 0);
                    $quantity = floatval($item['quantity'] ?? 0);
                    
                    // Validasi quantity harus > 0
                    if ($quantity <= 0) {
                        error_log("Item #{$index} skipped - quantity = 0");
                        continue;
                    }
                    
                    $itemSubtotal = $price * $quantity;
                    
                    // Store calculated subtotal in item
                    $item['subtotal'] = $itemSubtotal;
                    $validItems[] = $item;
                    $subtotal += $itemSubtotal;
                    
                    error_log("Item #{$index} valid - qty: {$quantity}, price: {$price}, subtotal: {$itemSubtotal}");
                } else {
                    error_log("Item #{$index} skipped - no product_id");
                }
            }
            
            error_log("Valid items count: " . count($validItems) . ", total subtotal: {$subtotal}");
            
            if (empty($validItems)) {
                flash('error', 'Tambahkan minimal 1 produk yang valid');
                redirect('sales/create');
                return;
            }
            
            // Validate admin fee setting for online sales
            $adminFeeSettingId = null;
            if ($saleType === 'online') {
                $adminFeeSettingId = post('admin_fee_setting_id');
                if (empty($adminFeeSettingId)) {
                    flash('error', 'Pilih platform untuk penjualan online');
                    redirect('sales/create');
                    return;
                }
            }
            
            $data = [
                'sale_type' => $saleType,
                'admin_fee_setting_id' => $adminFeeSettingId,
                'customer_name' => post('customer_name'),
                'customer_phone' => post('customer_phone'),
                'customer_address' => post('customer_address'),
                'subtotal' => $subtotal,
                'tax' => floatval(post('tax', 0)),
                'discount' => floatval(post('discount', 0)),
                'notes' => post('notes'),
                'items' => $validItems
            ];
            
            $saleId = $this->saleModel->create($data);
            
            if ($saleId !== false && $saleId > 0) {
                flash('success', 'Penjualan berhasil ditambahkan');
                redirect('sales');
            } else {
                flash('error', 'Gagal menambahkan penjualan');
                redirect('sales/create');
            }
            
        } catch (Exception $e) {
            error_log("Error storing sale: " . $e->getMessage());
            flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            redirect('sales/create');
        }
    }
    
    public function view($id) {
        $sale = $this->saleModel->getById($id);
        
        if (!$sale) {
            flash('error', 'Penjualan tidak ditemukan');
            redirect('sales');
            return;
        }
        
        $items = $this->saleModel->getItems($id);
        
        ob_start();
        include APP_PATH . '/views/sales/view.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Detail Penjualan #' . $sale['sale_number'];
        $activePage = 'sales';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function edit($id) {
        $sale = $this->saleModel->getById($id);
        
        if (!$sale) {
            flash('error', 'Penjualan tidak ditemukan');
            redirect('sales');
            return;
        }
        
        $items = $this->saleModel->getItems($id);
        $products = $this->productModel->getAll();
        $adminFeeSettings = $this->adminFeeModel->getAll(true);
        
        ob_start();
        include APP_PATH . '/views/sales/edit.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Edit Penjualan #' . $sale['sale_number'];
        $activePage = 'sales';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('sales');
            return;
        }
        
        try {
            $saleType = post('sale_type', 'offline');
            $items = json_decode(post('items', '[]'), true);
            
            if (empty($items)) {
                flash('error', 'Tambahkan minimal 1 produk');
                redirect('sales/edit/' . $id);
                return;
            }
            
            // Calculate subtotal
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['subtotal'];
            }
            
            $data = [
                'sale_type' => $saleType,
                'admin_fee_setting_id' => $saleType === 'online' ? post('admin_fee_setting_id') : null,
                'customer_name' => post('customer_name'),
                'customer_phone' => post('customer_phone'),
                'customer_address' => post('customer_address'),
                'subtotal' => $subtotal,
                'tax' => post('tax', 0),
                'discount' => post('discount', 0),
                'payment_method' => post('payment_method', 'cash'),
                'payment_status' => post('payment_status', 'pending'),
                'sale_status' => post('sale_status', 'pending'),
                'notes' => post('notes'),
                'items' => $items
            ];
            
            if ($this->saleModel->update($id, $data)) {
                flash('success', 'Penjualan berhasil diupdate');
                redirect('sales');
            } else {
                flash('error', 'Gagal mengupdate penjualan');
                redirect('sales/edit/' . $id);
            }
            
        } catch (Exception $e) {
            error_log("Error updating sale: " . $e->getMessage());
            flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            redirect('sales/edit/' . $id);
        }
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('sales');
            return;
        }
        
        if ($this->saleModel->delete($id)) {
            flash('success', 'Penjualan berhasil dihapus');
        } else {
            flash('error', 'Gagal menghapus penjualan');
        }
        
        redirect('sales');
    }
    
    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('sales');
            return;
        }
        
        $status = post('sale_status');
        $paymentStatus = post('payment_status');
        
        $data = [];
        if ($status) {
            $data['sale_status'] = $status;
        }
        if ($paymentStatus) {
            $data['payment_status'] = $paymentStatus;
        }
        
        if ($this->saleModel->update($id, $data)) {
            flash('success', 'Status berhasil diupdate');
        } else {
            flash('error', 'Gagal mengupdate status');
        }
        
        redirect('sales/view/' . $id);
    }
    
    /**
     * Admin Fee Settings Management
     */
    public function adminFeeSettings() {
        $settings = $this->adminFeeModel->getAll();
        
        ob_start();
        include APP_PATH . '/views/sales/admin_fee_settings.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Pengaturan Potongan Admin';
        $activePage = 'sales';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function storeAdminFeeSetting() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('sales/admin-fee-settings');
            return;
        }
        
        $data = [
            'name' => post('name'),
            'description' => post('description'),
            'fee_percentage' => floatval(post('fee_percentage', 0)),
            'fee_fixed' => floatval(post('fee_fixed', 0)),
            'is_active' => post('is_active', 1)
        ];
        
        if ($this->adminFeeModel->create($data)) {
            flash('success', 'Pengaturan admin fee berhasil ditambahkan');
        } else {
            flash('error', 'Gagal menambahkan pengaturan admin fee');
        }
        
        redirect('sales/admin-fee-settings');
    }
    
    public function updateAdminFeeSetting($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('sales/admin-fee-settings');
            return;
        }
        
        $data = [
            'name' => post('name'),
            'description' => post('description'),
            'fee_percentage' => floatval(post('fee_percentage', 0)),
            'fee_fixed' => floatval(post('fee_fixed', 0)),
            'is_active' => post('is_active', 1)
        ];
        
        if ($this->adminFeeModel->update($id, $data)) {
            flash('success', 'Pengaturan admin fee berhasil diupdate');
        } else {
            flash('error', 'Gagal mengupdate pengaturan admin fee');
        }
        
        redirect('sales/admin-fee-settings');
    }
    
    public function deleteAdminFeeSetting($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('sales/admin-fee-settings');
            return;
        }
        
        if ($this->adminFeeModel->delete($id)) {
            flash('success', 'Pengaturan admin fee berhasil dihapus');
        } else {
            flash('error', 'Gagal menghapus pengaturan admin fee');
        }
        
        redirect('sales/admin-fee-settings');
    }
    
    public function toggleAdminFeeSetting($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('sales/admin-fee-settings');
            return;
        }
        
        if ($this->adminFeeModel->toggleActive($id)) {
            flash('success', 'Status berhasil diubah');
        } else {
            flash('error', 'Gagal mengubah status');
        }
        
        redirect('sales/admin-fee-settings');
    }
    
    /**
     * Print sale invoice
     */
    public function print($id) {
        $sale = $this->saleModel->getById($id);
        
        if (!$sale) {
            flash('error', 'Penjualan tidak ditemukan');
            redirect('sales');
            return;
        }
        
        $items = $this->saleModel->getItems($id);
        
        include APP_PATH . '/views/sales/print.php';
    }
}
