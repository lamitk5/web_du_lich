<?php
/**
 * logout.php
 * Xử lý đăng xuất người dùng
 */

require_once 'config/config.php';

// Ghi log
$user = Auth::getCurrentUser();
if ($user) {
    logSuccess("User {$user['email']} logged out");
}

// Logout
Auth::logout();

// Flash message
setFlashMessage('success', 'Bạn đã đăng xuất thành công');

// Redirect tới login
redirect('login.php');
?>