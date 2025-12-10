<?php
/**
 * Tìm kiếm chuyến bay
 */

require_once '../config/config.php';

// Lấy tham số tìm kiếm
$from = $_GET['from'] ?? 'SGN';
$to = $_GET['to'] ?? 'HAN';
$date = $_GET['date'] ?? date('Y-m-d', strtotime('+1 day'));
$passengers = $_GET['passengers'] ?? 1;

// Filters
$stops = $_GET['stops'] ?? [];
$airlines = $_GET['airlines'] ?? [];
$sortBy = $_GET['sort'] ?? 'price_asc';

// Build query
$where = "departure_airport = ? AND arrival_airport = ? AND DATE(departure_time) = ? AND status = 'scheduled'";
$params = [$from, $to, $date];

if (!empty($airlines)) {
    $placeholders = str_repeat('?,', count($airlines) - 1) . '?';
    $where .= " AND airline_id IN ($placeholders)";
    $params = array_merge($params, $airlines);
}

// Sorting
$orderBy = match($sortBy) {
    'price_desc' => 'f.price DESC',
    'time_asc' => 'f.departure_time ASC',
    'time_desc' => 'f.departure_time DESC',
    default => 'f.price ASC'
};

// Lấy danh sách chuyến bay
$flights = db()->select("
    SELECT f.*, a.name as airline_name, a.code as airline_code
    FROM flights f
    INNER JOIN airlines a ON f.airline_id = a.id
    WHERE $where AND f.available_seats >= ?
    ORDER BY $orderBy
", array_merge($params, [$passengers]));

// Lấy danh sách hãng hàng không cho filter
$availableAirlines = db()->select("
    SELECT DISTINCT a.id, a.name, a.code
    FROM airlines a
    INNER JOIN flights f ON a.id = f.airline_id
    WHERE f.departure_airport = ? AND f.arrival_airport = ?
    AND DATE(f.departure_time) = ?
    ORDER BY a.name
", [$from, $to, $date]);

// Format date cho hiển thị
$displayDate = formatDate($date, 'd/m/Y');
?>
<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Tìm kiếm & Đặt vé máy bay - <?php echo SITE_NAME; ?></title>
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
<body class="bg-background-light dark:bg-background-dark font-display">
<div class="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
        <!-- Header -->
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#e7eff4] dark:border-b-background-dark px-4 sm:px-10 py-3 bg-background-light dark:bg-background-dark sticky top-0 z-20">
            <div class="flex items-center gap-4 text-[#0d171c] dark:text-white">
                <div class="size-6 text-primary">
                    <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078L44 7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094L4 42.4379Z" fill="currentColor"></path>
                    </svg>
                </div>
                <h2 class="text-[#0d171c] dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]"><?php echo SITE_NAME; ?></h2>
            </div>
            <div class="flex flex-1 justify-end items-center gap-4 sm:gap-8">
                <div class="flex gap-2">
                    <a href="register.php" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
                        <span class="truncate">Đăng ký</span>
                    </a>
                    <a href="login.php" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#e7eff4] dark:bg-primary/20 text-[#0d171c] dark:text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#dde8f0] dark:hover:bg-primary/30 transition-colors">
                        <span class="truncate">Đăng nhập</span>
                    </a>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <!-- Search Bar -->
            <div class="bg-gray-100 dark:bg-[#1f2d37] py-6 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white dark:bg-[#1a2831] p-4 sm:p-6 rounded-xl shadow-lg">
                        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[1fr_1fr_1fr_1fr_auto] gap-4 items-end">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Điểm đi</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">flight_takeoff</span>
                                    <input name="from" value="<?php echo htmlspecialchars($from); ?>" class="w-full h-12 rounded-lg border border-gray-200 dark:border-gray-700 bg-background-light dark:bg-background-dark pl-10 pr-4 text-sm"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Điểm đến</label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">flight_land</span>
                                    <input name="to" value="<?php echo htmlspecialchars($to); ?>" class="w-full h-12 rounded-lg border border-gray-200 dark:border-gray-700 bg-background-light dark:bg-background-dark pl-10 pr-4 text-sm"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Ngày đi</label>
                                <input name="date" value="<?php echo $date; ?>" type="date" class="w-full h-12 rounded-lg border border-gray-200 dark:border-gray-700 bg-background-light dark:bg-background-dark px-4 text-sm"/>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-medium text-gray-600 dark:text-gray-400">Số lượng hành khách</label>
                                <input name="passengers" value="<?php echo $passengers; ?>" type="number" min="1" max="10" class="w-full h-12 rounded-lg border border-gray-200 dark:border-gray-700 bg-background-light dark:bg-background-dark px-4 text-sm"/>
                            </div>
                            <button type="submit" class="flex w-full lg:w-auto min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors">
                                <span class="truncate">Tìm chuyến bay</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Filters Sidebar -->
                    <aside class="lg:col-span-1">
                        <div class="bg-white dark:bg-[#1a2831] p-6 rounded-xl shadow-lg space-y-6">
                            <h3 class="text-lg font-bold text-[#0d171c] dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">Bộ lọc</h3>
                            
                            <form method="GET">
                                <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
                                <input type="hidden" name="to" value="<?php echo htmlspecialchars($to); ?>">
                                <input type="hidden" name="date" value="<?php echo $date; ?>">
                                <input type="hidden" name="passengers" value="<?php echo $passengers; ?>">
                                
                                <!-- Airlines Filter -->
                                <div>
                                    <h4 class="font-semibold mb-2 text-[#0d171c] dark:text-white">Hãng hàng không</h4>
                                    <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                                        <?php foreach ($availableAirlines as $airline): ?>
                                        <div class="flex items-center group cursor-pointer">
                                            <input <?php echo in_array($airline['id'], $airlines) ? 'checked' : ''; ?> class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer" name="airlines[]" value="<?php echo $airline['id']; ?>" type="checkbox" onchange="this.form.submit()"/>
                                            <label class="ml-2 cursor-pointer"><?php echo htmlspecialchars($airline['name']); ?></label>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </aside>

                    <!-- Results -->
                    <div class="lg:col-span-3">
                        <div class="flex flex-col sm:flex-row justify-between items-baseline pb-4">
                            <h2 class="text-[#0d171c] dark:text-white text-xl font-bold leading-tight tracking-[-0.015em] mb-3 sm:mb-0">
                                Kết quả tìm kiếm: <?php echo count($flights); ?> chuyến bay
                            </h2>
                            <form method="GET" class="flex gap-3 overflow-x-auto">
                                <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
                                <input type="hidden" name="to" value="<?php echo htmlspecialchars($to); ?>">
                                <input type="hidden" name="date" value="<?php echo $date; ?>">
                                <input type="hidden" name="passengers" value="<?php echo $passengers; ?>">
                                
                                <button type="submit" name="sort" value="price_asc" class="flex h-9 shrink-0 items-center justify-center gap-x-1.5 rounded-lg <?php echo $sortBy === 'price_asc' ? 'bg-primary/10 text-primary' : 'bg-gray-100 dark:bg-[#2a3b47] hover:bg-gray-200'; ?> pl-4 pr-3 transition-colors">
                                    <p class="text-sm font-semibold leading-normal">Giá rẻ nhất</p>
                                </button>
                                <button type="submit" name="sort" value="time_asc" class="flex h-9 shrink-0 items-center justify-center gap-x-1.5 rounded-lg <?php echo $sortBy === 'time_asc' ? 'bg-primary/10 text-primary' : 'bg-gray-100 dark:bg-[#2a3b47] hover:bg-gray-200'; ?> pl-4 pr-3 transition-colors">
                                    <p class="text-sm font-medium leading-normal">Bay nhanh nhất</p>
                                </button>
                            </form>
                        </div>

                        <div class="flex flex-col gap-4">
                            <?php if (empty($flights)): ?>
                            <div class="bg-white dark:bg-[#1a2831] p-12 rounded-xl shadow-lg text-center">
                                <span class="material-symbols-outlined text-6xl text-gray-400 mb-4">flight_takeoff</span>
                                <p class="text-xl font-bold text-gray-600 dark:text-gray-400">Không tìm thấy chuyến bay phù hợp</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Vui lòng thử tìm kiếm với điều kiện khác</p>
                            </div>
                            <?php else: ?>
                            <?php foreach ($flights as $flight): 
                                $departureTime = new DateTime($flight['departure_time']);
                                $arrivalTime = new DateTime($flight['arrival_time']);
                                $duration = $departureTime->diff($arrivalTime);
                                $durationText = $duration->h . 'h ' . $duration->i . 'm';
                            ?>
                            <div class="group/flight-card flex flex-col rounded-xl overflow-hidden shadow-lg dark:shadow-none bg-white dark:bg-[#1a2831] hover:shadow-2xl hover:ring-2 hover:ring-primary/50 dark:hover:ring-primary/70 transition-all duration-300">
                                <div class="grid grid-cols-1 md:grid-cols-[1fr_auto_1fr] items-center gap-4 p-4 sm:p-6 cursor-pointer">
                                    <div class="flex items-center gap-4 w-full">
                                        <div class="h-10 w-10 flex-shrink-0 rounded bg-primary/10 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-primary">flight</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <p class="text-lg font-bold text-[#0d171c] dark:text-white">
                                                <?php echo $departureTime->format('H:i'); ?> - <?php echo $arrivalTime->format('H:i'); ?>
                                            </p>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo htmlspecialchars($flight['airline_name']); ?></p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo $durationText; ?></p>
                                        <div class="w-24 h-px bg-gray-300 dark:bg-gray-600 my-1"></div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bay thẳng</p>
                                    </div>
                                    <div class="flex items-center justify-between sm:justify-end w-full gap-6">
                                        <div class="flex flex-col items-start sm:items-end">
                                            <p class="text-lg font-bold text-primary"><?php echo formatCurrency($flight['price']); ?></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">/khách</p>
                                        </div>
                                        <a href="booking.php?flight_id=<?php echo $flight['id']; ?>&passengers=<?php echo $passengers; ?>" class="flex min-w-[100px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold hover:bg-primary/90 transition-colors">
                                            <span class="truncate">Chọn vé</span>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Details (hidden by default, shown on hover) -->
                                <div class="h-0 overflow-hidden group-hover/flight-card:h-auto transition-all duration-300">
                                    <div class="border-t border-gray-200 dark:border-gray-700 p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <h4 class="font-bold text-[#0d171c] dark:text-white mb-2">Chi tiết chuyến bay</h4>
                                            <p class="text-gray-600 dark:text-gray-300"><?php echo $flight['departure_airport']; ?> → <?php echo $flight['arrival_airport']; ?></p>
                                            <p class="text-gray-600 dark:text-gray-300">Số hiệu: <?php echo $flight['flight_code']; ?></p>
                                            <p class="text-gray-600 dark:text-gray-300">Còn <?php echo $flight['available_seats']; ?> ghế trống</p>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[#0d171c] dark:text-white mb-2">Dịch vụ đi kèm</h4>
                                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                                <span class="material-symbols-outlined text-base text-green-500">check_circle</span>
                                                <span>Hành lý xách tay: 7kg</span>
                                            </div>
                                        </div>
                                    </div>
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
</div>
</body>
</html>