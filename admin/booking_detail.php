<?php
/**
 *
 * Xem chi tiết đơn hàng (Admin)
 */
require_once '../config/config.php';
if (!function_exists('requireAdmin')) {
    /**
     * Kiểm tra người dùng đã đăng nhập và có quyền Admin chưa.
     * Nếu không, chuyển hướng về trang đăng nhập hoặc trang báo lỗi..
     */
    function requireAdmin() {
        if (!Auth::isLoggedIn() || !Auth::isAdmin()) {
            // Chuyển hướng về trang đăng nhập/trang chủ người dùng
            header('Location: ../login.php'); 
            exit;
        }
    }
}
requireAdmin();

$bookingId = $_GET['id'] ?? 0;

// 1. Lấy thông tin Booking + User
$booking = db()->selectOne("
    SELECT b.*, u.full_name, u.email, u.phone, u.avatar
    FROM bookings b
    INNER JOIN users u ON b.user_id = u.id
    WHERE b.id = ?
", [$bookingId]);

if (!$booking) {
    die("Không tìm thấy đơn hàng!");
}

// 2. Lấy chi tiết dịch vụ (Booking Details)
// Sử dụng LEFT JOIN để lấy thông tin tùy theo loại dịch vụ (flight/hotel/vehicle)
$details = db()->select("
    SELECT bd.*, 
        -- Thông tin Xe
        v.name as vehicle_name, v.image as vehicle_image, v.brand, v.license_plate, v.dia_chi as vehicle_address,
        -- Thông tin Chuyến bay
        f.flight_code, f.departure_airport, f.arrival_airport, f.departure_time, f.arrival_time, 
        a.name as airline_name,
        -- Thông tin Khách sạn (Giả sử service_id trỏ vào bảng rooms)
        r.room_type, h.name as hotel_name, h.address as hotel_address, h.city as hotel_city, h.image as hotel_image
    FROM booking_details bd
    -- Join Xe
    LEFT JOIN vehicles v ON bd.service_type = 'vehicle' AND bd.service_id = v.id
    -- Join Chuyến bay
    LEFT JOIN flights f ON bd.service_type = 'flight' AND bd.service_id = f.id
    LEFT JOIN airlines a ON f.airline_id = a.id
    -- Join Khách sạn
    LEFT JOIN rooms r ON bd.service_type = 'hotel' AND bd.service_id = r.id
    LEFT JOIN hotels h ON r.hotel_id = h.id
    WHERE bd.booking_id = ?
", [$bookingId]);

// Helper format trạng thái
$statusLabels = [
    'pending' => ['text' => 'Chờ xử lý', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
    'confirmed' => ['text' => 'Đã xác nhận', 'class' => 'bg-green-100 text-green-700 border-green-200'],
    'completed' => ['text' => 'Hoàn thành', 'class' => 'bg-blue-100 text-blue-700 border-blue-200'],
    'cancelled' => ['text' => 'Đã hủy', 'class' => 'bg-red-100 text-red-700 border-red-200']
];
$currentStatus = $statusLabels[$booking['status']] ?? $statusLabels['pending'];

?>
<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Chi tiết đơn #<?php echo htmlspecialchars($booking['booking_code']); ?> - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <style>.material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }</style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <div class="flex min-h-screen w-full">
        <?php include 'components/sidebar.php'; ?>

        <main class="flex-1 overflow-y-auto">
            <header class="sticky top-0 z-10 flex items-center gap-4 border-b border-gray-200 bg-white/80 px-6 py-3 backdrop-blur-sm dark:border-gray-700 dark:bg-gray-900/80">
                <a href="qly_booking.php" class="flex items-center justify-center rounded-full p-2 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h2 class="text-lg font-bold">Chi tiết đơn hàng</h2>
            </header>

            <div class="p-6 md:p-10 max-w-6xl mx-auto">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 dark:text-white">
                            #<?php echo htmlspecialchars($booking['booking_code']); ?>
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Đặt ngày <?php echo date('H:i d/m/Y', strtotime($booking['created_at'])); ?>
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <span class="px-4 py-2 rounded-full border font-bold text-sm flex items-center gap-2 <?php echo $currentStatus['class']; ?>">
                            <span class="material-symbols-outlined text-lg">info</span>
                            <?php echo $currentStatus['text']; ?>
                        </span>
                        
                        </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">person</span>
                                Khách hàng
                            </h3>
                            <div class="flex items-center gap-4 mb-4">
                                <div class="size-12 rounded-full bg-gray-100 flex items-center justify-center text-xl font-bold text-gray-600 uppercase">
                                    <?php echo substr($booking['full_name'], 0, 1); ?>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($booking['full_name']); ?></p>
                                    <p class="text-sm text-gray-500">ID: #<?php echo $booking['user_id']; ?></p>
                                </div>
                            </div>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
                                    <span class="material-symbols-outlined text-gray-400">mail</span>
                                    <?php echo htmlspecialchars($booking['email']); ?>
                                </div>
                                <div class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
                                    <span class="material-symbols-outlined text-gray-400">call</span>
                                    <?php echo htmlspecialchars($booking['phone'] ?? 'Chưa cập nhật'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">receipt_long</span>
                                Thanh toán
                            </h3>
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Phương thức</span>
                                    <span class="font-medium"><?php echo $booking['payment_method'] ?? 'Tiền mặt'; ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Trạng thái</span>
                                    <span class="font-medium <?php echo $booking['payment_status'] == 'paid' ? 'text-green-600' : 'text-red-600'; ?>">
                                        <?php echo $booking['payment_status'] == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán'; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="border-t border-dashed border-gray-200 dark:border-gray-700 pt-4 flex justify-between items-center">
                                <span class="font-bold">Tổng cộng</span>
                                <span class="text-2xl font-black text-primary"><?php echo formatCurrency($booking['total_amount']); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                                <h3 class="font-bold text-lg flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">format_list_bulleted</span>
                                    Chi tiết dịch vụ
                                </h3>
                            </div>
                            
                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                <?php foreach ($details as $item): ?>
                                <div class="p-6">
                                    
                                    <?php if ($item['service_type'] == 'flight'): ?>
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <span class="text-xs font-bold uppercase tracking-wider text-blue-500 mb-1 block">
                                                Vé máy bay
                                            </span>
                                            <h4 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                                <?php echo htmlspecialchars($item['flight_code']); ?>
                                                <span class="text-sm font-normal text-gray-500">(<?php echo htmlspecialchars($item['airline_name']); ?>)</span>
                                            </h4>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-900 dark:text-white"><?php echo formatCurrency($item['subtotal']); ?></p>
                                            <p class="text-xs text-gray-500">x<?php echo $item['quantity']; ?> vé</p>
                                        </div>
                                    </div>
                                    <div class="bg-blue-50 dark:bg-blue-900/10 rounded-lg p-4 border border-blue-100 dark:border-blue-800">
                                        <div class="flex items-center justify-between">
                                            <div class="text-center">
                                                <p class="text-2xl font-bold text-blue-700 dark:text-blue-400"><?php echo $item['departure_airport']; ?></p>
                                                <p class="text-sm font-medium"><?php echo date('H:i', strtotime($item['departure_time'])); ?></p>
                                                <p class="text-xs text-gray-500"><?php echo date('d/m/Y', strtotime($item['departure_time'])); ?></p>
                                            </div>
                                            <div class="flex-1 px-4 flex flex-col items-center">
                                                <span class="material-symbols-outlined text-gray-400 transform rotate-90 md:rotate-0">flight_takeoff</span>
                                                <div class="w-full h-0.5 bg-gray-300 dark:bg-gray-600 my-1 relative"></div>
                                                <span class="text-xs text-gray-500">Bay thẳng</span>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-2xl font-bold text-blue-700 dark:text-blue-400"><?php echo $item['arrival_airport']; ?></p>
                                                <p class="text-sm font-medium"><?php echo date('H:i', strtotime($item['arrival_time'])); ?></p>
                                                <p class="text-xs text-gray-500"><?php echo date('d/m/Y', strtotime($item['arrival_time'])); ?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <?php elseif ($item['service_type'] == 'hotel'): ?>
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <span class="text-xs font-bold uppercase tracking-wider text-purple-500 mb-1 block">
                                                Khách sạn
                                            </span>
                                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">
                                                <?php echo htmlspecialchars($item['hotel_name']); ?>
                                            </h4>
                                            <p class="text-sm text-gray-500 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm">location_on</span>
                                                <?php echo htmlspecialchars($item['hotel_address']); ?>, <?php echo htmlspecialchars($item['hotel_city']); ?>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-900 dark:text-white"><?php echo formatCurrency($item['subtotal']); ?></p>
                                            <p class="text-xs text-gray-500">x<?php echo $item['quantity']; ?> phòng</p>
                                        </div>
                                    </div>
                                    <div class="bg-purple-50 dark:bg-purple-900/10 rounded-lg p-4 border border-purple-100 dark:border-purple-800 flex gap-6">
                                        <div>
                                            <p class="text-xs text-gray-500 font-bold uppercase">Loại phòng</p>
                                            <p class="font-medium"><?php echo htmlspecialchars($item['room_type']); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 font-bold uppercase">Nhận phòng</p>
                                            <p class="font-medium"><?php echo !empty($item['check_in']) ? date('d/m/Y', strtotime($item['check_in'])) : '---'; ?></p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 font-bold uppercase">Trả phòng</p>
                                            <p class="font-medium"><?php echo !empty($item['check_out']) ? date('d/m/Y', strtotime($item['check_out'])) : '---'; ?></p>
                                        </div>
                                    </div>

                                    <?php elseif ($item['service_type'] == 'vehicle'): ?>
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <span class="text-xs font-bold uppercase tracking-wider text-amber-500 mb-1 block">
                                                Dịch vụ xe
                                            </span>
                                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">
                                                <?php echo htmlspecialchars($item['vehicle_name']); ?>
                                            </h4>
                                            <p class="text-sm text-gray-500">
                                                <?php echo htmlspecialchars($item['brand']); ?> • <?php echo htmlspecialchars($item['license_plate']); ?>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-900 dark:text-white"><?php echo formatCurrency($item['subtotal']); ?></p>
                                            <p class="text-xs text-gray-500">x<?php echo $item['quantity']; ?> xe</p>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-amber-50 dark:bg-amber-900/10 rounded-lg p-4 border border-amber-100 dark:border-amber-800 relative overflow-hidden">
                                        <div class="absolute left-[29px] top-8 bottom-8 w-0.5 bg-gray-300 dark:bg-gray-600 border-l border-dashed"></div>

                                        <div class="relative z-10 space-y-6">
                                            <div class="flex gap-4">
                                                <div class="flex-shrink-0 size-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center ring-4 ring-white dark:ring-gray-800">
                                                    <span class="material-symbols-outlined text-sm">my_location</span>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-gray-500 uppercase">Điểm đón</p>
                                                    <p class="font-medium text-gray-900 dark:text-white mt-0.5">
                                                        <?php echo htmlspecialchars($item['pickup_location'] ?? 'Chưa xác định'); ?>
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <?php echo !empty($item['check_in']) ? date('H:i - d/m/Y', strtotime($item['check_in'])) : ''; ?>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex gap-4">
                                                <div class="flex-shrink-0 size-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center ring-4 ring-white dark:ring-gray-800">
                                                    <span class="material-symbols-outlined text-sm">location_on</span>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-gray-500 uppercase">Điểm trả</p>
                                                    <p class="font-medium text-gray-900 dark:text-white mt-0.5">
                                                        <?php echo htmlspecialchars($item['dropoff_location'] ?? 'Chưa xác định'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($item['special_requests'])): ?>
                                    <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm">
                                        <span class="font-bold text-gray-700 dark:text-gray-300">Ghi chú:</span> 
                                        <span class="text-gray-600 dark:text-gray-400"><?php echo htmlspecialchars($item['special_requests']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>