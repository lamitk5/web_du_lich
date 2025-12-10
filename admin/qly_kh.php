<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Quản lý người dùng</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 24px;
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="relative flex min-h-screen w-full">
<!-- SideNavBar -->
<aside class="flex w-64 flex-col bg-white dark:bg-background-dark/50 border-r border-gray-200 dark:border-gray-700 p-4">
<div class="flex items-center gap-3 mb-8">
<div class="bg-primary text-white rounded-lg p-2 flex items-center justify-center">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1, 'wght' 600;">airplanemode_active</span>
</div>
<h1 class="text-xl font-bold text-gray-900 dark:text-white">FlyHigh Admin</h1>
</div>
<div class="flex flex-col flex-1 justify-between">
<nav class="flex flex-col gap-2">
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-primary/10" href="#">
<span class="material-symbols-outlined">dashboard</span>
<p class="text-sm font-medium">Dashboard</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-primary/10" href="#">
<span class="material-symbols-outlined">confirmation_number</span>
<p class="text-sm font-medium">Quản lý vé</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/10 text-primary dark:bg-primary/20" href="#">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">group</span>
<p class="text-sm font-bold">Quản lý người dùng</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-primary/10" href="#">
<span class="material-symbols-outlined">bar_chart</span>
<p class="text-sm font-medium">Báo cáo</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-primary/10" href="#">
<span class="material-symbols-outlined">settings</span>
<p class="text-sm font-medium">Cài đặt</p>
</a>
</nav>
<div class="flex flex-col gap-4">
<div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex gap-3">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Admin user avatar" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA1VbqeUnbSvdV0yk1d-ud_vIyIyQfjYP0uGsXonLbkVmnw2m_Txh_2rYkOds9LURFhXR4jJYxsuvuWJgbzs_RZom8TdPeLyZNt70gVruZtZvWQaMUOy4D70InSiAF_X1oDdOn0GFUya9Ln1LoGRPTeRhsYl5A6YMw_vRIbUYA1gj_3R0UqHQmt3zVss04SGu6rC-FJ3Aem1iqSNQdEUSKoblL5TX7QvcTO8cTb5SfztfskC6-rgexG847LkR0zMJj1xPTL6j7x8I0j");'></div>
<div class="flex flex-col">
<h1 class="text-gray-900 dark:text-white text-sm font-medium leading-normal">Admin</h1>
<p class="text-gray-500 dark:text-gray-400 text-xs font-normal leading-normal">admin@flyhigh.com</p>
</div>
</div>
<button class="flex w-full cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-gray-100 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 hover:bg-red-500/10 hover:text-red-500 text-sm font-bold leading-normal tracking-[0.015em]">
<span class="material-symbols-outlined !text-xl">logout</span>
<span class="truncate">Đăng xuất</span>
</button>
</div>
</div>
</aside>
<!-- Main Content -->
<main class="flex-1 p-8">
<div class="flex flex-col w-full">
<!-- PageHeading -->
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
<div class="flex flex-col">
<p class="text-gray-900 dark:text-white text-3xl font-bold leading-tight">Quản lý Người dùng</p>
<p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">Xem, tìm kiếm và quản lý tất cả tài khoản người dùng trong hệ thống.</p>
</div>
<div class="flex gap-2">
<button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 gap-2 text-sm font-bold leading-normal min-w-0 px-4 hover:bg-gray-50 dark:hover:bg-gray-600">
<span class="material-symbols-outlined !text-xl">upload</span>
<span class="truncate">Xuất dữ liệu</span>
</button>
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
<span class="material-symbols-outlined !text-xl">add</span>
<span class="truncate">Thêm người dùng mới</span>
</button>
</div>
</div>
<!-- Toolbar and Table container -->
<div class="bg-white dark:bg-background-dark/50 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
<!-- SearchBar & Chips -->
<div class="p-4 border-b border-gray-200 dark:border-gray-700">
<div class="flex flex-wrap items-center gap-4">
<div class="flex-1 min-w-[300px]">
<label class="flex flex-col w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-10">
<div class="text-gray-400 dark:text-gray-500 flex bg-gray-100 dark:bg-gray-700/50 items-center justify-center pl-3 rounded-l-lg">
<span class="material-symbols-outlined !text-2xl">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-r-lg text-gray-800 dark:text-gray-200 focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-gray-100 dark:bg-gray-700/50 h-full placeholder:text-gray-400 dark:placeholder:text-gray-500 px-4 text-sm font-normal leading-normal" placeholder="Tìm kiếm theo tên, email, hoặc ID người dùng..."/>
</div>
</label>
</div>
<div class="flex gap-2">
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-gray-100 dark:bg-gray-700/50 px-4">
<p class="text-gray-800 dark:text-gray-200 text-sm font-medium">Trạng thái: Tất cả</p>
<span class="material-symbols-outlined !text-xl text-gray-500 dark:text-gray-400">expand_more</span>
</button>
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-gray-100 dark:bg-gray-700/50 px-4">
<p class="text-gray-800 dark:text-gray-200 text-sm font-medium">Quyền hạn: Tất cả</p>
<span class="material-symbols-outlined !text-xl text-gray-500 dark:text-gray-400">expand_more</span>
</button>
</div>
</div>
</div>
<!-- User Table -->
<div class="overflow-x-auto">
<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
<thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700/50">
<tr>
<th class="p-4" scope="col"><input class="form-checkbox rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-primary focus:ring-primary/50" type="checkbox"/></th>
<th class="px-6 py-3 font-semibold" scope="col">Họ và tên</th>
<th class="px-6 py-3 font-semibold" scope="col">Trạng thái</th>
<th class="px-6 py-3 font-semibold" scope="col">Quyền hạn</th>
<th class="px-6 py-3 font-semibold" scope="col">Ngày tham gia</th>
<th class="px-6 py-3 font-semibold text-right" scope="col">Hành động</th>
</tr>
</thead>
<tbody>
<!-- Table Row 1 -->
<tr class="bg-white dark:bg-background-dark/50 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="w-4 p-4"><input class="form-checkbox rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-primary focus:ring-primary/50" type="checkbox"/></td>
<th class="flex items-center px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap" scope="row">
<img class="w-10 h-10 rounded-full" data-alt="Avatar of Lê Minh Anh" src="https://lh3.googleusercontent.com/aida-public/AB6AXuARrsg6E2aHiYSl-FXZKJSa6UHmCbMtZNrkFh3vsaJVMJm5ZXv5B6OyB7ZPakkQGRg0Db84mdxpFvMeSkBemzeqlE6glOcSOQVNOjCztHGp_b-1bcI2qbhzFQJpOICbs6_lJLH2NqZl90yJl_O9EoZq33quEs_lWOAdyLNo7BKag4zL3w8FqOz1APgwIswrTYA3ZdxoC3b9FaNoXNMDFXWxwOwvMEzwAkSn97RyrkhkhGQZB7901WTWVl5iyZ-2AWBVv8C_pDmuo1ue"/>
<div class="pl-3">
<div class="text-base font-semibold">Lê Minh Anh</div>
<div class="font-normal text-gray-500 dark:text-gray-400">minhanh.le@email.com</div>
</div>
</th>
<td class="px-6 py-4">
<div class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
<span class="size-2 rounded-full bg-green-500"></span>Hoạt động
                                        </div>
