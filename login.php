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
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Đăng nhập - Website du lịch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center relative overflow-hidden">

    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=2000" class="w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
    </div>

    <div class="relative z-10 w-full max-w-md p-6">
        
        <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl shadow-2xl p-8 md:p-10">
            <div class="text-center mb-8">
                <a href="user/trang_chu.php" class="inline-flex items-center gap-2 text-[#13ecc8] hover:scale-105 transition transform">
                    <span class="material-symbols-outlined text-4xl">other_houses</span>
                    <span class="font-extrabold text-2xl text-white">Flyhigh</span>
                </a>
                <h2 class="text-2xl font-bold text-white mt-6">Chào mừng trở lại!</h2>
                <p class="text-gray-300 text-sm mt-1">Nhập thông tin để tiếp tục.</p>
            </div>

            <?php if($error): ?>
                <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-3 rounded-xl text-sm font-bold text-center mb-6 flex items-center justify-center gap-2 backdrop-blur-sm">
                    <span class="material-symbols-outlined text-base">error</span> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-300 uppercase ml-1">Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-3 text-gray-400">mail</span>
                        <input type="email" name="email" required placeholder="name@example.com"
                               class="w-full bg-black/20 border border-white/10 rounded-xl px-12 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#13ecc8] focus:bg-black/40 transition">
                    </div>
                </div>
                
                <div class="space-y-1">
                    <div class="flex justify-between ml-1">
                        <label class="text-xs font-bold text-gray-300 uppercase">Mật khẩu</label>
                        <a href="quen_mat_khau.php" class="text-xs text-[#13ecc8] hover:underline">Quên mật khẩu?</a>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-3 text-gray-400">lock</span>
                        <input type="password" name="password" id="passInput" required placeholder="••••••••"
                               class="w-full bg-black/20 border border-white/10 rounded-xl px-12 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#13ecc8] focus:bg-black/40 transition">
                        <button type="button" onclick="togglePass()" class="absolute right-4 top-3 text-gray-400 hover:text-white">
                            <span class="material-symbols-outlined text-lg" id="eyeIcon">visibility_off</span>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#13ecc8] hover:bg-[#0fb89c] text-gray-900 font-bold py-3.5 rounded-xl shadow-lg hover:shadow-[#13ecc8]/20 transition transform hover:-translate-y-1 mt-2">
                    Đăng nhập
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-gray-400">
                Chưa có tài khoản? <a href="register.php" class="text-white font-bold hover:underline">Đăng ký ngay</a>
            </div>
        </div>
    </div>

    <script>
        function togglePass() {
            var x = document.getElementById("passInput");
            var icon = document.getElementById("eyeIcon");
            if (x.type === "password") {
                x.type = "text";
                icon.innerText = "visibility";
            } else {
                x.type = "password";
                icon.innerText = "visibility_off";
            }
        }
    </script>
</body>
</html>