-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 06:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travel_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `airlines`
--

CREATE TABLE `airlines` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `airlines`
--

INSERT INTO `airlines` (`id`, `code`, `name`, `logo`, `status`) VALUES
(1, 'VNA', 'Vietnam Airlines', NULL, 'active'),
(2, 'VJ', 'VietJet Air', NULL, 'active'),
(3, 'BBA', 'Bamboo Airways', NULL, 'active'),
(4, 'QH', 'Bamboo Airways', NULL, 'active'),
(5, 'BL', 'Pacific Airlines', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_code` varchar(20) NOT NULL,
  `booking_type` enum('flight','hotel','vehicle','combo') NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','refunded') DEFAULT 'unpaid',
  `payment_method` varchar(50) DEFAULT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `booking_code`, `booking_type`, `total_amount`, `status`, `payment_status`, `payment_method`, `booking_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 'VJ5892', 'flight', 12500000.00, 'confirmed', 'paid', NULL, '2025-12-07 16:31:41', NULL, '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(2, 2, 'HS9214', 'hotel', 7500000.00, 'pending', 'unpaid', NULL, '2025-12-07 16:31:41', NULL, '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(3, 3, 'VN8841', 'flight', 3700000.00, 'cancelled', 'refunded', NULL, '2025-12-07 16:31:41', NULL, '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(4, 2, '#12345', 'flight', 2500000.00, 'confirmed', 'paid', NULL, '2025-12-07 16:31:41', NULL, '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(5, 3, '#12346', 'hotel', 1800000.00, 'pending', 'unpaid', NULL, '2025-12-07 16:31:41', NULL, '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(6, 4, '#12347', 'vehicle', 1200000.00, 'cancelled', 'unpaid', NULL, '2025-12-07 16:31:41', NULL, '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(7, 5, '#12348', 'flight', 3100000.00, 'confirmed', 'paid', NULL, '2025-12-07 16:31:41', NULL, '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(8, 2, '#12349', 'hotel', 2000000.00, 'confirmed', 'paid', NULL, '2025-12-07 16:31:41', NULL, '2025-12-07 16:31:41', '2025-12-07 16:31:41');

-- --------------------------------------------------------

--
-- Table structure for table `booking_details`
--

CREATE TABLE `booking_details` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `service_type` enum('flight','hotel','vehicle') NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `passengers` text DEFAULT NULL,
  `special_requests` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

CREATE TABLE `flights` (
  `id` int(11) NOT NULL,
  `flight_code` varchar(20) NOT NULL,
  `airline_id` int(11) NOT NULL,
  `departure_airport` varchar(10) NOT NULL,
  `arrival_airport` varchar(10) NOT NULL,
  `departure_time` datetime NOT NULL,
  `arrival_time` datetime NOT NULL,
  `total_seats` int(11) NOT NULL,
  `available_seats` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('scheduled','delayed','cancelled','completed') DEFAULT 'scheduled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`id`, `flight_code`, `airline_id`, `departure_airport`, `arrival_airport`, `departure_time`, `arrival_time`, `total_seats`, `available_seats`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'VN255', 1, 'SGN', 'HAN', '2024-12-25 18:30:00', '2024-12-25 20:35:00', 150, 120, 2500000.00, 'scheduled', '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(2, 'VJ134', 2, 'DAD', 'SGN', '2024-12-26 09:15:00', '2024-12-26 10:30:00', 180, 180, 1200000.00, 'delayed', '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(3, 'QH202', 3, 'HAN', 'DAD', '2024-12-27 14:00:00', '2024-12-27 15:15:00', 120, 95, 1850000.00, 'scheduled', '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(4, 'BL6028', 5, 'PQC', 'HAN', '2024-12-28 21:45:00', '2024-12-28 23:50:00', 150, 140, 2100000.00, 'cancelled', '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(5, 'VN244', 1, 'SGN', 'HAN', '2024-08-28 08:00:00', '2024-08-28 10:05:00', 180, 150, 1560000.00, 'scheduled', '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(6, 'VJ150', 2, 'SGN', 'HAN', '2024-08-28 09:30:00', '2024-08-28 11:35:00', 180, 165, 1230000.00, 'scheduled', '2025-12-07 16:31:41', '2025-12-07 16:31:41');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `stars` tinyint(1) DEFAULT 3 CHECK (`stars` >= 1 and `stars` <= 5),
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `total_rooms` int(11) DEFAULT 0,
  `amenities` text DEFAULT NULL,
  `status` enum('active','inactive','hidden') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `name`, `address`, `city`, `stars`, `description`, `image`, `total_rooms`, `amenities`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Khách sạn Grand Saigon', 'Quận 1', 'TP.HCM', 5, 'Một trong những khách sạn lâu đời và sang trọng nhất tại Sài Gòn, mang đậm kiến trúc Pháp cổ.', NULL, 150, 'Hồ bơi,Wi-Fi,Nhà hàng,Spa', 'active', '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(2, 'Hanoi Daewoo Hotel', 'Ba Đình', 'Hà Nội', 5, 'Khách sạn 5 sao cao cấp tại trung tâm Hà Nội', NULL, 200, 'Hồ bơi,Wi-Fi,Bãi đỗ xe,Phòng gym', 'active', '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(3, 'InterContinental Danang', 'Sơn Trà', 'Đà Nẵng', 5, 'Resort sang trọng bên bờ biển Đà Nẵng', NULL, 180, 'Bãi biển riêng,Hồ bơi,Wi-Fi,Spa', 'hidden', '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(4, 'Vinpearl Resort & Spa', 'Phú Quốc', 'Kiên Giang', 5, 'Khu nghỉ dưỡng cao cấp tại đảo ngọc Phú Quốc', NULL, 350, 'Hồ bơi,Bãi biển,Wi-Fi,Nhà hàng', 'active', '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(5, 'Dalat Palace Heritage Hotel', 'TP. Đà Lạt', 'Lâm Đồng', 5, 'Khách sạn lịch sử với kiến trúc Pháp cổ điển', NULL, 120, 'Sân golf,Wi-Fi,Nhà hàng,Spa', 'active', '2025-12-07 16:31:41', '2025-12-07 16:31:41');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price_per_night` decimal(10,2) NOT NULL,
  `max_guests` int(11) DEFAULT 2,
  `description` text DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `hotel_id`, `room_type`, `quantity`, `price_per_night`, `max_guests`, `description`, `amenities`, `created_at`) VALUES
(1, 1, 'Standard', 50, 1200000.00, 2, NULL, 'TV,Wifi,Minibar', '2025-12-07 16:31:41'),
(2, 1, 'Deluxe', 70, 2500000.00, 2, NULL, 'TV,Wifi,Minibar,Balcony', '2025-12-07 16:31:41'),
(3, 1, 'Suite', 30, 5000000.00, 4, NULL, 'TV,Wifi,Minibar,Balcony,Living room', '2025-12-07 16:31:41'),
(4, 2, 'Superior', 80, 1800000.00, 2, NULL, 'TV,Wifi,Minibar', '2025-12-07 16:31:41'),
(5, 2, 'Executive', 100, 3500000.00, 3, NULL, 'TV,Wifi,Minibar,Work desk', '2025-12-07 16:31:41'),
(6, 3, 'Ocean View', 90, 4200000.00, 2, NULL, 'TV,Wifi,Sea view,Balcony', '2025-12-07 16:31:41'),
(7, 4, 'Garden Villa', 50, 8500000.00, 4, NULL, 'Private pool,Garden,Kitchen', '2025-12-07 16:31:41'),
(8, 5, 'Classic', 60, 2000000.00, 2, NULL, 'TV,Wifi,Fireplace', '2025-12-07 16:31:41');

-- --------------------------------------------------------

--
-- Table structure for table `system_alerts`
--

CREATE TABLE `system_alerts` (
  `id` int(11) NOT NULL,
  `type` enum('error','warning','info') DEFAULT 'info',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_resolved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_alerts`
--

INSERT INTO `system_alerts` (`id`, `type`, `title`, `message`, `is_resolved`, `created_at`, `resolved_at`) VALUES
(1, 'error', 'Lỗi thanh toán', 'Cổng thanh toán Stripe đang gặp sự cố. Cần kiểm tra ngay.', 0, '2025-12-07 16:31:41', NULL),
(2, 'warning', 'API hãng bay chậm', 'API của Vietnam Airlines (VNA) phản hồi chậm hơn 3 giây.', 0, '2025-12-07 16:31:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `status` enum('active','inactive','blocked') DEFAULT 'active',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@travel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0123456789', 'admin', 'active', NULL, '2025-12-07 16:31:40', '2025-12-07 16:31:40'),
(2, 'Lê Minh Anh', 'minhanh.le@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0987654321', 'user', 'active', NULL, '2025-12-07 16:31:40', '2025-12-07 16:31:40'),
(3, 'Trần Văn Bảo', 'baotran@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0912345678', 'user', 'blocked', NULL, '2025-12-07 16:31:40', '2025-12-07 16:31:40'),
(4, 'Phạm Thị Diệu', 'dieu.pham@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234567', 'admin', 'active', NULL, '2025-12-07 16:31:40', '2025-12-07 16:31:40'),
(5, 'Nguyễn Tuấn Kiệt', 'kiet.nguyen@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0945678901', 'user', 'active', NULL, '2025-12-07 16:31:40', '2025-12-07 16:31:40'),
(6, 'Admin', 'admin@gmail.com', '$2y$10$T9IuQ7KnI3nt010BSgQs.ufm124kAjE1YlJ8lGthHEqmg32DS4z72', '0373654414', 'admin', 'active', NULL, '2025-12-10 17:06:12', '2025-12-10 17:06:28');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('sedan','suv','minivan','van') NOT NULL,
  `brand` varchar(50) NOT NULL,
  `license_plate` varchar(20) NOT NULL,
  `seats` int(11) NOT NULL,
  `luggage_capacity` int(11) DEFAULT 2,
  `price_per_day` decimal(10,2) NOT NULL,
  `price_per_trip` decimal(10,2) DEFAULT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('available','rented','maintenance') DEFAULT 'available',
  `rating` decimal(2,1) DEFAULT 4.5,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `name`, `type`, `brand`, `license_plate`, `seats`, `luggage_capacity`, `price_per_day`, `price_per_trip`, `provider`, `amenities`, `image`, `status`, `rating`, `created_at`) VALUES
(1, 'VinFast VF8', 'suv', 'VinFast', '29A-123.45', 7, 4, 1200000.00, NULL, 'VinFast Rentals', 'AC,WiFi,Child seat', NULL, 'available', 4.8, '2025-12-07 16:31:41'),
(2, 'Toyota Vios', 'sedan', 'Toyota', '51G-678.90', 4, 2, 800000.00, 350000.00, 'Mai Linh Corp', 'AC,WiFi', NULL, 'rented', 4.5, '2025-12-07 16:31:41'),
(3, 'Ford Ranger', 'suv', 'Ford', '30H-112.23', 5, 4, 1500000.00, NULL, 'Avis Vietnam', 'AC', NULL, 'maintenance', 4.5, '2025-12-07 16:31:41'),
(4, 'Honda City', 'sedan', 'Honda', '92A-445.56', 4, 2, 900000.00, NULL, 'Budget Car Rental', 'AC,WiFi', NULL, 'available', 4.9, '2025-12-07 16:31:41'),
(5, 'Kia Carnival', 'minivan', 'Kia', '60F-778.89', 7, 10, 1800000.00, NULL, 'Hertz Vietnam', 'AC,WiFi,Drinks', NULL, 'available', 4.9, '2025-12-07 16:31:41'),
(6, 'Toyota Fortuner', 'suv', 'Toyota', '51G-111.22', 7, 4, 1400000.00, 500000.00, 'GoCar', 'AC,Child seat', NULL, 'available', 4.5, '2025-12-07 16:31:41'),
(7, 'Ford Transit', 'van', 'Ford', '29A-222.33', 16, 10, 2000000.00, 850000.00, 'Luxury Trans', 'AC,WiFi,Drinks', NULL, 'available', 4.9, '2025-12-07 16:31:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `airlines`
--
ALTER TABLE `airlines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_code` (`booking_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `flights`
--
ALTER TABLE `flights`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `flight_code` (`flight_code`),
  ADD KEY `airline_id` (`airline_id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `system_alerts`
--
ALTER TABLE `system_alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_plate` (`license_plate`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `airlines`
--
ALTER TABLE `airlines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `booking_details`
--
ALTER TABLE `booking_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flights`
--
ALTER TABLE `flights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `system_alerts`
--
ALTER TABLE `system_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD CONSTRAINT `booking_details_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `flights`
--
ALTER TABLE `flights`
  ADD CONSTRAINT `flights_ibfk_1` FOREIGN KEY (`airline_id`) REFERENCES `airlines` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
