<!DOCTYPE html>
<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Quản lý khách sạn</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
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
              "surface-light": "#ffffff",
              "surface-dark": "#18262f",
              "text-primary-light": "#0d171c",
              "text-primary-dark": "#f5f7f8",
              "text-secondary-light": "#49819c",
              "text-secondary-dark": "#a0b8c4",
              "border-light": "#cee0e8",
              "border-dark": "#334155",
            },
            fontFamily: {
              "display": ["Plus Jakarta Sans", "Noto Sans", "sans-serif"]
            },
            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 24px;
        }
        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
<div class="flex h-screen w-full">
<aside class="flex w-64 flex-col border-r border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark">
<div class="flex h-full flex-col justify-between p-4">
<div class="flex flex-col gap-6">
<div class="flex items-center gap-3 px-2">
<div class="text-primary" data-icon="TravelExplore" data-size="32px">
<span class="material-symbols-outlined" style="font-size: 32px;">travel_explore</span>
</div>
<h1 class="text-xl font-bold text-text-primary-light dark:text-text-primary-dark">Tourism Admin</h1>
</div>
<div class="flex flex-col gap-1">
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-text-secondary-light dark:text-text-secondary-dark hover:bg-primary/10" href="#">
<span class="material-symbols-outlined">dashboard</span>
<p class="text-sm font-medium">Dashboard</p>
</a>
<a class="flex items-center gap-3 rounded-lg bg-primary/10 px-3 py-2 text-primary" href="#">
<span class="material-symbols-outlined fill">hotel</span>
<p class="text-sm font-bold">Quản lý khách sạn</p>
</a>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-text-secondary-light dark:text-text-secondary-dark hover:bg-primary/10" href="#">
<span class="material-symbols-outlined">flight</span>
<p class="text-sm font-medium">Quản lý vé máy bay</p>
</a>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-text-secondary-light dark:text-text-secondary-dark hover:bg-primary/10" href="#">
<span class="material-symbols-outlined">deck</span>
<p class="text-sm font-medium">Quản lý dịch vụ</p>
</a>
<a class="flex items-center gap-3 rounded-lg px-3 py-2 text-text-secondary-light dark:text-text-secondary-dark hover:bg-primary/10" href="#">
<span class="material-symbols-outlined">settings</span>
<p class="text-sm font-medium">Cài đặt</p>
</a>
</div>
</div>
<div class="flex flex-col gap-4">
<div class="flex items-center gap-3 border-t border-border-light dark:border-border-dark pt-4">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Admin user avatar" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCj3kcQ-lNNhkWqpmKVtFawBdkwhaLFUWpWBSYc_acVLoypTLA0Q57Cp1ZFlU9SWnfu6g80b6zwMIlEPDcfNcM0PhbLIbBZm98aMyPYaCf36xrhjXWeEc5wWiwZuxbQE2PXsinGXqz_riTu0_RLqgii-7fj9fKik6Go4HGSvb7VYL4GRU0nVkxjZseZqFeV6FB42eCsB7i_17nd359vDHXmh2LWN9gPjt3UY2-U90iGsNSsutJz1V-p6ujJ2RVCXXriCIEzMSnxt7pE");'></div>
<div class="flex flex-col">
<h2 class="text-text-primary-light dark:text-text-primary-dark text-sm font-medium leading-tight">Admin</h2>
<p class="text-text-secondary-light dark:text-text-secondary-dark text-xs font-normal leading-tight">admin@tourism.com</p>
</div>
</div>
<button class="flex w-full cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary/10 text-primary text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/20">
<span class="material-symbols-outlined">logout</span>
<span class="truncate">Đăng xuất</span>
</button>
</div>
</div>
</aside>
<main class="flex-1 overflow-y-auto">
<div class="p-8">
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="flex flex-col gap-1">
<p class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold leading-tight tracking-tight">Quản lý khách sạn</p>
<p class="text-text-secondary-light dark:text-text-secondary-dark text-base font-normal leading-normal">Thêm, sửa, và quản lý thông tin các khách sạn trong hệ thống.</p>
</div>
<button class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
<span class="material-symbols-outlined">add</span>
<span class="truncate">Thêm khách sạn mới</span>
</button>
</div>
<div class="mt-6 flex flex-wrap items-center gap-4">
<div class="flex-1">
<label class="flex flex-col min-w-72 h-12 w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-full bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark focus-within:ring-2 focus-within:ring-primary">
<div class="text-text-secondary-light dark:text-text-secondary-dark flex items-center justify-center pl-4">
<span class="material-symbols-outlined">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden text-text-primary-light dark:text-text-primary-dark focus:outline-0 focus:ring-0 border-none bg-transparent h-full placeholder:text-text-secondary-light dark:placeholder:text-text-secondary-dark px-2 text-sm font-normal leading-normal" placeholder="Tìm kiếm theo tên khách sạn..." value=""/>
</div>
</label>
</div>
<div class="flex gap-3">
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark pl-4 pr-3 text-text-primary-light dark:text-text-primary-dark hover:bg-background-light dark:hover:bg-background-dark">
<p class="text-sm font-medium leading-normal">Địa điểm</p>
<span class="material-symbols-outlined" style="font-size: 20px;">expand_more</span>
</button>
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark pl-4 pr-3 text-text-primary-light dark:text-text-primary-dark hover:bg-background-light dark:hover:bg-background-dark">
<p class="text-sm font-medium leading-normal">Trạng thái</p>
<span class="material-symbols-outlined" style="font-size: 20px;">expand_more</span>
</button>
<button class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark pl-4 pr-3 text-text-primary-light dark:text-text-primary-dark hover:bg-background-light dark:hover:bg-background-dark">
<p class="text-sm font-medium leading-normal">Hạng sao</p>
<span class="material-symbols-outlined" style="font-size: 20px;">expand_more</span>
</button>
</div>
</div>
<div class="mt-6">
<div class="overflow-hidden rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark">
<div class="overflow-x-auto">
<table class="w-full min-w-[800px]">
<thead>
<tr class="bg-background-light dark:bg-background-dark">
<th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-text-secondary-light dark:text-text-secondary-dark">ID Khách sạn</th>
<th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-text-secondary-light dark:text-text-secondary-dark">Tên khách sạn</th>
<th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-text-secondary-light dark:text-text-secondary-dark">Địa chỉ</th>
<th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-text-secondary-light dark:text-text-secondary-dark">Số phòng</th>
<th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-text-secondary-light dark:text-text-secondary-dark">Trạng thái</th>
<th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-text-secondary-light dark:text-text-secondary-dark">Hành động</th>
</tr>
</thead>
<tbody class="divide-y divide-border-light dark:divide-border-dark">
<tr>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">#12345</td>
<td class="h-[72px] px-4 py-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Khách sạn Grand Saigon</td>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">Quận 1, TP.HCM</td>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">150</td>
<td class="h-[72px] px-4 py-2">
<div class="inline-flex items-center gap-1.5 rounded-full bg-green-100 dark:bg-green-900/40 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-300">
<span class="size-1.5 rounded-full bg-green-500"></span>
                                                Hoạt động
                                            </div>
