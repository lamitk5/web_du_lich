<?php
/**
 * Quản lý Homestay - Full Option (Thêm/Sửa/Xóa tại chỗ)
 */

require_once '../config/config.php'; 

if (!function_exists('requireAdmin')) {
    /**
     * Kiểm tra người dùng đã đăng nhập và có quyền Admin chưa.
     * Nếu không, chuyển hướng về trang đăng nhập hoặc trang báo lỗi.
     */
    function requireAdmin() {
        if (!Auth::isLoggedIn() || !Auth::isAdmin()) {
            // Chuyển hướng về trang đăng nhập/trang chủ người dùng
            header('Location: ../login.php'); 
            exit;
        }
    }
}
// --- 1. XỬ LÝ LOGIC: THÊM & SỬA & XÓA ---

// Xử lý Form Submit (Thêm hoặc Sửa)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    // --- LOGIC THÊM MỚI ---
    if ($_POST['action'] === 'add_homestay') {
        $name = $_POST['name'];
        $district = $_POST['district'];
        $address = $_POST['address'];
        $price_weekday = $_POST['price_weekday'];
        $price_weekend = $_POST['price_weekend'];
        $max_guests = $_POST['max_guests'];
        $description = $_POST['description'];
        
        // Upload ảnh
        $main_image = $_POST['image_url'] ?? '';
        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
            $ext = pathinfo($_FILES["main_image"]["name"], PATHINFO_EXTENSION);
            $new_filename = "home_" . time() . "." . $ext;
            if (move_uploaded_file($_FILES["main_image"]["tmp_name"], $target_dir . $new_filename)) {
                $main_image = $new_filename;
            }
        }

        $stmt = $conn->prepare("INSERT INTO homestays (name, district, address, price_weekday, price_weekend, max_guests, description, main_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssddiss", $name, $district, $address, $price_weekday, $price_weekend, $max_guests, $description, $main_image);
        $stmt->execute();
        header("Location: quanly_homestay.php?success=add");
        exit();
    }

    // --- LOGIC SỬA ---
    elseif ($_POST['action'] === 'edit_homestay') {
        $id = intval($_POST['id']);
        $name = $_POST['name'];
        $district = $_POST['district'];
        $address = $_POST['address'];
        $price_weekday = $_POST['price_weekday'];
        $price_weekend = $_POST['price_weekend'];
        $max_guests = $_POST['max_guests'];
        $description = $_POST['description'];
        
        // Mặc định giữ ảnh cũ
        $main_image = $_POST['current_main_image']; 

        // Nếu có upload ảnh mới
        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
            $ext = pathinfo($_FILES["main_image"]["name"], PATHINFO_EXTENSION);
            $new_filename = "home_" . time() . "_" . rand(100,999) . "." . $ext;
            if (move_uploaded_file($_FILES["main_image"]["tmp_name"], $target_dir . $new_filename)) {
                $main_image = $new_filename; // Cập nhật tên ảnh mới
            }
        } elseif (!empty($_POST['image_url'])) {
            // Nếu người dùng nhập URL mới
            $main_image = $_POST['image_url'];
        }

        $stmt = $conn->prepare("UPDATE homestays SET name=?, district=?, address=?, price_weekday=?, price_weekend=?, max_guests=?, description=?, main_image=? WHERE id=?");
        $stmt->bind_param("sssddissi", $name, $district, $address, $price_weekday, $price_weekend, $max_guests, $description, $main_image, $id);
        $stmt->execute();
        header("Location: quanly_homestay.php?success=edit");
        exit();
    }
}

// Xử lý Xóa
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM homestays WHERE id=$id");
    header("Location: quanly_homestay.php?success=delete");
    exit();
}

// --- 2. LẤY DỮ LIỆU HIỂN THỊ ---
$filter_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT 
            h.*, 
            COUNT(bd.id) as current_guests_count,
            GROUP_CONCAT(CONCAT(u.full_name, ' (', u.phone, ')') SEPARATOR ', ') as guests_names
        FROM homestays h
        LEFT JOIN booking_details bd ON h.id = bd.service_id 
            AND bd.service_type = 'hotel'
            AND bd.check_in <= '$filter_date' 
            AND bd.check_out > '$filter_date'
        LEFT JOIN bookings b ON bd.booking_id = b.id AND b.status = 'confirmed'
        LEFT JOIN users u ON b.user_id = u.id
        WHERE (h.name LIKE '%$search_keyword%' OR h.district LIKE '%$search_keyword%')
        GROUP BY h.id
        ORDER BY h.id DESC";

