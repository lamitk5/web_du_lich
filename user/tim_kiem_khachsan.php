<!DOCTYPE html>
<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Tìm kiếm &amp; Đặt phòng khách sạn</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#005A9C",
                        "secondary": "#FFA500",
                        "background-light": "#F5F5F5",
                        "background-dark": "#101c22",
                        "text-light": "#111827",
                        "text-dark": "#F5F5F5",
                        "card-light": "#FFFFFF",
                        "card-dark": "#1f2937",
                        "border-light": "#e5e7eb",
                        "border-dark": "#374151"
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "0.75rem",
                        "xl": "1rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
<div class="relative flex min-h-screen w-full flex-col">
<header class="sticky top-0 z-50 bg-card-light dark:bg-card-dark/80 backdrop-blur-sm border-b border-border-light dark:border-border-dark">
<div class="container mx-auto px-4">
<div class="flex h-16 items-center justify-between">
<div class="flex items-center gap-6">
<div class="flex items-center gap-2 text-primary">
<span class="material-symbols-outlined text-3xl">travel_explore</span>
<h2 class="text-xl font-bold leading-tight tracking-[-0.015em] text-text-light dark:text-text-dark">TravelCo</h2>
</div>
<nav class="hidden md:flex items-center gap-6">
<a class="text-sm font-medium text-text-light dark:text-text-dark hover:text-primary dark:hover:text-primary" href="#">Chuyến bay</a>
<a class="text-sm font-bold text-primary dark:text-primary" href="#">Khách sạn</a>
<a class="text-sm font-medium text-text-light dark:text-text-dark hover:text-primary dark:hover:text-primary" href="#">Hoạt động</a>
<a class="text-sm font-medium text-text-light dark:text-text-dark hover:text-primary dark:hover:text-primary" href="#">Cho thuê xe</a>
</nav>
</div>
<div class="flex items-center gap-3">
<button class="hidden sm:flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/10 text-primary text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/20">
<span class="truncate">Đăng nhập</span>
</button>
<button class="hidden sm:flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
<span class="truncate">Đăng ký</span>
</button>
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Avatar of the user" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB-8CKwsS1NQ1rDqhlEfWmUa6c62NZ6FmKnawEGdA3K2XuPHLrrgoHbVkwb0Sprln6IhzbQAEHibXnZXeSY9ZTUh7idLC4MJSuxl5jZgQDyU45MOG6ZyFi57IhPa0kRDfsFcx8ZNPToqK9RX5qsnPOmna01SVqZ7Qwkln06zZWevGZkhTR0og5HWHy_BMZxpDZIRQ5i5X8IAyQsqxGj9SV6EGLq3gGIZMjNEaKCjCasdOjwLQ_ZXwuU_gI-BgPQ5Um4bZii2ZvW1yej");'></div>
</div>
</div>
</div>
</header>
<main class="flex-grow">
<div class="container mx-auto px-4 py-8">
<section class="mb-8">
<div class="bg-card-light dark:bg-card-dark p-4 rounded-xl shadow-lg">
<div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
<label class="flex flex-col md:col-span-3">
<p class="text-sm font-medium leading-normal pb-2">Điểm đến</p>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-light/50 dark:text-text-dark/50">location_on</span>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg focus:outline-0 focus:ring-2 focus:ring-primary h-12 placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 p-3 pl-11 text-base font-normal leading-normal bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark" placeholder="Thành phố, khách sạn, địa danh..." value="Đà Nẵng"/>
</div>
</label>
<div class="flex flex-col md:col-span-4">
<p class="text-sm font-medium leading-normal pb-2">Ngày nhận - trả phòng</p>
<div class="grid grid-cols-2 gap-px bg-border-light dark:bg-border-dark rounded-lg overflow-hidden">
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-light/50 dark:text-text-dark/50">calendar_month</span>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden text-text-light dark:text-text-dark focus:outline-0 focus:ring-0 border-0 h-12 placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 p-3 pl-11 text-base font-normal leading-normal bg-background-light dark:bg-background-dark" type="text" value="05 Tháng 11, 2024"/>
</div>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-light/50 dark:text-text-dark/50">calendar_month</span>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden text-text-light dark:text-text-dark focus:outline-0 focus:ring-0 border-0 h-12 placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 p-3 pl-11 text-base font-normal leading-normal bg-background-light dark:bg-background-dark" type="text" value="07 Tháng 12, 2024"/>
</div>
</div>
</div>
<label class="flex flex-col md:col-span-3">
<p class="text-sm font-medium leading-normal pb-2">Số lượng khách</p>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-light/50 dark:text-text-dark/50">group</span>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg focus:outline-0 focus:ring-2 focus:ring-primary h-12 placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 p-3 pl-11 text-base font-normal leading-normal bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark" value="2 người lớn, 1 phòng"/>
</div>
</label>
<button class="md:col-span-2 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
<span class="material-symbols-outlined text-2xl mr-2">search</span>
<span>Tìm kiếm</span>
</button>
</div>
</div>
</section>
<section>
<div class="grid grid-cols-12 gap-6">
<div class="col-span-12 lg:col-span-8">
<div class="grid grid-cols-1 @container">
<div class="flex flex-col">
<div class="flex flex-col sm:flex-row justify-between items-baseline mb-4">
<h2 class="text-2xl font-bold">125 khách sạn tại Đà Nẵng</h2>
<div class="flex items-center gap-2">
<span class="text-sm font-medium">Sắp xếp theo:</span>
<select class="form-select text-sm font-bold border-none bg-transparent focus:ring-0 p-1 pr-7">
<option>Mặc định</option>
<option>Giá thấp đến cao</option>
<option>Đánh giá cao nhất</option>
</select>
</div>
</div>
<div class="bg-card-light dark:bg-card-dark p-4 rounded-xl shadow-sm mb-6">
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-center">
<div>
<h4 class="font-semibold mb-2 text-sm">Khoảng giá</h4>
<input class="w-full h-2 bg-background-light dark:bg-background-dark rounded-lg appearance-none cursor-pointer accent-primary" max="10000000" min="0" type="range" value="5000000"/>
<div class="flex justify-between text-xs text-text-light/70 dark:text-text-dark/70 mt-1">
<span>0đ</span>
<span>10M+</span>
</div>
</div>
<div>
<h4 class="font-semibold mb-2 text-sm">Hạng khách sạn</h4>
<div class="flex justify-between items-center space-x-1">
<button class="flex-1 border border-border-light dark:border-border-dark rounded p-2 text-center hover:border-secondary hover:text-secondary group transition-colors"><div class="flex justify-center text-secondary/50 group-hover:text-secondary transition-colors"><span class="material-symbols-outlined !text-base">star</span></div></button>
<button class="flex-1 border border-border-light dark:border-border-dark rounded p-2 text-center hover:border-secondary hover:text-secondary group transition-colors"><div class="flex justify-center text-secondary/50 group-hover:text-secondary transition-colors"><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span></div></button>
<button class="flex-1 border border-secondary bg-primary/10 text-secondary rounded p-2 text-center group transition-colors"><div class="flex justify-center"><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span></div></button>
<button class="flex-1 border border-border-light dark:border-border-dark rounded p-2 text-center hover:border-secondary hover:text-secondary group transition-colors"><div class="flex justify-center text-secondary/50 group-hover:text-secondary transition-colors"><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span></div></button>
<button class="flex-1 border border-border-light dark:border-border-dark rounded p-2 text-center hover:border-secondary hover:text-secondary group transition-colors"><div class="flex justify-center text-secondary/50 group-hover:text-secondary transition-colors"><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span></div></button>
</div>
</div>
<div class="col-span-1 sm:col-span-2 lg:col-span-2">
<h4 class="font-semibold mb-2 text-sm">Tiện nghi phổ biến</h4>
<div class="grid grid-cols-2 @md:grid-cols-4 gap-x-4 gap-y-2">
<label class="flex items-center space-x-2 text-sm cursor-pointer"><input checked="" class="form-checkbox rounded text-primary focus:ring-primary/50" type="checkbox"/><span>Hồ bơi</span></label>
<label class="flex items-center space-x-2 text-sm cursor-pointer"><input checked="" class="form-checkbox rounded text-primary focus:ring-primary/50" type="checkbox"/><span>Wi-Fi</span></label>
<label class="flex items-center space-x-2 text-sm cursor-pointer"><input class="form-checkbox rounded text-primary focus:ring-primary/50" type="checkbox"/><span>Bãi đỗ xe</span></label>
<label class="flex items-center space-x-2 text-sm cursor-pointer"><input class="form-checkbox rounded text-primary focus:ring-primary/50" type="checkbox"/><span>Gần bãi biển</span></label>
</div>
</div>
</div>
</div>
<div class="flex flex-col gap-6">
<div class="group bg-card-light dark:bg-card-dark rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
<div class="relative md:w-1/3">
<div class="h-48 md:h-full w-full bg-cover bg-center" data-alt="Luxury hotel with a large pool and palm trees" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDxsPC3cylWkeNe0H-dSiD3EUpffDiKpBWha_L8fMvfRJquDgE7OKHb9XfoZJmF6LQeLW0gHMNIJVMsrI2b6zzSz_ICvkZW_yMwyMNVkWOVhMBmnGZvbLkbOU0LWhv0I73IkWgQNMHzJer5eCRdxTHGIuipNDu3RZF9CbfL2RGeyCix8ArCKwdN4NbwXzVe4LspF99bDb8AWg9WvXVezR5KGn8VRWBX4bKxlrQtht7fNPsRa6lY00Y0sb5v5uPX2SNCPI94vwUKlTaM')"></div>
<div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute bottom-2 right-2 flex gap-1">
<div class="h-14 w-14 rounded-lg bg-cover bg-center border-2 border-white dark:border-card-dark" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDgb2rk-LXm2Iv_d76zdmrnvWUWhGsGl8uSqdduYyHwuLU9oWyKiWm7LbQm1wzr7NJOWBOLjpdJ3SZvSxE2LU9Jmp499uCD_8dyPExhFg0ARPjUj-kybqkxkKs6e-DhPuVdD7baHD5ctESjidAprLixkiy8djQ1iGw4DAxB0xE6dzmX-ySNmU1ZL1a6jligl5G5kOs999RbfjsVKHobf8C_tALZscNpAF8HsmHpg9BvMpd9SW4oVRTsSxGMXo883tf5Zv-SN45d_Xvr')"></div>
<div class="h-14 w-14 rounded-lg bg-cover bg-center border-2 border-white dark:border-card-dark" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDgb2rk-LXm2Iv_d76zdmrnvWUWhGsGl8uSqdduYyHwuLU9oWyKiWm7LbQm1wzr7NJOWBOLjpdJ3SZvSxE2LU9Jmp499uCD_8dyPExhFg0ARPjUj-kybqkxkKs6e-DhPuVdD7baHD5ctESjidAprLixkiy8djQ1iGw4DAxB0xE6dzmX-ySNmU1ZL1a6jligl5G5kOs999RbfjsVKHobf8C_tALZscNpAF8HsmHpg9BvMpd9SW4oVRTsSxGMXo883tf5Zv-SN45d_Xvr')"></div>
<div class="h-14 w-14 rounded-lg bg-black/50 flex items-center justify-center text-white font-bold text-lg border-2 border-white dark:border-card-dark">+5</div>
</div>
</div>
<div class="p-4 flex flex-col flex-grow md:w-2/3">
<div class="flex-grow">
<div class="flex items-start justify-between">
<div>
<div class="flex items-center text-secondary mb-1">
<span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span>
</div>
<h3 class="text-lg font-bold group-hover:text-primary transition-colors duration-300">InterContinental Danang</h3>
</div>
<div class="text-right flex-shrink-0 ml-4">
<p class="text-sm text-text-light/70 dark:text-text-dark/70">Từ</p>
<p class="text-2xl font-extrabold text-primary">8.500.000₫</p>
</div>
</div>
<div class="flex items-center gap-2 mt-1 mb-3">
<span class="bg-green-600 text-white text-sm font-bold px-2 py-0.5 rounded">9.2</span>
<span class="text-sm font-semibold">Tuyệt vời</span>
<span class="text-sm text-text-light/70 dark:text-text-dark/70">(1,280 đánh giá)</span>
</div>
<div class="border-y border-border-light dark:border-border-dark py-3 my-3">
<h4 class="text-sm font-semibold mb-2">Tiện nghi nổi bật</h4>
<div class="flex flex-wrap gap-x-4 gap-y-2 text-sm text-text-light/80 dark:text-text-dark/80">
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">pool</span>Hồ bơi</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">wifi</span>Wi-Fi miễn phí</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">beach_access</span>Bãi biển riêng</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">fitness_center</span>Trung tâm thể dục</span>
</div>
</div>
</div>
<div class="flex justify-between items-end mt-2">
<div class="text-sm text-green-600 font-semibold">
<p>Miễn phí hủy</p>
</div>
<a class="inline-flex items-center justify-center rounded-lg h-10 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors" href="#">Xem phòng trống</a>
</div>
</div>
</div>
<div class="group bg-card-light dark:bg-card-dark rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
<div class="relative md:w-1/3">
<div class="h-48 md:h-full w-full bg-cover bg-center" data-alt="Modern hotel room with a view of the sea" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDx8fRWgAIEA6iFvl9mHAFK1QvclfsgMA4NcinrOY4PrmfP-tknowpEDVsHqCXkCHGxxsoXylfsGEnIypQdxCA-NAXggKVnbXgUs6MPE_C0S4-X5h4J0Vs5PbrW6hocKYYg_QV3SpkNRg8RXJNANdKUuEN7B9xdWFLraWWTeCwDWeeRhsLpviRUW2cmQGA_TXy77CEL9nF9plYd-qXUA66Sz6L7AkAoGEL0wl4BNapeSduhIhSB6oOOFqc6CSnJk0_NC47eYPtUlEeg')"></div>
<div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute bottom-2 right-2 flex gap-1">
<div class="h-14 w-14 rounded-lg bg-cover bg-center border-2 border-white dark:border-card-dark" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDgb2rk-LXm2Iv_d76zdmrnvWUWhGsGl8uSqdduYyHwuLU9oWyKiWm7LbQm1wzr7NJOWBOLjpdJ3SZvSxE2LU9Jmp499uCD_8dyPExhFg0ARPjUj-kybqkxkKs6e-DhPuVdD7baHD5ctESjidAprLixkiy8djQ1iGw4DAxB0xE6dzmX-ySNmU1ZL1a6jligl5G5kOs999RbfjsVKHobf8C_tALZscNpAF8HsmHpg9BvMpd9SW4oVRTsSxGMXo883tf5Zv-SN45d_Xvr')"></div>
<div class="h-14 w-14 rounded-lg bg-cover bg-center border-2 border-white dark:border-card-dark" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDgb2rk-LXm2Iv_d76zdmrnvWUWhGsGl8uSqdduYyHwuLU9oWyKiWm7LbQm1wzr7NJOWBOLjpdJ3SZvSxE2LU9Jmp499uCD_8dyPExhFg0ARPjUj-kybqkxkKs6e-DhPuVdD7baHD5ctESjidAprLixkiy8djQ1iGw4DAxB0xE6dzmX-ySNmU1ZL1a6jligl5G5kOs999RbfjsVKHobf8C_tALZscNpAF8HsmHpg9BvMpd9SW4oVRTsSxGMXo883tf5Zv-SN45d_Xvr')"></div>
<div class="h-14 w-14 rounded-lg bg-black/50 flex items-center justify-center text-white font-bold text-lg border-2 border-white dark:border-card-dark">+8</div>
</div>
</div>
<div class="p-4 flex flex-col flex-grow md:w-2/3">
<div class="flex-grow">
<div class="flex items-start justify-between">
<div>
<div class="flex items-center text-secondary mb-1">
<span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base text-border-light dark:text-border-dark">star</span>
</div>
<h3 class="text-lg font-bold group-hover:text-primary transition-colors duration-300">Hyatt Regency Danang</h3>
</div>
<div class="text-right flex-shrink-0 ml-4">
<p class="text-sm text-text-light/70 dark:text-text-dark/70">Từ</p>
<p class="text-2xl font-extrabold text-primary">4.200.000₫</p>
</div>
</div>
<div class="flex items-center gap-2 mt-1 mb-3">
<span class="bg-green-600 text-white text-sm font-bold px-2 py-0.5 rounded">8.8</span>
<span class="text-sm font-semibold">Rất tốt</span>
<span class="text-sm text-text-light/70 dark:text-text-dark/70">(975 đánh giá)</span>
</div>
<div class="border-y border-border-light dark:border-border-dark py-3 my-3">
<h4 class="text-sm font-semibold mb-2">Tiện nghi nổi bật</h4>
<div class="flex flex-wrap gap-x-4 gap-y-2 text-sm text-text-light/80 dark:text-text-dark/80">
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">pool</span>Hồ bơi</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">wifi</span>Wi-Fi miễn phí</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">local_parking</span>Bãi đỗ xe</span>
</div>
</div>
</div>
<div class="flex justify-between items-end mt-2">
<div class="text-sm text-orange-500 font-semibold">
<p>Hủy có tính phí</p>
</div>
<a class="inline-flex items-center justify-center rounded-lg h-10 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors" href="#">Xem phòng trống</a>
</div>
</div>
</div>
<div class="group bg-card-light dark:bg-card-dark rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
<div class="relative md:w-1/3">
<div class="h-48 md:h-full w-full bg-cover bg-center" data-alt="Hotel exterior with a green lawn and modern architecture" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuA2gc3JbEIkd-h8s-1OORlT7Cx9ELkXIC9gr78CtczWJyLshoKI_vv4ylky7osAEtnXKa0EeU7R64ocjz2RKmHBLWo1DysegW52PWdr7P9dHciPshWrNVtELO2SD9vtvc8GUuAaNEgRSAKoAOqPSnf4UJpEvMAUkGr9qXtVj66EfGU3tgkt79K5MEhqNoP-JzBaUcYujTJQhmn1RhcU_TvpIDiIZnSv108Nw_A8LYirV3iw1bj54x0p8GSO1VazCI5yNxUkhSC1KQI3')"></div>
<div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
<div class="absolute bottom-2 right-2 flex gap-1">
<div class="h-14 w-14 rounded-lg bg-cover bg-center border-2 border-white dark:border-card-dark" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDgb2rk-LXm2Iv_d76zdmrnvWUWhGsGl8uSqdduYyHwuLU9oWyKiWm7LbQm1wzr7NJOWBOLjpdJ3SZvSxE2LU9Jmp499uCD_8dyPExhFg0ARPjUj-kybqkxkKs6e-DhPuVdD7baHD5ctESjidAprLixkiy8djQ1iGw4DAxB0xE6dzmX-ySNmU1ZL1a6jligl5G5kOs999RbfjsVKHobf8C_tALZscNpAF8HsmHpg9BvMpd9SW4oVRTsSxGMXo883tf5Zv-SN45d_Xvr')"></div>
<div class="h-14 w-14 rounded-lg bg-cover bg-center border-2 border-white dark:border-card-dark" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDgb2rk-LXm2Iv_d76zdmrnvWUWhGsGl8uSqdduYyHwuLU9oWyKiWm7LbQm1wzr7NJOWBOLjpdJ3SZvSxE2LU9Jmp499uCD_8dyPExhFg0ARPjUj-kybqkxkKs6e-DhPuVdD7baHD5ctESjidAprLixkiy8djQ1iGw4DAxB0xE6dzmX-ySNmU1ZL1a6jligl5G5kOs999RbfjsVKHobf8C_tALZscNpAF8HsmHpg9BvMpd9SW4oVRTsSxGMXo883tf5Zv-SN45d_Xvr')"></div>
<div class="h-14 w-14 rounded-lg bg-black/50 flex items-center justify-center text-white font-bold text-lg border-2 border-white dark:border-card-dark">+12</div>
</div>
</div>
<div class="p-4 flex flex-col flex-grow md:w-2/3">
<div class="flex-grow">
<div class="flex items-start justify-between">
<div>
<div class="flex items-center text-secondary mb-1">
<span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base">star</span><span class="material-symbols-outlined !text-base text-border-light dark:text-border-dark">star</span>
</div>
<h3 class="text-lg font-bold group-hover:text-primary transition-colors duration-300">TIA Wellness Resort</h3>
</div>
<div class="text-right flex-shrink-0 ml-4">
<p class="text-sm text-text-light/70 dark:text-text-dark/70">Từ</p>
<p class="text-2xl font-extrabold text-primary">12.000.000₫</p>
</div>
</div>
<div class="flex items-center gap-2 mt-1 mb-3">
<span class="bg-green-600 text-white text-sm font-bold px-2 py-0.5 rounded">9.5</span>
<span class="text-sm font-semibold">Xuất sắc</span>
<span class="text-sm text-text-light/70 dark:text-text-dark/70">(820 đánh giá)</span>
</div>
<div class="border-y border-border-light dark:border-border-dark py-3 my-3">
<h4 class="text-sm font-semibold mb-2">Tiện nghi nổi bật</h4>
<div class="flex flex-wrap gap-x-4 gap-y-2 text-sm text-text-light/80 dark:text-text-dark/80">
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">spa</span>Spa &amp; Wellness</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">pool</span>Hồ bơi</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">wifi</span>Wi-Fi miễn phí</span>
</div>
</div>
</div>
<div class="flex justify-between items-end mt-2">
<div class="text-sm text-green-600 font-semibold">
<p>Miễn phí hủy</p>
</div>
<a class="inline-flex items-center justify-center rounded-lg h-10 px-5 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition-colors" href="#">Xem phòng trống</a>
</div>
</div>
</div>
</div>
<nav class="flex items-center justify-center pt-8">
<ul class="flex items-center -space-x-px h-10 text-base">
<li><a class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-text-light/60 dark:text-text-dark/60 bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark rounded-s-lg hover:bg-background-light dark:hover:bg-background-dark transition-colors" href="#">Previous</a></li>
<li><a class="flex items-center justify-center px-4 h-10 leading-tight text-white bg-primary border border-primary" href="#">1</a></li>
<li><a class="flex items-center justify-center px-4 h-10 leading-tight text-text-light/60 dark:text-text-dark/60 bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark hover:bg-background-light dark:hover:bg-background-dark transition-colors" href="#">2</a></li>
<li><a class="flex items-center justify-center px-4 h-10 leading-tight text-text-light/60 dark:text-text-dark/60 bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark hover:bg-background-light dark:hover:bg-background-dark transition-colors" href="#">3</a></li>
<li><a class="flex items-center justify-center px-4 h-10 leading-tight text-text-light/60 dark:text-text-dark/60 bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark rounded-e-lg hover:bg-background-light dark:hover:bg-background-dark transition-colors" href="#">Next</a></li>
</ul>
</nav>
</div>
</div>
</div>
<aside class="col-span-12 lg:col-span-4">
<div class="sticky top-24">
<div class="w-full h-[80vh] bg-border-light dark:bg-border-dark rounded-xl shadow-lg overflow-hidden flex items-center justify-center">
<div class="text-center text-text-light/50 dark:text-text-dark/50">
<span class="material-symbols-outlined text-6xl">map</span>
<p class="mt-2 font-medium">Bản đồ sẽ hiển thị tại đây</p>
</div>
</div>
</div>
</aside>
</div>
</section>
</div>
</main>
<footer class="bg-card-light dark:bg-card-dark border-t border-border-light dark:border-border-dark mt-12">
<div class="container mx-auto px-4 py-8">
<div class="grid grid-cols-2 md:grid-cols-4 gap-8">
<div>
<h4 class="font-bold mb-2">Về chúng tôi</h4>
<ul class="space-y-1 text-sm text-text-light/80 dark:text-text-dark/80">
<li><a class="hover:underline hover:text-primary transition-colors" href="#">Giới thiệu</a></li>
<li><a class="hover:underline hover:text-primary transition-colors" href="#">Tuyển dụng</a></li>
<li><a class="hover:underline hover:text-primary transition-colors" href="#">Báo chí</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-2">Hỗ trợ</h4>
<ul class="space-y-1 text-sm text-text-light/80 dark:text-text-dark/80">
<li><a class="hover:underline hover:text-primary transition-colors" href="#">Trung tâm trợ giúp</a></li>
<li><a class="hover:underline hover:text-primary transition-colors" href="#">Chính sách hủy</a></li>
<li><a class="hover:underline hover:text-primary transition-colors" href="#">Liên hệ</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-2">Điều khoản</h4>
<ul class="space-y-1 text-sm text-text-light/80 dark:text-text-dark/80">
<li><a class="hover:underline hover:text-primary transition-colors" href="#">Điều khoản dịch vụ</a></li>
<li><a class="hover:underline hover:text-primary transition-colors" href="#">Chính sách bảo mật</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-2">Theo dõi chúng tôi</h4>
<div class="flex space-x-4">
<a class="text-text-light/80 dark:text-text-dark/80 hover:text-primary transition-colors" href="#">Facebook</a>
<a class="text-text-light/80 dark:text-text-dark/80 hover:text-primary transition-colors" href="#">Instagram</a>
</div>
</div>
</div>
<div class="border-t border-border-light dark:border-border-dark mt-8 pt-4 text-center text-sm text-text-light/60 dark:text-text-dark/60">
<p>© 2024 TravelCo. All rights reserved.</p>
</div>
</div>
</footer>
</div>
</body></html>