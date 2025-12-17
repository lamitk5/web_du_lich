<?php
/**
 * config/Database.php
 * Lớp quản lý kết nối và truy vấn CSDL
 */

class Database {
    private $connection;
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
     * Lấy đối tượng kết nối mysqli gốc (để dùng cho các hàm cần mysqli raw)
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Thực hiện truy vấn SELECT (Trả về mảng kết quả)
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
     * Lấy một dòng dữ liệu duy nhất
     */
    public function selectOne($sql, $params = []) {
        $results = $this->select($sql, $params);
        return count($results) > 0 ? $results[0] : null;
    }

    /**
     * Thực thi câu lệnh SQL Raw (INSERT, UPDATE, DELETE) có tham số
     * Hàm này quan trọng để code Booking hoạt động
     */
    public function execute($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            $this->lastError = $this->connection->error;
            // Ghi log lỗi nếu cần thiết
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
     * Chèn dữ liệu (Helper function)
     */
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        return $this->execute($sql, array_values($data));
    }
    
    /**
     * Cập nhật dữ liệu (Helper function)
     */
    public function update($table, $data, $condition, $params = []) {
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
        }
        $setStr = implode(', ', $set);
        $sql = "UPDATE $table SET $setStr WHERE $condition";
        
        $allParams = array_merge(array_values($data), $params);
        return $this->execute($sql, $allParams);
    }
    
    /**
     * Xóa dữ liệu (Helper function)
     */
    public function delete($table, $condition, $params = []) {
        $sql = "DELETE FROM $table WHERE $condition";
        return $this->execute($sql, $params);
    }
    
    /**
     * Đếm số bản ghi
     */
    public function count($table, $condition = '1=1', $params = []) {
        $sql = "SELECT COUNT(*) as total FROM $table WHERE $condition";
        $result = $this->selectOne($sql, $params);
        return $result ? (int)$result['total'] : 0;
    }
    
    // --- Các hàm tiện ích ---

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
    
    public function getLastInsertId() {
        return $this->connection->insert_id;
    }

    public function getLastError() {
        return $this->lastError;
    }
    
    /**
     * Bắt đầu một giao dịch (Transaction)
     */
    public function beginTransaction() {
        if (!$this->connection->begin_transaction()) {
            $this->lastError = $this->connection->error;
            return false;
        }
        return true;
    }

    /**
     * Xác nhận giao dịch (Commit)
     */
    public function commit() {
        if (!$this->connection->commit()) {
            $this->lastError = $this->connection->error;
            return false;
        }
        return true;
    }

    /**
     * Hoàn tác giao dịch (Rollback)
     */
    public function rollback() {
        if (!$this->connection->rollback()) {
            $this->lastError = $this->connection->error;
            return false;
        }
        return true;
    }
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
?>