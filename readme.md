# ✈️ Travel Booking System (Hệ thống Đặt vé Du lịch)
## 📂 Cấu trúc dự án

Dựa trên mã nguồn hiện tại, cấu trúc thư mục được tổ chức như sau:

TRAVEL_BOOKING_SYSTEM/
│
├── config/                 # Cấu hình hệ thống
│   ├── Auth.php            # Class xử lý xác thực (Đăng nhập/Đăng ký)
│   ├── config.php          # Cấu hình chung (Path, Constants)
│   └── database.php        # Cấu hình kết nối MySQL (PDO)
│
├── layout/                 # Layout giao diện chung
│   └── admin_template.php  # Template cho trang Admin
│
├── user/                   # Giao diện phía người dùng (Client)
│   ├── includes/           # Các thành phần con (Header, Footer...)
│   │   ├── header.php
│   ├──booking_confirm.php  # Xử lý đặt homestay
│   ├── homestay.php        # Danh sách Homestay
│   ├── homestay_detail.php # Thông tin chi tiết home
│   ├── chi_tiet_chuyen_bay.php # Thông tin chi tiết chuyến bay
│   ├── chi_tiet_xe.php     # Thông tin chi tiểt xe 
│   ├── booking_xe.php      # Xử lý đặt xe
│   ├── tim_kiem_chuyen_bay.php # Trang đặt vé máy bay
│   ├── tim_kiem_xe.php     # Trang đặt xe
│   ├── trang_chu.php       # Trang chủ User
│   └── thongtin.php        # Thông tin cá nhân & Lịch sử đặt chỗ
│
├── admin(Thư mục Admin)
│   ├── includes/           # Các thành phần con (Header, sidebar)
│   │   ├── header.php      # Thanh header dùng chung cho admin
│   │   ├── sidebar.php     # Thanh bên dùng chung cho admin
│   ├── dashboard.php       # Thống kê toàn bộ đơn
│   ├── qly_booking.php     # Quản lý đơn hàng
│   ├── qly_chuyenbay.php   # Quản lý chuyến bay
│   ├── qly_xe.php          # Quản lý xe
│   ├── qly_kh.php          # Quản lý khách hàng
│   ├── quanly_homestay.php # Quản lý homestay
│   └── sua_homestay.php    # Sửa thông tin homestay
│
├── logs/                   # Thư mục chứa log hệ thống
├── travel_booking.sql      # File Script tạo Database
├── index.php               # Entry point
├── login.php               # Trang đăng nhập
├── logout.php              # Xử lý đăng xuất
├── register.php            # Trang đăng ký
└── README.md               # Cấu trúc dự án