<?php
/**
 * Quản lý chuyến bay
 */

require_once '../config/config.php';
requireAdmin();

// Xử lý thêm/sửa/xóa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $data = [
            'flight_code' => cleanInput($_POST['flight_code']),
            'airline_id' => (int)$_POST['airline_id'],
            'departure_airport' => cleanInput($_POST['departure_airport']),
            'arrival_airport' => cleanInput($_POST['arrival_airport']),
            'departure_time' => $_POST['departure_date'] . ' ' . $_POST['departure_time'],
            'arrival_time' => $_POST['departure_date'] . ' ' . $_POST['arrival_time'],
            'total_seats' => (int)$_POST['total_seats'],
            'available_seats' => (int)$_POST['total_seats'],
            'price' => (float)$_POST['price'],
            'status' => $_POST['status'] ?? 'scheduled'
        ];
        
        if (db()->insert('flights', $data)) {
            setFlashMessage('success', 'Thêm chuyến bay thành công');
        } else {
            setFlashMessage('error', 'Có lỗi xảy ra');
        }
        redirect('qly_chuyenbay.php');
    }
    
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        if (db()->delete('flights', 'id = ?', [$id])) {
            setFlashMessage('success', 'Xóa chuyến bay thành công');
        } else {
            setFlashMessage('error', 'Không thể xóa chuyến bay');
        }
        redirect('qly_chuyenbay.php');
    }
}

// Lấy danh sách hãng hàng không
$airlines = db()->select("SELECT * FROM airlines WHERE status = 'active' ORDER BY name");

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = $_GET['search'] ?? '';
$airlineFilter = $_GET['airline'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND (flight_code LIKE ? OR departure_airport LIKE ? OR arrival_airport LIKE ?)";
    $searchTerm = "%$search%";
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
}

if ($airlineFilter) {
    $where .= " AND airline_id = ?";
    $params[] = $airlineFilter;
}

if ($statusFilter) {
    $where .= " AND status = ?";
    $params[] = $statusFilter;
}

$totalFlights = db()->count('flights', $where, $params);
$pagination = getPagination($page, $totalFlights);

