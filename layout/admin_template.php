<?php
/**
 * layout/admin_template.php
 * Template base cho tất cả trang admin
 * 
 * Cách sử dụng:
 * $pageTitle = 'Tên trang';
 * $pageDescription = 'Mô tả trang (optional)';
 * include '../layout/admin_template.php';
 * 
 * Sau đó viết HTML nội dung trong <?php if (false): ?> ... <?php endif; ?>
 */

if (!isset($pageTitle)) {
    $pageTitle = 'Admin Page';
}

if (!isset($pageDescription)) {
    $pageDescription = '';
}

require_once __DIR__ . '/../config/config.php';
requireAdmin();
?>
<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo htmlspecialchars($pageTitle); ?> - <?php echo SITE_NAME; ?> Admin</title>
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
                        "background-dark": "#101c22" 
                    },
                    fontFamily: { 
                        "display": ["Plus Jakarta Sans", "sans-serif"] 
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
    <!-- Custom styles slot -->
    <?php if (isset($customStyles)): echo $customStyles; endif; ?>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="flex w-full min-h-screen">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../components/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
        <!-- Header -->
        <?php include __DIR__ . '/../components/header.php'; ?>

        <!-- Page Content -->
        <div class="flex-1 overflow-y-auto p-6 md:p-10">
            <?php 
            // Flash messages
            $flash = getFlashMessage();
            if ($flash): 
            ?>
            <div class="mb-6 flex items-center gap-2 rounded-lg border px-4 py-3 <?php echo $flash['type'] === 'success' ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300' : 'border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300'; ?>">
                <span class="material-symbols-outlined"><?php echo $flash['type'] === 'success' ? 'check_circle' : 'error'; ?></span>
                <span class="text-sm font-medium"><?php echo htmlspecialchars($flash['message']); ?></span>
                <button class="ml-auto text-lg" onclick="this.parentElement.remove()">×</button>
            </div>
            <?php endif; ?>

            <!-- Page content goes here -->
            <?php if (isset($content)): echo $content; endif; ?>
        </div>
    </main>
</div>

<!-- Custom scripts slot -->
<?php if (isset($customScripts)): echo $customScripts; endif; ?>

<!-- Global scripts -->
<script>
    // Sidebar search
    const searchInput = document.getElementById('sidebarSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                const label = item.dataset.label || '';
                item.style.display = label.includes(searchTerm) ? 'flex' : 'none';
            });
        });
    }

    // Close alerts
    document.querySelectorAll('[data-dismiss="alert"]').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.alert').remove();
        });
    });

    // Dark mode persistence
    const htmlElement = document.documentElement;
    const prefersDark = localStorage.getItem('theme') === 'dark' || 
                        (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);
    if (prefersDark) {
        htmlElement.classList.add('dark');
    }
</script>
</body>
</html>