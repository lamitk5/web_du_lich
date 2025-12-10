<?php
/**
 * Index - Trang chính
 * Luôn chuyển hướng về trang chủ
 */

require_once 'config/config.php';

// Luôn chuyển về trang chủ
// Admin và User đều vào cùng trang chủ
// Phân quyền hiển thị sẽ được xử lý ở trang chủ
redirect('user/trang_chu.php');