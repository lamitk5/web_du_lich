<?php
// Đảm bảo logic kiểm tra đăng nhập/admin chạy trước khi hiển thị header
if (!isset($currentUser) && class_exists('Auth')) {
    $currentUser = getCurrentUser();
    $isAdmin = Auth::isAdmin();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Travel Booking'; ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0da6f2",
                        "background-light": "#f5f7f8",
                        "background-dark": "#101c22",
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
<body class="font-display bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-200 flex flex-col min-h-screen">

<div class="relative flex min-h-screen w-full flex-col">
    <header class="sticky top-0 z-50 w-full bg-white/90 dark:bg-[#101c22]/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <div class="flex h-20 items-center justify-between">
                <div class="flex items-center gap-3">
                    <a class="flex items-center gap-3 text-[#0d171c] dark:text-white hover:opacity-80 transition-opacity" href="trang_chu.php">
                        <div class="size-8 text-primary">
                            <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path d="M42.4379 44C42.4379 44 36.0744 33.9038 41.1692 24C46.8624 12.9336 42.2078 4 42.2078 4L7.01134 4C7.01134 4 11.6577 12.932 5.96912 23.9969C0.876273 33.9029 7.27094 44 7.27094 44L42.4379 44Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold leading-tight tracking-tight"><?php echo defined('SITE_NAME') ? SITE_NAME : 'TravelApp'; ?></h2>
                    </a>
                </div>

                <nav class="hidden lg:flex items-center gap-8">
                    <a class="text-slate-600 dark:text-slate-400 text-sm font-medium hover:text-primary transition-colors" href="trang_chu.php">Trang chủ</a>
                    <a class="text-slate-600 dark:text-slate-400 text-sm font-medium hover:text-primary transition-colors" href="tim_kiem_chuyenbay.php">Vé máy bay</a>
                    <a class="text-slate-600 dark:text-slate-400 text-sm font-medium hover:text-primary transition-colors" href="homestay.php">Homestays</a>
                    <a class="text-slate-600 dark:text-slate-400 text-sm font-medium hover:text-primary transition-colors" href="tim_kiem_xe.php">Thuê xe</a>
                    
                    <?php if (isset($isAdmin) && $isAdmin): ?>
                    <div class="relative group">
                        <button class="flex items-center gap-1 text-slate-600 dark:text-slate-400 text-sm font-medium hover:text-primary transition-colors">
                            <span>Quản lý</span>
                            <span class="material-symbols-outlined text-base">expand_more</span>
                        </button>
                        <div class="absolute top-full left-0 mt-2 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 py-2 z-50">
                            <a href="../admin/dashboard.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <span class="material-symbols-outlined text-base">dashboard</span>Dashboard
                            </a>
                            </div>
                    </div>
                    <?php endif; ?>
                </nav>

                <div class="flex items-center gap-3">
                    <button id="themeToggle" class="size-10 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <span class="material-symbols-outlined text-gray-600 dark:text-gray-300">dark_mode</span>
                    </button>

                    <?php if (isset($currentUser)): ?>
                    <div class="relative group">
                        <button class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <div class="size-9 rounded-full bg-cover bg-center" style='background-image: url("https://ui-avatars.com/api/?name=<?php echo urlencode($currentUser['full_name']); ?>&background=0da6f2&color=fff");'></div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-semibold"><?php echo htmlspecialchars(substr($currentUser['full_name'], 0, 15)); ?></p>
                            </div>
                            <span class="material-symbols-outlined text-base">expand_more</span>
                        </button>
                        <div class="absolute top-full right-0 mt-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 py-2 z-50">
                            <a href="thongtin.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <span class="material-symbols-outlined text-base">person</span>Thông tin
                            </a>
                            <hr class="my-2 border-gray-200 dark:border-gray-700">
                            <a href="../logout.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <span class="material-symbols-outlined text-base">logout</span>Đăng xuất
                            </a>
                        </div>
                    </div>
                    <?php else: ?>
                        <a href="login.php" class="px-4 py-2 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90">Đăng nhập</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    
    <script>
        const themeToggle = document.getElementById('themeToggle');
        const htmlElement = document.documentElement;
        const prefersDark = localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);
        if (prefersDark) htmlElement.classList.add('dark');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                htmlElement.classList.toggle('dark');
                localStorage.setItem('theme', htmlElement.classList.contains('dark') ? 'dark' : 'light');
            });
        }
    </script>