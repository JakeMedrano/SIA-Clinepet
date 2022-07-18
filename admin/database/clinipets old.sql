-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 17, 2022 at 09:56 PM
-- Server version: 10.3.34-MariaDB-log-cll-lve
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pinoypan_clinipets`
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
(8, 3, 2, '2022-06-13', 'cancel'),
(9, 7, 2, '2022-06-16', 'approve'),
(10, 8, 2, '2022-06-16', 'approve'),
(11, 8, 13, '2022-06-16', 'pending');

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
(12, 4, 7, 1);

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
(1, 'category 1'),
(2, 'Category 2'),
(3, 'category 3'),
(4, 'Category Test');

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
(8, NULL, NULL, NULL, 'pamela', '669ffc150d1f875819183addfc842cab', 'pamela');

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
(32, 3, 1, 0, 'Transaction ID: 21', '2022-06-17 13:22:44');

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
(1, 'item', '23', '1655389288test.jpg', 'test', '50.00', '250.00', 99, 'Category 1'),
(2, 'service', '345', '1655383409haircut.jpg', 'haircut', '50.00', '250.00', 0, NULL),
(3, 'item', '3453', NULL, 'dfgdgfd', '20.00', '50.00', 0, 'Category 2'),
(4, 'item', 'dfvd', '1655383139sdfsdf.jpg', 'sdfsdf', '0.00', '100.00', -18, 'Category 1'),
(7, 'item', 'sdsv', '1655383117t-shirt-blue-biryani-king.jpg', 'T-Shirt Blue Biryani King', '100.00', '250.00', -3, 'Category 1'),
(8, 'service', '4343', '1655383184serv.jpg', 'serv', '0.00', '300.00', 0, NULL),
(9, 'item', '123', '1655389356aso.jpg', 'aso', '500.00', '250.00', 0, 'Category 2'),
(10, 'service', 'saka', '1655390837saka.jpg', 'saka', '500.00', '600.00', 0, NULL),
(11, 'item', '0909', '1655392543pamela.jpg', 'pamela', '500.00', '600.00', -3, 'category 3'),
(12, 'item', '890', '1655392771pamela2.jpg', 'pamela2', '500.00', '700.00', -1, 'category 3'),
(13, 'service', '8908', '1655393059massage.jpg', 'massage', '500.00', '5000.00', 0, NULL);

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
(1, '1654953894728870234', 3, NULL, '', '1000.00', '250.00', '0.00', '0.00', '2022-06-11 13:02:48', 'POS', 1),
(2, '1654953894728870444', 3, NULL, '', '1000.00', '250.00', '0.00', '0.00', '2022-06-11 13:05:57', 'POS', 1),
(3, '1654953894728870731', 1, NULL, '', '1000.00', '250.00', '0.00', '0.00', '2022-06-11 13:24:54', 'POS', 0),
(4, '1655051211835484772', 3, NULL, '', '1000.00', '0.00', '0.00', '0.00', '2022-06-12 16:26:51', 'POS', 1),
(5, '1655054704207029186', 3, NULL, 'sdvds', '1000.00', '500.00', '0.00', '0.00', '2022-06-12 17:25:04', 'ONLINE-STORE', 1),
(6, '16550547932069193371', 3, NULL, 'sdvds', '1000.00', '500.00', '400.00', '500.00', '2022-06-12 17:26:33', 'ONLINE-STORE', 1),
(7, '1655055136786789360', 3, NULL, 'cvcbcvbc', '500.00', '250.00', '200.00', '250.00', '2022-06-12 17:32:16', 'POS', 1),
(8, '1655091016498366614', NULL, 3, 'test', '0.00', '0.00', '0.00', '0.00', '2022-06-13 03:30:16', 'ONLINE-STORE', 1),
(9, '1655091028251584897', NULL, 3, 'test', '0.00', '0.00', '1200.00', '1450.00', '2022-06-13 03:30:28', 'ONLINE-STORE', 1),
(10, '1655091078959341352', NULL, 3, 'test', '0.00', '0.00', '1200.00', '1450.00', '2022-06-13 03:31:18', 'ONLINE-STORE', 0),
(11, '16550913711460640784', NULL, 3, 'test', '0.00', '0.00', '730.00', '800.00', '2022-06-13 03:36:11', 'ONLINE-STORE', 1),
(12, '1655383522230562157', NULL, 4, 'test', '0.00', '0.00', '0.00', '0.00', '2022-06-16 12:45:22', 'ONLINE-STORE', 1),
(13, '16553840261298819972', 3, NULL, '', '500.00', '50.00', '270.00', '450.00', '2022-06-16 12:53:46', 'POS', 1),
(14, '1655388726538200212', NULL, 7, 'Abdul1', '0.00', '0.00', '450.00', '600.00', '2022-06-16 14:12:06', 'ONLINE-STORE', 1),
(15, '1655389154283783970', NULL, 7, 'Abdul1', '0.00', '0.00', '480.00', '650.00', '2022-06-16 14:19:14', 'ONLINE-STORE', 1),
(16, '16553892351696891579', NULL, 7, 'Abdul1', '0.00', '0.00', '500.00', '500.00', '2022-06-16 14:20:35', 'ONLINE-STORE', 1),
(17, '16553895861565403518', 3, NULL, '', '1000.00', '250.00', '600.00', '750.00', '2022-06-16 14:26:26', 'POS', 1),
(18, '1655392355222996100', NULL, 8, 'pamela', '0.00', '0.00', '750.00', '900.00', '2022-06-16 15:12:35', 'ONLINE-STORE', 1),
(19, '16553925821386595167', NULL, 8, 'pamela', '0.00', '0.00', '100.00', '600.00', '2022-06-16 15:16:22', 'ONLINE-STORE', 1),
(20, '1655472139497765656', 3, NULL, '', '12000.00', '100.00', '9400.00', '11900.00', '2022-06-17 13:22:19', 'POS', 1),
(21, '16554721641058284801', 3, NULL, '', '400.00', '100.00', '230.00', '300.00', '2022-06-17 13:22:44', 'POS', 1);

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
(1, 6, 1, 'test', 'item', '250.00', '50.00', '200.00', '250.00', 1, 1),
(2, 6, 2, 'haircut', 'service', '250.00', '50.00', '200.00', '250.00', 1, 1),
(3, 7, 1, 'test', 'item', '250.00', '50.00', '200.00', '250.00', 1, 1),
(4, 9, 1, 'test', 'item', '250.00', '50.00', '1000.00', '1250.00', 5, 1),
(5, 9, 4, 'ddf', 'item', '100.00', '0.00', '200.00', '200.00', 2, 1),
(6, 10, 1, 'test', 'item', '250.00', '50.00', '1000.00', '1250.00', 5, 0),
(7, 10, 4, 'ddf', 'item', '100.00', '0.00', '200.00', '200.00', 2, 0),
(8, 11, 4, 'ddf', 'item', '100.00', '0.00', '500.00', '500.00', 5, 1),
(9, 11, 1, 'test', 'item', '250.00', '50.00', '200.00', '250.00', 1, 1),
(10, 11, 3, 'dfgdgfd', 'item', '50.00', '20.00', '30.00', '50.00', 1, 1),
(11, 13, 3, 'dfgdgfd', 'item', '50.00', '20.00', '270.00', '450.00', 9, 1),
(12, 14, 4, 'sdfsdf', 'item', '100.00', '0.00', '100.00', '100.00', 1, 1),
(13, 14, 7, 'T-Shirt Blue Biryani King', 'item', '250.00', '100.00', '150.00', '250.00', 1, 1),
(14, 14, 1, 'test', 'item', '250.00', '50.00', '200.00', '250.00', 1, 1),
(15, 15, 1, 'test', 'item', '250.00', '50.00', '200.00', '250.00', 1, 1),
(16, 15, 4, 'sdfsdf', 'item', '100.00', '0.00', '100.00', '100.00', 1, 1),
(17, 15, 7, 'T-Shirt Blue Biryani King', 'item', '250.00', '100.00', '150.00', '250.00', 1, 1),
(18, 15, 3, 'dfgdgfd', 'item', '50.00', '20.00', '30.00', '50.00', 1, 1),
(19, 16, 4, 'sdfsdf', 'item', '100.00', '0.00', '500.00', '500.00', 5, 1),
(20, 17, 1, 'test', 'item', '250.00', '50.00', '400.00', '500.00', 2, 1),
(21, 17, 2, 'haircut', 'service', '250.00', '50.00', '200.00', '250.00', 1, 1),
(22, 18, 4, 'sdfsdf', 'item', '100.00', '0.00', '400.00', '400.00', 4, 1),
(23, 18, 7, 'T-Shirt Blue Biryani King', 'item', '250.00', '100.00', '150.00', '250.00', 1, 1),
(24, 18, 1, 'test', 'item', '250.00', '50.00', '200.00', '250.00', 1, 1),
(25, 19, 11, 'pamela', 'item', '600.00', '500.00', '100.00', '600.00', 1, 1),
(26, 20, 11, 'pamela', 'item', '600.00', '500.00', '200.00', '1200.00', 2, 1),
(27, 20, 13, 'massage', 'service', '5000.00', '500.00', '9000.00', '10000.00', 2, 1),
(28, 20, 12, 'pamela2', 'item', '700.00', '500.00', '200.00', '700.00', 1, 1),
(29, 21, 2, 'haircut', 'service', '250.00', '50.00', '200.00', '250.00', 1, 1),
(30, 21, 3, 'dfgdgfd', 'item', '50.00', '20.00', '30.00', '50.00', 1, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `transaction_item`
--
ALTER TABLE `transaction_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
