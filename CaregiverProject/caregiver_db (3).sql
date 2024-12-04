-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 04, 2024 at 03:06 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `caregiver_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

DROP TABLE IF EXISTS `contracts`;
CREATE TABLE IF NOT EXISTS `contracts` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `caregiver_username` varchar(100) DEFAULT NULL,
  `member_username` varchar(100) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `daily_hours` int NOT NULL,
  `rating` tinyint(1) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `total_hours` int NOT NULL DEFAULT '0',
  `total_cost` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `caregiver_username` (`caregiver_username`) USING BTREE,
  KEY `member_username` (`member_username`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `caregiver_username`, `member_username`, `start_date`, `end_date`, `daily_hours`, `rating`, `status`, `total_hours`, `total_cost`) VALUES
(2, 'test', 'qwe', '2024-12-20', '2024-12-28', 20, NULL, 'completed', 0, 0),
(3, 'qwe', 'qwe', '2024-12-04', '2024-12-05', 5, 3, 'completed', 0, 0),
(4, 'test', NULL, '2024-12-04', '2024-12-06', 7, NULL, 'accepted', 0, 0),
(5, 'test', 'qwe', '2024-12-18', '2025-01-02', 2, 5, 'completed', 0, 0),
(6, 'test', 'qwe', '2025-01-08', '2025-01-10', 1, 1, 'completed', 0, 0),
(7, 'test', 'qwe', '2024-12-04', '2024-12-05', 1, 1, 'completed', 0, 0),
(8, 'test', 'asd', '2024-12-03', '2024-12-05', 3, 2, 'completed', 9, 270),
(9, 'test', 'asd', '2024-12-04', '2024-12-05', 2, 4, 'completed', 4, 120),
(10, 'test', 'asd', '2024-12-10', '2024-12-11', 5, NULL, 'declined', 10, 300),
(11, 'test', 'asd', '2024-12-24', '2024-12-25', 5, NULL, 'accepted', 10, 300),
(12, 'test', 'asd', '2024-12-03', '2024-12-05', 3, NULL, 'accepted', 9, 270),
(13, 'test', 'asd', '2024-12-09', '2024-12-11', 1, NULL, 'accepted', 3, 90),
(14, 'test', 'asd', '2024-12-01', '2024-12-04', 2, NULL, 'accepted', 8, 240),
(15, 'test', 'asd', '2024-12-02', '2024-12-11', 4, NULL, 'accepted', 40, 1200),
(16, 'test', 'asd', '2024-12-03', '2024-12-06', 3, NULL, 'pending', 12, 360);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `available_time` int NOT NULL,
  `care_dollars` int NOT NULL DEFAULT '2000',
  `is_caregiver` tinyint(1) NOT NULL DEFAULT '0',
  `avg_rating` decimal(3,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `username`, `password`, `address`, `phone`, `available_time`, `care_dollars`, `is_caregiver`, `avg_rating`) VALUES
(19, 'test', 'test', '$2y$10$Vdjc5MEVPJgc/dsXpxgPtOr5VAcq26O8.Tldzx1.RzNe89WFFmus.', 'qwe', '123', -198, 2390, 1, 3),
(20, 'qwe', 'qwe', '$2y$10$0K7tfN3klzUPcz/LdiFnxec02oLkR2X8gR4asT91/g8nJgRa8SKAq', 'qwe', '123', 113, 2000, 1, 3),
(21, 'asd', 'asd', '$2y$10$BFpdwJN7MzB4TrunrBE3xukJiJrvdjk.fXfAf17aoGgjinC2LkOfK', 'qwe', '123', 123, 1610, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

DROP TABLE IF EXISTS `parents`;
CREATE TABLE IF NOT EXISTS `parents` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_username` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `age` int DEFAULT NULL,
  `health_needs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_username` (`member_username`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `member_username`, `name`, `age`, `health_needs`) VALUES
(12, 'qwe', 'qwe', 123, 'qwe'),
(11, 'test', 'test', 123, 'test'),
(13, 'asd', 'asd', 123, 'asd');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
