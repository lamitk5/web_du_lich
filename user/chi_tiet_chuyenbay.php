<?php
/**
 * users/chi_tiet_chuyenbay.php
 */

require_once '../config/config.php';

// --- PHẦN 1: XỬ LÝ ĐẶT VÉ (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'book_flight') {
    $currentUser = getCurrentUser();

    if (!$currentUser) {
        setFlashMessage('error', 'Vui lòng đăng nhập để đặt vé!');
        redirect('../login.php');
    }

    $flight_id = intval($_POST['flight_id']);
    $passengers = intval($_POST['passengers']);
    
    // Lấy thông tin chuyến bay và kiểm tra
    $flight = db()->selectOne("SELECT id, price, available_seats FROM flights WHERE id = ?", [$flight_id]);
    
    if (!$flight) {
        setFlashMessage('error', 'Chuyến bay không tồn tại!');
        redirect('tim_kiem_chuyenbay.php');
    }

    if ($passengers > $flight['available_seats']) {
        setFlashMessage('warning', 'Xin lỗi, chuyến bay chỉ còn ' . $flight['available_seats'] . ' ghế trống!');
        redirect('chi_tiet_chuyenbay.php?flight_id=' . $flight_id);
    }

    $total_price = $flight['price'] * $passengers;
    
    // 1. TẠO MÃ BOOKING DUY NHẤT
    $booking_code = 'FL' . strtoupper(substr(uniqid(), 8)) . rand(100, 999);
    
    // 2. INSERT vào bảng bookings
    $sql_insert_booking = "INSERT INTO bookings (user_id, booking_code, booking_type, total_amount, status, payment_status) 
                           VALUES (?, ?, 'flight', ?, 'pending', 'unpaid')";
    
    $inserted = db()->execute($sql_insert_booking, 
        [$currentUser['id'], $booking_code, $total_price]
    );

    if ($inserted) {
        $booking_id = db()->getLastInsertId(); // Lấy ID của Booking cha
        
        // 3. INSERT vào bảng booking_details
        $sql_detail = "INSERT INTO booking_details (booking_id, service_type, service_id, quantity, unit_price, subtotal)
                       VALUES (?, 'flight', ?, ?, ?, ?)";
        
        $detail_inserted = db()->execute($sql_detail, [
            $booking_id,
            $flight_id,
            $passengers,
            $flight['price'],
            $total_price
        ]);
        
        if ($detail_inserted) {
            // 4. Cập nhật số ghế trống
            db()->execute("UPDATE flights SET available_seats = available_seats - ? WHERE id = ?", [$passengers, $flight_id]);
            
            logSuccess("New flight booking created: {$booking_code}", ['user_id' => $currentUser['id'], 'flight_id' => $flight_id]);
            setFlashMessage('success', 'Đặt vé THÀNH CÔNG! Mã đặt vé của bạn là: **' . $booking_code . '**. Vui lòng kiểm tra email để nhận thông tin chi tiết.');
            
            // CHUYỂN HƯỚNG ĐÚNG VỀ TRANG CHI TIẾT CHUYẾN BAY
            redirect('chi_tiet_chuyenbay.php?flight_id=' . $flight_id); 
            
        } else {
            // Nếu chèn chi tiết thất bại, xóa booking cha để tránh dữ liệu rác
            db()->delete('bookings', 'id = ?', [$booking_id]);
            logError("Failed to insert booking_details (Booking ID: {$booking_id})", ['error' => db()->getLastError()]);
            setFlashMessage('error', 'Lỗi khi lưu chi tiết vé. Đơn hàng bị hủy. Vui lòng thử lại.');
            redirect('chi_tiet_chuyenbay.php?flight_id=' . $flight_id);
        }

    } else {
        // Lỗi INSERT vào bookings
        logError("SQL INSERT into Bookings failed", ['user_id' => $currentUser['id'], 'sql_error' => db()->getLastError()]);
        setFlashMessage('error', 'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng kiểm tra log hệ thống.');
        redirect('chi_tiet_chuyenbay.php?flight_id=' . $flight_id);
    }
    exit;
}

