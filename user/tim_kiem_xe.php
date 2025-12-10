<!DOCTYPE html>
<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Đặt xe đưa đón</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&amp;display=swap" rel="stylesheet"/>
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
              "content-light": "#ffffff",
              "content-dark": "#1a2a33",
              "text-primary-light": "#0d171c",
              "text-primary-dark": "#f0f5f8",
              "text-secondary-light": "#49819c",
              "text-secondary-dark": "#a3b5c0",
              "border-light": "#e7eff4",
              "border-dark": "#2c3e49",
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
        }
    </style>
</head>
<body class="font-display">
<div class="relative flex h-auto min-h-screen w-full flex-col bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<div class="px-4 md:px-10 lg:px-20 xl:px-40 flex flex-1 justify-center py-5">
<div class="layout-content-container flex flex-col w-full max-w-7xl flex-1">
<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-border-light dark:border-border-dark px-4 sm:px-10 py-3">
<div class="flex items-center gap-4 text-text-primary-light dark:text-text-primary-dark">
<div class="size-6 text-primary">
<svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<path d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078L44 7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094L4 42.4379Z" fill="currentColor"></path>
</svg>
</div>
<h2 class="text-text-primary-light dark:text-text-primary-dark text-lg font-bold leading-tight tracking-[-0.015em]">TravelServices</h2>
</div>
<div class="hidden lg:flex flex-1 justify-center gap-8">
<div class="flex items-center gap-9">
<a class="text-text-primary-light dark:text-text-primary-dark text-sm font-medium leading-normal" href="#">Chuyến bay</a>
<a class="text-text-primary-light dark:text-text-primary-dark text-sm font-medium leading-normal" href="#">Khách sạn</a>
<a class="text-primary text-sm font-bold leading-normal" href="#">Thuê xe</a>
<a class="text-text-primary-light dark:text-text-primary-dark text-sm font-medium leading-normal" href="#">Tours</a>
</div>
</div>
<div class="hidden sm:flex items-center gap-2">
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em]">
<span class="truncate">Đăng nhập</span>
</button>
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/20 text-text-primary-light dark:text-text-primary-dark dark:bg-primary/30 text-sm font-bold leading-normal tracking-[0.015em]">
<span class="truncate">Đăng ký</span>
</button>
</div>
<div class="flex items-center lg:hidden">
<button class="p-2 rounded-lg hover:bg-primary/20">
<span class="material-symbols-outlined text-text-primary-light dark:text-text-primary-dark">menu</span>
</button>
</div>
</header>
<main class="flex flex-col gap-8 py-8 px-4">
<div class="flex flex-wrap justify-between gap-3">
<h1 class="text-text-primary-light dark:text-text-primary-dark text-4xl font-black leading-tight tracking-[-0.033em] min-w-72">Đặt xe đưa đón sân bay &amp; Thuê xe tự lái</h1>
</div>
<div class="border border-border-light dark:border-border-dark rounded-xl bg-content-light dark:bg-content-dark shadow-sm">
<div class="pb-3">
<div class="flex border-b border-border-light dark:border-border-dark px-4 sm:px-6 gap-8">
<a class="flex items-center gap-2 border-b-[3px] border-b-primary text-text-primary-light dark:text-text-primary-dark pb-[13px] pt-4" href="#">
<span class="material-symbols-outlined text-primary">airport_shuttle</span>
<p class="text-sm font-bold leading-normal tracking-[0.015em]">Xe đưa đón</p>
</a>
<a class="flex items-center gap-2 border-b-[3px] border-b-transparent text-text-secondary-light dark:text-text-secondary-dark pb-[13px] pt-4" href="#">
<span class="material-symbols-outlined">directions_car</span>
<p class="text-sm font-bold leading-normal tracking-[0.015em]">Thuê xe tự lái</p>
</a>
</div>
</div>
<p class="text-text-primary-light dark:text-text-primary-dark text-base font-normal leading-normal pb-3 pt-1 px-4 sm:px-6">Nhập điểm đón/trả, ngày giờ để tìm chuyến xe hoàn hảo cho hành trình của bạn.</p>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 px-4 sm:px-6 py-3 items-end">
<label class="flex flex-col min-w-40 flex-1">
<p class="text-text-primary-light dark:text-text-primary-dark text-base font-medium leading-normal pb-2">Điểm đón</p>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary-light dark:text-text-secondary-dark">location_on</span>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-primary-light dark:text-text-primary-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark focus:border-primary h-14 placeholder:text-text-secondary-light dark:placeholder:text-text-secondary-dark p-[15px] pl-10 text-base font-normal leading-normal" placeholder="Sân bay, thành phố" value="Sân bay Tân Sơn Nhất (SGN)"/>
</div>
</label>
<label class="flex flex-col min-w-40 flex-1">
<p class="text-text-primary-light dark:text-text-primary-dark text-base font-medium leading-normal pb-2">Điểm trả</p>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary-light dark:text-text-secondary-dark">flag</span>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-primary-light dark:text-text-primary-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark focus:border-primary h-14 placeholder:text-text-secondary-light dark:placeholder:text-text-secondary-dark p-[15px] pl-10 text-base font-normal leading-normal" placeholder="Khách sạn, địa chỉ" value="Quận 1, TP. Hồ Chí Minh"/>
</div>
</label>
<label class="flex flex-col min-w-40 flex-1">
<p class="text-text-primary-light dark:text-text-primary-dark text-base font-medium leading-normal pb-2">Ngày đón</p>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary-light dark:text-text-secondary-dark">calendar_month</span>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-primary-light dark:text-text-primary-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark focus:border-primary h-14 placeholder:text-text-secondary-light dark:placeholder:text-text-secondary-dark p-[15px] pl-10 text-base font-normal leading-normal" type="date" value="2023-10-28"/>
</div>
</label>
<label class="flex flex-col min-w-40 flex-1">
<p class="text-text-primary-light dark:text-text-primary-dark text-base font-medium leading-normal pb-2">Giờ đón</p>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary-light dark:text-text-secondary-dark">schedule</span>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-primary-light dark:text-text-primary-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark focus:border-primary h-14 placeholder:text-text-secondary-light dark:placeholder:text-text-secondary-dark p-[15px] pl-10 text-base font-normal leading-normal" type="time" value="14:30"/>
</div>
</label>
<button class="flex min-w-[84px] w-full xl:max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-14 px-4 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] gap-2">
<span class="material-symbols-outlined">search</span>
<span class="truncate">Tìm kiếm xe</span>
</button>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<div class="lg:col-span-2 flex flex-col gap-6">
<div class="flex justify-between items-center">
<h2 class="text-xl font-bold text-text-primary-light dark:text-text-primary-dark">Kết quả: 3 xe phù hợp</h2>
<div class="flex items-center gap-2">
<label class="text-sm font-medium text-text-secondary-light dark:text-text-secondary-dark whitespace-nowrap" for="sort">Sắp xếp:</label>
<select class="form-select rounded-lg border-border-light dark:border-border-dark bg-content-light dark:bg-content-dark text-text-primary-light dark:text-text-primary-dark text-sm focus:ring-primary/50 focus:border-primary" id="sort">
<option>Giá thấp nhất</option>
<option>Giá cao nhất</option>
<option>Đánh giá cao nhất</option>
</select>
</div>
</div>
<div class="flex flex-col gap-4">
<div class="flex flex-col p-4 rounded-xl border border-primary bg-primary/5 dark:bg-primary/10 shadow-lg cursor-pointer transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
<div class="flex flex-col md:flex-row gap-4">
<div class="md:w-1/3">
<img alt="White Toyota Vios sedan car" class="w-full h-40 object-cover rounded-lg" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAMYCWA9n5Vf8ke1GXRkSqB-WCu8x3187RixBZIWFLvfR7XzISlZNSe8GD-7WEOF1ohCXTJOX5xWkbTtDfLi8TG5yC2oD6mnPuM4ExtuRgkzSN7qPcr5nAqWoVQih5l2Uu1VGjUy8OAEyNCVboml-QsE0TlBiwpAnY1zhTUE_CH-7HyU-9BRptjS80btuYwBaVqBpKJKnh5apbZwniErmwhYn8IulDv_GiSZERR6j45cfEx1gRRG-rSxruNpd7Bb7q_L_mEC5kGgBwV"/>
</div>
<div class="md:w-2/3 flex flex-col justify-between">
<div>
<div class="flex justify-between items-start">
<div>
<h3 class="text-lg font-bold text-text-primary-light dark:text-text-primary-dark">Toyota Vios</h3>
<p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Sedan 4 chỗ</p>
</div>
<div class="flex items-center gap-1 text-amber-400">
<span class="text-sm font-bold text-text-primary-light dark:text-text-primary-dark">4.8</span>
<span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1">star</span>
</div>
</div>
<div class="mt-2 grid grid-cols-2 gap-x-4 gap-y-1 text-sm text-text-primary-light dark:text-text-primary-dark">
<div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-primary">person</span><span>Tối đa 3 khách</span></div>
<div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-primary">work_outline</span><span>2 hành lý</span></div>
<div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-primary">ac_unit</span><span>Điều hòa</span></div>
<div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-primary">wifi</span><span>Wifi miễn phí</span></div>
</div>
</div>
<div class="mt-4 flex justify-between items-center">
<p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Nhà cung cấp: <a class="text-primary font-medium hover:underline" href="#">ABC Corp</a></p>
</div>
</div>
</div>
<div class="mt-4 pt-4 border-t border-border-light dark:border-border-dark flex flex-wrap gap-4 justify-between items-center">
<div class="flex flex-col">
<p class="text-lg font-bold text-primary">350.000 VNĐ</p>
<p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">Giá cuối cùng, đã bao gồm thuế và phí</p>
</div>
<button class="flex w-full sm:w-auto min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold transition-colors duration-300 hover:bg-primary/80">Chọn xe</button>
</div>
<div class="mt-3 text-xs text-text-secondary-light dark:text-text-secondary-dark">
<p><b>Điều khoản:</b> Miễn phí hủy trước 24 giờ. Phụ phí phát sinh nếu đi quá quãng đường hoặc thời gian.</p>
</div>
</div>
<div class="flex flex-col p-4 rounded-xl border border-border-light dark:border-border-dark bg-content-light dark:bg-content-dark shadow-sm hover:shadow-2xl hover:border-primary transition-all duration-300 cursor-pointer hover:-translate-y-1">
<div class="flex flex-col md:flex-row gap-4">
<div class="md:w-1/3">
<img alt="Black Toyota Fortuner SUV car" class="w-full h-40 object-cover rounded-lg" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCaOn-0OgQ_63Znw_amUDrCj39nghpMgWaUxy4YgadVeLE7j4yXCIB7VQkX4UjTAyI0iZYzJM1BswT9OZbRwda1o7e0hqadQJ4th9_lV2PYweR0A5LnMfk-2UlK54-FqZW2s_2ZIT2D83TAgXSviRC7EhrOMOtNaTl9aphVAxXwg0yXgM2ZPP9rRlbzTMi4lLON-wp1AeMjwS67xSBzfKjA8HvNFMIcyRgZD_D4F_oWkMvRjx6v8Z9wmilRNqcMVm4gh9vcIHsv7DQC"/>
</div>
<div class="md:w-2/3 flex flex-col justify-between">
<div>
<div class="flex justify-between items-start">
<div>
<h3 class="text-lg font-bold text-text-primary-light dark:text-text-primary-dark">Toyota Fortuner</h3>
<p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">SUV 7 chỗ</p>
</div>
<div class="flex items-center gap-1 text-amber-400">
<span class="text-sm font-bold text-text-primary-light dark:text-text-primary-dark">4.5</span>
<span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1">star</span>
</div>
</div>
<div class="mt-2 grid grid-cols-2 gap-x-4 gap-y-1 text-sm text-text-primary-light dark:text-text-primary-dark">
<div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-primary">person</span><span>Tối đa 6 khách</span></div>
<div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-primary">work_outline</span><span>4 hành lý</span></div>
<div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-primary">ac_unit</span><span>Điều hòa</span></div>
<div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-primary">child_care</span><span>Ghế trẻ em</span></div>
</div>
</div>
<div class="mt-4 flex justify-between items-center">
<p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Nhà cung cấp: <a class="text-primary font-medium hover:underline" href="#">GoCar</a></p>
</div>
</div>
</div>
<div class="mt-4 pt-4 border-t border-border-light dark:border-border-dark flex flex-wrap gap-4 justify-between items-center">
<div class="flex flex-col">
<p class="text-lg font-bold text-primary">500.000 VNĐ</p>
<p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">Giá cuối cùng, đã bao gồm thuế và phí</p>
</div>
<button class="flex w-full sm:w-auto min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold transition-colors duration-300 hover:bg-primary/80">Chọn xe</button>
</div>
<div class="mt-3 text-xs text-text-secondary-light dark:text-text-secondary-dark">
<p><b>Thông tin tài xế:</b> Nguyễn Văn B, 5 năm kinh nghiệm, thông thạo tiếng Anh.</p>
</div>
</div>
<div class="flex flex-col p-4 rounded-xl border border-border-light dark:border-border-dark bg-content-light dark:bg-content-dark shadow-sm hover:shadow-2xl hover:border-primary transition-all duration-300 cursor-pointer hover:-translate-y-1">
<div class="flex flex-col md:flex-row gap-4">
<div class="md:w-1/3">
<img alt="White Ford Transit van" class="w-full h-40 object-cover rounded-lg" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB5F89XwW8RQ7m-vMKXdqmz2OQg5ft_VTIDmc9aIvAmyxrW-yPDDHdPLG7LzxEElN6zuzajtF0dNnROC4GWQLbgVrt_oKmn7dQgx7afpCvSMQuAOyW1s30LFbl7knYe142Sx4PfYY3eRphkjHTI2I96EYykr32p7IpVSq6MUZN36pQU5BIqQGLrWYgsyzu56i09c_L2TqGHMjv59tUa-32FV5jDK66rkomtZuHC8v4EbsXAPJiULyLJmPEvBQD8I6nd1pG-RmRCUXkp"/>
</div>
<div class="md:w-2/3 flex flex-col justify-between">
<div>
<div class="flex justify-between items-start">
<div>
<h3 class="text-lg font-bold text-text-primary-light dark:text-text-primary-dark">Ford Transit</h3>
<p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Minivan 16 chỗ</p>
</div>
<div class="flex items-center gap-1 text-amber-400">
<span class="text-sm font-bold text-text-primary-light dark:text-text-primary-dark">4.9</span>
<span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1">star</span>
</div>
</div>
<div class="mt-2 grid grid-cols-2 gap-x-4 gap-y-1 text-sm text-text-primary-light dark:text-text-primary-dark">
<div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-primary">person</span><span>Tối đa 15 khách</span></div>
<div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-primary">work_outline</span><span>10 hành lý</span></div>
<div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-primary">ac_unit</span><span>Điều hòa</span></div>
<div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-primary">wifi</span><span>Wifi miễn phí</span></div>
<div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-base text-primary">local_drink</span><span>Nước uống</span></div>
</div>
</div>
<div class="mt-4 flex justify-between items-center">
<p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Nhà cung cấp: <a class="text-primary font-medium hover:underline" href="#">Luxury Trans</a></p>
</div>
</div>
</div>
<div class="mt-4 pt-4 border-t border-border-light dark:border-border-dark flex flex-wrap gap-4 justify-between items-center">
<div class="flex flex-col">
<p class="text-lg font-bold text-primary">850.000 VNĐ</p>
<p class="text-xs text-text-secondary-light dark:text-text-secondary-dark">Giá cuối cùng, đã bao gồm thuế và phí</p>
</div>
<button class="flex w-full sm:w-auto min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold transition-colors duration-300 hover:bg-primary/80">Chọn xe</button>
</div>
<div class="mt-3 text-xs text-text-secondary-light dark:text-text-secondary-dark">
<p><b>Điều khoản:</b> Miễn phí hủy trước 48 giờ. Đã bao gồm phí cầu đường, sân bay.</p>
</div>
</div>
</div>
</div>
<div class="lg:col-span-1 flex flex-col gap-6 sticky top-8 self-start">
<div class="flex flex-col gap-6 p-6 rounded-xl border border-border-light dark:border-border-dark bg-content-light dark:bg-content-dark">
<h3 class="text-lg font-bold text-text-primary-light dark:text-text-primary-dark">Bộ lọc</h3>
<div class="flex flex-col gap-3">
<h4 class="font-semibold text-text-primary-light dark:text-text-primary-dark">Khoảng giá</h4>
<input class="w-full h-2 bg-primary/20 rounded-lg appearance-none cursor-pointer accent-primary" max="5000000" min="0" type="range" value="1500000"/>
<div class="flex justify-between text-sm text-text-secondary-light dark:text-text-secondary-dark">
<span>0đ</span>
<span>5.000.000đ</span>
</div>
</div>
<div class="flex flex-col gap-3">
<h4 class="font-semibold text-text-primary-light dark:text-text-primary-dark">Loại xe</h4>
<label class="flex items-center gap-2">
<input checked="" class="form-checkbox rounded border-border-light dark:border-border-dark text-primary focus:ring-primary/50" type="checkbox"/>
<span class="text-sm text-text-primary-light dark:text-text-primary-dark">4 chỗ (Sedan)</span>
</label>
<label class="flex items-center gap-2">
<input class="form-checkbox rounded border-border-light dark:border-border-dark text-primary focus:ring-primary/50" type="checkbox"/>
<span class="text-sm text-text-primary-light dark:text-text-primary-dark">7 chỗ (SUV)</span>
</label>
<label class="flex items-center gap-2">
<input checked="" class="form-checkbox rounded border-border-light dark:border-border-dark text-primary focus:ring-primary/50" type="checkbox"/>
<span class="text-sm text-text-primary-light dark:text-text-primary-dark">16 chỗ (Minivan)</span>
</label>
</div>
<div class="flex flex-col gap-3">
<h4 class="font-semibold text-text-primary-light dark:text-text-primary-dark">Đánh giá</h4>
<div class="flex gap-1 text-amber-400">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1">star</span>
<span class="material-symbols-outlined">star</span>
<span class="text-sm text-text-secondary-light dark:text-text-secondary-dark ml-1">4 sao trở lên</span>
</div>
</div>
</div>
<div class="rounded-xl border border-border-light dark:border-border-dark bg-content-light dark:bg-content-dark overflow-hidden">
<div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
<img alt="A map showing the route from Tan Son Nhat airport to District 1" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCOLuy0u3b3zu1TU18ojqU0UsKGCO9wruFXsHtPXBN4UaRlqb0NZ81E40cl1WbhL7xoDZrTOt-XG4A9RvxX9BT5jyKGic0RQ6GTyrR8oUyNSVjJWv2dp0nAtCGvIgkk8K9m9tyK-e3WKjn5vT-O1K19XHAJtKUJWUjCVIVLtwQQztLIHmIRlo4yUrx-dSm8ylex7PjvrwypFfZmndj8RoGDIWi5P09oYufbwRdpewFtpUYqgHxp2fvkE3KajDOTE68qpgRORM5ERdrd"/>
</div>
<div class="p-4">
<h3 class="font-bold text-text-primary-light dark:text-text-primary-dark">Hành trình của bạn</h3>
<div class="mt-2 flex items-start gap-3">
<span class="material-symbols-outlined text-primary mt-1">location_on</span>
<div class="flex flex-col">
<p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Điểm đón</p>
<p class="font-medium text-text-primary-light dark:text-text-primary-dark">Sân bay Tân Sơn Nhất (SGN)</p>
</div>
</div>
<div class="mt-2 flex items-start gap-3">
<span class="material-symbols-outlined text-primary mt-1">flag</span>
<div class="flex flex-col">
<p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Điểm trả</p>
<p class="font-medium text-text-primary-light dark:text-text-primary-dark">Quận 1, TP. Hồ Chí Minh</p>
</div>
</div>
</div>
</div>
</div>
</div>
</main>
</div>
</div>
</div>
</div>
</body></html>