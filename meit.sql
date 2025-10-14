-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 14, 2025 at 02:06 PM
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
(2, 'raphael@admin.nl', '$2y$12$LOrT6xf7uv1ivWdxUQcLkOrO/RJPwRP8VBK4LhuHEHsemDKSao572');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(6, 'Raphael', NULL, 'EazyOnline', 'raphael@deelnemer.nl', NULL, '$2y$12$WSYwgwICYumAsC4fXWKvv.gjIyzwvkE0/52Pvk8Mzr/bylOq/nxTS', NULL, '2025-10-14 12:00:42');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ajaTDPEisNQAmR6t0iLP7NuKYpqOOrQV6VkE8y7R', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoicWxHOUF5VU1JblJtaHl6eHU4QTJadUh5MlNmRENCZ000NXBhZVM5NCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9tZWl0LnRlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjU6ImxvZ2luIjtiOjA7czoyOiJpZCI7TjtzOjU6ImFkbWluIjtOO3M6NToiZW1haWwiO047fQ==', 1760450678),
('sppuxcWFii6wWJxWkR3bRDKfqCX85hR2XEp735HN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiYm5WdU5KdGFtYlhMclVrMTR1UGZieTJqbDBrWU8yMlZLdjVLZ0tONCI7czo1OiJsb2dpbiI7YjoxO3M6MjoiaWQiO2k6MjtzOjU6ImVtYWlsIjtOO3M6NToiYWRtaW4iO2I6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNjoiaHR0cDovL21laXQudGVzdC9vdmVyemljaHQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1760450709);

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `trainingen`
--
ALTER TABLE `trainingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ceremonies`
--
ALTER TABLE `ceremonies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `deelnemers`
--
ALTER TABLE `deelnemers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `trainingen`
--
ALTER TABLE `trainingen`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
