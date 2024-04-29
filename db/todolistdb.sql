-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2024 at 11:42 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `todolistdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounttable`
--

CREATE TABLE `accounttable` (
  `user_id` int(11) NOT NULL,
  `nameOfUser` varchar(100) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `userPassword` varchar(200) NOT NULL,
  `bg_color` varchar(50) DEFAULT NULL,
  `sectionId` varchar(150) NOT NULL,
  `positionId` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounttable`
--

INSERT INTO `accounttable` (`user_id`, `nameOfUser`, `userName`, `userPassword`, `bg_color`, `sectionId`, `positionId`) VALUES
(1, 'Admin', 'admin', 'YWRtaW4=', '#00ffff', '1', '1'),
(2, 'Uno San', 'unosan', 'dW5vc2Fu', '#ace5ee', '1', '1'),
(3, 'Francis Cristobal', 'fcristobal', 'ZmNyaXN0b2JhbA==', '#ace5ee', '1', '1'),
(4, 'Uno san', 'Uno', 'VW5vMw==', '#89cff0', '2', '1'),
(5, 'Arvin', 'Arvin', 'QXJ2aW4=', '#ffbf00', '2', '4'),
(6, 'Daryl', 'Daryl', 'RGFyeWw=', '#ffbf00', '2', '4'),
(7, 'Rain', 'Rain', 'UmFpbg==', '#89cff0', '2', '4'),
(8, 'Wilnie', 'Wilnie', 'V2lsbmll', '#ffbf00', '2', '4'),
(9, 'Francis', 'Francis', 'RnJhbmNpcw==', '#f0f8ff', '2', '1'),
(10, 'Jhona', 'Jhona', 'SmhvbmE=', '#ffbf00', '3', '4'),
(11, 'Norj', 'Norj', 'Tm9yag==', '#bf94e4', '3', '4'),
(12, 'Kim', 'Kim', 'S2lt', '#00ffff', '3', '4'),
(13, 'Irene', 'Irene', 'SXJlbmU=', '#ffbf00', '3', '3'),
(14, 'Uno', 'Uno', 'VW5vMQ==', '#bf94e4', '3', '1'),
(15, 'Francis', 'Francis', 'RnJhbmNpczE=', '#ace5ee', '3', '1'),
(16, 'Dang', 'Dang', 'RGFuZw==', '#ed872d', '3', '4'),
(17, 'Aure', 'Aure', 'QXVyZQ==', '#a4c639', '3', '4'),
(18, 'Rhona', 'Rhona', 'UmhvbmE=', '#a4c639', '3', '4'),
(19, 'Nenneth', 'Nenneth', 'TmVubmV0aA==', '#89cff0', '3', '4'),
(20, 'Jen', 'Jen', 'SmVu', '#ed872d', '3', '4'),
(21, 'Mariz', 'Mariz', 'TWFyaXo=', '#bf94e4', '3', '4'),
(22, 'Uno', 'Uno', 'VW5vMg==', '#ffe135', '4', '1'),
(23, 'Chona', 'Chona', 'Q2hvbmE=', '#ffbf00', '4', '3'),
(24, 'Francis', 'Francis', 'RnJhbmNpczI=', '#bf94e4', '4', '1'),
(25, 'Lina Ramos', 'Lina', 'TGluYQ==', '#89cff0', '4', '4'),
(26, 'Joselle Dela Cruz', 'Joselle', 'Sm9zZWxsZQ==', '#f0f8ff', '4', '4'),
(27, 'Myrene Pantua', 'Myrene', 'TXlyZW5l', '#8c92ac', '4', '4'),
(28, 'Shaina Dejito', 'Shaina', 'U2hhaW5h', '#bf94e4', '4', '4'),
(29, 'Mary Jane', 'Jane', 'SmFuZQ==', '#ed872d', '4', '4'),
(30, 'Joylyn Guinto', 'Joylyn', 'Sm95bHlu', '#ffe135', '4', '4'),
(31, 'Riza', 'Riza', 'Uml6YQ==', '#f0f8ff', '2', '4'),
(32, 'Angelu', 'Angelu', 'QW5nZWx1', '#bf94e4', '2', '4'),
(33, 'Mira', 'Mira', 'TWlyYQ==', '#a4c639', '3', '4'),
(34, 's', 's', 'cw==', '#f0f8ff', '1', '4');

-- --------------------------------------------------------

--
-- Table structure for table `columntable`
--

CREATE TABLE `columntable` (
  `columnId` int(11) NOT NULL,
  `columnName` varchar(50) NOT NULL,
  `sectionId` int(11) NOT NULL,
  `bg_color` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `columntable`
--

INSERT INTO `columntable` (`columnId`, `columnName`, `sectionId`, `bg_color`) VALUES
(1, 'To-Do', 2, '#3f99f2'),
(2, 'On-Going', 2, '#F4CE46'),
(3, 'Done', 2, '#00B961'),
(4, 'To-Do', 3, '#3f99f2'),
(5, 'On-Going', 3, '#F4CE46'),
(6, 'Done', 3, '#00B961'),
(7, 'To-Do', 4, '#3f99f2'),
(8, 'On-Going', 4, '#F4CE46'),
(9, 'Done', 4, '#0cb800'),
(10, 'On-Hold', 2, '#cf4f4f'),
(11, 'Waiting for Reply', 2, '#f0f24a'),
(13, 'To-Do', 1, '#33fcff'),
(14, 'New column', 1, '#ff0000'),
(15, 'Verified', 4, '#000000');

-- --------------------------------------------------------

--
-- Table structure for table `positiontable`
--

CREATE TABLE `positiontable` (
  `positionId` int(11) NOT NULL,
  `positionName` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `positiontable`
--

INSERT INTO `positiontable` (`positionId`, `positionName`) VALUES
(1, 'Admin'),
(2, 'Manager'),
(3, 'Supervisor'),
(4, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `sectiontable`
--

CREATE TABLE `sectiontable` (
  `sectionId` int(11) NOT NULL,
  `sectionName` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sectiontable`
--

INSERT INTO `sectiontable` (`sectionId`, `sectionName`) VALUES
(1, 'IT'),
(2, 'AUTO3'),
(3, 'Auto1'),
(4, 'Auto2');

-- --------------------------------------------------------

--
-- Table structure for table `tasktable`
--

CREATE TABLE `tasktable` (
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_name` varchar(100) NOT NULL,
  `task_description` varchar(255) NOT NULL,
  `task_remark` text DEFAULT NULL,
  `task_status` int(1) NOT NULL,
  `task_level` smallint(6) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `task_date_start` datetime NOT NULL,
  `task_date_end` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasktable`
--

INSERT INTO `tasktable` (`task_id`, `user_id`, `task_name`, `task_description`, `task_remark`, `task_status`, `task_level`, `display_order`, `task_date_start`, `task_date_end`) VALUES
(2, 5, 'Update of improvement activity plan for PPES', '', NULL, 1, 3, 3, '2024-01-10 08:00:00', '2024-01-19 15:00:00'),
(3, 6, 'Installation of corrugated tube guide with a switch ', '', '21AB Circuit Board\n1 of 8 : done submission of JRF to JIG\n>> agreed target date to finish fabrication : 17-Jan-24 (Wed)\n\n7 of 8 : to follow remarks once the 1 of 8 corr tube guide with a switch already passed through 4M\n', 2, 2, 3, '2024-01-10 08:00:00', '2024-01-12 14:00:00'),
(4, 6, 'Modification of stopper for corrugated tube', '', '21AB Taping Board\n1 of 8 : done 4M (9-Jan-24 : Tue)\n7 of 8 : done 4M (18-Jan-24 : Thu)\n', 3, 2, 2, '2024-01-10 08:00:00', '2024-01-12 14:00:00'),
(7, 8, 'WI for repairing of corrugated tube', '', NULL, 3, 1, 1, '2024-01-10 09:30:00', '2024-01-10 14:00:00'),
(8, 12, 'TNJP transferred items', '1st batch (6 items)', NULL, 5, 3, 1, '2024-01-10 10:00:00', '2024-01-12 15:00:00'),
(9, 12, 'TNJP transferred items', '2nd batch (5 items)', NULL, 5, 3, 2, '2024-01-10 10:00:00', '2024-01-19 15:00:00'),
(10, 12, 'TNJP transferred items', '3rd batch (5 items)', NULL, 5, 3, 3, '2024-01-10 10:00:00', '2024-01-26 15:00:00'),
(11, 12, 'TNJP transferred items', '4th batch (5 items)', NULL, 5, 4, 4, '2024-01-10 10:00:00', '2024-02-02 15:00:00'),
(12, 12, 'TNJP transferred items', '5th batch (5 items)', NULL, 5, 4, 5, '2024-01-10 10:00:00', '2024-02-09 15:00:00'),
(13, 12, 'TNJP transferred items', '6th batch (5items)', NULL, 5, 4, 6, '2024-01-10 10:00:00', '2024-02-16 15:00:00'),
(14, 12, 'TNJP transferred items', '7th batch (5 items)', NULL, 5, 4, 7, '2024-01-10 10:00:00', '2024-02-23 15:00:00'),
(15, 12, 'TNJP transferred items', '8th batch (3 items)', NULL, 5, 4, 8, '2024-01-10 10:00:00', '2024-03-01 15:00:00'),
(16, 27, 'HET RM DN', '', NULL, 7, 4, 2, '2024-01-10 00:08:00', '2024-01-19 16:00:00'),
(17, 30, 'CCS 537 Model', '', NULL, 15, 1, 1, '2024-01-10 07:00:00', '2024-01-10 15:00:00'),
(19, 30, 'Taxan', '+2 Boards & Original mate', NULL, 9, 3, 1, '2024-01-10 14:00:00', '2024-01-20 08:00:00'),
(20, 29, 'PMX FG Non moving', '', 'on going', 8, 3, 1, '2024-01-10 14:00:00', '2024-02-01 08:00:00'),
(21, 26, 'AKI Pallet DN', '', NULL, 15, 3, 2, '2024-01-10 14:45:00', '2024-01-24 14:46:00'),
(22, 26, 'AUD CSS Result', '', NULL, 9, 3, 3, '2024-01-11 08:53:00', '2024-01-17 08:53:00'),
(23, 1, 'sample', 'sample', NULL, 1, 4, 0, '2024-01-11 09:03:00', '2024-01-11 09:03:00'),
(24, 1, 'sample', 'dsadas', NULL, 1, 4, 0, '2024-01-11 09:06:00', '2024-01-11 09:06:00'),
(25, 1, 'dasdas', 'dasda', NULL, 1, 2, 0, '2024-01-11 09:08:00', '2024-01-11 09:08:00'),
(26, 29, 'AOD CSS Result', '', NULL, 8, 3, 2, '2024-01-11 09:13:00', '2024-01-19 09:13:00'),
(27, 22, 'sdfadasdfa', 'asdfas', NULL, 1, 4, 0, '2024-01-11 10:19:00', '2024-01-11 01:19:00'),
(28, 1, 'dasda', 'dasdas', NULL, 14, 4, 2, '2024-01-11 09:25:00', '2024-01-11 09:26:00'),
(29, 1, 'dasd', 'dasda', NULL, 14, 4, 3, '2024-01-11 09:24:00', '2024-01-11 09:24:00'),
(30, 1, 'aaa', 'aa', NULL, 13, 4, 3, '2024-01-11 09:24:00', '2024-01-11 09:24:00'),
(31, 2, 'this is sample only', 'this is sample only', NULL, 14, 4, 1, '2024-01-20 09:28:00', '2024-01-27 09:29:00'),
(33, 30, '537 Revised CCS', '', NULL, 15, 1, 3, '2024-01-11 09:36:00', '2024-01-11 16:00:00'),
(34, 27, 'AOD Req. SQAM', '', NULL, 9, 3, 2, '2024-01-11 10:43:00', '2024-01-31 10:43:00'),
(35, 30, 'AUD Req. SQAM', '', NULL, 8, 3, 4, '2024-01-11 10:44:00', '2024-01-31 10:44:00'),
(36, 28, 'Hea Neaton RM PO', '', NULL, 15, 3, 4, '2024-01-12 10:51:00', '2024-01-17 10:51:00'),
(37, 5, 'The flow of QAR', '', NULL, 2, 2, 1, '2024-01-12 09:00:00', '2024-01-12 14:00:00'),
(38, 28, 'Joyson RM PO', '', NULL, 15, 3, 5, '2024-01-15 08:31:00', '2024-01-17 08:31:00'),
(39, 29, 'TDK RM Debit Note', '255.62', NULL, 7, 3, 0, '2024-01-16 09:44:00', '2024-01-19 09:44:00'),
(42, 30, 'HICZ Cost Down QL/ Price Notice', '', NULL, 7, 2, 0, '2024-01-17 08:23:00', '2024-01-19 08:23:00'),
(43, 30, 'CSR Activity Plan & Sales Dev\'t Review', '', NULL, 7, 3, 0, '2024-01-17 08:27:00', '2024-01-20 08:27:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounttable`
--
ALTER TABLE `accounttable`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `columntable`
--
ALTER TABLE `columntable`
  ADD PRIMARY KEY (`columnId`);

--
-- Indexes for table `positiontable`
--
ALTER TABLE `positiontable`
  ADD PRIMARY KEY (`positionId`);

--
-- Indexes for table `sectiontable`
--
ALTER TABLE `sectiontable`
  ADD PRIMARY KEY (`sectionId`);

--
-- Indexes for table `tasktable`
--
ALTER TABLE `tasktable`
  ADD PRIMARY KEY (`task_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounttable`
--
ALTER TABLE `accounttable`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `columntable`
--
ALTER TABLE `columntable`
  MODIFY `columnId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `positiontable`
--
ALTER TABLE `positiontable`
  MODIFY `positionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sectiontable`
--
ALTER TABLE `sectiontable`
  MODIFY `sectionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tasktable`
--
ALTER TABLE `tasktable`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
