<?php
/**
 * Quản lý chuyến bay
 */

require_once '../config/config.php';
requireAdmin();

// --- XỬ LÝ PHP (Thêm/Sửa/Xóa) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Chuẩn bị dữ liệu chung
    $data = [
        'flight_code' => cleanInput($_POST['flight_code']),
        'airline_id' => (int)$_POST['airline_id'],
        'departure_airport' => cleanInput($_POST['departure_airport']),
        'arrival_airport' => cleanInput($_POST['arrival_airport']),
        'departure_time' => $_POST['departure_date'] . ' ' . $_POST['departure_time'],
        'arrival_time' => $_POST['departure_date'] . ' ' . $_POST['arrival_time'],
        'total_seats' => (int)$_POST['total_seats'],
        // available_seats logic xử lý bên dưới tùy action
    ];

    if ($action === 'add') {
        $data['available_seats'] = (int)$_POST['total_seats']; // Mới thêm thì ghế trống = tổng ghế
        $data['price'] = (float)$_POST['price'];
        $data['status'] = $_POST['status'] ?? 'scheduled';
        
        if (db()->insert('flights', $data)) {
            setFlashMessage('success', 'Thêm chuyến bay thành công');
        } else {
            setFlashMessage('error', 'Có lỗi xảy ra khi thêm');
        }
        redirect('qly_chuyenbay.php');
    }
    
    if ($action === 'edit') {
        $id = (int)$_POST['id'];
        $data['price'] = (float)$_POST['price'];
        $data['status'] = $_POST['status'];
        
        // Lưu ý: Khi sửa, ta không reset available_seats về total_seats 
        // vì có thể đã có người đặt vé. Ta chỉ update thông tin hành trình/giá.
        
        if (db()->update('flights', $data, 'id = ?', [$id])) {
            setFlashMessage('success', 'Cập nhật chuyến bay thành công');
        } else {
            setFlashMessage('error', 'Không thể cập nhật chuyến bay');
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

// --- XỬ LÝ TÌM KIẾM & PHÂN TRANG ---
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = $_GET['search'] ?? '';
$airlineFilter = $_GET['airline'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND (f.flight_code LIKE ? OR f.departure_airport LIKE ? OR f.arrival_airport LIKE ?)";
    $searchTerm = "%$search%";
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
}
if ($airlineFilter) {
    $where .= " AND f.airline_id = ?";
    $params[] = $airlineFilter;
}
if ($statusFilter) {
    $where .= " AND f.status = ?";
    $params[] = $statusFilter;
}

$countQuery = db()->select("SELECT COUNT(*) as total FROM flights f WHERE $where", $params);
$totalFlights = $countQuery ? (int)$countQuery[0]['total'] : 0;
$pagination = getPagination($page, $totalFlights);

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
<?php include 'components/sidebar.php'; ?>

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
                <button onclick="openAddModal()" class="flex items-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-bold text-white hover:bg-primary/90">
                    <span class="material-symbols-outlined">add</span>
                    <span>Thêm chuyến bay mới</span>
                </button>
            </div>

            <form method="GET" class="mb-6 flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/50 md:flex-row">
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input name="search" value="<?php echo htmlspecialchars($search); ?>" autocomplete="off" class="w-full rounded-lg border-gray-300 pl-10 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" placeholder="Tìm kiếm mã chuyến, sân bay..."/>
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
                        <option value="completed" <?php echo $statusFilter === 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                    </select>
                    <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">
                        Tìm
                    </button>
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
                        <?php if (empty($flights)): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">Không tìm thấy chuyến bay nào.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($flights as $flight): 
                            $statusClass = ''; $statusText = '';
                            switch($flight['status']) {
                                case 'scheduled': $statusClass = 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'; $statusText = 'Đúng giờ'; break;
                                case 'delayed': $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300'; $statusText = 'Trễ giờ'; break;
                                case 'cancelled': $statusClass = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'; $statusText = 'Đã hủy'; break;
                                case 'completed': $statusClass = 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300'; $statusText = 'Hoàn thành'; break;
                            }
                            $flightJson = htmlspecialchars(json_encode($flight), ENT_QUOTES, 'UTF-8');
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
                                    <button onclick="openEditModal(<?php echo $flightJson; ?>)" class="rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-primary dark:hover:bg-gray-800">
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
                <span class="text-sm text-gray-500">Hiển thị <b><?php echo $totalFlights > 0 ? min($pagination['offset'] + 1, $totalFlights) : 0; ?>-<?php echo min($pagination['offset'] + $pagination['items_per_page'], $totalFlights); ?></b> trên <b><?php echo $totalFlights; ?></b> kết quả</span>
                <div class="flex gap-2">
                    <?php if ($pagination['has_prev']): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&airline=<?php echo urlencode($airlineFilter); ?>&status=<?php echo urlencode($statusFilter); ?>" class="rounded border border-gray-300 px-3 py-1 hover:bg-gray-50 text-sm">Trước</a>
                    <?php endif; ?>
                    <?php if ($pagination['has_next']): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&airline=<?php echo urlencode($airlineFilter); ?>&status=<?php echo urlencode($statusFilter); ?>" class="rounded border border-gray-300 px-3 py-1 hover:bg-gray-50 text-sm">Sau</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 transition-opacity">
    <div class="bg-white dark:bg-background-dark w-full max-w-2xl rounded-xl shadow-lg transform transition-all">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold">Thêm chuyến bay mới</h3>
            <button type="button" onclick="closeAddModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
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
                        <option value="completed">Hoàn thành</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-8">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium text-sm">Hủy</button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white hover:bg-primary/90 font-bold text-sm shadow-sm">Thêm mới</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 transition-opacity">
    <div class="bg-white dark:bg-background-dark w-full max-w-2xl rounded-xl shadow-lg transform transition-all">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold">Cập nhật chuyến bay</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" class="p-6">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id"> <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <label class="block mb-2 font-medium">Mã chuyến bay</label>
                    <input required id="edit_flight_code" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="flight_code" type="text"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Hãng hàng không</label>
                    <select required id="edit_airline_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="airline_id">
                        <?php foreach ($airlines as $airline): ?>
                        <option value="<?php echo $airline['id']; ?>"><?php echo htmlspecialchars($airline['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Điểm đi</label>
                    <input required id="edit_departure_airport" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="departure_airport" type="text"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Điểm đến</label>
                    <input required id="edit_arrival_airport" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="arrival_airport" type="text"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Ngày bay</label>
                    <input required id="edit_departure_date" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="departure_date" type="date"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Giờ khởi hành</label>
                    <input required id="edit_departure_time" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="departure_time" type="time"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Giờ đến</label>
                    <input required id="edit_arrival_time" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="arrival_time" type="time"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Tổng số ghế</label>
                    <input required id="edit_total_seats" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="total_seats" type="number" min="1"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Giá vé (VNĐ)</label>
                    <input required id="edit_price" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="price" type="number" min="0" step="1000"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Tình trạng</label>
                    <select required id="edit_status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" name="status">
                        <option value="scheduled">Đúng giờ</option>
                        <option value="delayed">Trễ giờ</option>
                        <option value="cancelled">Đã hủy</option>
                        <option value="completed">Hoàn thành</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-8">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium text-sm">Hủy</button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white hover:bg-primary/90 font-bold text-sm shadow-sm">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
    // XỬ LÝ MODAL THÊM
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }
    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    // XỬ LÝ MODAL SỬA
    function openEditModal(data) {
        document.getElementById('editModal').classList.remove('hidden');
        
        // Điền dữ liệu vào form Edit (Lưu ý: các ID input đều có tiền tố 'edit_')
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_flight_code').value = data.flight_code;
        document.getElementById('edit_airline_id').value = data.airline_id;
        document.getElementById('edit_departure_airport').value = data.departure_airport;
        document.getElementById('edit_arrival_airport').value = data.arrival_airport;
        document.getElementById('edit_total_seats').value = data.total_seats;
        document.getElementById('edit_price').value = data.price;
        document.getElementById('edit_status').value = data.status;

        // Tách ngày giờ
        const depParts = data.departure_time.split(' ');
        const arrParts = data.arrival_time.split(' ');
        
        document.getElementById('edit_departure_date').value = depParts[0];
        document.getElementById('edit_departure_time').value = depParts[1].substring(0, 5);
        document.getElementById('edit_arrival_time').value = arrParts[1].substring(0, 5);
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
    
    // Đóng modal khi click ra vùng đen
    window.onclick = function(event) {
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');
        if (event.target == addModal) closeAddModal();
        if (event.target == editModal) closeEditModal();
    }
</script>
</body>
</html>