</td>
<td class="h-[72px] px-4 py-2">
<div class="flex items-center gap-2">
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-primary dark:hover:text-primary" title="Chỉnh sửa thông tin khách sạn"><span class="material-symbols-outlined" style="font-size: 20px;">edit</span></button>
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-primary dark:hover:text-primary" title="Quản lý phòng"><span class="material-symbols-outlined" style="font-size: 20px;">door_front</span></button>
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-red-500" title="Xóa khách sạn"><span class="material-symbols-outlined" style="font-size: 20px;">delete</span></button>
</div>
</td>
</tr>
<tr>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">#12346</td>
<td class="h-[72px] px-4 py-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Hanoi Daewoo Hotel</td>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">Ba Đình, Hà Nội</td>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">200</td>
<td class="h-[72px] px-4 py-2">
<div class="inline-flex items-center gap-1.5 rounded-full bg-green-100 dark:bg-green-900/40 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-300">
<span class="size-1.5 rounded-full bg-green-500"></span>
                                                Hoạt động
                                            </div>
</td>
<td class="h-[72px] px-4 py-2">
<div class="flex items-center gap-2">
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-primary dark:hover:text-primary" title="Chỉnh sửa thông tin khách sạn"><span class="material-symbols-outlined" style="font-size: 20px;">edit</span></button>
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-primary dark:hover:text-primary" title="Quản lý phòng"><span class="material-symbols-outlined" style="font-size: 20px;">door_front</span></button>
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-red-500" title="Xóa khách sạn"><span class="material-symbols-outlined" style="font-size: 20px;">delete</span></button>
</div>
</td>
</tr>
<tr>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">#12347</td>
<td class="h-[72px] px-4 py-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">InterContinental Danang</td>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">Sơn Trà, Đà Nẵng</td>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">180</td>
<td class="h-[72px] px-4 py-2">
<div class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 dark:bg-gray-700/50 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-300">
<span class="size-1.5 rounded-full bg-gray-400"></span>
                                                Ẩn
                                            </div>
