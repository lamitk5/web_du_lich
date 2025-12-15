<?php
/**
 * Quản lý Xe
 */
require_once '../config/config.php';
requireAdmin();

// --- XỬ LÝ THÊM / SỬA / XÓA ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Dữ liệu chung
    $data = [
        'name' => cleanInput($_POST['name']),
        'brand' => cleanInput($_POST['brand']),
        'type' => cleanInput($_POST['type']),
        'license_plate' => cleanInput($_POST['license_plate']),
        'seats' => (int)$_POST['seats'],
        'price_per_day' => (float)$_POST['price_per_day'],
        'provider' => cleanInput($_POST['provider']),
        'status' => $_POST['status'] ?? 'available'
    ];

    if ($action === 'add') {
        if (db()->insert('vehicles', $data)) {
            setFlashMessage('success', 'Thêm xe thành công');
        } else {
            setFlashMessage('error', 'Lỗi: Biển số xe có thể đã tồn tại');
        }
        redirect('qly_xe.php');
    }

    if ($action === 'edit') {
        $id = (int)$_POST['id'];
        if (db()->update('vehicles', $data, 'id = ?', [$id])) {
            setFlashMessage('success', 'Cập nhật thông tin xe thành công');
        } else {
            setFlashMessage('error', 'Không thể cập nhật xe');
        }
        redirect('qly_xe.php');
    }

    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        if (db()->delete('vehicles', 'id = ?', [$id])) {
            setFlashMessage('success', 'Xóa xe thành công');
        } else {
            setFlashMessage('error', 'Không thể xóa xe');
        }
        redirect('qly_xe.php');
    }
}

