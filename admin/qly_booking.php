<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Quản lý đặt chỗ</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24;
        }
    </style>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#0da6f2",
              "background-light": "#f5f7f8",
              "background-dark": "#101c22",
              "success": "#28a745",
              "warning": "#ffc107",
              "danger": "#dc3545",
              "surface-light": "#ffffff",
              "surface-dark": "#1a2a33",
              "text-light": "#0d171c",
              "text-dark": "#e7eff4",
              "text-muted-light": "#49819c",
              "text-muted-dark": "#a0b8c4",
              "border-light": "#cee0e8",
              "border-dark": "#2c4a5c"
            },
            fontFamily: {
              "display": ["Manrope", "sans-serif"]
            },
            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
          },
        },
      }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
<div class="flex min-h-screen">
<!-- SideNavBar -->
<aside class="w-64 flex-shrink-0 bg-surface-light dark:bg-surface-dark border-r border-border-light dark:border-border-dark flex flex-col">
<div class="p-6">
<h2 class="text-2xl font-bold text-primary">TravelAdmin</h2>
</div>
<div class="flex flex-col justify-between flex-grow p-4">
<div class="flex flex-col gap-4">
<div class="flex gap-3 items-center">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Admin user avatar" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB9Fxud4ao4qbAYYiLFrJhuY15RLPYKge85DV-8A2wL1NXS5hJw684wIYxbuXVlmE_07PYBttUIH8OY51JqRN97OtqV0Ni7X5gceqRFi-Hyx9Gxzf-Nxvgoxjpcj1z-S3PkaTCE-sLWIOsffaCbvB8rU6FQZR-MPf0YsvLK0pyZDq8QrAVkcW0M-6i4ZvfH25Z8q0htYmbDUicG4pgWzhSBxhwUtUrLycWmZKfAQvoHG0CFpH59aBGRcwvu8g1IkNul-kkNE7hC-C46");'></div>
<div class="flex flex-col">
<h1 class="text-text-light dark:text-text-dark text-base font-medium leading-normal">Admin Name</h1>
<p class="text-text-muted-light dark:text-text-muted-dark text-sm font-normal leading-normal">admin@travel.com</p>
</div>
</div>
<div class="flex flex-col gap-2 mt-4">
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 transition-colors" href="#">
<span class="material-symbols-outlined text-text-muted-light dark:text-text-muted-dark">dashboard</span>
<p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal">Dashboard</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/10 text-primary" href="#">
<span class="material-symbols-outlined !fill-1">confirmation_number</span>
<p class="font-medium leading-normal text-sm">Quản lý đặt chỗ</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 transition-colors" href="#">
<span class="material-symbols-outlined text-text-muted-light dark:text-text-muted-dark">group</span>
<p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal">Quản lý người dùng</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 transition-colors" href="#">
<span class="material-symbols-outlined text-text-muted-light dark:text-text-muted-dark">bar_chart</span>
<p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal">Báo cáo</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-primary/10 transition-colors" href="#">
<span class="material-symbols-outlined text-text-muted-light dark:text-text-muted-dark">settings</span>
<p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal">Cài đặt</p>
</a>
</div>
</div>
<div class="flex flex-col gap-1">
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-danger/10 text-danger transition-colors" href="#">
<span class="material-symbols-outlined">logout</span>
<p class="text-sm font-medium leading-normal">Đăng xuất</p>
</a>
</div>
</div>
</aside>
<!-- Main Content -->
<main class="flex-1 p-8">
<div class="max-w-7xl mx-auto">
<!-- PageHeading -->
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
<p class="text-text-light dark:text-text-dark text-4xl font-black leading-tight tracking-[-0.033em]">Quản lý đặt chỗ</p>
<button class="flex items-center justify-center gap-2 h-11 px-6 bg-primary text-white rounded-lg font-bold text-sm hover:opacity-90 transition-opacity">
<span class="material-symbols-outlined">add_circle</span>
<span>Thêm đặt chỗ</span>
</button>
</div>
<div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-border-light dark:border-border-dark">
<!-- SearchBar and Filters -->
<div class="flex flex-col md:flex-row gap-4 mb-4">
<!-- SearchBar -->
<div class="flex-grow">
<label class="flex flex-col h-12 w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-full">
<div class="text-text-muted-light dark:text-text-muted-dark flex bg-background-light dark:bg-background-dark items-center justify-center pl-4 rounded-l-lg">
<span class="material-symbols-outlined">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-background-light dark:bg-background-dark h-full placeholder:text-text-muted-light placeholder:dark:text-text-muted-dark px-4 rounded-l-none pl-2 text-base font-normal leading-normal" placeholder="Tìm theo mã đặt chỗ, tên khách hàng..." value=""/>
</div>
</label>
</div>
<!-- Chips -->
<div class="flex gap-3 items-center">
<button class="flex h-12 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-background-light dark:bg-background-dark px-4 hover:bg-primary/10 transition-colors">
<p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal">Trạng thái</p>
<span class="material-symbols-outlined text-text-muted-light dark:text-text-muted-dark">arrow_drop_down</span>
</button>
<button class="flex h-12 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-background-light dark:bg-background-dark px-4 hover:bg-primary/10 transition-colors">
<p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal">Loại dịch vụ</p>
<span class="material-symbols-outlined text-text-muted-light dark:text-text-muted-dark">arrow_drop_down</span>
</button>
<button class="flex h-12 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-background-light dark:bg-background-dark px-4 hover:bg-primary/10 transition-colors">
<p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal">Ngày đặt</p>
<span class="material-symbols-outlined text-text-muted-light dark:text-text-muted-dark">arrow_drop_down</span>
</button>
</div>
</div>
<!-- Table -->
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead>
<tr class="border-b border-border-light dark:border-border-dark">
<th class="px-4 py-3 text-text-muted-light dark:text-text-muted-dark text-sm font-medium">Mã đặt chỗ</th>
<th class="px-4 py-3 text-text-muted-light dark:text-text-muted-dark text-sm font-medium">Tên khách hàng</th>
<th class="px-4 py-3 text-text-muted-light dark:text-text-muted-dark text-sm font-medium">Loại dịch vụ</th>
<th class="px-4 py-3 text-text-muted-light dark:text-text-muted-dark text-sm font-medium">Thông tin chi tiết</th>
<th class="px-4 py-3 text-text-muted-light dark:text-text-muted-dark text-sm font-medium">Ngày tạo</th>
<th class="px-4 py-3 text-text-muted-light dark:text-text-muted-dark text-sm font-medium">Tổng tiền</th>
<th class="px-4 py-3 text-text-muted-light dark:text-text-muted-dark text-sm font-medium">Trạng thái</th>
<th class="px-4 py-3 text-text-muted-light dark:text-text-muted-dark text-sm font-medium">Hành động</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-border-light dark:border-border-dark hover:bg-primary/5 transition-colors">
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">#12345</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">Nguyễn Văn A</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal flex items-center gap-2"><span class="material-symbols-outlined text-primary">flight</span><span>Vé máy bay</span></td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">SGN -&gt; HAN</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">01/08/2024</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">2.500.000đ</td>
<td class="px-4 py-3"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">Đã xác nhận</span></td>
<td class="px-4 py-3"><button class="text-primary font-bold text-sm hover:underline">Xem chi tiết</button></td>
</tr>
<tr class="border-b border-border-light dark:border-border-dark hover:bg-primary/5 transition-colors">
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">#12346</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">Trần Thị B</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal flex items-center gap-2"><span class="material-symbols-outlined text-primary">hotel</span><span>Khách sạn</span></td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">Khách sạn ABC</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">01/08/2024</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">1.800.000đ</td>
<td class="px-4 py-3"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">Chờ thanh toán</span></td>
<td class="px-4 py-3"><button class="text-primary font-bold text-sm hover:underline">Xem chi tiết</button></td>
</tr>
<tr class="border-b border-border-light dark:border-border-dark hover:bg-primary/5 transition-colors">
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">#12347</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">Lê Văn C</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal flex items-center gap-2"><span class="material-symbols-outlined text-primary">directions_car</span><span>Thuê xe</span></td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">Toyota Vios</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">31/07/2024</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">1.200.000đ</td>
<td class="px-4 py-3"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger/10 text-danger">Đã hủy</span></td>
<td class="px-4 py-3"><button class="text-primary font-bold text-sm hover:underline">Xem chi tiết</button></td>
</tr>
<tr class="border-b border-border-light dark:border-border-dark hover:bg-primary/5 transition-colors">
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">#12348</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">Phạm Thị D</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal flex items-center gap-2"><span class="material-symbols-outlined text-primary">flight</span><span>Vé máy bay</span></td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">HAN -&gt; DAD</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">30/07/2024</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">3.100.000đ</td>
<td class="px-4 py-3"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">Đã xác nhận</span></td>
<td class="px-4 py-3"><button class="text-primary font-bold text-sm hover:underline">Xem chi tiết</button></td>
</tr>
<tr class="border-b border-border-light dark:border-border-dark hover:bg-primary/5 transition-colors">
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">#12349</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">Hoàng Văn E</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal flex items-center gap-2"><span class="material-symbols-outlined text-primary">hotel</span><span>Khách sạn</span></td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">Khách sạn XYZ</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">29/07/2024</td>
<td class="px-4 py-3 text-text-light dark:text-text-dark text-sm font-normal">2.000.000đ</td>
<td class="px-4 py-3"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">Đã xác nhận</span></td>
<td class="px-4 py-3"><button class="text-primary font-bold text-sm hover:underline">Xem chi tiết</button></td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="flex items-center justify-between mt-6">
<p class="text-sm text-text-muted-light dark:text-text-muted-dark">Hiển thị 1-5 trên 50 kết quả</p>
<div class="flex items-center gap-2">
<button class="flex items-center justify-center size-9 rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark hover:bg-primary/10 transition-colors disabled:opacity-50" disabled="">
<span class="material-symbols-outlined">chevron_left</span>
</button>
<button class="flex items-center justify-center size-9 rounded-lg border border-primary bg-primary text-white font-bold text-sm">
<span>1</span>
</button>
<button class="flex items-center justify-center size-9 rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-sm hover:bg-primary/10 transition-colors">
<span>2</span>
</button>
<button class="flex items-center justify-center size-9 rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-sm hover:bg-primary/10 transition-colors">
<span>3</span>
</button>
<span class="text-text-muted-light dark:text-text-muted-dark">...</span>
<button class="flex items-center justify-center size-9 rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark text-sm hover:bg-primary/10 transition-colors">
<span>10</span>
</button>
<button class="flex items-center justify-center size-9 rounded-lg border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark hover:bg-primary/10 transition-colors">
<span class="material-symbols-outlined">chevron_right</span>
</button>
</div>
</div>
</div>
</div>
</main>
</div>
</body></html>