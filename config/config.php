<?php
/**
 * config/config.php
 * Cấu hình chung của ứng dụng - Phiên bản đầy đủ
 */

// ============ 1. Database Configuration ============
define('DB_HOST', 'localhost');
define('DB_NAME', 'travel_booking');
define('DB_USER', 'root');
define('DB_PASS', '');

// ============ 2. Site Configuration ============
define('SITE_NAME', 'FlyHigh');
define('SITE_URL', 'http://localhost/flyhigh');

// ============ 3. Security & Session ============
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
define('SESSION_TIMEOUT', 3600);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 15 * 60); 

// ============ 4. Timezone & Error Reporting ============
date_default_timezone_set('Asia/Ho_Chi_Minh');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ============ 5. Require Files ============
require_once __DIR__ . '/Database.php';

// Load Auth nếu có
if (file_exists(__DIR__ . '/Auth.php')) {
    require_once __DIR__ . '/Auth.php';
    if (class_exists('Auth') && method_exists('Auth', 'init')) {
        Auth::init();
    }
}

// ============ 6. Database Instance (Singleton) ============
$db = null;

function db() {
    global $db;
    if ($db === null) {
        $db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }
    return $db;
}

function getConnection() {
    return db()->getConnection();
}

// ============ 7. AUTH HELPER FUNCTIONS ============

/**
 * Lấy user hiện tại
 */
function getCurrentUser() {
    if (class_exists('Auth')) {
        return Auth::getCurrentUser();
    }
    return $_SESSION['user'] ?? null;
}

/**
 * Kiểm tra đăng nhập
 */
function isLoggedIn() {
    if (class_exists('Auth')) {
        return Auth::isLoggedIn();
    }
    return isset($_SESSION['user']);
}

/**
 * Yêu cầu đăng nhập (Chặn trang)
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        setFlashMessage('error', 'Vui lòng đăng nhập để tiếp tục.');
        header("Location: login.php");
        exit;
    }
}

// ============ 8. FLASH MESSAGES (THÔNG BÁO) ============

/**
 * Tạo thông báo flash (lưu trong session để hiện ở trang tiếp theo)
 * @param string $type 'success', 'error', 'warning', 'info'
 * @param string $message Nội dung thông báo
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Lấy và xóa thông báo flash
 */
function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// ============ 9. LOGGING SYSTEM (GHI NHẬT KÝ) ============

/**
 * Ghi log cơ bản vào file
 * @param string $message Thông điệp log
 * @param string $type Loại log (INFO, SUCCESS, ERROR)
 * @param array $context Dữ liệu bổ sung (ví dụ: ['ip' => '...'])
 */
function writeLog($message, $type = 'INFO', $context = []) { 
    $logDir = __DIR__ . '/../logs'; // Thư mục logs nằm ngoài config
    
    // Tạo thư mục nếu chưa có
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $date = date('Y-m-d');
    $time = date('H:i:s');
    $logFile = $logDir . "/log-$date.txt";
    
    // Chuyển context thành chuỗi JSON (nếu có)
    $contextString = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
    
    // Format: [TIME] [TYPE] Message {Context}
    $content = "[$time] [$type] $message{$contextString}" . PHP_EOL;
    
    // Ghi nối tiếp vào file (Append)
    file_put_contents($logFile, $content, FILE_APPEND);
}

/**
 * Ghi log thành công
 */
function logSuccess($message, $context = []) {
    writeLog($message, 'SUCCESS', $context);
}

/**
 * Ghi log lỗi
 */
function logError($message, $context = []) {
    writeLog($message, 'ERROR', $context);
}

// ============ 10. GENERAL HELPERS & VALIDATION ============

function redirect($url) {
    header("Location: " . $url);
    exit;
}

function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.') . '₫';
}

function formatDate($datetime, $format = 'd/m/Y') {
    if (empty($datetime)) return '-';
    return date($format, strtotime($datetime));
}

function cleanInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Kiểm tra xem chuỗi có phải là một định dạng email hợp lệ hay không
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function dd($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die;
}

// ============ 11. SECURITY & AUTH HELPERS ============

/**
 * Lấy địa chỉ IP của người dùng
 */
function getUserIP() {
    // Ưu tiên các header proxy nếu có
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // Lọc IP nếu nó là danh sách
    if (strpos($ip, ',') !== false) {
        $ip = trim(explode(',', $ip)[0]);
    }
    return $ip;
}

/**
 * Ghi lại nỗ lực đăng nhập thất bại và kiểm tra khóa IP
 * @param bool $success True nếu đăng nhập thành công, False nếu thất bại
 */
