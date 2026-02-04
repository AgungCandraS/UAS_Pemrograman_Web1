<?php
/**
 * Database Class (MySQLi)
 * Replacement for PDO-based Database.php
 * Compatible with PHP 7.4+ and shared hosting without PDO extension
 */

class Database {
    private static $instance = null;

    /** @var mysqli */
    private $connection;

    /** @var int */
    private $affectedRows = 0;

    /**
     * Private constructor for singleton
     */
    private function __construct() {
        // Ambil config dari constant yang sudah kamu pakai
        $host = defined('DB_HOST') ? DB_HOST : 'localhost';
        $name = defined('DB_NAME') ? DB_NAME : 'acax9288_bisnisku';
        $user = defined('DB_USER') ? DB_USER : 'acax9288_users';
        $pass = defined('DB_PASS') ? DB_PASS : 'Acas080106#';

        $charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';

        $this->connection = @new mysqli($host, $user, $pass, $name);

        if ($this->connection->connect_error) {
            error_log("Database connection failed (MySQLi): " . $this->connection->connect_error);
            die("Database connection failed.");
        }

        // set charset
        $this->connection->set_charset($charset);
    }

    /**
     * Get database instance (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get MySQLi connection
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Prepare + bind params automatically
     */
    private function prepareAndBind($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            error_log("DB prepare failed: " . $this->connection->error . " | SQL: " . $sql);
            return false;
        }

        if (!empty($params)) {
            $types = '';
            $values = [];

            foreach ($params as $p) {
                if (is_int($p)) $types .= 'i';
                elseif (is_float($p)) $types .= 'd';
                else $types .= 's';
                $values[] = $p;
            }

            // bind_param butuh reference
            $refs = [];
            $refs[] = &$types;
            for ($i = 0; $i < count($values); $i++) {
                $refs[] = &$values[$i];
            }

            call_user_func_array([$stmt, 'bind_param'], $refs);
        }

        return $stmt;
    }

    /**
     * Execute a query and return statement (for fetch)
     */
    public function query($sql, $params = []) {
        $stmt = $this->prepareAndBind($sql, $params);
        if ($stmt === false) return false;

        $ok = $stmt->execute();
        if (!$ok) {
            if (defined('APP_DEBUG') && APP_DEBUG) {
                throw new Exception("DB query error: " . $stmt->error);
            }
            error_log("DB query error: " . $stmt->error . " | SQL: " . $sql);
            $stmt->close();
            return false;
        }

        $this->affectedRows = $stmt->affected_rows;
        return $stmt;
    }

    /**
     * Execute a query and return boolean
     */
    public function execute($sql, $params = []) {
        $stmt = $this->prepareAndBind($sql, $params);
        if ($stmt === false) return false;

        $ok = $stmt->execute();
        if (!$ok) {
            if (defined('APP_DEBUG') && APP_DEBUG) {
                throw new Exception("DB execute error: " . $stmt->error);
            }
            error_log("DB execute error: " . $stmt->error . " | SQL: " . $sql);
        }

        $this->affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $ok;
    }

    /**
     * Get affected rows from last query
     */
    public function affectedRows() {
        return (int)$this->affectedRows;
    }

    /**
     * Fetch all rows
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        if ($stmt === false) return [];

        $result = $stmt->get_result();
        $rows = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $result->free();
        }

        $stmt->close();
        return $rows;
    }

    /**
     * Fetch single row
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        if ($stmt === false) return null;

        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;

        if ($result) $result->free();
        $stmt->close();

        return $row;
    }

    /**
     * Insert data (returns insert_id or true on success, false on failure)
     */
    public function insert($table, $data) {
        if (!is_array($data) || empty($data)) return false;

        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');

        $sql = "INSERT INTO {$table} (" . implode(', ', $fields) . ")
                VALUES (" . implode(', ', $placeholders) . ")";

        $stmt = $this->query($sql, array_values($data));
        if ($stmt === false) return false;

        $insertId = $this->connection->insert_id;
        $stmt->close();
        
        // Return insert_id if > 0, otherwise return true (for success without auto-increment)
        return $insertId > 0 ? $insertId : true;
    }

    /**
     * Update data
     */
    public function update($table, $data, $where, $whereParams = []) {
        if (!is_array($data) || empty($data)) return false;

        $fields = [];
        foreach (array_keys($data) as $field) {
            $fields[] = "{$field} = ?";
        }

        $sql = "UPDATE {$table} SET " . implode(', ', $fields) . " WHERE {$where}";
        $params = array_merge(array_values($data), (array)$whereParams);

        return $this->execute($sql, $params);
    }

    /**
     * Delete data
     */
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->execute($sql, (array)$params);
    }

    /**
     * Transaction helpers (MySQLi)
     */
    public function beginTransaction() {
        return $this->connection->begin_transaction();
    }

    public function commit() {
        return $this->connection->commit();
    }

    public function rollback() {
        return $this->connection->rollback();
    }
}