// Lấy danh sách chuyến bay
$flights = db()->select("
    SELECT f.*, a.name as airline_name, a.code as airline_code
    FROM flights f
    INNER JOIN airlines a ON f.airline_id = a.id
    WHERE $where
    ORDER BY f.departure_time DESC
    LIMIT {$pagination['items_per_page']} OFFSET {$pagination['offset']}
", $params);

$currentUser = getCurrentUser();
$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Quản lý chuyến bay - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
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
<body class="font-display bg-background-light dark:bg-background-dark text-[#0d171c] dark:text-gray-200">
<div class="relative flex h-auto min-h-screen w-full flex-col">
    <!-- Header -->
    <header class="sticky top-0 z-10 flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 dark:border-gray-700 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm px-4 md:px-10 py-3">
        <div class="flex items-center gap-4">
            <span class="material-symbols-outlined text-primary" style="font-size: 28px;">connecting_airports</span>
            <h2 class="text-lg font-bold tracking-tight">Quản lý vé máy bay</h2>
        </div>
        <div class="flex items-center gap-4">
            <button class="flex cursor-pointer items-center justify-center rounded-lg h-10 w-10 bg-primary/20 hover:bg-primary/30 text-primary">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://ui-avatars.com/api/?name=<?php echo urlencode($currentUser['full_name']); ?>&background=0da6f2&color=fff");'></div>
        </div>
    </header>

    <main class="flex-1 p-4 md:p-10">
        <div class="mx-auto max-w-7xl">
            <!-- Flash Message -->
            <?php if ($flash): ?>
            <div class="mb-6 bg-<?php echo $flash['type'] === 'success' ? 'green' : 'red'; ?>-50 border border-<?php echo $flash['type'] === 'success' ? 'green' : 'red'; ?>-200 text-<?php echo $flash['type'] === 'success' ? 'green' : 'red'; ?>-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <span class="material-symbols-outlined"><?php echo $flash['type'] === 'success' ? 'check_circle' : 'error'; ?></span>
                <span><?php echo htmlspecialchars($flash['message']); ?></span>
            </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <p class="text-4xl font-black tracking-tighter">Quản lý chuyến bay</p>
                <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="flex items-center justify-center gap-2 rounded-lg h-10 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-wide shadow-sm hover:bg-primary/90">
                    <span class="material-symbols-outlined">add</span>
                    Thêm chuyến bay mới
                </button>
            </div>

            <!-- Search & Filter -->
            <div class="p-4 bg-white dark:bg-background-dark/50 rounded-xl shadow-sm mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="col-span-1 lg:col-span-2">
                        <label class="flex flex-col w-full">
                            <div class="flex w-full flex-1 items-stretch rounded-lg h-12">
                                <div class="text-gray-400 flex border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 items-center justify-center pl-4 rounded-l-lg">
                                    <span class="material-symbols-outlined">search</span>
                                </div>
                                <input name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-r-lg text-sm text-[#0d171c] dark:text-gray-200 focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 h-full placeholder:text-gray-400 px-4" placeholder="Tìm kiếm theo mã chuyến bay, hãng hàng không..."/>
                            </div>
                        </label>
                    </div>
                    <div class="col-span-1">
                        <select name="airline" class="form-select h-12 w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 text-left">
                            <option value="">Hãng hàng không</option>
                            <?php foreach ($airlines as $airline): ?>
                            <option value="<?php echo $airline['id']; ?>" <?php echo $airlineFilter == $airline['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($airline['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-span-1">
                        <select name="status" class="form-select h-12 w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 text-left">
                            <option value="">Tình trạng chuyến bay</option>
                            <option value="scheduled" <?php echo $statusFilter === 'scheduled' ? 'selected' : ''; ?>>Đúng giờ</option>
                            <option value="delayed" <?php echo $statusFilter === 'delayed' ? 'selected' : ''; ?>>Trễ giờ</option>
                            <option value="cancelled" <?php echo $statusFilter === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-span-1 lg:col-span-4 flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">Tìm kiếm</button>
                        <a href="qly_chuyenbay.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Đặt lại</a>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto bg-white dark:bg-background-dark/50 rounded-xl shadow-sm">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-200 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Mã chuyến bay</th>
                            <th class="px-6 py-4 font-medium">Hãng hàng không</th>
                            <th class="px-6 py-4 font-medium">Điểm đi/đến</th>
                            <th class="px-6 py-4 font-medium">Thời gian</th>
                            <th class="px-6 py-4 font-medium">Số ghế</th>
                            <th class="px-6 py-4 font-medium">Giá vé</th>
                            <th class="px-6 py-4 font-medium">Tình trạng</th>
                            <th class="px-6 py-4 font-medium text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php foreach ($flights as $flight): 
                            $statusClass = $flight['status'] === 'scheduled' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 
                                          ($flight['status'] === 'delayed' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300' : 
                                           'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300');
                            $statusText = $flight['status'] === 'scheduled' ? 'Đúng giờ' : 
                                         ($flight['status'] === 'delayed' ? 'Trễ giờ' : 'Đã hủy');
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-6 py-4 font-bold text-primary"><?php echo htmlspecialchars($flight['flight_code']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($flight['airline_name']); ?></td>
                            <td class="px-6 py-4 font-medium"><?php echo htmlspecialchars($flight['departure_airport']); ?> → <?php echo htmlspecialchars($flight['arrival_airport']); ?></td>
                            <td class="px-6 py-4"><?php echo formatDateTime($flight['departure_time'], 'd/m/Y H:i'); ?></td>
                            <td class="px-6 py-4"><?php echo $flight['available_seats']; ?>/<?php echo $flight['total_seats']; ?></td>
                            <td class="px-6 py-4"><?php echo formatCurrency($flight['price']); ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full <?php echo $statusClass; ?> px-3 py-1 text-xs font-semibold">
                                    <?php echo $statusText; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-2">
                                    <button class="p-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-300">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </button>
                                    <form method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?');" class="inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $flight['id']; ?>">
                                        <button type="submit" class="p-2 rounded-md hover:bg-red-100 dark:hover:bg-red-900/50 text-red-500">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex flex-col md:flex-row items-center justify-between mt-6 px-2">
                <span class="text-sm text-gray-500 dark:text-gray-400 mb-4 md:mb-0">
                    Hiển thị <span class="font-semibold"><?php echo min($pagination['offset'] + 1, $totalFlights); ?>-<?php echo min($pagination['offset'] + $pagination['items_per_page'], $totalFlights); ?></span> 
                    trên <span class="font-semibold"><?php echo $totalFlights; ?></span> chuyến bay
                </span>
                <div class="flex items-center space-x-2">
                    <?php if ($pagination['has_prev']): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&airline=<?php echo urlencode($airlineFilter); ?>&status=<?php echo urlencode($statusFilter); ?>" 
                       class="flex items-center justify-center h-9 w-9 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span class="material-symbols-outlined text-lg">chevron_left</span>
                    </a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 2); $i <= min($pagination['total_pages'], $page + 2); $i++): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&airline=<?php echo urlencode($airlineFilter); ?>&status=<?php echo urlencode($statusFilter); ?>" 
                       class="flex items-center justify-center h-9 w-9 rounded-lg <?php echo $i === $page ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'; ?> text-sm font-medium">
                        <?php echo $i; ?>
                    </a>
                    <?php endfor; ?>

                    <?php if ($pagination['has_next']): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&airline=<?php echo urlencode($airlineFilter); ?>&status=<?php echo urlencode($statusFilter); ?>" 
                       class="flex items-center justify-center h-9 w-9 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span class="material-symbols-outlined text-lg">chevron_right</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-background-dark w-full max-w-2xl rounded-xl shadow-lg">
        <div class="flex items-center justify-between p-4 md:p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold">Thêm chuyến bay mới</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" class="p-4 md:p-6">
            <input type="hidden" name="action" value="add">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <label class="block mb-1.5 font-medium" for="flight_code">Mã chuyến bay</label>
                    <input required class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700" id="flight_code" name="flight_code" placeholder="VN255" type="text"/>
                </div>
                <div>
                    <label class="block mb-1.5 font-medium" for="airline_id">Hãng hàng không</label>
                    <select required class="form-select w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700" id="airline_id" name="airline_id">
                        <?php foreach ($airlines as $airline): ?>
                        <option value="<?php echo $airline['id']; ?>"><?php echo htmlspecialchars($airline['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-1.5 font-medium">Điểm đi</label>
                    <input required class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700" name="departure_airport" placeholder="SGN" type="text"/>
                </div>
                <div>
                    <label class="block mb-1.5 font-medium">Điểm đến</label>
                    <input required class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700" name="arrival_airport" placeholder="HAN" type="text"/>
                </div>
                <div>
                    <label class="block mb-1.5 font-medium">Ngày bay</label>
                    <input required class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700" name="departure_date" type="date"/>
                </div>
                <div>
                    <label class="block mb-1.5 font-medium">Giờ khởi hành</label>
                    <input required class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700" name="departure_time" type="time"/>
                </div>
                <div>
                    <label class="block mb-1.5 font-medium">Giờ đến</label>
                    <input required class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700" name="arrival_time" type="time"/>
                </div>
                <div>
                    <label class="block mb-1.5 font-medium">Tổng số ghế</label>
                    <input required class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700" name="total_seats" placeholder="150" type="number" min="1"/>
                </div>
                <div>
                    <label class="block mb-1.5 font-medium">Giá vé (VNĐ)</label>
                    <input required class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700" name="price" placeholder="2500000" type="number" min="0" step="1000"/>
                </div>
                <div>
                    <label class="block mb-1.5 font-medium">Tình trạng</label>
                    <select required class="form-select w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700" name="status">
                        <option value="scheduled">Đúng giờ</option>
                        <option value="delayed">Trễ giờ</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex items-center justify-center rounded-lg h-10 px-5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm font-bold hover:bg-gray-300 dark:hover:bg-gray-600">Hủy</button>
                <button type="submit" class="flex items-center justify-center gap-2 rounded-lg h-10 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-wide shadow-sm hover:bg-primary/90">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>