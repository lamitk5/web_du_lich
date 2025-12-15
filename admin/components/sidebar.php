<?php
// Lấy tên file hiện tại (ví dụ: qly_booking.php)
$current_page = basename($_SERVER['PHP_SELF']);

// Hàm helper để kiểm tra active
function is_active($page_name, $current_page) {
    return $page_name === $current_page;
}

// Class cho menu active (dựa theo CSS của bạn: bg-primary/10 text-primary)
$active_class = "bg-primary/10 text-primary";
// Class cho menu thường
$normal_class = "text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800";

// Lấy thông tin user (đảm bảo hàm này đã có trong config)
$user = getCurrentUser(); 
?>

<aside class="flex w-64 flex-col gap-y-6 border-r border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900 h-screen sticky top-0">
    <div class="flex items-center gap-3 px-2">
        <div class="bg-primary/10 text-primary rounded-lg p-2">
            <span class="material-symbols-outlined">travel_explore</span>
        </div>
        <h1 class="text-xl font-bold tracking-tight"><?php echo SITE_NAME; ?> Admin</h1>
    </div>

    <nav class="flex flex-1 flex-col gap-2">
        <a class="flex items-center gap-3 rounded-lg px-3 py-2 <?php echo is_active('dashboard.php', $current_page) ? $active_class : $normal_class; ?>" href="dashboard.php">
            <span class="material-symbols-outlined" style="<?php echo is_active('dashboard.php', $current_page) ? "font-variation-settings: 'FILL' 1;" : ""; ?>">dashboard</span>
            <p class="text-sm font-medium">Dashboard</p>
        </a>

        <a class="flex items-center gap-3 rounded-lg px-3 py-2 <?php echo is_active('qly_chuyenbay.php', $current_page) ? $active_class : $normal_class; ?>" href="qly_chuyenbay.php">
            <span class="material-symbols-outlined" style="<?php echo is_active('qly_chuyenbay.php', $current_page) ? "font-variation-settings: 'FILL' 1;" : ""; ?>">flight</span>
            <p class="text-sm font-medium">Vé máy bay</p>
        </a>

        <a class="flex items-center gap-3 rounded-lg px-3 py-2 <?php echo is_active('qly_khachsan.php', $current_page) ? $active_class : $normal_class; ?>" href="qly_khachsan.php">
            <span class="material-symbols-outlined" style="<?php echo is_active('qly_khachsan.php', $current_page) ? "font-variation-settings: 'FILL' 1;" : ""; ?>">hotel</span>
            <p class="text-sm font-medium">Khách sạn</p>
        </a>

        <a class="flex items-center gap-3 rounded-lg px-3 py-2 <?php echo is_active('qly_xe.php', $current_page) ? $active_class : $normal_class; ?>" href="qly_xe.php">
            <span class="material-symbols-outlined" style="<?php echo is_active('qly_xe.php', $current_page) ? "font-variation-settings: 'FILL' 1;" : ""; ?>">directions_car</span>
            <p class="text-sm font-medium">Quản lý xe</p>
        </a>

        <a class="flex items-center gap-3 rounded-lg px-3 py-2 <?php echo is_active('qly_booking.php', $current_page) ? $active_class : $normal_class; ?>" href="qly_booking.php">
            <span class="material-symbols-outlined" style="<?php echo is_active('qly_booking.php', $current_page) ? "font-variation-settings: 'FILL' 1;" : ""; ?>">confirmation_number</span>
            <p class="text-sm font-medium">Đặt chỗ</p>
        </a>

        <a class="flex items-center gap-3 rounded-lg px-3 py-2 <?php echo is_active('qly_kh.php', $current_page) ? $active_class : $normal_class; ?>" href="qly_kh.php">
            <span class="material-symbols-outlined" style="<?php echo is_active('qly_kh.php', $current_page) ? "font-variation-settings: 'FILL' 1;" : ""; ?>">group</span>
            <p class="text-sm font-medium">Người dùng</p>
        </a>
    </nav>

    <div class="flex items-center gap-3 border-t border-gray-200 dark:border-gray-700 pt-4">
        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" 
             style='background-image: url("https://ui-avatars.com/api/?name=<?php echo urlencode($user['full_name'] ?? 'Admin'); ?>&background=0da6f2&color=fff");'>
        </div>
        <div class="flex flex-col">
            <p class="text-sm font-medium"><?php echo htmlspecialchars($user['full_name'] ?? 'Administrator'); ?></p>
            <a href="../logout.php" class="text-xs text-red-500 hover:text-red-700 hover:underline">Đăng xuất</a>
        </div>
    </div>
</aside>