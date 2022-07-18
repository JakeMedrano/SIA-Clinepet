-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2022 at 05:25 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clinipets`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `request_date` date NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `customer_id`, `product_id`, `request_date`, `status`) VALUES
(18, 14, 30, '2022-07-22', 'approve'),
(19, 16, 33, '2022-07-21', 'decline'),
(22, 13, 29, '2022-07-28', 'approve'),
(24, 21, 31, '2022-07-21', 'decline');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `customer_id`, `product_id`, `quantity`) VALUES
(11, 3, 1, 1),
(12, 4, 7, 1),
(26, 8, 12, 1),
(60, 14, 38, 1),
(61, 14, 39, 1),
(62, 14, 43, 1),
(63, 14, 40, 1),
(64, 15, 39, 1),
(65, 15, 40, 1),
(66, 15, 43, 1),
(67, 15, 44, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'DOG FOOD'),
(2, 'CAT FOOD'),
(5, 'DOG SOAP'),
(6, 'CAT SOAP');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `auth_type` enum('app','third_party') DEFAULT NULL,
  `oauth_provider` enum('facebook','google') DEFAULT NULL,
  `oauth_uid` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `auth_type`, `oauth_provider`, `oauth_uid`, `username`, `password`, `name`) VALUES
(1, NULL, NULL, NULL, 'sdfs', '1e565d06b8e38b36eb07696bd540337f', 'sdf'),
(2, NULL, NULL, NULL, 'wefwe', 'e85f43d2778a65f04d0fa897b4aa5113', 'ewfwf'),
(3, NULL, NULL, NULL, 'test', '098f6bcd4621d373cade4e832627b4f6', 'test'),
(4, NULL, NULL, NULL, 'test', '202cb962ac59075b964b07152d234b70', 'test'),
(5, NULL, NULL, NULL, 'Abdul', 'fcea920f7412b5da7be0cf42b8c93759', 'Abdul'),
(6, NULL, NULL, NULL, 'Abdul123', '827ccb0eea8a706c4c34a16891f84e7b', 'Abdul123'),
(7, NULL, NULL, NULL, 'Abdul1', '7706e268aad4a1a7a952564bfe95d408', 'Abdul1'),
(8, NULL, NULL, NULL, 'pamela', '669ffc150d1f875819183addfc842cab', 'pamela'),
(9, NULL, NULL, NULL, 'jake', '1200cf8ad328a60559cf5e7c5f46ee6d', 'jakeabriam@gmail.com'),
(10, NULL, NULL, NULL, 'Abriam', '75e9fa7c6dcef02ca2c5425a4d7a614d', 'Abriam'),
(11, NULL, NULL, NULL, 'sample@yahoo.com', '5e8ff9bf55ba3508199d22e984129be6', 'sample'),
(12, NULL, NULL, NULL, 'sample1@yahoo,com', '5e8ff9bf55ba3508199d22e984129be6', 'sample'),
(13, NULL, NULL, NULL, 'edwin@gmail.com', '8e6e509fba12de7be9ff1cb5333a69d2', 'Edwin'),
(14, NULL, NULL, NULL, 'MELODEE@gmail.com', '1213be24c89e264524029ba7fd45befc', 'MELODEE'),
(15, NULL, NULL, NULL, 'juanDelacruz@gmail.com', '3f88c3fad991ffa1d67eb8cf899752e9', 'Juan'),
(16, NULL, NULL, NULL, 'juanjuan@gmail.com', 'a94652aa97c7211ba8954dd15a3cf838', 'juan'),
(17, NULL, NULL, NULL, 'juanjuanjuan@gmail.com', '7e5da5ece8edaa937e431c8600377c91', 'juan'),
(18, NULL, NULL, NULL, 'medrano@gmail.com', '92eaf3719159c372f3d50337e0a14f57', 'Juan'),
(19, NULL, NULL, NULL, 'medrano@gmail.com', 'a94652aa97c7211ba8954dd15a3cf838', 'Juan'),
(20, NULL, NULL, NULL, 'medrano11@gmail.com', '83615f55ab03be38cc865a9bf3ee9eb4', 'juan'),
(21, NULL, NULL, NULL, 'murillo11@gmail.com', 'a80466e8048c57e39fced138cd69c862', 'Juan');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `old_qty` int(11) NOT NULL,
  `new_qty` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `inventory_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `old_qty`, `new_qty`, `note`, `inventory_date`) VALUES