</td>
<td class="h-[72px] px-4 py-2">
<div class="flex items-center gap-2">
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-primary dark:hover:text-primary" title="Chỉnh sửa thông tin khách sạn"><span class="material-symbols-outlined" style="font-size: 20px;">edit</span></button>
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-primary dark:hover:text-primary" title="Quản lý phòng"><span class="material-symbols-outlined" style="font-size: 20px;">door_front</span></button>
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-red-500" title="Xóa khách sạn"><span class="material-symbols-outlined" style="font-size: 20px;">delete</span></button>
</div>
</td>
</tr>
<tr>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">#12348</td>
<td class="h-[72px] px-4 py-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Vinpearl Resort &amp; Spa</td>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">Phú Quốc, Kiên Giang</td>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">350</td>
<td class="h-[72px] px-4 py-2">
<div class="inline-flex items-center gap-1.5 rounded-full bg-green-100 dark:bg-green-900/40 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-300">
<span class="size-1.5 rounded-full bg-green-500"></span>
                                                Hoạt động
                                            </div>
</td>
<td class="h-[72px] px-4 py-2">
<div class="flex items-center gap-2">
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-primary dark:hover:text-primary" title="Chỉnh sửa thông tin khách sạn"><span class="material-symbols-outlined" style="font-size: 20px;">edit</span></button>
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-primary dark:hover:text-primary" title="Quản lý phòng"><span class="material-symbols-outlined" style="font-size: 20px;">door_front</span></button>
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-red-500" title="Xóa khách sạn"><span class="material-symbols-outlined" style="font-size: 20px;">delete</span></button>
</div>
</td>
</tr>
<tr>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">#12349</td>
<td class="h-[72px] px-4 py-2 text-sm font-medium text-text-primary-light dark:text-text-primary-dark">Dalat Palace Heritage Hotel</td>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">TP. Đà Lạt, Lâm Đồng</td>
<td class="h-[72px] px-4 py-2 text-sm font-normal text-text-secondary-light dark:text-text-secondary-dark">120</td>
<td class="h-[72px] px-4 py-2">
<div class="inline-flex items-center gap-1.5 rounded-full bg-green-100 dark:bg-green-900/40 px-2 py-1 text-xs font-medium text-green-700 dark:text-green-300">
<span class="size-1.5 rounded-full bg-green-500"></span>
                                                Hoạt động
                                            </div>