</td>
<td class="px-6 py-4"><span class="font-medium text-gray-800 dark:text-gray-200">User</span></td>
<td class="px-6 py-4"><span class="text-gray-600 dark:text-gray-400">20/07/2023</span></td>
<td class="px-6 py-4">
<div class="flex justify-end gap-1">
<button class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700"><span class="material-symbols-outlined !text-xl">visibility</span></button>
<button class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700"><span class="material-symbols-outlined !text-xl">edit</span></button>
<button class="p-2 rounded-lg text-red-500 hover:bg-red-500/10"><span class="material-symbols-outlined !text-xl">lock</span></button>
</div>
</td>
</tr>
<!-- Table Row 2 -->
<tr class="bg-white dark:bg-background-dark/50 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="w-4 p-4"><input class="form-checkbox rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-primary focus:ring-primary/50" type="checkbox"/></td>
<th class="flex items-center px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap" scope="row">
<img class="w-10 h-10 rounded-full" data-alt="Avatar of Trần Văn Bảo" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA0RBaU8VPZusW3j0Tp9WF5qGV_Ie7gha1VnGO0mEdahJ_b3MVG8TXdNgjEvIsojKVnJ8eZ5MP3LhNKQiEfQUwkCYKqXsOm0Hi98PB3rt7iPrieFmXIrMpbVWqVcO6vtKomZIHFsbNeHzgG-obRb0iZByIYUetRf1GL-2tpFwcFxhk5bkxQyuF5TakPcN-MvM5uR26GKB-Rdxpr_061yTclsWWANovBMq4_55abzKHPSh7W4l-WHLBPRwQmXFVM5QjPnrJSpHMH-v5W"/>
<div class="pl-3">
<div class="text-base font-semibold">Trần Văn Bảo</div>
<div class="font-normal text-gray-500 dark:text-gray-400">baotran@email.com</div>
</div>
</th>
<td class="px-6 py-4">
<div class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
<span class="size-2 rounded-full bg-red-500"></span>Bị khóa
                                        </div>
