-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2025 at 11:27 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cvkelapa`
--

-- --------------------------------------------------------

--
-- Table structure for table `mt_user`
--

CREATE TABLE `mt_user` (
  `mt_user_id` int(6) NOT NULL,
  `kd_pegawai` char(10) NOT NULL,
  `nama` varchar(75) NOT NULL,
  `role_id` int(2) NOT NULL,
  `email` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mt_user`
--

INSERT INTO `mt_user` (`mt_user_id`, `kd_pegawai`, `nama`, `role_id`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, '9000000586', 'Agustin Setya', 1, 'agustin@gmail.com', '$2y$10$mITHVf038SzQruIoYbBVQOUJTwq1FaJ80UwRUmcPjmm/YvZ4.87rG', '2025-08-18 19:55:58', '2025-08-18 19:55:58'),
(2, '9000000123', 'Rini', 2, 'rini@gmail.com', '$2y$10$vzD.MZ6TWnxSA6/ZuubkxOPbm92dCmDWF80G4Jnt7MTCCIhz6xUlO', '2025-08-18 19:55:58', '2025-08-18 19:55:58');

-- --------------------------------------------------------

--
-- Table structure for table `m_role`
--

CREATE TABLE `m_role` (
  `m_role_id` int(2) NOT NULL,
  `nama` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_role`
--

INSERT INTO `m_role` (`m_role_id`, `nama`) VALUES
(1, 'All'),
(2, 'Admin Gudang A'),
(3, 'HRD'),
(4, 'Admin Gudang B');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mt_user`
--
ALTER TABLE `mt_user`
  ADD PRIMARY KEY (`mt_user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `m_role`
--
ALTER TABLE `m_role`
  ADD PRIMARY KEY (`m_role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mt_user`
--
ALTER TABLE `mt_user`
  MODIFY `mt_user_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `m_role`
--
ALTER TABLE `m_role`
  MODIFY `m_role_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mt_user`
--
ALTER TABLE `mt_user`
  ADD CONSTRAINT `mt_user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `m_role` (`m_role_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
