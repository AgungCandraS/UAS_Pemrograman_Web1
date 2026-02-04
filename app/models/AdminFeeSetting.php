<?php
/**
 * Admin Fee Setting Model
 * Model untuk mengatur potongan admin fee platform online
 */

class AdminFeeSetting {
    private $db;
    private $dbInstance;
    private $table = 'admin_fee_settings';
    
    public function __construct() {
        $this->dbInstance = Database::getInstance();
        $this->db = $this->dbInstance->getConnection();
    }
    
    /**
     * Get all admin fee settings
     */
    public function getAll($activeOnly = false) {
        $query = "SELECT * FROM {$this->table}";
        $params = [];
        
        if ($activeOnly) {
            $query .= " WHERE is_active = 1";
        }
        
        $query .= " ORDER BY name ASC";
        
        return $this->dbInstance->fetchAll($query, $params);
    }
    
    /**
     * Get admin fee setting by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->dbInstance->fetchOne($query, [$id]);
    }
    
    /**
     * Create new admin fee setting
     */
    public function create($data) {
        try {
            $query = "INSERT INTO {$this->table} (name, description, fee_percentage, fee_fixed, is_active) 
                      VALUES (?, ?, ?, ?, ?)";
            
            $params = [
                $data['name'],
                $data['description'] ?? '',
                $data['fee_percentage'] ?? 0,
                $data['fee_fixed'] ?? 0,
                $data['is_active'] ?? 1
            ];
            
            $this->dbInstance->execute($query, $params);
            return $this->db->insert_id;
            
        } catch (Exception $e) {
            error_log("Error creating admin fee setting: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update admin fee setting
     */
    public function update($id, $data) {
        try {
            $fields = [];
            $params = [];
            
            $allowedFields = ['name', 'description', 'fee_percentage', 'fee_fixed', 'is_active'];
            
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
            
            return $this->dbInstance->execute($query, $params);
            
        } catch (Exception $e) {
            error_log("Error updating admin fee setting: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete admin fee setting
     */
    public function delete($id) {
        try {
            // Check if being used by any sales
            $checkQuery = "SELECT COUNT(*) as count FROM sales WHERE admin_fee_setting_id = ?";
            $result = $this->dbInstance->fetchOne($checkQuery, [$id]);
            
            if (($result['count'] ?? 0) > 0) {
                // Don't delete, just deactivate
                return $this->update($id, ['is_active' => 0]);
            }
            
            $query = "DELETE FROM {$this->table} WHERE id = ?";
            return $this->dbInstance->execute($query, [$id]);
            
        } catch (Exception $e) {
            error_log("Error deleting admin fee setting: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toggle active status
     */
    public function toggleActive($id) {
        try {
            $query = "UPDATE {$this->table} SET is_active = NOT is_active WHERE id = ?";
            return $this->dbInstance->execute($query, [$id]);
            
        } catch (Exception $e) {
            error_log("Error toggling admin fee setting: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get admin fee setting by name
     */
    public function getByName($name) {
        $query = "SELECT * FROM {$this->table} WHERE name = ? AND is_active = 1";
        return $this->dbInstance->fetchOne($query, [$name]);
    }
}
