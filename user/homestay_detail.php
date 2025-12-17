<?php
/**
 * homestay_detail.php
 * Hiển thị chi tiết homestay và form đặt phòng
 */

// 1. KẾT NỐI CONFIG & AUTH
require_once '../config/config.php';

// 2. LẤY THÔNG TIN USER
$currentUser = Auth::getCurrentUser();
$is_logged_in = ($currentUser !== null);

// 3. KIỂM TRA ID HOMESTAY
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: homestay.php");
    exit;
}

$homestay_id = intval($_GET['id']);

// 4. LẤY DỮ LIỆU HOMESTAY TỪ DB
$sql = "SELECT * FROM homestays WHERE id = ?";
$homestay = null;
$result = db()->select($sql, [$homestay_id]);

if (count($result) > 0) {
    $homestay = $result[0];
} else {
    die("Không tìm thấy homestay này."); 
}

// 5. KIỂM TRA TRẠNG THÁI YÊU THÍCH
$is_liked = false;
if ($is_logged_in) {
    $check_like = db()->select("SELECT * FROM wishlists WHERE user_id = ? AND homestay_id = ?", [$currentUser['id'], $homestay_id]);
    if (count($check_like) > 0) {
        $is_liked = true;
    }
}

// 6. XỬ LÝ DỮ LIỆU HIỂN THỊ
// Ảnh:
$img_src = $homestay['main_image'];
if (empty($img_src)) $img_src = 'https://placehold.co/800x600?text=No+Image';
elseif (!filter_var($img_src, FILTER_VALIDATE_URL)) $img_src = 'uploads/' . $img_src;

// Giá:
$price_weekday = $homestay['price_weekday'];
$price_weekend = $homestay['price_weekend'] ?? $price_weekday;

// Tiện ích
$amenities_list = [];
if (isset($homestay['amenities']) && !empty($homestay['amenities'])) {
    $amenities_list = array_map('trim', explode(',', $homestay['amenities']));
} else {
    $amenities_list = ['Wifi tốc độ cao', 'Điều hòa 2 chiều', 'Bếp đầy đủ', 'Máy giặt', 'Chỗ đỗ xe', 'Smart TV'];
}
// 7. LẤY FLASH MESSAGE 
$flash_message = getFlashMessage();

