<?php
/**
 * users/trang_chu.php
 * Trang chủ - Admin và User đều truy cập được
 * Admin thấy thêm menu "Quản lý"
 */

require_once '../config/config.php';

// Bắt buộc login để truy cập trang này
requireLogin();

// Lấy thông tin user hiện tại
$currentUser = getCurrentUser();
$isLoggedIn = Auth::isLoggedIn();
$isAdmin = Auth::isAdmin();

// Lấy thông tin bookings sắp tới của user
$upcomingBookings = [];
if ($isLoggedIn) {
    $upcomingBookings = db()->select("
        SELECT b.*, bd.service_type, bd.check_in
        FROM bookings b
        LEFT JOIN booking_details bd ON b.id = bd.booking_id
        WHERE b.user_id = ? 
        AND b.status IN ('confirmed', 'pending')
        AND (bd.check_in IS NULL OR bd.check_in >= CURDATE())
        ORDER BY b.created_at DESC
        LIMIT 3
    ", [$currentUser['id']]);
}

// Lấy một số chuyến bay nổi bật
$featuredFlights = db()->select("
    SELECT f.*, a.name as airline_name
    FROM flights f
    INNER JOIN airlines a ON f.airline_id = a.id
    WHERE f.status = 'scheduled' 
    AND f.departure_time > NOW()
    AND f.available_seats > 0
    ORDER BY f.price ASC
    LIMIT 4
");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Trang chủ - <?php echo SITE_NAME; ?></title>
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
<body class="font-display bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-200">
<div class="relative flex min-h-screen w-full flex-col">
    <!-- Header -->
    <header class="sticky top-0 z-50 w-full bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700">
        <div class="container mx-auto px-4">
            <div class="flex h-20 items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <a class="flex items-center gap-3 text-[#0d171c] dark:text-white hover:opacity-80 transition-opacity" href="trang_chu.php">
                        <div class="size-8 text-primary">
                            <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path d="M42.4379 44C42.4379 44 36.0744 33.9038 41.1692 24C46.8624 12.9336 42.2078 4 42.2078 4L7.01134 4C7.01134 4 11.6577 12.932 5.96912 23.9969C0.876273 33.9029 7.27094 44 7.27094 44L42.4379 44Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold leading-tight tracking-tight"><?php echo SITE_NAME; ?></h2>
                    </a>
                </div>

                <!-- Navigation Menu -->
                <nav class="hidden lg:flex items-center gap-8">
                    <a class="text-[#0d171c] dark:text-white text-sm font-medium hover:text-primary transition-colors" href="trang_chu.php">Trang chủ</a>
                    <a class="text-slate-600 dark:text-slate-400 text-sm font-medium hover:text-primary transition-colors" href="tim_kiem_chuyenbay.php">Vé máy bay</a>
                    <a class="text-slate-600 dark:text-slate-400 text-sm font-medium hover:text-primary transition-colors" href="tim_kiem_khachsan.php">Khách sạn</a>
                    <a class="text-slate-600 dark:text-slate-400 text-sm font-medium hover:text-primary transition-colors" href="tim_kiem_xe.php">Thuê xe</a>
                    <a class="text-slate-600 dark:text-slate-400 text-sm font-medium hover:text-primary transition-colors" href="thongtin.php">Đặt chỗ của tôi</a>
                    
                    <!-- Admin Menu - Only show for admins -->
                    <?php if ($isAdmin): ?>
                    <div class="relative group">
                        <button class="flex items-center gap-1 text-slate-600 dark:text-slate-400 text-sm font-medium hover:text-primary transition-colors">
                            <span>Quản lý</span>
                            <span class="material-symbols-outlined text-base">expand_more</span>
                        </button>
                        <!-- Dropdown Menu for Admin -->
                        <div class="absolute top-full left-0 mt-2 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 py-2 z-50">
                            <a href="../admin/dashboard.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <span class="material-symbols-outlined text-base">dashboard</span>
                                <span>Dashboard</span>
                            </a>
                            <a href="../admin/qly_booking.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <span class="material-symbols-outlined text-base">confirmation_number</span>
                                <span>Quản lý đặt chỗ</span>
                            </a>
                            <a href="../admin/qly_chuyenbay.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <span class="material-symbols-outlined text-base">flight</span>
                                <span>Quản lý chuyến bay</span>
                            </a>
                            <a href="../admin/qly_khachsan.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <span class="material-symbols-outlined text-base">hotel</span>
                                <span>Quản lý khách sạn</span>
                            </a>
                            <a href="../admin/qly_xe.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <span class="material-symbols-outlined text-base">directions_car</span>
                                <span>Quản lý xe</span>
                            </a>
                            <a href="../admin/qly_kh.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <span class="material-symbols-outlined text-base">group</span>
                                <span>Quản lý người dùng</span>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </nav>

                <!-- Right Side: Theme Toggle & User Menu -->
                <div class="flex items-center gap-3">
                    <!-- Theme Toggle Button -->
                    <button 
                        id="themeToggle"
                        class="size-10 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                        title="Toggle dark mode"
                    >
                        <span class="material-symbols-outlined text-gray-600 dark:text-gray-300">dark_mode</span>
                    </button>

                    <!-- User Menu -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <div class="size-9 rounded-full bg-cover bg-center" style='background-image: url("https://ui-avatars.com/api/?name=<?php echo urlencode($currentUser['full_name']); ?>&background=0da6f2&color=fff");'></div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-semibold"><?php echo htmlspecialchars(substr($currentUser['full_name'], 0, 20)); ?></p>
                                <p class="text-xs text-gray-500"><?php echo $isAdmin ? 'Admin' : 'User'; ?></p>
                            </div>
                            <span class="material-symbols-outlined text-base">expand_more</span>
                        </button>
                        
                        <!-- User Dropdown Menu -->
                        <div class="absolute top-full right-0 mt-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 py-2 z-50">
                            <a href="thongtin.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <span class="material-symbols-outlined text-base">person</span>
                                <span>Thông tin cá nhân</span>
                            </a>
                            <a href="thongtin.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <span class="material-symbols-outlined text-base">confirmation_number</span>
                                <span>Đặt chỗ của tôi</span>
                            </a>
                            <hr class="my-2 border-gray-200 dark:border-gray-700">
                            <a href="../logout.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <span class="material-symbols-outlined text-base">logout</span>
                                <span>Đăng xuất</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        <!-- Hero Section -->
        <section class="relative py-20 lg:py-0 lg:h-[600px] flex items-center">
            <div class="absolute inset-0 bg-cover bg-center" style='background-image: linear-gradient(rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.5) 100%), url("https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1920&h=600&fit=crop");'></div>
            <div class="relative container mx-auto px-4 z-10 w-full">
                <div class="max-w-4xl mx-auto text-center text-white">
                    <h1 class="text-4xl font-black leading-tight tracking-tight md:text-6xl">
                        Khám phá thế giới, tìm kiếm chuyến bay của bạn
                    </h1>
                    <p class="mt-4 text-base font-normal leading-normal md:text-lg max-w-2xl mx-auto">
                        Tìm ưu đãi tốt nhất cho vé máy bay, khách sạn và thuê xe đến hàng ngàn điểm đến trên toàn thế giới.
                    </p>
                </div>

                <!-- Search Form -->
                <div class="mt-12 max-w-6xl mx-auto bg-white dark:bg-slate-800/95 rounded-xl shadow-2xl">
                    <div class="p-4 sm:p-6">
                        <form action="tim_kiem_chuyenbay.php" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Điểm đi</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">flight_takeoff</span>
                                    <input name="from" value="SGN" required class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary dark:text-white" placeholder="Từ đâu?"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Điểm đến</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">flight_land</span>
                                    <input name="to" value="HAN" required class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary dark:text-white" placeholder="Đến đâu?"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Ngày đi</label>
                                <input name="date" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" type="date" required class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 text-sm focus:ring-2 focus:ring-primary dark:text-white"/>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Hành khách</label>
                                <input name="passengers" value="1" type="number" min="1" max="10" required class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 text-sm focus:ring-2 focus:ring-primary dark:text-white"/>
                            </div>
                            <button type="submit" class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-5 bg-amber-500 text-[#0d171c] text-sm font-bold hover:bg-amber-400 transition-colors">
                                <span class="material-symbols-outlined mr-2">search</span>
                                <span>Tìm</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Upcoming Bookings Section -->
        <?php if (!empty($upcomingBookings)): ?>
        <section class="py-16 sm:py-24 bg-white dark:bg-slate-800">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12">Đặt chỗ sắp tới của bạn</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                    <?php foreach ($upcomingBookings as $booking): ?>
                    <div class="bg-background-light dark:bg-slate-900 rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="material-symbols-outlined text-primary text-2xl">
                                <?php echo $booking['service_type'] === 'flight' ? 'flight' : ($booking['service_type'] === 'hotel' ? 'hotel' : 'directions_car'); ?>
                            </span>
                            <div>
                                <p class="font-bold text-sm"><?php echo htmlspecialchars($booking['booking_code']); ?></p>
                                <p class="text-xs text-gray-500"><?php echo ucfirst($booking['service_type']); ?></p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-primary mb-2"><?php echo formatCurrency($booking['total_amount']); ?></p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php echo $booking['status'] === 'confirmed' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300'; ?>">
                            <?php echo $booking['status'] === 'confirmed' ? 'Đã xác nhận' : 'Chờ thanh toán'; ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-8">
                    <a href="thongtin.php" class="inline-flex items-center gap-2 text-primary hover:underline font-semibold">
                        <span>Xem tất cả đặt chỗ</span>
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Featured Flights Section -->
        <section class="py-16 sm:py-24">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center mb-12">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight text-[#0d171c] dark:text-white">Chuyến bay giá tốt</h2>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Đặt ngay để nhận ưu đãi tốt nhất</p>
                    </div>
                    <a href="tim_kiem_chuyenbay.php" class="hidden sm:inline-flex items-center gap-2 text-primary hover:underline font-semibold">
                        <span>Xem tất cả</span>
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($featuredFlights as $flight): ?>
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="material-symbols-outlined text-primary">flight</span>
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($flight['airline_name']); ?></p>
                            </div>
                            <div class="mb-4">
                                <p class="text-2xl font-bold"><?php echo $flight['departure_airport']; ?> → <?php echo $flight['arrival_airport']; ?></p>
                                <p class="text-sm text-gray-500"><?php echo formatDateTime($flight['departure_time'], 'd/m/Y'); ?></p>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="text-xs text-gray-500">Từ</p>
                                    <p class="text-2xl font-bold text-primary"><?php echo formatCurrency($flight['price']); ?></p>
                                </div>
                                <a href="tim_kiem_chuyenbay.php?from=<?php echo $flight['departure_airport']; ?>&to=<?php echo $flight['arrival_airport']; ?>&date=<?php echo date('Y-m-d', strtotime($flight['departure_time'])); ?>" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90 transition-colors">
                                    Đặt ngay
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="bg-white dark:bg-slate-800 py-16 sm:py-24">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold tracking-tight">Dịch vụ của chúng tôi</h2>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Mọi thứ bạn cần cho một chuyến đi hoàn hảo</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <a href="tim_kiem_chuyenbay.php" class="p-6 bg-background-light dark:bg-slate-900 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center justify-center size-12 rounded-full bg-primary/10 text-primary mb-4">
                            <span class="material-symbols-outlined" style="font-size: 28px;">flight</span>
                        </div>
                        <h3 class="text-lg font-bold">Vé máy bay</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Giá vé cạnh tranh đến mọi điểm đến</p>
                    </a>
                    <a href="tim_kiem_khachsan.php" class="p-6 bg-background-light dark:bg-slate-900 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center justify-center size-12 rounded-full bg-primary/10 text-primary mb-4">
                            <span class="material-symbols-outlined" style="font-size: 28px;">hotel</span>
                        </div>
                        <h3 class="text-lg font-bold">Đặt khách sạn</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Hàng ngàn khách sạn với giá tốt nhất</p>
                    </a>
                    <a href="tim_kiem_xe.php" class="p-6 bg-background-light dark:bg-slate-900 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center justify-center size-12 rounded-full bg-primary/10 text-primary mb-4">
                            <span class="material-symbols-outlined" style="font-size: 28px;">directions_car</span>
                        </div>
                        <h3 class="text-lg font-bold">Thuê xe</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Lựa chọn thuê xe linh hoạt và tiện lợi</p>
                    </a>
                    <a href="hotro.php" class="p-6 bg-background-light dark:bg-slate-900 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center justify-center size-12 rounded-full bg-primary/10 text-primary mb-4">
                            <span class="material-symbols-outlined" style="font-size: 28px;">support_agent</span>
                        </div>
                        <h3 class="text-lg font-bold">Hỗ trợ 24/7</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Đội ngũ hỗ trợ luôn sẵn sàng giúp bạn</p>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 mt-16">
        <div class="container mx-auto px-4 py-16">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
                <div>
                    <h4 class="font-bold text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">info</span>
                        Về chúng tôi
                    </h4>
                    <ul class="space-y-2">
                        <li><a class="hover:text-white transition-colors" href="#">Giới thiệu</a></li>
                        <li><a class="hover:text-white transition-colors" href="#">Liên hệ chúng tôi</a></li>
                        <li><a class="hover:text-white transition-colors" href="#">Tin tức</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">help</span>
                        Hỗ trợ
                    </h4>
                    <ul class="space-y-2">
                        <li><a class="hover:text-white transition-colors" href="hotro.php">Câu hỏi thường gặp</a></li>
                        <li><a class="hover:text-white transition-colors" href="#">Trung tâm hỗ trợ</a></li>
                        <li><a class="hover:text-white transition-colors" href="#">Liên hệ hỗ trợ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        Dịch vụ
                    </h4>
                    <ul class="space-y-2">
                        <li><a class="hover:text-white transition-colors" href="tim_kiem_chuyenbay.php">Vé máy bay</a></li>
                        <li><a class="hover:text-white transition-colors" href="tim_kiem_khachsan.php">Khách sạn</a></li>
                        <li><a class="hover:text-white transition-colors" href="tim_kiem_xe.php">Thuê xe</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">gavel</span>
                        Pháp lý
                    </h4>
                    <ul class="space-y-2">
                        <li><a class="hover:text-white transition-colors" href="#">Điều khoản sử dụng</a></li>
                        <li><a class="hover:text-white transition-colors" href="#">Chính sách bảo mật</a></li>
                        <li><a class="hover:text-white transition-colors" href="#">Chính sách cookie</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-700 pt-8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-slate-400 text-sm">© 2024 <?php echo SITE_NAME; ?>. Tất cả quyền được bảo vệ.</p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="text-slate-400 hover:text-white transition-colors" title="Twitter">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2s9 5 20 5a9.5 9.5 0 00-9-5.5c4.75 2.25 7-7 7-7"></path></svg>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors" title="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a6 6 0 00-6 6v3H7v4h2v8h4v-8h3l1-4h-4V8a2 2 0 012-2h3z"></path></svg>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors" title="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Dark mode toggle
        const themeToggle = document.getElementById('themeToggle');
        const htmlElement = document.documentElement;
        const prefersDark = localStorage.getItem('theme') === 'dark' || 
                            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);

        if (prefersDark) {
            htmlElement.classList.add('dark');
        }

        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                htmlElement.classList.toggle('dark');
                localStorage.setItem('theme', htmlElement.classList.contains('dark') ? 'dark' : 'light');
            });
        }
    </script>
</body>
</html>