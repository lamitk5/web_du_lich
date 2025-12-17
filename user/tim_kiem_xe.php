<?php
/**
 * users/tim_kiem_xe.php
 */

require_once '../config/config.php';

// --- 1. LẤY THAM SỐ TÌM KIẾM ---
$pickupLocation = $_GET['pickup'] ?? '';
$date = $_GET['date'] ?? date('Y-m-d');
$time = $_GET['time'] ?? '09:00';
$passengers = $_GET['passengers'] ?? 4;
$maxPrice = $_GET['max_price'] ?? 5000000;
$sort = $_GET['sort'] ?? 'price_asc';
$selectedTypes = $_GET['types'] ?? []; 

// --- 2. XÂY DỰNG QUERY ---
$sql = "SELECT * FROM vehicles WHERE status = 'available'";
$params = [];

// Lọc theo địa chỉ (Tìm kiếm tương đối)
if (!empty($pickupLocation)) {
    // Tìm xe có địa chỉ chứa từ khóa
    $sql .= " AND dia_chi LIKE ?";
    $params[] = "%" . $pickupLocation . "%";
}

// Lọc theo số ghế
$sql .= " AND seats >= ?";
$params[] = $passengers;

// Lọc theo giá trần
$sql .= " AND price_per_day <= ?";
$params[] = $maxPrice;

// Lọc theo loại xe
if (!empty($selectedTypes)) {
    $placeholders = str_repeat('?,', count($selectedTypes) - 1) . '?';
    $sql .= " AND type IN ($placeholders)";
    $params = array_merge($params, $selectedTypes);
}

// Sắp xếp
$orderBy = match($sort) {
    'price_desc' => 'price_per_day DESC',
    'rating_desc' => 'rating DESC',
    default => 'price_per_day ASC'
};
$sql .= " ORDER BY $orderBy";

// --- 3. THỰC THI ---
$vehicles = db()->select($sql, $params);

// Helper render tiện ích
function renderAmenities($amenitiesStr) {
    $list = explode(',', $amenitiesStr);
    $html = '';
    $iconMap = [
        'AC' => 'ac_unit', 'WiFi' => 'wifi', 'Child seat' => 'child_care', 
        'Drinks' => 'local_drink', 'TV' => 'tv', 'Map' => 'map', 'Bluetooth' => 'bluetooth'
    ];
    foreach ($list as $item) {
        $item = trim($item);
        $icon = $iconMap[$item] ?? 'check_circle';
        $html .= "<div class='flex items-center gap-1.5'><span class='material-symbols-outlined text-base text-primary'>$icon</span><span>$item</span></div>";
    }
    return $html;
}
require_once 'includes/header.php';
?>
<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Tìm kiếm xe - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0da6f2",
                        "background-light": "#f5f7f8",
                        "background-dark": "#101c22",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-800 dark:text-slate-200">
