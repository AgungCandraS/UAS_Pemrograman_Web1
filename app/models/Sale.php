<?php
/**
 * Sales Model
 * Menggantikan Order Model dengan fitur offline/online dan admin fee
 */

class Sale {
    private $db;
    private $dbInstance;
    private $table = 'sales';
    
    public function __construct() {
        $this->dbInstance = Database::getInstance();
        $this->db = $this->dbInstance->getConnection();
    }
    
    /**
     * Get all sales with filters
     */
    public function getAll($status = null, $limit = 20, $offset = 0, $search = '', $paymentStatus = null, $saleType = null) {
        $query = "SELECT s.*, 
                  afs.name as platform_name,
                  u.full_name as created_by_name,
                  (s.final_total - COALESCE((SELECT SUM(si.cost_price * si.quantity) 
                                              FROM sale_items si 
                                              WHERE si.sale_id = s.id), 0)) as profit
                  FROM {$this->table} s
                  LEFT JOIN admin_fee_settings afs ON s.admin_fee_setting_id = afs.id
                  LEFT JOIN users u ON s.created_by = u.id
                  WHERE 1=1";
        
        $params = [];
        
        if ($saleType) {
            $query .= " AND s.sale_type = ?";
            $params[] = $saleType;
        }
        
        if ($search) {
            $query .= " AND (s.sale_number LIKE ? OR s.customer_name LIKE ? OR s.customer_phone LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $query .= " ORDER BY s.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->dbInstance->fetchAll($query, $params);
    }
    
    /**
     * Count sales with filters
     */
    public function count($status = null, $search = '', $paymentStatus = null, $saleType = null) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        $params = [];
        
        if ($saleType) {
            $query .= " AND sale_type = ?";
            $params[] = $saleType;
        }
        
        if ($search) {
            $query .= " AND (sale_number LIKE ? OR customer_name LIKE ? OR customer_phone LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $result = $this->dbInstance->fetchOne($query, $params);
        return $result['total'] ?? 0;
    }
    
    /**
     * Get sale by ID
     */
    public function getById($id) {
        $query = "SELECT s.*, 
                  afs.name as platform_name,
                  afs.fee_percentage as platform_fee_percentage,
                  u.full_name as created_by_name
                  FROM {$this->table} s
                  LEFT JOIN admin_fee_settings afs ON s.admin_fee_setting_id = afs.id
                  LEFT JOIN users u ON s.created_by = u.id
                  WHERE s.id = ?";
        
        return $this->dbInstance->fetchOne($query, [$id]);
    }
    
    /**
     * Get sale items
     */
    public function getItems($saleId) {
        $query = "SELECT si.*, p.stock 
                  FROM sale_items si
                  LEFT JOIN products p ON si.product_id = p.id
                  WHERE si.sale_id = ?
                  ORDER BY si.id";
        
        return $this->dbInstance->fetchAll($query, [$saleId]);
    }
    
    /**
     * Generate unique sale number
     */
    public function generateSaleNumber($saleType = 'offline') {
        $prefix = $saleType === 'online' ? 'ONLINE' : 'OFFLINE';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
        
        return "{$prefix}-{$date}-{$random}";
    }
    
    /**
     * Create new sale
     */
    public function create($data) {
        try {
            $this->dbInstance->beginTransaction();
            
            // Generate sale number if not provided
            if (empty($data['sale_number'])) {
                $data['sale_number'] = $this->generateSaleNumber($data['sale_type'] ?? 'offline');
            }
            
            // Insert sale
            $query = "INSERT INTO {$this->table} (
                        sale_number, sale_type, admin_fee_setting_id, customer_id, 
                        customer_name, customer_phone, customer_address,
                        subtotal, tax, discount, notes, created_by
                      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $data['sale_number'],
                $data['sale_type'] ?? 'offline',
                $data['admin_fee_setting_id'] ?? null,
                $data['customer_id'] ?? null,
                $data['customer_name'],
                $data['customer_phone'] ?? '',
                $data['customer_address'] ?? '',
                $data['subtotal'],
                $data['tax'] ?? 0,
                $data['discount'] ?? 0,
                $data['notes'] ?? '',
                $_SESSION['user_id']
            ];
            
            $result = $this->dbInstance->execute($query, $params);
            
            if ($result === false) {
                error_log('Sale CRITICAL: Execute failed');
                $this->dbInstance->rollback();
                return false;
            }
            
            $saleId = $this->db->insert_id;
            error_log("Sale insert - insert_id: " . $saleId);
            
            // Jika insert_id = 0, coba ambil dengan LAST_INSERT_ID()
            if ($saleId === 0 || !$saleId) {
                $lastInsertResult = $this->db->query("SELECT LAST_INSERT_ID() as last_id");
                if ($lastInsertResult) {
                    $row = $lastInsertResult->fetch_assoc();
                    $saleId = $row['last_id'] ?? 0;
                    error_log("Sale - LAST_INSERT_ID(): " . $saleId);
                }
            }
            
            // Jika masih 0, cari berdasarkan sale_number
            if ($saleId === 0 || !$saleId) {
                $checkSale = $this->dbInstance->fetchOne(
                    "SELECT id FROM {$this->table} WHERE sale_number = ? ORDER BY created_at DESC LIMIT 1",
                    [$data['sale_number']]
                );
                if ($checkSale && isset($checkSale['id'])) {
                    $saleId = $checkSale['id'];
                    error_log("Sale - found via sale_number: " . $saleId);
                } else {
                    error_log('Sale CRITICAL: Cannot find inserted sale!');
                    $this->dbInstance->rollback();
                    return false;
                }
            }
            
            // Insert sale items
            $totalCost = 0;
            if (!empty($data['items'])) {
                $itemQuery = "INSERT INTO sale_items (
                                sale_id, product_id, product_name, product_sku,
                                quantity, price, cost_price, subtotal
                              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                
                $itemCount = 0;
                foreach ($data['items'] as $item) {
                    // Skip if product_id is empty
                    if (empty($item['product_id'])) {
                        error_log('Sale item skipped - empty product_id: ' . json_encode($item));
                        continue;
                    }
                    
                    // Get cost_price from product
                    $product = $this->dbInstance->fetchOne(
                        "SELECT cost_price FROM products WHERE id = ?",
                        [$item['product_id']]
                    );
                    $costPrice = $product['cost_price'] ?? 0;
                    $quantity = $item['quantity'] ?? 1;
                    $totalCost += ($costPrice * $quantity);
                    
                    $itemParams = [
                        $saleId,
                        $item['product_id'] ?? null,
                        $item['product_name'] ?? '',
                        $item['product_sku'] ?? '',
                        $quantity,
                        $item['price'] ?? 0,
                        $costPrice,
                        $item['subtotal'] ?? 0
                    ];
                    
                    error_log('Inserting sale item: ' . json_encode($itemParams));
                    
                    $itemResult = $this->dbInstance->execute($itemQuery, $itemParams);
                    if ($itemResult === false) {
                        error_log('Sale Error: Failed to insert item - ' . json_encode($itemParams));
                        $this->dbInstance->rollback();
                        return false;
                    }
                    
                    $itemCount++;
                    
                    // Update stock
                    if (!empty($item['product_id']) && !empty($quantity)) {
                        $updateStock = "UPDATE products SET stock = stock - ? WHERE id = ?";
                        $this->dbInstance->execute($updateStock, [$quantity, $item['product_id']]);
                    }
                }
                
                error_log("Sale created successfully - ID: {$saleId}, Items: {$itemCount}");
            }
            
            // Calculate and update profit: final_total - total_cost
            // Note: admin_fee is already deducted from final_total
            $profit = ($data['subtotal'] - $data['tax'] + $data['discount']) - $totalCost;
            if ($data['sale_type'] === 'online' && !empty($data['admin_fee_setting_id'])) {
                // For online sales, need to get admin fee and subtract from profit
                $saleData = $this->getById($saleId);
                $profit = $saleData['final_total'] - $totalCost;
            }
            
            // Update profit in sales table
            $updateProfit = "UPDATE {$this->table} SET profit = ? WHERE id = ?";
            $this->dbInstance->execute($updateProfit, [$profit, $saleId]);
            error_log("Profit calculated: {$profit} (final_total - total_cost: {$totalCost})");
            
            $this->dbInstance->commit();
            return $saleId;
            
        } catch (Exception $e) {
            $this->dbInstance->rollback();
            error_log("Error creating sale: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update sale
     */
    public function update($id, $data) {
        try {
            $this->dbInstance->beginTransaction();
            
            // Build update query dynamically
            $fields = [];
            $params = [];
            
            $allowedFields = [
                'sale_type', 'admin_fee_setting_id', 'customer_id', 'customer_name',
                'customer_phone', 'customer_address', 'subtotal', 'tax', 'discount', 'notes'
            ];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "{$field} = ?";
                    $params[] = $data[$field];
                }
            }
            
            if (empty($fields)) {
                return true;
            }
            
            $params[] = $id;
            $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
            
            $this->dbInstance->execute($query, $params);
            
            // Update items if provided
            if (isset($data['items'])) {
                // Delete old items and restore stock
                $oldItems = $this->getItems($id);
                foreach ($oldItems as $oldItem) {
                    if ($oldItem['product_id']) {
                        $restoreStock = "UPDATE products SET stock = stock + ? WHERE id = ?";
                        $this->dbInstance->execute($restoreStock, [$oldItem['quantity'], $oldItem['product_id']]);
                    }
                }
                
                // Delete old items
                $deleteItems = "DELETE FROM sale_items WHERE sale_id = ?";
                $this->dbInstance->execute($deleteItems, [$id]);
                
                // Insert new items
                $itemQuery = "INSERT INTO sale_items (
                                sale_id, product_id, product_name, product_sku,
                                quantity, price, cost_price, subtotal
                              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                
                $totalCost = 0;
                foreach ($data['items'] as $item) {
                    // Get cost_price from product
                    $product = $this->dbInstance->fetchOne(
                        "SELECT cost_price FROM products WHERE id = ?",
                        [$item['product_id']]
                    );
                    $costPrice = $product['cost_price'] ?? 0;
                    $quantity = $item['quantity'] ?? 1;
                    $totalCost += ($costPrice * $quantity);
                    
                    $itemParams = [
                        $id,
                        $item['product_id'],
                        $item['product_name'],
                        $item['product_sku'] ?? '',
                        $quantity,
                        $item['price'],
                        $costPrice,
                        $item['subtotal']
                    ];
                    
                    $this->dbInstance->execute($itemQuery, $itemParams);
                    
                    // Update stock
                    if ($item['product_id']) {
                        $updateStock = "UPDATE products SET stock = stock - ? WHERE id = ?";
                        $this->dbInstance->execute($updateStock, [$quantity, $item['product_id']]);
                    }
                }
                
                // Calculate and update profit
                $saleData = $this->getById($id);
                $profit = $saleData['final_total'] - $totalCost;
                $updateProfit = "UPDATE {$this->table} SET profit = ? WHERE id = ?";
                $this->dbInstance->execute($updateProfit, [$profit, $id]);
            }
            
            $this->dbInstance->commit();
            return true;
            
        } catch (Exception $e) {
            $this->dbInstance->rollback();
            error_log("Error updating sale: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete sale
     */
    public function delete($id) {
        try {
            $this->dbInstance->beginTransaction();
            
            // Restore stock first
            $items = $this->getItems($id);
            foreach ($items as $item) {
                if ($item['product_id']) {
                    $restoreStock = "UPDATE products SET stock = stock + ? WHERE id = ?";
                    $this->dbInstance->execute($restoreStock, [$item['quantity'], $item['product_id']]);
                }
            }
            
            // Delete sale (items will be deleted by CASCADE)
            $query = "DELETE FROM {$this->table} WHERE id = ?";
            $this->dbInstance->execute($query, [$id]);
            
            $this->dbInstance->commit();
            return true;
            
        } catch (Exception $e) {
            $this->dbInstance->rollback();
            error_log("Error deleting sale: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get sales statistics
     */
    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_sales,
                    COUNT(CASE WHEN sale_type = 'offline' THEN 1 END) as offline_sales,
                    COUNT(CASE WHEN sale_type = 'online' THEN 1 END) as online_sales,
                    SUM(CASE WHEN sale_type = 'offline' THEN final_total ELSE 0 END) as offline_revenue,
                    SUM(CASE WHEN sale_type = 'online' THEN final_total ELSE 0 END) as online_revenue,
                    SUM(final_total) as total_revenue,
                    SUM(admin_fee_amount) as total_admin_fees,
                    SUM(profit) as total_profit
                  FROM {$this->table}";
        
        return $this->dbInstance->fetchOne($query);
    }
    
    /**
     * Get monthly sales data
     */
    public function getMonthlySales($months = 6) {
        $query = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as count,
                    SUM(final_total) as total,
                    SUM(CASE WHEN sale_type = 'offline' THEN final_total ELSE 0 END) as offline_total,
                    SUM(CASE WHEN sale_type = 'online' THEN final_total ELSE 0 END) as online_total
                  FROM {$this->table}
                  WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? MONTH)
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                  ORDER BY month DESC";
        
        return $this->dbInstance->fetchAll($query, [$months]);
    }
}
