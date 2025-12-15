<?php
/**
 * login.php
 * Trang đăng nhập chung
 * Admin & User đều dùng trang này
 * Sau login, tự động redirect đến trang thích hợp
 */

require_once 'config/config.php';

// Nếu đã đăng nhập, redirect tới trang thích hợp
if (Auth::isLoggedIn()) {
    if (Auth::isAdmin()) {
        redirect('admin/dashboard.php');
    } else {
        redirect('user/trang_chu.php');
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra IP bị khóa
    if (isIPLocked()) {
        $error = 'Tài khoản của bạn bị khóa tạm thời. Vui lòng thử lại sau 15 phút.';
    } else {
        $email = cleanInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Kiểm tra input
        if (empty($email) || empty($password)) {
            $error = 'Vui lòng nhập email và mật khẩu';
        } elseif (!isValidEmail($email)) {
            $error = 'Email không hợp lệ';
        } else {
            // Xác minh user
            $user = Auth::verifyEmail($email, $password);
            
            if ($user) {
                // Login thành công
                Auth::login($user);
                recordLoginAttempt(true);
                logSuccess("User {$user['email']} logged in from " . getUserIP());
                
                // Redirect tới trang thích hợp
                if ($user['role'] === 'admin') {
                    redirect('user/trang_chu.php');
                } else {
                    redirect('user/trang_chu.php');
                }
            } else {
                // Login thất bại
                recordLoginAttempt(false);
                $error = 'Email hoặc mật khẩu không chính xác';
                logError("Failed login attempt for email: {$email}", ['ip' => getUserIP()]);
            }
        }
    }
}

$pageTitle = 'Đăng Nhập';
?>
<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo $pageTitle; ?> - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { 
                        "primary": "#0da6f2", 
                        "background-light": "#f5f7f8", 
                        "background-dark": "#101c22" 
                    },
                    fontFamily: { "display": ["Plus Jakarta Sans", "sans-serif"] }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="font-display flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center size-16 bg-white rounded-2xl shadow-lg mb-4">
                <span class="material-symbols-outlined text-4xl text-primary">travel_explore</span>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2"><?php echo SITE_NAME; ?></h1>
            <p class="text-white/80">Travel & Booking Management System</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8 md:p-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-1">Đăng Nhập</h2>
                <p class="text-gray-500 text-sm mb-6">Nhập thông tin tài khoản của bạn</p>

                <!-- Error Message -->
                <?php if (!empty($error)): ?>
                <div class="mb-6 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    <span class="material-symbols-outlined flex-shrink-0 mt-0.5">error</span>
                    <div>
                        <p class="font-medium text-sm"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">mail</span>
                            <input 
                                type="email" 
                                name="email" 
                                placeholder="admin@flyhigh.com"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2.5 text-gray-900 placeholder:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                required
                            />
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                            <a href="forgot_password.php" class="text-xs text-primary hover:underline">Quên mật khẩu?</a>
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                            <input 
                                type="password" 
                                id="password"
                                name="password" 
                                placeholder="Nhập mật khẩu"
                                class="w-full rounded-lg border border-gray-300 pl-10 pr-10 py-2.5 text-gray-900 placeholder:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                required
                            />
                            <button 
                                type="button"
                                onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                <span class="material-symbols-outlined" id="toggleIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember_me"
                            name="remember_me" 
                            class="rounded border-gray-300 text-primary focus:ring-primary"
                        />
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">
                            Nhớ tôi trong 30 ngày
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-primary to-blue-600 text-white font-bold py-2.5 rounded-lg hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 mt-6"
                    >
                        <span class="material-symbols-outlined">login</span>
                        <span>Đăng Nhập</span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Hoặc</span>
                    </div>
                </div>

                <!-- Register Link -->
                <p class="text-center text-sm text-gray-600">
                    Chưa có tài khoản? 
                    <a href="register.php" class="text-primary font-bold hover:underline">
                        Đăng ký ngay
                    </a>
                </p>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 text-center border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    © 2024 <?php echo SITE_NAME; ?>. Tất cả quyền được bảo vệ.
                </p>
            </div>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-6 rounded-lg bg-white/10 border border-white/20 p-4 backdrop-blur-sm">
            <p class="text-white/80 text-xs font-medium mb-2">📝 Tài khoản demo:</p>
            <ul class="text-white/70 text-xs space-y-1">
                <li><strong>Admin:</strong> admin@travel.com / password</li>
                <li><strong>User:</strong> minhanh.le@email.com / password</li>
            </ul>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    </script>
</body>
</html>