</td>
<td class="px-6 py-4"><span class="font-medium text-gray-800 dark:text-gray-200">User</span></td>
<td class="px-6 py-4"><span class="text-gray-600 dark:text-gray-400">15/06/2023</span></td>
<td class="px-6 py-4">
<div class="flex justify-end gap-1">
<button class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700"><span class="material-symbols-outlined !text-xl">visibility</span></button>
<button class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700"><span class="material-symbols-outlined !text-xl">edit</span></button>
<button class="p-2 rounded-lg text-green-500 hover:bg-green-500/10"><span class="material-symbols-outlined !text-xl">lock_open</span></button>
</div>
</td>
</tr>
<!-- Table Row 3 -->
<tr class="bg-white dark:bg-background-dark/50 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="w-4 p-4"><input class="form-checkbox rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-primary focus:ring-primary/50" type="checkbox"/></td>
<th class="flex items-center px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap" scope="row">
<img class="w-10 h-10 rounded-full" data-alt="Avatar of Phạm Thị Diệu" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDjGnHJMAOKuaTXDhPpm5Ycd6UQcMHuTrPOWbjlc12nrP46i-W6DHqb46KGMqPICMjlMDMIcuIIwY2RCtB5C-wf3SzCojDhNls1z8cSo7A5ftEc5fpi6xudB2u88yJ54aN-ERuORzVLt-p7otBplDGvVFZ8F_cxPFuIxDb3fMpNlJQEkL-V66Y-teHY52xsSjpkgvrcIrkinqF9lD9lc-vkWZfSi6mC4yeg0b7ZuJpZ0c9xtCJfNc7na1FD5_jlZSdF68_WPRgdtW3t"/>
<div class="pl-3">
<div class="text-base font-semibold">Phạm Thị Diệu</div>
<div class="font-normal text-gray-500 dark:text-gray-400">dieu.pham@email.com</div>
</div>
</th>
<td class="px-6 py-4">
<div class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
<span class="size-2 rounded-full bg-green-500"></span>Hoạt động
                                        </div>
</td>
<td class="px-6 py-4"><span class="font-bold text-primary dark:text-primary/90">Admin</span></td>
<td class="px-6 py-4"><span class="text-gray-600 dark:text-gray-400">01/03/2023</span></td>
<td class="px-6 py-4">
<div class="flex justify-end gap-1">
<button class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700"><span class="material-symbols-outlined !text-xl">visibility</span></button>
<button class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700"><span class="material-symbols-outlined !text-xl">edit</span></button>
<button class="p-2 rounded-lg text-red-500 hover:bg-red-500/10"><span class="material-symbols-outlined !text-xl">lock</span></button>
</div>
</td>
</tr>
<!-- Add more rows as needed -->
<tr class="bg-white dark:bg-background-dark/50 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="w-4 p-4"><input class="form-checkbox rounded border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-primary focus:ring-primary/50" type="checkbox"/></td>
<th class="flex items-center px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap" scope="row">
<img class="w-10 h-10 rounded-full" data-alt="Avatar of Nguyễn Tuấn Kiệt" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAYO-w3-qSnG1XYk3BFAdKvfJj4sWXU6uG0K8-N0BRk3AEmuxWydjFK4athHDHbSjzxsFyyjDHBqrD1WcvgR2LazHAY3H7WIPl29PDEz1wJOJJ7EVUefzfmWONRF5AN9ylQzCVJm9xVqCMtRlSfPrCE-Mf0KRaQhPx5hfylFXre7TBCmgiaVYDBRZTLmbBEHar-rGE3mL6WaZ7NqErFViVAivg5i2Y5vDqpA3eYFhNGrqMekxn38uzckSRsIK8JihySGfJNKHX3q0aq"/>
<div class="pl-3">
<div class="text-base font-semibold">Nguyễn Tuấn Kiệt</div>
<div class="font-normal text-gray-500 dark:text-gray-400">kiet.nguyen@email.com</div>
</div>
</th>
<td class="px-6 py-4">
<div class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
<span class="size-2 rounded-full bg-green-500"></span>Hoạt động
                                        </div>
</td>
<td class="px-6 py-4"><span class="font-medium text-gray-800 dark:text-gray-200">User</span></td>
<td class="px-6 py-4"><span class="text-gray-600 dark:text-gray-400">10/01/2024</span></td>
<td class="px-6 py-4">
<div class="flex justify-end gap-1">
<button class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700"><span class="material-symbols-outlined !text-xl">visibility</span></button>
<button class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700"><span class="material-symbols-outlined !text-xl">edit</span></button>
<button class="p-2 rounded-lg text-red-500 hover:bg-red-500/10"><span class="material-symbols-outlined !text-xl">lock</span></button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination -->
<nav aria-label="Table navigation" class="flex flex-wrap items-center justify-between p-4">
<span class="text-sm font-normal text-gray-500 dark:text-gray-400">Hiển thị <span class="font-semibold text-gray-900 dark:text-white">1-10</span> trên <span class="font-semibold text-gray-900 dark:text-white">1000</span></span>
<ul class="inline-flex -space-x-px text-sm h-8">
<li><a class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" href="#">Previous</a></li>
<li><a class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" href="#">1</a></li>
<li><a class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" href="#">2</a></li>
<li><a aria-current="page" class="flex items-center justify-center px-3 h-8 text-primary border border-gray-300 bg-primary/10 hover:bg-primary/20 dark:border-gray-700 dark:bg-gray-700 dark:text-white" href="#">3</a></li>
<li><a class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" href="#">...</a></li>
<li><a class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" href="#">100</a></li>
<li><a class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" href="#">Next</a></li>
</ul>
</nav>
</div>
</div>
</main>
</div>
</body></html>