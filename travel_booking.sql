-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 11:56 AM
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
(3, 'BL', 'Jestar Pacific', NULL, 'active'),
(4, 'QH', 'Bamboo Airways', NULL, 'active'),
(5, 'VN', 'Pacific Airlines', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `author` varchar(100) DEFAULT 'Admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `summary`, `content`, `image`, `author`, `created_at`) VALUES
(1, 'Top 10 Homestay View Hồ Tây Cực Chill', 'Tổng hợp những homestay có view bao trọn Hồ Tây, không gian lãng mạn thích hợp cho các cặp đôi.', '<div class=\"blog-body\">\r\n    <h3>1. Tại sao nên chọn homestay Hồ Tây?</h3>\r\n    <p>Hồ Tây luôn là địa điểm lãng mạn bậc nhất Hà Nội. Với không gian thoáng đãng, những con đường rợp bóng cây và đặc biệt là cảnh hoàng hôn cực phẩm, đây là nơi lý tưởng để các cặp đôi đi trốn ngay trong lòng thành phố.</p>\r\n    <p>Các homestay tại đây thường được thiết kế với cửa kính lớn (panorama) để tận dụng tối đa view hồ. Hãy tưởng tượng buổi sáng thức dậy, kéo rèm ra là mặt nước mênh mông.</p>\r\n    \r\n    <img src=\"https://images.unsplash.com/photo-1510798831971-661eb04b3739?q=80&w=1000\" alt=\"Hygge Homestay\">\r\n    \r\n    <h3>2. Hygge Homestay - Khu rừng nhỏ</h3>\r\n    <p>Nằm trên đường Âu Cơ, Hygge mang đến một không gian xanh mướt như một khu rừng Bắc Âu thu nhỏ. Căn nhà được xây dựng chủ yếu bằng gỗ và kính.</p>\r\n    <p>Giá phòng dao động từ 1.200.000đ - 1.800.000đ/đêm.</p>\r\n</div>', 'https://images.unsplash.com/photo-1512918760513-95f1fde64203?q=80&w=1000', 'Hồng Đăng', '2025-12-14 07:23:49'),
(2, 'Kinh Nghiệm Du Lịch Hà Nội Mùa Thu', 'Mùa thu Hà Nội đẹp nao lòng với hương hoa sữa và cốm xanh. Cùng khám phá lịch trình 24h trọn vẹn.', '<div class=\"blog-body\">\r\n    <h3>Mùa thu Hà Nội có gì đặc biệt?</h3>\r\n    <p>Hà Nội mùa thu, cây cơm nguội vàng, cây bàng lá đỏ... Câu hát ấy đã đi vào tiềm thức của bao người. Mùa thu Hà Nội bắt đầu từ tháng 9 đến tháng 11.</p>\r\n    \r\n    <h3>Buổi sáng: Cà phê phố cổ</h3>\r\n    <p>Hãy bắt đầu ngày mới bằng một bát phở bò Lý Quốc Sư. Sau đó, ghé Đinh Café hoặc Giảng để thưởng thức một cốc cà phê trứng béo ngậy.</p>\r\n    \r\n    <img src=\"https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=1000\" alt=\"Cafe Hà Nội\">\r\n    \r\n    <h3>Buổi chiều: Đường Phan Đình Phùng</h3>\r\n    <p>Đây được mệnh danh là con đường lãng mạn nhất Hà Nội. Vào mùa thu, nắng xiên qua kẽ lá tạo nên những vệt sáng tuyệt đẹp.</p>\r\n</div>', 'https://images.unsplash.com/photo-1555921015-5532091f6026?q=80&w=1000', 'Mai Anh', '2025-12-14 07:23:49'),
(3, 'Review 5 Quán Cà Phê Trứng Ngon Nhất', 'Cà phê trứng là đặc sản không thể bỏ qua. Đây là danh sách 5 quán cafe trứng chuẩn vị truyền thống.', '<div class=\"blog-body\">\r\n    <h3>1. Cà phê Giảng</h3>\r\n    <p>Nhắc đến cà phê trứng, không thể không nhắc đến Giảng. Đây là nơi khai sinh ra món đồ uống huyền thoại này. Cốc cà phê được đặt trong một bát nước nóng để giữ nhiệt.</p>\r\n    \r\n    <img src=\"https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=1000\" alt=\"Cafe Trứng\">\r\n    \r\n    <h3>2. Cà phê Đinh</h3>\r\n    <p>Nằm trên tầng 2 của một căn nhà cổ, Đinh mang đậm nét hoài niệm của Hà Nội xưa. Quán khá nhỏ và luôn đông khách, nhưng bù lại bạn sẽ có view nhìn thẳng ra Hồ Gươm.</p>\r\n</div>', 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=1000', 'Admin', '2025-12-14 07:23:49'),
(4, 'Bí Kíp Chụp Ảnh Sống Ảo Tại Homestay', 'Chỉ cần một chiếc điện thoại và biết cách chọn góc sáng, bạn sẽ có ngay bộ ảnh nghìn like.', '<div class=\"blog-body\">\r\n    <h3>1. Tận dụng ánh sáng tự nhiên</h3>\r\n    <p>Ánh sáng là yếu tố quan trọng nhất. Hãy mở hết rèm cửa, chụp ảnh vào khung giờ vàng (7-9h sáng hoặc 3-5h chiều). Ánh nắng xiên sẽ tạo hiệu ứng đổ bóng rất nghệ thuật.</p>\r\n    \r\n    <h3>2. Góc chụp thần thánh</h3>\r\n    <p>Đừng chỉ chụp thẳng mặt. Hãy thử góc nghiêng, góc từ trên xuống hoặc chụp qua gương. Một chiếc gương toàn thân là đạo cụ không thể thiếu tại các homestay.</p>\r\n    \r\n    <img src=\"https://images.unsplash.com/photo-1522771753037-6333610d2acc?q=80&w=1000\" alt=\"Sống ảo\">\r\n</div>', 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=1000', 'Thùy Chi', '2025-12-14 07:23:49'),
(5, 'Food Tour Hà Nội: Ăn Sập Chợ Đồng Xuân', 'Cầm 200k trong tay liệu có thể ăn no nê tại thiên đường ẩm thực chợ Đồng Xuân?', '<div class=\"blog-body\">\r\n    <h3>Món 1: Bún chả kẹp que tre (35k)</h3>\r\n    <p>Khác với bún chả nướng vỉ, bún chả kẹp que tre có hương vị khói đặc trưng. Thịt nướng cháy xém cạnh, thơm lừng mùi sả và hành tím.</p>\r\n    \r\n    <h3>Món 2: Chè thập cẩm (20k)</h3>\r\n    <p>Giữa cái nắng oi ả, một cốc chè thập cẩm mát lạnh là chân ái. Chè ở chợ Đồng Xuân nổi tiếng với nhiều loại topping tự làm.</p>\r\n    \r\n    <img src=\"https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1000\" alt=\"Bún chả\">\r\n</div>', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1000', 'Tuấn Hưng', '2025-12-14 07:23:49'),
(6, 'Xu Hướng Staycation: Nghỉ Dưỡng Tại Chỗ', 'Không cần đi xa, xu hướng Staycation (du lịch tại chỗ) đang trở thành lựa chọn hàng đầu.', '<div class=\"blog-body\">\r\n    <h3>Staycation là gì?</h3>\r\n    <p>Staycation là từ ghép của Stay (ở lại) và Vacation (kỳ nghỉ). Nghĩa là bạn sẽ đi du lịch ngay tại thành phố mình đang sống. Không cần vé máy bay, không cần visa.</p>\r\n    \r\n    <h3>Lợi ích của Staycation</h3>\r\n    <ul>\r\n        <li>Tiết kiệm chi phí di chuyển.</li>\r\n        <li>Chủ động thời gian.</li>\r\n        <li>Khám phá những góc lạ của thành phố quen thuộc.</li>\r\n    </ul>\r\n    \r\n    <img src=\"https://images.unsplash.com/photo-1571896349842-6e53ce41e86a?q=80&w=1000\" alt=\"Staycation\">\r\n</div>', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1000', 'Admin', '2025-12-14 07:23:49');

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
(6, 4, '#12347', 'vehicle', 1200000.00, 'cancelled', 'unpaid', NULL, '2025-12-07 16:31:41', NULL, '2025-12-07 16:31:41', '2025-12-07 16:31:41'),
(7, 5, '#12348', 'flight', 3100000.00, 'confirmed', 'paid', NULL, '2025-12-07 16:31:41', NULL, '2025-12-07 16:31:41', '2025-12-07 16:31:41');

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
  `pickup_location` varchar(255) DEFAULT NULL COMMENT 'Điểm đón khách',
  `dropoff_location` varchar(255) DEFAULT NULL COMMENT 'Điểm trả khách',
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `passengers` text DEFAULT NULL,
  `special_requests` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_details`
--

INSERT INTO `booking_details` (`id`, `booking_id`, `service_type`, `service_id`, `quantity`, `unit_price`, `subtotal`, `pickup_location`, `dropoff_location`, `check_in`, `check_out`, `passengers`, `special_requests`) VALUES
(1, 1, 'flight', 2, 5, 2500000.00, 12500000.00, NULL, NULL, '2025-12-26', NULL, 'Lê Minh Anh, Nguyễn Văn A', NULL),
(2, 2, 'hotel', 4, 2, 3750000.00, 7500000.00, NULL, NULL, '2025-12-20', '2025-12-25', NULL, NULL),
(3, 6, 'vehicle', 1, 1, 1200000.00, 1200000.00, 'Sân bay Nội Bài, Hà Nội', 'Khách sạn Melia, 44 Lý Thường Kiệt', '2025-12-08', NULL, NULL, 'Cần tài xế biết tiếng Anh'),
(4, 7, 'vehicle', 5, 1, 3100000.00, 3100000.00, 'Trung tâm Đà Nẵng', 'Bà Nà Hills', '2025-12-15', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `admin_reply` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
(1, 'VN255', 1, 'SGN', 'HAN', '2025-12-25 18:30:00', '2025-12-25 20:35:00', 150, 120, 2500000.00, 'scheduled', '2025-12-07 16:31:41', '2025-12-16 09:17:03'),
(2, 'VJ134', 2, 'DAD', 'SGN', '2025-12-09 09:15:00', '2025-12-09 10:30:00', 180, 180, 1200000.00, 'delayed', '2025-12-07 16:31:41', '2025-12-16 09:18:16'),
(4, 'BL6028', 5, 'PQC', 'HAN', '2026-01-28 21:45:00', '2026-01-28 23:50:00', 150, 140, 2100000.00, 'cancelled', '2025-12-07 16:31:41', '2025-12-16 09:16:33'),
(5, 'VN244', 1, 'SGN', 'HAN', '2025-12-31 08:00:00', '2025-12-31 10:05:00', 180, 150, 1560000.00, 'scheduled', '2025-12-07 16:31:41', '2025-12-16 09:17:35'),
(6, 'VJ150', 2, 'SGN', 'HAN', '2026-01-08 09:30:00', '2026-01-08 11:35:00', 180, 165, 1230000.00, 'scheduled', '2025-12-07 16:31:41', '2025-12-16 09:17:14');

-- --------------------------------------------------------

--
-- Table structure for table `flight_bookings`
--

CREATE TABLE `flight_bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `booking_code` varchar(20) NOT NULL,
  `passenger_name` varchar(100) NOT NULL,
  `passenger_phone` varchar(20) NOT NULL,
  `passenger_email` varchar(100) NOT NULL,
  `seat_count` int(11) DEFAULT 1,
  `total_price` decimal(15,0) NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `homestays`
--

CREATE TABLE `homestays` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price_weekday` decimal(10,0) NOT NULL DEFAULT 0,
  `price_weekend` decimal(10,0) DEFAULT 0,
  `address` varchar(255) DEFAULT NULL,
  `district` varchar(100) DEFAULT 'Trung tâm',
  `description` text DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `extra_image_1` varchar(255) DEFAULT NULL,
  `extra_image_2` varchar(255) DEFAULT NULL,
  `max_guests` int(11) DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `extra_image_3` varchar(255) DEFAULT NULL,
  `extra_image_4` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `homestays`
--

INSERT INTO `homestays` (`id`, `name`, `price_weekday`, `price_weekend`, `address`, `district`, `description`, `main_image`, `extra_image_1`, `extra_image_2`, `max_guests`, `created_at`, `extra_image_3`, `extra_image_4`) VALUES
(1, 'Chill House Hồ Tây', 1200000, 1500000, 'Tô Ngọc Vân', 'Tây Hồ', 'View hồ cực chill.', 'https://images.unsplash.com/photo-1566073771259-6a8506099945', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b', 'https://images.unsplash.com/photo-1554995207-c18c203602cb', 4, '2025-12-14 03:35:07', NULL, NULL),
(2, 'Vintage Home Phố Cổ', 800000, 1000000, 'Mã Mây', 'Hoàn Kiếm', 'Các loại phòng tại Granda Lake View Hotel & Apartment\r\nGranda Lake View Hotel & Apartment cung cấp nhiều loại phòng đa dạng, phù hợp với nhu cầu của từng du khách. Mỗi phòng đều được trang bị đầy đủ tiện nghi như máy lạnh, tivi màn hình phẳng, tủ lạnh mini, máy sấy tóc và kết nối Wi-Fi miễn phí. Phòng tắm riêng với vòi sen hiện đại và các vật dụng cá nhân cần thiết cũng được cung cấp, đảm bảo sự tiện lợi và thoải mái cho khách lưu trú.\r\n\r\nCác điểm tham quan gần khách sạn\r\nTừ Granda Lake View Hotel & Apartment, du khách có thể dễ dàng tiếp cận nhiều điểm tham quan nổi tiếng của Hà Nội. Trung tâm thương mại Vincom Center Nguyễn Chí Thanh chỉ cách khách sạn khoảng 2,2 km, tương đương 10 phút lái xe. Bảo tàng Dân tộc học Việt Nam nằm cách khách sạn khoảng 4 km, mất khoảng 15 phút lái xe. Ngoài ra, du khách cũng có thể ghé thăm Bảo tàng Mỹ thuật Việt Nam, cách khách sạn khoảng 5,6 km, tương đương 20 phút lái xe, để chiêm ngưỡng các tác phẩm nghệ thuật độc đáo.\r\n\r\nTiện ích nổi bật tại Granda Lake View Hotel & Apartment\r\nKhách sạn cung cấp nhiều tiện ích hấp dẫn nhằm mang lại trải nghiệm tốt nhất cho du khách. Nhà hàng trong khuôn viên phục vụ các món ăn đa dạng, từ ẩm thực địa phương đến quốc tế, đáp ứng nhu cầu ẩm thực của mọi du khách. Dịch vụ phòng 24/7 giúp du khách có thể thưởng thức bữa ăn ngay tại phòng một cách tiện lợi. Ngoài ra, khách sạn còn có quầy bar, nơi du khách có thể thư giãn và thưởng thức các loại đồ uống phong phú.\r\n\r\nPhương tiện giao thông công cộng gần khách sạn\r\nGranda Lake View Hotel & Apartment nằm gần các tuyến đường chính, giúp du khách dễ dàng di chuyển bằng phương tiện công cộng. Bến xe Mỹ Đình cách khách sạn khoảng 5 km, tương đương 15 phút lái xe. Từ đây, du khách có thể bắt các chuyến xe buýt hoặc taxi để di chuyển đến các khu vực khác trong thành phố hoặc các tỉnh lân cận.\r\n\r\nChất lượng dịch vụ tại Granda Lake View Hotel & Apartment\r\nĐội ngũ nhân viên tại Granda Lake View Hotel & Apartment luôn tận tâm và chuyên nghiệp, sẵn sàng hỗ trợ du khách 24/7. Quầy lễ tân hoạt động liên tục, đảm bảo quá trình nhận và trả phòng diễn ra nhanh chóng và thuận tiện. Dịch vụ dọn phòng hàng ngày giúp duy trì không gian sạch sẽ và thoải mái cho khách lưu trú. Ngoài ra, khách sạn còn cung cấp dịch vụ giặt ủi và lưu trữ hành lý, đáp ứng mọi nhu cầu của du khách trong suốt thời gian lưu trú.\r\n\r\nĐánh giá của du khách trên Traveloka về Granda Lake View Hotel & Apartment\r\nTheo đánh giá trên Traveloka, Granda Lake View Hotel & Apartment nhận được điểm số 8.8/10, được xếp hạng là \"Ấn tượng\" từ 28 đánh giá của khách đã ở. Nhiều du khách khen ngợi vị trí thuận lợi của khách sạn, gần các điểm du lịch và chợ, cùng với phòng ốc sạch sẽ và tiện nghi. Một số khách hàng cũng đánh giá cao sự nhiệt tình và chu đáo của nhân viên, cũng như giá cả hợp lý, đặc biệt vào cuối tuần. Tuy nhiên, một số ý kiến góp ý về vấn đề cách âm giữa các phòng, nhưng nhìn chung, Granda Lake View Hotel & Apartment vẫn là lựa chọn được nhiều du khách tin tưởng khi đến Hà Nội.\r\n\r\nKhu vực xung quanh Granda Lake View Hotel & Apartment\r\nKhách sạn nằm trong khu vực phường Trung Hòa, một khu vực sầm uất với nhiều tiện ích xung quanh. Chợ Trung Kính chỉ cách khách sạn vài bước chân, là nơi lý tưởng để du khách trải nghiệm văn hóa địa phương và mua sắm các sản phẩm đặc sản. Ngoài ra, khu vực này còn có nhiều quán ăn, cà phê và cửa hàng tiện lợi, đáp ứng mọi nhu cầu của du khách. Với vị trí gần trung tâm, du khách có thể dễ dàng di chuyển đến các điểm tham quan và vui chơi giải trí trong thành phố.', 'uploads/home_1765691911_353.jpeg', 'uploads/home_1765691928_187.jpeg', 'uploads/home_1765691911_887.jpeg', 2, '2025-12-14 03:35:07', NULL, NULL),
(3, 'The Old Quarter Hidden Gem', 850000, 1100000, 'Ngõ Huyện, Hàng Trống', 'Hoàn Kiếm', 'Nằm ẩn mình trong con ngõ nhỏ yên tĩnh giữa lòng phố cổ náo nhiệt. Căn nhà giữ nguyên kiến trúc Pháp cổ với trần cao, cửa sổ lá sách xanh và ban công trồng đầy hoa giấy. Phù hợp cho cặp đôi muốn tìm cảm giác hoài niệm.', 'https://images.unsplash.com/photo-1590490360182-c33d57733427?q=80&w=1000', 'https://images.unsplash.com/photo-1505693436371-52e403ad46ac?q=80&w=1000', 'https://images.unsplash.com/photo-1513694203232-719a280e022f?q=80&w=1000', 2, '2025-12-14 06:16:13', NULL, NULL),
(4, 'Hanoi Art House Balcony', 1200000, 1500000, 'Phùng Hưng, Hàng Mã', 'Hoàn Kiếm', 'Homestay dành cho người yêu nghệ thuật. Tường được vẽ tranh tường độc đáo, nội thất gỗ mộc mạc. Ban công rộng view thẳng ra đường tàu Phùng Hưng cực chill để uống trà chiều.', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=1000', 'https://images.unsplash.com/photo-1501183638710-841dd1904471?q=80&w=1000', 'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=1000', 4, '2025-12-14 06:16:13', NULL, NULL),
(5, 'La Vie En Rose Studio', 950000, 1200000, 'Tràng Thi', 'Hoàn Kiếm', 'Studio phong cách lãng mạn với tông màu hồng pastel chủ đạo. Trang bị đầy đủ bếp, bồn tắm nằm sang trọng. Vị trí đắc địa chỉ cách Hồ Gươm 2 phút đi bộ.', 'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?q=80&w=1000', 'https://images.unsplash.com/photo-1584622050111-993a426fbf0a?q=80&w=1000', 'https://images.unsplash.com/photo-1512918760513-95f1fde64203?q=80&w=1000', 2, '2025-12-14 06:16:13', NULL, NULL),
(6, 'West Lake Sunset View', 1500000, 1800000, 'Từ Hoa, Quảng An', 'Tây Hồ', 'Căn hộ cao cấp view trực diện Hồ Tây. Thiết kế kính tràn Panorama giúp bạn ngắm trọn hoàng hôn rực rỡ ngay tại giường ngủ. Đầy đủ tiện nghi 5 sao, bể bơi vô cực trên tầng thượng.', 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=1000', 'https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=1000', 'https://images.unsplash.com/photo-1560448204-61dc36dc98c8?q=80&w=1000', 4, '2025-12-14 06:16:13', NULL, NULL),
(7, 'Minimalist Loft Tay Ho', 700000, 900000, 'Âu Cơ', 'Tây Hồ', 'Phong cách tối giản (Minimalism) Nhật Bản. Không gian thoáng đãng, nhiều ánh sáng tự nhiên. Thích hợp cho dân Digital Nomad cần không gian làm việc yên tĩnh và sáng tạo.', 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?q=80&w=1000', 'https://images.unsplash.com/photo-1536376072261-38c75010e6c9?q=80&w=1000', 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=1000', 2, '2025-12-14 06:16:13', NULL, NULL),
(8, 'Tropical Garden Villa', 3500000, 4200000, 'Tô Ngọc Vân', 'Tây Hồ', 'Biệt thự sân vườn nhiệt đới xanh mát giữa lòng Hà Nội. Có hồ bơi riêng, khu vực BBQ ngoài trời, thích hợp cho nhóm bạn hoặc gia đình tổ chức tiệc cuối tuần.', 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?q=80&w=1000', 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?q=80&w=1000', 'https://images.unsplash.com/photo-1600596542815-22b89152522f?q=80&w=1000', 10, '2025-12-14 06:16:13', NULL, NULL),
(9, 'Indochine Suite Ba Dinh', 1800000, 2200000, 'Phan Đình Phùng', 'Ba Đình', 'Căn hộ phong cách Đông Dương (Indochine) sang trọng nằm trên con đường đẹp nhất Hà Nội. Nội thất gỗ gụ, gạch bông cổ điển. Gần Lăng Bác và Hoàng Thành Thăng Long.', 'https://images.unsplash.com/photo-1507089947368-19c1da9775ae?q=80&w=1000', 'https://images.unsplash.com/photo-1550581190-9c1c48d21d6c?q=80&w=1000', 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?q=80&w=1000', 3, '2025-12-14 06:16:13', NULL, NULL),
(10, 'Scandinavian Bright Home', 1100000, 1300000, 'Kim Mã', 'Ba Đình', 'Căn hộ Bắc Âu ngập tràn ánh sáng. Tone màu trắng chủ đạo kết hợp cây xanh. Gần Lotte Center, Vincom Metropolis, thuận tiện mua sắm và giải trí.', 'https://images.unsplash.com/photo-1616486338812-3dadae4b4f9d?q=80&w=1000', 'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?q=80&w=1000', 'https://images.unsplash.com/photo-1484154218962-a1c002085d2f?q=80&w=1000', 4, '2025-12-14 06:16:13', NULL, NULL),
(11, 'Modern Sky City Apartment', 1600000, 2000000, 'Cầu Giấy', 'Cầu Giấy', 'Căn hộ tầng cao tại tòa Discovery Complex. View toàn cảnh thành phố lung linh về đêm. Trang bị Smart Home, Netflix 4K, máy giặt sấy. Phù hợp khách công tác.', 'https://images.unsplash.com/photo-1522771753037-6333610d2acc?q=80&w=1000', 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=1000', 'https://images.unsplash.com/photo-1556911220-bff31c812dba?q=80&w=1000', 4, '2025-12-14 06:16:13', NULL, NULL),
(12, 'Cozy Family Condo', 1300000, 1500000, 'Trần Thái Tông', 'Cầu Giấy', 'Căn hộ 2 phòng ngủ ấm cúng dành cho gia đình. Có khu vui chơi trẻ em nội khu, gần công viên Cầu Giấy. Bếp rộng đầy đủ dụng cụ nấu nướng.', 'https://images.unsplash.com/photo-1460317442991-0ec209397118?q=80&w=1000', 'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?q=80&w=1000', 'https://images.unsplash.com/photo-1584622050111-993a426fbf0a?q=80&w=1000', 5, '2025-12-14 06:16:13', NULL, NULL),
(13, 'Industrial Loft 1990', 800000, 1000000, 'Lò Đúc', 'Hai Bà Trưng', 'Căn Loft phong cách công nghiệp cực chất với tường gạch trần, sàn bê tông mài và nội thất kim loại. Điểm check-in cực ngầu cho giới trẻ.', 'https://images.unsplash.com/photo-1515263487990-61b07816b324?q=80&w=1000', 'https://images.unsplash.com/photo-1554104707-a76b270e4bbb?q=80&w=1000', 'https://images.unsplash.com/photo-1515362778563-6a8d0e44bc0b?q=80&w=1000', 2, '2025-12-14 06:16:13', NULL, NULL),
(14, 'Times City Luxury Stay', 2200000, 2600000, 'Minh Khai', 'Hai Bà Trưng', 'Căn hộ 3 ngủ tại Times City Park Hill. Tận hưởng tiện ích đẳng cấp: Nhạc nước, Thủy cung, TTTM Vincom Mega Mall ngay dưới chân nhà.', 'https://images.unsplash.com/photo-1560448205-4d9b3e6bb6db?q=80&w=1000', 'https://images.unsplash.com/photo-1560185007-cde436f6a4d0?q=80&w=1000', 'https://images.unsplash.com/photo-1560185008-a98751bb513d?q=80&w=1000', 6, '2025-12-14 06:16:13', NULL, NULL),
(15, 'Green Oasis Dong Da', 900000, 1200000, 'Hào Nam', 'Đống Đa', 'Một ốc đảo xanh yên bình ngay gần nhạc viện Hà Nội. Sân thượng trồng nhiều cây xanh, thích hợp đọc sách và thiền. Phòng ốc sạch sẽ, decor tinh tế.', 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?q=80&w=1000', 'https://images.unsplash.com/photo-1585544314038-a0d07e499a64?q=80&w=1000', 'https://images.unsplash.com/photo-1595846519845-68e298c2edd8?q=80&w=1000', 2, '2025-12-14 06:16:13', NULL, NULL),
(16, 'Vintage Retro House', 650000, 800000, 'Đặng Văn Ngữ', 'Đống Đa', 'Căn phòng nhỏ xinh nằm trong khu tập thể cũ, view nhìn xuống những cửa hàng quần áo vintage. Không gian hoài cổ, ấm cúng, giá cả phải chăng.', 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=1000', 'https://images.unsplash.com/photo-1520277739336-7bf67edfa768?q=80&w=1000', 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?q=80&w=1000', 2, '2025-12-14 06:16:13', NULL, NULL),
(17, 'Red River Riverside House', 2000000, 2500000, 'Ngọc Thụy', 'Long Biên', 'Nhà nguyên căn view sông Hồng lộng gió. Không gian mở, gần gũi thiên nhiên. Có sân cỏ rộng để cắm trại và nướng BBQ ngắm cầu Long Biên.', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?q=80&w=1000', 'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?q=80&w=1000', 'https://images.unsplash.com/photo-1533633354749-6790e0e183df?q=80&w=1000', 8, '2025-12-14 06:16:13', NULL, NULL),
(18, 'Eco Green Apartment', 1200000, 1400000, 'Sài Đồng', 'Long Biên', 'Căn hộ trong khu đô thị sinh thái Vinhomes Riverside. Không gian sang trọng, an ninh 24/7. Tận hưởng cuộc sống xanh chuẩn resort.', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=1000', 'https://images.unsplash.com/photo-1560185008-b033106af5c3?q=80&w=1000', 'https://images.unsplash.com/photo-1560185009-ddb1c9e86e99?q=80&w=1000', 4, '2025-12-14 06:16:13', NULL, NULL),
(19, 'Pine Forest Cabin Soc Son', 1800000, 2500000, 'Minh Phú', 'Sóc Sơn', 'Cabin gỗ nằm giữa rừng thông bạt ngàn. Trải nghiệm cảm giác như đang ở Đà Lạt ngay gần Hà Nội. Có lưới nằm đọc sách, bể ngâm ngoài trời.', 'https://images.unsplash.com/photo-1449844908441-8829872d2607?q=80&w=1000', 'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?q=80&w=1000', 'https://images.unsplash.com/photo-1521401830884-6c03c1c87ebb?q=80&w=1000', 4, '2025-12-14 06:16:13', NULL, NULL),
(20, 'Hilltop Villa with Pool', 4500000, 5500000, 'Hiền Ninh', 'Sóc Sơn', 'Biệt thự trên đồi với bể bơi vô cực view núi rừng hùng vĩ. 5 phòng ngủ, phòng karaoke, bàn bi-a. Lựa chọn hoàn hảo cho Company Trip hoặc họp lớp.', 'https://images.unsplash.com/photo-1613977257363-707ba9348227?q=80&w=1000', 'uploads/update_1765693294_646.jpg', 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?q=80&w=1000', 15, '2025-12-14 06:16:13', NULL, NULL),
(21, 'Smart Studio My Dinh', 850000, 1050000, 'Mễ Trì', 'Nam Từ Liêm', 'Căn hộ Studio nhỏ xinh ngay gần Keangnam và The Manor. Thiết kế thông minh, tối ưu diện tích. Gần nhiều nhà hàng Hàn Quốc ngon nổi tiếng.', 'https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=1000', 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?q=80&w=1000', 'https://images.unsplash.com/photo-1534349762230-e0cadf78f5da?q=80&w=1000', 2, '2025-12-14 06:16:13', NULL, NULL),
(22, 'Royal City Royal Stay', 1900000, 2300000, 'Nguyễn Trãi', 'Thanh Xuân', 'Trải nghiệm phong cách hoàng gia Châu Âu tại Royal City. Căn hộ rộng 100m2, nội thất cổ điển sang trọng. Tiện ích đa dạng ngay dưới hầm.', 'https://images.unsplash.com/photo-1560185127-6ed189bf02f4?q=80&w=1000', 'uploads/update_1765693221_989.jpg', 'uploads/update_1765693221_860.jpeg', 6, '2025-12-14 06:16:13', NULL, NULL);

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
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT 'General',
  `author` varchar(50) DEFAULT 'Admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `description`, `content`, `image_url`, `category`, `author`, `created_at`) VALUES
(1, 'Top 5 Homestay đẹp nhất 2024', 'Danh sách tổng hợp những nơi đáng sống ảo...', NULL, 'uploads/blog_1.jpg', 'General', 'Admin', '2025-12-13 14:39:58');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `homestay_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `reply` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `homestay_id`, `rating`, `comment`, `reply`, `created_at`) VALUES
(2, 1, 21, 4, 'ok\r\n', NULL, '2025-12-14 07:27:29');

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
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT '#',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_notifications`
--

INSERT INTO `user_notifications` (`id`, `user_id`, `message`, `link`, `is_read`, `created_at`) VALUES
(1, 2, 'Chào mừng bạn đến với Homestay App! Chúc bạn có kỳ nghỉ vui vẻ.', '#', 0, '2025-12-13 14:39:58'),
(2, 3, 'Cảm ơn bé đã lựa chọn chúng tôi❤️', 'trang_chu.php', 1, '2025-12-13 14:42:44'),
(3, 1, 'Cảm ơn bé đã lựa chọn chúng tôi❤️', 'trang_chu.php', 1, '2025-12-13 14:43:53');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dia_chi` varchar(100) DEFAULT NULL,
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

INSERT INTO `vehicles` (`id`, `name`, `dia_chi`, `type`, `brand`, `license_plate`, `seats`, `luggage_capacity`, `price_per_day`, `price_per_trip`, `provider`, `amenities`, `image`, `status`, `rating`, `created_at`) VALUES
(1, 'VinFast VF8', 'Hà Nội', 'suv', 'VinFast', '29A-123.45', 7, 4, 1200000.00, NULL, 'VinFast Rentals', 'AC,WiFi,Child seat', NULL, 'available', 4.8, '2025-12-07 16:31:41'),
(2, 'Toyota Vios', 'Hồ Chí Minh', 'sedan', 'Toyota', '51G-678.90', 4, 2, 800000.00, 350000.00, 'Mai Linh Corp', 'AC,WiFi', NULL, 'rented', 4.5, '2025-12-07 16:31:41'),
(3, 'Ford Ranger', 'Đà Nẵng', 'suv', 'Ford', '30H-112.23', 5, 4, 1500000.00, NULL, 'Avis Vietnam', 'AC', NULL, 'maintenance', 4.5, '2025-12-07 16:31:41'),
(4, 'Honda City', 'Quảng Ninh', 'sedan', 'Honda', '92A-445.56', 4, 2, 900000.00, NULL, 'Budget Car Rental', 'AC,WiFi', NULL, 'available', 4.9, '2025-12-07 16:31:41'),
(5, 'Kia Carnival', 'Hồ Chí Minh', 'minivan', 'Kia', '60F-778.89', 7, 10, 1800000.00, NULL, 'Hertz Vietnam', 'AC,WiFi,Drinks', NULL, 'available', 4.9, '2025-12-07 16:31:41'),
(6, 'Toyota Fortuner', 'Cần Thơ', 'suv', 'Toyota', '51G-111.22', 7, 4, 1400000.00, 500000.00, 'GoCar', 'AC,Child seat', NULL, 'available', 4.5, '2025-12-07 16:31:41'),
(7, 'Ford Transit', 'Hà Nội', 'van', 'Ford', '29A-222.33', 16, 10, 2000000.00, 850000.00, 'Luxury Trans', 'AC,WiFi,Drinks', NULL, 'available', 4.9, '2025-12-07 16:31:41'),
(8, 'Mazda 3 Premium', 'Đà Nẵng', 'sedan', 'Mazda', '43A-567.89', 4, 3, 950000.00, NULL, 'Da Nang Car Rental', 'AC,WiFi,Bluetooth,Map', NULL, 'available', 4.8, '2025-12-16 08:15:42'),
(9, 'Hyundai SantaFe', 'Khánh Hòa', 'suv', 'Hyundai', '79A-123.99', 7, 4, 1300000.00, NULL, 'Nha Trang Tourist', 'AC,WiFi,Child seat', NULL, 'available', 4.7, '2025-12-16 08:15:42'),
(10, 'Mitsubishi Xpander', 'Kiên Giang', 'minivan', 'Mitsubishi', '68A-098.76', 7, 5, 1100000.00, 400000.00, 'Phu Quoc Travel', 'AC,WiFi,Drinks', NULL, 'available', 4.6, '2025-12-16 08:15:42'),
(11, 'Toyota Innova', 'Lâm Đồng', 'minivan', 'Toyota', '49A-112.23', 8, 6, 1000000.00, NULL, 'Da Lat Transport', 'AC,Map', NULL, 'available', 4.5, '2025-12-16 08:15:42'),
(12, 'Kia Cerato', 'Thừa Thiên Huế', 'sedan', 'Kia', '75A-334.55', 4, 3, 850000.00, NULL, 'Hue City Tour', 'AC,WiFi', NULL, 'available', 4.9, '2025-12-16 08:15:42'),
(13, 'VinFast Lux A2.0', 'Hải Phòng', 'sedan', 'VinFast', '15A-667.88', 4, 3, 1100000.00, NULL, 'Hai Phong Cars', 'AC,WiFi,Bluetooth', NULL, 'available', 5.0, '2025-12-16 08:15:42'),
(14, 'Ford Everest Titanium', 'Quảng Ninh', 'suv', 'Ford', '14A-998.11', 7, 4, 1600000.00, NULL, 'Ha Long Bay Services', 'AC,WiFi,Map,TV', NULL, 'available', 4.8, '2025-12-16 08:15:42'),
(15, 'Honda City RS', 'Cần Thơ', 'sedan', 'Honda', '65A-221.44', 4, 2, 800000.00, NULL, 'Mien Tay Trans', 'AC,WiFi', NULL, 'rented', 4.7, '2025-12-16 08:15:42'),
(16, 'Mercedes-Benz C300', 'Hà Nội', 'sedan', 'Mercedes', '30K-119.99', 4, 2, 2500000.00, NULL, 'Hanoi Luxury Cars', 'AC,WiFi,Bluetooth,Leather seats', NULL, 'available', 5.0, '2025-12-16 08:15:42'),
(17, 'Hyundai Solati', 'Hồ Chí Minh', 'van', 'Hyundai', '51B-555.66', 16, 12, 2200000.00, 900000.00, 'Saigon Tourist', 'AC,WiFi,TV,Karaoke', NULL, 'available', 4.6, '2025-12-16 08:15:42'),
(18, 'Toyota Corolla Cross', 'Đà Nẵng', 'suv', 'Toyota', '43A-777.12', 5, 4, 1200000.00, NULL, 'Central Car Rental', 'AC,WiFi', NULL, 'available', 4.8, '2025-12-16 08:15:42'),
(19, 'Kia Sedona', 'Khánh Hòa', 'minivan', 'Kia', '79A-444.22', 7, 8, 1900000.00, NULL, 'Cam Ranh Transfers', 'AC,WiFi,Automatic door', NULL, 'available', 4.9, '2025-12-16 08:15:42');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `homestay_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `homestay_id`, `created_at`) VALUES
(19, 1, 24, '2025-12-13 16:01:56');

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
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_code` (`booking_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_bookings_user_id` (`user_id`),
  ADD KEY `idx_bookings_status` (`status`),
  ADD KEY `idx_bookings_user_status` (`user_id`,`status`);

--
-- Indexes for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flights`
--
ALTER TABLE `flights`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `flight_code` (`flight_code`),
  ADD KEY `airline_id` (`airline_id`),
  ADD KEY `idx_flights_departure_airport` (`departure_airport`),
  ADD KEY `idx_flights_arrival_airport` (`arrival_airport`),
  ADD KEY `idx_flights_departure_time` (`departure_time`);

--
-- Indexes for table `flight_bookings`
--
ALTER TABLE `flight_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `homestays`
--
ALTER TABLE `homestays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `homestay_id` (`homestay_id`);

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
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_plate` (`license_plate`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `homestay_id` (`homestay_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `airlines`
--
ALTER TABLE `airlines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `booking_details`
--
ALTER TABLE `booking_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flights`
--
ALTER TABLE `flights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `flight_bookings`
--
ALTER TABLE `flight_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `homestays`
--
ALTER TABLE `homestays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
