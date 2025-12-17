<?php
/**
 * chi_tiet_xe.php
 * Xử lý thuê xe ngay tại trang
 */

require_once '../config/config.php';

// --- XỬ LÝ POST (KHI BẤM ĐẶT XE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'book_vehicle') {
    $currentUser = Auth::getCurrentUser();
    if (!$currentUser) {
        echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='login.php';</script>"; exit;
    }
    
    $vehicle_id = intval($_POST['vehicle_id']);
    $total_price = floatval($_POST['price']); 

    $sql = "INSERT INTO bookings (user_id, item_id, type, total_price, status, created_at) VALUES (?, ?, 'vehicle', ?, 'pending', NOW())";
    db()->execute($sql, [$currentUser['id'], $vehicle_id, $total_price]);

    echo "<script>alert('Yêu cầu thuê xe đã được gửi!'); window.location.href='chi_tiet_xe.php?id=$vehicle_id';</script>";
    exit;
}

// --- HIỂN THỊ ---
$vehicleId = $_GET['id'] ?? 0;
$vehicle = db()->selectOne("SELECT * FROM vehicles WHERE id = ?", [$vehicleId]);
if (!$vehicle) die("Không tìm thấy xe.");

require_once 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title><?php echo htmlspecialchars($vehicle['name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
</head>
<body class="bg-gray-50 text-slate-800">
    <main class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <img src="<?php echo $vehicle['image']; ?>" class="w-full rounded-xl shadow-lg mb-6">
                <h1 class="text-3xl font-bold"><?php echo htmlspecialchars($vehicle['name']); ?></h1>
                <p class="mt-4 text-gray-600"><?php echo nl2br(htmlspecialchars($vehicle['description'])); ?></p>
            </div>

            <div>
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-200 sticky top-10">
                    <p class="text-sm text-gray-500">Giá thuê trọn gói</p>
                    <div class="text-3xl font-black text-blue-600 mb-6">
                        <?php echo number_format($vehicle['price_per_day'], 0, ',', '.'); ?>₫ <span class="text-sm text-gray-400">/ ngày</span>
                    </div>

                    <form method="POST" action="">
                        <input type="hidden" name="action" value="book_vehicle">
                        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['id']; ?>">
                        <input type="hidden" name="price" value="<?php echo $vehicle['price_per_day']; ?>">
                        
                        <button type="submit" onclick="return confirm('Xác nhận thuê chiếc xe này?');" 
                                class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-lg">
                            Liên hệ thuê xe ngay
                        </button>
                    </form>
                    
                    <p class="text-xs text-center text-gray-400 mt-4">Nhân viên sẽ gọi lại xác nhận lịch trình.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>