<?php
/**
 * Trang Xử lý Đặt vé & Thanh toán
 * Tên file: booking_process.php
 */

require_once '../config/config.php';

// Kiểm tra đăng nhập (Bắt buộc phải đăng nhập mới được đặt)
if (!isLoggedIn()) {
    redirect('login.php');
}

$currentUser = getCurrentUser();
$flightId = isset($_GET['flight_id']) ? (int)$_GET['flight_id'] : 0;
$error = '';
$success = '';

// =======================================================================
// PHẦN 1: XỬ LÝ POST (KHI NGƯỜI DÙNG BẤM THANH TOÁN)
// =======================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $flightId = (int)$_POST['flight_id'];
    $quantity = (int)$_POST['quantity'];
    $paymentMethod = cleanInput($_POST['payment_method']); // credit_card, bank_transfer, momo...

    if ($quantity < 1) {
        $error = "Số lượng vé không hợp lệ.";
    } else {
        // Bắt đầu Transaction (Quan trọng để đảm bảo tính toàn vẹn dữ liệu)
        // Lưu ý: Nếu lớp db() của bạn không hỗ trợ beginTransaction, hãy dùng $conn->beginTransaction() từ đối tượng PDO gốc
        try {
            // Lấy kết nối PDO gốc để dùng Transaction
            $pdo = db()->getConnection(); // Giả sử hàm db() trả về wrapper, cần lấy PDO object.
            // Nếu db() là PDO object luôn thì dùng $pdo = db();
            
            if (method_exists($pdo, 'beginTransaction')) {
                $pdo->beginTransaction();
            }

            // 1. Kiểm tra lại số ghế trống và giá tiền hiện tại (Lock row để tránh xung đột)
            // Dùng FOR UPDATE để khóa dòng này lại cho đến khi transaction xong
            $stmt = $pdo->prepare("SELECT price, available_seats, flight_code FROM flights WHERE id = ? FOR UPDATE");
            $stmt->execute([$flightId]);
            $flight = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$flight) {
                throw new Exception("Chuyến bay không tồn tại.");
            }

            if ($flight['available_seats'] < $quantity) {
                throw new Exception("Xin lỗi, chỉ còn lại " . $flight['available_seats'] . " ghế trống.");
            }

            // 2. Tính toán tổng tiền
            $totalAmount = $flight['price'] * $quantity;
            $bookingCode = 'BK' . strtoupper(uniqid()); // Tạo mã booking ngẫu nhiên: BK6578A...

            // 3. Tạo đơn hàng trong bảng 'bookings'
            $stmtBooking = $pdo->prepare("
                INSERT INTO bookings (user_id, booking_code, booking_type, total_amount, status, payment_status, payment_method) 
                VALUES (?, ?, 'flight', ?, 'confirmed', 'paid', ?)
            ");
            $stmtBooking->execute([$currentUser['id'], $bookingCode, $totalAmount, $paymentMethod]);
            $bookingId = $pdo->lastInsertId();

            // 4. Lưu chi tiết đơn hàng vào 'booking_details' (Theo file SQL của bạn)
            $stmtDetail = $pdo->prepare("
                INSERT INTO booking_details (booking_id, service_type, service_id, quantity, unit_price, subtotal) 
                VALUES (?, 'flight', ?, ?, ?, ?)
            ");
            $stmtDetail->execute([$bookingId, $flightId, $quantity, $flight['price'], $totalAmount]);

            // 5. Lưu lịch sử thanh toán vào bảng 'payments'
            $stmtPayment = $pdo->prepare("
                INSERT INTO payments (booking_id, amount, payment_method, status, payment_date) 
                VALUES (?, ?, ?, 'completed', NOW())
            ");
            $stmtPayment->execute([$bookingId, $totalAmount, $paymentMethod]);

            // 6. CẬP NHẬT TRỪ SỐ GHẾ TRỐNG (YÊU CẦU CỦA BẠN)
            $newAvailableSeats = $flight['available_seats'] - $quantity;
            $stmtUpdateFlight = $pdo->prepare("UPDATE flights SET available_seats = ? WHERE id = ?");
            $stmtUpdateFlight->execute([$newAvailableSeats, $flightId]);

            // 7. Hoàn tất Transaction
            if (method_exists($pdo, 'commit')) {
                $pdo->commit();
            }

            // Chuyển hướng hoặc báo thành công
            setFlashMessage('success', "Đặt vé thành công! Mã đơn: $bookingCode");
            redirect('qly_booking.php'); // Chuyển về trang quản lý đơn hàng của user

        } catch (Exception $e) {
            // Nếu có lỗi, hoàn tác mọi thay đổi (Rollback)
            if (isset($pdo) && method_exists($pdo, 'rollBack')) {
                $pdo->rollBack();
            }
            $error = "Lỗi xử lý: " . $e->getMessage();
        }
    }
}

// =======================================================================
// PHẦN 2: LẤY DỮ LIỆU ĐỂ HIỂN THỊ RA FORM
// =======================================================================
$flight = db()->select("SELECT f.*, a.name as airline_name FROM flights f JOIN airlines a ON f.airline_id = a.id WHERE f.id = ?", [$flightId]);
if (empty($flight)) {
    die("Không tìm thấy thông tin chuyến bay."); // Hoặc redirect về trang chủ
}
$flight = $flight[0];
?>

<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Xác nhận đặt vé - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
        
        <div class="text-center border-b border-gray-200 pb-6">
            <h2 class="text-2xl font-bold text-gray-900">Xác nhận đặt vé & Thanh toán</h2>
            <p class="mt-2 text-sm text-gray-600">Vui lòng kiểm tra kỹ thông tin trước khi thanh toán.</p>
        </div>

        <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Lỗi!</strong> <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900">Thông tin chuyến bay</h3>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-blue-600"><?php echo htmlspecialchars($flight['flight_code']); ?></span>
                        <span class="text-sm text-gray-500"><?php echo htmlspecialchars($flight['airline_name']); ?></span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div>
                            <p class="font-medium"><?php echo $flight['departure_airport']; ?></p>
                            <p class="text-gray-500"><?php echo date('H:i d/m', strtotime($flight['departure_time'])); ?></p>
                        </div>
                        <div class="text-gray-400">➝</div>
                        <div class="text-right">
                            <p class="font-medium"><?php echo $flight['arrival_airport']; ?></p>
                            <p class="text-gray-500"><?php echo date('H:i d/m', strtotime($flight['arrival_time'])); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Giá vé cơ bản:</span>
                    <span class="font-medium"><?php echo number_format($flight['price'], 0, ',', '.'); ?> VNĐ</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b">
                    <span class="text-gray-600">Số ghế còn trống:</span>
                    <span class="font-medium text-green-600"><?php echo $flight['available_seats']; ?> ghế</span>
                </div>
            </div>

            <form method="POST" action="" class="space-y-6" id="paymentForm">
                <input type="hidden" name="flight_id" value="<?php echo $flight['id']; ?>">
                
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Số lượng hành khách</label>
                    <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $flight['available_seats']; ?>" value="1" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                           onchange="updateTotal()">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Người đặt vé</label>
                    <input type="text" value="<?php echo htmlspecialchars($currentUser['full_name']); ?> (<?php echo htmlspecialchars($currentUser['email']); ?>)" disabled
                           class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phương thức thanh toán</label>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input id="pay_bank" name="payment_method" type="radio" value="bank_transfer" checked class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <label for="pay_bank" class="ml-3 block text-sm font-medium text-gray-700">Chuyển khoản ngân hàng</label>
                        </div>
                        <div class="flex items-center">
                            <input id="pay_card" name="payment_method" type="radio" value="credit_card" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <label for="pay_card" class="ml-3 block text-sm font-medium text-gray-700">Thẻ tín dụng / Ghi nợ quốc tế</label>
                        </div>
                        <div class="flex items-center">
                            <input id="pay_cod" name="payment_method" type="radio" value="cash" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <label for="pay_cod" class="ml-3 block text-sm font-medium text-gray-700">Thanh toán tại văn phòng</label>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Tổng thanh toán:</span>
                        <span class="text-2xl font-bold text-blue-600" id="totalDisplay"><?php echo number_format($flight['price'], 0, ',', '.'); ?> VNĐ</span>
                    </div>
                </div>

                <button type="submit" onclick="return confirm('Xác nhận thanh toán và đặt vé?')" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    THANH TOÁN NGAY
                </button>
                
                <div class="text-center mt-2">
                    <a href="index.php" class="text-sm text-gray-500 hover:text-gray-700">Quay lại tìm kiếm</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Hàm cập nhật tổng tiền bằng JS ngay khi đổi số lượng
    const pricePerTicket = <?php echo $flight['price']; ?>;
    
    function updateTotal() {
        const quantity = document.getElementById('quantity').value;
        const total = quantity * pricePerTicket;
        
        // Format tiền tệ kiểu Việt Nam
        const formatted = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total);
        document.getElementById('totalDisplay').innerText = formatted.replace('₫', 'VNĐ');
    }
</script>
</body>
</html>