// --- PHẦN 2: LẤY DỮ LIỆU HIỂN THỊ (GET) ---
$flightId = $_GET['flight_id'] ?? 0;
$flight = db()->selectOne("
    SELECT f.*, a.name as airline_name, a.code as airline_code, a.logo as airline_logo
    FROM flights f
    INNER JOIN airlines a ON f.airline_id = a.id
    WHERE f.id = ?
", [$flightId]);

if (!$flight) {
    setFlashMessage('error', 'Không tìm thấy chuyến bay!');
    redirect('tim_kiem_chuyenbay.php');
    exit;
}

$dep = new DateTime($flight['departure_time']);
$arr = new DateTime($flight['arrival_time']);
$duration = $dep->diff($arr)->format('%h giờ %i phút');
$default_passengers = isset($_GET['passengers']) ? max(1, intval($_GET['passengers'])) : 1;

require_once 'includes/header.php'; 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo htmlspecialchars($flight['flight_code']); ?> - Chi tiết chuyến bay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#0da6f2',
                    }
                }
            }
        }
    </script>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { vertical-align: bottom; }
    </style>
</head>
<body class="bg-[#f5f7f8] text-slate-800 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">

    <main class="container mx-auto px-4 py-8 max-w-7xl">
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
            <a href="trang_chu.php" class="hover:text-blue-500">Trang chủ</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="tim_kiem_chuyenbay.php" class="hover:text-blue-500">Vé máy bay</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="font-bold text-gray-800 dark:text-white"><?php echo htmlspecialchars($flight['departure_airport']); ?> - <?php echo htmlspecialchars($flight['arrival_airport']); ?></span>
        </div>

        <?php if ($flash = getFlashMessage()): ?>
            <div class="p-4 mb-6 rounded-xl text-sm font-bold flex items-center gap-3 <?php 
                echo $flash['type'] === 'success' ? 'bg-green-500/10 text-green-400 border border-green-500/50' : 
                     ($flash['type'] === 'warning' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/50' : 
                     'bg-red-500/10 text-red-400 border border-red-500/50'); 
            ?>">
                <span class="material-symbols-outlined text-lg">
                    <?php echo $flash['type'] === 'success' ? 'check_circle' : 'error'; ?>
                </span>
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b border-gray-100 dark:border-gray-700 pb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-lg bg-gray-50 dark:bg-gray-700 flex items-center justify-center border border-gray-100 dark:border-gray-600 overflow-hidden">
                                <?php if($flight['airline_logo']): ?>
                                    <img src="<?php echo $flight['airline_logo']; ?>" class="w-full h-full object-contain">
                                <?php else: ?>
                                    <span class="font-bold text-gray-400"><?php echo $flight['airline_code']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($flight['airline_name']); ?></h1>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Số hiệu: <span class="font-bold text-blue-600 dark:text-blue-400"><?php echo htmlspecialchars($flight['flight_code']); ?></span> • Airbus A320</p>
                            </div>
                        </div>
                        <div class="text-right hidden sm:block">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold">
                                <span class="material-symbols-outlined text-[14px]">check_circle</span> Bay thẳng
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-2 sm:px-8 py-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl relative overflow-hidden transition-colors">
                        <div class="text-center z-10 relative">
                            <p class="text-3xl font-black text-blue-600 dark:text-blue-400"><?php echo $dep->format('H:i'); ?></p>
                            <p class="font-bold text-gray-800 dark:text-gray-200"><?php echo $flight['departure_airport']; ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo $dep->format('d/m/Y'); ?></p>
                        </div>

                        <div class="flex-1 flex flex-col items-center px-4 relative">
                            <p class="text-xs font-bold text-gray-400 mb-1"><?php echo $duration; ?></p>
                            <div class="w-full h-[2px] bg-gray-300 dark:bg-gray-600 relative">
                                <span class="material-symbols-outlined absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-blue-500 bg-gray-50 dark:bg-gray-800 px-1 rotate-90">flight</span>
                            </div>
                        </div>

                        <div class="text-center z-10 relative">
                            <p class="text-3xl font-black text-blue-600 dark:text-blue-400"><?php echo $arr->format('H:i'); ?></p>
                            <p class="font-bold text-gray-800 dark:text-gray-200"><?php echo $flight['arrival_airport']; ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo $arr->format('d/m/Y'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-gray-900 dark:text-white">
                        <span class="material-symbols-outlined text-blue-500">info</span> Thông tin vé & Hành lý
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-blue-50/50 dark:bg-blue-900/20">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 mt-0.5">luggage</span>
                            <div>
                                <p class="font-bold text-sm text-gray-800 dark:text-gray-200">Hành lý xách tay</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">07 kg / khách</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-blue-50/50 dark:bg-blue-900/20">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 mt-0.5">no_luggage</span>
                            <div>
                                <p class="font-bold text-sm text-gray-800 dark:text-gray-200">Hành lý ký gửi</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Mua thêm</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-blue-50/50 dark:bg-blue-900/20">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 mt-0.5">restaurant</span>
                            <div>
                                <p class="font-bold text-sm text-gray-800 dark:text-gray-200">Suất ăn</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Phục vụ đồ uống nhẹ</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-blue-50/50 dark:bg-blue-900/20">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 mt-0.5">event_seat</span>
                            <div>
                                <p class="font-bold text-sm text-gray-800 dark:text-gray-200">Ghế ngồi</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Hạng Phổ thông</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                    <h3 class="text-lg font-bold mb-2 text-gray-900 dark:text-white">Lưu ý quan trọng</h3>
                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-300 space-y-2">
                        <li>Hành khách vui lòng có mặt tại sân bay trước 90 phút so với giờ khởi hành.</li>
                        <li>Vé đã đặt có thể hoàn/hủy theo quy định của hãng hàng không.</li>
                        <li>Mang theo giấy tờ tùy thân hợp lệ (CCCD/Hộ chiếu).</li>
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-20 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl overflow-hidden transition-colors duration-300">
                    <div class="bg-gray-900 dark:bg-gray-950 text-white p-4 text-center">
                        <p class="text-sm opacity-80">Giá vé một người</p>
                        <p class="text-2xl font-bold"><?php echo formatCurrency($flight['price']); ?></p>
                    </div>

                    <div class="p-6">
                        <form action="" method="POST" id="booking-form">
                            <input type="hidden" name="action" value="book_flight">
                            <input type="hidden" name="flight_id" value="<?php echo $flightId; ?>">

                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Số lượng khách</label>
                                <div class="relative">
                                    <select name="passengers" id="passengers" class="w-full p-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl font-bold appearance-none outline-none focus:border-blue-500 dark:text-white transition">
                                        <?php 
                                        $max_bookable = min(10, $flight['available_seats']); 
                                        for($i = 1; $i <= $max_bookable; $i++): 
                                        ?>
                                            <option value="<?php echo $i; ?>" <?php echo ($i == $default_passengers) ? 'selected' : ''; ?>>
                                                <?php echo $i; ?> người lớn
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-3 text-gray-400 pointer-events-none">expand_more</span>
                                </div>
                                <p class="text-xs text-green-600 dark:text-green-400 mt-2 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">check</span>
                                    Còn <?php echo $flight['available_seats']; ?> ghế trống
                                </p>
                            </div>

                            <div class="border-t border-dashed border-gray-200 dark:border-gray-600 py-4 space-y-2 mb-4">
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                    <span>Giá vé x <span id="pax-count">1</span></span>
                                    <span id="sub-total"><?php echo formatCurrency($flight['price']); ?></span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                    <span>Thuế & Phí</span>
                                    <span>0₫</span>
                                </div>
                                <div class="flex justify-between text-lg font-black text-gray-900 dark:text-white pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <span>Tổng cộng</span>
                                    <span id="total-price" class="text-blue-600 dark:text-blue-400"><?php echo formatCurrency($flight['price'] * $default_passengers); ?></span>
                                </div>
                            </div>

                            <button type="submit" onclick="return confirm('Xác nhận đặt vé chuyến bay này?');" 
                                    class="w-full bg-amber-400 hover:bg-amber-500 text-gray-900 font-bold py-4 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 group">
                                <span>Tiến hành đặt vé</span>
                                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </button>
                            
                            <p class="text-[10px] text-gray-400 text-center mt-3">
                                Bằng việc đặt vé, bạn đồng ý với điều khoản sử dụng.
                            </p>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        // 1. Logic Tính tiền
        const pricePerTicket = <?php echo $flight['price']; ?>;
        const selectPax = document.getElementById('passengers');
        const displayCount = document.getElementById('pax-count');
        const displaySub = document.getElementById('sub-total');
        const displayTotal = document.getElementById('total-price');

        const formatter = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });

        selectPax.addEventListener('change', function() {
            const count = parseInt(this.value);
            const total = count * pricePerTicket;
            displayCount.innerText = count;
            
            // Định dạng tiền tệ và loại bỏ ký hiệu '₫' ở cuối trước khi thêm lại (để tránh lỗi)
            const formattedTotal = formatter.format(total).replace('₫', '').trim() + '₫';

            displaySub.innerText = formattedTotal;
            displayTotal.innerText = formattedTotal;
        });
        
        selectPax.dispatchEvent(new Event('change'));

        // 2. Logic Toggle Dark Mode
        const themeToggleBtn = document.getElementById('theme-toggle');
        
        if (themeToggleBtn) { // Đảm bảo nút tồn tại trong header
            themeToggleBtn.addEventListener('click', function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
            });
        }
    </script>

</body>
</html>