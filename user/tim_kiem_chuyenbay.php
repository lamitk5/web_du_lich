<?php
/**
 * users/tim_kiem_chuyenbay.php
 */

require_once '../config/config.php';

// 1. LẤY THAM SỐ TÌM KIẾM
// Mặc định để rỗng để hiển thị tất cả nếu người dùng chưa nhập gì
$from = $_GET['from'] ?? ''; 
$to = $_GET['to'] ?? '';
$date = $_GET['date'] ?? '';
$passengers = $_GET['passengers'] ?? 1;

// Filters phụ
$airlines = $_GET['airlines'] ?? [];
$sortBy = $_GET['sort'] ?? 'price_asc';

// 2. XÂY DỰNG TRUY VẤN SQL
// Điều kiện cơ bản: Chuyến bay phải đang lên lịch (scheduled)
$whereClause = "f.status = 'scheduled'";
$params = [];

// Nếu có chọn điểm đi
if (!empty($from)) {
    $whereClause .= " AND f.departure_airport = ?";
    $params[] = $from;
}

// Nếu có chọn điểm đến
if (!empty($to)) {
    $whereClause .= " AND f.arrival_airport = ?";
    $params[] = $to;
}

// Nếu có chọn ngày (Nếu không chọn ngày, sẽ hiện tất cả chuyến bay tương lai)
if (!empty($date)) {
    $whereClause .= " AND DATE(f.departure_time) = ?";
    $params[] = $date;
} else {
    // Nếu không chọn ngày cụ thể, chỉ lấy các chuyến bay từ thời điểm hiện tại trở đi
}

// Lọc theo hãng bay
if (!empty($airlines)) {
    $placeholders = str_repeat('?,', count($airlines) - 1) . '?';
    $whereClause .= " AND f.airline_id IN ($placeholders)";
    $params = array_merge($params, $airlines);
}

// Lọc theo số ghế trống
$whereClause .= " AND f.available_seats >= ?";
$params[] = $passengers;

// Sắp xếp
$orderBy = match($sortBy) {
    'price_desc' => 'f.price DESC',
    'time_asc' => 'f.departure_time ASC',
    'time_desc' => 'f.departure_time DESC',
    default => 'f.price ASC'
};

