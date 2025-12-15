# Travel Booking System

Hệ thống đặt vé máy bay, khách sạn và thuê xe trực tuyến

## 📁 Cấu trúc dự án

```
travel-booking-system/
│
├── config/
│   ├── database.php          # Cấu hình kết nối database
│   └── config.php             # Cấu hình chung của hệ thống
│
├── includes/
│   ├── header.php             # Header chung
│   ├── footer.php             # Footer chung
│   └── functions.php          # Các hàm tiện ích
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── admin/
│   ├── dashboard.php          # Trang tổng quan quản trị
│   ├── qly_booking.php        # Quản lý đặt chỗ
│   ├── qly_chuyenbay.php      # Quản lý chuyến bay
│   ├── qly_kh.php             # Quản lý người dùng
│   ├── qly_khachsan.php       # Quản lý khách sạn
│   └── qly_xe.php             # Quản lý xe
│
├── user/
│   ├── trang_chu.php          # Trang chủ
│   ├── tim_kiem_chuyenbay.php # Tìm kiếm chuyến bay
│   ├── tim_kiem_khachsan.php  # Tìm kiếm khách sạn
│   ├── tim_kiem_xe.php        # Tìm kiếm xe
│   ├── thongtin.php           # Quản lý đặt chỗ của người dùng
│   └── hotro.php              # Hỗ trợ khách hàng
│
├── database/
│   └── travel_booking.sql     # File SQL tạo database
│
├── README.md                  # File này
└── index.php                  # Trang chủ chính
```

## 🗄️ Database Schema

### Bảng chính:

1. **users** - Quản lý người dùng
2. **flights** - Thông tin chuyến bay
3. **hotels** - Thông tin khách sạn
4. **rooms** - Phòng khách sạn
5. **vehicles** - Xe cho thuê
6. **bookings** - Đơn đặt chỗ
7. **booking_details** - Chi tiết đơn đặt chỗ
8. **payments** - Thanh toán

## 🚀 Cài đặt

### Yêu cầu hệ thống:
- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Apache/Nginx web server
- Composer (optional)

### Các bước cài đặt:

1. **Clone project**
```bash
git clone <repository-url>
cd travel-booking-system
```

2. **Tạo database**
```bash
mysql -u root -p < database/travel_booking.sql
```

3. **Cấu hình database**
- Mở file `config/database.php`
- Cập nhật thông tin kết nối:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'travel_booking');
```

4. **Chạy ứng dụng**
- Truy cập: `http://localhost/travel-booking-system`

## 👤 Tài khoản mặc định

### Admin:
- Email: `admin@travel.com`
- Password: `admin123`

### User:
- Email: `user@example.com`
- Password: `user123`

## 🎨 Tính năng

### Người dùng:
- ✈️ Tìm kiếm và đặt vé máy bay
- 🏨 Tìm kiếm và đặt phòng khách sạn
- 🚗 Thuê xe
- 📋 Quản lý đặt chỗ của mình
- 💳 Thanh toán trực tuyến
- 🆘 Hỗ trợ khách hàng

### Quản trị viên:
- 📊 Dashboard tổng quan
- ✈️ Quản lý chuyến bay
- 🏨 Quản lý khách sạn
- 🚗 Quản lý xe
- 👥 Quản lý người dùng
- 📝 Quản lý đặt chỗ
- 📈 Báo cáo thống kê

## 🔧 Công nghệ sử dụng

- **Frontend**: HTML5, TailwindCSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Icons**: Material Symbols Outlined
- **Fonts**: Plus Jakarta Sans

## 📝 License

MIT License

## 👨‍💻 Tác giả

Travel Booking System Team

## 📧 Liên hệ

Email: support@travelbooking.com
Phone: 1900 1234