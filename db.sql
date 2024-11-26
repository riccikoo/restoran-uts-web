-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for uts-web
CREATE DATABASE IF NOT EXISTS `uts-web` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `uts-web`;

-- Dumping structure for table uts-web.bill
CREATE TABLE IF NOT EXISTS `bill` (
  `bill_id` varchar(20) NOT NULL DEFAULT '',
  `table_id` int(11) DEFAULT NULL,
  `bill_name` varchar(50) DEFAULT NULL,
  `bill_total` int(11) DEFAULT NULL,
  `bill_status` enum('pending','paid','cancel') DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`bill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table uts-web.bill: ~30 rows (approximately)
REPLACE INTO `bill` (`bill_id`, `table_id`, `bill_name`, `bill_total`, `bill_status`, `created_at`) VALUES
	('011124-0001', 1, 'Toni', 144228, 'paid', '2024-11-01 12:10:00'),
	('011124-0002', 2, 'Ahmad', 62050, 'pending', '2024-11-01 14:22:00'),
	('011124-0003', 3, 'Budi', 32000, 'paid', '2024-11-01 15:15:00'),
	('021124-0001', 1, 'Eka', 76500, 'cancel', '2024-11-02 09:30:00'),
	('021124-0002', 2, 'Dewi', 50500, 'paid', '2024-11-02 10:45:00'),
	('021124-0003', 3, 'Sari', 92500, 'paid', '2024-11-02 13:00:00'),
	('031124-0001', 1, 'Joko', 155000, 'pending', '2024-11-03 11:25:00'),
	('031124-0002', 2, 'Fitri', 45000, 'paid', '2024-11-03 12:40:00'),
	('031124-0003', 3, 'Wawan', 111000, 'cancel', '2024-11-03 16:20:00'),
	('041124-0001', 1, 'Tini', 54000, 'paid', '2024-11-04 08:15:00'),
	('041124-0002', 2, 'Rina', 82500, 'paid', '2024-11-04 10:25:00'),
	('041124-0003', 3, 'Luki', 153000, 'pending', '2024-11-04 12:35:00'),
	('051124-0001', 1, 'Dani', 30000, 'paid', '2024-11-05 09:05:00'),
	('051124-0002', 2, 'Yani', 125500, 'paid', '2024-11-05 10:30:00'),
	('051124-0003', 3, 'Fajar', 80500, 'cancel', '2024-11-05 14:10:00'),
	('061124-0001', 1, 'Ayu', 64000, 'paid', '2024-11-06 11:20:00'),
	('061124-0002', 2, 'Rama', 87000, 'pending', '2024-11-06 12:30:00'),
	('061124-0003', 3, 'Susi', 148000, 'paid', '2024-11-06 16:45:00'),
	('071124-0001', 1, 'Hana', 92000, 'cancel', '2024-11-07 08:10:00'),
	('071124-0002', 2, 'Dino', 132000, 'paid', '2024-11-07 11:00:00'),
	('071124-0003', 3, 'Agus', 34000, 'pending', '2024-11-07 14:05:00'),
	('081124-0001', 1, 'Sita', 58000, 'paid', '2024-11-08 10:30:00'),
	('081124-0002', 2, 'Nina', 78000, 'cancel', '2024-11-08 12:50:00'),
	('081124-0003', 3, 'Rudi', 62000, 'paid', '2024-11-08 13:55:00'),
	('091124-0001', 1, 'Udin', 49000, 'paid', '2024-11-09 09:20:00'),
	('091124-0002', 2, 'Faisal', 74000, 'pending', '2024-11-09 11:40:00'),
	('091124-0003', 3, 'Ilham', 98000, 'cancel', '2024-11-09 16:30:00'),
	('101124-0001', 1, 'Tara', 68000, 'paid', '2024-11-10 10:05:00'),
	('101124-0002', 2, 'Lina', 55000, 'paid', '2024-11-10 11:10:00'),
	('101124-0003', 3, 'Yoga', 119000, 'pending', '2024-11-10 14:20:00'),
	('131124-0001', 2, 'RicoFA', 37000, 'pending', '2024-11-13 18:07:07'),
	('131124-0002', 1, 'RicoF', 49000, 'paid', '2024-11-13 18:16:41'),
	('141124-0001', 1, 'rapli', 33000, 'pending', '2024-11-14 08:41:54');

-- Dumping structure for table uts-web.bill_details
CREATE TABLE IF NOT EXISTS `bill_details` (
  `bill_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_id` varchar(50) NOT NULL DEFAULT '',
  `menu_id` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`bill_detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table uts-web.bill_details: ~50 rows (approximately)
REPLACE INTO `bill_details` (`bill_detail_id`, `bill_id`, `menu_id`, `quantity`, `price`) VALUES
	(1, '011124-0001', '01-002', 2, 10000),
	(2, '011124-0001', '02-005', 3, 3000),
	(3, '011124-0002', '01-009', 4, 5000),
	(4, '011124-0002', '02-010', 1, 7000),
	(5, '011124-0003', '01-004', 1, 17000),
	(6, '021124-0001', '01-008', 5, 2000),
	(7, '021124-0001', '01-002', 2, 10000),
	(8, '021124-0002', '02-009', 3, 5000),
	(9, '021124-0002', '01-010', 1, 12000),
	(10, '021124-0003', '01-011', 2, 15000),
	(11, '031124-0001', '02-010', 1, 7000),
	(12, '031124-0001', '01-009', 4, 5000),
	(13, '031124-0002', '01-002', 3, 10000),
	(14, '031124-0002', '01-012', 1, 10000),
	(15, '031124-0003', '02-005', 3, 3000),
	(16, '041124-0001', '01-002', 2, 10000),
	(17, '041124-0001', '01-011', 2, 15000),
	(18, '041124-0002', '01-008', 5, 2000),
	(19, '041124-0002', '01-004', 1, 17000),
	(20, '041124-0003', '02-009', 2, 5000),
	(21, '051124-0001', '02-010', 1, 7000),
	(22, '051124-0001', '01-004', 1, 17000),
	(23, '051124-0002', '01-009', 3, 5000),
	(24, '051124-0002', '02-005', 2, 3000),
	(25, '051124-0003', '01-010', 1, 12000),
	(26, '061124-0001', '01-011', 2, 15000),
	(27, '061124-0001', '01-008', 4, 2000),
	(28, '061124-0002', '01-002', 3, 10000),
	(29, '061124-0002', '01-004', 2, 17000),
	(30, '061124-0003', '02-009', 3, 5000),
	(31, '071124-0001', '01-009', 2, 5000),
	(32, '071124-0001', '02-010', 2, 7000),
	(33, '071124-0002', '01-002', 4, 10000),
	(34, '071124-0002', '01-012', 1, 10000),
	(35, '071124-0003', '02-005', 2, 3000),
	(36, '081124-0001', '01-008', 3, 2000),
	(37, '081124-0001', '01-011', 1, 15000),
	(38, '081124-0002', '01-002', 2, 10000),
	(39, '081124-0002', '02-009', 3, 5000),
	(40, '081124-0003', '01-004', 2, 17000),
	(41, '091124-0001', '01-009', 2, 5000),
	(42, '091124-0001', '02-010', 3, 7000),
	(43, '091124-0002', '01-008', 2, 2000),
	(44, '091124-0002', '01-011', 1, 15000),
	(45, '091124-0003', '01-004', 3, 17000),
	(46, '101124-0001', '01-012', 1, 10000),
	(47, '101124-0001', '02-005', 2, 3000),
	(48, '101124-0002', '01-009', 1, 5000),
	(49, '101124-0002', '02-010', 2, 7000),
	(50, '101124-0003', '01-008', 4, 2000),
	(51, '131124-0001', '01-012', 1, 10000),
	(52, '131124-0001', '01-011', 1, 15000),
	(53, '131124-0001', '01-010', 1, 12000),
	(54, '131124-0002', '01-010', 3, 12000),
	(55, '131124-0002', '01-012', 1, 10000),
	(56, '131124-0002', '02-005', 1, 3000),
	(57, '141124-0001', '02-005', 2, 3000),
	(58, '141124-0001', '01-013', 1, 12000),
	(59, '141124-0001', '01-012', 1, 10000),
	(60, '141124-0001', '02-009', 1, 5000);

-- Dumping structure for table uts-web.menu
CREATE TABLE IF NOT EXISTS `menu` (
  `menu_id` varchar(50) NOT NULL DEFAULT '',
  `menu_name` varchar(30) DEFAULT NULL,
  `menu_desc` varchar(50) DEFAULT NULL,
  `menu_price` int(11) DEFAULT NULL,
  `menu_status` enum('available','unavaliable') DEFAULT 'unavaliable',
  `menu_photo` varchar(50) DEFAULT '/image/default.png',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table uts-web.menu: ~11 rows (approximately)
REPLACE INTO `menu` (`menu_id`, `menu_name`, `menu_desc`, `menu_price`, `menu_status`, `menu_photo`) VALUES
	('01-002', 'Mie Ayam Cihuyy', 'HMMM ENNAKNYOO', 10000, '', 'image/01-002.jpe'),
	('01-004', 'Seblak Cihuy', 'HMMMMMMMM PEDAS NYOO', 17000, 'available', 'image/01-004.jpeg'),
	('01-008', 'Aqil Strawberry Luxury', 'CIHUUUUUYYYYYY🤣', 2000, 'available', 'image/01-008.png'),
	('01-009', 'Cilok BAPRI', 'HMM LEMAK NYOOO', 5000, 'available', 'image/01-009.webp'),
	('01-011', 'Sotoshop', 'HMMMMMMMMM KOYAA NYOOOOO', 15000, 'available', 'image/01-011.jpg'),
	('01-012', 'Basreng Cihuyyyyy', 'HMMMMMMM RADANG NYOOO', 10000, 'available', 'image/01-012.jpg'),
	('01-013', 'Ketoprak', 'Ketoprak gurih', 12000, 'available', 'image/01-013.webp'),
	('02-005', 'Es teh manis anget', 'HMMMMMM DEMAM NYOOOOOO', 3000, 'available', 'image/02-005.png'),
	('02-009', 'Cendol', 'HMMM SEGAR NYO', 5000, 'available', 'image/02-009.jpe'),
	('02-010', 'Capucino Cin Aww~~', 'HMMMMM CAFEINE NYOO', 7000, 'available', 'image/02-010.jpeg'),
	('02-011', 'Es teh manis anget', 'CIHUUUUUYYYYYY🤣', 12000, 'available', 'image/02-011.png');

-- Dumping structure for table uts-web.tables
CREATE TABLE IF NOT EXISTS `tables` (
  `table_id` int(11) NOT NULL AUTO_INCREMENT,
  `table_number` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`table_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table uts-web.tables: ~3 rows (approximately)
REPLACE INTO `tables` (`table_id`, `table_number`) VALUES
	(1, '001'),
	(2, '002'),
	(3, '003');

-- Dumping structure for table uts-web.user
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(30) NOT NULL,
  `user_name` varchar(50) NOT NULL DEFAULT '0',
  `user_level` enum('Staff','Admin') NOT NULL DEFAULT 'Staff',
  `status` enum('Active','NonActive') NOT NULL DEFAULT 'NonActive',
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table uts-web.user: ~6 rows (approximately)
REPLACE INTO `user` (`user_id`, `user_email`, `user_password`, `user_name`, `user_level`, `status`, `created_at`) VALUES
	('20241029-0003', 'ricofadlialfiansyah09@gmail.com', '12345', 'ric.cikoo', 'Staff', 'Active', '2024-10-29'),
	('20241029-0004', 'aliyy@gmail.com', '12345', 'aliyy', 'Admin', 'Active', '2024-10-29'),
	('20241029-0005', 'rahadrin@gmail.com', 'ricogasing', 'hikaru', 'Staff', 'Active', '2024-10-29'),
	('20241110-0001', 'ciko@gmail.com', '11111', 'ric.cikoo', 'Admin', 'Active', '2024-11-10'),
	('20241113-0001', 'ciko@gmail.com', '123', 'Rico', 'Staff', 'NonActive', '2024-11-14'),
	('20241113-0002', 'ciko1@gmail.com', '123', 'Rico', 'Admin', 'Active', '2024-11-14');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
