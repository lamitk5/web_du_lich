<?php
/**
 * Trang đăng ký
 */

// 1. Nạp Config
require_once 'config/config.php';

// 2. [QUAN TRỌNG] Nạp Auth.php để có hàm isAdmin()
// Dòng này sẽ sửa lỗi isAdmin
if (file_exists('config/Auth.php')) {
    require_once 'config/Auth.php';
}

// 3. [FIX NHANH] Tự định nghĩa hàm isValidPhone nếu máy báo thiếu
// Dòng này sẽ sửa lỗi isValidPhone
if (!function_exists('isValidPhone')) {
    function isValidPhone($phone) {
        // Kiểm tra số điện thoại: cho phép 10-11 chữ số
        return preg_match('/^[0-9]{10,11}$/', $phone);
    }
}

// 4. [FIX NHANH] Dự phòng hàm isAdmin nếu trong Auth.php quên viết
if (!function_exists('isAdmin')) {
    function isAdmin() {
        // Kiểm tra session role (sửa 'role' thành tên key bạn dùng trong CSDL)
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'; // Hoặc 'role'
    }
}

// Nếu đã đăng nhập thì chuyển hướng
if (function_exists('isLoggedIn') && isLoggedIn()) {
    redirect(isAdmin() ? 'admin/dashboard.php' : 'user/trang_chu.php');
}

$error = '';

// Xử lý đăng ký
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // [FIX] Kiểm tra xem hàm cleanInput có tồn tại không trước khi dùng
    // Nếu hàm này nằm trong config.php thì tốt, nếu không ta dùng trim() và htmlspecialchars() thay thế
    $fullName = function_exists('cleanInput') ? cleanInput($_POST['full_name'] ?? '') : trim(htmlspecialchars($_POST['full_name'] ?? ''));
    $email    = function_exists('cleanInput') ? cleanInput($_POST['email'] ?? '') : trim(htmlspecialchars($_POST['email'] ?? ''));
    $phone    = function_exists('cleanInput') ? cleanInput($_POST['phone'] ?? '') : trim(htmlspecialchars($_POST['phone'] ?? ''));
    
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $agree    = isset($_POST['agree']);

    // Validate
    if (empty($fullName) || empty($email) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ thông tin bắt buộc';
    } elseif (function_exists('isValidEmail') && !isValidEmail($email)) { // Thêm check function_exists cho an toàn
        $error = 'Email không hợp lệ';
    } elseif (!empty($phone) && function_exists('isValidPhone') && !isValidPhone($phone)) {
        $error = 'Số điện thoại không hợp lệ';
    } elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự';
    } elseif ($password !== $confirmPassword) {
        $error = 'Mật khẩu xác nhận không khớp';
    } elseif (!$agree) {
        $error = 'Vui lòng đồng ý với điều khoản sử dụng';
    } else {
        // Kiểm tra email đã tồn tại
        // Đảm bảo hàm db() tồn tại
        if (function_exists('db')) {
            $existingUser = db()->selectOne("SELECT id FROM users WHERE email = ?", [$email]);

            if ($existingUser) {
                $error = 'Email này đã được đăng ký';
            } else {
                // Tạo tài khoản mới
                // [FIX] Kiểm tra hàm hashPassword
                $passHash = function_exists('hashPassword') ? hashPassword($password) : password_hash($password, PASSWORD_DEFAULT);
                
                $userId = db()->insert('users', [
                    'full_name' => $fullName,
                    'email'     => $email,
                    'phone'     => $phone,
                    'password'  => $passHash,
                    'role'      => 'user',
                    'status'    => 'active'
                ]);

                if ($userId) {
                    if (function_exists('setFlashMessage')) {
                        setFlashMessage('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
                    }
                    if (function_exists('redirect')) {
                        redirect('login.php');
                    } else {
                        header("Location: login.php");
                        exit;
                    }
                } else {
                    $error = 'Có lỗi xảy ra khi lưu vào CSDL. Vui lòng thử lại.';
                }
            }
        } else {
            $error = 'Lỗi hệ thống: Không tìm thấy kết nối Database (hàm db()).';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Đăng ký - Flyhigh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center relative overflow-hidden">

    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2000" class="w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
    </div>

    <div class="relative z-10 w-full max-w-md p-6">
        <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl shadow-2xl p-8 md:p-10">
            <div class="text-center mb-6">
                <a href="trang_chu.php" class="inline-flex items-center gap-2 text-[#13ecc8] hover:scale-105 transition transform">
                    <span class="material-symbols-outlined text-4xl">other_houses</span>
                    <span class="font-extrabold text-2xl text-white">Flyhigh</span>
                </a>
                <h2 class="text-2xl font-bold text-white mt-4">Tạo tài khoản mới</h2>
            </div>

            <?php if($error_message): ?>
                <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-3 rounded-xl text-sm font-bold text-center mb-4">
                    ⚠️ <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <?php if($success_message): ?>
                <div class="bg-green-500/20 border border-green-500/50 text-green-200 p-3 rounded-xl text-sm font-bold text-center mb-4">
                    ✅ <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-3 text-gray-400">person</span>
                    <input type="text" name="fullname" required placeholder="Họ và tên"
                           class="w-full bg-black/20 border border-white/10 rounded-xl px-12 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#13ecc8]">
                </div>
                
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-3 text-gray-400">mail</span>
                    <input type="email" name="email" required placeholder="Email"
                           class="w-full bg-black/20 border border-white/10 rounded-xl px-12 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#13ecc8]">
                </div>

                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-3 text-gray-400">lock</span>
                    <input type="password" name="password" required placeholder="Mật khẩu"
                           class="w-full bg-black/20 border border-white/10 rounded-xl px-12 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#13ecc8]">
                </div>

                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-3 text-gray-400">lock_reset</span>
                    <input type="password" name="confirm_password" required placeholder="Nhập lại mật khẩu"
                           class="w-full bg-black/20 border border-white/10 rounded-xl px-12 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#13ecc8]">
                </div>

                <button type="submit" class="w-full bg-[#13ecc8] hover:bg-[#0fb89c] text-gray-900 font-bold py-3.5 rounded-xl shadow-lg hover:scale-[1.02] transition mt-2">
                    Đăng ký
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-400">
                Đã có tài khoản? <a href="login.php" class="text-white font-bold hover:underline">Đăng nhập</a>
            </div>
        </div>
    </div>
</body>
</html> 