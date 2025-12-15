<?php
/**
 * Quản lý Đặt chỗ
 */

require_once '../config/config.php';
requireAdmin();

// --- XỬ LÝ HÀNH ĐỘNG ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Cập nhật trạng thái
    if ($action === 'update_status') {
        $id = (int)$_POST['id'];
        $status = cleanInput($_POST['status']);
        
        if (db()->update('bookings', ['status' => $status], 'id = ?', [$id])) {
            setFlashMessage('success', 'Cập nhật trạng thái thành công');
        } else {
            setFlashMessage('error', 'Có lỗi xảy ra');
        }
        redirect('qly_booking.php');
    }
    
    // Xóa đặt chỗ
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        if (db()->delete('bookings', 'id = ?', [$id])) {
            setFlashMessage('success', 'Xóa đặt chỗ thành công');
        } else {
            setFlashMessage('error', 'Không thể xóa đặt chỗ');
        }
        redirect('qly_booking.php');
    }
}

// --- TÌM KIẾM & PHÂN TRANG ---
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = $_GET['search'] ?? '';
$typeFilter = $_GET['type'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND (b.booking_code LIKE ? OR u.full_name LIKE ? OR u.email LIKE ?)";
    $searchTerm = "%$search%";
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
}

if ($typeFilter) {
    $where .= " AND b.booking_type = ?";
    $params[] = $typeFilter;
}

if ($statusFilter) {
    $where .= " AND b.status = ?";
    $params[] = $statusFilter;
}

$totalBookings = db()->count('bookings b INNER JOIN users u ON b.user_id = u.id', $where, $params);
$pagination = getPagination($page, $totalBookings);

