-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 26, 2025 at 01:26 PM
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
  `id` int NOT NULL,
  `id_deelnemer` int NOT NULL,
  `id_training` int NOT NULL,
  `betaal_status` smallint NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `aanmeldingen`
--

INSERT INTO `aanmeldingen` (`id`, `id_deelnemer`, `id_training`, `betaal_status`, `created_at`) VALUES
(1, 5, 1, 1, '2025-09-01 11:51:45'),
(2, 6, 1, 0, '2025-09-02 11:51:45'),
(3, 7, 1, 0, '2025-09-02 11:51:45'),
(5, 9, 1, 0, '2025-09-03 11:51:45'),
(6, 10, 3, 2, '2025-09-09 11:51:45'),
(7, 11, 5, 0, '2025-09-09 11:51:45'),
(8, 13, 5, 0, '2025-09-09 11:51:45'),
(9, 13, 3, 2, '2025-09-10 11:51:45'),
(10, 14, 3, 1, '2025-09-11 11:51:45'),
(11, 15, 3, 1, '2025-09-11 11:51:45'),
(12, 5, 5, 2, '2025-09-11 11:51:45'),
(15, 1, 7, 2, '2025-09-15 11:51:45'),
(16, 9, 7, 1, '2025-09-15 11:51:45'),
(19, 5, 7, 2, '2025-09-15 11:51:45'),
(21, 5, 3, 0, '2025-09-19 11:51:45'),
(25, 5, 18, 1, '2025-09-21 11:51:45'),
(26, 22, 18, 0, '2025-09-22 11:51:45'),
(27, 23, 18, 2, '2025-09-24 11:51:45'),
(31, 5, 20, 0, '2025-09-25 11:51:45'),
(32, 24, 20, 1, '2025-09-26 11:51:45');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `email` text NOT NULL,
  `wachtwoord` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `wachtwoord`) VALUES
(1, 'admin@test.mail', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `ceremonies`
--

CREATE TABLE `ceremonies` (
  `id` int NOT NULL,
  `id_deelnemer` int NOT NULL,
  `datum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ceremonies`
--

INSERT INTO `ceremonies` (`id`, `id_deelnemer`, `datum`) VALUES
(1, 5, '2025-11-11'),
(2, 13, '2025-12-08'),
(4, 14, '2025-10-12');

-- --------------------------------------------------------

--
-- Table structure for table `deelnemers`
--

CREATE TABLE `deelnemers` (
  `id` int NOT NULL,
  `voornaam` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tussenvoegsel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `achternaam` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` text NOT NULL,
  `telefoon_nummer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `wachtwoord` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `deelnemers`
--

INSERT INTO `deelnemers` (`id`, `voornaam`, `tussenvoegsel`, `achternaam`, `email`, `telefoon_nummer`, `wachtwoord`) VALUES
(1, 'Test', NULL, 'Tester', 'test@email.test', NULL, '123'),
(5, 'Test', NULL, 'Tester', 'test2@email.test', '06-12345678', '123'),
(6, 'Test3', 'de', 'Tester', 'test3@email.test', NULL, '123'),
(7, 'Test4', NULL, 'Tester', 'test4@email.test', NULL, '123'),
(9, 'Test5', NULL, 'Tester', 'test5@email.test', NULL, '123'),
(10, 'Test6', 'der', 'Tester', 'test6@email.test', NULL, '123'),
(11, 'Test7', NULL, 'Tester', 'test7@email.test', NULL, '1234'),
(12, 'Test8', NULL, 'Tester', 'test8@email.test', NULL, '123'),
(13, 'Test9', NULL, 'Tester', 'test9@email.test', NULL, '123'),
(14, 'Test10', NULL, 'Tester', 'test10@email.test', '06-87654321', '123'),
(15, 'Test11', NULL, 'Tester', 'test11@email.test', NULL, '123'),
(22, 'Test12', 'de', 'Tester', 'test12@mail.test', NULL, '123'),
(23, 'Test13', NULL, 'Tester', 'test13@mail.test', NULL, '123'),
(24, 'Test14', NULL, 'Tester', 'test14@mail.nl', NULL, '123');

-- --------------------------------------------------------

--
-- Table structure for table `intakegesprekken`
--

CREATE TABLE `intakegesprekken` (
  `id` int NOT NULL,
  `id_deelnemer` int NOT NULL,
  `datum` date NOT NULL,
  `begin_tijd` time NOT NULL,
  `eind_tijd` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `intakegesprekken`
--

INSERT INTO `intakegesprekken` (`id`, `id_deelnemer`, `datum`, `begin_tijd`, `eind_tijd`) VALUES
(2, 5, '2025-09-24', '13:15:00', '16:15:00'),
(4, 13, '2025-09-30', '09:40:00', '11:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `trainingen`
--

CREATE TABLE `trainingen` (
  `id` int NOT NULL,
  `start_moment` datetime NOT NULL,
  `start_moment_2` datetime NOT NULL,
  `start_moment_3` datetime NOT NULL,
  `start_moment_4` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `trainingen`
--

INSERT INTO `trainingen` (`id`, `start_moment`, `start_moment_2`, `start_moment_3`, `start_moment_4`) VALUES
(1, '2025-07-01 10:00:00', '2025-07-15 14:40:00', '2025-07-29 08:00:00', '2025-08-12 15:00:00'),
(3, '2025-09-09 14:00:00', '2025-09-16 10:00:00', '2025-09-23 13:00:00', '2025-09-30 09:00:00'),
(5, '2025-09-15 16:00:00', '2025-09-16 13:50:00', '2025-09-23 09:15:00', '2025-10-03 10:45:00'),
(7, '2025-12-01 12:40:00', '2025-12-08 07:00:00', '2025-12-15 13:15:00', '2025-12-22 16:00:00'),
(18, '2026-01-01 00:00:00', '2026-01-08 18:30:00', '2026-01-15 09:45:00', '2026-01-22 21:20:00'),
(20, '2026-02-02 14:00:00', '2026-02-09 16:15:00', '2026-02-16 18:18:00', '2026-02-23 08:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aanmeldingen`
--
ALTER TABLE `aanmeldingen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ceremonies`
--
ALTER TABLE `ceremonies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deelnemers`
--
ALTER TABLE `deelnemers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `intakegesprekken`
--
ALTER TABLE `intakegesprekken`
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ceremonies`
--
ALTER TABLE `ceremonies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `deelnemers`
--
ALTER TABLE `deelnemers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `intakegesprekken`
--
ALTER TABLE `intakegesprekken`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `trainingen`
--
ALTER TABLE `trainingen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
