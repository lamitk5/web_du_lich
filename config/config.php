<?php
/**
 * config/config.php
 * Cấu hình chung của ứng dụng
 */

// ============ Database Configuration ============
define('DB_HOST', 'localhost');
define('DB_NAME', 'travel_booking');
define('DB_USER', 'root');
define('DB_PASS', '');

// ============ Site Configuration ============
define('SITE_NAME', 'FlyHigh');
define('SITE_URL', 'http://localhost/flyhigh');
define('SITE_EMAIL', 'admin@flyhigh.com');

// ============ Security Configuration ============
define('SESSION_TIMEOUT', 3600); // 1 giờ (seconds)
define('PASSWORD_MIN_LENGTH', 6);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 15 * 60); // 15 phút (seconds)

// ============ Timezone ============
date_default_timezone_set('Asia/Ho_Chi_Minh');

// ============ Error Reporting ============
error_reporting(E_ALL);
ini_set('display_errors', 0); // Không hiển thị lỗi ở browser
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// ============ Require Files ============
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Auth.php';

// ============ Global Database Instance ============
$db = null;

function db() {
    global $db;
    if ($db === null) {
        $db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }
    return $db;
}

// ============ Helper Functions ============

/**
 * Chuyển hướng trang
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Kiểm tra người dùng đã đăng nhập
 */
function isLoggedIn() {
    return Auth::isLoggedIn();
}

/**
 * Lấy người dùng hiện tại
 */
function getCurrentUser() {
    return Auth::getCurrentUser();
}

/**
 * Yêu cầu đăng nhập
 */
function requireLogin() {
    if (!Auth::isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect('login.php');
    }
}

/**
 * Yêu cầu quyền admin
 */
function requireAdmin() {
    if (!Auth::isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect('../login.php'); // Redirect tới login ở ngoài thư mục admin
    }
    
    if (!Auth::isAdmin()) {
        header("HTTP/1.0 403 Forbidden");
        die("Bạn không có quyền truy cập trang này");
    }
}

/**
 * Làm sạch input
 */
function cleanInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Kiểm tra email hợp lệ
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Mã hóa mật khẩu
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Kiểm tra mật khẩu
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Định dạng tiền tệ
 */
function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.') . '₫';
}

/**
 * Định dạng ngày giờ
 */
function formatDateTime($datetime, $format = 'd/m/Y H:i') {
    if (empty($datetime)) {
        return '-';
    }
    return date($format, strtotime($datetime));
}

/**
 * Lấy tin nhắn flash
 */
function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Đặt tin nhắn flash
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Lấy phân trang
 */
function getPagination($page = 1, $total = 0, $items_per_page = 10) {
    $page = max(1, (int)$page);
    $offset = ($page - 1) * $items_per_page;
    $total_pages = ceil($total / $items_per_page);
    
    return [
        'page' => $page,
        'items_per_page' => $items_per_page,
        'offset' => $offset,
        'total' => $total,
        'total_pages' => $total_pages,
        'has_prev' => $page > 1,
        'has_next' => $page < $total_pages
    ];
}

/**
 * Tạo slug từ chuỗi
 */