$bookings = db()->select("
    SELECT b.*, u.full_name, u.email, u.phone
    FROM bookings b
    INNER JOIN users u ON b.user_id = u.id
    WHERE $where
    ORDER BY b.created_at DESC
    LIMIT {$pagination['items_per_page']} OFFSET {$pagination['offset']}
", $params);

// Thống kê nhanh (để hiển thị cards phía trên nếu cần, hoặc giữ logic cũ)
$stats = db()->selectOne("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
        SUM(total_amount) as total_revenue
    FROM bookings
");

$currentUser = getCurrentUser();
$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Quản lý Đặt chỗ - <?php echo SITE_NAME; ?></title>
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
            <h2 class="text-lg font-bold">Quản lý Đặt chỗ</h2>
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
                <div>
                    <h1 class="text-3xl font-black tracking-tight">Danh sách Đặt chỗ</h1>
                    <p class="text-gray-500 text-sm mt-1">Theo dõi và xử lý đơn hàng của khách hàng.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/50">
                    <p class="text-xs font-medium text-gray-500">Tổng doanh thu</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white"><?php echo formatCurrency($stats['total_revenue']); ?></p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/50">
                    <p class="text-xs font-medium text-green-600">Đã xác nhận</p>
                    <p class="mt-1 text-2xl font-bold text-green-700"><?php echo $stats['confirmed']; ?> đơn</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/50">
                    <p class="text-xs font-medium text-yellow-600">Chờ xử lý</p>
                    <p class="mt-1 text-2xl font-bold text-yellow-700"><?php echo $stats['pending']; ?> đơn</p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/50">
                    <p class="text-xs font-medium text-red-600">Đã hủy</p>
                    <p class="mt-1 text-2xl font-bold text-red-700"><?php echo $stats['cancelled']; ?> đơn</p>
                </div>
            </div>

            <form method="GET" class="mb-6 flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/50 md:flex-row">
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input name="search" value="<?php echo htmlspecialchars($search); ?>" class="w-full rounded-lg border-gray-300 pl-10 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" placeholder="Mã đơn, tên khách, email..."/>
                </div>
                <div class="flex gap-3">
                    <select name="type" class="rounded-lg border-gray-300 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" onchange="this.form.submit()">
                        <option value="">Tất cả dịch vụ</option>
                        <option value="flight" <?php echo $typeFilter === 'flight' ? 'selected' : ''; ?>>Vé máy bay</option>
                        <option value="hotel" <?php echo $typeFilter === 'hotel' ? 'selected' : ''; ?>>Khách sạn</option>
                        <option value="vehicle" <?php echo $typeFilter === 'vehicle' ? 'selected' : ''; ?>>Dịch vụ xe</option>
                    </select>
                    <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Chờ xử lý</option>
                        <option value="confirmed" <?php echo $statusFilter === 'confirmed' ? 'selected' : ''; ?>>Đã xác nhận</option>
                        <option value="completed" <?php echo $statusFilter === 'completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                        <option value="cancelled" <?php echo $statusFilter === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                    </select>
                    <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">
                        Lọc
                    </button>
                </div>
            </form>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900/50">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Mã đơn</th>
                            <th class="px-6 py-4 font-medium">Khách hàng</th>
                            <th class="px-6 py-4 font-medium">Dịch vụ</th>
                            <th class="px-6 py-4 font-medium">Ngày đặt</th>
                            <th class="px-6 py-4 font-medium">Tổng tiền</th>
                            <th class="px-6 py-4 font-medium">Trạng thái</th>
                            <th class="px-6 py-4 font-medium text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php if (empty($bookings)): ?>
                            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">Không tìm thấy đơn đặt chỗ nào.</td></tr>
                        <?php endif; ?>

                        <?php foreach ($bookings as $b): 
                            $statusClass = ''; $statusText = '';
                            switch($b['status']) {
                                case 'confirmed': $statusClass = 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'; $statusText = 'Đã xác nhận'; break;
                                case 'pending': $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300'; $statusText = 'Chờ xử lý'; break;
                                case 'cancelled': $statusClass = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'; $statusText = 'Đã hủy'; break;
                                case 'completed': $statusClass = 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300'; $statusText = 'Hoàn thành'; break;
                            }
                            
                            $serviceIcon = '';
                            if ($b['booking_type'] == 'flight') $serviceIcon = 'flight';
                            elseif ($b['booking_type'] == 'hotel') $serviceIcon = 'hotel';
                            elseif ($b['booking_type'] == 'vehicle') $serviceIcon = 'directions_car';
                            else $serviceIcon = 'loyalty';
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-primary"><?php echo htmlspecialchars($b['booking_code']); ?></td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($b['full_name']); ?></div>
                                <div class="text-xs text-gray-500"><?php echo htmlspecialchars($b['email']); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm text-gray-400"><?php echo $serviceIcon; ?></span>
                                    <span class="capitalize"><?php echo $b['booking_type']; ?></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                <div><?php echo formatDateTime($b['created_at'], 'H:i'); ?></div>
                                <div class="text-xs"><?php echo formatDateTime($b['created_at'], 'd/m/Y'); ?></div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                <?php echo formatCurrency($b['total_amount']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo $statusClass; ?>">
                                    <?php echo $statusText; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="booking_detail.php?id=<?php echo $b['id']; ?>" class="rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-primary dark:hover:bg-gray-800" title="Xem chi tiết">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                    
                                    <form method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?');" class="inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $b['id']; ?>">
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
                    Hiển thị <b><?php echo $totalBookings > 0 ? min($pagination['offset'] + 1, $totalBookings) : 0; ?>-<?php echo min($pagination['offset'] + $pagination['items_per_page'], $totalBookings); ?></b> 
                    trên <b><?php echo $totalBookings; ?></b> kết quả
                </span>
                <div class="flex gap-2">
                    <?php if ($pagination['has_prev']): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&type=<?php echo urlencode($typeFilter); ?>&status=<?php echo urlencode($statusFilter); ?>" 
                       class="rounded border border-gray-300 px-3 py-1 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800 text-sm">Trước</a>
                    <?php endif; ?>
                    
                    <?php if ($pagination['has_next']): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&type=<?php echo urlencode($typeFilter); ?>&status=<?php echo urlencode($statusFilter); ?>" 
                       class="rounded border border-gray-300 px-3 py-1 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800 text-sm">Sau</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>