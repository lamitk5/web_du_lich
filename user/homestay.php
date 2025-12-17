<?php
/**
 * homestay.php
 */

// 1. KẾT NỐI CONFIG & AUTH
require_once '../config/config.php';

// 2. LẤY THÔNG TIN NGƯỜI DÙNG HIỆN TẠI
$currentUser = Auth::getCurrentUser();
$is_logged_in = ($currentUser !== null);

// Xử lý hiển thị thông tin user
$fullname = $currentUser['full_name'] ?? 'Khách';
$role = $currentUser['role'] ?? 'user';
$avatar_raw = $currentUser['avatar'] ?? '';

// Xử lý đường dẫn ảnh đại diện
if (!empty($avatar_raw)) {
    if (strpos($avatar_raw, 'http') === 0 || strpos($avatar_raw, 'uploads/') === 0) {
        $user_avatar = $avatar_raw;
    } else {
        $user_avatar = "uploads/avatars/" . $avatar_raw;
    }
} else {
    $user_avatar = "https://www.svgrepo.com/show/452030/avatar-default.svg";
}

// 3. XỬ LÝ YÊU THÍCH
$liked_homestays = [];
if ($is_logged_in) {
    $u_id = $currentUser['id'];
    // Lấy danh sách ID các homestay đã like
    $sql_likes = "SELECT homestay_id FROM wishlists WHERE user_id = ?";
    $res_likes = db()->select($sql_likes, [$u_id]);
    foreach($res_likes as $l) {
        $liked_homestays[] = $l['homestay_id'];
    }
}

// 4. THÔNG BÁO
$unread_count = 0;
if ($is_logged_in) {
    // Đếm thông báo chưa đọc (is_read = 0)
    $unread_count = db()->count('user_notifications', "user_id = ? AND is_read = 0", [$currentUser['id']]);
}

// 5. XỬ LÝ TÌM KIẾM & LẤY DỮ LIỆU
$params = [];
$where_clauses = ["1=1"]; // Mặc định lấy hết

// Lọc theo địa điểm (Tìm trong Tên, Quận hoặc Địa chỉ)
if (isset($_GET['location']) && !empty($_GET['location'])) {
    $location = trim($_GET['location']);
    $where_clauses[] = "(name LIKE ? OR district LIKE ? OR address LIKE ?)";
    $searchTerm = "%$location%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    
    // Lưu lịch sử tìm kiếm vào session
    $_SESSION['last_search'] = ['location' => $location, 'guests' => $_GET['guests'] ?? 2];
}

// Lọc theo số lượng khách
if (isset($_GET['guests']) && !empty($_GET['guests'])) {
    $guests = intval($_GET['guests']);
    $where_clauses[] = "max_guests >= ?";
    $params[] = $guests;
}

// Ghép câu lệnh SQL
$sql_where = implode(' AND ', $where_clauses);

// Query lấy dữ liệu từ bảng HOMESTAYS
$sql = "SELECT * FROM homestays WHERE $sql_where ORDER BY created_at DESC";

