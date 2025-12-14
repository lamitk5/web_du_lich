<?php
/**
 * Trang Dashboard Admin
 * Hiển thị thống kê tổng quan hệ thống
 */

require_once '../config/config.php';
requireAdmin();

// Lấy thống kê tổng quan
$stats = [
    'total_users' => db()->count('users'),
    'total_bookings' => db()->count('bookings'),
    'total_flights' => db()->count('flights'),
    'total_hotels' => db()->count('hotels'),
    'total_cars' => db()->count('cars'),
];

// Doanh thu tháng này
$currentMonth = date('Y-m');
$revenue = db()->selectOne("
    SELECT 
        COALESCE(SUM(total_amount), 0) as total,
        COUNT(*) as count
    FROM bookings 
    WHERE DATE_FORMAT(created_at, '%Y-%m') = ? 
    AND status IN ('confirmed', 'completed')
", [$currentMonth]);

// Đặt chỗ theo trạng thái
$bookingStats = db()->select("
    SELECT status, COUNT(*) as count 
    FROM bookings 
    GROUP BY status
");

// Booking gần đây
$recentBookings = db()->select("
    SELECT b.*, u.full_name, u.email, bd.service_type
    FROM bookings b
    INNER JOIN users u ON b.user_id = u.id
    LEFT JOIN booking_details bd ON b.id = bd.booking_id
    ORDER BY b.created_at DESC
    LIMIT 10
");

// Chuyến bay sắp khởi hành
$upcomingFlights = db()->select("
    SELECT f.*, a.name as airline_name
    FROM flights f
    INNER JOIN airlines a ON f.airline_id = a.id
    WHERE f.departure_time > NOW()
    AND f.status = 'scheduled'
    ORDER BY f.departure_time ASC
    LIMIT 5
");

// Thống kê theo dịch vụ
$serviceStats = db()->select("
    SELECT 
        bd.service_type,
        COUNT(*) as count,
        SUM(b.total_amount) as revenue
    FROM booking_details bd
    INNER JOIN bookings b ON bd.booking_id = b.id
    WHERE b.status IN ('confirmed', 'completed')
    GROUP BY bd.service_type
");

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard - <?php echo SITE_NAME; ?> Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { "primary": "#0da6f2", "background-light": "#f5f7f8", "background-dark": "#101c22" },
                    fontFamily: { "display": ["Plus Jakarta Sans", "sans-serif"] }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .active-nav { background-color: rgba(13, 166, 242, 0.1); color: #0da6f2; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="flex min-h-screen w-full">
    <!-- Sidebar -->
    <aside class="flex w-64 flex-col gap-y-6 border-r border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
        <div class="flex items-center gap-3 px-2">
            <div class="bg-primary/10 text-primary rounded-lg p-2">
                <span class="material-symbols-outlined">travel_explore</span>
            </div>
            <h1 class="text-xl font-bold tracking-tight"><?php echo SITE_NAME; ?> Admin</h1>
        </div>
        <nav class="flex flex-1 flex-col gap-2">
            <a class="active-nav flex items-center gap-3 rounded-lg px-3 py-2" href="dashboard.php">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                <p class="text-sm font-medium">Dashboard</p>
            </a>
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="qly_chuyenbay.php">
                <span class="material-symbols-outlined">flight</span>
                <p class="text-sm font-medium">Vé máy bay</p>
            </a>
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="qly_khachsan.php">
                <span class="material-symbols-outlined">hotel</span>
                <p class="text-sm font-medium">Khách sạn</p>
            </a>
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="qly_xe.php">
                <span class="material-symbols-outlined">directions_car</span>
                <p class="text-sm font-medium">Quản lý xe</p>
            </a>
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="qly_booking.php">
                <span class="material-symbols-outlined">confirmation_number</span>
                <p class="text-sm font-medium">Đặt chỗ</p>
            </a>
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="qly_kh.php">
                <span class="material-symbols-outlined">group</span>
                <p class="text-sm font-medium">Người dùng</p>
            </a>
        </nav>
        <div class="flex items-center gap-3 border-t border-gray-200 dark:border-gray-700 pt-4">
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://ui-avatars.com/api/?name=<?php echo urlencode($currentUser['full_name']); ?>&background=0da6f2&color=fff");'></div>
            <div class="flex flex-col">
                <p class="text-sm font-medium"><?php echo htmlspecialchars($currentUser['full_name']); ?></p>
                <p class="text-xs text-gray-500">Quản trị viên</p>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
        <header class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white/80 px-6 py-3 backdrop-blur-sm dark:border-gray-700 dark:bg-gray-900/80">
            <div>
                <h2 class="text-lg font-bold">Dashboard</h2>
                <p class="text-xs text-gray-500">Chào mừng trở lại, <?php echo htmlspecialchars($currentUser['full_name']); ?>!</p>
            </div>
            <div class="flex gap-2">
                <button class="flex size-9 items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <span class="material-symbols-outlined text-gray-600 dark:text-gray-300">notifications</span>
                </button>
            </div>
        </header>

        <div class="p-6 md:p-10">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Doanh thu tháng -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-start justify-between mb-4">
                        <div class="bg-white/20 rounded-lg p-3">
                            <span class="material-symbols-outlined text-3xl">payments</span>
                        </div>
                        <span class="text-xs bg-white/20 rounded-full px-2 py-1">Tháng này</span>
                    </div>
                    <p class="text-sm opacity-90 mb-1">Doanh thu</p>
                    <p class="text-3xl font-bold"><?php echo formatCurrency($revenue['total']); ?></p>
                    <p class="text-xs opacity-75 mt-2"><?php echo $revenue['count']; ?> đơn hoàn thành</p>
                </div>

                <!-- Tổng đặt chỗ -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between mb-4">
                        <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-3">
                            <span class="material-symbols-outlined text-3xl text-green-600 dark:text-green-400">confirmation_number</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-1">Tổng đặt chỗ</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white"><?php echo number_format($stats['total_bookings']); ?></p>
                    <p class="text-xs text-gray-500 mt-2">Tất cả thời gian</p>
                </div>

                <!-- Người dùng -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between mb-4">
                        <div class="bg-purple-100 dark:bg-purple-900/30 rounded-lg p-3">
                            <span class="material-symbols-outlined text-3xl text-purple-600 dark:text-purple-400">group</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-1">Người dùng</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white"><?php echo number_format($stats['total_users']); ?></p>
                    <p class="text-xs text-gray-500 mt-2">Tài khoản đã đăng ký</p>
                </div>

                <!-- Chuyến bay -->
                <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between mb-4">
                        <div class="bg-amber-100 dark:bg-amber-900/30 rounded-lg p-3">
                            <span class="material-symbols-outlined text-3xl text-amber-600 dark:text-amber-400">flight</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mb-1">Chuyến bay</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white"><?php echo number_format($stats['total_flights']); ?></p>
                    <p class="text-xs text-gray-500 mt-2">Đang hoạt động</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Thống kê theo dịch vụ -->
                <div class="lg:col-span-1 bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold mb-4">Dịch vụ phổ biến</h3>
                    <div class="space-y-4">
                        <?php 
                        $serviceIcons = [
                            'flight' => ['icon' => 'flight', 'color' => 'blue', 'name' => 'Vé máy bay'],
                            'hotel' => ['icon' => 'hotel', 'color' => 'green', 'name' => 'Khách sạn'],
                            'car' => ['icon' => 'directions_car', 'color' => 'amber', 'name' => 'Thuê xe']
                        ];
                        
                        $totalService = array_sum(array_column($serviceStats, 'count'));
                        
                        foreach ($serviceStats as $service): 
                            $config = $serviceIcons[$service['service_type']] ?? ['icon' => 'help', 'color' => 'gray', 'name' => 'Khác'];
                            $percentage = $totalService > 0 ? round(($service['count'] / $totalService) * 100, 1) : 0;
                        ?>
                        <div class="flex items-center gap-4">
                            <div class="bg-<?php echo $config['color']; ?>-100 dark:bg-<?php echo $config['color']; ?>-900/30 rounded-lg p-2">
                                <span class="material-symbols-outlined text-<?php echo $config['color']; ?>-600 dark:text-<?php echo $config['color']; ?>-400"><?php echo $config['icon']; ?></span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium"><?php echo $config['name']; ?></p>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-<?php echo $config['color']; ?>-500 rounded-full h-2" style="width: <?php echo $percentage; ?>%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 font-medium"><?php echo $percentage; ?>%</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900 dark:text-white"><?php echo number_format($service['count']); ?></p>
                                <p class="text-xs text-gray-500"><?php echo formatCurrency($service['revenue']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Trạng thái đặt chỗ -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold mb-4">Trạng thái đặt chỗ</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <?php 
                        $statusConfig = [
                            'pending' => ['label' => 'Chờ thanh toán', 'color' => 'yellow', 'icon' => 'schedule'],
                            'confirmed' => ['label' => 'Đã xác nhận', 'color' => 'green', 'icon' => 'check_circle'],
                            'completed' => ['label' => 'Hoàn thành', 'color' => 'blue', 'icon' => 'task_alt'],
                            'cancelled' => ['label' => 'Đã hủy', 'color' => 'red', 'icon' => 'cancel']
                        ];
                        
                        $bookingStatusMap = [];
                        foreach ($bookingStats as $stat) {
                            $bookingStatusMap[$stat['status']] = $stat['count'];
                        }
                        
                        foreach ($statusConfig as $status => $config):
                            $count = $bookingStatusMap[$status] ?? 0;
                        ?>
                        <div class="bg-<?php echo $config['color']; ?>-50 dark:bg-<?php echo $config['color']; ?>-900/20 rounded-lg p-4 border border-<?php echo $config['color']; ?>-200 dark:border-<?php echo $config['color']; ?>-800">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="material-symbols-outlined text-<?php echo $config['color']; ?>-600 dark:text-<?php echo $config['color']; ?>-400 text-lg"><?php echo $config['icon']; ?></span>
                            </div>
                            <p class="text-2xl font-bold text-<?php echo $config['color']; ?>-700 dark:text-<?php echo $config['color']; ?>-300"><?php echo number_format($count); ?></p>
                            <p class="text-xs text-<?php echo $config['color']; ?>-600 dark:text-<?php echo $config['color']; ?>-400 mt-1"><?php echo $config['label']; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Đặt chỗ gần đây -->
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold">Đặt chỗ gần đây</h3>
                        <a href="qly_booking.php" class="text-primary hover:underline text-sm font-medium">Xem tất cả</a>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php foreach (array_slice($recentBookings, 0, 5) as $booking): 
                            $statusClass = $booking['status'] === 'confirmed' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 
                                          ($booking['status'] === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300' : 
                                           'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300');
                        ?>
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-primary/10 rounded-lg p-2">
                                        <span class="material-symbols-outlined text-primary">
                                            <?php echo $booking['service_type'] === 'flight' ? 'flight' : ($booking['service_type'] === 'hotel' ? 'hotel' : 'directions_car'); ?>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm"><?php echo htmlspecialchars($booking['booking_code']); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo htmlspecialchars($booking['full_name']); ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-sm text-primary"><?php echo formatCurrency($booking['total_amount']); ?></p>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium <?php echo $statusClass; ?> mt-1">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Chuyến bay sắp khởi hành -->
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold">Chuyến bay sắp khởi hành</h3>
                        <a href="qly_chuyenbay.php" class="text-primary hover:underline text-sm font-medium">Xem tất cả</a>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php foreach ($upcomingFlights as $flight): ?>
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-sm text-primary"><?php echo htmlspecialchars($flight['flight_code']); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($flight['airline_name']); ?></p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-sm font-medium"><?php echo $flight['departure_airport']; ?></span>
                                        <span class="material-symbols-outlined text-gray-400 text-sm">arrow_forward</span>
                                        <span class="text-sm font-medium"><?php echo $flight['arrival_airport']; ?></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold"><?php echo formatDateTime($flight['departure_time'], 'H:i'); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo formatDateTime($flight['departure_time'], 'd/m/Y'); ?></p>
                                    <p class="text-xs text-gray-500 mt-1"><?php echo $flight['available_seats']; ?> ghế trống</p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>