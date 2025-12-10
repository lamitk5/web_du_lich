<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Quản lý xe</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
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
            "display": ["Plus Jakarta Sans", "Noto Sans", "sans-serif"]
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
    }
  </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display">
<div class="relative flex min-h-screen w-full flex-col">
<div class="flex h-full w-full">
<!-- SideNavBar -->
<aside class="flex w-64 flex-col gap-y-6 border-r border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900/50">
<div class="flex items-center gap-3">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Admin user avatar" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCsg_kBMdLbPEh6pbZ4h_AUvom5DCDuGg-qKvcZZRhAas0ybQwbHPcVqvXFfOhPRfSG7mHiBGC7wuERtSY-6LIX40eO7X9qnSH_tvAEwEKrR1JTk72aJlKekiupcEst4HDxW3ef-lTKrTKYILzM6v5hPaR9rSCYcCgGJicRLB2RWYbyuVcMEVtPPas7mvBp3IrvZJr7aTLYFAG17w9xsvvYExxxai3YX3pPWeRD7puPRHXsKqgMf36VbnELM6uKthJ4DAO1spZQu8Jc");'></div>
<div class="flex flex-col">
<h1 class="text-gray-900 text-base font-medium leading-normal dark:text-white">Admin Portal</h1>
<p class="text-gray-500 text-sm font-normal leading-normal dark:text-gray-400">admin@flyhigh.com</p>
</div>
</div>
<nav class="flex flex-1 flex-col justify-between">
<div class="flex flex-col gap-2">
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800" href="#">
<span class="material-symbols-outlined text-gray-800 dark:text-gray-200">dashboard</span>
<p class="text-sm font-medium leading-normal">Dashboard</p>
</a>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800" href="#">
<span class="material-symbols-outlined text-gray-800 dark:text-gray-200">airplane_ticket</span>
<p class="text-sm font-medium leading-normal">Quản lý vé</p>
</a>
<a class="flex items-center gap-3 rounded-lg bg-primary/10 px-3 py-2 text-primary dark:bg-primary/20" href="#">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">directions_car</span>
<p class="text-sm font-medium leading-normal">Quản lý xe</p>
</a>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800" href="#">
<span class="material-symbols-outlined text-gray-800 dark:text-gray-200">group</span>
<p class="text-sm font-medium leading-normal">Khách hàng</p>
</a>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800" href="#">
<span class="material-symbols-outlined text-gray-800 dark:text-gray-200">pie_chart</span>
<p class="text-sm font-medium leading-normal">Báo cáo</p>
</a>
</div>
<div class="flex flex-col gap-1">
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800" href="#">
<span class="material-symbols-outlined text-gray-800 dark:text-gray-200">settings</span>
<p class="text-sm font-medium leading-normal">Settings</p>
</a>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800" href="#">
<span class="material-symbols-outlined text-gray-800 dark:text-gray-200">logout</span>
<p class="text-sm font-medium leading-normal">Logout</p>
</a>
</div>
</nav>
</aside>
<!-- Main Content -->
<main class="flex-1 overflow-y-auto">
<div class="mx-auto max-w-7xl p-6">
<!-- PageHeading -->
<div class="flex flex-wrap items-center justify-between gap-4">
<h1 class="text-3xl font-black leading-tight tracking-[-0.03em] text-gray-900 dark:text-white min-w-72">Quản lý xe</h1>
<button class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg bg-primary h-10 px-4 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
<span class="material-symbols-outlined">add</span>
<span class="truncate">Thêm xe mới</span>
</button>
</div>
<!-- Search & Filter Section -->
<div class="mt-6 flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900/50">
<!-- SearchBar -->
<label class="flex h-12 w-full flex-col">
<div class="flex h-full w-full flex-1 items-stretch rounded-lg">
<div class="flex items-center justify-center rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 pl-4 text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400">
<span class="material-symbols-outlined">search</span>
</div>
<input class="form-input h-full w-full min-w-0 flex-1 resize-none overflow-hidden rounded-r-lg border border-l-0 border-gray-300 bg-white px-4 text-base font-normal leading-normal text-gray-900 placeholder:text-gray-500 focus:border-primary focus:outline-0 focus:ring-0 dark:border-gray-600 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-400" placeholder="Tìm kiếm theo tên xe, biển số..." value=""/>
</div>
</label>
<!-- Chips -->
<div class="flex flex-wrap gap-3">
<button class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-gray-100 pl-4 pr-2 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700">
<p class="text-gray-800 text-sm font-medium leading-normal dark:text-gray-200">Nhà cung cấp</p>
<span class="material-symbols-outlined text-gray-800 dark:text-gray-200">arrow_drop_down</span>
</button>
<button class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-gray-100 pl-4 pr-2 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700">
<p class="text-gray-800 text-sm font-medium leading-normal dark:text-gray-200">Loại xe</p>
<span class="material-symbols-outlined text-gray-800 dark:text-gray-200">arrow_drop_down</span>
</button>
<button class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-gray-100 pl-4 pr-2 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700">
<p class="text-gray-800 text-sm font-medium leading-normal dark:text-gray-200">Trạng thái</p>
<span class="material-symbols-outlined text-gray-800 dark:text-gray-200">arrow_drop_down</span>
</button>
</div>
</div>
<!-- Table -->
<div class="mt-6 flow-root">
<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
<div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
<div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm dark:border-gray-700">
<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
<thead class="bg-gray-50 dark:bg-gray-800">
<tr>
<th class="px-4 py-3 text-left text-sm font-medium text-gray-800 dark:text-gray-200" scope="col">Tên xe</th>
<th class="px-4 py-3 text-left text-sm font-medium text-gray-800 dark:text-gray-200" scope="col">Nhà cung cấp</th>
<th class="px-4 py-3 text-left text-sm font-medium text-gray-800 dark:text-gray-200" scope="col">Biển số</th>
<th class="px-4 py-3 text-left text-sm font-medium text-gray-800 dark:text-gray-200" scope="col">Loại xe</th>
<th class="px-4 py-3 text-left text-sm font-medium text-gray-800 dark:text-gray-200" scope="col">Giá (ngày)</th>
<th class="px-4 py-3 text-left text-sm font-medium text-gray-800 dark:text-gray-200" scope="col">Trạng thái</th>
<th class="px-4 py-3 text-right text-sm font-medium text-gray-800 dark:text-gray-200" scope="col">Hành động</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900/50">
<tr>
<td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">VinFast VF8</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">VinFast Rentals</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">29A-123.45</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">SUV</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">1.200.000đ</td>
<td class="whitespace-nowrap px-4 py-4 text-sm">
<span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-900/40 dark:text-green-300">Sẵn có</span>
</td>
<td class="whitespace-nowrap px-4 py-4 text-right text-sm font-medium">
<div class="flex items-center justify-end gap-x-2">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary"><span class="material-symbols-outlined text-xl">edit</span></button>
<button class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500"><span class="material-symbols-outlined text-xl">delete</span></button>
</div>
</td>
</tr>
<tr>
<td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">Toyota Vios</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">Mai Linh Corp</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">51G-678.90</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">Sedan</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">800.000đ</td>
<td class="whitespace-nowrap px-4 py-4 text-sm">
<span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300">Đang thuê</span>
</td>
<td class="whitespace-nowrap px-4 py-4 text-right text-sm font-medium">
<div class="flex items-center justify-end gap-x-2">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary"><span class="material-symbols-outlined text-xl">edit</span></button>
<button class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500"><span class="material-symbols-outlined text-xl">delete</span></button>
</div>
</td>
</tr>
<tr>
<td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">Ford Ranger</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">Avis Vietnam</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">30H-112.23</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">Bán tải</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">1.500.000đ</td>
<td class="whitespace-nowrap px-4 py-4 text-sm">
<span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-900/40 dark:text-red-300">Bảo trì</span>
</td>
<td class="whitespace-nowrap px-4 py-4 text-right text-sm font-medium">
<div class="flex items-center justify-end gap-x-2">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary"><span class="material-symbols-outlined text-xl">edit</span></button>
<button class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500"><span class="material-symbols-outlined text-xl">delete</span></button>
</div>
</td>
</tr>
<tr>
<td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">Honda City</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">Budget Car Rental</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">92A-445.56</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">Sedan</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">900.000đ</td>
<td class="whitespace-nowrap px-4 py-4 text-sm">
<span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-900/40 dark:text-green-300">Sẵn có</span>
</td>
<td class="whitespace-nowrap px-4 py-4 text-right text-sm font-medium">
<div class="flex items-center justify-end gap-x-2">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary"><span class="material-symbols-outlined text-xl">edit</span></button>
<button class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500"><span class="material-symbols-outlined text-xl">delete</span></button>
</div>
</td>
</tr>
<tr>
<td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">Kia Carnival</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">Hertz Vietnam</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">60F-778.89</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">MPV</td>
<td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">1.800.000đ</td>
<td class="whitespace-nowrap px-4 py-4 text-sm">
<span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-900/40 dark:text-green-300">Sẵn có</span>
</td>
<td class="whitespace-nowrap px-4 py-4 text-right text-sm font-medium">
<div class="flex items-center justify-end gap-x-2">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-primary"><span class="material-symbols-outlined text-xl">edit</span></button>
<button class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500"><span class="material-symbols-outlined text-xl">delete</span></button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
<!-- Pagination -->
<nav aria-label="Pagination" class="mt-6 flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6 dark:border-gray-700">
<div class="hidden sm:block">
<p class="text-sm text-gray-700 dark:text-gray-300">
                Showing
                <span class="font-medium">1</span>
                to
                <span class="font-medium">5</span>
                of
                <span class="font-medium">20</span>
                results
              </p>
</div>
<div class="flex flex-1 justify-between sm:justify-end">
<a class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700" href="#">Previous</a>
<a class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700" href="#">Next</a>
</div>
</nav>
</div>
</main>
</div>
</div>
</body></html>