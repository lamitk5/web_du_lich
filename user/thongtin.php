<?php
/**
 * users/thongtin.php
 * Trang quản lý thông tin cá nhân và lịch sử đặt chỗ
 **/

require_once '../config/config.php';
requireLogin(); // Bắt buộc đăng nhập

$currentUser = getCurrentUser();
$pageTitle = "Quản lý đặt chỗ - " . SITE_NAME;

// --- 1. XỬ LÝ CẬP NHẬT THÔNG TIN CÁ NHÂN ---
$updateMsg = '';
$updateType = ''; // success hoặc error

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $fullName = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $userId = $currentUser['id'];

    // Xử lý upload Avatar
    $avatarPath = $currentUser['avatar']; // Mặc định giữ ảnh cũ
    
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $uploadDir = '../uploads/avatars/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $fileName = 'avatar_' . $userId . '_' . time() . '.' . $ext;
            $targetFile = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
                $avatarPath = 'uploads/avatars/' . $fileName; // Lưu đường dẫn tương đối vào DB
            }
        } else {
            $updateMsg = "Chỉ chấp nhận file ảnh (JPG, PNG, GIF)";
            $updateType = 'error';
        }
    }

    // Cập nhật vào Database
    if (empty($updateMsg)) {
        $updateData = [
            'full_name' => $fullName,
            'phone' => $phone,
            'avatar' => $avatarPath
        ];

        // Gọi hàm update($table, $data, $condition, $params)
        if (db()->update('users', $updateData, 'id = ?', [$userId])) {
            $updateMsg = "Cập nhật hồ sơ thành công!";
            $updateType = 'success';
            
            // Cập nhật lại session user để hiển thị ngay lập tức
            $_SESSION['user_name'] = $fullName;
            $currentUser = db()->selectOne("SELECT * FROM users WHERE id = ?", [$userId]);
        } else {
            // Lỗi hệ thống: Hiển thị lỗi SQL
            $updateMsg = "Lỗi hệ thống: " . db()->getLastError();
            $updateType = 'error';
        }
    }
}

// --- 2. LẤY LỊCH SỬ ĐẶT CHỖ (Đã tối ưu Query) ---
$filterType = $_GET['type'] ?? 'all';
$typeCondition = "";
$params = [$currentUser['id']];
$dbType = '';
switch ($filterType) {
    case 'flight':
        $dbType = 'flight';
        break;
    case 'hotel':
        $dbType = 'hotel';
        break;
    case 'car':
        $dbType = 'vehicle';
        break;
    default:
        $dbType = '';
        break;
}

if ($dbType !== '') {
    $typeCondition = "AND b.booking_type = ?";
    $params[] = $dbType;
}
// END SỬA LỖI

// Map mã sân bay
$airportMap = [
    'SGN' => 'TP. HCM',
    'HAN' => 'Hà Nội',
    'DAD' => 'Đà Nẵng',
    'CXR' => 'Nha Trang',
    'PQC' => 'Phú Quốc',
    'DLI' => 'Đà Lạt',
    'HPH' => 'Hải Phòng',
    'VDO' => 'Vân Đồn',
    'VCA' => 'Cần Thơ'
];

// Query Clean: Không JOIN lung tung để tránh nhân bản dòng
$sqlBookings = "
    SELECT 
        b.id as booking_id, b.booking_code, b.total_amount, b.status, b.payment_status, b.created_at, b.booking_type,
        bd.service_type, bd.check_in, bd.check_out, bd.pickup_location,
        -- Thông tin Chuyến bay
        f.flight_code, f.departure_time, f.arrival_time, 
        f.departure_airport, f.arrival_airport,
        al.name as airline_name, al.logo as airline_logo,
        -- Thông tin Khách sạn
        h.name as homestay_name, h.main_image as homestay_image, h.address as homestay_address,
        -- Thông tin Xe
        v.name as vehicle_name, v.image as vehicle_image, v.brand as vehicle_brand, v.type as vehicle_type
    FROM bookings b
    JOIN booking_details bd ON b.id = bd.booking_id
    -- Left Join từng loại dịch vụ
    LEFT JOIN flights f ON (bd.service_type = 'flight' AND bd.service_id = f.id)
    LEFT JOIN airlines al ON f.airline_id = al.id
    LEFT JOIN homestays h ON (bd.service_type = 'hotel' AND bd.service_id = h.id)
    LEFT JOIN vehicles v ON (bd.service_type = 'vehicle' AND bd.service_id = v.id)
    
    WHERE b.user_id = ? $typeCondition
    ORDER BY b.created_at DESC
