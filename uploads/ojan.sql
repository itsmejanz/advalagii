-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 20, 2026 at 02:09 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ojan`
--

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int NOT NULL,
  `sender_id` int DEFAULT NULL,
  `message` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `sender_id`, `message`, `created_at`) VALUES
(9, 4, 's', '2026-04-18 19:49:48'),
(10, 4, 'd', '2026-04-18 19:51:50'),
(11, 4, 'dddd', '2026-04-18 19:51:53'),
(12, 6, 'Woi anj', '2026-04-18 19:54:51'),
(13, 6, 'Wkjwkww', '2026-04-18 19:54:57'),
(14, 5, 'Uii', '2026-04-19 12:33:50'),
(15, 4, 'P', '2026-04-20 01:41:06'),
(16, 7, 'tes', '2026-04-20 01:42:41'),
(17, 4, 'Tes', '2026-04-20 01:42:44');

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

CREATE TABLE `stories` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stories`
--

INSERT INTO `stories` (`id`, `user_id`, `image`, `lat`, `lng`, `created_at`) VALUES
(6, 4, 'uploads/1776543358_story.png', -6.2938, 106.883, '2026-04-18 20:15:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `bio` text,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `username`, `bio`, `avatar`) VALUES
(4, 'admin@admin.com', 'admin@admin.com', '$2y$10$xRx2pzSlAvuhWZhyck5an.vfxcRnraVAUgAC0vd0bjyM3.Cs1IAyi', 'admin@admin.com', 'siapasih', 'profile/1776481296.JPG'),
(5, 'user 2', 'ojanxsec@gmail.com', '$2y$10$c5EB5dNLeu3RhFiKoRtmc.AVjoxXjO0.MXDk./yCCF5miSqCYr/7C', 'ojanxsec@gmail.com', 'tester 2', 'profile/1776482717.jpg'),
(6, 'FAUZAN SALAM MUTAQIN ', 'ojanxsecc@gmail.com', '$2y$10$K.cfhj9QmG07QYUrE.f6YeBOyAnSUYJkFfbqQe0CgkNXt9RlaJlVi', 'ojanadmin', 'Akun admin ke dua', NULL),
(7, 'Fardan', 'fardanallyh1@gmail.com', '$2y$10$9aprDiG.e5J2cgWaesMoOOR4LOw62qVQD.q9KgLug8X1EbRITFtFG', 'fardan', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_location`
--

CREATE TABLE `users_location` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users_location`
--

INSERT INTO `users_location` (`id`, `user_id`, `lat`, `lng`, `updated_at`) VALUES
(3, 4, -6.254817, 106.919506, '2026-04-18 03:12:33'),
(6, 5, -6.2938, 106.883, '2026-04-18 03:20:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_location`
--
ALTER TABLE `users_location`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `stories`
--
ALTER TABLE `stories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users_location`
--
ALTER TABLE `users_location`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