function createSlug($string) {
    $string = strtolower($string);
    $string = trim($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    $string = trim($string, '-');
    return $string;
}

/**
 * Sinh mã booking
 */
function generateBookingCode() {
    return strtoupper(substr(uniqid(), -6));
}

/**
 * Lấy địa chỉ IP
 */
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

/**
 * Ghi log
 */
function writeLog($message, $type = 'info') {
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . '/' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$type] $message\n";
    
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

/**
 * Ghi log error
 */
function logError($message, $context = []) {
    $contextStr = !empty($context) ? ' | ' . json_encode($context) : '';
    writeLog($message . $contextStr, 'ERROR');
}

/**
 * Ghi log success
 */
function logSuccess($message) {
    writeLog($message, 'SUCCESS');
}

/**
 * Kiểm tra IP bị khóa (brute force protection)
 */
function isIPLocked() {
    $ip = getUserIP();
    $lockFile = __DIR__ . '/../logs/ip_locks.json';
    
    if (!file_exists($lockFile)) {
        return false;
    }
    
    $locks = json_decode(file_get_contents($lockFile), true);
    
    if (isset($locks[$ip])) {
        $lockTime = $locks[$ip]['time'];
        $attempts = $locks[$ip]['attempts'];
        
        // Nếu hết thời gian lock
        if (time() - $lockTime > LOCKOUT_TIME) {
            // Xóa lock
            unset($locks[$ip]);
            file_put_contents($lockFile, json_encode($locks));
            return false;
        }
        
        // Nếu vẫn còn lock
        if ($attempts >= MAX_LOGIN_ATTEMPTS) {
            return true;
        }
    }
    
    return false;
}

/**
 * Record login attempt
 */
function recordLoginAttempt($success = false) {
    $ip = getUserIP();
    $lockFile = __DIR__ . '/../logs/ip_locks.json';
    
    $locks = file_exists($lockFile) ? json_decode(file_get_contents($lockFile), true) : [];
    
    if ($success) {
        // Reset attempts nếu login thành công
        if (isset($locks[$ip])) {
            unset($locks[$ip]);
        }
    } else {
        // Increment attempts
        if (!isset($locks[$ip])) {
            $locks[$ip] = ['attempts' => 0, 'time' => time()];
        }
        
        $locks[$ip]['attempts']++;
        $locks[$ip]['time'] = time();
    }
    
    file_put_contents($lockFile, json_encode($locks));
}

/**
 * Kiểm tra quyền hạn
 */
function hasPermission($action) {
    $user = getCurrentUser();
    if (!$user) {
        return false;
    }
    
    // Admin có tất cả quyền
    if ($user['role'] === 'admin') {
        return true;
    }
    
    return false;
}

/**
 * Lấy URL redirect sau login
 */
function getRedirectAfterLogin() {
    if (isset($_SESSION['redirect_after_login'])) {
        $url = $_SESSION['redirect_after_login'];
        unset($_SESSION['redirect_after_login']);
        return $url;
    }
    
    return Auth::getRedirectUrl();
}

/**
 * Initialize Auth
 */
function formatDate($datetime, $format = 'd/m/Y') {
    if (empty($datetime)) return '-';
    return date($format, strtotime($datetime));
}

/**
 * [MỚI] Hàm tạo Badge trạng thái tự động (HTML)
 * Fix lỗi: Call to undefined function getStatusBadge()
 */
function getStatusBadge($status) {
    switch ($status) {
        case 'confirmed':
        case 'active':
        case 'paid':
        case 'available':
            $color = 'green';
            $label = 'Đã xác nhận';
            if ($status == 'active') $label = 'Hoạt động';
            if ($status == 'paid') $label = 'Đã thanh toán';
            if ($status == 'available') $label = 'Sẵn có';
            break;
            
        case 'pending':
        case 'unpaid':
        case 'scheduled':
            $color = 'yellow';
            $label = 'Chờ xử lý';
            if ($status == 'scheduled') $label = 'Đúng giờ';
            if ($status == 'unpaid') $label = 'Chưa thanh toán';
            break;
            
        case 'completed':
            $color = 'blue';
            $label = 'Hoàn thành';
            break;
            
        case 'cancelled':
        case 'blocked':
        case 'maintenance':
        case 'refunded':
            $color = 'red';
            $label = 'Đã hủy';
            if ($status == 'blocked') $label = 'Đã khóa';
            if ($status == 'maintenance') $label = 'Bảo trì';
            if ($status == 'refunded') $label = 'Đã hoàn tiền';
            break;
            
        default:
            $color = 'gray';
            $label = $status;
    }

    // Trả về HTML badge chuẩn Tailwind
    return "<span class='inline-flex items-center gap-1.5 rounded-full bg-{$color}-100 px-2.5 py-0.5 text-xs font-medium text-{$color}-800 dark:bg-{$color}-900/30 dark:text-{$color}-300'>
                <span class='h-1.5 w-1.5 rounded-full bg-{$color}-500'></span>
                $label
            </span>";
}

Auth::init();
?>