(1, 1, 12, 13, NULL, '2022-06-11 13:21:08'),
(2, 1, 13, 12, 'Transaction ID: 6', '2022-06-12 17:26:33'),
(3, 1, 12, 11, 'Transaction ID: 7', '2022-06-12 17:32:16'),
(4, 1, 11, 6, 'Transaction ID: 9', '2022-06-13 03:30:28'),
(5, 4, 0, -2, 'Transaction ID: 9', '2022-06-13 03:30:28'),
(6, 1, 6, 1, 'Transaction ID: 10', '2022-06-13 03:31:18'),
(7, 4, -2, -4, 'Transaction ID: 10', '2022-06-13 03:31:18'),
(8, 4, -4, -9, 'Transaction ID: 11', '2022-06-13 03:36:11'),
(9, 1, 1, 0, 'Transaction ID: 11', '2022-06-13 03:36:11'),
(10, 3, 0, -1, 'Transaction ID: 11', '2022-06-13 03:36:11'),
(11, 1, 0, 5, 'Void Transaction: 10', '2022-06-13 03:37:01'),
(12, 4, -9, -7, 'Void Transaction: 10', '2022-06-13 03:37:01'),
(13, 3, -1, -10, 'Transaction ID: 13', '2022-06-16 12:53:46'),
(14, 4, -7, -8, 'Transaction ID: 14', '2022-06-16 14:12:06'),
(15, 7, 0, -1, 'Transaction ID: 14', '2022-06-16 14:12:06'),
(16, 1, 5, 4, 'Transaction ID: 14', '2022-06-16 14:12:06'),
(17, 1, 5, 10, NULL, '2022-06-16 14:12:09'),
(18, 1, 10, 9, 'Transaction ID: 15', '2022-06-16 14:19:14'),
(19, 4, -8, -9, 'Transaction ID: 15', '2022-06-16 14:19:14'),
(20, 7, -1, -2, 'Transaction ID: 15', '2022-06-16 14:19:14'),
(21, 3, -10, -11, 'Transaction ID: 15', '2022-06-16 14:19:14'),
(22, 4, -9, -14, 'Transaction ID: 16', '2022-06-16 14:20:35'),
(23, 1, 9, 7, 'Transaction ID: 17', '2022-06-16 14:26:27'),
(24, 1, 7, 100, NULL, '2022-06-16 14:51:06'),
(25, 3, -11, 1, NULL, '2022-06-16 14:51:43'),
(26, 4, -14, -18, 'Transaction ID: 18', '2022-06-16 15:12:35'),
(27, 7, -2, -3, 'Transaction ID: 18', '2022-06-16 15:12:35'),
(28, 1, 100, 99, 'Transaction ID: 18', '2022-06-16 15:12:35'),
(29, 11, 0, -1, 'Transaction ID: 19', '2022-06-16 15:16:22'),
(30, 11, -1, -3, 'Transaction ID: 20', '2022-06-17 13:22:19'),
(31, 12, 0, -1, 'Transaction ID: 20', '2022-06-17 13:22:19'),
(32, 3, 1, 0, 'Transaction ID: 21', '2022-06-17 13:22:44'),
(33, 1, 99, 0, NULL, '2022-06-17 15:00:39'),
(34, 4, -18, 0, NULL, '2022-06-18 01:50:56'),
(35, 7, -3, 0, NULL, '2022-06-18 01:51:04'),
(36, 11, -3, 0, NULL, '2022-06-18 01:51:12'),
(37, 12, -1, 0, NULL, '2022-06-18 01:51:20'),
(38, 12, -1, 0, NULL, '2022-06-18 01:51:24'),
(39, 25, 0, -4, 'Transaction ID: 22', '2022-06-20 14:17:24'),
(40, 21, 0, -4, 'Transaction ID: 22', '2022-06-20 14:17:24'),
(41, 20, 0, -4, 'Transaction ID: 22', '2022-06-20 14:17:24'),
(42, 28, 0, -3, 'Transaction ID: 22', '2022-06-20 14:17:24'),
(43, 27, 0, -2, 'Transaction ID: 22', '2022-06-20 14:17:24'),
(44, 26, 0, -3, 'Transaction ID: 22', '2022-06-20 14:17:25'),
(45, 20, -4, 100, NULL, '2022-06-20 14:19:00'),
(46, 21, -4, 100, NULL, '2022-06-20 14:19:05'),
(47, 25, -4, 100, NULL, '2022-06-20 14:19:11'),
(48, 26, -3, 100, NULL, '2022-06-20 14:19:19'),
(49, 27, -2, 100, NULL, '2022-06-20 14:19:25'),
(50, 28, -3, 100, NULL, '2022-06-20 14:19:32'),
(51, 20, 100, 50, NULL, '2022-06-20 15:16:14'),
(52, 21, 100, 50, NULL, '2022-06-20 15:16:21'),
(53, 25, 100, 50, NULL, '2022-06-20 15:16:29'),
(54, 26, 100, 50, NULL, '2022-06-20 15:16:32'),
(55, 27, 100, 50, NULL, '2022-06-20 15:16:37'),
(56, 28, 100, 50, NULL, '2022-06-20 15:16:41'),
(57, 20, 50, 0, NULL, '2022-06-20 15:17:58'),
(58, 21, 50, 0, NULL, '2022-06-20 15:18:02'),
(59, 25, 50, 0, NULL, '2022-06-20 15:18:05'),
(60, 25, 0, 0, NULL, '2022-06-20 15:18:09'),
(61, 26, 50, 0, NULL, '2022-06-20 15:18:13'),
(62, 27, 50, 0, NULL, '2022-06-20 15:18:17'),
(63, 28, 50, 0, NULL, '2022-06-20 15:18:21'),
(64, 26, 0, 20, NULL, '2022-06-20 15:23:53'),
(65, 27, 0, 20, NULL, '2022-06-20 15:23:57'),
(66, 28, 0, 20, NULL, '2022-06-20 15:24:00'),
(67, 38, 0, 20, NULL, '2022-06-20 15:24:03'),
(68, 39, 0, 20, NULL, '2022-06-20 15:24:07'),
(69, 40, 0, 20, NULL, '2022-06-20 15:24:11'),
(70, 26, 20, 0, NULL, '2022-06-20 15:30:27'),
(71, 27, 20, 0, NULL, '2022-06-20 15:30:33'),
(72, 28, 20, 0, NULL, '2022-06-20 15:30:37'),
(73, 41, 0, 10, NULL, '2022-06-20 15:31:48'),
(74, 42, 0, 10, NULL, '2022-06-20 15:31:54'),
(75, 43, 0, 10, NULL, '2022-06-20 15:31:59'),
(76, 39, 20, 19, 'Transaction ID: 23', '2022-06-20 15:33:57'),
(77, 40, 20, 18, 'Transaction ID: 23', '2022-06-20 15:33:57'),
(78, 38, 20, 19, 'Transaction ID: 23', '2022-06-20 15:33:58'),
(79, 43, 10, 9, 'Transaction ID: 23', '2022-06-20 15:33:58'),
(80, 42, 10, 9, 'Transaction ID: 23', '2022-06-20 15:33:58'),
(81, 41, 10, 9, 'Transaction ID: 23', '2022-06-20 15:33:58'),
(82, 40, 18, 17, 'Transaction ID: 25', '2022-06-20 15:52:40'),
(83, 39, 19, 18, 'Transaction ID: 25', '2022-06-20 15:52:40'),
(84, 43, 9, 8, 'Transaction ID: 25', '2022-06-20 15:52:40'),
(85, 42, 9, 8, 'Transaction ID: 25', '2022-06-20 15:52:41'),
(86, 41, 9, 8, 'Transaction ID: 25', '2022-06-20 15:52:41'),
(87, 40, 17, 16, 'Transaction ID: 26', '2022-06-21 00:46:08'),
(88, 42, 8, 0, NULL, '2022-06-21 00:53:40'),
(89, 44, 0, 5, NULL, '2022-06-21 00:57:31'),
(90, 40, 16, 5, NULL, '2022-06-21 00:57:39'),
(91, 39, 18, 10, NULL, '2022-06-21 00:57:48'),
(92, 44, 5, 0, NULL, '2022-06-21 00:58:19'),
(93, 44, 0, 10, NULL, '2022-06-21 00:59:14'),
(94, 38, 19, 18, 'Transaction ID: 27', '2022-06-21 01:00:01'),
(95, 45, 0, -1, 'Transaction ID: 28', '2022-06-27 12:29:56'),
(96, 40, 5, 4, 'Transaction ID: 28', '2022-06-27 12:29:56'),
(97, 39, 10, 9, 'Transaction ID: 28', '2022-06-27 12:29:56'),
(98, 38, 18, 17, 'Transaction ID: 28', '2022-06-27 12:29:56'),
(99, 46, 0, -1, 'Transaction ID: 28', '2022-06-27 12:29:57'),
(100, 44, 10, 9, 'Transaction ID: 28', '2022-06-27 12:29:57'),
(101, 43, 8, 7, 'Transaction ID: 28', '2022-06-27 12:29:57'),
(102, 45, -1, 0, NULL, '2022-06-27 12:31:17'),
(103, 46, -1, 0, NULL, '2022-06-27 12:31:35'),
(104, 38, 17, 16, 'Transaction ID: 29', '2022-06-27 12:37:05'),
(105, 39, 9, 8, 'Transaction ID: 29', '2022-06-27 12:37:05'),
(106, 43, 7, 6, 'Transaction ID: 30', '2022-07-17 06:01:07'),
(107, 48, 0, -3, 'Transaction ID: 30', '2022-07-17 06:01:08'),
(108, 52, 0, -1, 'Transaction ID: 30', '2022-07-17 06:01:08'),
(109, 45, 0, 11, NULL, '2022-07-17 06:28:31'),
(110, 38, 16, 0, NULL, '2022-07-17 06:31:03'),
(111, 39, 8, 0, NULL, '2022-07-17 06:31:08'),
(112, 44, 9, 7, 'Transaction ID: 31', '2022-07-17 06:52:11'),
(113, 49, 0, -1, 'Transaction ID: 31', '2022-07-17 06:52:12'),
(114, 38, 0, 8, NULL, '2022-07-17 06:57:39'),
(115, 39, 0, 10, NULL, '2022-07-17 07:04:26'),
(116, 39, 10, 9, 'Transaction ID: 38', '2022-07-17 07:06:06'),
(117, 39, 9, 8, 'Transaction ID: 39', '2022-07-17 07:06:23'),
(118, 44, 7, 6, 'Transaction ID: 39', '2022-07-17 07:06:24'),
(119, 43, 6, 5, 'Transaction ID: 40', '2022-07-17 07:21:54'),
(120, 48, -3, -6, 'Transaction ID: 40', '2022-07-17 07:21:55'),
(121, 52, -1, -2, 'Transaction ID: 40', '2022-07-17 07:21:55'),
(122, 38, 8, 10, NULL, '2022-07-17 07:25:47');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `current_stock` int(11) NOT NULL,
  `category` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `type`, `barcode`, `image`, `name`, `cost`, `price`, `current_stock`, `category`) VALUES
