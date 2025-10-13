-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 13, 2025 at 01:48 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meit`
--

-- --------------------------------------------------------

--
-- Table structure for table `aanmeldingen`
--

CREATE TABLE `aanmeldingen` (
  `id` bigint UNSIGNED NOT NULL,
  `id_deelnemer` int UNSIGNED NOT NULL,
  `id_training` int UNSIGNED NOT NULL,
  `betaal_status` smallint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `amount_paid` int NOT NULL DEFAULT '0',
  `amount_due_remaining` int NOT NULL DEFAULT '0',
  `due_at` datetime DEFAULT NULL,
  `stripe_customer_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_checkout_session_id` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_payment_intent_id` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_payment_method_id` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `aanmeldingen`
--

INSERT INTO `aanmeldingen` (`id`, `id_deelnemer`, `id_training`, `betaal_status`, `created_at`, `updated_at`, `amount_paid`, `amount_due_remaining`, `due_at`, `stripe_customer_id`, `stripe_checkout_session_id`, `stripe_payment_intent_id`, `stripe_payment_method_id`, `customer_email`) VALUES
(1, 5, 1, 1, '2025-09-01 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 6, 1, 0, '2025-09-02 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 7, 1, 0, '2025-09-02 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 9, 1, 0, '2025-09-03 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 10, 3, 2, '2025-09-09 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 11, 5, 0, '2025-09-09 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 13, 5, 0, '2025-09-09 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 13, 3, 2, '2025-09-10 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 14, 3, 1, '2025-09-11 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 15, 3, 1, '2025-09-11 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 5, 5, 2, '2025-09-11 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 1, 7, 2, '2025-09-15 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 9, 7, 1, '2025-09-15 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 5, 7, 2, '2025-09-15 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 5, 3, 0, '2025-09-19 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 5, 18, 1, '2025-09-21 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 22, 18, 0, '2025-09-22 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 23, 18, 2, '2025-09-24 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 5, 20, 0, '2025-09-25 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 24, 20, 1, '2025-09-26 09:51:45', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 25, 24, 2, '2025-10-03 12:13:18', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 27, 18, 2, '2025-10-08 07:47:44', '2025-10-08 11:20:36', 44400, 0, '2025-12-25 00:00:00', 'fake_40b738776ec5466e', 'cs_test_a1Su6DYJ5DTsiwKFWxfyb5wb38zZl2ObFj9Da8ESr9N5aYYkc22EyaXR8z', NULL, NULL, 'boyd.halfman@gmail.com'),
(45, 27, 20, 2, '2025-10-08 07:54:21', '2025-10-08 09:50:39', 44400, 0, '2026-01-26 00:00:00', 'fake_447bd656ce440b27', NULL, NULL, NULL, 'boyd.halfman@gmail.com'),
(46, 27, 7, 0, '2025-10-08 08:00:51', '2025-10-08 08:00:51', 0, 0, NULL, 'fake_b154348fb9e9c747', NULL, NULL, NULL, 'boyd.halfman@gmail.com'),
(49, 28, 20, 2, '2025-10-08 11:38:12', '2025-10-08 11:38:51', 44400, 0, NULL, 'fake_33f608b7f2626a56', NULL, NULL, NULL, 'test16@email.test'),
(51, 29, 7, 0, '2025-10-10 07:23:25', '2025-10-10 07:23:28', 0, 0, NULL, 'fake_d98edcf851fda2f9', NULL, NULL, NULL, 'test17@email.test'),
(52, 29, 29, 1, '2025-10-13 10:26:36', '2025-10-13 10:29:27', 22200, 22200, '2025-10-17 00:00:00', 'fake_14a3a607fb88c2c4', 'cs_test_a14kh77ETemAETyEVRQfDCIawd3g3DlkZdCLV2YeGk29yVDCQihIp8L6ui', NULL, NULL, 'test17@email.test'),
(53, 29, 18, 2, '2025-10-13 10:30:58', '2025-10-13 10:30:58', 44400, 0, NULL, 'fake_80126a4110170115', NULL, NULL, NULL, 'test17@email.test');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `wachtwoord` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `wachtwoord`) VALUES
(1, 'admin@test.mail', '$2y$12$Fl5iMTl2ou7J6mycn6NZZuLdbY2wmYRY0Pcks6NJVxTU0Dt02hVYm');

-- --------------------------------------------------------

--
-- Table structure for table `ceremonies`
--

CREATE TABLE `ceremonies` (
  `id` bigint UNSIGNED NOT NULL,
  `id_deelnemer` int UNSIGNED NOT NULL,
  `datum` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ceremonies`
--

INSERT INTO `ceremonies` (`id`, `id_deelnemer`, `datum`, `created_at`) VALUES
(1, 5, '2025-11-11', '2025-10-08 14:08:08'),
(2, 13, '2025-12-08', '2025-10-08 14:08:08'),
(4, 14, '2025-10-12', '2025-10-08 14:08:08'),
(5, 28, '2025-10-23', '2025-10-08 14:08:08'),
(6, 5, '2025-10-15', '2025-10-13 13:46:55');

