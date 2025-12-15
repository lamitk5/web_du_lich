<?php
/**
 * config/Auth.php
 * Lớp xử lý authentication (login/logout/session)
 */

class Auth {
    private static $sessionTimeout = 3600; // 1 giờ
    
    /**
     * Khởi tạo Auth (gọi ở config.php)
     */
    public static function init() {
        // Start session nếu chưa start
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra session timeout
        self::checkTimeout();
    }
    
    /**
     * Đăng nhập người dùng
     */
    public static function login($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();
        
        // Lưu vào cookie (optional - để nhớ login)
        if (isset($_POST['remember_me'])) {
            setcookie('user_remember', $user['id'], time() + (30 * 24 * 60 * 60), '/');
        }
        
        return true;
    }
    
    /**
     * Đăng xuất người dùng
     */
    public static function logout() {
        // Xóa session
        $_SESSION = [];
        
        // Xóa cookies
        if (isset($_COOKIE['PHPSESSID'])) {
            setcookie('PHPSESSID', '', time() - 3600, '/');
        }
        if (isset($_COOKIE['user_remember'])) {
            setcookie('user_remember', '', time() - 3600, '/');
        }
        
        // Destroy session
        session_destroy();
        
        return true;
    }
    
    /**
     * Kiểm tra người dùng đã đăng nhập hay chưa
     */
    public static function isLoggedIn() {
        self::init();
        
        // Kiểm tra session
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            return true;
        }
        
        // Kiểm tra cookie remember
        if (isset($_COOKIE['user_remember']) && !empty($_COOKIE['user_remember'])) {
            $userId = (int)$_COOKIE['user_remember'];
            $user = db()->selectOne("SELECT * FROM users WHERE id = ? AND status = 'active'", [$userId]);
            
            if ($user) {
                self::login($user);
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Lấy thông tin người dùng hiện tại
     */
    public static function getCurrentUser() {
        self::init();
        
        if (!self::isLoggedIn()) {
            return null;
        }
        
        // Lấy từ CSDL để đảm bảo dữ liệu mới nhất
        return db()->selectOne("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
    }
    
    /**
     * Kiểm tra có phải admin không
     */
    public static function isAdmin() {
        self::init();
        
        if (!self::isLoggedIn()) {
            return false;
        }
        
        return $_SESSION['user_role'] === 'admin';
    }
    
    /**
     * Kiểm tra session timeout
     */
    private static function checkTimeout() {
        if (!isset($_SESSION['last_activity'])) {
            $_SESSION['last_activity'] = time();
            return;
        }
        
        $elapsed = time() - $_SESSION['last_activity'];
        
        // Nếu quá thời gian timeout
        if ($elapsed > self::$sessionTimeout) {
            self::logout();
            return;
        }
        
        // Update lại last activity
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Thay đổi mật khẩu
     */
    public static function changePassword($userId, $oldPassword, $newPassword) {
        $user = db()->selectOne("SELECT * FROM users WHERE id = ?", [$userId]);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Người dùng không tồn tại'];
        }
        
        if (!password_verify($oldPassword, $user['password'])) {
            return ['success' => false, 'message' => 'Mật khẩu cũ không chính xác'];
        }
        
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        
        if (db()->update('users', ['password' => $newHash], 'id = ?', [$userId])) {
            return ['success' => true, 'message' => 'Thay đổi mật khẩu thành công'];
        }
        
        return ['success' => false, 'message' => 'Lỗi khi thay đổi mật khẩu'];
    }
    
    /**
     * Xác minh email
     */
    public static function verifyEmail($email, $password) {
        $user = db()->selectOne("SELECT * FROM users WHERE email = ?", [$email]);
        
        if (!$user) {
            return null;
        }
        
        if (!password_verify($password, $user['password'])) {
            return null;
        }
        
        if ($user['status'] === 'blocked') {
            return null;
        }
        
        return $user;
    }
    
    /**
     * Tạo token reset password
     */
    public static function createResetToken($userId) {
        $token = bin2hex(random_bytes(32));
        $hash = hash('sha256', $token);
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 giờ
        
        db()->update('users', 
            ['reset_token' => $hash, 'reset_expires' => $expires], 
            'id = ?', 
            [$userId]
        );
        
        return $token;
    }
    
    /**
     * Xác minh reset token
     */
    public static function verifyResetToken($token) {
        $hash = hash('sha256', $token);
        $user = db()->selectOne(
            "SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()",
            [$hash]
        );
        
        return $user;
    }
    
    /**
     * Reset mật khẩu
     */
    public static function resetPassword($userId, $newPassword) {
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        
        return db()->update('users', 
            ['password' => $newHash, 'reset_token' => NULL, 'reset_expires' => NULL], 
            'id = ?', 
            [$userId]
        );
    }
    
    /**
     * Lấy URL redirect sau login
     */
    public static function getRedirectUrl() {
        if (self::isAdmin()) {
            return 'admin/dashboard.php';
        }
        return 'index.php';
    }
}
?>