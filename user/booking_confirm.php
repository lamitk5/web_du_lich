<?php
/**
 * users/booking_confirm.php
 * Xử lý logic đặt phòng Homestay (INSERT vào DB) và thông báo kết quả.
 */

require_once '../config/config.php';
requireLogin(); // Bắt buộc đăng nhập

// Lấy thông tin người dùng đã đăng nhập
$currentUser = getCurrentUser();
$userId = $currentUser['id'];

// --- KIỂM TRA PHƯƠNG THỨC GỬI DỮ LIỆU ---
$data = $_REQUEST; 

// 1. Lấy và kiểm tra các tham số cần thiết
$homestay_id = intval($data['homestay_id'] ?? 0);
$checkInDate = $data['checkin'] ?? '';
$checkOutDate = $data['checkout'] ?? '';
$guests = intval($data['guests'] ?? 1);

// Chuyển hướng quay lại nếu thiếu ID
if ($homestay_id === 0) {
    setFlashMessage('error', 'Không tìm thấy homestay để đặt.');
    redirect('homestay.php');
}

// Lấy thông tin Homestay để tính giá
$homestay = db()->selectOne("SELECT id, price_weekday, max_guests FROM homestays WHERE id = ?", [$homestay_id]);

if (!$homestay) {
    setFlashMessage('error', 'Homestay không hợp lệ.');
    redirect('homestay_detail.php?id=' . $homestay_id);
}

// 2. Kiểm tra tính hợp lệ cơ bản
if (empty($checkInDate) || empty($checkOutDate) || $guests < 1 || $guests > $homestay['max_guests']) {
    setFlashMessage('error', 'Vui lòng điền đầy đủ thông tin ngày nhận/trả phòng và số khách hợp lệ.');
    redirect('homestay_detail.php?id=' . $homestay_id);
}

// 3. Tính toán số đêm và Tổng tiền
try {
    $d1 = new DateTime($checkInDate);
    $d2 = new DateTime($checkOutDate);
    $interval = $d1->diff($d2);
    $nights = $interval->days;

    if ($nights <= 0) {
        setFlashMessage('error', 'Ngày trả phòng phải sau ngày nhận phòng.');
        redirect('homestay_detail.php?id=' . $homestay_id);
    }

    $price_weekday = $homestay['price_weekday'];
    $subTotal = $nights * $price_weekday;
    $serviceFee = round($subTotal * 0.05);
    $totalAmount = $subTotal + $serviceFee;

} catch (Exception $e) {
    setFlashMessage('error', 'Lỗi định dạng ngày tháng.');
    redirect('homestay_detail.php?id=' . $homestay_id);
}


// --- 4. TIẾN HÀNH INSERT VÀO DATABASE ---

// 4.1. Tạo mã Booking duy nhất
$bookingCode = 'HS' . strtoupper(substr(uniqid(), 8)) . rand(100, 999);

// 4.2. INSERT vào bảng bookings
$sqlInsertBooking = "INSERT INTO bookings (user_id, booking_code, booking_type, total_amount, status, payment_status, booking_date) 
                     VALUES (?, ?, 'hotel', ?, 'pending', 'unpaid', NOW())";

$inserted = db()->execute($sqlInsertBooking, 
    [$userId, $bookingCode, $totalAmount]
);

if ($inserted) {
    $bookingId = db()->getLastInsertId();
    
    // 4.3. INSERT vào bảng booking_details
    $sqlDetail = "INSERT INTO booking_details (booking_id, service_type, service_id, quantity, unit_price, subtotal, check_in, check_out)
                   VALUES (?, 'hotel', ?, ?, ?, ?, ?, ?)";
    
    $detailInserted = db()->execute($sqlDetail, [
        $bookingId,
        $homestay_id,
        $guests,
        $price_weekday,
        $subTotal,
        $checkInDate,
        $checkOutDate
    ]);
    
    if ($detailInserted) {
        // THÀNH CÔNG
        logSuccess("New homestay booking confirmed: {$bookingCode}", ['user_id' => $userId, 'homestay_id' => $homestay_id, 'amount' => $totalAmount]);
        setFlashMessage('success', 'Đặt phòng thành công! Mã đặt chỗ của bạn là: **' . $bookingCode . '**. Vui lòng kiểm tra mục Quản lý đặt chỗ.');
        
    } else {
        // LỖI: Chi tiết thất bại, rollback (xóa booking cha)
        db()->delete('bookings', 'id = ?', [$bookingId]);
        logError("Failed to insert booking_details (Homestay ID: {$homestay_id})", ['error' => db()->getLastError()]);
        setFlashMessage('error', 'Lỗi hệ thống khi lưu chi tiết đơn hàng. Đơn hàng bị hủy.');
    }
} else {
    // LỖI: INSERT booking thất bại
    logError("SQL INSERT into Bookings failed for homestay", ['user_id' => $userId, 'sql_error' => db()->getLastError()]);
    setFlashMessage('error', 'Lỗi hệ thống khi tạo đơn hàng. Vui lòng thử lại.');
}

// 5. Chuyển hướng về trang chi tiết homestay để hiển thị thông báo
redirect('homestay_detail.php?id=' . $homestay_id);
exit;
?>