-- --------------------------------------------------------

--
-- Table structure for table `deelnemers`
--

CREATE TABLE `deelnemers` (
  `id` bigint UNSIGNED NOT NULL,
  `voornaam` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tussenvoegsel` text COLLATE utf8mb4_unicode_ci,
  `achternaam` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefoon_nummer` text COLLATE utf8mb4_unicode_ci,
  `wachtwoord` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deelnemers`
--

INSERT INTO `deelnemers` (`id`, `voornaam`, `tussenvoegsel`, `achternaam`, `email`, `telefoon_nummer`, `wachtwoord`, `created_at`, `updated_at`) VALUES
(1, 'Test', NULL, 'Tester', 'test@email.test', NULL, '$2y$12$CUm9CNQ7eNUhJH9ckiWNCumMmteFxAAvnD7vvF4BzLYLPcI2JGXaW', NULL, '2025-10-10 07:10:38'),
(5, 'Test', NULL, 'Tester', 'test2@email.test', '06-12345678', '123', NULL, NULL),
(6, 'Test3', 'de', 'Tester', 'test3@email.test', NULL, '123', NULL, NULL),
(7, 'Test4', NULL, 'Tester', 'test4@email.test', NULL, '123', NULL, NULL),
(9, 'Test5', NULL, 'Tester', 'test5@email.test', NULL, '123', NULL, NULL),
(10, 'Test6', 'der', 'Tester', 'test6@email.test', NULL, '123', NULL, NULL),
(11, 'Test7', NULL, 'Tester', 'test7@email.test', NULL, '1234', NULL, NULL),
(12, 'Test8', NULL, 'Tester', 'test8@email.test', NULL, '123', NULL, NULL),
(13, 'Test9', NULL, 'Tester', 'test9@email.test', NULL, '123', NULL, NULL),
(14, 'Test10', NULL, 'Tester', 'test10@email.test', '06-87654321', '123', NULL, NULL),
(15, 'Test11', NULL, 'Tester', 'test11@email.test', NULL, '123', NULL, NULL),
(22, 'Test12', 'de', 'Tester', 'test12@mail.test', NULL, '123', NULL, NULL),
(23, 'Test13', NULL, 'Tester', 'test13@mail.test', NULL, '123', NULL, NULL),
(24, 'Test14', NULL, 'Tester', 'test14@mail.nl', NULL, '123', NULL, NULL),
(25, 'dwad', 'dwada', 'dwwadad', 'dwadaw@dwadad.nl', NULL, 'ihw20kimv@', NULL, NULL),
(26, 'Test15', 'der', 'Tester', 'test15@email.test', NULL, '123', NULL, NULL),
(27, 'Boyd', NULL, 'Halfman', 'boyd.halfman@gmail.com', '0633770299', '$2y$12$7SD1n3eixTqdnJ0olvu4beGvNLx6rR5u/R5Vnif6w6T4zyYIQJ0nS', NULL, NULL),
(28, 'Test16', NULL, 'Tester', 'test16@email.test', NULL, '$2y$12$OC0O8JKV6sClxcOriU5ia.9d9e7oatslosN7ziupr1R/9tLT.Eq2G', NULL, NULL),
(29, 'Test17', NULL, 'Tester', 'test17@email.test', NULL, '$2y$12$K5Q1kIvCca8RcX/fZR73yOUJe8JHHryCUOyNkecVgXgwPBeyxzcFm', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `intakegesprekken`
--

CREATE TABLE `intakegesprekken` (
  `id` bigint UNSIGNED NOT NULL,
  `id_deelnemer` int UNSIGNED NOT NULL,
  `datum` date NOT NULL,
  `begin_tijd` time NOT NULL,
  `eind_tijd` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `intakegesprekken`
--

INSERT INTO `intakegesprekken` (`id`, `id_deelnemer`, `datum`, `begin_tijd`, `eind_tijd`, `created_at`) VALUES
(4, 13, '2025-09-30', '09:40:00', '11:00:00', '2025-10-06 08:50:29'),
(8, 1, '2025-10-06', '09:05:00', '10:05:00', '2025-10-06 08:50:29'),
(13, 1, '2025-10-17', '11:46:00', '12:46:00', '2025-10-06 08:50:29'),
(14, 26, '2025-10-17', '13:00:00', '14:00:00', '2025-10-06 08:50:29'),
(16, 26, '2025-10-09', '11:00:00', '12:00:00', '2025-10-06 08:50:29');

-- --------------------------------------------------------

--
-- Table structure for table `intake_mogelijkheden`
--

CREATE TABLE `intake_mogelijkheden` (
  `id` bigint UNSIGNED NOT NULL,
  `datum` date NOT NULL,
  `begin_tijd` time NOT NULL,
  `eind_tijd` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `intake_mogelijkheden`
--

INSERT INTO `intake_mogelijkheden` (`id`, `datum`, `begin_tijd`, `eind_tijd`, `created_at`) VALUES
(1, '2025-09-30', '08:24:00', '17:00:00', '2025-09-29 09:24:51'),
(2, '2025-10-02', '10:00:00', '16:49:00', '2025-09-29 10:51:11'),
(3, '2025-09-29', '12:50:00', '18:00:00', '2025-09-29 10:58:50'),
(9, '2025-10-06', '08:00:00', '09:05:00', '2025-10-03 08:54:47'),
(10, '2025-10-06', '10:05:00', '16:10:00', '2025-10-03 08:54:47'),
(11, '2025-10-10', '09:53:00', '12:00:00', '2025-10-03 13:12:28'),
(16, '2025-10-17', '08:46:00', '11:46:00', '2025-10-03 14:10:44'),
(20, '2025-10-22', '10:00:00', '18:15:00', '2025-10-06 07:59:27'),
(21, '2025-10-10', '15:00:00', '18:25:00', '2025-10-08 13:37:59'),
(25, '2025-10-17', '14:00:00', '16:00:00', '2025-10-08 13:47:51'),
(26, '2025-10-14', '15:55:00', '18:55:00', '2025-10-10 11:55:25');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_08_084701_create_aanmeldings_table', 1),
(5, '2025_10_08_084954_create_admins_table', 1),
(6, '2025_10_08_085419_create_deelnemers_table', 1),
(7, '2025_10_08_085630_create_intake_gesprekken_table', 1),
(8, '2025_10_08_085953_create_intake_mogelijkheden_table', 1),
(9, '2025_10_08_090222_create_trainingen_table', 1),
(10, '2025_10_08_090422_create_ceremonies_table', 1),
(11, '2025_10_08_092608_add_stripe_fields_to_aanmeldingen', 2),
(12, '2025_10_08_093517_add_timestamps_to_deelnemers_table', 3),
(13, '2025_10_08_140048_add_created_at_to_ceremonies_table', 4),
(14, '2025_10_08_140352_add_created_at_to_ceremonies_table', 5),
(15, '2025_10_08_140530_add_created_at_to_ceremonies_table', 6),
(16, '2025_10_08_140736_add_created_at_to_ceremonies_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `trainingen`
--

CREATE TABLE `trainingen` (
  `id` bigint UNSIGNED NOT NULL,
  `start_moment` datetime NOT NULL,
  `start_moment_2` datetime NOT NULL,
  `start_moment_3` datetime NOT NULL,
  `start_moment_4` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trainingen`
--

INSERT INTO `trainingen` (`id`, `start_moment`, `start_moment_2`, `start_moment_3`, `start_moment_4`) VALUES
(1, '2025-07-01 10:00:00', '2025-07-15 14:40:00', '2025-07-29 08:00:00', '2025-08-12 15:00:00'),
(3, '2025-09-09 14:00:00', '2025-09-16 10:00:00', '2025-09-23 13:00:00', '2025-09-30 09:00:00'),
(5, '2025-09-15 16:00:00', '2025-09-16 13:50:00', '2025-09-23 09:15:00', '2025-10-03 10:45:00'),
(7, '2025-12-01 12:40:00', '2025-12-08 08:00:00', '2025-12-15 13:15:00', '2025-12-22 16:00:00'),
(18, '2026-01-01 00:00:00', '2026-01-08 18:30:00', '2026-01-15 09:45:00', '2026-01-22 21:20:00'),
(20, '2026-02-02 14:00:00', '2026-02-09 16:15:00', '2026-02-16 18:18:00', '2026-02-23 08:00:00'),
(24, '2025-10-07 15:00:00', '2025-10-18 16:00:00', '2025-10-25 17:00:00', '2025-11-01 14:00:00'),
(29, '2025-10-24 11:00:00', '2025-10-29 14:00:00', '2025-11-05 13:30:00', '2025-11-11 08:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aanmeldingen`
--
ALTER TABLE `aanmeldingen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aanmeldingen_id_deelnemer_index` (`id_deelnemer`),
  ADD KEY `aanmeldingen_id_training_index` (`id_training`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ceremonies`
--
ALTER TABLE `ceremonies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ceremonies_id_deelnemer_index` (`id_deelnemer`);

--
-- Indexes for table `deelnemers`
--
ALTER TABLE `deelnemers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `intakegesprekken`
--
ALTER TABLE `intakegesprekken`
  ADD PRIMARY KEY (`id`),
  ADD KEY `intakegesprekken_id_deelnemer_index` (`id_deelnemer`);

--
-- Indexes for table `intake_mogelijkheden`
--
ALTER TABLE `intake_mogelijkheden`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainingen`
--
ALTER TABLE `trainingen`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aanmeldingen`
--
ALTER TABLE `aanmeldingen`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ceremonies`
--
ALTER TABLE `ceremonies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `deelnemers`
--
ALTER TABLE `deelnemers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `intakegesprekken`
--
ALTER TABLE `intakegesprekken`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `intake_mogelijkheden`
--
ALTER TABLE `intake_mogelijkheden`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `trainingen`
--
ALTER TABLE `trainingen`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
