<?php
/**
 * components/header.php
 * Header component cho tất cả trang admin
 */

$currentUser = getCurrentUser();
$pageTitle = $pageTitle ?? 'Dashboard';
?>

<!-- Header -->
<header class="sticky top-0 z-30 flex items-center justify-between border-b border-gray-200 bg-white/95 px-6 py-3 backdrop-blur-sm dark:border-gray-700 dark:bg-gray-900/95">
    <div class="flex items-center gap-4">
        <!-- Mobile menu toggle -->
        <button 
            id="sidebarToggle"
            class="hidden lg:hidden size-9 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            title="Toggle sidebar"
        >
            <span class="material-symbols-outlined">menu</span>
        </button>

        <!-- Page title -->
        <div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($pageTitle); ?></h2>
            <?php if (isset($pageDescription)): ?>
            <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo htmlspecialchars($pageDescription); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right side actions -->
    <div class="flex items-center gap-3">
        <!-- Notifications -->
        <div class="relative group">
            <button 
                class="relative size-9 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                id="notificationBtn"
            >
                <span class="material-symbols-outlined text-gray-600 dark:text-gray-300">notifications</span>
                <span class="absolute top-1 right-1 size-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- Notification dropdown -->
            <div 
                id="notificationDropdown"
                class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200"
            >
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="font-bold text-sm">Thông báo</h3>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    <a href="#" class="flex items-start gap-3 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border-b border-gray-100 dark:border-gray-700">
                        <div class="size-2 rounded-full bg-primary mt-2 flex-shrink-0"></div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Đơn hàng mới</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Có 5 đơn hàng chờ xác nhận</p>
                            <p class="text-xs text-gray-400 mt-1">2 phút trước</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-start gap-3 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border-b border-gray-100 dark:border-gray-700">
                        <div class="size-2 rounded-full bg-yellow-500 mt-2 flex-shrink-0"></div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Cảnh báo hệ thống</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">API máy bay chậm hơn 3 giây</p>
                            <p class="text-xs text-gray-400 mt-1">30 phút trước</p>
                        </div>
                    </a>
                </div>
                <div class="p-3 border-t border-gray-200 dark:border-gray-700 text-center">
                    <a href="#" class="text-xs font-medium text-primary hover:underline">Xem tất cả thông báo</a>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="w-px h-6 bg-gray-200 dark:bg-gray-700"></div>

        <!-- Theme toggle -->
        <button 
            id="themeToggle"
            class="size-9 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            title="Toggle dark mode"
        >
            <span class="material-symbols-outlined text-gray-600 dark:text-gray-300">dark_mode</span>
        </button>

        <!-- User menu -->
        <div class="relative group">
            <button 
                class="flex items-center gap-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 px-2 py-1 transition-colors"
            >
                <div 
                    class="size-8 rounded-full bg-cover bg-center" 
                    style="background-image: url('https://ui-avatars.com/api/?name=<?php echo urlencode($currentUser['full_name']); ?>&background=0da6f2&color=fff');"
                ></div>
                <span class="hidden sm:inline text-sm font-medium text-gray-700 dark:text-gray-300 max-w-[100px] truncate">
                    <?php echo htmlspecialchars(explode(' ', $currentUser['full_name'])[0]); ?>
                </span>
                <span class="material-symbols-outlined text-gray-600 dark:text-gray-400 text-[20px]">expand_more</span>
            </button>

            <!-- User dropdown menu -->
            <div class="absolute right-0 mt-0 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($currentUser['full_name']); ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?php echo htmlspecialchars($currentUser['email']); ?></p>
                </div>
                <div class="py-2">
                    <a href="profile.php" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                        <span>Hồ sơ</span>
                    </a>
                    <a href="settings.php" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span class="material-symbols-outlined text-[20px]">settings</span>
                        <span>Cài đặt</span>
                    </a>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 py-2">
                    <a href="\web_du_lich/logout.php" class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                        <span>Đăng xuất</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Dark mode toggle
    const themeToggle = document.getElementById('themeToggle');
    const htmlElement = document.documentElement;
    const prefersDark = localStorage.getItem('theme') === 'dark' || 
                        (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);

    if (prefersDark) {
        htmlElement.classList.add('dark');
    }

    themeToggle.addEventListener('click', function() {
        htmlElement.classList.toggle('dark');
        localStorage.setItem('theme', htmlElement.classList.contains('dark') ? 'dark' : 'light');
    });

    // Notification button
    const notificationBtn = document.getElementById('notificationBtn');
    if (notificationBtn) {
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('opacity-100');
            dropdown.classList.toggle('invisible');
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown) {
            dropdown.classList.add('invisible');
            dropdown.classList.remove('opacity-100');
        }
    });
</script>