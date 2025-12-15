<?php
/**
 * Quản lý Khách sạn
 */
require_once '../config/config.php';
requireAdmin();

// --- XỬ LÝ THÊM / SỬA / XÓA ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    $data = [
        'name' => cleanInput($_POST['name']),
        'address' => cleanInput($_POST['address']),
        'city' => cleanInput($_POST['city']),
        'stars' => (int)$_POST['stars'],
        'total_rooms' => (int)$_POST['total_rooms'],
        'status' => $_POST['status'] ?? 'active'
    ];

    if ($action === 'add') {
        if (db()->insert('hotels', $data)) {
            setFlashMessage('success', 'Thêm khách sạn thành công');
        } else {
            setFlashMessage('error', 'Có lỗi xảy ra');
        }
        redirect('qly_khachsan.php');
    }

    if ($action === 'edit') {
        $id = (int)$_POST['id'];
        if (db()->update('hotels', $data, 'id = ?', [$id])) {
            setFlashMessage('success', 'Cập nhật khách sạn thành công');
        } else {
            setFlashMessage('error', 'Không thể cập nhật');
        }
        redirect('qly_khachsan.php');
    }

    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        if (db()->delete('hotels', 'id = ?', [$id])) {
            setFlashMessage('success', 'Xóa khách sạn thành công');
        } else {
            setFlashMessage('error', 'Không thể xóa');
        }
        redirect('qly_khachsan.php');
    }
}

// --- TÌM KIẾM & PHÂN TRANG ---
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = $_GET['search'] ?? '';
$cityFilter = $_GET['city'] ?? '';
$starFilter = $_GET['stars'] ?? '';

$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND name LIKE ?";
    $params[] = "%$search%";
}
if ($cityFilter) {
    $where .= " AND city = ?";
    $params[] = $cityFilter;
}
if ($starFilter) {
    $where .= " AND stars = ?";
    $params[] = $starFilter;
}

$countQuery = db()->select("SELECT COUNT(*) as total FROM hotels WHERE $where", $params);
$totalItems = $countQuery ? (int)$countQuery[0]['total'] : 0;
$pagination = getPagination($page, $totalItems);

