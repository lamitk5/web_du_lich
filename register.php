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
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Đăng ký - <?php echo defined('SITE_NAME') ? SITE_NAME : 'Website'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#0da6f2",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="font-display bg-gray-50">
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="flex justify-center">
                <div class="size-16 text-primary">
                    <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078L44 7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094L4 42.4379Z" fill="currentColor"></path>
                    </svg>
                </div>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Tạo tài khoản mới
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Đã có tài khoản? 
                <a href="login.php" class="font-medium text-primary hover:text-primary/80">
                    Đăng nhập ngay
                </a>
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <?php if ($error): ?>
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <span class="material-symbols-outlined text-red-500">error</span>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-5">
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Họ và tên <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">person</span>
                        <input 
                            id="full_name" 
                            name="full_name" 
                            type="text" 
                            required 
                            value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                            class="appearance-none rounded-lg relative block w-full pl-10 px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Nguyễn Văn A"
                        />
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">email</span>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                            class="appearance-none rounded-lg relative block w-full pl-10 px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="your.email@example.com"
                        />
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Số điện thoại
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">phone</span>
                        <input 
                            id="phone" 
                            name="phone" 
                            type="tel" 
                            value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                            class="appearance-none rounded-lg relative block w-full pl-10 px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="0987654321"
                        />
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Mật khẩu <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            minlength="6"
                            class="appearance-none rounded-lg relative block w-full pl-10 px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="••••••••"
                        />
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Tối thiểu 6 ký tự</p>
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Xác nhận mật khẩu <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                        <input 
                            id="confirm_password" 
                            name="confirm_password" 
                            type="password" 
                            required 
                            minlength="6"
                            class="appearance-none rounded-lg relative block w-full pl-10 px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="••••••••"
                        />
                    </div>
                </div>

                <div class="flex items-start">
                    <input 
                        id="agree" 
                        name="agree" 
                        type="checkbox" 
                        required
                        class="h-4 w-4 mt-1 text-primary focus:ring-primary border-gray-300 rounded"
                    />
                    <label for="agree" class="ml-2 block text-sm text-gray-900">
                        Tôi đồng ý với 
                        <a href="#" class="text-primary hover:text-primary/80">Điều khoản sử dụng</a> 
                        và 
                        <a href="#" class="text-primary hover:text-primary/80">Chính sách bảo mật</a>
                    </label>
                </div>

                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors"
                    >
                        <span class="material-symbols-outlined mr-2">person_add</span>
                        Đăng ký
                    </button>
                </div>
            </form>
        </div>
</div>
</body>
</html>