-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for shopex
CREATE DATABASE IF NOT EXISTS `shopex` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `shopex`;

-- Dumping structure for table shopex.admin
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table shopex.admin: ~1 rows (approximately)
REPLACE INTO `admin` (`id`, `username`, `password`) VALUES
	(1, 'admin', 'admin123');

-- Dumping structure for table shopex.bank
CREATE TABLE IF NOT EXISTS `bank` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `card_number` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `cvv` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `member_name` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `bank_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table shopex.bank: ~2 rows (approximately)
REPLACE INTO `bank` (`id`, `bank_name`, `card_number`, `cvv`, `member_name`, `user_id`) VALUES
	(3, 'ZA0AuRQIXS1Q2kbh5KdaqnI2cG1SOVA2QWpnYUpSZ2RDNjRGbUE9PQ==', 'wZuy0W/qd4CxKCjGrIlE4FpWT2xDVWg0Y2xUbWRmNStGTVc0NVE9PQ==', 'evkWKaw/PZfnmQ0uHAdoODNUNjN1VEZvUG5WcjYraXpPT09Ib2c9PQ==', 'aiAxhW9XP+Dt25+M0X1WUklTcks3WlpvMjVkc2JyOXBxSGxzUmc9PQ==', 2),
	(4, 'N8Ft8RTdsIxug89G5E17vWdYUjNoMFlPelRRamd1K3RWU0NSc1E9PQ==', '7YG/DIB2uL4IdRSHqu3ZwDhpQVVNaTV5TkRKTVRFeFdkckNLYlE9PQ==', '3W76u/RP7yZmo+hzA+QTo3hleU1aQkRtYzFiWWZYN1VWY1BXZFE9PQ==', 'eGj3SKQP2Ec140mhRpVgp3JCOGYwU3dDMzV6TzhHS2RaS3I3Vnc9PQ==', 2);

-- Dumping structure for table shopex.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table shopex.cart: ~2 rows (approximately)
REPLACE INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
	(9, 2, 1, 1, '2025-05-07 07:15:58'),
	(10, 2, 2, 1, '2025-05-07 07:42:51');

-- Dumping structure for table shopex.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table shopex.categories: ~3 rows (approximately)
REPLACE INTO `categories` (`id`, `name`) VALUES
	(1, 'Elektronik'),
	(2, 'Pakaian'),
	(3, 'Makanan');

-- Dumping structure for table shopex.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `price` int NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table shopex.products: ~3 rows (approximately)
REPLACE INTO `products` (`id`, `name`, `price`, `stock`, `description`, `image`, `category_id`, `created_at`) VALUES
	(1, 'Smartphone XYZ', 2500000, 10, 'Smartphone dengan fitur lengkap.', 'smartphone.png', 1, '2025-05-04 11:39:10'),
	(2, 'Kaos Polos', 75000, 50, 'Kaos bahan katun.', 'kaos.png', 2, '2025-05-04 11:39:10'),
	(3, 'Keripik Kentang', 15000, 100, 'Snack renyah dan gurih.', 'keripik.png', 3, '2025-05-04 11:39:10');

-- Dumping structure for table shopex.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date` timestamp NOT NULL,
  `status` enum('pending','processed','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `total` decimal(10,0) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `bank_id` int NOT NULL,
  `recipient_name` varchar(512) NOT NULL,
  `recipient_email` varchar(512) NOT NULL,
  `recipient_address` varchar(512) NOT NULL,
  `recipient_postal_code` varchar(512) NOT NULL,
  `recipient_phone` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table shopex.transactions: ~5 rows (approximately)
REPLACE INTO `transactions` (`id`, `user_id`, `date`, `status`, `total`, `created_at`, `bank_id`, `recipient_name`, `recipient_email`, `recipient_address`, `recipient_postal_code`, `recipient_phone`) VALUES
	(2, 2, '2025-05-05 08:04:50', 'pending', 2500000, '2025-05-05 08:04:50', 3, '0', '0', '0', '0', '0'),
	(3, 2, '2025-05-05 08:06:26', 'pending', 2650000, '2025-05-05 08:06:26', 4, '0', '0', '0', '0', '0'),
	(4, 2, '2025-05-06 06:05:56', 'completed', 7590000, '2025-05-06 06:05:56', 4, '0', '0', '0', '0', '0'),
	(5, 2, '2025-05-06 06:45:29', 'completed', 2500000, '2025-05-06 06:45:29', 3, 'qyiTVp659FefKkWAw5vV9UxSVElSNkRBSXNoais3Z0JONExJeVE9PQ==', 'gK5BaGONiau15epBXoQ+eWN0VndSMk1ORnArelAwdUh3ajJJNWRTa3M5R25qNi8vSXRnVW1YcEx0OU09', '+s0/Ei1mHCnbDcDoOFSLGHpxVTY2UklYT09ra1p1TTkwZXlOMkE9PQ==', 'q6VbvnOHriS1VVNwGm2FMTFKcHlaUXJTL2FQUUh1VWZBVUV3UEE9PQ==', 'xQJj6RD/SzJ/sidOkNJf0Hl3cXg4czVZWGtMYVJJN1RpOFN1ZFE9PQ=='),
	(6, 3, '2025-05-29 20:10:09', 'pending', 2500000, '2025-05-29 20:10:09', 0, '5y20pkOy+Ksrab8j/NtI8E5kdHNRK1dwN1NiNVFCazBuVCtIMHc9PQ==', 'j7XmHQGFzyKuWVcD4hnfmXpVM0xucHBsSXRPNUJocVRrTDVIeURiSVJZWXY3NXB6cTUrL3N2QnJFaVE9', 'ILikwReSEPlV8uYh9mOiyStpUUwyZVc0M0pvUmFwb054QmtpWGc9PQ==', 'AbaIqmsn8MfTMFk/P3erv2JLSjQ2cWZRdnV6R1VWOTh5U3V1a1E9PQ==', '1cBSctblFL5iTfNwr2FqxWpKZ3VqNmlLZEVZZllZRFpOOUFWckE9PQ==');

-- Dumping structure for table shopex.transaction_details
CREATE TABLE IF NOT EXISTS `transaction_details` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `total` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_id` (`transaction_id`),
  KEY `produk_id` (`product_id`),
  CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table shopex.transaction_details: ~8 rows (approximately)
REPLACE INTO `transaction_details` (`id`, `transaction_id`, `product_id`, `total`, `price`) VALUES
	(1, 2, 1, 1, 2500000.00),
	(2, 3, 1, 1, 2500000.00),
	(3, 3, 2, 2, 75000.00),
	(4, 4, 3, 1, 15000.00),
	(5, 4, 1, 3, 2500000.00),
	(6, 4, 2, 1, 75000.00),
	(7, 5, 1, 1, 2500000.00),
	(8, 6, 1, 1, 2500000.00);

-- Dumping structure for table shopex.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `address` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table shopex.users: ~4 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `address`, `phone`) VALUES
	(1, 'fadli', 'fadli@gmail.com', '$2y$10$FfP58.1AE3Z2VdNcZ8qDRen2XRD5SeWgqOtsedHKHj8gJDKrIUOo2', '2025-05-04 13:10:36', 'Jalan Sadang, Cibiru, Cileunyi, Kabupaten Bandung', '08123456789'),
	(2, 'Ziyad', 'ziyad@gmail.com', '$2y$10$L607uxy67Wfm9HFSVma/uekKxYi6AqhPZI0yyxL4VSlXEfoillm7G', '2025-05-04 17:26:47', '07JAUeb9AMKHrvUlY3pZWVhET1dXUXpVQXpyZUw2MjA2S2ovNWc9PQ==', 'DFcFsDDA1JEcURSnIv3JWnlOU3F5ZkQ5cVU1ZjRvTlJXVWxPNlE9PQ=='),
	(3, 'val', 'rifalsh21@gmail.com', '$2y$10$MUi1gy1miPqxbObA.KPHxeiQ7rSZvP3m10AamnxMVlKYA1rMEtXS.', '2025-05-10 05:38:16', 'tMxKN64QyJFSGghZ5Soe/FBGemVmL1ByVDNZeURabXR4SkdPb1E9PQ==', '+2QcX5I483ycRw4UZVGwv0NnNkRoTTBNRmVNVkVmUnA2c3pZZVE9PQ=='),
	(4, 'admin', 'admin@gmail.com', '$2y$10$ww6gqMXP8hAExnzgp7.ET.TaGe2iHip.RV5ZVR9ofnYQLen4VoPdG', '2025-05-19 03:37:38', 'v4B1aovYqhvBAg12gZK+TEFhM2E3a3JDVW40OFBUbFdWNHpUM1E9PQ==', '9XnMP/oWCICa212olLLgeXRnWVRnRmVXNm1mMWRhTzRpU1YzQ0E9PQ==');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