// 3. THỰC THI TRUY VẤN
$flights = db()->select("
    SELECT f.*, a.name as airline_name, a.code as airline_code, a.logo
    FROM flights f
    INNER JOIN airlines a ON f.airline_id = a.id
    WHERE $whereClause
    ORDER BY $orderBy
", $params);

// Lấy danh sách hãng bay để làm bộ lọc (chỉ lấy các hãng có chuyến bay)
$availableAirlines = db()->select("SELECT * FROM airlines WHERE status = 'active'");
require_once 'includes/header.php';
?>
<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Tìm kiếm vé máy bay - <?php echo SITE_NAME; ?></title>
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-800 dark:text-slate-200">
<div class="relative flex min-h-screen w-full flex-col">
    <main class="flex-1">
    <div class="relative h-[250px] w-full overflow-hidden">
        <img src="https://png.pngtree.com/thumb_back/fw800/background/20220709/pngtree-a-wing-of-airline-trip-plane-rapid-photo-image_1127013.jpg" 
             alt="Banner Chuyến Bay" 
             class="absolute inset-0 w-full h-full object-cover z-0"
             onerror="this.style.display='none'" />
             
        <div class="absolute inset-0 bg-black/50 z-0"></div>
        
        <div class="relative container mx-auto px-4 h-full flex flex-col justify-center text-white z-10">
            <h1 class="text-3xl md:text-4xl font-black mb-2 drop-shadow-lg">Săn vé máy bay giá rẻ</h1>
            <p class="text-lg text-gray-200 drop-shadow-md">Kết nối mọi miền tổ quốc với mức giá tốt nhất</p>
        </div>
    </div>
        <div class="bg-white dark:bg-[#1f2d37] border-b border-gray-200 dark:border-gray-700 py-6">
            <div class="container mx-auto px-4">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Điểm đi</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">flight_takeoff</span>
                            <input name="from" value="<?php echo htmlspecialchars($from); ?>" placeholder="VD: SGN" class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 pl-10 pr-4 text-sm font-semibold focus:ring-2 focus:ring-primary outline-none"/>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Điểm đến</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">flight_land</span>
                            <input name="to" value="<?php echo htmlspecialchars($to); ?>" placeholder="VD: HAN" class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 pl-10 pr-4 text-sm font-semibold focus:ring-2 focus:ring-primary outline-none"/>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Ngày đi</label>
                        <input name="date" value="<?php echo $date; ?>" type="date" class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 text-sm font-semibold focus:ring-2 focus:ring-primary outline-none"/>
                    </div>
                    <button type="submit" class="w-full h-11 bg-amber-500 hover:bg-amber-400 text-[#0d171c] font-bold rounded-lg transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">search</span>
                        Tìm kiếm
                    </button>
                </form>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <aside class="hidden lg:block lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-[#1a2831] p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-lg">Bộ lọc</h3>
                            <a href="tim_kiem_chuyenbay.php" class="text-xs text-primary hover:underline">Xóa lọc</a>
                        </div>
                        
                        <form id="filterForm" method="GET">
                            <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
                            <input type="hidden" name="to" value="<?php echo htmlspecialchars($to); ?>">
                            <input type="hidden" name="date" value="<?php echo $date; ?>">
                            <input type="hidden" name="passengers" value="<?php echo $passengers; ?>">

                            <div class="mb-6">
                                <h4 class="font-semibold text-sm mb-3 text-gray-500 uppercase">Hãng hàng không</h4>
                                <div class="space-y-2">
                                    <?php foreach ($availableAirlines as $airline): ?>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="airlines[]" value="<?php echo $airline['id']; ?>" 
                                                <?php echo in_array($airline['id'], $airlines) ? 'checked' : ''; ?>
                                                onchange="document.getElementById('filterForm').submit()"
                                                class="peer size-4 appearance-none rounded border border-gray-300 checked:bg-primary checked:border-primary transition-colors"/>
                                            <span class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                            </span>
                                        </div>
                                        <span class="text-sm font-medium group-hover:text-primary transition-colors"><?php echo htmlspecialchars($airline['name']); ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </aside>

                <div class="lg:col-span-3">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                        <h2 class="text-xl font-bold">
                            <?php if(empty($flights)): ?>
                                Không tìm thấy chuyến bay
                            <?php else: ?>
                                Tìm thấy <span class="text-primary"><?php echo count($flights); ?></span> chuyến bay
                            <?php endif; ?>
                        </h2>
                        
                        <div class="flex bg-white dark:bg-[#1a2831] p-1 rounded-lg border border-gray-200 dark:border-gray-700">
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price_asc'])); ?>" 
                               class="px-4 py-1.5 rounded-md text-sm font-medium transition-colors <?php echo $sortBy === 'price_asc' ? 'bg-primary/10 text-primary' : 'hover:bg-gray-100 dark:hover:bg-gray-700'; ?>">
                                Giá thấp nhất
                            </a>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'time_asc'])); ?>" 
                               class="px-4 py-1.5 rounded-md text-sm font-medium transition-colors <?php echo $sortBy === 'time_asc' ? 'bg-primary/10 text-primary' : 'hover:bg-gray-100 dark:hover:bg-gray-700'; ?>">
                                Cất cánh sớm nhất
                            </a>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <?php if (empty($flights)): ?>
                            <div class="bg-white dark:bg-[#1a2831] rounded-xl p-8 text-center border border-gray-200 dark:border-gray-700">
                                <div class="inline-flex items-center justify-center size-20 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                    <span class="material-symbols-outlined text-4xl text-gray-400">flight_off</span>
                                </div>
                                <h3 class="text-lg font-bold mb-2">Không tìm thấy chuyến bay phù hợp</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">
                                    <?php if(!empty($date)): ?>
                                        Hiện tại không có chuyến bay nào vào ngày <b><?php echo date('d/m/Y', strtotime($date)); ?></b>. 
                                        Bạn có thể thử bỏ chọn ngày để xem tất cả các chuyến bay có sẵn.
                                    <?php else: ?>
                                        Vui lòng thử thay đổi điểm đi, điểm đến hoặc điều chỉnh bộ lọc tìm kiếm.
                                    <?php endif; ?>
                                </p>
                                <a href="tim_kiem_chuyenbay.php" class="inline-flex items-center px-4 py-2 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition-colors">
                                    Xem tất cả chuyến bay
                                </a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($flights as $flight): 
                                $dep = new DateTime($flight['departure_time']);
                                $arr = new DateTime($flight['arrival_time']);
                                $interval = $dep->diff($arr);
                                $duration = $interval->format('%Hh %Im');
                            ?>
                            <div class="group bg-white dark:bg-[#1a2831] rounded-xl p-0 shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 transition-all duration-200 overflow-hidden">
                                <div class="p-5 grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                                    
                                    <div class="col-span-1 flex items-center gap-3">
                                        <div class="size-10 rounded-full bg-gray-100 flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined">flight</span>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[#0d171c] dark:text-white"><?php echo htmlspecialchars($flight['airline_name']); ?></h4>
                                            <span class="text-xs px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-500 font-medium">
                                                <?php echo htmlspecialchars($flight['flight_code']); ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-span-2 flex items-center justify-center gap-4 text-center">
                                        <div>
                                            <p class="text-xl font-bold"><?php echo $dep->format('H:i'); ?></p>
                                            <p class="text-xs font-semibold text-gray-500 uppercase"><?php echo $flight['departure_airport']; ?></p>
                                        </div>
                                        
                                        <div class="flex flex-col items-center w-full max-w-[120px]">
                                            <p class="text-xs text-gray-500 mb-1"><?php echo $duration; ?></p>
                                            <div class="relative w-full h-[2px] bg-gray-300 dark:bg-gray-600 rounded-full">
                                                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 size-2 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Bay thẳng</p>
                                        </div>

                                        <div>
                                            <p class="text-xl font-bold"><?php echo $arr->format('H:i'); ?></p>
                                            <p class="text-xs font-semibold text-gray-500 uppercase"><?php echo $flight['arrival_airport']; ?></p>
                                        </div>
                                    </div>

                                    <div class="col-span-1 flex flex-row md:flex-col justify-between items-center md:items-end gap-2 md:pl-6 md:border-l border-gray-100 dark:border-gray-700">
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500 line-through decoration-red-500 decoration-2">
                                                <?php echo formatCurrency($flight['price'] * 1.2); // Giá ảo cao hơn ?>
                                            </p>
                                            <p class="text-xl font-bold text-primary">
                                                <?php echo formatCurrency($flight['price']); ?>
                                            </p>
                                            <p class="text-[10px] text-gray-400">/khách</p>
                                        </div>
                                        
                                        <a href="chi_tiet_chuyenbay.php?flight_id=<?php echo $flight['id']; ?>&passengers=<?php echo $passengers; ?>" 
                                           class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-[#0d171c] text-sm font-bold rounded-lg transition-colors shadow-sm">
                                            Chọn vé
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 dark:bg-[#23333f] px-5 py-2 flex justify-between items-center text-xs text-gray-500 border-t border-gray-100 dark:border-gray-700">
                                    <span>Ngày bay: <b class="text-gray-700 dark:text-gray-300"><?php echo $dep->format('d/m/Y'); ?></b></span>
                                    <span class="flex items-center gap-1 text-green-600 dark:text-green-400">
                                        <span class="material-symbols-outlined text-[14px]">event_seat</span>
                                        Còn <?php echo $flight['available_seats']; ?> ghế
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>