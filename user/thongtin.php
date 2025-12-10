<!DOCTYPE html>
<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Quản lý đặt chỗ của tôi - TravelViet</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#0da6f2",
            "background-light": "#f5f7f8",
            "background-dark": "#101c22",
            "card-light": "#ffffff",
            "card-dark": "#1a2a34",
            "text-light-primary": "#0d171c",
            "text-dark-primary": "#f5f7f8",
            "text-light-secondary": "#49819c",
            "text-dark-secondary": "#a0b8c4",
            "border-light": "#e7eff4",
            "border-dark": "#2c3e4a",
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
      font-variation-settings:
      'FILL' 0,
      'wght' 400,
      'GRAD' 0,
      'opsz' 24
    }
  </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<header class="w-full bg-card-light dark:bg-card-dark sticky top-0 z-10 shadow-sm">
<div class="container mx-auto px-4">
<div class="flex items-center justify-between whitespace-nowrap border-b border-solid border-border-light dark:border-border-dark py-3">
<div class="flex items-center gap-4 text-text-light-primary dark:text-text-dark-primary">
<div class="text-primary">
<svg class="size-6" fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<path d="M13.8261 17.4264C16.7203 18.1174 20.2244 18.5217 24 18.5217C27.7756 18.5217 31.2797 18.1174 34.1739 17.4264C36.9144 16.7722 39.9967 15.2331 41.3563 14.1648L24.8486 40.6391C24.4571 41.267 23.5429 41.267 23.1514 40.6391L6.64374 14.1648C8.00331 15.2331 11.0856 16.7722 13.8261 17.4264Z"></path>
<path clip-rule="evenodd" d="M39.998 12.236C39.9944 12.2537 39.9875 12.2845 39.9748 12.3294C39.9436 12.4399 39.8949 12.5741 39.8346 12.7175C39.8168 12.7597 39.7989 12.8007 39.7813 12.8398C38.5103 13.7113 35.9788 14.9393 33.7095 15.4811C30.9875 16.131 27.6413 16.5217 24 16.5217C20.3587 16.5217 17.0125 16.131 14.2905 15.4811C12.0012 14.9346 9.44505 13.6897 8.18538 12.8168C8.17384 12.7925 8.16216 12.767 8.15052 12.7408C8.09919 12.6249 8.05721 12.5114 8.02977 12.411C8.00356 12.3152 8.00039 12.2667 8.00004 12.2612C8.00004 12.261 8 12.2607 8.00004 12.2612C8.00004 12.2359 8.0104 11.9233 8.68485 11.3686C9.34546 10.8254 10.4222 10.2469 11.9291 9.72276C14.9242 8.68098 19.1919 8 24 8C28.8081 8 33.0758 8.68098 36.0709 9.72276C37.5778 10.2469 38.6545 10.8254 39.3151 11.3686C39.9006 11.8501 39.9857 12.1489 39.998 12.236ZM4.95178 15.2312L21.4543 41.6973C22.6288 43.5809 25.3712 43.5809 26.5457 41.6973L43.0534 15.223C43.0709 15.1948 43.0878 15.1662 43.104 15.1371L41.3563 14.1648C43.104 15.1371 43.1038 15.1374 43.104 15.1371L43.1051 15.135L43.1065 15.1325L43.1101 15.1261L43.1199 15.1082C43.1276 15.094 43.1377 15.0754 43.1497 15.0527C43.1738 15.0075 43.2062 14.9455 43.244 14.8701C43.319 14.7208 43.4196 14.511 43.5217 14.2683C43.6901 13.8679 44 13.0689 44 12.2609C44 10.5573 43.003 9.22254 41.8558 8.2791C40.6947 7.32427 39.1354 6.55361 37.385 5.94477C33.8654 4.72057 29.133 4 24 4C18.867 4 14.1346 4.72057 10.615 5.94478C8.86463 6.55361 7.30529 7.32428 6.14419 8.27911C4.99695 9.22255 3.99999 10.5573 3.99999 12.2609C3.99999 13.1275 4.29264 13.9078 4.49321 14.3607C4.60375 14.6102 4.71348 14.8196 4.79687 14.9689C4.83898 15.0444 4.87547 15.1065 4.9035 15.1529C4.91754 15.1762 4.92954 15.1957 4.93916 15.2111L4.94662 15.223L4.95178 15.2312ZM35.9868 18.996L24 38.22L12.0131 18.996C12.4661 19.1391 12.9179 19.2658 13.3617 19.3718C16.4281 20.1039 20.0901 20.5217 24 20.5217C27.9099 20.5217 31.5719 20.1039 34.6383 19.3718C35.082 19.2658 35.5339 19.1391 35.9868 18.996Z" fill-rule="evenodd"></path>
</svg>
</div>
<h2 class="text-text-light-primary dark:text-text-dark-primary text-lg font-bold leading-tight tracking-[-0.015em]">TravelViet</h2>
</div>
<div class="flex flex-1 justify-center">
<div class="flex items-center gap-9">
<a class="text-text-light-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary text-sm font-medium leading-normal" href="#">Trang chủ</a>
<a class="text-text-light-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary text-sm font-medium leading-normal" href="#">Ưu đãi</a>
<a class="text-text-light-primary dark:text-text-dark-primary text-sm font-bold leading-normal" href="#">Quản lý đặt chỗ</a>
<a class="text-text-light-secondary dark:text-text-dark-secondary hover:text-primary dark:hover:text-primary text-sm font-medium leading-normal" href="#">Liên hệ</a>
</div>
</div>
<div class="flex gap-4 items-center">
<button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-transparent text-text-light-primary dark:text-text-dark-primary gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
<span class="material-symbols-outlined text-2xl text-text-light-secondary dark:text-text-dark-secondary">notifications</span>
</button>
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="User avatar" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD3xB9vRPWL98sXoZK0o7k6RF_yNvfbTNuq8M1gP1bXL31rSbN0ejCUWCE0tlhpa7OssYniFHspJyJnoaJC6jsrgbVGj0m_ka7OByv82CyXxELg58oSk1TOSSOwluc5unt8uoAHQ4uPn-FPLO5JDUiG28mVJCCjkEar0Tdc__puNNU07pswABsN99v6qSQ7umMw7hGD818SgcZy0H-D36kP3y8P6v9MMgvFVgFLjeRykoL-9g8by7OmJUtHMTM0fnMJiDRFzb7L48Lo");'></div>
</div>
</div>
</div>
</header>
<main class="flex-1">
<div class="container mx-auto px-4 py-8">
<div class="layout-content-container flex flex-col flex-1 gap-6">
<div class="flex flex-wrap justify-between gap-4 items-center">
<div class="flex min-w-72 flex-col gap-2">
<p class="text-text-light-primary dark:text-text-dark-primary text-4xl font-black leading-tight tracking-[-0.033em]">Quản lý đặt chỗ của tôi</p>
<p class="text-text-light-secondary dark:text-text-dark-secondary text-base font-normal leading-normal">Xem lại, chỉnh sửa hoặc hủy các đặt chỗ vé máy bay, khách sạn và xe của bạn.</p>
</div>
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-blue-600 dark:hover:bg-blue-500 transition-colors">
<span class="truncate">Đặt chuyến đi mới</span>
</button>
</div>
<div class="pb-3">
<div class="flex border-b border-border-light dark:border-border-dark gap-8">
<a class="flex flex-col items-center justify-center border-b-[3px] border-b-primary text-text-light-primary dark:text-text-dark-primary pb-[13px] pt-4" href="#">
<p class="text-sm font-bold leading-normal tracking-[0.015em]">Tất cả</p>
</a>
<a class="flex flex-col items-center justify-center border-b-[3px] border-b-transparent text-text-light-secondary dark:text-text-dark-secondary pb-[13px] pt-4 hover:text-text-light-primary dark:hover:text-text-dark-primary" href="#">
<p class="text-sm font-bold leading-normal tracking-[0.015em]">Chuyến bay</p>
</a>
<a class="flex flex-col items-center justify-center border-b-[3px] border-b-transparent text-text-light-secondary dark:text-text-dark-secondary pb-[13px] pt-4 hover:text-text-light-primary dark:hover:text-text-dark-primary" href="#">
<p class="text-sm font-bold leading-normal tracking-[0.015em]">Khách sạn</p>
</a>
<a class="flex flex-col items-center justify-center border-b-[3px] border-b-transparent text-text-light-secondary dark:text-text-dark-secondary pb-[13px] pt-4 hover:text-text-light-primary dark:hover:text-text-dark-primary" href="#">
<p class="text-sm font-bold leading-normal tracking-[0.015em]">Xe</p>
</a>
</div>
</div>
<div class="py-3">
<label class="flex flex-col min-w-40 h-12 w-full">
<div class="flex w-full flex-1 items-stretch rounded-xl h-full">
<div class="text-text-light-secondary dark:text-text-dark-secondary flex border-none bg-card-light dark:bg-card-dark items-center justify-center pl-4 rounded-l-xl border-r-0">
<span class="material-symbols-outlined text-2xl">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-text-light-primary dark:text-text-dark-primary focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark h-full placeholder:text-text-light-secondary dark:placeholder:text-text-dark-secondary px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal" placeholder="Tìm kiếm theo mã đặt chỗ hoặc tên hành khách" value=""/>
</div>
</label>
</div>
<div class="flex flex-col gap-6">
<div class="overflow-hidden rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark">
<div class="p-6">
<div class="flex flex-wrap items-start justify-between gap-6">
<div class="flex items-center gap-4">
<div class="h-16 w-16 flex-shrink-0">
<div class="h-full w-full rounded-lg bg-cover bg-center bg-no-repeat" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD2he1vd_c4s7GGGgfQCECwUhcKfpUp0FNFRA9G3iMJpLllO24T-pLh77P0aC679p4Fe7dY_EmFo6q8gI4ANmlS13PMh7wJDfMcANwZWn2LWy2IoW8BnJp6y8Nf--oubxrqBs9inBzHuoyxVwBgj39rw4whaLmSnbJVCDqYppSbLz6--Xcw6MHVsILzcFMuYlxqsGWVaq-9666tYHoBrAojRh1_9pfD2JQtl7TBU-gB4jPWPiyFmgE5-nKRNSVmU7VPHBJ04MOKkeWW");'></div>
</div>
<div class="flex flex-col gap-1">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-base text-text-light-secondary dark:text-text-dark-secondary">flight</span>
<p class="text-sm font-medium text-text-light-secondary dark:text-text-dark-secondary">Chuyến bay</p>
</div>
<h3 class="text-lg font-bold text-text-light-primary dark:text-text-dark-primary">Chuyến bay đến Singapore</h3>
<p class="text-sm text-text-light-secondary dark:text-text-dark-secondary">Mã đặt chỗ: <span class="font-medium text-text-light-primary dark:text-text-dark-primary">VJ5892</span></p>
</div>
</div>
<div class="flex flex-wrap items-center gap-6">
<div class="flex flex-col items-start gap-1 sm:items-end">
<span class="inline-flex rounded-full bg-green-100 dark:bg-green-900 px-3 py-1 text-xs font-semibold leading-5 text-green-700 dark:text-green-300">Đã xác nhận &amp; Thanh toán</span>
<div class="text-sm text-text-light-secondary dark:text-text-dark-secondary">Tổng cộng: <span class="text-base font-bold text-text-light-primary dark:text-text-dark-primary">12.500.000đ</span></div>
</div>
<div class="flex items-center gap-2">
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-9 px-4 bg-background-light dark:bg-background-dark text-text-light-primary dark:text-text-dark-primary text-sm font-medium leading-normal border border-border-light dark:border-border-dark transition-colors hover:bg-border-light dark:hover:bg-border-dark">
<span class="material-symbols-outlined text-xl">edit</span><span class="truncate">Chỉnh sửa</span>
</button>
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-9 px-4 bg-background-light dark:bg-background-dark text-text-light-primary dark:text-text-dark-primary text-sm font-medium leading-normal border border-border-light dark:border-border-dark transition-colors hover:bg-red-100 dark:hover:bg-red-900/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-200 dark:hover:border-red-900/30">
<span class="material-symbols-outlined text-xl">cancel</span><span class="truncate">Hủy</span>
</button>
</div>
</div>
</div>
<div class="mt-6 border-t border-border-light dark:border-border-dark pt-6">
<div class="flex flex-col gap-4">
<div class="flex items-center gap-6">
<div class="flex flex-col items-center">
<div class="text-lg font-bold text-text-light-primary dark:text-text-dark-primary">SGN</div>
<div class="text-sm text-text-light-secondary dark:text-text-dark-secondary">TP. HCM</div>
</div>
<div class="flex flex-1 flex-col items-center">
<div class="text-sm text-text-light-secondary dark:text-text-dark-secondary">Vietjet Air</div>
<div class="my-1 h-px w-full bg-border-light dark:bg-border-dark"></div>
<div class="text-xs text-text-light-secondary dark:text-text-dark-secondary">25/12/2024</div>
</div>
<div class="flex flex-col items-center">
<div class="text-lg font-bold text-text-light-primary dark:text-text-dark-primary">SIN</div>
<div class="text-sm text-text-light-secondary dark:text-text-dark-secondary">Singapore</div>
</div>
</div>
<div class="flex items-center gap-6">
<div class="flex flex-col items-center">
<div class="text-lg font-bold text-text-light-primary dark:text-text-dark-primary">SIN</div>
<div class="text-sm text-text-light-secondary dark:text-text-dark-secondary">Singapore</div>
</div>
<div class="flex flex-1 flex-col items-center">
<div class="text-sm text-text-light-secondary dark:text-text-dark-secondary">Vietjet Air</div>
<div class="my-1 h-px w-full bg-border-light dark:bg-border-dark"></div>
<div class="text-xs text-text-light-secondary dark:text-text-dark-secondary">01/01/2025</div>
</div>
<div class="flex flex-col items-center">
<div class="text-lg font-bold text-text-light-primary dark:text-text-dark-primary">SGN</div>
<div class="text-sm text-text-light-secondary dark:text-text-dark-secondary">TP. HCM</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="overflow-hidden rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark">
<div class="p-6">
<div class="flex flex-wrap items-start justify-between gap-6">
<div class="flex items-center gap-4">
<div class="h-16 w-16 flex-shrink-0">
<div class="h-full w-full rounded-lg bg-cover bg-center bg-no-repeat" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAxvwAVT8yOn_29naZi-dP7q3ul_VSl560K-hEfY6smILGLQTTcir2JJL4OyZGZ97Pht2pDyp33U6gQJBy5v9yVUOWqjsIwGS7-eC4PLrXFC7eMWVWNSQJ6WQgenkN3lSrFP5IWk0E0QEWCJDY0F-L30y8Ti8seARucBY2uwiccdFlFgQMhn3YLFW-cPaw-8mKcxbj6XfBG9QnJ5bV2g9lLx6jA_IY0o_xP_ti3lZb-XoiyGVEKYYhxjzEUoAJl7Je-3YFyQDQ8NcXW");'></div>
</div>
<div class="flex flex-col gap-1">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-base text-text-light-secondary dark:text-text-dark-secondary">hotel</span>
<p class="text-sm font-medium text-text-light-secondary dark:text-text-dark-secondary">Khách sạn</p>
</div>
<h3 class="text-lg font-bold text-text-light-primary dark:text-text-dark-primary">Khách sạn Marina Bay Sands</h3>
<p class="text-sm text-text-light-secondary dark:text-text-dark-secondary">Mã đặt chỗ: <span class="font-medium text-text-light-primary dark:text-text-dark-primary">HS9214</span></p>
</div>
</div>
<div class="flex flex-wrap items-center gap-6">
<div class="flex flex-col items-start gap-1 sm:items-end">
<span class="inline-flex rounded-full bg-orange-100 dark:bg-orange-900 px-3 py-1 text-xs font-semibold leading-5 text-orange-700 dark:text-orange-300">Chờ thanh toán</span>
<div class="text-sm text-text-light-secondary dark:text-text-dark-secondary">Hạn chót: 24/11/2024</div>
</div>
<div class="flex items-center gap-2">
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-9 px-4 bg-background-light dark:bg-background-dark text-text-light-primary dark:text-text-dark-primary text-sm font-medium leading-normal border border-border-light dark:border-border-dark transition-colors hover:bg-red-100 dark:hover:bg-red-900/20 hover:text-red-700 dark:hover:text-red-300 hover:border-red-200 dark:hover:border-red-900/30">
<span class="material-symbols-outlined text-xl">cancel</span><span class="truncate">Hủy</span>
</button>
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-9 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] transition-colors hover:bg-blue-600 dark:hover:bg-blue-500">
<span class="material-symbols-outlined text-xl">payment</span><span class="truncate">Thanh toán</span>
</button>
</div>
</div>
</div>
<div class="mt-6 border-t border-border-light dark:border-border-dark pt-6">
<div class="flex items-center gap-4">
<div class="flex flex-col">
<div class="text-sm font-medium text-text-light-secondary dark:text-text-dark-secondary">Nhận phòng</div>
<div class="text-lg font-bold text-text-light-primary dark:text-text-dark-primary">25/12/2024</div>
</div>
<span class="material-symbols-outlined text-2xl text-text-light-secondary dark:text-text-dark-secondary">arrow_forward</span>
<div class="flex flex-col">
<div class="text-sm font-medium text-text-light-secondary dark:text-text-dark-secondary">Trả phòng</div>
<div class="text-lg font-bold text-text-light-primary dark:text-text-dark-primary">28/12/2024</div>
</div>
<div class="ml-auto flex items-center gap-2 text-sm text-text-light-secondary dark:text-text-dark-secondary">
<span class="material-symbols-outlined text-xl">schedule</span>
<span>3 đêm</span>
</div>
</div>
</div>
</div>
</div>
<div class="overflow-hidden rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark">
<div class="p-6">
<div class="flex flex-wrap items-start justify-between gap-6">
<div class="flex items-center gap-4">
<div class="h-16 w-16 flex-shrink-0">
<div class="h-full w-full rounded-lg bg-cover bg-center bg-no-repeat" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDmsYzOvZ3pcNYiFWt5L7uw82tM1Id5FkOYGb5UQ5jBXFjQPy6-ADeKJTD08akoq8TEquuL9umu_7VFlrwfbCRTxyEDGwN2c9IbRE8xlFh4zxJfIYscJqnEXdjDPnGCbx74fS9KkrxItUDkZ-QxZlhwpcWCzOeGjPHsIxlcDrsA3Alu-pXCWX7zqvJKC0jp2FxFQTE1hjKU3IiAYUAn2dIi4IDTrFqUZACtDZcTVfN3xpA_c8Dp-9__ay_sKFM8XaJ3zz6Iz2oL-dV9");'></div>
</div>
<div class="flex flex-col gap-1">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-base text-text-light-secondary dark:text-text-dark-secondary">flight</span>
<p class="text-sm font-medium text-text-light-secondary dark:text-text-dark-secondary">Chuyến bay</p>
</div>
<h3 class="text-lg font-bold text-text-light-primary dark:text-text-dark-primary">Chuyến bay đến Đà Nẵng</h3>
<p class="text-sm text-text-light-secondary dark:text-text-dark-secondary">Mã đặt chỗ: <span class="font-medium text-text-light-primary dark:text-text-dark-primary">VN8841</span></p>
</div>
</div>
<div class="flex flex-wrap items-center gap-6">
<div class="flex flex-col items-start gap-1 sm:items-end">
<span class="inline-flex rounded-full bg-red-100 dark:bg-red-900 px-3 py-1 text-xs font-semibold leading-5 text-red-700 dark:text-red-300">Đã hủy</span>
<div class="text-sm text-text-light-secondary dark:text-text-dark-secondary">Đã hủy ngày 10/11/2024</div>
</div>
<div class="flex items-center gap-2">
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-9 px-4 bg-background-light dark:bg-background-dark text-text-light-primary dark:text-text-dark-primary text-sm font-medium leading-normal border border-border-light dark:border-border-dark transition-colors hover:bg-border-light dark:hover:bg-border-dark">
<span class="material-symbols-outlined text-xl">info</span><span class="truncate">Xem chi tiết</span>
</button>
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-9 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] transition-colors hover:bg-blue-600 dark:hover:bg-blue-500">
<span class="material-symbols-outlined text-xl">refresh</span><span class="truncate">Đặt lại</span>
</button>
</div>
</div>
</div>
<div class="mt-6 border-t border-border-light dark:border-border-dark pt-6">
<div class="flex items-center gap-6 opacity-60">
<div class="flex flex-col items-center">
<div class="text-lg font-bold text-text-light-primary dark:text-text-dark-primary">SGN</div>
<div class="text-sm text-text-light-secondary dark:text-text-dark-secondary">TP. HCM</div>
</div>
<div class="flex flex-1 flex-col items-center">
<div class="text-sm text-text-light-secondary dark:text-text-dark-secondary">Vietnam Airlines</div>
<div class="my-1 h-px w-full bg-border-light dark:bg-border-dark"></div>
<div class="text-xs text-text-light-secondary dark:text-text-dark-secondary">15/11/2024</div>
</div>
<div class="flex flex-col items-center">
<div class="text-lg font-bold text-text-light-primary dark:text-text-dark-primary">DAD</div>
<div class="text-sm text-text-light-secondary dark:text-text-dark-secondary">Đà Nẵng</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</main>
</div>
</div>
</body></html>