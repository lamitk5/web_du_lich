<?php
/**
 * config/Database.php
 * Lớp quản lý kết nối và truy vấn CSDL
 */

class Database {
    private $connection;
    private $lastQuery;
    private $lastError;
    
    public function __construct($host, $database, $username, $password) {
        try {
            $this->connection = new mysqli($host, $username, $password, $database);
            
            if ($this->connection->connect_error) {
                throw new Exception("Lỗi kết nối: " . $this->connection->connect_error);
            }
            
            $this->connection->set_charset("utf8mb4");
        } catch (Exception $e) {
            die("Không thể kết nối CSDL: " . $e->getMessage());
        }
    }
    
    /**
     * Thực hiện truy vấn SELECT
     */
    public function select($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            $this->lastError = $this->connection->error;
            return [];
        }
        
        if (!empty($params)) {
            $types = $this->getParamTypes($params);
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        $stmt->close();
        return $data;
    }
    
    /**
     * Lấy một bản ghi
     */
    public function selectOne($sql, $params = []) {
        $results = $this->select($sql, $params);
        return count($results) > 0 ? $results[0] : null;
    }
    
    /**
     * Chèn dữ liệu
     */
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            $this->lastError = $this->connection->error;
            return false;
        }
        
        $types = $this->getParamTypes(array_values($data));
        $values = array_values($data);
        $stmt->bind_param($types, ...$values);
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    /**
     * Cập nhật dữ liệu
     */
    public function update($table, $data, $condition, $params = []) {
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
        }
        $setStr = implode(', ', $set);
        $sql = "UPDATE $table SET $setStr WHERE $condition";
        
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            $this->lastError = $this->connection->error;
            return false;
        }
        
        $allParams = array_merge(array_values($data), $params);
        $types = $this->getParamTypes($allParams);
        $stmt->bind_param($types, ...$allParams);
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    /**
     * Xóa dữ liệu
     */
    public function delete($table, $condition, $params = []) {
        $sql = "DELETE FROM $table WHERE $condition";
        
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            $this->lastError = $this->connection->error;
            return false;
        }
        
        if (!empty($params)) {
            $types = $this->getParamTypes($params);
            $stmt->bind_param($types, ...$params);
        }
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    /**
     * Đếm số bản ghi
     */
    public function count($table, $condition = '1=1', $params = []) {
        $sql = "SELECT COUNT(*) as total FROM $table WHERE $condition";
        $result = $this->selectOne($sql, $params);
        return $result ? (int)$result['total'] : 0;
    }
    
    /**
     * Lấy loại dữ liệu của tham số
     */
    private function getParamTypes($params) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        return $types;
    }
    
    /**
     * Bắt đầu transaction
     */
    public function beginTransaction() {
        $this->connection->begin_transaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit() {
        $this->connection->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback() {
        $this->connection->rollback();
    }
    
    /**
     * Lấy lỗi cuối cùng
     */
    public function getLastError() {
        return $this->lastError;
    }
    
    /**
     * Lấy ID được chèn cuối cùng
     */
    public function getLastInsertId() {
        return $this->connection->insert_id;
    }
    
    /**
     * Đóng kết nối
     */
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
    
    /**
     * Thực hiện truy vấn tùy chỉnh
     */
    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            $this->lastError = $this->connection->error;
            return false;
        }
        
        if (!empty($params)) {
            $types = $this->getParamTypes($params);
            $stmt->bind_param($types, ...$params);
        }
        
        return $stmt->execute();
    }
}
?>