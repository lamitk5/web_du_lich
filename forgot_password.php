<?php
/**
 * forgot_password.php
 * Trang quên mật khẩu
 */

require_once 'config/config.php';

// Nếu đã đăng nhập
if (Auth::isLoggedIn()) {
    redirect(Auth::getRedirectUrl());
}

$error = '';
$success = '';
$step = isset($_GET['step']) ? $_GET['step'] : 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step == 1) {
        // Bước 1: Nhập email
        $email = cleanInput($_POST['email'] ?? '');
        
        if (empty($email)) {
            $error = 'Vui lòng nhập email';
        } elseif (!isValidEmail($email)) {
            $error = 'Email không hợp lệ';
        } else {
            // Kiểm tra email có tồn tại
            $user = db()->selectOne("SELECT * FROM users WHERE email = ?", [$email]);
            
            if (!$user) {
                $error = 'Email không tồn tại trong hệ thống';
            } else {
                // Tạo reset token
                $token = Auth::createResetToken($user['id']);
                
                // TODO: Gửi email với link reset
                // sendResetEmail($email, $token);
                
                $success = 'Nếu email tồn tại trong hệ thống, bạn sẽ nhận được email hướng dẫn reset mật khẩu';
                logSuccess("Password reset requested for email: {$email}");
                
                // Trong thực tế, bạn cần gửi email. Ở đây chỉ hiển thị thông báo
            }
        }
    } elseif ($step == 2) {
        // Bước 2: Reset mật khẩu
        $token = cleanInput($_POST['token'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($token)) {
            $error = 'Token không hợp lệ';
        } elseif (empty($newPassword) || empty($confirmPassword)) {
            $error = 'Vui lòng nhập mật khẩu';
        } elseif (strlen($newPassword) < PASSWORD_MIN_LENGTH) {
            $error = "Mật khẩu phải có ít nhất " . PASSWORD_MIN_LENGTH . " ký tự";
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Mật khẩu xác nhận không khớp';
        } else {
            // Xác minh token
            $user = Auth::verifyResetToken($token);
            
            if (!$user) {
                $error = 'Token không hợp lệ hoặc đã hết hạn';
            } else {
                // Reset mật khẩu
                if (Auth::resetPassword($user['id'], $newPassword)) {
                    $success = 'Mật khẩu đã được reset thành công. <a href="login.php" class="font-bold underline">Đăng nhập ngay</a>';
                    logSuccess("Password reset successful for user: {$user['email']}");
                } else {
                    $error = 'Lỗi khi reset mật khẩu. Vui lòng thử lại.';
                }
            }
        }
    }
}

$pageTitle = 'Quên Mật Khẩu';
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
        <!-- Back Button -->
        <a href="login.php" class="inline-flex items-center gap-2 text-white hover:text-white/80 mb-6 transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
            <span class="text-sm font-medium">Quay lại đăng nhập</span>
        </a>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8 md:p-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Quên Mật Khẩu?</h2>
                <p class="text-gray-500 text-sm mb-6">
                    <?php echo $step == 1 ? 'Nhập email của bạn để nhận hướng dẫn reset mật khẩu' : 'Nhập mật khẩu mới của bạn'; ?>
                </p>

                <!-- Error Message -->
                <?php if (!empty($error)): ?>
                <div class="mb-6 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    <span class="material-symbols-outlined flex-shrink-0 mt-0.5">error</span>
                    <div>
                        <p class="font-medium text-sm"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Success Message -->
                <?php if (!empty($success)): ?>
                <div class="mb-6 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                    <span class="material-symbols-outlined flex-shrink-0 mt-0.5">check_circle</span>
                    <div>
                        <p class="font-medium text-sm"><?php echo $success; ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Form -->
                <form method="POST" class="space-y-5">
                    <?php if ($step == 1): ?>
                        <!-- Email Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">mail</span>
                                <input 
                                    type="email" 
                                    name="email" 
                                    placeholder="admin@flyhigh.com"
                                    class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2.5 text-gray-900 placeholder:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                    required
                                />
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Token Input (Hidden) -->
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>" />
                        
                        <!-- New Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu mới</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                                <input 
                                    type="password" 
                                    name="new_password" 
                                    placeholder="Nhập mật khẩu mới"
                                    class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2.5 text-gray-900 placeholder:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Xác nhận mật khẩu</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                                <input 
                                    type="password" 
                                    name="confirm_password" 
                                    placeholder="Xác nhận mật khẩu"
                                    class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2.5 text-gray-900 placeholder:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                    required
                                />
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-primary to-blue-600 text-white font-bold py-2.5 rounded-lg hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 mt-6"
                    >
                        <span class="material-symbols-outlined">send</span>
                        <span><?php echo $step == 1 ? 'Gửi' : 'Cập nhật mật khẩu'; ?></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>