require_once 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo htmlspecialchars($homestay['name']); ?> - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { vertical-align: bottom; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">
<?php if ($flash_message): 
    $type = $flash_message['type']; 
    $message = $flash_message['message'];
    $color_bg = ($type === 'success') ? 'bg-green-100' : 'bg-red-100';
    $color_border = ($type === 'success') ? 'border-green-400' : 'border-red-400';
    $color_text = ($type === 'success') ? 'text-green-700' : 'text-red-700';
?>
    <div class="fixed top-0 left-0 w-full z-50 p-4">
        <div id="flash-message-alert" class="max-w-7xl mx-auto border <?php echo $color_bg; ?> <?php echo $color_border; ?> <?php echo $color_text; ?> px-4 py-3 rounded-lg relative shadow-xl" role="alert">
            <strong class="font-bold mr-2"><?php echo ($type === 'success') ? '🎉 Hoàn tất!' : '⚠️ Lỗi!'; ?></strong>
            <span class="block sm:inline"><?php echo $message; ?></span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="document.getElementById('flash-message-alert').remove();">
                <span class="material-symbols-outlined text-lg">close</span>
            </span>
        </div>
    </div>
<?php endif; ?>
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-3 text-sm text-gray-500 flex items-center gap-2">
            <a href="index.php" class="hover:text-[#13ecc8]">Trang chủ</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="homestay.php" class="hover:text-[#13ecc8]">Homestay</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-gray-900 font-medium truncate"><?php echo htmlspecialchars($homestay['name']); ?></span>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 py-8 w-full flex-grow">
        
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">
                    <?php echo htmlspecialchars($homestay['name']); ?>
                </h1>
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-red-500">location_on</span>
                        <?php echo htmlspecialchars($homestay['address']); ?>, <?php echo htmlspecialchars($homestay['district']); ?>
                    </span>
                    <span class="hidden md:inline text-gray-300">|</span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-blue-500">group</span>
                        Tối đa <?php echo $homestay['max_guests']; ?> khách
                    </span>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="toggleHeartDetail(this, <?php echo $homestay_id; ?>)" 
                        class="px-4 py-2 rounded-full border border-gray-200 font-semibold text-sm flex items-center gap-2 hover:bg-gray-50 transition <?php echo $is_liked ? 'text-red-500' : 'text-gray-700'; ?>">
                    <span class="material-symbols-outlined" style="<?php echo $is_liked ? "font-variation-settings: 'FILL' 1;" : ""; ?>">
                        <?php echo $is_liked ? 'favorite' : 'favorite_border'; ?>
                    </span>
                    <span id="txt-like"><?php echo $is_liked ? 'Đã lưu' : 'Lưu tin'; ?></span>
                </button>
                <button class="px-4 py-2 rounded-full border border-gray-200 font-semibold text-sm flex items-center gap-2 hover:bg-gray-50 transition text-gray-700">
                    <span class="material-symbols-outlined">ios_share</span> Chia sẻ
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 h-[300px] md:h-[450px] rounded-2xl overflow-hidden mb-10 relative">
            <div class="md:col-span-2 h-full">
                <img src="<?php echo $img_src; ?>" class="w-full h-full object-cover hover:scale-105 transition duration-700 cursor-pointer">
            </div>
            <div class="hidden md:grid md:col-span-1 grid-rows-2 gap-2 h-full">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=800" class="w-full h-full object-cover hover:scale-105 transition duration-700 cursor-pointer">
                <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=800" class="w-full h-full object-cover hover:scale-105 transition duration-700 cursor-pointer">
            </div>
            <div class="hidden md:grid md:col-span-1 grid-rows-2 gap-2 h-full">
                <img src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?q=80&w=800" class="w-full h-full object-cover hover:scale-105 transition duration-700 cursor-pointer">
                <div class="relative w-full h-full cursor-pointer group">
                    <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=800" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center group-hover:bg-black/40 transition">
                        <span class="text-white font-bold text-lg">+ Xem tất cả ảnh</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <div class="lg:col-span-2">
                <div class="mb-10 border-b border-gray-100 pb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Giới thiệu về chỗ ở này</h2>
                    <div class="prose max-w-none text-gray-600 leading-relaxed text-sm md:text-base">
                        <?php echo nl2br(htmlspecialchars($homestay['description'])); ?>
                    </div>
                </div>

                <div class="mb-10">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Tiện nghi có sẵn</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-8">
                        <?php foreach($amenities_list as $amenity): ?>
                        <div class="flex items-center gap-3 text-gray-700">
                            <span class="material-symbols-outlined text-[#13ecc8]">check_circle</span>
                            <span><?php echo htmlspecialchars($amenity); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                </div>

            <div class="relative">
                <div class="sticky top-24 bg-white border border-gray-200 rounded-2xl shadow-xl p-6">
                    
                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <span class="text-gray-500 text-xs font-bold line-through"><?php echo number_format($price_weekday * 1.2, 0, ',', '.'); ?>₫</span>
                            <div class="flex items-end gap-1">
                                <span class="text-2xl font-black text-[#13ecc8]"><?php echo number_format($price_weekday, 0, ',', '.'); ?>₫</span>
                                <span class="text-gray-500 text-sm font-medium mb-1">/ đêm</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-xs font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded">
                            <span class="material-symbols-outlined text-[14px] text-yellow-500">star</span>
                            4.9 (20 đánh giá)
                        </div>
                    </div>

                    <form action="booking_confirm.php" method="GET" id="booking-form">
                        <input type="hidden" name="homestay_id" value="<?php echo $homestay_id; ?>">
                        
                        <div class="border border-gray-300 rounded-t-xl overflow-hidden flex mb-[-1px]">
                            <div class="flex-1 border-r border-gray-300 p-3 bg-white hover:bg-gray-50 transition relative">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">Nhận phòng</label>
                                <input type="text" id="checkin" name="checkin" class="w-full text-sm font-bold bg-transparent outline-none cursor-pointer placeholder-gray-400" placeholder="Chọn ngày">
                            </div>
                            <div class="flex-1 p-3 bg-white hover:bg-gray-50 transition relative">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">Trả phòng</label>
                                <input type="text" id="checkout" name="checkout" class="w-full text-sm font-bold bg-transparent outline-none cursor-pointer placeholder-gray-400" placeholder="Chọn ngày">
                            </div>
                        </div>

                        <div class="border border-gray-300 rounded-b-xl p-3 mb-4 bg-white hover:bg-gray-50 transition">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">Khách</label>
                            <select name="guests" class="w-full text-sm font-bold bg-transparent outline-none cursor-pointer">
                                <?php for($i = 1; $i <= $homestay['max_guests']; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?> khách</option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div id="price-calculation" class="hidden space-y-3 mb-6 pt-4 border-t border-dashed border-gray-200 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span id="calc-text">0₫ x 0 đêm</span>
                                <span id="calc-subtotal" class="font-medium">0₫</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Phí dịch vụ (5%)</span>
                                <span id="calc-fee" class="font-medium">0₫</span>
                            </div>
                            <div class="flex justify-between text-gray-900 font-bold text-lg pt-3 border-t border-gray-200">
                                <span>Tổng cộng</span>
                                <span id="calc-total">0₫</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-[#13ecc8] hover:bg-[#10d4b4] text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-300 flex justify-center items-center gap-2">
                            Đặt phòng ngay
                        </button>
                        
                        <p class="text-center text-xs text-gray-400 mt-3">Bạn vẫn chưa bị trừ tiền ở bước này</p>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-900 text-white py-12 mt-auto text-center">
        <p class="text-gray-500 text-sm">© <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
    </footer>

    <script>
        // 1. Cấu hình giá
        const priceWeekday = <?php echo $price_weekday; ?>;
        
        // 2. Xử lý Datepicker
        const checkinInput = document.getElementById('checkin');
        const checkoutInput = document.getElementById('checkout');
        const priceCalcDiv = document.getElementById('price-calculation');

        const checkinPicker = flatpickr(checkinInput, {
            minDate: "today",
            dateFormat: "Y-m-d",
            locale: "vn",
            onChange: function(selectedDates, dateStr, instance) {
                checkoutPicker.set('minDate', dateStr);
                setTimeout(() => checkoutPicker.open(), 100);
                calculateTotal();
            }
        });

        const checkoutPicker = flatpickr(checkoutInput, {
            minDate: "today",
            dateFormat: "Y-m-d",
            locale: "vn",
            onChange: function(selectedDates, dateStr, instance) {
                calculateTotal();
            }
        });

        function calculateTotal() {
            const d1 = checkinPicker.selectedDates[0];
            const d2 = checkoutPicker.selectedDates[0];

            if (d1 && d2 && d2 > d1) {
                const diffTime = Math.abs(d2 - d1);
                const nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const subTotal = nights * priceWeekday;
                const serviceFee = subTotal * 0.05; 
                const total = subTotal + serviceFee;

                document.getElementById('calc-text').innerText = new Intl.NumberFormat('vi-VN').format(priceWeekday) + '₫ x ' + nights + ' đêm';
                document.getElementById('calc-subtotal').innerText = new Intl.NumberFormat('vi-VN').format(subTotal) + '₫';
                document.getElementById('calc-fee').innerText = new Intl.NumberFormat('vi-VN').format(serviceFee) + '₫';
                document.getElementById('calc-total').innerText = new Intl.NumberFormat('vi-VN').format(total) + '₫';

                priceCalcDiv.classList.remove('hidden');
            } else {
                priceCalcDiv.classList.add('hidden');
            }
        }

        // 3. Xử lý Like
        function toggleHeartDetail(btn, id) {
             const isLoggedIn = <?php echo $is_logged_in ? 'true' : 'false'; ?>;
            if (!isLoggedIn) {
                alert('Vui lòng đăng nhập!'); return;
            }
            const iconSpan = btn.querySelector('.material-symbols-outlined');
            const textSpan = document.getElementById('txt-like');
            const isLiked = btn.classList.contains('text-red-500');

            if (isLiked) {
                btn.classList.remove('text-red-500');
                btn.classList.add('text-gray-700');
                iconSpan.innerText = 'favorite_border';
                iconSpan.style.fontVariationSettings = "";
                textSpan.innerText = "Lưu tin";
                fetch('api/wishlist.php?action=remove&id=' + id);
            } else {
                btn.classList.add('text-red-500');
                btn.classList.remove('text-gray-700');
                iconSpan.innerText = 'favorite';
                iconSpan.style.fontVariationSettings = "'FILL' 1";
                textSpan.innerText = "Đã lưu";
                fetch('api/wishlist.php?action=add&id=' + id);
            }
        }
    </script>
</body>
</html>