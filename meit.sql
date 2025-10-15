-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 15, 2025 at 12:37 PM
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
(56, 2, 31, 2, '2025-10-14 10:51:20', '2025-10-14 10:51:36', 44400, 0, NULL, 'fake_f653b6d136b8bc5e', 'cs_test_a15VpCcMMaJ4gZukKlDzvjraHcHU54TZOIo0KjcXHm9f8uci2ocwsKUW2W', NULL, NULL, 'pietjansen@email.nl'),
(57, 3, 31, 0, '2025-10-14 11:08:27', '2025-10-14 11:08:27', 0, 0, NULL, 'fake_167e7c5e96012066', NULL, NULL, NULL, 'chrisvanderheiden@email.nl'),
(58, 4, 31, 1, '2025-10-14 11:11:08', '2025-10-14 11:11:08', 22200, 22200, '2025-10-14 00:00:00', 'fake_2167522df1471ac9', NULL, NULL, NULL, 'geertjanvandepol@email.nl'),
(59, 1, 31, 2, '2025-10-14 11:12:23', '2025-10-14 11:12:23', 44400, 0, NULL, 'fake_3762712cb16c2ea2', NULL, NULL, NULL, 'sannemol@email.nl'),
(60, 1, 30, 0, '2025-10-14 11:15:23', '2025-10-14 11:15:23', 0, 0, NULL, 'fake_ae01d3473113ca4b', NULL, NULL, NULL, 'sannemol@email.nl'),
(61, 5, 30, 1, '2025-10-14 11:18:20', '2025-10-14 11:18:20', 22200, 22200, '2025-10-27 00:00:00', 'fake_5dea458a3756b2cc', NULL, NULL, NULL, 'lennybloem@email.com');

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
(1, 'admin@test.mail', '$2y$12$Fl5iMTl2ou7J6mycn6NZZuLdbY2wmYRY0Pcks6NJVxTU0Dt02hVYm'),
(2, 'raphael@admin.nl', '$2y$12$LOrT6xf7uv1ivWdxUQcLkOrO/RJPwRP8VBK4LhuHEHsemDKSao572'),
(3, 'jacelyn@admin.nl', '$2y$12$6/uNVkt2yWHUC3/yDEvP6eLWgnS.r2NwmaZclDeonFW.Ux6Zp97au');

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
(8, 1, '2025-10-16', '2025-10-14 13:36:55');

-- --------------------------------------------------------

--
-- Table structure for table `deelnemers`
--

CREATE TABLE `deelnemers` (
  `id` bigint UNSIGNED NOT NULL,
  `voornaam` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tussenvoegsel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `achternaam` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefoon_nummer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `wachtwoord` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deelnemers`
--

INSERT INTO `deelnemers` (`id`, `voornaam`, `tussenvoegsel`, `achternaam`, `email`, `telefoon_nummer`, `wachtwoord`, `created_at`, `updated_at`) VALUES
(1, 'Sanne', NULL, 'Mol', 'sannemol@email.nl', NULL, '$2y$12$JxdV4QzmShAdlQCzu2inE.Pevx977hkKbTsHfyL6WwsI1xzWdNuPW', NULL, '2025-10-14 11:04:00'),
(2, 'Piet', NULL, 'Jansen', 'pietjansen@email.com', NULL, '$2y$12$vXWjGt4MTo8Y2dLF8sTcGep2Sg2l/YZIBtgdyvYRidlQNgf8SC7bi', NULL, '2025-10-14 12:01:35'),
(3, 'Christien', 'van der', 'Heiden', 'chrisvanderheiden@email.nl', NULL, '$2y$12$J9VUwWviRge6TKrKeKsxcuTr5gyUFBnyntx.sSWI.6p6LfLbv.7Xi', NULL, '2025-10-14 12:02:26'),
(4, 'Geert-Jan', 'van de', 'Pol', 'geertjanvandepol@email.nl', '06-12345678', '$2y$12$j3H3ptJK2FueGF1v7tMpdOU367Mstr0Yw/8LaR4hTuDqArM0rn4UG', NULL, '2025-10-14 12:02:51'),
(5, 'Lenny', NULL, 'Bloem', 'lennybloem@email.com', '06-12345678', '$2y$12$3DCGMfiaccVNBgvYBmwGK.PxXVhjFwxCxKLZyUMaeaMh7VAm8xkS2', NULL, '2025-10-14 12:04:32'),
(6, 'Raphael', NULL, 'EazyOnline', 'raphael@deelnemer.nl', NULL, '$2y$12$WSYwgwICYumAsC4fXWKvv.gjIyzwvkE0/52Pvk8Mzr/bylOq/nxTS', NULL, '2025-10-14 12:00:42'),
(7, 'Jacelyn', NULL, 'Blok', 'jacelyn@meit.nl ', NULL, '$2y$12$dj5qM33SFmDe8NXPahO/V.a2LQc5o64Cil/SfMqSAe7q1aIJFHmsO', NULL, '2025-10-15 10:35:40');

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
(24, 5, '2025-10-15', '12:00:00', '13:00:00', '2025-10-14 13:24:35'),
(26, 2, '2025-10-28', '10:00:00', '11:00:00', '2025-10-14 14:01:49'),
(27, 4, '2025-10-15', '15:00:00', '16:00:00', '2025-10-14 14:03:17');

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
(36, '2025-10-23', '14:00:00', '20:00:00', '2025-10-14 13:24:11'),
(37, '2025-10-15', '10:00:00', '12:00:00', '2025-10-14 13:24:35'),
(40, '2025-11-02', '10:00:00', '18:00:00', '2025-10-14 13:30:56'),
(41, '2025-11-05', '13:00:00', '15:15:00', '2025-10-14 13:31:21'),
(42, '2025-10-18', '10:15:00', '13:00:00', '2025-10-14 13:32:24'),
(43, '2025-10-28', '08:00:00', '10:00:00', '2025-10-14 14:01:49'),
(44, '2025-10-28', '11:00:00', '16:00:00', '2025-10-14 14:01:49'),
(45, '2025-10-15', '13:00:00', '15:00:00', '2025-10-14 14:03:17'),
(46, '2025-10-15', '16:00:00', '17:00:00', '2025-10-14 14:03:17');

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
(30, '2025-11-03 15:00:00', '2025-11-10 11:45:00', '2025-11-17 12:30:00', '2025-11-24 16:00:00'),
(31, '2025-10-21 14:00:00', '2025-10-29 12:00:00', '2025-11-07 14:00:00', '2025-11-12 09:00:00'),
(32, '2025-12-04 14:00:00', '2025-12-11 14:00:00', '2025-12-18 14:00:00', '2025-12-25 14:00:00'),
(33, '2025-10-13 11:00:00', '2025-10-23 10:00:00', '2025-11-01 16:00:00', '2025-11-27 08:00:00');

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ceremonies`
--
ALTER TABLE `ceremonies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `deelnemers`
--
ALTER TABLE `deelnemers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `intakegesprekken`
--
ALTER TABLE `intakegesprekken`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `intake_mogelijkheden`
--
ALTER TABLE `intake_mogelijkheden`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `trainingen`
--
ALTER TABLE `trainingen`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
