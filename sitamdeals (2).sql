-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2026 at 08:22 PM
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
-- Database: `sitamdeals`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `kasir_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `kasir_id`, `created_at`, `status`) VALUES
(23, 3, NULL, '2026-04-13 15:25:26', 'selesai'),
(24, 3, NULL, '2026-04-13 16:00:46', 'ditolak'),
(25, 3, NULL, '2026-04-14 04:56:19', 'ditolak'),
(26, 3, NULL, '2026-04-14 06:05:20', 'ditolak'),
(27, 3, NULL, '2026-04-14 06:40:06', 'ditolak'),
(28, 2, NULL, '2026-04-14 07:13:11', 'selesai'),
(29, 3, NULL, '2026-04-14 08:26:43', 'pending'),
(30, 3, NULL, '2026-04-14 11:38:52', 'selesai'),
(31, 1, NULL, '2026-04-14 11:42:27', 'diproses'),
(32, 1, NULL, '2026-04-14 11:44:02', 'diproses'),
(33, 2, NULL, '2026-04-14 11:45:28', 'diterima'),
(34, 2, NULL, '2026-04-14 12:24:44', 'pending'),
(35, 2, NULL, '2026-04-14 12:25:07', 'pending'),
(36, 2, NULL, '2026-04-14 12:34:24', 'pending'),
(37, 2, NULL, '2026-04-14 12:37:52', 'pending'),
(38, 2, NULL, '2026-04-14 12:42:07', 'pending'),
(39, 2, NULL, '2026-04-14 12:43:28', 'pending'),
(40, 2, NULL, '2026-04-14 12:44:40', 'pending'),
(41, 2, NULL, '2026-04-14 12:46:49', 'pending'),
(42, 2, NULL, '2026-04-14 12:48:46', 'pending'),
(43, 2, NULL, '2026-04-14 12:53:52', 'pending'),
(44, 2, NULL, '2026-04-14 13:01:57', 'pending'),
(45, 2, NULL, '2026-04-14 13:05:50', 'pending'),
(46, 2, NULL, '2026-04-14 13:20:42', 'pending'),
(47, 2, NULL, '2026-04-14 13:21:42', 'pending'),
(48, 2, NULL, '2026-04-14 13:24:45', 'pending'),
(49, 2, NULL, '2026-04-14 13:27:00', 'pending'),
(50, 2, NULL, '2026-04-14 13:27:52', 'pending'),
(51, 2, NULL, '2026-04-14 13:28:31', 'pending'),
(52, 1, NULL, '2026-04-14 13:46:31', 'pending'),
(53, 1, NULL, '2026-04-14 13:46:59', 'pending'),
(54, 1, NULL, '2026-04-14 13:51:15', 'pending'),
(55, 1, NULL, '2026-04-14 13:51:48', 'pending'),
(56, 1, NULL, '2026-04-14 13:52:13', 'pending'),
(57, 1, NULL, '2026-04-14 13:53:16', 'pending'),
(58, 1, NULL, '2026-04-14 13:53:33', 'pending'),
(59, 1, NULL, '2026-04-14 13:53:47', 'pending'),
(60, 1, NULL, '2026-04-14 13:54:09', 'pending'),
(61, 3, NULL, '2026-04-14 14:27:42', 'pending'),
(62, 3, NULL, '2026-04-14 14:33:09', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `grade` enum('A','B','C') DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `grade`, `price`, `qty`) VALUES
(7, 23, 2, 'B', 6300, 1),
(8, 24, 2, 'B', 6300, 2),
(9, 24, 1, 'A', 22950, 2),
(10, 25, 9, 'A', 12750, 1),
(11, 26, 1, 'C', 13500, 1),
(12, 27, 2, 'A', 7650, 2),
(13, 28, 2, 'A', 7650, 1),
(14, 29, 1, 'B', 18900, 1),
(15, 32, 1, 'B', 18900, 1),
(17, 58, 12, 'B', 5600, 1),
(18, 59, 12, 'B', 5600, 1),
(19, 60, 12, 'B', 5600, 1),
(20, 61, 2, 'C', 4500, 1),
(21, 62, 2, 'B', 6300, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `stock_A` int(11) DEFAULT 0,
  `stock_B` int(11) DEFAULT 0,
  `stock_C` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `category`, `stock_A`, `stock_B`, `stock_C`, `image`) VALUES
(1, 'Minyak Goreng 2L', 'Minyak goreng sawit murni 2 liter untuk memasak sehari-hari. \r\nGrade A (Rp 27.000): Kemasan botol plastik bolong kecil di tutup, tidak bocor, isi produk aman. \r\nGrade B (Rp 22.000): Kardus kemasan penyok samping, expired 5 bulan lagi. \r\nGrade C (Rp 16.000): Plastik kemasan robek besar sudah di-repack, expired 1 bulan lagi', 27000, 'Bumbu & Rempah', 4, 4, 0, 'minyak-goreng.jpg'),
(2, 'Susu UHT 1L', 'Susu UHT full cream 1 liter, kaya nutrisi dan tahan lama tanpa pengawet. \r\nGrade A (Rp 9.000): Botol ada goresan kecil di label, barang sisa event Lebaran 2025. \r\nGrade B (Rp 7.000): Kardus kemasan penyok samping, expired 5 bulan lagi. \r\nGrade C (Rp 5.000): Kardus penyok parah, expired 1.5 bulan lagi', 9000, 'Susu & Olahan', 8, 1, 4, 'susu-uht.jpg'),
(3, 'Beras 5Kg', 'Beras putih premium kualitas terbaik, pulen dan wangi, kemasan 5 kg. \r\nGrade A (Rp 64.000): Plastik kemasan bolong titik-titik kecil sudah direkatkan. \r\nGrade B (Rp 53.000): Plastik kemasan robek sedang sudah ditambal, expired 4 bulan lagi. \r\nGrade C (Rp 38.000): Plastik kemasan robek besar sudah di-repack, expired 1 bulan lagi', 64000, 'Bumbu & Rempah', 10, 6, 2, 'beras.jpg'),
(4, 'Kecap Manis 600ml', 'Kecap manis asli khas Indonesia untuk bumbu masakan. \r\nGrade A (Rp 13.000): Botol kaca ada goresan kecil di label. \r\nGrade B (Rp 11.000): Botol penyok tutup, expired 6 bulan lagi. \r\nGrade C (Rp 8.000): Botol penyok parah di badan, expired 1.5 bulan lagi', 13000, 'Bumbu & Rempah', 15, 7, 3, 'kecap.jpg'),
(5, 'Tepung Terigu 1Kg', 'Tepung terigu serbaguna kualitas premium. \r\nGrade A (Rp 12.000): Plastik kemasan bolong kecil. \r\nGrade B (Rp 10.000): Plastik kemasan bolong sedang. \r\nGrade C (Rp 7.000): Plastik robek besar sudah di-repack', 12000, 'Bumbu & Rempah', 20, 10, 5, 'tepung-terigu.jpg'),
(6, 'Indomie Goreng', 'Mie instan goreng favorit. \r\nGrade A: Kemasan rapi. \r\nGrade B: Kemasan sedikit penyok. \r\nGrade C: Kemasan sobek luar', 3500, 'Camilan & Minuman', 30, 20, 10, 'indomie-goreng.png'),
(7, 'Air Mineral 1.5L', 'Air mineral segar. \nGrade A: Botol mulus. \nGrade B: Label lecet. \nGrade C: Botol penyok ringan', 5000, 'Camilan & Minuman', 25, 15, 8, 'air-mineral.jpg'),
(8, 'Sabun Lifebuoy', 'Sabun antibakteri. \r\nGrade A: Kemasan bagus. \r\nGrade B: Box penyok. \r\nGrade C: Kemasan terbuka', 4000, 'Perawatan Diri', 18, 10, 5, 'lifebuoy.jpg'),
(9, 'Shampoo Sunsilk', 'Shampoo rambut halus. \r\nGrade A: Botol mulus. \r\nGrade B: Tutup lecet. \r\nGrade C: Label rusak', 15000, 'Perawatan Diri', 11, 8, 4, 'shampo.jpg'),
(10, 'Detergen Rinso Cair 700ml', 'Deterjen pembersih noda. \r\nGrade A: Kemasan utuh. \r\nGrade B: Plastik penyok. \r\nGrade C: Plastik robek kecil', 18000, 'Kebersihan Rumah', 14, 9, 5, 'rinsoo.jpg'),
(11, 'Pembersih Lantai', 'Cairan pel lantai. \r\nGrade A: Botol bagus. \r\nGrade B: Tutup lecet. \r\nGrade C: Botol penyok', 12000, 'Kebersihan Rumah', 10, 6, 3, 'soklin-lantai.jpg'),
(12, 'Biskuit Roma', 'Biskuit renyah. \r\nGrade A: Box utuh. \r\nGrade B: Box penyok. \r\nGrade C: Kemasan terbuka', 8000, 'Camilan & Minuman', 22, 9, 6, 'biskuit-roma.jpg'),
(13, 'Susu Kental Manis', 'Susu topping. \r\nGrade A: Kaleng mulus. \r\nGrade B: Kaleng penyok. \r\nGrade C: Label rusak', 11000, 'Susu & Olahan', 16, 9, 4, 'skm.png'),
(14, 'Popok Bayi', 'Popok nyaman. \r\nGrade A: Kemasan baru. \r\nGrade B: Kemasan penyok. \r\nGrade C: Kemasan robek', 45000, 'Kebutuhan Bayi', 10, 5, 2, 'pampers.jpg'),
(15, 'Minyak Telon', 'Minyak bayi. \r\nGrade A: Botol bagus. \r\nGrade B: Label rusak. \r\nGrade C: Tutup lecet', 20000, 'Kebutuhan Bayi', 8, 4, 2, 'minyak-telon.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','kasir','pembeli') DEFAULT 'pembeli'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Nayla', 'admin@gmail.com', '12345', 'admin'),
(2, 'Kasir', 'kasir@gmail.com', '11111', 'kasir'),
(3, 'User', 'user@gmail.com', '12345', 'pembeli'),
(4, 'Nayla Arviandini', 'nayla.arviandini@gmail.com', '123456', 'pembeli');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