";

$bookings = db()->select($sqlBookings, $params);

// Nhúng Header
require_once 'includes/header.php';
?>

<main class="flex-1 bg-background-light dark:bg-gray-900 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <aside class="w-full lg:w-1/4">
                <div class="bg-white dark:bg-[#1a2831] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sticky top-24">
                    <h3 class="text-lg font-bold mb-4 text-[#0d171c] dark:text-white">Hồ sơ của tôi</h3>
                    
                    <?php if($updateMsg): ?>
                        <div class="mb-4 p-3 rounded text-sm <?php echo $updateType == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                            <?php echo $updateMsg; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data" class="space-y-4">
                        <div class="flex flex-col items-center mb-4">
                            <div class="relative group cursor-pointer w-24 h-24">
                                <?php 
                                    $avatarUrl = !empty($currentUser['avatar']) && file_exists('../' . $currentUser['avatar']) 
                                        ? '../' . $currentUser['avatar'] 
                                        : "https://ui-avatars.com/api/?name=" . urlencode($currentUser['full_name']) . "&background=0da6f2&color=fff&size=128";
                                ?>
                                <img src="<?php echo $avatarUrl; ?>" alt="Avatar" class="w-full h-full rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-md">
                                <label class="absolute inset-0 flex items-center justify-center bg-black/40 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                    <span class="material-symbols-outlined">camera_alt</span>
                                    <input type="file" name="avatar" class="hidden" accept="image/*">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Chạm để đổi ảnh</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Họ và tên</label>
                            <input type="text" name="full_name" value="<?php echo htmlspecialchars($currentUser['full_name']); ?>" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-sm focus:ring-primary dark:text-white px-3 py-2">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email</label>
                            <input type="email" value="<?php echo htmlspecialchars($currentUser['email']); ?>" disabled class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-900 text-gray-500 text-sm cursor-not-allowed px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Số điện thoại</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($currentUser['phone'] ?? ''); ?>" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-sm focus:ring-primary dark:text-white px-3 py-2">
                        </div>

                        <button type="submit" name="update_profile" class="w-full py-2.5 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition-colors shadow-lg shadow-primary/30 mt-4">
                            Lưu thay đổi
                        </button>
                    </form>
                </div>
            </aside>

            <div class="w-full lg:w-3/4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <h1 class="text-2xl font-black text-[#0d171c] dark:text-white">Quản lý đặt chỗ</h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Xem lại lịch sử và trạng thái các chuyến đi của bạn</p>
                    </div>
                    <a href="trang_chu.php" class="px-4 py-2 bg-amber-500 text-[#0d171c] font-bold rounded-lg hover:bg-amber-400 transition-colors text-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">add_circle</span>
                        Đặt chuyến mới
                    </a>
                </div>

                <div class="border-b border-gray-200 dark:border-gray-700 mb-6 overflow-x-auto">
                    <nav class="flex space-x-8 min-w-max">
                        <a href="?type=all" class="pb-4 text-sm font-bold border-b-2 <?php echo $filterType == 'all' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'; ?>">
                            Tất cả
                        </a>
                        <a href="?type=flight" class="pb-4 text-sm font-bold border-b-2 <?php echo $filterType == 'flight' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'; ?>">
                            <span class="material-symbols-outlined text-lg align-bottom mr-1">flight</span>Chuyến bay
                        </a>
                        <a href="?type=hotel" class="pb-4 text-sm font-bold border-b-2 <?php echo $filterType == 'hotel' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'; ?>">
                            <span class="material-symbols-outlined text-lg align-bottom mr-1">hotel</span>Homestays
                        </a>
                        <a href="?type=car" class="pb-4 text-sm font-bold border-b-2 <?php echo $filterType == 'car' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'; ?>">
                            <span class="material-symbols-outlined text-lg align-bottom mr-1">directions_car</span>Xe
                        </a>
                    </nav>
                </div>

                <div class="space-y-6">
                    <?php if (empty($bookings)): ?>
                        <div class="text-center py-16 bg-white dark:bg-[#1a2831] rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                            <span class="material-symbols-outlined text-6xl text-gray-300">receipt_long</span>
                            <p class="mt-4 text-gray-500 font-medium">Bạn chưa có đơn đặt chỗ nào trong mục này.</p>
                            <a href="trang_chu.php" class="mt-4 inline-block text-primary hover:underline font-bold">Khám phá ngay</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($bookings as $booking): ?>
                            <div class="bg-white dark:bg-[#1a2831] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow group">
                                <div class="p-6">
                                    <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="h-14 w-14 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden shrink-0">
                                                <?php if ($booking['booking_type'] == 'flight'): ?>
                                                    <?php if(!empty($booking['airline_logo'])): ?>
                                                        <img src="<?php echo $booking['airline_logo']; ?>" class="w-full h-full object-contain">
                                                    <?php else: ?>
                                                        <span class="material-symbols-outlined text-2xl text-primary">flight</span>
                                                    <?php endif; ?>
                                                <?php elseif ($booking['booking_type'] == 'hotel'): ?>
                                                    <?php if(!empty($booking['homestay_image'])): ?>
                                                        <img src="<?php echo $booking['homestay_image']; ?>" class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <span class="material-symbols-outlined text-2xl text-orange-500">hotel</span>
                                                    <?php endif; ?>
                                                <?php elseif ($booking['booking_type'] == 'vehicle'): ?>
                                                     <?php if(!empty($booking['vehicle_image'])): ?>
                                                        <img src="<?php echo $booking['vehicle_image']; ?>" class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <span class="material-symbols-outlined text-2xl text-green-600">directions_car</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>

                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="uppercase text-[10px] font-bold text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded tracking-wider">
                                                        <?php echo $booking['booking_type']; ?>
                                                    </span>
                                                    <span class="text-xs text-gray-400">#<?php echo $booking['booking_code']; ?></span>
                                                </div>
                                                <h3 class="text-lg font-bold text-[#0d171c] dark:text-white mt-1 line-clamp-1">
                                                    <?php 
                                                        if ($booking['booking_type'] == 'flight') {
                                                            $destName = $airportMap[$booking['arrival_airport']] ?? $booking['arrival_airport'];
                                                            echo "Bay tới " . $destName;
                                                        }
                                                        elseif ($booking['booking_type'] == 'hotel') echo $booking['homestay_name'] ?? 'Homestays chưa rõ tên';
                                                        elseif ($booking['booking_type'] == 'vehicle') echo $booking['vehicle_name'] ?? 'Thuê xe';
                                                    ?>
                                                </h3>
                                            </div>
                                        </div>

                                        <div class="text-right ml-auto">
                                            <?php echo getStatusBadge($booking['status']); ?>
                                            <p class="text-lg font-black text-primary mt-1"><?php echo formatCurrency($booking['total_amount']); ?></p>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-100 dark:border-gray-700 my-4"></div>

                                    <div class="text-sm text-gray-600 dark:text-gray-300">
                                        <?php if ($booking['booking_type'] == 'flight'): ?>
                                            <div class="flex items-center gap-4 sm:gap-8">
                                                <div class="text-center min-w-[60px]">
                                                    <div class="font-bold text-lg"><?php echo date('H:i', strtotime($booking['departure_time'])); ?></div>
                                                    <div class="text-primary text-xs font-bold uppercase">
                                                        <?php echo $booking['departure_airport']; ?>
                                                    </div>
                                                    <div class="text-[10px] text-gray-400">
                                                        <?php echo $airportMap[$booking['departure_airport']] ?? ''; ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex-1 flex flex-col items-center">
                                                    <span class="text-[10px] text-gray-400 mb-1"><?php echo $booking['airline_name']; ?></span>
                                                    <div class="w-full h-[1px] bg-gray-300 dark:bg-gray-600 relative flex items-center justify-between">
                                                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full"></div>
                                                        <span class="material-symbols-outlined text-xs text-gray-400 bg-white dark:bg-[#1a2831] px-1">flight</span>
                                                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full"></div>
                                                    </div>
                                                    <span class="text-[10px] text-gray-400 mt-1"><?php echo date('d/m/Y', strtotime($booking['departure_time'])); ?></span>
                                                </div>
                                                
                                                <div class="text-center min-w-[60px]">
                                                    <div class="font-bold text-lg"><?php echo date('H:i', strtotime($booking['arrival_time'])); ?></div>
                                                    <div class="text-primary text-xs font-bold uppercase">
                                                        <?php echo $booking['arrival_airport']; ?>
                                                    </div>
                                                    <div class="text-[10px] text-gray-400">
                                                        <?php echo $airportMap[$booking['arrival_airport']] ?? ''; ?>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php elseif ($booking['booking_type'] == 'hotel'): ?>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <p class="flex items-center gap-2 text-gray-500 text-xs uppercase font-bold mb-1">Check-in / Check-out</p>
                                                    <p class="font-semibold">
                                                        <?php echo formatDateTime($booking['check_in'], 'd/m/Y'); ?> 
                                                        <span class="text-gray-400 mx-1">→</span> 
                                                        <?php echo formatDateTime($booking['check_out'], 'd/m/Y'); ?>
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="flex items-center gap-2 text-gray-500 text-xs uppercase font-bold mb-1">Địa chỉ</p>
                                                    <p class="line-clamp-1" title="<?php echo $booking['homestay_address']; ?>">
                                                        <?php echo $booking['homestay_address']; ?>
                                                    </p>
                                                </div>
                                            </div>

                                        <?php elseif ($booking['booking_type'] == 'vehicle'): ?>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <p class="flex items-center gap-2 text-gray-500 text-xs uppercase font-bold mb-1">Ngày đón</p>
                                                    <p class="font-semibold"><?php echo formatDateTime($booking['check_in'], 'd/m/Y'); ?></p>
                                                </div>
                                                <div>
                                                    <p class="flex items-center gap-2 text-gray-500 text-xs uppercase font-bold mb-1">Điểm đón</p>
                                                    <p class="line-clamp-1" title="<?php echo $booking['pickup_location']; ?>">
                                                        <?php echo $booking['pickup_location'] ?? 'Chưa cập nhật'; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex justify-end gap-3 mt-6">
                                        <?php if ($booking['status'] == 'pending'): ?>
                                            <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-lg hover:bg-gray-200 transition-colors uppercase tracking-wide">
                                                Hủy đơn
                                            </button>
                                            <button class="px-4 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary/90 transition-colors shadow-md shadow-primary/20 uppercase tracking-wide">
                                                Thanh toán
                                            </button>
                                        <?php else: ?>
                                            <button class="px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 text-xs font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors uppercase tracking-wide">
                                                Chi tiết
                                            </button>
                                            <?php if ($booking['booking_type'] == 'flight'): ?>
                                                <a href="tim_kiem_chuyenbay.php" class="px-4 py-2 bg-primary/10 text-primary text-xs font-bold rounded-lg hover:bg-primary/20 transition-colors uppercase tracking-wide">
                                                    Đặt lại
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</main>

<footer class="bg-white dark:bg-[#1a2831] border-t border-gray-200 dark:border-gray-700 py-8 mt-auto">
    <div class="container mx-auto px-4 text-center">
        <p class="text-sm text-gray-500">© <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
    </div>
</footer>

</body>
</html>