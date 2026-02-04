<?php
/**
 * Session Model
 * Manages database sessions and login history
 */

class Session {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all active sessions
     */
    public function getActiveSessions($minutes = 30) {
        $timestamp = time() - ($minutes * 60);
        $sql = "SELECT s.*, u.full_name, u.email 
                FROM sessions s
                LEFT JOIN users u ON s.user_id = u.id
                WHERE s.last_activity > ?
                ORDER BY s.last_activity DESC";
        return $this->db->fetchAll($sql, [$timestamp]);
    }
    
    /**
     * Get user sessions
     */
    public function getUserSessions($userId) {
        $sql = "SELECT id, ip_address, user_agent, last_activity, created_at 
                FROM sessions 
                WHERE user_id = ? 
                ORDER BY last_activity DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    /**
     * Destroy specific session
     */
    public function destroySession($sessionId) {
        $sql = "DELETE FROM sessions WHERE id = ?";
        return $this->db->execute($sql, [$sessionId]);
    }
    
    /**
     * Destroy all user sessions
     */
    public function destroyAllUserSessions($userId) {
        $sql = "DELETE FROM sessions WHERE user_id = ?";
        return $this->db->execute($sql, [$userId]);
    }
    
    /**
     * Clean expired sessions
     */
    public function cleanExpiredSessions($hours = 24) {
        $timestamp = time() - ($hours * 3600);
        $sql = "DELETE FROM sessions WHERE last_activity < ?";
        return $this->db->execute($sql, [$timestamp]);
    }
    
    /**
     * Log user login
     */
    public function logLogin($userId, $status = 'success') {
        $data = [
            'user_id' => $userId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'status' => $status
        ];
        return $this->db->insert('login_history', $data);
    }
    
    /**
     * Update logout time
     */
    public function logLogout($userId) {
        $sql = "UPDATE login_history 
                SET logout_at = NOW() 
                WHERE user_id = ? 
                AND logout_at IS NULL 
                ORDER BY login_at DESC 
                LIMIT 1";
        return $this->db->execute($sql, [$userId]);
    }
    
    /**
     * Get user login history
     */
    public function getLoginHistory($userId, $limit = 10) {
        $sql = "SELECT * FROM login_history 
                WHERE user_id = ? 
                ORDER BY login_at DESC 
                LIMIT ?";
        return $this->db->fetchAll($sql, [$userId, $limit]);
    }
    
    /**
     * Get recent login attempts
     */
    public function getRecentLoginAttempts($email, $minutes = 15) {
        $sql = "SELECT COUNT(*) as count 
                FROM login_history lh
                JOIN users u ON lh.user_id = u.id
                WHERE u.email = ? 
                AND lh.status = 'failed'
                AND lh.login_at > DATE_SUB(NOW(), INTERVAL ? MINUTE)";
        $result = $this->db->fetchOne($sql, [$email, $minutes]);
        return $result['count'] ?? 0;
    }
    
    /**
     * Count active users
     */
    public function countActiveUsers($minutes = 30) {
        $timestamp = time() - ($minutes * 60);
        $sql = "SELECT COUNT(DISTINCT user_id) as count 
                FROM sessions 
                WHERE user_id IS NOT NULL 
                AND last_activity > ?";
        $result = $this->db->fetchOne($sql, [$timestamp]);
        return $result['count'] ?? 0;
    }
}