// Thực thi query thông qua class Database (hàm select)
$result = db()->select($sql, $params);
require_once 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Trang chủ - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { vertical-align: middle; }
        .slide { 
            position: absolute; inset: 0; width: 100%; height: 100%; 
            background-size: cover; background-position: center; 
            transition: opacity 1.5s ease-in-out; 
        }
        @keyframes pulse-logo {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .logo-pulse { animation: pulse-logo 0.8s infinite; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <div class="relative h-[500px] w-full overflow-hidden group bg-gray-900">
        <div id="slider-container" class="absolute inset-0 w-full h-full">
            <div class="slide opacity-100 z-10" style="background-image: url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=1920');"></div>
            <div class="slide opacity-0 z-0" style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1920');"></div>
            <div class="slide opacity-0 z-0" style="background-image: url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=1920');"></div>
        </div>
        <div class="absolute inset-0 bg-black/40 z-20 pointer-events-none"></div>
        
        <div class="absolute inset-0 flex flex-col items-center justify-center px-4 text-center z-30">
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 drop-shadow-xl tracking-tight">
                Tìm kiếm chốn dừng chân hoàn hảo
            </h1>
            <p class="text-base md:text-xl text-gray-100 font-medium mb-10 drop-shadow-md max-w-2xl">
                Khám phá hàng ngàn homestay độc đáo tại Hà Nội và vùng lân cận.
            </p>

            <form action="homestay.php" method="GET" class="bg-white p-2 rounded-full shadow-2xl max-w-4xl w-full flex flex-col md:flex-row gap-2 items-center relative z-40 pl-6">
                <div class="flex-1 w-full text-left border-b md:border-b-0 md:border-r border-gray-200 py-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Địa điểm</label>
                    <input type="text" id="location-input" name="location" class="w-full outline-none text-gray-900 font-bold placeholder-gray-300" placeholder="Tây Hồ, Hoàn Kiếm, Cầu Giấy..." value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
                </div>
                <div class="w-full md:w-40 text-left border-b md:border-b-0 md:border-r border-gray-200 py-2 px-4">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Ngày đến</label>
                    <input type="date" class="w-full outline-none text-gray-700 font-medium cursor-pointer bg-transparent text-sm">
                </div>
                <div class="w-full md:w-40 text-left py-2 px-4">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Số khách</label>
                    <input type="number" name="guests" min="1" class="w-full outline-none text-gray-900 font-bold placeholder-gray-300" placeholder="Ví dụ: 2" value="<?php echo isset($_GET['guests']) ? htmlspecialchars($_GET['guests']) : ''; ?>">
                </div>
                <button type="submit" class="bg-[#13ecc8] hover:bg-[#10d4b4] text-white rounded-full px-8 py-3 font-bold text-lg shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">search</span> Tìm
                </button>
            </form>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 py-16 w-full flex-grow">
        
        <?php if(isset($_SESSION['last_search'])): ?>
        <div class="mb-10">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Tìm kiếm gần đây</h2>
            <a href="homestay.php?location=<?php echo urlencode($_SESSION['last_search']['location']); ?>&guests=<?php echo $_SESSION['last_search']['guests']; ?>" 
               class="inline-flex items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition cursor-pointer min-w-[300px]">
                <div class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center text-gray-400">
                    <span class="material-symbols-outlined text-3xl">history</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-gray-900 text-lg"><?php echo htmlspecialchars($_SESSION['last_search']['location']); ?></span>
                    <span class="text-sm text-gray-500">
                        <?php echo $_SESSION['last_search']['guests']; ?> khách · Tìm lại
                    </span>
                </div>
            </a>
        </div>
        <?php endif; ?>

        <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">
                <?php echo (isset($_GET['location']) && !empty($_GET['location'])) ? 'Kết quả cho "' . htmlspecialchars($_GET['location']) . '"' : 'Điểm đến nổi bật'; ?>
            </h2>
             <div class="flex flex-wrap gap-2">
                <a href="homestay.php" class="px-4 py-2 bg-black text-white border border-black rounded-full text-sm font-bold shadow-sm transition">Tất cả</a>
                <a href="homestay.php?location=Tây Hồ" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium hover:border-[#13ecc8] hover:text-[#13ecc8] transition">Tây Hồ</a>
                <a href="homestay.php?location=Hoàn Kiếm" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium hover:border-[#13ecc8] hover:text-[#13ecc8] transition">Hoàn Kiếm</a>
                <a href="homestay.php?location=Sóc Sơn" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm font-medium hover:border-[#13ecc8] hover:text-[#13ecc8] transition">Sóc Sơn</a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php 
            if (count($result) > 0):
                foreach($result as $row): 
                    $is_liked = in_array($row['id'], $liked_homestays);
                    $tooltip = $is_liked ? "Bỏ thích" : "Thêm vào yêu thích";

                    // Xử lý ảnh: Bảng homestays dùng cột 'main_image'
                    $img_src = $row['main_image'];
                    if (empty($img_src)) {
                        $img_src = 'https://placehold.co/600x400?text=No+Image';
                    } elseif (!filter_var($img_src, FILTER_VALIDATE_URL)) {
                        $img_src = 'uploads/' . $img_src;
                    }
                    // Xử lý giá: Cột 'price_weekday'
                    $display_price = $row['price_weekday'] > 0 ? number_format($row['price_weekday'], 0, ',', '.') . '₫' : 'Liên hệ';
                    
                    // Xử lý sức chứa: Cột 'max_guests'
                    $capacity = $row['max_guests'] ? $row['max_guests'] : 2;
            ?>
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 group border border-gray-100 flex flex-col h-full overflow-hidden relative">
                
                <a href="homestay_detail.php?id=<?php echo $row['id']; ?>" class="block relative h-64 overflow-hidden">
                    <img src="<?php echo $img_src; ?>" 
                         onerror="this.src='https://placehold.co/600x400?text=Error'"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold shadow-sm flex items-center gap-1 text-gray-800">
                        <span class="material-symbols-outlined text-[16px] text-red-500">location_on</span>
                        <?php echo htmlspecialchars($row['district']); ?>
                    </div>
                </a>
                
                <button onclick="toggleHeart(this, <?php echo $row['id']; ?>)" 
                   class="absolute top-3 right-3 p-2 rounded-full backdrop-blur-md transition z-10 group/heart 
                   <?php echo $is_liked ? 'bg-white text-red-500 shadow-md' : 'bg-black/20 text-white hover:bg-white hover:text-red-500'; ?>" 
                   title="<?php echo $tooltip; ?>">
                    <span class="material-symbols-outlined block" style="<?php echo $is_liked ? "font-variation-settings: 'FILL' 1;" : ""; ?>">
                        <?php echo $is_liked ? 'favorite' : 'favorite_border'; ?>
                    </span>
                </button>

                <div class="p-5 flex flex-col flex-1">
                    <h3 class="font-bold text-lg text-gray-900 line-clamp-2 leading-tight mb-1">
                        <a href="homestay_detail.php?id=<?php echo $row['id']; ?>" class="hover:text-[#13ecc8] transition"><?php echo htmlspecialchars($row['name']); ?></a>
                    </h3>
                    <p class="text-gray-500 text-xs mb-3 line-clamp-1"><?php echo htmlspecialchars($row['address']); ?></p>
                    
                    <div class="flex items-center gap-4 text-xs text-gray-500 font-medium mb-4 pt-3 border-t border-dashed border-gray-100 mt-auto">
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[18px]">group</span> 
                            Tối đa <?php echo $capacity; ?> khách
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-end">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-gray-400 font-bold uppercase">Giá ngày thường</span>
                            <div class="text-[#13ecc8] font-black text-xl"><?php echo $display_price; ?></div>
                        </div>
                        <a href="homestay_detail.php?id=<?php echo $row['id']; ?>" class="bg-gray-900 text-white p-2 rounded-lg hover:bg-[#13ecc8] transition shadow-lg">
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; else: ?>
                <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-dashed border-gray-300">
                    <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">search_off</span>
                    <p class="text-xl font-bold text-gray-600">Không tìm thấy homestay nào.</p>
                    <p class="text-gray-400 mt-2">Hãy thử tìm kiếm khu vực khác xem sao!</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-gray-900 text-white py-12 mt-auto text-center">
        <p class="text-gray-500 text-sm">© <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
    </footer>

    <script>
        // 1. SLIDER
        const slides = document.querySelectorAll('.slide');
        let currentSlide = 0;
        function nextSlide() {
            slides[currentSlide].classList.remove('opacity-100', 'z-10');
            slides[currentSlide].classList.add('opacity-0', 'z-0');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.remove('opacity-0', 'z-0');
            slides[currentSlide].classList.add('opacity-100', 'z-10');
        }
        setInterval(nextSlide, 5000);

        // 2. TOGGLE HEART (Xử lý AJAX Thả tim)
        function toggleHeart(btn, homestayId) {
            // Check login (biến này được in ra từ PHP)
            const isLoggedIn = <?php echo $is_logged_in ? 'true' : 'false'; ?>;
            
            if (!isLoggedIn) {
                alert('Vui lòng đăng nhập để lưu homestay yêu thích!');
                window.location.href = 'login.php';
                return;
            }

            // Gửi request AJAX
            // Đây là phần giao diện người dùng
            const iconSpan = btn.querySelector('span');
            const isLiked = btn.classList.contains('bg-white');

            if (isLiked) {
                // Đang like -> Bỏ like
                btn.className = 'absolute top-3 right-3 p-2 rounded-full backdrop-blur-md transition z-10 group/heart bg-black/20 text-white hover:bg-white hover:text-red-500';
                iconSpan.innerText = 'favorite_border';
                iconSpan.style.fontVariationSettings = "";
                btn.title = "Thêm vào yêu thích";
                
                // Fetch API remove...
                fetch('api/wishlist.php?action=remove&id=' + homestayId);
            } else {
                // Chưa like -> Like
                btn.className = 'absolute top-3 right-3 p-2 rounded-full backdrop-blur-md transition z-10 group/heart bg-white text-red-500 shadow-md';
                iconSpan.innerText = 'favorite';
                iconSpan.style.fontVariationSettings = "'FILL' 1";
                btn.title = "Bỏ thích";
                
                // Fetch API add...
                fetch('api/wishlist.php?action=add&id=' + homestayId);
            }
        }
    </script>
</body>
</html>