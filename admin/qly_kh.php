<?php
/**
 * Quản lý người dùng
 */

require_once '../config/config.php';
if (!function_exists('requireAdmin')) {
    /**
     * Kiểm tra người dùng đã đăng nhập và có quyền Admin chưa.
     * Nếu không, chuyển hướng về trang đăng nhập hoặc trang báo lỗi.
     */
    function requireAdmin() {
        if (!Auth::isLoggedIn() || !Auth::isAdmin()) {
            // Chuyển hướng về trang đăng nhập/trang chủ người dùng
            header('Location: ../login.php'); 
            exit;
        }
    }
}
requireAdmin();

// --- XỬ LÝ HÀNH ĐỘNG (KHÓA / MỞ KHÓA) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $userId = (int)$_POST['user_id'];
    
    // Ngăn chặn tự khóa chính mình
    if ($userId == getCurrentUser()['id']) {
        setFlashMessage('error', 'Bạn không thể khóa chính mình.');
        redirect('qly_kh.php');
    }

    if ($action === 'lock') {
        if (db()->update('users', ['status' => 'blocked'], 'id = ?', [$userId])) {
            setFlashMessage('success', 'Đã khóa tài khoản thành công');
        } else {
            setFlashMessage('error', 'Có lỗi xảy ra khi khóa');
        }
    }
    
    if ($action === 'unlock') {
        if (db()->update('users', ['status' => 'active'], 'id = ?', [$userId])) {
            setFlashMessage('success', 'Đã mở khóa tài khoản');
        } else {
            setFlashMessage('error', 'Có lỗi xảy ra khi mở khóa');
        }
    }
    
    redirect('qly_kh.php');
}

// --- TÌM KIẾM & PHÂN TRANG ---
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';

// Chỉ lấy user thường, không lấy admin
$where = "role = 'user'";
$params = [];

if ($search) {
    $where .= " AND (full_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $searchTerm = "%$search%";
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
}

if ($statusFilter) {
    $where .= " AND status = ?";
    $params[] = $statusFilter;
}

$totalUsers = db()->count('users', $where, $params);
$pagination = getPagination($page, $totalUsers);

$users = db()->select("
    SELECT * FROM users
    WHERE $where
    ORDER BY created_at DESC
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
    <title>Quản lý Người dùng - <?php echo SITE_NAME; ?></title>
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
    <?php 
    $pageTitle = 'Quản lý Người dùng';
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
                <div>
                    <h1 class="text-3xl font-black tracking-tight">Danh sách tài khoản</h1>
                    <p class="text-gray-500 text-sm mt-1">Quản lý và theo dõi trạng thái người dùng.</p>
                </div>
                </div>

            <form method="GET" class="mb-6 flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/50 md:flex-row">
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input name="search" value="<?php echo htmlspecialchars($search); ?>" class="w-full rounded-lg border-gray-300 pl-10 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" placeholder="Tìm theo tên, email, số điện thoại..."/>
                </div>
                <div class="flex gap-3">
                    <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" <?php echo $statusFilter === 'active' ? 'selected' : ''; ?>>Hoạt động</option>
                        <option value="blocked" <?php echo $statusFilter === 'blocked' ? 'selected' : ''; ?>>Bị khóa</option>
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
                            <th class="px-6 py-4 font-medium">Họ và tên</th>
                            <th class="px-6 py-4 font-medium">Email</th>
                            <th class="px-6 py-4 font-medium">Liên hệ / Địa chỉ</th>
                            <th class="px-6 py-4 font-medium">Trạng thái</th>
                            <th class="px-6 py-4 font-medium">Ngày tham gia</th>
                            <th class="px-6 py-4 font-medium text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php foreach ($users as $user): 
                            $statusClass = $user['status'] === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 
                                          'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
                            $statusText = $user['status'] === 'active' ? 'Hoạt động' : 'Bị khóa';
                            $avatarUrl = $user['avatar'] ?? "https://ui-avatars.com/api/?name=" . urlencode($user['full_name']) . "&background=random";
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="size-9 rounded-full bg-gray-200 bg-cover border border-gray-200" style='background-image: url("<?php echo $avatarUrl; ?>");'></div>
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($user['full_name']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="px-6 py-4 text-gray-500 font-mono text-xs">
                                <?php echo !empty($user['phone']) ? htmlspecialchars($user['phone']) : '<span class="italic text-gray-400">Chưa cập nhật</span>'; ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo $statusClass; ?>">
                                    <span class="size-1.5 rounded-full <?php echo $user['status'] === 'active' ? 'bg-green-500' : 'bg-red-500'; ?>"></span>
                                    <?php echo $statusText; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500"><?php echo formatDateTime($user['created_at'], 'd/m/Y'); ?></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <?php if ($user['status'] === 'active'): ?>
                                        <form method="POST" onsubmit="return confirm('Bạn có chắc muốn KHÓA tài khoản này? Người dùng sẽ không thể đăng nhập.');" class="inline">
                                            <input type="hidden" name="action" value="lock">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" class="flex items-center gap-1 rounded bg-red-50 px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-100 border border-red-200">
                                                <span class="material-symbols-outlined text-[16px]">lock</span> Khóa
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" onsubmit="return confirm('Mở khóa tài khoản này?');" class="inline">
                                            <input type="hidden" name="action" value="unlock">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" class="flex items-center gap-1 rounded bg-green-50 px-2 py-1 text-xs font-medium text-green-600 hover:bg-green-100 border border-green-200">
                                                <span class="material-symbols-outlined text-[16px]">lock_open</span> Mở
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($users)): ?>
            <div class="mt-8 text-center py-12 bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-300 dark:border-gray-600">
                <span class="material-symbols-outlined text-6xl text-gray-300 mx-auto block mb-4">person_off</span>
                <p class="text-gray-500 font-medium">Không tìm thấy người dùng nào phù hợp</p>
                <a href="qly_kh.php" class="mt-2 inline-block text-primary text-sm hover:underline">Xóa bộ lọc tìm kiếm</a>
            </div>
            <?php endif; ?>

            <div class="mt-4 flex items-center justify-between border-t border-gray-200 px-2 py-4 dark:border-gray-700">
                <span class="text-sm text-gray-500">
                    Hiển thị <b><?php echo $totalUsers > 0 ? min($pagination['offset'] + 1, $totalUsers) : 0; ?>-<?php echo min($pagination['offset'] + $pagination['items_per_page'], $totalUsers); ?></b> 
                    trên <b><?php echo $totalUsers; ?></b> kết quả
                </span>
                <div class="flex gap-2">
                    <?php if ($pagination['has_prev']): ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($statusFilter); ?>" 
                       class="rounded border border-gray-300 px-3 py-1 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800 text-sm">Trước</a>
                    <?php endif; ?>
                    
                    <?php if ($pagination['has_next']): ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($statusFilter); ?>" 
                       class="rounded border border-gray-300 px-3 py-1 hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-800 text-sm">Sau</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>