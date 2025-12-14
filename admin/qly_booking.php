<!DOCTYPE html>
<html class="light" lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Quản lý Đặt chỗ - FlyHigh Admin</title>
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
            <h1 class="text-xl font-bold tracking-tight">FlyHigh Admin</h1>
        </div>
        <nav class="flex flex-1 flex-col gap-2">
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="dashboard.php">
                <span class="material-symbols-outlined">dashboard</span> <p class="text-sm font-medium">Dashboard</p>
            </a>
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="qly_chuyenbay.php">
                <span class="material-symbols-outlined">flight</span> <p class="text-sm font-medium">Vé máy bay</p>
            </a>
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="qly_khachsan.php">
                <span class="material-symbols-outlined">hotel</span> <p class="text-sm font-medium">Khách sạn</p>
            </a>
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="qly_xe.php">
                <span class="material-symbols-outlined">directions_car</span> <p class="text-sm font-medium">Quản lý xe</p>
            </a>
            <a class="active-nav flex items-center gap-3 rounded-lg px-3 py-2" href="qly_booking.php">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">confirmation_number</span> <p class="text-sm font-medium">Đặt chỗ</p>
            </a>
            <a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" href="qly_kh.php">
                <span class="material-symbols-outlined">group</span> <p class="text-sm font-medium">Người dùng</p>
            </a>
        </nav>
        <div class="flex items-center gap-3 border-t border-gray-200 dark:border-gray-700 pt-4">
            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" style='background-image: url("https://ui-avatars.com/api/?name=Admin&background=0da6f2&color=fff");'></div>
            <div class="flex flex-col">
                <p class="text-sm font-medium">Admin</p>
                <p class="text-xs text-gray-500">admin@flyhigh.com</p>
            </div>
        </div>
    </aside>

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
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <h1 class="text-3xl font-black tracking-tight">Danh sách đặt chỗ</h1>
                <button class="flex items-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-bold text-white hover:bg-primary/90">
                    <span class="material-symbols-outlined">add</span>
                    <span>Tạo booking mới</span>
                </button>
            </div>

            <div class="mb-6 flex flex-col md:flex-row gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900/50">
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input class="w-full rounded-lg border-gray-300 pl-10 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800" placeholder="Mã booking, tên khách..."/>
                </div>
                <div class="flex gap-3">
                    <select class="rounded-lg border-gray-300 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800">
                        <option>Tất cả dịch vụ</option>
                        <option>Vé máy bay</option>
                        <option>Khách sạn</option>
                        <option>Thuê xe</option>
                    </select>
                    <select class="rounded-lg border-gray-300 text-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-800">
                        <option>Tất cả trạng thái</option>
                        <option>Đã xác nhận</option>
                        <option>Chờ thanh toán</option>
                        <option>Đã hủy</option>
                    </select>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900/50">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Mã đặt chỗ</th>
                            <th class="px-6 py-4 font-medium">Khách hàng</th>
                            <th class="px-6 py-4 font-medium">Dịch vụ</th>
                            <th class="px-6 py-4 font-medium">Chi tiết</th>
                            <th class="px-6 py-4 font-medium">Tổng tiền</th>
                            <th class="px-6 py-4 font-medium">Trạng thái</th>
                            <th class="px-6 py-4 font-medium text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-primary">#12345</td>
                            <td class="px-6 py-4">Nguyễn Văn A</td>
                            <td class="px-6 py-4 flex items-center gap-2"><span class="material-symbols-outlined text-primary text-lg">flight</span> Vé máy bay</td>
                            <td class="px-6 py-4 text-gray-500">SGN -> HAN</td>
                            <td class="px-6 py-4 font-semibold">2.500.000đ</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-300">Đã xác nhận</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-primary hover:underline font-medium">Chi tiết</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>