(29, 'service', '10998', '1655735857pet-spa.jpg', 'Pet Spa', '500.00', '600.00', 0, NULL),
(30, 'service', '10997', '1655736124dog-grooming.jpg', 'Dog Grooming', '500.00', '600.00', 0, NULL),
(31, 'service', '10996', '1655736217cat-grooming.jpg', 'Cat Grooming', '500.00', '600.00', 0, NULL),
(33, 'service', '10995', '1655737406vaccination.jpg', 'Vaccination', '1000.00', '1500.00', 0, NULL),
(34, 'service', '10994', '1655737505deworming.jpg', 'Deworming', '500.00', '600.00', 0, NULL),
(35, 'service', '10993', '1655737635surgery.jpg', 'Surgery', '1000.00', '1500.00', 0, NULL),
(36, 'service', '10992', '1655737783treatment.jpg', 'Treatment', '800.00', '1000.00', 0, NULL),
(37, 'service', '10991', '1655738123boarding.jpg', 'Boarding', '500.00', '700.00', 0, NULL),
(38, 'item', '10987', '1655738442nutrichunk-1.3-kg.jpg', 'NutriChunk 1.3 Kg', '279.00', '379.00', 10, 'DOG FOOD'),
(39, 'item', '10986', '1655738521nutrichunk-10-kg.jpg', 'NutriChunk 10 Kg', '1450.00', '1550.00', 8, 'DOG FOOD'),
(40, 'item', '10985', '1655738574nutrichunk-22-kg.jpg', 'NutriChunk 22 Kg', '2450.00', '2550.00', 4, 'DOG FOOD'),
(41, 'item', '10984', '1655738921cuties-cat-food-22kg.jpg', 'Cuties Cat Food 1.3kg', '279.00', '379.00', 8, 'CAT FOOD'),
(43, 'item', '10982', '1655739086cuties-cat-food-22kg.jpg', 'Cuties Cat Food 10Kg', '1450.00', '1550.00', 5, 'CAT FOOD'),
(44, 'item', '10983', '1655772908cuties-cat-food-10kg.jpg', 'Cuties Cat Food 22Kg', '2450.00', '2550.00', 6, 'CAT FOOD'),
(45, 'item', '10981', '1656332703nutrichunk-bundle.jpg', 'NutriChunk Bundle ', '4279.00', '4379.00', 11, 'DOG FOOD'),
(46, 'item', '10980', '1656332496cuties-cat-food-bundle.jpg', 'Cuties Cat Food Bundle', '1037.00', '1137.00', 0, 'CAT FOOD'),
(47, 'item', '10979', '1656340913bearing-moisturizing.jpg', 'Bearing Moisturizing ', '100.00', '150.00', 0, 'DOG SOAP'),
(48, 'item', '10978', '1656341148bearing-natural-herbal.jpg', 'Bearing Natural Herbal ', '100.00', '150.00', -6, 'DOG SOAP'),
(49, 'item', '10977', '1656341674bearing-anti---tick-&-flea.jpg', 'Bearing Anti Tick&Flea', '100.00', '150.00', -1, 'DOG SOAP'),
(50, 'item', '10976', '1656342220bearing-bundle.jpg', 'Bearing Soap Bundle', '900.00', '1350.00', 0, 'DOG SOAP'),
(51, 'item', '10975', '1656344606petzyme.jpg', 'Petzyme ', '100.00', '150.00', 0, 'CAT SOAP'),
(52, 'item', '10974', '1656422270madre-de-cacao-soap.jpg', 'Madre de cacao Soap', '40.00', '50.00', -2, 'CAT SOAP'),
(53, 'item', '10973', '1656423216lori-soap-non-toxic.jpg', 'Lori Soap Non Toxic', '100.00', '150.00', 0, 'CAT SOAP'),
(54, 'item', '10972', '1656423667lori-soap-bundle.jpg', 'Lori Soap Bundle', '400.00', '550.00', 0, 'CAT SOAP');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `ref_number` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_cash` decimal(10,2) NOT NULL,
  `customer_change` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `revenue` decimal(10,2) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `ref_number`, `user_id`, `customer_id`, `customer_name`, `customer_cash`, `customer_change`, `profit`, `revenue`, `transaction_date`, `type`, `status`) VALUES
(24, '16557395171407444558', 3, NULL, '', '1000.00', '400.00', '100.00', '600.00', '2022-06-20 15:38:37', 'POS', 1),
(31, '16580407241198280060', NULL, 20, 'juan', '0.00', '0.00', '250.00', '5250.00', '2022-07-17 06:52:05', 'ONLINE-STORE', 1),
(38, '165804156628484366', NULL, 20, 'juan', '0.00', '0.00', '100.00', '1550.00', '2022-07-17 07:06:06', 'ONLINE-STORE', 1),
(39, '165804158346098424', NULL, 20, 'juan', '0.00', '0.00', '200.00', '4100.00', '2022-07-17 07:06:23', 'ONLINE-STORE', 1),
(40, '16580425111000623204', NULL, 21, 'Juan', '0.00', '0.00', '260.00', '2050.00', '2022-07-17 07:21:51', 'ONLINE-STORE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_item`
--

