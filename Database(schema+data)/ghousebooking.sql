-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jun 14, 2025 at 04:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ghousebooking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_cred`
--

CREATE TABLE `admin_cred` (
  `sr_no` int(11) NOT NULL,
  `admin_name` varchar(150) NOT NULL,
  `admin_pass` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_cred`
--

INSERT INTO `admin_cred` (`sr_no`, `admin_name`, `admin_pass`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `booking_details`
--

CREATE TABLE `booking_details` (
  `sr_no` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `guesthouse_name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `total_pay` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `phonenum` varchar(100) NOT NULL,
  `address` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_details`
--

INSERT INTO `booking_details` (`sr_no`, `booking_id`, `guesthouse_name`, `price`, `total_pay`, `user_name`, `phonenum`, `address`) VALUES
(22, 52, 'guesthouse3', 32, 64, 'hass', '11111', 'asdd'),
(23, 53, 'guesthouse1', 123, 246, 'charbel', '76325233', 'batroun'),
(24, 54, 'guesthouse', 123, 123, 'charbel', '76325233', 'batroun'),
(25, 55, 'guesthouse', 123, 123, 'charbel', '76325233', 'batroun'),
(26, 56, 'guesthouse3', 123, 123, 'charbel', '76325233', 'batroun'),
(27, 57, 'guesthouse3', 123, 123, 'charbel', '71717171', '123'),
(28, 58, 'byblos', 123, 123, 'charbel', '12348', 'batroun');

-- --------------------------------------------------------

--
-- Table structure for table `booking_order`
--

CREATE TABLE `booking_order` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `guesthouse_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `arrival` int(11) NOT NULL DEFAULT 0,
  `order_id` varchar(150) NOT NULL,
  `datentime` datetime NOT NULL DEFAULT current_timestamp(),
  `paid` int(11) NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_order`
--

INSERT INTO `booking_order` (`booking_id`, `user_id`, `guesthouse_id`, `check_in`, `check_out`, `arrival`, `order_id`, `datentime`, `paid`, `is_deleted`) VALUES
(52, 56, 19, '2024-12-22', '2024-12-24', 0, 'ORD_568912572', '2024-12-21 10:11:43', 1, 0),
(56, 57, 20, '2024-12-22', '2024-12-23', 0, 'ORD_574923464', '2024-12-21 13:07:56', 1, 0),
(57, 58, 20, '2024-12-22', '2024-12-23', 0, 'ORD_585677472', '2024-12-21 15:31:42', 1, 0),
(58, 60, 20, '2024-12-23', '2024-12-24', 0, 'ORD_603355975', '2024-12-21 16:07:05', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `carousel`
--

CREATE TABLE `carousel` (
  `sr_no` int(11) NOT NULL,
  `image` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carousel`
--

INSERT INTO `carousel` (`sr_no`, `image`) VALUES
(15, 'IMG_19610.jpg'),
(16, 'IMG_25369.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `contact_details`
--

CREATE TABLE `contact_details` (
  `sr_no` int(11) NOT NULL,
  `address` varchar(50) NOT NULL,
  `gmap` varchar(100) NOT NULL,
  `pn1` bigint(20) NOT NULL,
  `pn2` bigint(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fb` varchar(100) NOT NULL,
  `insta` varchar(100) NOT NULL,
  `tw` varchar(100) NOT NULL,
  `iframe` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_details`
--

INSERT INTO `contact_details` (`sr_no`, `address`, `gmap`, `pn1`, `pn2`, `email`, `fb`, `insta`, `tw`, `iframe`) VALUES
(1, 'XYZ, Beirut, Lebanon', 'https://goo.gl/maps/T1YM8d4fJsoczstd6ss', 9610000000, 961010101010, 'emaill@gmail.com', 'https://www.facebook.com/', 'https://www.instagram.com/', 'https://www.twitter.com/', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13191.795399718421!2d35.65399065487359!3d34.24984880615875!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151f58b24cc6e709:0xfd68c47a5405dcad!2sBatroun!5e0!3m2!1sen!2slb!4v1727594232497!5m2!1sen!2slb');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `icon`, `name`, `description`) VALUES
(20, 'IMG_98846.svg', 'Heater', ''),
(21, 'IMG_98045.svg', 'AC', ''),
(22, 'IMG_38594.svg', 'Wifi', ''),
(23, 'IMG_43639.svg', 'Telivision', ''),
(24, 'IMG_53267.svg', 'massage', '');

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `name`) VALUES
(24, '1 bathroom'),
(25, '2 bathrooms'),
(28, '1 Spacious Living Room'),
(29, 'Outdoor Spaces'),
(30, 'Kitchen');

-- --------------------------------------------------------

--
-- Table structure for table `guesthouses`
--

CREATE TABLE `guesthouses` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `area` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `adult` int(11) NOT NULL,
  `children` int(11) NOT NULL,
  `description` varchar(350) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `removed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guesthouses`
--

INSERT INTO `guesthouses` (`id`, `name`, `area`, `price`, `quantity`, `adult`, `children`, `description`, `status`, `removed`) VALUES
(20, 'byblos', 123, 123, 123, 10, 10, 'guest house', 1, 0),
(21, 'batroun', 123, 150, 123, 10, 10, 'test description', 1, 0),
(22, 'sayda', 123, 200, 2, 1, 2, '123', 0, 0),
(23, 'sour', 123, 20, 1, 2, 3, 'asd', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `guesthouse_facilities`
--

CREATE TABLE `guesthouse_facilities` (
  `guesthouse_id` int(11) NOT NULL,
  `facilities_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guesthouse_facilities`
--

INSERT INTO `guesthouse_facilities` (`guesthouse_id`, `facilities_id`) VALUES
(13, 20),
(13, 21),
(13, 22),
(14, 20),
(14, 21),
(14, 22),
(14, 23),
(19, 21),
(20, 20),
(20, 21),
(20, 22),
(21, 20),
(21, 23),
(22, 21),
(23, 20);

-- --------------------------------------------------------

--
-- Table structure for table `guesthouse_features`
--

CREATE TABLE `guesthouse_features` (
  `guesthouse_id` int(11) NOT NULL,
  `features_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guesthouse_features`
--

INSERT INTO `guesthouse_features` (`guesthouse_id`, `features_id`) VALUES
(13, 24),
(13, 25),
(13, 28),
(14, 25),
(14, 29),
(14, 30),
(19, 24),
(20, 24),
(20, 28),
(21, 24),
(21, 25),
(22, 24),
(23, 24),
(23, 28);

-- --------------------------------------------------------

--
-- Table structure for table `guesthouse_images`
--

CREATE TABLE `guesthouse_images` (
  `sr_no` int(11) NOT NULL,
  `image` varchar(150) NOT NULL,
  `thumb` tinyint(4) NOT NULL DEFAULT 0,
  `guesthouse_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guesthouse_images`
--

INSERT INTO `guesthouse_images` (`sr_no`, `image`, `thumb`, `guesthouse_id`) VALUES
(19, 'IMG_15846.jpg', 1, 13),
(22, 'IMG_49565.jpeg', 1, 14),
(23, 'IMG_65372.webp', 1, 19),
(24, 'IMG_69773.jpeg', 1, 20),
(25, 'IMG_22760.jpeg', 0, 21),
(28, 'IMG_94309.webp', 1, 21),
(29, 'IMG_21429.webp', 1, 22);

-- --------------------------------------------------------

--
-- Table structure for table `rating_review`
--

CREATE TABLE `rating_review` (
  `sr_no` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `guesthouse_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` text NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `datentime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating_review`
--

INSERT INTO `rating_review` (`sr_no`, `booking_id`, `guesthouse_id`, `user_id`, `rating`, `review`, `seen`, `datentime`) VALUES
(4, 14, 5, 7, 5, '1asdlkfj Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero', 1, '2022-08-20 00:22:25'),
(5, 12, 3, 8, 1, '3asdlkfj Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos voluptate vero sed tempore illo atque beatae asperiores, adipisci dicta quia nisi voluptates impedit perspiciatis, nobis libero', 1, '2022-08-20 00:22:34'),
(8, 0, 13, 51, 3, 'very nice', 0, '2024-12-21 00:41:13'),
(9, 0, 21, 57, 3, 'nice', 0, '2024-12-21 13:06:53'),
(10, 0, 22, 60, 4, 'good', 1, '2024-12-21 16:07:54');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `sr_no` int(11) NOT NULL,
  `site_title` varchar(50) NOT NULL,
  `site_about` varchar(250) NOT NULL,
  `shutdown` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`sr_no`, `site_title`, `site_about`, `shutdown`) VALUES
(1, 'GuestHouse', 'Looking for a cozy and welcoming place to stay? Discover our guesthouses, where comfort meets convenience. Whether you&#039;re traveling for business or leisure, we offer a range of accommodations to suit your needs. Join us and make your stay unforg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `team_details`
--

CREATE TABLE `team_details` (
  `sr_no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `picture` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_details`
--

INSERT INTO `team_details` (`sr_no`, `name`, `picture`) VALUES
(15, 'John', 'IMG_20319.jpg'),
(16, 'Fady', 'IMG_79331.jpg'),
(17, 'Marwan', 'IMG_66041.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_cred`
--

CREATE TABLE `user_cred` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `address` varchar(120) NOT NULL,
  `phonenum` varchar(100) NOT NULL,
  `pincode` int(11) NOT NULL,
  `dob` date NOT NULL,
  `profile` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `is_verified` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `datentime` datetime NOT NULL DEFAULT current_timestamp(),
  `verification_code` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_cred`
--

INSERT INTO `user_cred` (`id`, `name`, `email`, `address`, `phonenum`, `pincode`, `dob`, `profile`, `password`, `is_verified`, `status`, `datentime`, `verification_code`) VALUES
(55, 'mary', 'mary@test.com', '123', '123456', 321, '2006-11-27', 'IMG_85043.jpeg', '$2y$10$Gzb.0wKIHKzR4.Idj4YsuOzht5hhmhDZVgXmpgkdm6cj7ELXeAV8O', 0, 1, '2024-12-20 23:43:14', NULL),
(56, 'hass', 'hassane.jaber8@gmail.com', 'asdd', '11111', 1234, '2006-11-08', 'IMG_82179.jpeg', '$2y$10$UIsyLQaadeN/sRn5PhulVux5dngGpm0hyC4xzQ9mq3HSp8nRKl8GC', 1, 1, '2024-12-21 08:19:56', NULL),
(59, 'fady', 'fady.test@gmail.com', 'address', '123', 123, '2006-12-04', 'IMG_54130.jpeg', '$2y$10$JE.2A2eGOVfqvigEpzB6xOkXUAC71bkOKExgA2eUTPAAHETE3IGJy', 0, 1, '2024-12-21 15:44:39', NULL),
(61, 'Charbel Khabbaz', 'charbelkhabbaz@test.com', 'Batroun', '00000001', 1212, '2007-06-05', 'IMG_28909.jpeg', '$2y$10$Q6a9NQ9MP.leozQzDwEF8OVDGIhA.joiZJz9texcpiWiKf6WnNuuW', 0, 1, '2025-06-14 15:25:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_queries`
--

CREATE TABLE `user_queries` (
  `sr_no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` varchar(500) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `seen` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_queries`
--

INSERT INTO `user_queries` (`sr_no`, `name`, `email`, `subject`, `message`, `date`, `seen`) VALUES
(152, 'charbel', 'ask.tjwebdev@gmail.com', 'asdasd', 'fdsaas', '2024-10-05', 1),
(153, 'Mohamad', 'charbelkhabbaz@test.com', 'ddddd', '12341', '2024-12-08', 1),
(154, 'charbel', 'charbelkhabbaz@test.com', 'asdasd', 'qwd', '2024-12-20', 1),
(155, 'test', 'ask.tjwebdev@gmail.com', 'test', 'test', '2024-12-21', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_cred`
--
ALTER TABLE `admin_cred`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `booking_order`
--
ALTER TABLE `booking_order`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `guesthouse_id` (`guesthouse_id`);

--
-- Indexes for table `carousel`
--
ALTER TABLE `carousel`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `contact_details`
--
ALTER TABLE `contact_details`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guesthouses`
--
ALTER TABLE `guesthouses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guesthouse_facilities`
--
ALTER TABLE `guesthouse_facilities`
  ADD PRIMARY KEY (`guesthouse_id`,`facilities_id`),
  ADD KEY `facilities_id_idx` (`facilities_id`),
  ADD KEY `guesthouse_id_idx` (`guesthouse_id`);

--
-- Indexes for table `guesthouse_features`
--
ALTER TABLE `guesthouse_features`
  ADD PRIMARY KEY (`guesthouse_id`,`features_id`),
  ADD KEY `features_id_idx` (`features_id`),
  ADD KEY `guesthouse_id_idx` (`guesthouse_id`);

--
-- Indexes for table `guesthouse_images`
--
ALTER TABLE `guesthouse_images`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `guesthouse_id` (`guesthouse_id`);

--
-- Indexes for table `rating_review`
--
ALTER TABLE `rating_review`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `team_details`
--
ALTER TABLE `team_details`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `user_cred`
--
ALTER TABLE `user_cred`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_queries`
--
ALTER TABLE `user_queries`
  ADD PRIMARY KEY (`sr_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_cred`
--
ALTER TABLE `admin_cred`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking_details`
--
ALTER TABLE `booking_details`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `booking_order`
--
ALTER TABLE `booking_order`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `carousel`
--
ALTER TABLE `carousel`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `contact_details`
--
ALTER TABLE `contact_details`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `guesthouses`
--
ALTER TABLE `guesthouses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `guesthouse_images`
--
ALTER TABLE `guesthouse_images`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `rating_review`
--
ALTER TABLE `rating_review`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `team_details`
--
ALTER TABLE `team_details`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_cred`
--
ALTER TABLE `user_cred`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `user_queries`
--
ALTER TABLE `user_queries`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
