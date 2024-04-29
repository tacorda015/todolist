-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2024 at 05:22 AM
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
(1, 'First User', 'firstUser', 'U2FtcGxlQDEyMw==', '#9cb6dd', '1', '4'),
(2, 'Second User', 'secondUser', 'U2FtcGxlQDEyMw==', '#22ddd0', '1', '4'),
(3, 'Third User', 'thirdUser', 'U2FtcGxlQDEyMw==', '#47e166', '1', '4'),
(4, 'Fourth User', 'fourthUser', 'U2FtcGxlQDEyMw==', '#eaea25', '1', '4'),
(5, 'Fifth User', 'fifthUser', 'U2FtcGxlQDEyMw==', '#fc6fff', '1', '4'),
(6, 'admin', 'admin', 'YWRtaW4=', '#00ffff', '1', '1'),
(7, 'IT Staff', 'it', 'U2FtcGxlQDEyMw==', '#f0f8ff', '5', '4'),
(8, 'd', 'd', 'ZA==', '#ffe135', '5', '4'),
(9, 'IT Admin', 'itadmin', 'YWRtaW4=', '#00ffff', '1', '1'),
(10, 'newstaff', 'newstaff', 'bmV3c3RhZmY=', '#8c92ac', '4', '3'),
(11, 'Staff One', '', 'c3RhZmZvbmU=', '#bf94e4', '4', '4'),
(12, 'Staff Two', 'stafftwo', 'U2FtcGxlQDEyMw==', '#89cff0', '4', '4'),
(13, '3rd Staff', '3rdstaff', 'U2FtcGxlQDEyMw==', '#00ffff', '4', '3'),
(14, 'sampl', 'sanple', 'c2FtcGxl', '#ed872d', '1', '2'),
(16, 'this is the name of staffs', 'thisisusernames', 'dGhpc2lzcGFzc3dvcmRz', '#bf94e4', '9', '2'),
(17, 'this is added of managers', 'NewUsers', 'TmV3VXNlcnM=', '#8c92ac', '', ''),
(18, 'this is the name of staffs', 'ssss', 'c3M=', '#8c92ac', '', ''),
(19, 'sss', 'ss', 'c3M=', '#ffbf00', '', ''),
(20, 'a', 'a', 'YQ==', '#00ffff', '', ''),
(21, 'a', 'a', 'YQ==', '#bf94e4', '', ''),
(22, 's', 's', 'cw==', '#00ffff', '', ''),
(23, 'as', 'as', 'YXM=', '#8c92ac', '1', '2'),
(24, 's', 's', 'cw==', '#89cff0', '1', '2'),
(25, 'rsa', 'rsa', 'cnNh', '#89cff0', '1', '4');

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
(2, 'Sales'),
(4, 'HRD'),
(5, 'Finance'),
(6, 'QCA'),
(8, 'Logistic'),
(9, 'Jig'),
(10, 'Machine');

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
(26, 3, 'todo number 1', 'todo number 1', 'sample', 2, 4, 2, '2024-01-07 15:30:00', '2024-01-13 15:30:00'),
(27, 3, 'a', 'a', NULL, 3, 1, 1, '2024-01-03 03:35:00', '2024-01-06 08:59:00'),
(29, 2, 'this is sample', 'this is the new sample', NULL, 2, 1, 3, '2024-01-04 11:48:00', '2024-01-04 11:48:00'),
(30, 11, 'Change Password', 'This is sample only', 'this is the remarks', 1, 3, 0, '2024-01-09 09:20:00', '2024-01-09 10:25:00'),
(31, 3, 'sample', 'sss', NULL, 3, 4, 2, '2024-01-10 09:54:00', '2024-01-11 09:55:00'),
(32, 5, 'sample', 'ss', 'Need a conformation\nNew ', 1, 4, 1, '2024-01-09 10:36:00', '2024-01-09 10:36:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounttable`
--
ALTER TABLE `accounttable`
  ADD PRIMARY KEY (`user_id`);

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `positiontable`
--
ALTER TABLE `positiontable`
  MODIFY `positionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sectiontable`
--
ALTER TABLE `sectiontable`
  MODIFY `sectionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tasktable`
--
ALTER TABLE `tasktable`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
