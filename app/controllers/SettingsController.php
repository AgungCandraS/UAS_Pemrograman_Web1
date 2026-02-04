<?php
/**
 * Settings Controller
 */

class SettingsController {
    private $db;
    
    public function __construct() {
        require_auth();
        $this->db = Database::getInstance();
    }
    
    public function index() {
        $settings = $this->db->fetchAll("SELECT * FROM settings");
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting['key_name']] = $setting['value'];
        }
        
        ob_start();
        include APP_PATH . '/views/settings/index.php';
        $content = ob_get_clean();
        
        $pageTitle = 'Pengaturan';
        include APP_PATH . '/views/layouts/app.php';
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('settings'));
        }
        
        try {
            // Get all POST data
            $postData = $_POST;
            
            // Remove submit button if exists
            unset($postData['submit']);
            
            // Update each setting
            foreach ($postData as $key => $value) {
                // Check if setting exists
                $existing = $this->db->fetchOne(
                    "SELECT id FROM settings WHERE key_name = ?",
                    [$key]
                );
                
                if ($existing) {
                    // Update existing setting
                    $this->db->query(
                        "UPDATE settings SET value = ?, updated_at = NOW() WHERE key_name = ?",
                        [$value, $key]
                    );
                } else {
                    // Insert new setting
                    $this->db->query(
                        "INSERT INTO settings (key_name, value, updated_at) VALUES (?, ?, NOW())",
                        [$key, $value]
                    );
                }
            }
            
            $_SESSION['success'] = 'Pengaturan berhasil disimpan';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Gagal menyimpan pengaturan: ' . $e->getMessage();
        }
        
        redirect(base_url('settings'));
    }
    
    // Platform Management
    public function savePlatform() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('settings'));
        }
        
        try {
            $id = $_POST['platform_id'] ?? null;
            $name = trim($_POST['platform_name']);
            $description = trim($_POST['platform_description']);
            $fee_percentage = floatval($_POST['platform_fee']);
            $is_active = isset($_POST['platform_active']) ? 1 : 0;
            
            // Validation
            if (empty($name)) {
                throw new Exception('Nama platform wajib diisi');
            }
            
            if ($fee_percentage < 0 || $fee_percentage > 100) {
                throw new Exception('Persentase potongan harus antara 0-100');
            }
            
            if ($id) {
                // Update existing platform
                $this->db->query(
                    "UPDATE admin_fee_settings SET name = ?, description = ?, fee_percentage = ?, is_active = ?, updated_at = NOW() WHERE id = ?",
                    [$name, $description, $fee_percentage, $is_active, $id]
                );
                $_SESSION['success'] = 'Platform berhasil diperbarui';
            } else {
                // Insert new platform
                $this->db->query(
                    "INSERT INTO admin_fee_settings (name, description, fee_percentage, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())",
                    [$name, $description, $fee_percentage, $is_active]
                );
                $_SESSION['success'] = 'Platform berhasil ditambahkan';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Gagal menyimpan platform: ' . $e->getMessage();
        }
        
        redirect(base_url('settings'));
    }
    
    public function deletePlatform($id) {
        try {
            // Check if platform exists
            $platform = $this->db->fetchOne("SELECT name FROM admin_fee_settings WHERE id = ?", [$id]);
            if (!$platform) {
                throw new Exception('Platform tidak ditemukan');
            }
            
            // Check if platform is used in sales
            $usedCount = $this->db->fetchOne("SELECT COUNT(*) as count FROM sales WHERE platform_id = ?", [$id]);
            if ($usedCount['count'] > 0) {
                throw new Exception('Platform masih digunakan di ' . $usedCount['count'] . ' penjualan. Tidak dapat dihapus.');
            }
            
            // Delete platform
            $this->db->query("DELETE FROM admin_fee_settings WHERE id = ?", [$id]);
            $_SESSION['success'] = 'Platform "' . $platform['name'] . '" berhasil dihapus';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Gagal menghapus platform: ' . $e->getMessage();
        }
        
        redirect(base_url('settings'));
    }
    
    // Product Type Management (dari HR)
    public function saveProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('settings'));
        }
        
        try {
            $id = $_POST['product_id'] ?? null;
            $name = trim($_POST['product_name']);
            $linking_rate = floatval($_POST['product_linking_rate']);
            $rajut_rate = floatval($_POST['product_rajut_rate']);
            
            // Validation
            if (empty($name)) {
                throw new Exception('Nama produk wajib diisi');
            }
            
            if ($linking_rate < 0 || $rajut_rate < 0) {
                throw new Exception('Rate tidak boleh negatif');
            }
            
            // Check duplicate name
            if ($id) {
                $existing = $this->db->fetchOne(
                    "SELECT id FROM product_types WHERE name = ? AND id != ?",
                    [$name, $id]
                );
            } else {
                $existing = $this->db->fetchOne(
                    "SELECT id FROM product_types WHERE name = ?",
                    [$name]
                );
            }
            
            if ($existing) {
                throw new Exception('Produk dengan nama "' . $name . '" sudah ada');
            }
            
            if ($id) {
                // Update existing product
                $this->db->query(
                    "UPDATE product_types SET name = ?, linking_rate = ?, rajut_rate = ? WHERE id = ?",
                    [$name, $linking_rate, $rajut_rate, $id]
                );
                $_SESSION['success'] = 'Master produk berhasil diperbarui';
            } else {
                // Insert new product
                $this->db->query(
                    "INSERT INTO product_types (name, linking_rate, rajut_rate) VALUES (?, ?, ?)",
                    [$name, $linking_rate, $rajut_rate]
                );
                $_SESSION['success'] = 'Master produk berhasil ditambahkan';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Gagal menyimpan master produk: ' . $e->getMessage();
        }
        
        redirect(base_url('settings'));
    }
    
    public function deleteProduct($id) {
        try {
            // Check if product exists
            $product = $this->db->fetchOne("SELECT name FROM product_types WHERE id = ?", [$id]);
            if (!$product) {
                throw new Exception('Produk tidak ditemukan');
            }
            
            // Check if product is used in work_records
            $usedCount = $this->db->fetchOne("SELECT COUNT(*) as count FROM work_records WHERE product_type_id = ?", [$id]);
            if ($usedCount['count'] > 0) {
                throw new Exception('Produk masih digunakan di ' . $usedCount['count'] . ' catatan kerja. Tidak dapat dihapus.');
            }
            
            // Delete product
            $this->db->query("DELETE FROM product_types WHERE id = ?", [$id]);
            $_SESSION['success'] = 'Master produk "' . $product['name'] . '" berhasil dihapus';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Gagal menghapus master produk: ' . $e->getMessage();
        }
        
        redirect(base_url('settings'));
    }
    
    // Category Management (DEPRECATED - kept for compatibility)
    public function saveCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('settings'));
        }
        
        try {
            $id = $_POST['category_id'] ?? null;
            $name = trim($_POST['category_name']);
            $description = trim($_POST['category_description']);
            $icon = $_POST['category_icon'] ?? 'fa-tag';
            
            // Validation
            if (empty($name)) {
                throw new Exception('Nama kategori wajib diisi');
            }
            
            // Check duplicate name
            if ($id) {
                $existing = $this->db->fetchOne(
                    "SELECT id FROM categories WHERE name = ? AND id != ?",
                    [$name, $id]
                );
            } else {
                $existing = $this->db->fetchOne(
                    "SELECT id FROM categories WHERE name = ?",
                    [$name]
                );
            }
            
            if ($existing) {
                throw new Exception('Kategori dengan nama "' . $name . '" sudah ada');
            }
            
            if ($id) {
                // Update existing category
                $this->db->query(
                    "UPDATE categories SET name = ?, description = ?, icon = ?, updated_at = NOW() WHERE id = ?",
                    [$name, $description, $icon, $id]
                );
                $_SESSION['success'] = 'Kategori berhasil diperbarui';
            } else {
                // Insert new category
                $this->db->query(
                    "INSERT INTO categories (name, description, icon, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())",
                    [$name, $description, $icon]
                );
                $_SESSION['success'] = 'Kategori berhasil ditambahkan';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Gagal menyimpan kategori: ' . $e->getMessage();
        }
        
        redirect(base_url('settings'));
    }
    
    public function deleteCategory($id) {
        try {
            // Check if category exists
            $category = $this->db->fetchOne("SELECT name FROM categories WHERE id = ?", [$id]);
            if (!$category) {
                throw new Exception('Kategori tidak ditemukan');
            }
            
            // Set products with this category to NULL (no category)
            $this->db->query("UPDATE products SET category_id = NULL WHERE category_id = ?", [$id]);
            
            // Delete category
            $this->db->query("DELETE FROM categories WHERE id = ?", [$id]);
            $_SESSION['success'] = 'Kategori "' . $category['name'] . '" berhasil dihapus';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Gagal menghapus kategori: ' . $e->getMessage();
        }
        
        redirect(base_url('settings'));
    }
    
    // Employee Management
    public function saveEmployee() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('settings'));
        }
        
        try {
            $id = $_POST['employee_id'] ?? null;
            $full_name = trim($_POST['employee_name']);
            $email = trim($_POST['employee_email']);
            $phone = trim($_POST['employee_phone']);
            $role = $_POST['employee_role'];
            $password = $_POST['employee_password'] ?? '';
            $status = isset($_POST['employee_status']) ? 'active' : 'inactive';
            
            // Validation
            if (empty($full_name)) {
                throw new Exception('Nama lengkap wajib diisi');
            }
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email tidak valid');
            }
            
            // Check duplicate email
            if ($id) {
                $existing = $this->db->fetchOne(
                    "SELECT id FROM users WHERE email = ? AND id != ?",
                    [$email, $id]
                );
            } else {
                $existing = $this->db->fetchOne(
                    "SELECT id FROM users WHERE email = ?",
                    [$email]
                );
            }
            
            if ($existing) {
                throw new Exception('Email "' . $email . '" sudah digunakan');
            }
            
            // Password validation
            if ($id) {
                // Update existing employee
                if (!empty($password)) {
                    if (strlen($password) < 6) {
                        throw new Exception('Password minimal 6 karakter');
                    }
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $this->db->query(
                        "UPDATE users SET full_name = ?, email = ?, phone = ?, role = ?, password = ?, status = ?, updated_at = NOW() WHERE id = ?",
                        [$full_name, $email, $phone, $role, $hashedPassword, $status, $id]
                    );
                } else {
                    $this->db->query(
                        "UPDATE users SET full_name = ?, email = ?, phone = ?, role = ?, status = ?, updated_at = NOW() WHERE id = ?",
                        [$full_name, $email, $phone, $role, $status, $id]
                    );
                }
                $_SESSION['success'] = 'Karyawan berhasil diperbarui';
            } else {
                // Insert new employee
                if (empty($password)) {
                    throw new Exception('Password wajib diisi untuk karyawan baru');
                }
                if (strlen($password) < 6) {
                    throw new Exception('Password minimal 6 karakter');
                }
                
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $this->db->query(
                    "INSERT INTO users (full_name, email, phone, role, password, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())",
                    [$full_name, $email, $phone, $role, $hashedPassword, $status]
                );
                $_SESSION['success'] = 'Karyawan berhasil ditambahkan';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Gagal menyimpan karyawan: ' . $e->getMessage();
        }
        
        redirect(base_url('settings'));
    }
    
    public function deleteEmployee($id) {
        try {
            // Check if employee exists
            $employee = $this->db->fetchOne("SELECT full_name, email FROM users WHERE id = ?", [$id]);
            if (!$employee) {
                throw new Exception('Karyawan tidak ditemukan');
            }
            
            // Prevent deleting current user
            if ($id == $_SESSION['user_id']) {
                throw new Exception('Anda tidak dapat menghapus akun Anda sendiri');
            }
            
            // Check if employee has created sales
            $salesCount = $this->db->fetchOne("SELECT COUNT(*) as count FROM sales WHERE user_id = ?", [$id]);
            if ($salesCount['count'] > 0) {
                throw new Exception('Karyawan ini memiliki ' . $salesCount['count'] . ' transaksi penjualan. Nonaktifkan akun alih-alih menghapus.');
            }
            
            // Delete employee
            $this->db->query("DELETE FROM users WHERE id = ?", [$id]);
            $_SESSION['success'] = 'Karyawan "' . $employee['full_name'] . '" berhasil dihapus';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Gagal menghapus karyawan: ' . $e->getMessage();
        }
        
        redirect(base_url('settings'));
    }
}
