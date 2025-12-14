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
                    colors: { "primary": "#0da6f2", "background-light": "#f5f7f8", "background-dark": "#101c22" },
                    fontFamily: { "display": ["Plus Jakarta Sans", "sans-serif"] }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .active-nav { background-color: rgba(13, 166, 242, 0.1); color: #0da6f2; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="flex min-h-screen w-full">
    <aside class="flex w-64 flex-col gap-y-6 border-r border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
        <div class="flex items-center gap-3 px-2">
            <div class="bg-primary/10 text-primary rounded-lg p-2">
                <span class="material-symbols-outlined">travel_explore</span>
            </div>
            <h1 class="text-xl font-bold tracking-tight"><?php echo SITE_NAME; ?> Admin</h1>
        </div>
        <nav class="flex flex-1 flex-col gap-2">
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="dashboard.php">
                <span class="material-symbols-outlined">dashboard</span> <p class="text-sm font-medium">Dashboard</p>
            </a>
            <a class="active-nav flex items-center gap-3 rounded-lg px-3 py-2" href="qly_chuyenbay.php">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">flight</span> <p class="text-sm font-medium">Vé máy bay</p>
            </a>
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="qly_khachsan.php">
                <span class="material-symbols-outlined">hotel</span> <p class="text-sm font-medium">Khách sạn</p>
            </a>
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="qly_xe.php">
                <span class="material-symbols-outlined">directions_car</span> <p class="text-sm font-medium">Quản lý xe</p>
            </a>
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="qly_booking.php">
                <span class="material-symbols-outlined">confirmation_number</span> <p class="text-sm font-medium">Đặt chỗ</p>
            </a>
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="qly_kh.php">
                <span class="material-symbols-outlined">group</span> <p class="text-sm font-medium">Người dùng</p>
            </a>
        </nav>
        <div class="flex items-center gap-3 border-t border-gray-200 dark:border-gray-700 pt-4">
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://ui-avatars.com/api/?name=<?php echo urlencode($currentUser['full_name']); ?>&background=0da6f2&color=fff");'></div>
            <div class="flex flex-col">
                <p class="text-sm font-medium"><?php echo htmlspecialchars($currentUser['full_name']); ?></p>
                <p class="text-xs text-gray-500">Quản trị viên</p>
            </div>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto">
        <header class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white/80 px-6 py-3 backdrop-blur-sm dark:border-gray-700 dark:bg-gray-900/80">
            <h2 class="text-lg font-bold">Quản lý Chuyến bay</h2>
            <div class="flex gap-2">
                <button class="flex size-9 items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <span class="material-symbols-outlined text-gray-600 dark:text-gray-300">notifications</span>
                </button>
            </div>
        </header>

        <div class="p-6 md:p-10">
            <?php if ($flash): ?>
            <div class="mb-6 flex items-center gap-2 rounded-lg border px-4 py-3 <?php echo $flash['type'] === 'success' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700'; ?>">
                <span class="material-symbols-outlined"><?php echo $flash['type'] === 'success' ? 'check_circle' : 'error'; ?></span>
                <span class="text-sm font-medium"><?php echo htmlspecialchars($flash['message']); ?></span>
            </div>
            <?php endif; ?>

            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <h1 class="text-3xl font-black tracking-tight">Danh sách chuyến bay</h1>
                <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="flex items-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-bold text-white hover:bg-primary/90">
                    <span class="material-symbols-outlined">add</span>
                    <span>Thêm chuyến bay mới</span>
                </button>
            </div>

            <form method="GET" class="mb-6 flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/50 md:flex-row">
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input name="search" value="<?php echo htmlspecialchars($search); ?>" class="w-full rounded-lg border-gray-300 pl-10 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" placeholder="Tìm kiếm mã chuyến, sân bay..."/>
                </div>
                <div class="flex gap-3">
                    <select name="airline" class="rounded-lg border-gray-300 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" onchange="this.form.submit()">
                        <option value="">Tất cả hãng</option>
                        <?php foreach ($airlines as $airline): ?>
                        <option value="<?php echo $airline['id']; ?>" <?php echo $airlineFilter == $airline['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($airline['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        <option value="scheduled" <?php echo $statusFilter === 'scheduled' ? 'selected' : ''; ?>>Đúng giờ</option>
                        <option value="delayed" <?php echo $statusFilter === 'delayed' ? 'selected' : ''; ?>>Trễ giờ</option>
                        <option value="cancelled" <?php echo $statusFilter === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                    </select>
                </div>
            </form>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900/50">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Mã chuyến bay</th>
                            <th class="px-6 py-4 font-medium">Hãng hàng không</th>
                            <th class="px-6 py-4 font-medium">Lịch trình</th>
                            <th class="px-6 py-4 font-medium">Thời gian</th>
                            <th class="px-6 py-4 font-medium">Số ghế</th>
                            <th class="px-6 py-4 font-medium">Giá vé</th>
                            <th class="px-6 py-4 font-medium">Trạng thái</th>
                            <th class="px-6 py-4 font-medium text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php foreach ($flights as $flight): 
                            $statusClass = $flight['status'] === 'scheduled' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 
                                          ($flight['status'] === 'delayed' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : 
                                           'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300');
                            $statusText = $flight['status'] === 'scheduled' ? 'Đúng giờ' : 
                                         ($flight['status'] === 'delayed' ? 'Trễ giờ' : 'Đã hủy');
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-primary"><?php echo htmlspecialchars($flight['flight_code']); ?></td>
                            <td class="px-6 py-4 text-gray-900 dark:text-white"><?php echo htmlspecialchars($flight['airline_name']); ?></td>
                            <td class="px-6 py-4">
                                <span class="font-medium"><?php echo htmlspecialchars($flight['departure_airport']); ?></span>
                                <span class="text-gray-400 mx-1">→</span>
                                <span class="font-medium"><?php echo htmlspecialchars($flight['arrival_airport']); ?></span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                <div><?php echo formatDateTime($flight['departure_time'], 'H:i'); ?></div>
                                <div class="text-xs"><?php echo formatDateTime($flight['departure_time'], 'd/m/Y'); ?></div>
                            </td>
                            <td class="px-6 py-4 text-gray-500"><?php echo $flight['available_seats']; ?>/<?php echo $flight['total_seats']; ?></td>
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white"><?php echo formatCurrency($flight['price']); ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo $statusClass; ?>">
                                    <?php echo $statusText; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-primary dark:hover:bg-gray-800">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                    <form method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?');" class="inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $flight['id']; ?>">
                                        <button type="submit" class="rounded p-1 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-gray-200 px-2 py-4 dark:border-gray-700">
                <span class="text-sm text-gray-500">
                    Hiển thị <b><?php echo min($pagination['offset'] + 1, $totalFlights); ?>-<?php echo min($pagination['offset'] + $pagination['items_per_page'], $totalFlights); ?></b> 
                    trên <b><?php echo $totalFlights; ?></b> kết quả
                </span>
                <div class="flex gap-2">
                    <?php if ($pagination['has_prev']): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&airline=<?php echo urlencode($airlineFilter); ?>" 
                       class="rounded border border-gray-300 px-3 py-1 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800 text-sm">Trước</a>
                    <?php endif; ?>
                    
                    <?php if ($pagination['has_next']): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&airline=<?php echo urlencode($airlineFilter); ?>" 
                       class="rounded border border-gray-300 px-3 py-1 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800 text-sm">Sau</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-background-dark w-full max-w-2xl rounded-xl shadow-lg transform transition-all">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold">Thêm chuyến bay mới</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" class="p-6">
            <input type="hidden" name="action" value="add">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <label class="block mb-2 font-medium">Mã chuyến bay</label>
                    <input required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="flight_code" placeholder="VN255" type="text"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Hãng hàng không</label>
                    <select required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="airline_id">
                        <?php foreach ($airlines as $airline): ?>
                        <option value="<?php echo $airline['id']; ?>"><?php echo htmlspecialchars($airline['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Điểm đi</label>
                    <input required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="departure_airport" placeholder="SGN" type="text"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Điểm đến</label>
                    <input required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="arrival_airport" placeholder="HAN" type="text"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Ngày bay</label>
                    <input required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="departure_date" type="date"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Giờ khởi hành</label>
                    <input required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="departure_time" type="time"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Giờ đến</label>
                    <input required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="arrival_time" type="time"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Tổng số ghế</label>
                    <input required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="total_seats" placeholder="150" type="number" min="1"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Giá vé (VNĐ)</label>
                    <input required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="price" placeholder="2500000" type="number" min="0" step="1000"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Tình trạng</label>
                    <select required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="status">
                        <option value="scheduled">Đúng giờ</option>
                        <option value="delayed">Trễ giờ</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-8">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium text-sm">Hủy</button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white hover:bg-primary/90 font-bold text-sm shadow-sm">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>