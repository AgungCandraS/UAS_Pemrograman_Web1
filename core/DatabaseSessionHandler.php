<?php
/**
 * Database Session Handler
 * Compatible with PHP 7.4+ (no union types)
 */

class DatabaseSessionHandler implements SessionHandlerInterface {
    private $db;
    private $lifetime;
    private $userId = null;

    public function __construct() {
        $this->db = Database::getInstance();
        $lt = ini_get('session.gc_maxlifetime');
        $this->lifetime = ($lt !== false && $lt !== null && $lt !== '') ? (int)$lt : 7200;
    }

    /** @return bool */
    public function open($savePath, $sessionName) {
        return true;
    }

    /** @return bool */
    public function close() {
        return true;
    }

    /** @return string|false */
    public function read($id) {
        $sql = "SELECT payload FROM sessions WHERE id = ? LIMIT 1";
        $result = $this->db->fetchOne($sql, [$id]);

        if ($result && isset($result['payload'])) {
            $this->touch($id);
            return (string)$result['payload'];
        }

        return '';
    }

    /** @return bool */
    public function write($id, $data) {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $ipAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
        $lastActivity = time();

        $sql = "SELECT id FROM sessions WHERE id = ? LIMIT 1";
        $exists = $this->db->fetchOne($sql, [$id]);

        if ($exists) {
            $updateData = [
                'user_id' => $userId,
                'payload' => $data,
                'last_activity' => $lastActivity,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent
            ];

            return $this->db->update('sessions', $updateData, 'id = ?', [$id]);
        }

        $insertData = [
            'id' => $id,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'payload' => $data,
            'last_activity' => $lastActivity
        ];

        return $this->db->insert('sessions', $insertData) !== false;
    }

    /** @return bool */
    public function destroy($id) {
        $sql = "DELETE FROM sessions WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    /** @return int|false */
    public function gc($maxlifetime) {
        $expired = time() - (int)$maxlifetime;
        $sql = "DELETE FROM sessions WHERE last_activity < ?";
        $this->db->execute($sql, [$expired]);
        return $this->db->affectedRows();
    }

    private function touch($id) {
        $sql = "UPDATE sessions SET last_activity = ? WHERE id = ?";
        return $this->db->execute($sql, [time(), $id]);
    }

    public function getUserSessions($userId) {
        $sql = "SELECT id, ip_address, user_agent, last_activity, created_at
                FROM sessions
                WHERE user_id = ?
                ORDER BY last_activity DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }

    public function destroyOtherSessions($userId, $currentSessionId) {
        $sql = "DELETE FROM sessions WHERE user_id = ? AND id != ?";
        return $this->db->execute($sql, [$userId, $currentSessionId]);
    }

    public function countActiveSessions() {
        $recentTime = time() - 1800;
        $sql = "SELECT COUNT(DISTINCT user_id) as count FROM sessions WHERE last_activity > ?";
        $result = $this->db->fetchOne($sql, [$recentTime]);
        return isset($result['count']) ? (int)$result['count'] : 0;
    }
}
