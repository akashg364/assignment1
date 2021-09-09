-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2021 at 11:56 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assignmentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

CREATE TABLE `keys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT 0,
  `is_private_key` tinyint(1) NOT NULL DEFAULT 0,
  `ip_addresses` text DEFAULT NULL,
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `keys`
--

INSERT INTO `keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(1, 1, 'MyRandomToken', 0, 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblrouter`
--

CREATE TABLE `tblrouter` (
  `id` int(11) NOT NULL,
  `sapid` char(18) NOT NULL,
  `hostname` char(14) NOT NULL,
  `loopback` int(10) UNSIGNED NOT NULL,
  `mac_address` char(17) NOT NULL,
  `status` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedon` datetime NOT NULL DEFAULT current_timestamp(),
  `isDeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblrouter`
--

INSERT INTO `tblrouter` (`id`, `sapid`, `hostname`, `loopback`, `mac_address`, `status`, `createdon`, `updatedon`, `isDeleted`) VALUES
(1, '1234', 'testt', 2130706433, 'test', 1, '2021-09-09 15:02:12', '2021-09-09 15:21:25', 0),
(2, '9874', 'test', 2130706434, 'test31212', 1, '2021-09-09 15:21:40', '2021-09-09 15:21:51', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblrouter`
--
ALTER TABLE `tblrouter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sapid` (`sapid`,`hostname`,`loopback`,`mac_address`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblrouter`
--
ALTER TABLE `tblrouter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
