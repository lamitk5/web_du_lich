<?php
/**
 * Quản lý chuyến bay - Phiên bản đã sửa lỗi Logic & Định dạng ngày giờ
 */

require_once '../config/config.php';

// --- KIỂM TRA QUYỀN ADMIN ---
if (!function_exists('requireAdmin')) {
    function requireAdmin() {
        if (!Auth::isLoggedIn() || !Auth::isAdmin()) {
            header('Location: ../login.php'); 
            exit;
        }
    }
}
requireAdmin();

// --- XỬ LÝ FORM (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // 1. XỬ LÝ XÓA
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        if (db()->delete('flights', 'id = ?', [$id])) {
            setFlashMessage('success', 'Xóa chuyến bay thành công');
        } else {
            setFlashMessage('error', 'Không thể xóa (Có thể đã có vé đặt cho chuyến này)');
        }
        redirect('qly_chuyenbay.php');
    }

    // 2. CHUẨN BỊ DỮ LIỆU CHUNG CHO ADD VÀ EDIT
    if ($action === 'add' || $action === 'edit') {
        // Lấy dữ liệu từ Form
        $flight_code = cleanInput($_POST['flight_code']);
        $airline_id  = (int)$_POST['airline_id'];
        
        // Cắt chuỗi sân bay tối đa 10 ký tự (theo cấu trúc DB) và viết hoa
        $depAirport  = strtoupper(substr(cleanInput($_POST['departure_airport']), 0, 10));
        $arrAirport  = strtoupper(substr(cleanInput($_POST['arrival_airport']), 0, 10));
        
        $total_seats = (int)$_POST['total_seats'];
        $price       = (float)$_POST['price'];
        $status      = $_POST['status'];

        // Xử lý Ngày & Giờ
        $depDateRaw = $_POST['departure_date']; // YYYY-MM-DD
        $depTimeRaw = $_POST['departure_time']; // HH:MM
        $arrTimeRaw = $_POST['arrival_time'];   // HH:MM

        $departure_full = "$depDateRaw $depTimeRaw:00"; 

        // Logic bay qua đêm: Nếu giờ đến nhỏ hơn giờ đi => Sang ngày hôm sau
        if (strtotime($arrTimeRaw) < strtotime($depTimeRaw)) {
            $arrDate = date('Y-m-d', strtotime("$depDateRaw +1 day"));
            $arrival_full = "$arrDate $arrTimeRaw:00";
        } else {
            // Bay trong ngày
            $arrival_full = "$depDateRaw $arrTimeRaw:00";
        }

        // Dữ liệu cơ bản
        $data = [
            'flight_code'       => $flight_code,
            'airline_id'        => $airline_id,
            'departure_airport' => $depAirport,
            'arrival_airport'   => $arrAirport,
            'departure_time'    => $departure_full,
            'arrival_time'      => $arrival_full,
            'total_seats'       => $total_seats,
            'price'             => $price,
            'status'            => $status
        ];

        // 3. THỰC HIỆN THÊM MỚI (ADD)
        if ($action === 'add') {
            // Khi thêm mới, ghế trống = tổng ghế
            $data['available_seats'] = $total_seats;

            if (db()->insert('flights', $data)) {
                setFlashMessage('success', 'Thêm chuyến bay mới thành công!');
            } else {
                $err = db()->getLastError();
                if (strpos($err, 'Duplicate') !== false) {
                    setFlashMessage('error', "Mã chuyến bay <b>$flight_code</b> đã tồn tại!");
                } else {
                    setFlashMessage('error', "Lỗi hệ thống: " . $err);
                }
            }
        }

        // 4. THỰC HIỆN CẬP NHẬT (EDIT)
        if ($action === 'edit') {
            $id = (int)$_POST['id'];
            if (db()->update('flights', $data, 'id = ?', [$id])) {
                setFlashMessage('success', 'Cập nhật thông tin chuyến bay thành công!');
            } else {
                setFlashMessage('error', 'Lỗi cập nhật: ' . db()->getLastError());
            }
        }

        redirect('qly_chuyenbay.php');
    }
}

// --- LẤY DỮ LIỆU HIỂN THỊ ---