</td>
<td class="h-[72px] px-4 py-2">
<div class="flex items-center gap-2">
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-primary dark:hover:text-primary" title="Chỉnh sửa thông tin khách sạn"><span class="material-symbols-outlined" style="font-size: 20px;">edit</span></button>
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-primary dark:hover:text-primary" title="Quản lý phòng"><span class="material-symbols-outlined" style="font-size: 20px;">door_front</span></button>
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-red-500" title="Xóa khách sạn"><span class="material-symbols-outlined" style="font-size: 20px;">delete</span></button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<div class="flex items-center justify-between border-t border-border-light dark:border-border-dark px-4 py-3">
<p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Hiển thị <span class="font-medium text-text-primary-light dark:text-text-primary-dark">1-5</span> trên <span class="font-medium text-text-primary-light dark:text-text-primary-dark">50</span> kết quả</p>
<div class="flex items-center gap-2">
<button class="flex h-8 w-8 items-center justify-center rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-text-secondary-light dark:text-text-secondary-dark hover:bg-background-light dark:hover:bg-background-dark disabled:opacity-50" disabled="">
<span class="material-symbols-outlined" style="font-size: 20px;">chevron_left</span>
</button>
<button class="flex h-8 w-8 items-center justify-center rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-text-secondary-light dark:text-text-secondary-dark hover:bg-background-light dark:hover:bg-background-dark">
<span class="material-symbols-outlined" style="font-size: 20px;">chevron_right</span>
</button>
</div>
</div>
</div>
</div>
</div>
</main>
</div>
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
<div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-xl bg-surface-light dark:bg-surface-dark shadow-2xl flex flex-col">
<div class="sticky top-0 bg-surface-light dark:bg-surface-dark z-10 p-6 flex items-center justify-between border-b border-border-light dark:border-border-dark">
<h2 class="text-xl font-bold text-text-primary-light dark:text-text-primary-dark">Chỉnh sửa thông tin khách sạn</h2>
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-text-primary-light dark:hover:text-text-primary-dark">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<div class="p-6 space-y-6">
<div class="space-y-4">
<h3 class="text-lg font-semibold text-text-primary-light dark:text-text-primary-dark">Thông tin cơ bản</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
<label class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1 block" for="hotel-name">Tên khách sạn</label>
<input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark" id="hotel-name" type="text" value="Khách sạn Grand Saigon"/>
</div>
<div>
<label class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1 block" for="hotel-address">Địa chỉ</label>
<input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark" id="hotel-address" type="text" value="Quận 1, TP.HCM"/>
</div>
</div>
<div>
<label class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1 block" for="hotel-description">Mô tả</label>
<textarea class="form-textarea w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark" id="hotel-description" rows="4">Một trong những khách sạn lâu đời và sang trọng nhất tại Sài Gòn, mang đậm kiến trúc Pháp cổ.</textarea>
</div>
<div>
<label class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1 block">Hình ảnh</label>
<div class="flex items-center gap-4">
<img alt="Hotel Image" class="h-20 w-20 rounded-lg object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDX2bpfjrqK5sDv5W-4GuSmTOH19J6RXy5zzESbZEdLLrPMfwJDUClMuEEZjqJVuR9NWYieD-P0ye8Sr00_hk49LjSP9ltGsJMG2kTZ3z78QJrADNj0ZVrY5pZTQLd_oCsk_MZGUM7F1P8-Rqt4TjxGGDuFFKi9dpxwjgVWgp4Xj2necD1y1uyXaZy9tJbHWn36x7rbdLlIx7GlwFMFWbfFqSKcuJsZWB2VdUuqrZKQO8_yaxV96z9sPTE6jtSgSZ-8RfpKVvDDt6pO"/>
<button class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark text-text-primary-light dark:text-text-primary-dark text-sm font-bold leading-normal tracking-[0.015em] hover:bg-background-light dark:hover:bg-background-dark">
<span class="material-symbols-outlined" style="font-size:20px">upload</span>
<span class="truncate">Tải ảnh lên</span>
</button>
</div>
</div>
</div>
<div class="space-y-4">
<div class="flex items-center justify-between">
<h3 class="text-lg font-semibold text-text-primary-light dark:text-text-primary-dark">Quản lý phòng</h3>
<button class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-9 px-3 bg-primary/10 text-primary text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/20">
<span class="material-symbols-outlined" style="font-size:18px">add</span>
<span class="truncate">Thêm loại phòng</span>
</button>
</div>
<div class="space-y-4">
<div class="rounded-lg border border-border-light dark:border-border-dark p-4">
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
<div>
<label class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1 block" for="room-type-1">Loại phòng</label>
<input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark" id="room-type-1" type="text" value="Standard"/>
</div>
<div>
<label class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1 block" for="room-count-1">Số lượng phòng</label>
<input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark" id="room-count-1" type="number" value="50"/>
</div>
<div>
<label class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1 block" for="room-price-1">Giá (VND)</label>
<input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark" id="room-price-1" type="text" value="1,200,000"/>
</div>
<div class="flex items-center gap-2">
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-red-500"><span class="material-symbols-outlined" style="font-size: 20px;">delete</span></button>
</div>
</div>
</div>
<div class="rounded-lg border border-border-light dark:border-border-dark p-4">
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
<div>
<label class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1 block" for="room-type-2">Loại phòng</label>
<input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark" id="room-type-2" type="text" value="Deluxe"/>
</div>
<div>
<label class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1 block" for="room-count-2">Số lượng phòng</label>
<input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark" id="room-count-2" type="number" value="70"/>
</div>
<div>
<label class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1 block" for="room-price-2">Giá (VND)</label>
<input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark" id="room-price-2" type="text" value="2,500,000"/>
</div>
<div class="flex items-center gap-2">
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-red-500"><span class="material-symbols-outlined" style="font-size: 20px;">delete</span></button>
</div>
</div>
</div>
<div class="rounded-lg border border-border-light dark:border-border-dark p-4">
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
<div>
<label class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1 block" for="room-type-3">Loại phòng</label>
<input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark" id="room-type-3" type="text" value="Suite"/>
</div>
<div>
<label class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1 block" for="room-count-3">Số lượng phòng</label>
<input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark" id="room-count-3" type="number" value="30"/>
</div>
<div>
<label class="text-sm font-medium text-text-primary-light dark:text-text-primary-dark mb-1 block" for="room-price-3">Giá (VND)</label>
<input class="form-input w-full rounded-lg border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark" id="room-price-3" type="text" value="5,000,000"/>
</div>
<div class="flex items-center gap-2">
<button class="text-text-secondary-light dark:text-text-secondary-dark hover:text-red-500"><span class="material-symbols-outlined" style="font-size: 20px;">delete</span></button>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="sticky bottom-0 bg-surface-light dark:bg-surface-dark z-10 p-6 flex justify-end gap-3 border-t border-border-light dark:border-border-dark">
<button class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark text-text-primary-light dark:text-text-primary-dark text-sm font-bold leading-normal tracking-[0.015em] hover:bg-background-light dark:hover:bg-background-dark">
<span class="truncate">Hủy bỏ</span>
</button>
<button class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
<span class="truncate">Lưu thay đổi</span>
</button>
</div>
</div>
</div>
</body></html>