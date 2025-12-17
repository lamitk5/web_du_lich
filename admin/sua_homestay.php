<?php
session_start();
// Tắt báo lỗi Warning rác, chỉ hiện lỗi nghiêm trọng
error_reporting(E_ERROR | E_PARSE);

// 1. KIỂM TRA QUYỀN ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Bạn cần đăng nhập quyền Admin! <a href='login.php'>Đăng nhập</a>");
}

require_once 'config/db.php'; 

// Lấy ID homestay cần sửa
if (!isset($_GET['id'])) {
    die("Không tìm thấy ID Homestay cần sửa.");
}
$id = intval($_GET['id']);

// 2. LẤY THÔNG TIN CŨ
$result = $conn->query("SELECT * FROM homestays WHERE id = $id");
if ($result->num_rows == 0) {
    die("Homestay không tồn tại.");
}
$row = $result->fetch_assoc();

$message = "";
$error = "";

// 3. XỬ LÝ CẬP NHẬT
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $address = $conn->real_escape_string($_POST['address']);
    $district = $conn->real_escape_string($_POST['district']);
    $price_weekday = intval($_POST['price_weekday']);
    $price_weekend = intval($_POST['price_weekend']);
    $max_guests = intval($_POST['max_guests']);
    $description = $conn->real_escape_string($_POST['description']);

    // --- HÀM UPLOAD ĐÃ SỬA LỖI ---
    function processImage($inputName, $currentImage) {
        // Định nghĩa thư mục upload ngay đầu hàm
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

        // Nếu người dùng CHỌN file mới
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == 0) {
            $ext = pathinfo($_FILES[$inputName]["name"], PATHINFO_EXTENSION);
            $new_name = "update_" . time() . "_" . rand(100,999) . "." . $ext;
            
            if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $target_dir . $new_name)) {
                return $target_dir . $new_name; // Trả về ảnh MỚI (vd: uploads/anh.jpg)
            }
        }
        // Nếu KHÔNG chọn file mới -> Trả về ảnh CŨ
        return $currentImage;
    }

    $img1 = processImage('main_image', $row['main_image']);
    $img2 = processImage('extra_image_1', $row['extra_image_1']);
    $img3 = processImage('extra_image_2', $row['extra_image_2']);

    $sql = "UPDATE homestays SET 
            name='$name', address='$address', district='$district', 
            price_weekday='$price_weekday', price_weekend='$price_weekend', 
            max_guests='$max_guests', description='$description',
            main_image='$img1', extra_image_1='$img2', extra_image_2='$img3'
            WHERE id=$id";

    if ($conn->query($sql)) {
        $message = "✅ Đã cập nhật thành công!";
        // Refresh dữ liệu để hiện ảnh mới
        $row = $conn->query("SELECT * FROM homestays WHERE id=$id")->fetch_assoc();
    } else {
        $error = "❌ Lỗi SQL: " . $conn->error;
    }
}

// Hàm hỗ trợ hiển thị ảnh trong form
function showImagePreview($path) {
    if (empty($path)) {
        echo '<div class="h-24 w-full bg-gray-100 flex items-center justify-center text-xs text-gray-400 border rounded">Chưa có ảnh</div>';
    } else {
        // Xử lý hiển thị ảnh cũ
        $displayPath = (strpos($path, 'http') === 0 || strpos($path, 'uploads/') === 0) ? $path : 'uploads/' . $path;
        echo '<img src="' . $displayPath . '" class="h-24 w-full object-cover rounded border border-gray-300">';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <title>Sửa Homestay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700&display=swap" rel="stylesheet"/>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h1 class="text-2xl font-black text-gray-800">Sửa Homestay #<?php echo $id; ?></h1>
            <a href="quanly_homestay.php" class="text-gray-500 font-bold hover:text-blue-600 transition">← Quay lại danh sách</a>
        </div>

        <?php if($message): ?>
            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6 font-bold border border-green-200 flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-6 font-bold border border-red-200">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tên Homestay</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" class="w-full border p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Khu vực</label>
                    <input type="text" name="district" value="<?php echo htmlspecialchars($row['district']); ?>" class="w-full border p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Giá ngày thường</label>
                    <input type="number" name="price_weekday" value="<?php echo $row['price_weekday']; ?>" class="w-full border p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Giá cuối tuần</label>
                    <input type="number" name="price_weekend" value="<?php echo $row['price_weekend']; ?>" class="w-full border p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Số khách tối đa</label>
                    <input type="number" name="max_guests" value="<?php echo $row['max_guests']; ?>" class="w-full border p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Địa chỉ chi tiết</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" class="w-full border p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                <h3 class="font-bold text-blue-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Quản lý hình ảnh
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase text-gray-500">1. Ảnh Đại diện (Chính)</label>
                        <?php showImagePreview($row['main_image']); ?>
                        <input type="file" name="main_image" class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-white file:text-blue-700 hover:file:bg-blue-50 cursor-pointer"/>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase text-gray-500">2. Ảnh Phụ 1</label>
                        <?php showImagePreview($row['extra_image_1']); ?>
                        <input type="file" name="extra_image_1" class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-white file:text-blue-700 hover:file:bg-blue-50 cursor-pointer"/>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase text-gray-500">3. Ảnh Phụ 2</label>
                        <?php showImagePreview($row['extra_image_2']); ?>
                        <input type="file" name="extra_image_2" class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-white file:text-blue-700 hover:file:bg-blue-50 cursor-pointer"/>
                    </div>
                </div>
                <p class="text-xs text-blue-400 mt-4 italic">* Nếu không chọn ảnh mới, hệ thống sẽ giữ lại ảnh cũ.</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Mô tả chi tiết</label>
                <textarea name="description" rows="5" class="w-full border p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"><?php echo htmlspecialchars($row['description']); ?></textarea>
            </div>

            <button type="submit" class="w-full bg-gray-900 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-blue-600 transition-all transform hover:-translate-y-1">
                LƯU THAY ĐỔI
            </button>
        </form>
    </div>
</body>
</html>