$hotels = db()->select("
    SELECT * FROM hotels 
    WHERE $where 
    ORDER BY id DESC 
    LIMIT {$pagination['items_per_page']} OFFSET {$pagination['offset']}
", $params);

// Lấy danh sách thành phố distinct để làm bộ lọc
$cities = db()->select("SELECT DISTINCT city FROM hotels ORDER BY city");

$currentUser = getCurrentUser();
$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Quản lý Khách sạn - <?php echo SITE_NAME; ?></title>
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
            <h2 class="text-lg font-bold">Quản lý Khách sạn</h2>
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
                    <h1 class="text-3xl font-black tracking-tight">Danh sách Khách sạn</h1>
                    <p class="text-gray-500 text-sm mt-1">Quản lý thông tin và tình trạng phòng.</p>
                </div>
                <button onclick="openAddModal()" class="flex items-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-bold text-white hover:bg-primary/90">
                    <span class="material-symbols-outlined">add</span>
                    <span>Thêm khách sạn</span>
                </button>
            </div>

            <form method="GET" class="mb-6 grid grid-cols-1 gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/50 md:grid-cols-4">
                <div class="md:col-span-2 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input name="search" value="<?php echo htmlspecialchars($search); ?>" class="w-full rounded-lg border-gray-300 pl-10 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" placeholder="Tìm tên khách sạn..."/>
                </div>
                <select name="city" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800">
                    <option value="">Địa điểm: Tất cả</option>
                    <?php foreach ($cities as $c): ?>
                    <option value="<?php echo $c['city']; ?>" <?php echo $cityFilter == $c['city'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['city']); ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="stars" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800">
                    <option value="">Hạng sao: Tất cả</option>
                    <?php for($i=5; $i>=1; $i--): ?>
                    <option value="<?php echo $i; ?>" <?php echo $starFilter == $i ? 'selected' : ''; ?>><?php echo $i; ?> sao</option>
                    <?php endfor; ?>
                </select>
            </form>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900/50">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Tên khách sạn</th>
                            <th class="px-6 py-4 font-medium">Địa chỉ</th>
                            <th class="px-6 py-4 font-medium">Số phòng</th>
                            <th class="px-6 py-4 font-medium">Hạng</th>
                            <th class="px-6 py-4 font-medium">Trạng thái</th>
                            <th class="px-6 py-4 font-medium text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php if (empty($hotels)): ?>
                            <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Không tìm thấy khách sạn nào.</td></tr>
                        <?php endif; ?>

                        <?php foreach ($hotels as $h): 
                            $statusClass = $h['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600';
                            $statusText = $h['status'] === 'active' ? 'Hoạt động' : ($h['status'] === 'hidden' ? 'Đã ẩn' : 'Ngừng hoạt động');
                            $hJson = htmlspecialchars(json_encode($h), ENT_QUOTES, 'UTF-8');
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($h['name']); ?></td>
                            <td class="px-6 py-4 text-gray-500"><?php echo htmlspecialchars($h['address'] . ', ' . $h['city']); ?></td>
                            <td class="px-6 py-4 text-gray-500"><?php echo $h['total_rooms']; ?></td>
                            <td class="px-6 py-4 text-yellow-500 font-bold"><?php echo $h['stars']; ?>★</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo $statusClass; ?>">
                                    <span class="size-1.5 rounded-full bg-current"></span> <?php echo $statusText; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="openEditModal(<?php echo $hJson; ?>)" class="rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-primary"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                    <form method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa khách sạn này?');" class="inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $h['id']; ?>">
                                        <button class="rounded p-1 text-gray-500 hover:bg-red-50 hover:text-red-600"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-gray-200 px-2 py-4 dark:border-gray-700">
                <span class="text-sm text-gray-500">Hiển thị <?php echo $totalItems > 0 ? min($pagination['offset'] + 1, $totalItems) : 0; ?>-<?php echo min($pagination['offset'] + $pagination['items_per_page'], $totalItems); ?> trên <?php echo $totalItems; ?></span>
                <div class="flex gap-2">
                    <?php if ($pagination['has_prev']): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" class="rounded border px-3 py-1 hover:bg-gray-50 text-sm">Trước</a>
                    <?php endif; ?>
                    <?php if ($pagination['has_next']): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" class="rounded border px-3 py-1 hover:bg-gray-50 text-sm">Sau</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-background-dark w-full max-w-lg rounded-xl shadow-lg">
        <div class="flex items-center justify-between p-6 border-b dark:border-gray-700">
            <h3 class="text-xl font-bold">Thêm khách sạn mới</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-500"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" class="p-6">
            <input type="hidden" name="action" value="add">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2"><label class="block mb-1 text-sm font-medium">Tên khách sạn</label><input required name="name" class="w-full rounded-lg border-gray-300 dark:bg-gray-800"/></div>
                <div class="col-span-2"><label class="block mb-1 text-sm font-medium">Địa chỉ</label><input required name="address" class="w-full rounded-lg border-gray-300 dark:bg-gray-800"/></div>
                <div><label class="block mb-1 text-sm font-medium">Thành phố</label><input required name="city" class="w-full rounded-lg border-gray-300 dark:bg-gray-800"/></div>
                <div><label class="block mb-1 text-sm font-medium">Số sao</label>
                    <select name="stars" class="w-full rounded-lg border-gray-300 dark:bg-gray-800">
                        <option value="5">5 Sao</option>
                        <option value="4">4 Sao</option>
                        <option value="3">3 Sao</option>
                        <option value="2">2 Sao</option>
                    </select>
                </div>
                <div><label class="block mb-1 text-sm font-medium">Tổng số phòng</label><input required type="number" name="total_rooms" class="w-full rounded-lg border-gray-300 dark:bg-gray-800" min="1"/></div>
                <div><label class="block mb-1 text-sm font-medium">Trạng thái</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 dark:bg-gray-800">
                        <option value="active">Hoạt động</option>
                        <option value="hidden">Tạm ẩn</option>
                        <option value="inactive">Ngừng hoạt động</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 rounded-lg border">Hủy</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white font-bold">Thêm mới</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white dark:bg-background-dark w-full max-w-lg rounded-xl shadow-lg">
        <div class="flex items-center justify-between p-6 border-b dark:border-gray-700">
            <h3 class="text-xl font-bold">Cập nhật khách sạn</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-500"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" class="p-6">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2"><label class="block mb-1 text-sm font-medium">Tên khách sạn</label><input required id="edit_name" name="name" class="w-full rounded-lg border-gray-300 dark:bg-gray-800"/></div>
                <div class="col-span-2"><label class="block mb-1 text-sm font-medium">Địa chỉ</label><input required id="edit_address" name="address" class="w-full rounded-lg border-gray-300 dark:bg-gray-800"/></div>
                <div><label class="block mb-1 text-sm font-medium">Thành phố</label><input required id="edit_city" name="city" class="w-full rounded-lg border-gray-300 dark:bg-gray-800"/></div>
                <div><label class="block mb-1 text-sm font-medium">Số sao</label>
                    <select id="edit_stars" name="stars" class="w-full rounded-lg border-gray-300 dark:bg-gray-800">
                        <option value="5">5 Sao</option>
                        <option value="4">4 Sao</option>
                        <option value="3">3 Sao</option>
                        <option value="2">2 Sao</option>
                    </select>
                </div>
                <div><label class="block mb-1 text-sm font-medium">Tổng số phòng</label><input required type="number" id="edit_total_rooms" name="total_rooms" class="w-full rounded-lg border-gray-300 dark:bg-gray-800" min="1"/></div>
                <div><label class="block mb-1 text-sm font-medium">Trạng thái</label>
                    <select id="edit_status" name="status" class="w-full rounded-lg border-gray-300 dark:bg-gray-800">
                        <option value="active">Hoạt động</option>
                        <option value="hidden">Tạm ẩn</option>
                        <option value="inactive">Ngừng hoạt động</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 rounded-lg border">Hủy</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white font-bold">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }
    function openEditModal(data) {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_name').value = data.name;
        document.getElementById('edit_address').value = data.address;
        document.getElementById('edit_city').value = data.city;
        document.getElementById('edit_stars').value = data.stars;
        document.getElementById('edit_total_rooms').value = data.total_rooms;
        document.getElementById('edit_status').value = data.status;
    }
</script>
</body>
</html>