function recordLoginAttempt($success) {
    $ip = getUserIP();
    
    // Khởi tạo session cho IP nếu chưa có
    if (!isset($_SESSION['login_attempts'][$ip])) {
        $_SESSION['login_attempts'][$ip] = [
            'count' => 0,
            'lockout_until' => 0
        ];
    }

    if ($success) {
        // Nếu thành công, reset bộ đếm
        $_SESSION['login_attempts'][$ip]['count'] = 0;
        $_SESSION['login_attempts'][$ip]['lockout_until'] = 0;
    } else {
        // Nếu thất bại, tăng bộ đếm
        $_SESSION['login_attempts'][$ip]['count']++;

        // Nếu vượt quá số lần cho phép, khóa IP
        if ($_SESSION['login_attempts'][$ip]['count'] >= MAX_LOGIN_ATTEMPTS) {
            // Thiết lập thời gian khóa
            $_SESSION['login_attempts'][$ip]['lockout_until'] = time() + LOCKOUT_TIME;
            
            // Ghi log khóa IP
            writeLog("IP locked due to {$ip}", 'SECURITY', ['ip' => $ip, 'reason' => 'Too many failed login attempts']);
        }
    }
}

// Bổ sung vào config.php

/**
 * Định dạng ngày giờ/ngày tháng
 */
function formatDateTime($datetime, $format = 'd/m/Y H:i:s') {
    if (empty($datetime) || $datetime === '0000-00-00 00:00:00') return '-';
    return date($format, strtotime($datetime));
}

/**
 * Hiển thị huy hiệu (badge) cho trạng thái đơn hàng
 */
function getStatusBadge($status) {
    switch (strtolower($status)) {
        case 'confirmed':
            return '<span class="px-3 py-1 text-xs font-bold text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-400 rounded-full">Đã xác nhận</span>';
        case 'pending':
            return '<span class="px-3 py-1 text-xs font-bold text-amber-700 bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400 rounded-full">Chờ xử lý</span>';
        case 'cancelled':
            return '<span class="px-3 py-1 text-xs font-bold text-red-700 bg-red-100 dark:bg-red-900/30 dark:text-red-400 rounded-full">Đã hủy</span>';
        case 'completed':
            return '<span class="px-3 py-1 text-xs font-bold text-blue-700 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 rounded-full">Hoàn tất</span>';
        default:
            return '<span class="px-3 py-1 text-xs font-bold text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded-full">'.htmlspecialchars($status).'</span>';
    }
}
/**
 * Kiểm tra xem IP hiện tại có bị khóa tạm thời hay không
 * @return bool True nếu bị khóa, False nếu không
 */
function isIPLocked() {
    $ip = getUserIP();
    
    if (isset($_SESSION['login_attempts'][$ip])) {
        $attempts = $_SESSION['login_attempts'][$ip];
        
        // Kiểm tra xem thời gian khóa đã hết chưa
        if ($attempts['lockout_until'] > time()) {
            return true; // Vẫn đang bị khóa
        } else {
            // Nếu đã hết thời gian khóa, reset bộ đếm
            if ($attempts['count'] >= MAX_LOGIN_ATTEMPTS) {
                $_SESSION['login_attempts'][$ip]['count'] = 0;
            }
        }
    }
    return false;
}
function getPagination($total_items, $current_page, $items_per_page = 10) {
    // 1. Ép kiểu và đảm bảo giá trị hợp lệ
    $total_items = (int)$total_items;
    $items_per_page = max(1, (int)$items_per_page); // Số mục phải >= 1

    // 2. Xử lý trường hợp không có mục nào
    if ($total_items <= 0) {
        return [
            'total_items' => 0, 
            'total_pages' => 1, 
            'current_page' => 1, 
            'items_per_page' => $items_per_page,
            'offset' => 0, 
            'has_prev' => false, 
            'has_next' => false,
            'range_start' => 0, // Mục bắt đầu trên trang hiện tại (0 nếu không có)
            'range_end' => 0    // Mục kết thúc trên trang hiện tại (0 nếu không có)
        ];
    }
    
    // 3. Tính tổng số trang
    $total_pages = ceil($total_items / $items_per_page);
    
    // 4. Đảm bảo trang hiện tại nằm trong phạm vi (1 đến total_pages)
    $current_page = max(1, min((int)$current_page, $total_pages));
    
    // 5. Tính OFFSET (vị trí bắt đầu truy vấn trong SQL: LIMIT offset, items_per_page)
    $offset = ($current_page - 1) * $items_per_page;

    // 6. Tính phạm vi hiển thị trên trang (Ví dụ: Hiển thị 10-19)
    $range_start = $offset + 1;
    $range_end = min($offset + $items_per_page, $total_items);
    
    // 7. Trả về kết quả
    return [
        'total_items' => $total_items,
        'total_pages' => (int)$total_pages,
        'current_page' => (int)$current_page,
        'items_per_page' => (int)$items_per_page,
        'offset' => (int)$offset,
        'has_prev' => $current_page > 1,
        'has_next' => $current_page < $total_pages,
        'range_start' => (int)$range_start,
        'range_end' => (int)$range_end
    ];
}
?>