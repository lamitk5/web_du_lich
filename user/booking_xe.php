<?php
/**
 * users/booking.php
 */

require_once '../config/config.php';
requireLogin();

$currentUser = getCurrentUser();
$vehicleId = $_GET['vehicle_id'] ?? $_POST['vehicle_id'] ?? 0;
$step = $_POST['step'] ?? 'confirm';

// 1. Lấy thông tin xe
$vehicle = db()->selectOne("SELECT * FROM vehicles WHERE id = ?", [$vehicleId]);

if (!$vehicle) {
    echo "<script>alert('Xe không tồn tại!'); window.location.href='tim_kiem_xe.php';</script>";
    exit;
}

// 2. Xử lý đặt xe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 'process') {
    try {
        // Lấy dữ liệu form
        $pickup = $_POST['pickup'] ?? '';
        $dropoff = $_POST['dropoff'] ?? '';
        $date = $_POST['date'] ?? date('Y-m-d');
        $time = $_POST['time'] ?? '09:00';
        $passengers = $_POST['passengers'] ?? 1;
        $notes = $_POST['notes'] ?? '';
        
        $totalAmount = $vehicle['price_per_day']; 

        // --- BẮT ĐẦU TRANSACTION ---
        db()->beginTransaction();

        // A. Lưu vào bảng BOOKINGS
        $bookingCode = 'CAR' . time() . rand(10, 99);
        
        // Chuẩn bị mảng dữ liệu để dùng hàm insert()
        $bookingData = [
            'user_id' => $currentUser['id'],
            'booking_code' => $bookingCode,
            'booking_type' => 'vehicle',
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Gọi hàm insert($table, $data) có sẵn
        $insertBooking = db()->insert('bookings', $bookingData);
        
        if (!$insertBooking) {
            throw new Exception("Lỗi tạo đơn hàng: " . db()->getLastError());
        }
        
        // Lấy ID vừa tạo
        $bookingId = db()->getLastInsertId();

        // B. Lưu vào bảng BOOKING_DETAILS
        $fullNotes = "Giờ đón: $time. Ghi chú: $notes";

        $detailData = [
            'booking_id' => $bookingId,
            'service_type' => 'vehicle',
            'service_id' => $vehicleId,
            'quantity' => 1,
            'unit_price' => $vehicle['price_per_day'],
            'subtotal' => $totalAmount,
            'pickup_location' => $pickup,    // Lưu điểm đón
            'dropoff_location' => $dropoff,  // Lưu điểm trả
            'check_in' => $date,
            'passengers' => $passengers,
            'special_requests' => $fullNotes
        ];

        $insertDetail = db()->insert('booking_details', $detailData);

        if (!$insertDetail) {
            throw new Exception("Lỗi lưu chi tiết: " . db()->getLastError());
        }

        // --- COMMIT TRANSACTION ---
        db()->commit();

        echo "<script>alert('Đặt xe thành công! Mã đơn: $bookingCode'); window.location.href='thongtin.php';</script>";
        exit;

    } catch (Exception $e) {
        // --- ROLLBACK TRANSACTION ---
        db()->rollback();
        $error = "Lỗi hệ thống: " . $e->getMessage();
    }
}

// Lấy dữ liệu hiển thị
$pickupVal = $_GET['pickup'] ?? $_POST['pickup'] ?? $vehicle['dia_chi'] ?? '';
$dropoffVal = $_GET['dropoff'] ?? $_POST['dropoff'] ?? '';
$dateVal = $_GET['date'] ?? $_POST['date'] ?? date('Y-m-d');
$timeVal = $_GET['time'] ?? $_POST['time'] ?? '09:00';
?>

<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Xác nhận đặt xe - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { "primary": "#0da6f2", "background-light": "#f5f7f8", "background-dark": "#101c22" },
                    fontFamily: { "display": ["Plus Jakarta Sans", "sans-serif"] }
                }
            }
        }
    </script>
    <style>.material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }</style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-800 dark:text-slate-200">
    <header class="bg-white dark:bg-[#1a2831] shadow-sm border-b border-gray-200 dark:border-gray-700 h-16 flex items-center">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="tim_kiem_xe.php" class="font-bold text-xl text-primary flex items-center gap-2">
                <span class="material-symbols-outlined">arrow_back</span> Quay lại
            </a>
            <h1 class="font-bold text-lg">Xác nhận đặt xe</h1>
            <div class="w-20"></div> 
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <?php if(isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Lỗi!</strong>
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <input type="hidden" name="step" value="process">
            <input type="hidden" name="vehicle_id" value="<?php echo $vehicleId; ?>">

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-[#1a2831] p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">trip_origin</span>
                        Thông tin lộ trình
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-500 mb-2">Điểm đón khách (*)</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-3 text-green-500">my_location</span>
                                <input required type="text" name="pickup" value="<?php echo htmlspecialchars($pickupVal); ?>" 
                                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-primary outline-none" 
                                       placeholder="Ví dụ: Sân bay Nội Bài...">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-500 mb-2">Điểm trả khách (*)</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-3 text-red-500">location_on</span>
                                <input required type="text" name="dropoff" value="<?php echo htmlspecialchars($dropoffVal); ?>" 
                                       class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-primary outline-none" 
                                       placeholder="Ví dụ: Khách sạn Melia...">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-500 mb-2">Ngày đón</label>
                            <input required type="date" name="date" value="<?php echo $dateVal; ?>" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-primary outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-500 mb-2">Giờ đón</label>
                            <input required type="time" name="time" value="<?php echo $timeVal; ?>" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-primary outline-none">
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-bold text-gray-500 mb-2">Ghi chú cho tài xế</label>
                        <textarea name="notes" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-primary outline-none"></textarea>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#1a2831] p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Thông tin liên hệ
                    </h2>
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xl">
                            <?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?>
                        </div>
                        <div>
                            <p class="font-bold text-lg"><?php echo htmlspecialchars($currentUser['full_name']); ?></p>
                            <p class="text-gray-500 text-sm"><?php echo htmlspecialchars($currentUser['email']); ?></p>
                            <p class="text-gray-500 text-sm"><?php echo htmlspecialchars($currentUser['phone'] ?? 'Chưa cập nhật SĐT'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-[#1a2831] p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 sticky top-8">
                    <h3 class="font-bold text-lg mb-4 text-center">Tóm tắt đơn hàng</h3>
                    <div class="mb-4 rounded-xl overflow-hidden">
                        <img src="<?php echo htmlspecialchars($vehicle['image'] ?? 'https://placehold.co/600x400'); ?>" class="w-full h-32 object-cover">
                    </div>
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <h4 class="font-bold text-lg"><?php echo htmlspecialchars($vehicle['name']); ?></h4>
                        <p class="text-sm text-gray-500"><?php echo ucfirst($vehicle['type']); ?> • <?php echo $vehicle['brand']; ?></p>
                    </div>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Giá thuê xe</span>
                            <span class="font-bold"><?php echo formatCurrency($vehicle['price_per_day']); ?></span>
                        </div>
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between items-end">
                            <span class="font-bold">Tổng cộng</span>
                            <span class="text-2xl font-black text-primary"><?php echo formatCurrency($vehicle['price_per_day']); ?></span>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-4 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-2">
                        Xác nhận đặt xe
                        <span class="material-symbols-outlined">check_circle</span>
                    </button>
                </div>
            </div>
        </form>
    </main>
</body>
</html>