$result = $conn->query($sql);
$homestays_data = [];
$stats = ['empty' => 0, 'partial' => 0, 'full' => 0];

if ($result) {
    while($row = $result->fetch_assoc()) {
        $capacity = isset($row['max_guests']) ? $row['max_guests'] : 2; 
        $guests = $row['current_guests_count']; 
        if ($guests == 0) $stats['empty']++;
        elseif ($guests >= $capacity) $stats['full']++; 
        else $stats['partial']++;
        $homestays_data[] = $row;
    }
}
$total_homestays = count($homestays_data);
$pageTitle = 'Quản lý Homestay';
?>
<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo $pageTitle; ?> - Admin</title>
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
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="flex w-full min-h-screen">
    <?php include 'components/sidebar.php'; ?>

    <main class="flex-1 flex flex-col relative">
        <?php include 'components/header.php'; ?>

        <div class="flex-1 overflow-y-auto p-6 md:p-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Danh sách Homestay</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                        Ngày: <span class="font-bold text-primary bg-primary/10 px-2 py-0.5 rounded"><?php echo date('d/m/Y', strtotime($filter_date)); ?></span>
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <form method="GET" class="flex flex-1 sm:flex-none bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm h-11 focus-within:ring-2 focus-within:ring-primary/20 transition-shadow">
                        <div class="flex items-center border-r border-gray-200 dark:border-gray-700 px-3 bg-gray-50 dark:bg-gray-800">
                            <span class="material-symbols-outlined text-gray-400 text-sm">calendar_month</span>
                            <input type="date" name="date" value="<?php echo $filter_date; ?>" class="border-none focus:ring-0 text-sm text-gray-700 dark:text-gray-200 font-medium bg-transparent outline-none w-auto cursor-pointer">
                        </div>
                        <div class="flex items-center px-3 flex-1 w-full sm:w-64 bg-white dark:bg-gray-900">
                            <input type="text" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="Tìm tên homestay..." class="border-none focus:ring-0 text-sm text-gray-700 dark:text-gray-200 w-full outline-none bg-transparent placeholder-gray-400">
                        </div>
                        <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-4 font-bold transition flex items-center justify-center">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                    </form>
                    
                    <button onclick="toggleModal('addModal')" class="h-11 bg-primary hover:bg-blue-600 text-white px-4 rounded-xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20 transition whitespace-nowrap cursor-pointer">
                        <span class="material-symbols-outlined">add</span> 
                        <span class="hidden sm:inline">Thêm Mới</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-900 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-2"><span class="material-symbols-outlined text-blue-600 dark:text-blue-400">home_work</span></div>
                        <p class="text-xs font-bold text-gray-500 uppercase">Tổng cộng</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white ml-1"><?php echo $total_homestays; ?></p>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-2"><span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span></div>
                        <p class="text-xs font-bold text-gray-500 uppercase">Còn trống</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white ml-1"><?php echo $stats['empty']; ?></p>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="bg-yellow-100 dark:bg-yellow-900/30 rounded-lg p-2"><span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">group</span></div>
                        <p class="text-xs font-bold text-gray-500 uppercase">Đang ở</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white ml-1"><?php echo $stats['partial']; ?></p>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-2"><span class="material-symbols-outlined text-red-600 dark:text-red-400">lock</span></div>
                        <p class="text-xs font-bold text-gray-500 uppercase">Hết phòng</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white ml-1"><?php echo $stats['full']; ?></p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="p-4 font-bold text-gray-600 dark:text-gray-400 uppercase text-xs">Homestay</th>
                                <th class="p-4 font-bold text-gray-600 dark:text-gray-400 uppercase text-xs text-center">Sức chứa</th>
                                <th class="p-4 font-bold text-gray-600 dark:text-gray-400 uppercase text-xs text-center">Trạng thái</th>
                                <th class="p-4 font-bold text-gray-600 dark:text-gray-400 uppercase text-xs text-center">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <?php if(empty($homestays_data)): ?>
                                <tr><td colspan="4" class="p-8 text-center text-gray-500">Không tìm thấy homestay.</td></tr>
                            <?php else: ?>
                                <?php foreach($homestays_data as $row): 
                                    $capacity = $row['max_guests'] ?? 2;
                                    $img_src = $row['main_image'];
                                    if (!filter_var($img_src, FILTER_VALIDATE_URL)) { $img_src = "uploads/" . $img_src; }
                                ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition group">
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="relative w-12 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100">
                                                <img src="<?php echo $img_src; ?>" onerror="this.src='https://placehold.co/100x100?text=No+Img'" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900 dark:text-white group-hover:text-primary transition"><?php echo htmlspecialchars($row['name']); ?></p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 font-mono mt-0.5">
                                                    ID: <?php echo $row['id']; ?> • <?php echo htmlspecialchars($row['district']); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-xs border border-gray-200 dark:border-gray-700"><?php echo $capacity; ?> người</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <?php if ($row['current_guests_count'] == 0): ?>
                                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 px-2 py-1 rounded-full text-xs font-bold"><span class="material-symbols-outlined text-[16px]">check_circle</span> Trống</span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 px-2 py-1 rounded-full text-xs font-bold" title="<?php echo htmlspecialchars($row['guests_names']); ?>"><span class="material-symbols-outlined text-[16px]">lock</span> Đang thuê</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button onclick='openEditModal(this)' 
                                                data-id="<?php echo $row['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                                data-district="<?php echo htmlspecialchars($row['district']); ?>"
                                                data-address="<?php echo htmlspecialchars($row['address']); ?>"
                                                data-price-weekday="<?php echo $row['price_weekday']; ?>"
                                                data-price-weekend="<?php echo $row['price_weekend']; ?>"
                                                data-max-guests="<?php echo $row['max_guests']; ?>"
                                                data-description="<?php echo htmlspecialchars($row['description']); ?>"
                                                data-image="<?php echo $row['main_image']; ?>"
                                                class="p-2 bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 rounded-lg hover:bg-blue-100 transition cursor-pointer">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </button>
                                            
                                            <button onclick="openDeleteModal(<?php echo $row['id']; ?>)" class="p-2 bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 rounded-lg hover:bg-red-100 transition cursor-pointer">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div id="addModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" onclick="toggleModal('addModal')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-200 dark:border-gray-700">
                    
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">add_home</span> Thêm Homestay Mới
                        </h3>
                        <button onclick="toggleModal('addModal')" class="text-gray-400 hover:text-gray-500 transition"><span class="material-symbols-outlined">close</span></button>
                    </div>

                    <form action="" method="POST" enctype="multipart/form-data" class="p-6">
                        <input type="hidden" name="action" value="add_homestay">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div><label class="block text-sm font-medium mb-1 dark:text-white">Tên Homestay *</label><input type="text" name="name" required class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></div>
                            <div><label class="block text-sm font-medium mb-1 dark:text-white">Tỉnh / Thành phố *</label><input type="text" name="district" required class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></div>
                        </div>
                        <div class="mb-4"><label class="block text-sm font-medium mb-1 dark:text-white">Quận / Huyện </label><input type="text" name="address" class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></div>
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div><label class="block text-sm font-medium mb-1 dark:text-white">Giá ngày thường</label><input type="number" name="price_weekday" required class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></div>
                            <div><label class="block text-sm font-medium mb-1 dark:text-white">Giá cuối tuần</label><input type="number" name="price_weekend" required class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></div>
                            <div><label class="block text-sm font-medium mb-1 dark:text-white">Số khách</label><input type="number" name="max_guests" value="2" class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></div>
                        </div>
                        <div class="mb-4"><label class="block text-sm font-medium mb-1 dark:text-white">Mô tả</label><textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></textarea></div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1 dark:text-white">Hình ảnh</label>
                            <input type="file" name="main_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                            <input type="text" name="image_url" placeholder="Hoặc URL ảnh..." class="mt-2 w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm">
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" onclick="toggleModal('addModal')" class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium">Hủy</button>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold shadow-lg">Lưu Mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" onclick="toggleModal('editModal')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-200 dark:border-gray-700">
                    
                    <div class="bg-blue-50 dark:bg-blue-900/20 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-blue-100 dark:border-blue-800">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600">edit_square</span> Cập Nhật Homestay
                        </h3>
                        <button onclick="toggleModal('editModal')" class="text-gray-400 hover:text-gray-500 transition"><span class="material-symbols-outlined">close</span></button>
                    </div>

                    <form action="" method="POST" enctype="multipart/form-data" class="p-6">
                        <input type="hidden" name="action" value="edit_homestay">
                        <input type="hidden" name="id" id="edit_id">
                        <input type="hidden" name="current_main_image" id="edit_current_image_val">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div><label class="block text-sm font-medium mb-1 dark:text-white">Tên Homestay *</label><input type="text" name="name" id="edit_name" required class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></div>
                            <div><label class="block text-sm font-medium mb-1 dark:text-white">Tỉnh/Thành phố *</label><input type="text" name="district" id="edit_district" required class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></div>
                        </div>
                        <div class="mb-4"><label class="block text-sm font-medium mb-1 dark:text-white">Quận/Huyện</label><input type="text" name="address" id="edit_address" class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></div>
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div><label class="block text-sm font-medium mb-1 dark:text-white">Giá ngày thường</label><input type="number" name="price_weekday" id="edit_price_weekday" required class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></div>
                            <div><label class="block text-sm font-medium mb-1 dark:text-white">Giá cuối tuần</label><input type="number" name="price_weekend" id="edit_price_weekend" required class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></div>
                            <div><label class="block text-sm font-medium mb-1 dark:text-white">Số khách</label><input type="number" name="max_guests" id="edit_max_guests" class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></div>
                        </div>
                        <div class="mb-4"><label class="block text-sm font-medium mb-1 dark:text-white">Mô tả</label><textarea name="description" id="edit_description" rows="3" class="w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm"></textarea></div>
                        
                        <div class="mb-4 grid grid-cols-1 sm:grid-cols-[100px_1fr] gap-4 items-center">
                            <div class="h-20 w-24 rounded border overflow-hidden bg-gray-100">
                                <img id="edit_image_preview" src="" class="w-full h-full object-cover" onerror="this.style.display='none'">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-white">Thay đổi ảnh (Nếu cần)</label>
                                <input type="file" name="main_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                <input type="text" name="image_url" placeholder="Hoặc nhập URL mới..." class="mt-2 w-full rounded-lg border-gray-300 dark:bg-gray-900 text-sm">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" onclick="toggleModal('editModal')" class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium">Hủy</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold shadow-lg">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" onclick="toggleModal('deleteModal')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-200 dark:border-gray-700 p-6">
                    <div class="text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                            <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-2xl">warning</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Xóa Homestay này?</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Hành động này không thể hoàn tác.</p>
                    </div>
                    <div class="mt-6 flex justify-center gap-3">
                        <button type="button" onclick="toggleModal('deleteModal')" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50">Hủy</button>
                        <a id="confirmDeleteBtn" href="#" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 shadow-lg shadow-red-500/30">Xóa ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    function openDeleteModal(id) {
        const link = document.getElementById('confirmDeleteBtn');
        link.href = "quanly_homestay.php?delete=" + id;
        toggleModal('deleteModal');
    }

    // Hàm điền dữ liệu vào form Edit
    function openEditModal(button) {
        // Lấy data từ attribute của nút bấm
        document.getElementById('edit_id').value = button.getAttribute('data-id');
        document.getElementById('edit_name').value = button.getAttribute('data-name');
        document.getElementById('edit_district').value = button.getAttribute('data-district');
        document.getElementById('edit_address').value = button.getAttribute('data-address');
        document.getElementById('edit_price_weekday').value = button.getAttribute('data-price-weekday');
        document.getElementById('edit_price_weekend').value = button.getAttribute('data-price-weekend');
        document.getElementById('edit_max_guests').value = button.getAttribute('data-max-guests');
        document.getElementById('edit_description').value = button.getAttribute('data-description');
        
        // Xử lý ảnh
        const imgSrc = button.getAttribute('data-image');
        document.getElementById('edit_current_image_val').value = imgSrc;
        
        const preview = document.getElementById('edit_image_preview');
        if (imgSrc) {
            preview.style.display = 'block';
            preview.src = (imgSrc.startsWith('http') || imgSrc.startsWith('uploads/')) ? imgSrc : 'uploads/' + imgSrc;
        } else {
            preview.style.display = 'none';
        }

        toggleModal('editModal');
    }

    // Đóng modal khi nhấn ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            document.querySelectorAll('[id$="Modal"]').forEach(el => el.classList.add('hidden'));
        }
    });
</script>

</body>
</html>