// Lấy danh sách hãng bay
$airlines = db()->select("SELECT * FROM airlines WHERE status = 'active' ORDER BY name");

// Phân trang & Tìm kiếm
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = $_GET['search'] ?? '';
$airlineFilter = $_GET['airline'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND (f.flight_code LIKE ? OR f.departure_airport LIKE ? OR f.arrival_airport LIKE ?)";
    $term = "%$search%";
    $params = array_merge($params, [$term, $term, $term]);
}
if ($airlineFilter) {
    $where .= " AND f.airline_id = ?";
    $params[] = $airlineFilter;
}
if ($statusFilter) {
    $where .= " AND f.status = ?";
    $params[] = $statusFilter;
}

// Đếm tổng
$countQ = db()->select("SELECT COUNT(*) as total FROM flights f WHERE $where", $params);
$totalFlights = $countQ ? (int)$countQ[0]['total'] : 0;
$pagination = getPagination($page, $totalFlights);

// Lấy danh sách chuyến bay
$flights = db()->select("
    SELECT f.*, a.name as airline_name 
    FROM flights f
    INNER JOIN airlines a ON f.airline_id = a.id
    WHERE $where
    ORDER BY f.departure_time DESC
    LIMIT {$pagination['items_per_page']} OFFSET {$pagination['offset']}
", $params);

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
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="flex min-h-screen w-full">
    <?php include 'components/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto">
        <?php 
        $pageTitle = 'Quản lý Chuyến bay';
        include 'components/header.php'; 
        ?>
        <div class="p-6 md:p-10">
            <?php if ($flash): ?>
            <div class="mb-6 flex items-center gap-2 rounded-lg border px-4 py-3 <?php echo $flash['type'] === 'success' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700'; ?>">
                <span class="material-symbols-outlined"><?php echo $flash['type'] === 'success' ? 'check_circle' : 'error'; ?></span>
                <span class="text-sm font-medium"><?php echo htmlspecialchars($flash['message']); ?></span>
            </div>
            <?php endif; ?>

            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <h1 class="text-3xl font-black tracking-tight">Danh sách chuyến bay</h1>
                <button onclick="openAddModal()" class="flex items-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-bold text-white hover:bg-primary/90 shadow-lg shadow-primary/20">
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
                        <?php foreach ($airlines as $al): ?>
                        <option value="<?php echo $al['id']; ?>" <?php echo $airlineFilter == $al['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($al['name']); ?>
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
                </div>
            </form>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900/50">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Mã chuyến</th>
                            <th class="px-6 py-4 font-medium">Hãng bay</th>
                            <th class="px-6 py-4 font-medium">Lịch trình</th>
                            <th class="px-6 py-4 font-medium">Thời gian</th>
                            <th class="px-6 py-4 font-medium">Ghế (Trống/Tổng)</th>
                            <th class="px-6 py-4 font-medium">Giá vé</th>
                            <th class="px-6 py-4 font-medium">Trạng thái</th>
                            <th class="px-6 py-4 font-medium text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php if (empty($flights)): ?>
                            <tr><td colspan="8" class="px-6 py-8 text-center text-gray-500">Không tìm thấy dữ liệu.</td></tr>
                        <?php endif; ?>

                        <?php foreach ($flights as $f): 
                            $stClass = match($f['status']) {
                                'scheduled' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                                'delayed'   => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                'completed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                                default     => 'bg-gray-100'
                            };
                            $stName = match($f['status']) {
                                'scheduled' => 'Đúng giờ',
                                'delayed'   => 'Trễ giờ',
                                'cancelled' => 'Đã hủy',
                                'completed' => 'Hoàn thành',
                                default     => $f['status']
                            };
                            $fJson = htmlspecialchars(json_encode($f), ENT_QUOTES, 'UTF-8');
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-primary"><?php echo htmlspecialchars($f['flight_code']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($f['airline_name']); ?></td>
                            <td class="px-6 py-4">
                                <span class="font-bold"><?php echo htmlspecialchars($f['departure_airport']); ?></span>
                                <span class="text-gray-400 mx-1">→</span>
                                <span class="font-bold"><?php echo htmlspecialchars($f['arrival_airport']); ?></span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                <div class="font-medium"><?php echo date('H:i', strtotime($f['departure_time'])); ?></div>
                                <div class="text-xs text-gray-500"><?php echo date('d/m/Y', strtotime($f['departure_time'])); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-primary"><?php echo $f['available_seats']; ?></span>
                                <span class="text-gray-400">/</span>
                                <span><?php echo $f['total_seats']; ?></span>
                            </td>
                            <td class="px-6 py-4 font-semibold"><?php echo number_format($f['price'], 0, ',', '.'); ?>đ</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo $stClass; ?>">
                                    <?php echo $stName; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="openEditModal(<?php echo $fJson; ?>)" class="rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-primary transition">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                    <form method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa chuyến bay này?');" class="inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $f['id']; ?>">
                                        <button type="submit" class="rounded p-1 text-gray-500 hover:bg-red-50 hover:text-red-600 transition">
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
                <span class="text-sm text-gray-500">Hiển thị <b><?php echo $totalFlights > 0 ? min($pagination['offset'] + 1, $totalFlights) : 0; ?>-<?php echo min($pagination['offset'] + $pagination['items_per_page'], $totalFlights); ?></b> của <b><?php echo $totalFlights; ?></b></span>
                <div class="flex gap-2">
                    <?php if ($pagination['has_prev']): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&airline=<?php echo urlencode($airlineFilter); ?>&status=<?php echo urlencode($statusFilter); ?>" class="rounded border px-3 py-1 text-sm hover:bg-gray-50">Trước</a>
                    <?php endif; ?>
                    <?php if ($pagination['has_next']): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&airline=<?php echo urlencode($airlineFilter); ?>&status=<?php echo urlencode($statusFilter); ?>" class="rounded border px-3 py-1 text-sm hover:bg-gray-50">Sau</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 transition-opacity backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-xl shadow-2xl transform transition-all">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-xl font-bold">Thêm chuyến bay mới</h3>
            <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" class="p-6">
            <input type="hidden" name="action" value="add">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <label class="block mb-2 font-medium">Mã chuyến bay</label>
                    <input required name="flight_code" type="text" placeholder="VN123" class="w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Hãng hàng không</label>
                    <select required name="airline_id" class="w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600">
                        <?php foreach ($airlines as $al): ?>
                        <option value="<?php echo $al['id']; ?>"><?php echo htmlspecialchars($al['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Điểm đi (Mã SB)</label>
                    <input required name="departure_airport" type="text" placeholder="SGN" maxlength="10" class="w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 uppercase"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Điểm đến (Mã SB)</label>
                    <input required name="arrival_airport" type="text" placeholder="HAN" maxlength="10" class="w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 uppercase"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Ngày bay</label>
                    <input required name="departure_date" type="date" class="w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600"/>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block mb-2 font-medium">Giờ đi</label>
                        <input required name="departure_time" type="time" class="w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600"/>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium">Giờ đến</label>
                        <input required name="arrival_time" type="time" class="w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600"/>
                    </div>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Tổng số ghế</label>
                    <input required name="total_seats" type="number" min="1" placeholder="180" class="w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Giá vé (VNĐ)</label>
                    <input required name="price" type="number" min="0" step="1000" placeholder="1500000" class="w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600"/>
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-2 font-medium">Trạng thái</label>
                    <select required name="status" class="w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600">
                        <option value="scheduled">Đúng giờ (Scheduled)</option>
                        <option value="delayed">Trễ giờ (Delayed)</option>
                        <option value="cancelled">Đã hủy (Cancelled)</option>
                        <option value="completed">Hoàn thành (Completed)</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-8">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium">Hủy</button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white hover:bg-primary/90 font-bold shadow-md">Lưu chuyến bay</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 transition-opacity backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-xl shadow-2xl transform transition-all">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-xl font-bold">Cập nhật chuyến bay</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" class="p-6">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id"> 
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <label class="block mb-2 font-medium">Mã chuyến bay</label>
                    <input required id="edit_flight_code" name="flight_code" type="text" class="w-full rounded-lg border-gray-300 focus:ring-primary dark:bg-gray-700 dark:border-gray-600"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Hãng hàng không</label>
                    <select required id="edit_airline_id" name="airline_id" class="w-full rounded-lg border-gray-300 focus:ring-primary dark:bg-gray-700 dark:border-gray-600">
                        <?php foreach ($airlines as $al): ?>
                        <option value="<?php echo $al['id']; ?>"><?php echo htmlspecialchars($al['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Điểm đi</label>
                    <input required id="edit_departure_airport" name="departure_airport" maxlength="10" type="text" class="w-full rounded-lg border-gray-300 focus:ring-primary dark:bg-gray-700 dark:border-gray-600 uppercase"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Điểm đến</label>
                    <input required id="edit_arrival_airport" name="arrival_airport" maxlength="10" type="text" class="w-full rounded-lg border-gray-300 focus:ring-primary dark:bg-gray-700 dark:border-gray-600 uppercase"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Ngày bay</label>
                    <input required id="edit_departure_date" name="departure_date" type="date" class="w-full rounded-lg border-gray-300 focus:ring-primary dark:bg-gray-700 dark:border-gray-600"/>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block mb-2 font-medium">Giờ đi</label>
                        <input required id="edit_departure_time" name="departure_time" type="time" class="w-full rounded-lg border-gray-300 focus:ring-primary dark:bg-gray-700 dark:border-gray-600"/>
                    </div>
                    <div>
                        <label class="block mb-2 font-medium">Giờ đến</label>
                        <input required id="edit_arrival_time" name="arrival_time" type="time" class="w-full rounded-lg border-gray-300 focus:ring-primary dark:bg-gray-700 dark:border-gray-600"/>
                    </div>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Tổng ghế (Không đổi ghế trống)</label>
                    <input required id="edit_total_seats" name="total_seats" type="number" min="1" class="w-full rounded-lg border-gray-300 focus:ring-primary dark:bg-gray-700 dark:border-gray-600"/>
                </div>
                <div>
                    <label class="block mb-2 font-medium">Giá vé</label>
                    <input required id="edit_price" name="price" type="number" min="0" step="1000" class="w-full rounded-lg border-gray-300 focus:ring-primary dark:bg-gray-700 dark:border-gray-600"/>
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-2 font-medium">Trạng thái</label>
                    <select required id="edit_status" name="status" class="w-full rounded-lg border-gray-300 focus:ring-primary dark:bg-gray-700 dark:border-gray-600">
                        <option value="scheduled">Đúng giờ</option>
                        <option value="delayed">Trễ giờ</option>
                        <option value="cancelled">Đã hủy</option>
                        <option value="completed">Hoàn thành</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-8">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium">Hủy</button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white hover:bg-primary/90 font-bold shadow-md">Cập nhật ngay</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }
    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    function openEditModal(data) {
        document.getElementById('editModal').classList.remove('hidden');
        
        // Điền dữ liệu
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_flight_code').value = data.flight_code;
        document.getElementById('edit_airline_id').value = data.airline_id;
        document.getElementById('edit_departure_airport').value = data.departure_airport;
        document.getElementById('edit_arrival_airport').value = data.arrival_airport;
        document.getElementById('edit_total_seats').value = data.total_seats;
        document.getElementById('edit_price').value = Math.floor(data.price); // Xóa số thập phân nếu có
        document.getElementById('edit_status').value = data.status;

        // Xử lý tách ngày giờ từ chuỗi "YYYY-MM-DD HH:MM:SS"
        // data.departure_time ví dụ: "2025-12-25 18:30:00"
        const depParts = data.departure_time.split(' ');
        const arrParts = data.arrival_time.split(' ');
        
        if (depParts.length >= 2) {
            document.getElementById('edit_departure_date').value = depParts[0];
            document.getElementById('edit_departure_time').value = depParts[1].substring(0, 5); // Lấy HH:MM
        }
        if (arrParts.length >= 2) {
            document.getElementById('edit_arrival_time').value = arrParts[1].substring(0, 5); // Lấy HH:MM
        }
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
    
    // Đóng khi click ra ngoài
    window.onclick = function(e) {
        if (e.target.id === 'addModal') closeAddModal();
        if (e.target.id === 'editModal') closeEditModal();
    }
</script>
</body>
</html>