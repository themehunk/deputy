-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2022 at 03:04 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exports`
--

-- --------------------------------------------------------

--
-- Table structure for table `timesheet`
--

CREATE TABLE `timesheet` (
  `id` int(11) NOT NULL,
  `unid` varchar(100) NOT NULL,
  `fnam` varchar(100) NOT NULL,
  `lanme` varchar(100) NOT NULL,
  `area_name` varchar(100) NOT NULL,
  `ts_date` date NOT NULL,
  `ts_cost` float NOT NULL,
  `ts_total_time` float NOT NULL,
  `approved` int(11) NOT NULL,
  `ts_access_level` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timesheet`
--

INSERT INTO `timesheet` (`id`, `unid`, `fnam`, `lanme`, `area_name`, `ts_date`, `ts_cost`, `ts_total_time`, `approved`, `ts_access_level`) VALUES
(2, 'jamesrahim', 'James', 'Rahim', 'Kitchen', '2022-06-13', 142.7, 4.38, 0, ' Junior (19yrs or under)'),
(3, 'haymanhong', 'Hayman', 'Hong', 'Kitchen', '2022-06-14', 176.58, 8.13, 0, 'Employee'),
(4, 'jamesrahim', 'James', 'Rahim', 'Kitchen', '2022-06-15', 84.38, 5.18, 0, ' Junior (19yrs or under)'),
(5, 'samanthabailey', 'Samantha', 'Bailey', 'Kitchen', '2022-06-16', 80.72, 6.2, 0, 'Apprentice'),
(6, 'hannahstewart', 'Hannah', 'Stewart', 'Kitchen', '2022-06-16', 79.68, 6.12, 0, 'Apprentice'),
(7, 'haymanhong', 'Hayman', 'Hong', 'Kitchen', '2022-06-16', 129.23, 5.95, 0, 'Employee'),
(8, 'haymanhong', 'Hayman', 'Hong', 'Kitchen', '2022-06-17', 156.38, 7.2, 0, 'Employee'),
(9, 'jamesrahim', 'James', 'Rahim', 'Kitchen', '2022-06-17', 99.04, 6.08, 0, ' Junior (19yrs or under)'),
(10, 'aleeshia keilaalegado', 'Aleeshia Keila', 'Alegado', 'Kitchen', '2022-06-18', 214.38, 6.58, 0, 'Employee'),
(11, 'samanthabailey', 'Samantha', 'Bailey', 'Kitchen', '2022-06-18', 75.78, 5.82, 0, 'Apprentice'),
(12, 'luciabennett', 'Lucia', 'Bennett', 'Kitchen', '2022-06-18', 49.7, 3.05, 0, ' Junior (19yrs or under)'),
(13, 'jamesrahim', 'James', 'Rahim', 'Kitchen', '2022-06-18', 149.93, 7.67, 0, ' Junior (19yrs or under)'),
(14, 'haymanhong', 'Hayman', 'Hong', 'Kitchen', '2022-06-19', 254.12, 7.8, 0, 'Employee'),
(15, 'jamesrahim', 'James', 'Rahim', 'Kitchen', '2022-06-19', 191.96, 9.82, 0, ' Junior (19yrs or under)'),
(16, 'ethanhose', 'Ethan', 'Hose', 'Kitchen', '2022-06-19', 105.92, 6.5, 0, ' Junior (19yrs or under)'),
(17, 'haymanhong', 'Hayman', 'Hong', 'Kitchen', '2022-06-15', 0, 8.5, 0, 'Employee'),
(18, 'aleeshia keilaalegado', 'Aleeshia Keila', 'Alegado', 'Kitchen', '2022-06-17', 0, 6.5, 0, 'Employee'),
(19, 'davidchatelot', 'David', 'Chatelot', 'Lower Ground Cafe', '2022-06-13', 0, 8.45, 0, 'Employee'),
(20, 'harrymorgan', 'Harry', 'Morgan', 'Lower Ground Cafe', '2022-06-13', 275.14, 5.63, 0, 'Employee'),
(21, 'chloetaylor', 'Chloe', 'Taylor', 'Lower Ground Cafe', '2022-06-13', 257.26, 6.77, 0, ' Junior (19yrs or under)'),
(22, 'davidchatelot', 'David', 'Chatelot', 'Lower Ground Cafe', '2022-06-14', 0, 8.6, 0, 'Employee'),
(23, 'davidchatelot', 'David', 'Chatelot', 'Lower Ground Cafe', '2022-06-15', 0, 8.62, 0, 'Employee'),
(24, 'davidchatelot', 'David', 'Chatelot', 'Lower Ground Cafe', '2022-06-16', 0, 8.32, 0, 'Employee'),
(25, 'davidchatelot', 'David', 'Chatelot', 'Lower Ground Cafe', '2022-06-17', 0, 8.43, 0, 'Employee'),
(26, 'jayliedoan', 'Jaylie', 'Doan', 'Lower Ground Cafe', '2022-06-17', 0, 1, 0, 'WEP'),
(27, 'harrymorgan', 'Harry', 'Morgan', 'Lower Ground Cafe', '2022-06-18', 215.3, 7.93, 0, 'Employee'),
(28, 'freyabuxton', 'freya', 'buxton', 'Lower Ground Cafe', '2022-06-18', 127.68, 5.6, 0, ' Junior (19yrs or under)'),
(29, 'lukadagarin', 'Luka', 'Dagarin', 'Lower Ground Cafe', '2022-06-18', 113.96, 5.83, 0, ' Junior (19yrs or under)'),
(30, 'freyabuxton', 'freya', 'buxton', 'Lower Ground Cafe', '2022-06-19', 132.7, 5.82, 0, ' Junior (19yrs or under)'),
(31, 'harrymorgan', 'Harry', 'Morgan', 'Lower Ground Cafe', '2022-06-19', 255.75, 7.85, 0, 'Employee'),
(32, 'tomroberts', 'tom', 'roberts', 'Lower Ground Cafe', '2022-06-19', 89.3, 5.48, 0, ' Junior (19yrs or under)'),
(33, 'lukadagarin', 'Luka', 'Dagarin', 'Lower Ground Cafe', '2022-06-19', 116.31, 5.95, 0, ' Junior (19yrs or under)'),
(34, 'tomroberts', 'tom', 'roberts', 'Lower Ground Cafe', '2022-06-18', 0, 5.5, 0, ' Junior (19yrs or under)'),
(35, 'rodolfoandrade', 'Rodolfo', 'Andrade', 'Management', '2022-06-15', 143.41, 5.7, 0, 'Location Manager'),
(36, 'rodolfoandrade', 'Rodolfo', 'Andrade', 'Management', '2022-06-16', 140.14, 5.57, 0, 'Location Manager'),
(37, 'rodolfoandrade', 'Rodolfo', 'Andrade', 'Management', '2022-06-17', 239.77, 9.53, 0, 'Location Manager'),
(38, 'rodolfoandrade', 'Rodolfo', 'Andrade', 'Management', '2022-06-18', 432.44, 13.75, 0, 'Location Manager'),
(39, 'rodolfoandrade', 'Rodolfo', 'Andrade', 'Management', '2022-06-19', 222.67, 5.9, 0, 'Location Manager'),
(40, 'chloetaylor', 'Chloe', 'Taylor', 'Restaurant Floor', '2022-06-17', 185.48, 6.28, 0, ' Junior (19yrs or under)'),
(41, 'tobyrobertson', 'Toby', 'Robertson', 'Restaurant Floor', '2022-06-17', 185.48, 6.28, 0, ' Junior (19yrs or under)'),
(42, 'wednesdaysheedy', 'Wednesday', 'Sheedy', 'Restaurant Floor', '2022-06-17', 155.78, 5.4, 0, ' Junior (19yrs or under)'),
(43, 'freyabuxton', 'freya', 'buxton', 'Restaurant Floor', '2022-06-17', 117.13, 4.07, 0, ' Junior (19yrs or under)'),
(44, 'lailarahim', 'Laila', 'Rahim', 'Restaurant Floor', '2022-06-17', 57.64, 4.07, 0, ' Junior (19yrs or under)'),
(45, 'chloetaylor', 'Chloe', 'Taylor', 'Restaurant Floor', '2022-06-18', 282.47, 8.67, 0, ' Junior (19yrs or under)'),
(46, 'williamostergar', 'William', 'Ostergar', 'Restaurant Floor', '2022-06-18', 0, 20.68, 0, 'WEP'),
(47, 'tobyrobertson', 'Toby', 'Robertson', 'Restaurant Floor', '2022-06-18', 262.92, 8.07, 0, ' Junior (19yrs or under)'),
(48, 'wednesdaysheedy', 'Wednesday', 'Sheedy', 'Restaurant Floor', '2022-06-18', 254.82, 7.82, 0, ' Junior (19yrs or under)'),
(49, 'susannahpaget', 'Susannah', 'Paget', 'Restaurant Floor', '2022-06-18', 104.97, 5.37, 0, ' Junior (19yrs or under)'),
(50, 'samroberts', 'Sam', 'Roberts', 'Restaurant Floor', '2022-06-18', 128.04, 6.55, 0, ' Junior (19yrs or under)'),
(51, 'wednesdaysheedy', 'Wednesday', 'Sheedy', 'Restaurant Floor', '2022-06-19', 191.61, 5.88, 0, ' Junior (19yrs or under)'),
(52, 'susannahpaget', 'Susannah', 'Paget', 'Restaurant Floor', '2022-06-19', 111.81, 5.72, 0, ' Junior (19yrs or under)'),
(53, 'chloetaylor', 'Chloe', 'Taylor', 'Restaurant Floor', '2022-06-19', 151.8, 5.8, 0, ' Junior (19yrs or under)'),
(54, 'samroberts', 'Sam', 'Roberts', 'Restaurant Floor', '2022-06-19', 98.33, 5.03, 0, ' Junior (19yrs or under)'),
(55, 'lailarahim', 'Laila', 'Rahim', 'Restaurant Floor', '2022-06-19', 79.52, 4.88, 0, ' Junior (19yrs or under)'),
(56, 'roberttownsend', 'Robert', 'Townsend', 'Restaurant Floor', '2022-06-19', 78.71, 4.83, 0, ' Junior (19yrs or under)'),
(57, 'haymanhong', 'Hayman', 'Hong', '', '2022-06-13', 165.07, 7.6, 0, 'Employee'),
(58, 'hannahstewart', 'Hannah', 'Stewart', '', '2022-06-17', 84.63, 6.5, 0, 'Apprentice');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timesheet`
--
ALTER TABLE `timesheet`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timesheet`
--
ALTER TABLE `timesheet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