// --- XỬ LÝ TÌM KIẾM & PHÂN TRANG ---
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = $_GET['search'] ?? '';
$typeFilter = $_GET['type'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$where = "1=1";
$params = [];

if ($search) {
    $where .= " AND (name LIKE ? OR license_plate LIKE ? OR brand LIKE ?)";
    $term = "%$search%";
    $params = array_merge($params, [$term, $term, $term]);
}
if ($typeFilter) {
    $where .= " AND type = ?";
    $params[] = $typeFilter;
}
if ($statusFilter) {
    $where .= " AND status = ?";
    $params[] = $statusFilter;
}

// Đếm tổng
$countQuery = db()->select("SELECT COUNT(*) as total FROM vehicles WHERE $where", $params);
$totalItems = $countQuery ? (int)$countQuery[0]['total'] : 0;
$pagination = getPagination($page, $totalItems);

// Lấy dữ liệu
$vehicles = db()->select("
    SELECT * FROM vehicles 
    WHERE $where 
    ORDER BY id DESC 
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
    <title>Quản lý xe - <?php echo SITE_NAME; ?></title>
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
            <h2 class="text-lg font-bold">Quản lý Dịch vụ Xe</h2>
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
                <h1 class="text-3xl font-black tracking-tight">Danh sách xe</h1>
                <button onclick="openAddModal()" class="flex items-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-bold text-white hover:bg-primary/90">
                    <span class="material-symbols-outlined">add</span>
                    <span>Thêm xe mới</span>
                </button>
            </div>

            <form method="GET" class="mb-6 flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/50 md:flex-row">
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input name="search" value="<?php echo htmlspecialchars($search); ?>" class="w-full rounded-lg border-gray-300 pl-10 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" placeholder="Tìm tên xe, biển số, hãng..."/>
                </div>
                <div class="flex gap-3">
                    <select name="type" class="rounded-lg border-gray-300 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" onchange="this.form.submit()">
                        <option value="">Tất cả loại xe</option>
                        <option value="sedan" <?php echo $typeFilter === 'sedan' ? 'selected' : ''; ?>>Sedan (4 chỗ)</option>
                        <option value="suv" <?php echo $typeFilter === 'suv' ? 'selected' : ''; ?>>SUV (5-7 chỗ)</option>
                        <option value="minivan" <?php echo $typeFilter === 'minivan' ? 'selected' : ''; ?>>Minivan</option>
                        <option value="van" <?php echo $typeFilter === 'van' ? 'selected' : ''; ?>>Van (16 chỗ)</option>
                    </select>
                    <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        <option value="available" <?php echo $statusFilter === 'available' ? 'selected' : ''; ?>>Sẵn có</option>
                        <option value="rented" <?php echo $statusFilter === 'rented' ? 'selected' : ''; ?>>Đang thuê</option>
                        <option value="maintenance" <?php echo $statusFilter === 'maintenance' ? 'selected' : ''; ?>>Bảo trì</option>
                    </select>
                    <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300">Tìm</button>
                </div>
            </form>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900/50">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Tên xe</th>
                            <th class="px-6 py-4 font-medium">Nhà cung cấp</th>
                            <th class="px-6 py-4 font-medium">Biển số</th>
                            <th class="px-6 py-4 font-medium">Loại xe</th>
                            <th class="px-6 py-4 font-medium">Giá (ngày)</th>
                            <th class="px-6 py-4 font-medium">Trạng thái</th>
                            <th class="px-6 py-4 font-medium text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php if (empty($vehicles)): ?>
                            <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Không tìm thấy dữ liệu.</td></tr>
                        <?php endif; ?>
                        
                        <?php foreach ($vehicles as $v): 
                            $statusClass = $v['status'] === 'available' ? 'bg-green-100 text-green-700' : ($v['status'] === 'rented' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-700');
                            $statusText = $v['status'] === 'available' ? 'Sẵn có' : ($v['status'] === 'rented' ? 'Đang thuê' : 'Bảo trì');
                            $vJson = htmlspecialchars(json_encode($v), ENT_QUOTES, 'UTF-8');
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($v['name']); ?></td>
                            <td class="px-6 py-4 text-gray-500"><?php echo htmlspecialchars($v['provider'] ?? 'N/A'); ?></td>
                            <td class="px-6 py-4 font-mono text-gray-500"><?php echo htmlspecialchars($v['license_plate']); ?></td>
                            <td class="px-6 py-4 uppercase"><?php echo htmlspecialchars($v['type']); ?></td>
                            <td class="px-6 py-4 font-semibold text-primary"><?php echo formatCurrency($v['price_per_day']); ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo $statusClass; ?>">
                                    <?php echo $statusText; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="openEditModal(<?php echo $vJson; ?>)" class="rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-primary dark:hover:bg-gray-800">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                    <form method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa xe này?');" class="inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $v['id']; ?>">
                                        <button class="rounded p-1 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20">
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
            <h3 class="text-xl font-bold">Thêm xe mới</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-500"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" class="p-6">
            <input type="hidden" name="action" value="add">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2"><label class="block mb-1 text-sm font-medium">Tên xe</label><input required name="name" class="w-full rounded-lg border-gray-300 dark:bg-gray-800" placeholder="VinFast VF8"/></div>
                <div><label class="block mb-1 text-sm font-medium">Hãng xe</label><input required name="brand" class="w-full rounded-lg border-gray-300 dark:bg-gray-800" placeholder="VinFast"/></div>
                <div><label class="block mb-1 text-sm font-medium">Biển số</label><input required name="license_plate" class="w-full rounded-lg border-gray-300 dark:bg-gray-800" placeholder="30A-123.45"/></div>
                <div><label class="block mb-1 text-sm font-medium">Loại xe</label>
                    <select name="type" class="w-full rounded-lg border-gray-300 dark:bg-gray-800">
                        <option value="sedan">Sedan (4 chỗ)</option>
                        <option value="suv">SUV (5-7 chỗ)</option>
                        <option value="minivan">Minivan</option>
                        <option value="van">Van (16 chỗ)</option>
                    </select>
                </div>
                <div><label class="block mb-1 text-sm font-medium">Số ghế</label><input required type="number" name="seats" class="w-full rounded-lg border-gray-300 dark:bg-gray-800" value="4"/></div>
                <div class="col-span-2"><label class="block mb-1 text-sm font-medium">Giá thuê (VNĐ/ngày)</label><input required type="number" name="price_per_day" class="w-full rounded-lg border-gray-300 dark:bg-gray-800" placeholder="1000000"/></div>
                <div class="col-span-2"><label class="block mb-1 text-sm font-medium">Nhà cung cấp</label><input name="provider" class="w-full rounded-lg border-gray-300 dark:bg-gray-800" placeholder="Hertz, Avis..."/></div>
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
            <h3 class="text-xl font-bold">Cập nhật xe</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-500"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" class="p-6">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2"><label class="block mb-1 text-sm font-medium">Tên xe</label><input required id="edit_name" name="name" class="w-full rounded-lg border-gray-300 dark:bg-gray-800"/></div>
                <div><label class="block mb-1 text-sm font-medium">Hãng xe</label><input required id="edit_brand" name="brand" class="w-full rounded-lg border-gray-300 dark:bg-gray-800"/></div>
                <div><label class="block mb-1 text-sm font-medium">Biển số</label><input required id="edit_license_plate" name="license_plate" class="w-full rounded-lg border-gray-300 dark:bg-gray-800"/></div>
                <div><label class="block mb-1 text-sm font-medium">Loại xe</label>
                    <select id="edit_type" name="type" class="w-full rounded-lg border-gray-300 dark:bg-gray-800">
                        <option value="sedan">Sedan</option>
                        <option value="suv">SUV</option>
                        <option value="minivan">Minivan</option>
                        <option value="van">Van</option>
                    </select>
                </div>
                <div><label class="block mb-1 text-sm font-medium">Số ghế</label><input required type="number" id="edit_seats" name="seats" class="w-full rounded-lg border-gray-300 dark:bg-gray-800"/></div>
                <div class="col-span-2"><label class="block mb-1 text-sm font-medium">Giá thuê (VNĐ/ngày)</label><input required type="number" id="edit_price" name="price_per_day" class="w-full rounded-lg border-gray-300 dark:bg-gray-800"/></div>
                <div><label class="block mb-1 text-sm font-medium">Nhà cung cấp</label><input id="edit_provider" name="provider" class="w-full rounded-lg border-gray-300 dark:bg-gray-800"/></div>
                <div><label class="block mb-1 text-sm font-medium">Trạng thái</label>
                    <select id="edit_status" name="status" class="w-full rounded-lg border-gray-300 dark:bg-gray-800">
                        <option value="available">Sẵn có</option>
                        <option value="rented">Đang thuê</option>
                        <option value="maintenance">Bảo trì</option>
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
        document.getElementById('edit_brand').value = data.brand;
        document.getElementById('edit_license_plate').value = data.license_plate;
        document.getElementById('edit_type').value = data.type;
        document.getElementById('edit_seats').value = data.seats;
        document.getElementById('edit_price').value = data.price_per_day;
        document.getElementById('edit_provider').value = data.provider;
        document.getElementById('edit_status').value = data.status;
    }
</script>
</body>
</html>