<div class="relative flex min-h-screen w-full flex-col">
    <main class="flex-1">
    <div class="relative h-[250px] w-full overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2000&auto=format&fit=crop');"></div>
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative container mx-auto px-4 h-full flex flex-col justify-center text-white">
            <h1 class="text-3xl md:text-4xl font-black mb-2">Thuê xe tự lái & Có tài xế</h1>
            <p class="text-lg text-gray-200">Vi vu mọi nẻo đường với đội xe chất lượng cao</p>
        </div>
    </div>
        <div class="bg-white dark:bg-[#1f2d37] border-b border-gray-200 dark:border-gray-700 py-8">
            <div class="container mx-auto px-4">
                <h1 class="text-3xl font-black mb-2 text-[#0d171c] dark:text-white">Đặt xe đưa đón & Du lịch</h1>
                <p class="text-gray-500 dark:text-gray-400 mb-8">Trải nghiệm hành trình thoải mái với đội xe chất lượng cao.</p>

                <div class="bg-white dark:bg-[#1a2831] p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                        
                        <div class="flex flex-col gap-1.5 lg:col-span-2">
                            <label class="text-xs font-bold text-gray-500 uppercase">Điểm đón (Tỉnh/Thành)</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">location_on</span>
                                <input name="pickup" value="<?php echo htmlspecialchars($pickupLocation); ?>" class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 pl-10 pr-4 text-sm font-semibold focus:ring-2 focus:ring-primary outline-none" placeholder="Nhập địa điểm (vd: Hà Nội, Đà Nẵng)..."/>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase">Ngày đón</label>
                            <div class="relative">
                                <input type="date" name="date" value="<?php echo $date; ?>" class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 text-sm font-semibold focus:ring-2 focus:ring-primary outline-none"/>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase">Giờ đón</label>
                            <div class="relative">
                                <input type="time" name="time" value="<?php echo $time; ?>" class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 text-sm font-semibold focus:ring-2 focus:ring-primary outline-none"/>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase">Số khách</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">group</span>
                                <input type="number" name="passengers" min="1" max="45" value="<?php echo $passengers; ?>" class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 pl-10 pr-4 text-sm font-semibold focus:ring-2 focus:ring-primary outline-none"/>
                            </div>
                        </div>

                        <button type="submit" class="w-full h-11 bg-amber-500 hover:bg-amber-400 text-[#0d171c] font-bold rounded-lg transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">search</span>
                            Tìm xe
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold">Kết quả: <?php echo count($vehicles); ?> xe phù hợp</h2>
                        
                        <?php if(!empty($pickupLocation)): ?>
                            <span class="text-sm bg-primary/10 text-primary px-3 py-1 rounded-full font-semibold border border-primary/20">
                                Tại: <?php echo htmlspecialchars($pickupLocation); ?>
                            </span>
                        <?php endif; ?>

                        <form method="GET" class="flex">
                            <input type="hidden" name="pickup" value="<?php echo htmlspecialchars($pickupLocation); ?>">
                            <input type="hidden" name="passengers" value="<?php echo $passengers; ?>">
                            <select name="sort" onchange="this.form.submit()" class="rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm focus:ring-primary py-2 pl-3 pr-8 cursor-pointer">
                                <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Giá thấp nhất</option>
                                <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Giá cao nhất</option>
                                <option value="rating_desc" <?php echo $sort == 'rating_desc' ? 'selected' : ''; ?>>Đánh giá cao nhất</option>
                            </select>
                        </form>
                    </div>

                    <?php if (empty($vehicles)): ?>
                        <div class="bg-white dark:bg-[#1a2831] p-8 rounded-xl text-center border border-gray-200 dark:border-gray-700 shadow-sm">
                            <span class="material-symbols-outlined text-6xl text-gray-400 mb-4">no_transfer</span>
                            <h3 class="text-xl font-bold text-gray-600">Không tìm thấy xe phù hợp</h3>
                            <p class="text-gray-500 mt-2">Thử tìm địa điểm khác hoặc giảm tiêu chí lọc.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($vehicles as $vehicle): ?>
                        <div class="bg-white dark:bg-[#1a2831] rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all group">
                            <div class="flex flex-col md:flex-row gap-5">
                                <div class="md:w-1/3 relative overflow-hidden rounded-lg">
                                    <img src="<?php echo htmlspecialchars($vehicle['image'] ?? 'https://placehold.co/600x400?text=No+Image'); ?>" 
                                         alt="<?php echo htmlspecialchars($vehicle['name']); ?>" 
                                         class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500"/>
                                </div>
                                
                                <div class="md:w-2/3 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h3 class="text-lg font-bold text-[#0d171c] dark:text-white"><?php echo htmlspecialchars($vehicle['name']); ?></h3>
                                                
                                                <div class="flex items-center gap-1 text-sm text-gray-500 mt-1">
                                                    <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                                                    <span><?php echo htmlspecialchars($vehicle['dia_chi'] ?? 'Toàn quốc'); ?></span>
                                                    <span class="mx-1">•</span>
                                                    <span class="uppercase font-semibold text-xs border border-gray-300 rounded px-1"><?php echo ucfirst($vehicle['type']); ?></span>
                                                </div>

                                            </div>
                                            <div class="flex items-center gap-1 text-amber-500 bg-amber-50 dark:bg-amber-900/20 px-2 py-1 rounded-md">
                                                <span class="font-bold"><?php echo $vehicle['rating']; ?></span>
                                                <span class="material-symbols-outlined text-sm">star</span>
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-300 mt-3">
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-base text-primary">person</span>
                                                <span><?php echo $vehicle['seats']; ?> chỗ</span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-base text-primary">luggage</span>
                                                <span><?php echo $vehicle['luggage_capacity']; ?> hành lý</span>
                                            </div>
                                            <?php echo renderAmenities($vehicle['amenities']); ?>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between border-t border-gray-100 dark:border-gray-700 pt-4">
                                        <div>
                                            <p class="text-xl font-bold text-primary"><?php echo formatCurrency($vehicle['price_per_day']); ?></p>
                                            <p class="text-xs text-gray-500">Giá tham khảo / ngày</p>
                                        </div>
                                        <a href="chi_tiet_xe.php?id=<?php echo $vehicle['id']; ?>&pickup=<?php echo urlencode($pickupLocation); ?>&date=<?php echo $date; ?>&time=<?php echo $time; ?>" 
                                           class="px-6 py-2 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition-colors shadow-md shadow-primary/20">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-[#1a2831] p-5 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 sticky top-24">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-lg">Bộ lọc tìm kiếm</h3>
                            <a href="tim_kiem_xe.php" class="text-xs text-primary hover:underline">Xóa lọc</a>
                        </div>
                        
                        <form id="filterForm" method="GET">
                            <input type="hidden" name="pickup" value="<?php echo htmlspecialchars($pickupLocation); ?>">
                            <input type="hidden" name="passengers" value="<?php echo $passengers; ?>">

                            <div class="space-y-6">
                                <div>
                                    <h4 class="font-semibold text-sm mb-3">Giá tối đa / ngày</h4>
                                    <input type="range" name="max_price" class="w-full accent-primary h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" 
                                           min="500000" max="5000000" step="100000" 
                                           value="<?php echo $maxPrice; ?>"
                                           oninput="document.getElementById('priceDisplay').innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(this.value)"
                                           onchange="this.form.submit()">
                                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                                        <span>500k</span>
                                        <span id="priceDisplay" class="font-bold text-primary"><?php echo formatCurrency($maxPrice); ?></span>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-sm mb-3">Loại xe</h4>
                                    <div class="space-y-3">
                                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 p-1 rounded">
                                            <input type="checkbox" name="types[]" value="sedan" <?php echo in_array('sedan', $selectedTypes) ? 'checked' : ''; ?> onchange="this.form.submit()" class="rounded text-primary focus:ring-primary border-gray-300 size-4">
                                            <span class="text-sm">Sedan (4 chỗ)</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 p-1 rounded">
                                            <input type="checkbox" name="types[]" value="suv" <?php echo in_array('suv', $selectedTypes) ? 'checked' : ''; ?> onchange="this.form.submit()" class="rounded text-primary focus:ring-primary border-gray-300 size-4">
                                            <span class="text-sm">SUV (7 chỗ)</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 p-1 rounded">
                                            <input type="checkbox" name="types[]" value="minivan" <?php echo in_array('minivan', $selectedTypes) ? 'checked' : ''; ?> onchange="this.form.submit()" class="rounded text-primary focus:ring-primary border-gray-300 size-4">
                                            <span class="text-sm">Minivan (Gia đình)</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 p-1 rounded">
                                            <input type="checkbox" name="types[]" value="van" <?php echo in_array('van', $selectedTypes) ? 'checked' : ''; ?> onchange="this.form.submit()" class="rounded text-primary focus:ring-primary border-gray-300 size-4">
                                            <span class="text-sm">Van (16 chỗ)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
</body>
</html>