CREATE TABLE `transaction_item` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `revenue` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction_item`
--

INSERT INTO `transaction_item` (`id`, `transaction_id`, `product_id`, `product_name`, `type`, `price`, `cost`, `profit`, `revenue`, `quantity`, `status`) VALUES
(43, 24, 31, 'Cat Grooming', 'service', '600.00', '500.00', '100.00', '600.00', 1, 1),
(63, 31, 44, 'Cuties Cat Food 22Kg', 'item', '2550.00', '2450.00', '200.00', '5100.00', 2, 1),
(64, 31, 49, 'Bearing Anti Tick&Flea', 'item', '150.00', '100.00', '50.00', '150.00', 1, 1),
(65, 38, 39, 'NutriChunk 10 Kg', 'item', '1550.00', '1450.00', '100.00', '1550.00', 1, 1),
(66, 39, 39, 'NutriChunk 10 Kg', 'item', '1550.00', '1450.00', '100.00', '1550.00', 1, 1),
(67, 39, 44, 'Cuties Cat Food 22Kg', 'item', '2550.00', '2450.00', '100.00', '2550.00', 1, 1),
(68, 40, 43, 'Cuties Cat Food 10Kg', 'item', '1550.00', '1450.00', '100.00', '1550.00', 1, 1),
(69, 40, 48, 'Bearing Natural Herbal ', 'item', '150.00', '100.00', '150.00', '450.00', 3, 1),
(70, 40, 52, 'Madre de cacao Soap', 'item', '50.00', '40.00', '10.00', '50.00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type` enum('admin','customer') NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `username`, `password`, `name`) VALUES
(3, 'admin', 'admin', 'f5bb0c8de146c67b44babbf4e6584cc0', 'admin cli');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_item`
--
ALTER TABLE `transaction_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `transaction_item`
--
ALTER TABLE `transaction_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
