-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2025 at 12:03 PM
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
-- Table structure for table `mt_pegawai`
--

CREATE TABLE `mt_pegawai` (
  `mt_pegawai_id` int(6) NOT NULL,
  `kd_pegawai` char(10) NOT NULL,
  `nama` varchar(75) NOT NULL,
  `jenis_kelamin` enum('P','L') DEFAULT NULL,
  `role_id` int(2) NOT NULL,
  `penempatan_id` int(10) DEFAULT NULL,
  `email` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mt_pegawai`
--

INSERT INTO `mt_pegawai` (`mt_pegawai_id`, `kd_pegawai`, `nama`, `jenis_kelamin`, `role_id`, `penempatan_id`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, '9000000586', 'Agustin Setya', 'P', 1, NULL, 'agustin@gmail.com', '$2y$10$P6bTD/iV6ghdA86GAUEAGe39izhoNIMdQOcQpBGMBoJ/MkRcBsl3e', '2025-08-18 19:55:58', '2025-08-19 11:00:31'),
(2, '9000000123', 'Rian', 'L', 4, 1, 'rian@gmail.com', '$2y$10$vzD.MZ6TWnxSA6/ZuubkxOPbm92dCmDWF80G4Jnt7MTCCIhz6xUlO', '2025-08-18 19:55:58', '2025-08-21 09:32:45');

-- --------------------------------------------------------

--
-- Table structure for table `mt_pembelian`
--

CREATE TABLE `mt_pembelian` (
  `mt_pembelian_id` int(10) NOT NULL,
  `tg_pembelian` date NOT NULL,
  `gudang_id` int(10) NOT NULL,
  `berat_kelapa` int(10) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mt_pembelian`
--

INSERT INTO `mt_pembelian` (`mt_pembelian_id`, `tg_pembelian`, `gudang_id`, `berat_kelapa`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, '2025-08-21', 1, 570, '2025-08-21 09:35:47', 'agustin@gmail.com', '2025-08-21 09:35:47', '');

-- --------------------------------------------------------

--
-- Table structure for table `mt_pengolahan`
--

CREATE TABLE `mt_pengolahan` (
  `mt_pengolahan_id` int(10) NOT NULL,
  `pembelian_id` int(10) NOT NULL,
  `kd_pegawai` char(10) NOT NULL,
  `berat_daging` int(10) NOT NULL,
  `berat_kopra` int(10) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_gudang`
--

CREATE TABLE `m_gudang` (
  `m_gudang_id` int(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_gudang`
--

INSERT INTO `m_gudang` (`m_gudang_id`, `nama`, `status`, `created_at`) VALUES
(1, 'Gudang Luluk', 1, '2025-08-21 09:32:09'),
(2, 'Gudang Sutik', 1, '2025-08-21 09:32:09');

-- --------------------------------------------------------

--
-- Table structure for table `m_komponen_gaji`
--

CREATE TABLE `m_komponen_gaji` (
  `m_komponen_gaji_id` int(5) NOT NULL,
  `gudang_id` int(10) NOT NULL,
  `takaran_daging` int(10) NOT NULL,
  `upah_takaran_daging` float NOT NULL,
  `takaran_kopra` int(10) NOT NULL,
  `upah_takaran_kopra` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_komponen_gaji`
--

INSERT INTO `m_komponen_gaji` (`m_komponen_gaji_id`, `gudang_id`, `takaran_daging`, `upah_takaran_daging`, `takaran_kopra`, `upah_takaran_kopra`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 750, 560000, 350, 10000, '2025-08-21 10:12:03', 'agustin@gmail.com', '2025-08-21 10:12:03', ''),
(2, 2, 750, 500000, 350, 8000, '2025-08-21 10:12:03', 'agustin@gmail.com', '2025-08-21 10:12:03', '');

-- --------------------------------------------------------

--
-- Table structure for table `m_role`
--

CREATE TABLE `m_role` (
  `m_role_id` int(2) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `role_scope` enum('all','gudang') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_role`
--

INSERT INTO `m_role` (`m_role_id`, `nama`, `role_scope`) VALUES
(1, 'Administrator', 'all'),
(2, 'Validator', 'gudang'),
(3, 'HRD', 'all'),
(4, 'Admin Gudang', 'gudang');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mt_pegawai`
--
ALTER TABLE `mt_pegawai`
  ADD PRIMARY KEY (`mt_pegawai_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `penempatan_id` (`penempatan_id`),
  ADD KEY `kd_pegawai` (`kd_pegawai`);

--
-- Indexes for table `mt_pembelian`
--
ALTER TABLE `mt_pembelian`
  ADD PRIMARY KEY (`mt_pembelian_id`),
  ADD KEY `gudang_id` (`gudang_id`);

--
-- Indexes for table `mt_pengolahan`
--
ALTER TABLE `mt_pengolahan`
  ADD PRIMARY KEY (`mt_pengolahan_id`),
  ADD KEY `pembelian_id` (`pembelian_id`),
  ADD KEY `kd_pegawai` (`kd_pegawai`);

--
-- Indexes for table `m_gudang`
--
ALTER TABLE `m_gudang`
  ADD PRIMARY KEY (`m_gudang_id`),
  ADD KEY `m_gudang_id` (`m_gudang_id`);

--
-- Indexes for table `m_komponen_gaji`
--
ALTER TABLE `m_komponen_gaji`
  ADD PRIMARY KEY (`m_komponen_gaji_id`),
  ADD KEY `gudang_id` (`gudang_id`);

--
-- Indexes for table `m_role`
--
ALTER TABLE `m_role`
  ADD PRIMARY KEY (`m_role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mt_pegawai`
--
ALTER TABLE `mt_pegawai`
  MODIFY `mt_pegawai_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mt_pembelian`
--
ALTER TABLE `mt_pembelian`
  MODIFY `mt_pembelian_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mt_pengolahan`
--
ALTER TABLE `mt_pengolahan`
  MODIFY `mt_pengolahan_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_gudang`
--
ALTER TABLE `m_gudang`
  MODIFY `m_gudang_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `m_komponen_gaji`
--
ALTER TABLE `m_komponen_gaji`
  MODIFY `m_komponen_gaji_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `m_role`
--
ALTER TABLE `m_role`
  MODIFY `m_role_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mt_pegawai`
--
ALTER TABLE `mt_pegawai`
  ADD CONSTRAINT `fk_pegawai_penempatan` FOREIGN KEY (`penempatan_id`) REFERENCES `m_gudang` (`m_gudang_id`),
  ADD CONSTRAINT `mt_pegawai_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `m_role` (`m_role_id`);

--
-- Constraints for table `mt_pembelian`
--
ALTER TABLE `mt_pembelian`
  ADD CONSTRAINT `fk_pembelian_gudang` FOREIGN KEY (`gudang_id`) REFERENCES `m_gudang` (`m_gudang_id`);

--
-- Constraints for table `mt_pengolahan`
--
ALTER TABLE `mt_pengolahan`
  ADD CONSTRAINT `fk_pengolahan_pegawai` FOREIGN KEY (`kd_pegawai`) REFERENCES `mt_pegawai` (`kd_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengolahan_pembelian` FOREIGN KEY (`pembelian_id`) REFERENCES `mt_pembelian` (`mt_pembelian_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `m_komponen_gaji`
--
ALTER TABLE `m_komponen_gaji`
  ADD CONSTRAINT `fk_gaji_gudang` FOREIGN KEY (`gudang_id`) REFERENCES `m_gudang` (`m_gudang_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
