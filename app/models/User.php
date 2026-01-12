<?php
/**
 * User Model
 * Handles user data operations
 */

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find user by email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        return $this->db->fetchOne($sql, [$email]);
    }
    
    /**
     * Find user by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    /**
     * Create new user
     */
    public function create($data) {
        $data['password'] = password_hash($data['password'], HASH_ALGO, ['cost' => HASH_COST]);
        return $this->db->insert('users', $data);
    }
    
    /**
     * Update user
     */
    public function update($id, $data) {
        return $this->db->update('users', $data, 'id = ?', [$id]);
    }
    
    /**
     * Verify password
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Get all users
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT id, full_name, email, phone, role, status, created_at FROM users";
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Count total users
     */
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }
    
    /**
     * Update remember token
     */
    public function updateRememberToken($userId, $token) {
        return $this->db->update('users', ['remember_token' => $token], 'id = ?', [$userId]);
    }
    
    /**
     * Find user by remember token
     */
    public function findByRememberToken($token) {
        $sql = "SELECT * FROM users WHERE remember_token = ? AND status = 'active'";
        return $this->db->fetchOne($sql, [$token]);
    }
}
