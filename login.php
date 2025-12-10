<?php
/**
 * Trang đăng nhập
 */

require_once 'config/config.php';

// Nếu đã đăng nhập thì chuyển hướng
if (isLoggedIn()) {
    redirect(isAdmin() ? 'admin/dashboard.php' : 'user/trang_chu.php');
}

$error = '';
$success = '';

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    // Validate
    if (empty($email) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ thông tin';
    } else {
        // Tìm user
        $user = db()->selectOne("SELECT * FROM users WHERE email = ?", [$email]);
        
        if ($user && verifyPassword($password, $user['password'])) {
            // Check status
            if ($user['status'] === 'blocked') {
                $error = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.';
            } else if ($user['status'] === 'inactive') {
                $error = 'Tài khoản của bạn chưa được kích hoạt.';
            } else {
                // Đăng nhập thành công
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                
                // Remember me
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token', $token, time() + (86400 * 30), '/');
                    // Lưu token vào database (cần tạo bảng remember_tokens)
                }
                
                // Admin và User đều được chuyển về trang chủ
                // Admin sẽ thấy menu "Quản lý" ở trang chủ
                redirect('user/trang_chu.php');
            }
        } else {
            $error = 'Email hoặc mật khẩu không chính xác';
        }
    }
}

// Lấy flash message nếu có
$flash = getFlashMessage();
if ($flash) {
    if ($flash['type'] === 'success') {
        $success = $flash['message'];
    } else {
        $error = $flash['message'];
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Đăng nhập - <?php echo SITE_NAME; ?></title>
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
        <!-- Logo -->
        <div class="text-center">
            <div class="flex justify-center">
                <div class="size-16 text-primary">
                    <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078L44 7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094L4 42.4379Z" fill="currentColor"></path>
                    </svg>
                </div>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Đăng nhập vào <?php echo SITE_NAME; ?>
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Hoặc 
                <a href="register.php" class="font-medium text-primary hover:text-primary/80">
                    tạo tài khoản mới
                </a>
            </p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <?php if ($error): ?>
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <span class="material-symbols-outlined text-red-500">error</span>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <span class="material-symbols-outlined text-green-500">check_circle</span>
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
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

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Mật khẩu
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            class="appearance-none rounded-lg relative block w-full pl-10 px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="••••••••"
                        />
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                        />
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="forgot-password.php" class="font-medium text-primary hover:text-primary/80">
                            Quên mật khẩu?
                        </a>
                    </div>
                </div>

                <!-- Submit -->
                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors"
                    >
                        <span class="material-symbols-outlined mr-2">login</span>
                        Đăng nhập
                    </button>
                </div>

                <!-- Demo accounts -->
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <p class="text-xs text-gray-500 mb-3">Tài khoản demo:</p>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-gray-50 p-2 rounded">
                            <p class="font-semibold text-gray-700">Admin:</p>
                            <p class="text-gray-600">admin@travel.com</p>
                            <p class="text-gray-600">password</p>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <p class="font-semibold text-gray-700">User:</p>
                            <p class="text-gray-600">minhanh.le@email.com</p>
                            <p class="text-gray-600">password</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Back to home -->
        <div class="text-center">
            <a href="user/trang_chu.php" class="text-sm text-gray-600 hover:text-primary flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Về trang chủ
            </a>
        </div>
    </div>
</div>
</body>
</html>