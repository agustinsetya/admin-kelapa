-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2025 at 07:39 AM
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
-- Table structure for table `mt_gaji_pegawai`
--

CREATE TABLE `mt_gaji_pegawai` (
  `mt_gaji_pegawai_id` int(10) NOT NULL,
  `tg_proses_gaji` date NOT NULL,
  `kd_pegawai` char(10) NOT NULL,
  `gudang_id` int(10) NOT NULL,
  `upah_total_daging` float NOT NULL,
  `upah_total_kopra` float NOT NULL,
  `upah_produksi` float NOT NULL,
  `bonus` float NOT NULL,
  `total_gaji_bersih` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mt_gaji_pegawai`
--

INSERT INTO `mt_gaji_pegawai` (`mt_gaji_pegawai_id`, `tg_proses_gaji`, `kd_pegawai`, `gudang_id`, `upah_total_daging`, `upah_total_kopra`, `upah_produksi`, `bonus`, `total_gaji_bersih`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(16, '2025-09-02', '9000000123', 1, 9333330, 8000000, 17333300, 100000, 17333300, '2025-09-02 01:43:35', '', '2025-09-02 10:11:56', ''),
(17, '2025-09-02', '9000000123', 2, 2333330, 160000, 2493330, 0, 2493330, '2025-09-02 01:43:35', '', '2025-09-02 01:43:35', ''),
(18, '2025-09-02', '9000000123', 4, 0, 0, 0, 0, 0, '2025-09-02 02:16:28', '', '2025-09-02 02:16:28', ''),
(19, '2025-09-02', '9000000123', 6, 0, 0, 0, 0, 0, '2025-09-02 02:16:28', '', '2025-09-02 02:16:28', ''),
(20, '2025-09-02', '9000000586', 3, 0, 0, 0, 0, 0, '2025-09-02 02:16:34', '', '2025-09-02 02:16:34', '');

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
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mt_pegawai`
--

INSERT INTO `mt_pegawai` (`mt_pegawai_id`, `kd_pegawai`, `nama`, `jenis_kelamin`, `role_id`, `penempatan_id`, `created_at`, `updated_at`) VALUES
(1, '9000000586', 'Agustin Setya', 'P', 1, 1, '2025-08-18 19:55:58', '2025-08-27 17:21:01'),
(2, '9000000123', 'Rian', 'L', 4, 1, '2025-08-18 19:55:58', '2025-08-21 09:32:45');

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
(1, '2025-08-21', 1, 57, '2025-08-21 09:35:47', 'agustin@gmail.com', '2025-08-28 10:44:13', 'agustin@gmail.com'),
(2, '2025-08-28', 3, 275, '2025-08-28 10:47:47', '', '2025-08-29 01:11:57', 'agustin@gmail.com'),
(3, '2025-08-28', 2, 28, '2025-08-28 11:31:52', '', '2025-08-29 00:12:30', 'agustin@gmail.com'),
(4, '2025-08-29', 4, 520, '2025-08-29 01:11:44', '', '2025-08-29 01:11:44', '');

-- --------------------------------------------------------

--
-- Table structure for table `mt_pengeluaran`
--

CREATE TABLE `mt_pengeluaran` (
  `mt_pengeluaran_id` int(11) NOT NULL,
  `tg_pengeluaran` date NOT NULL,
  `ktg_pengeluaran_id` int(10) NOT NULL,
  `gudang_id` int(10) NOT NULL,
  `jumlah` int(10) NOT NULL,
  `biaya` float NOT NULL,
  `kd_pegawai` char(10) NOT NULL,
  `status` enum('BELUM_BAYAR','SUDAH_BAYAR') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mt_pengeluaran`
--

INSERT INTO `mt_pengeluaran` (`mt_pengeluaran_id`, `tg_pengeluaran`, `ktg_pengeluaran_id`, `gudang_id`, `jumlah`, `biaya`, `kd_pegawai`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(2, '2025-08-31', 1, 3, 11, 1100000, '9000000123', 'SUDAH_BAYAR', '2025-08-31 11:03:08', 'agustin@gmail.com', '2025-08-31 12:29:27', 'agustin@gmail.com'),
(3, '2025-08-31', 2, 2, 5, 400000, '9000000123', 'BELUM_BAYAR', '2025-08-31 12:29:47', 'agustin@gmail.com', '2025-08-31 12:33:44', 'agustin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `mt_pengolahan`
--

CREATE TABLE `mt_pengolahan` (
  `mt_pengolahan_id` int(10) NOT NULL,
  `tg_pengolahan` date NOT NULL DEFAULT current_timestamp(),
  `gudang_id` int(10) NOT NULL,
  `kd_pegawai` char(10) NOT NULL,
  `berat_daging` int(10) NOT NULL,
  `berat_kopra` int(10) NOT NULL,
  `bonus` float NOT NULL,
  `tg_proses_gaji` date DEFAULT NULL,
  `is_stat_gaji` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mt_pengolahan`
--

INSERT INTO `mt_pengolahan` (`mt_pengolahan_id`, `tg_pengolahan`, `gudang_id`, `kd_pegawai`, `berat_daging`, `berat_kopra`, `bonus`, `tg_proses_gaji`, `is_stat_gaji`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(3, '2025-08-26', 1, '9000000123', 250, 500, 0, NULL, 1, '2025-08-28 18:12:38', 'agustin@gmail.com', '2025-09-02 08:43:35', 'agustin@gmail.com'),
(4, '2025-08-19', 4, '9000000586', 200, 482, 0, NULL, 0, '2025-08-28 18:12:38', 'agustin@gmail.com', '2025-08-28 18:16:09', ''),
(5, '2025-08-20', 1, '9000000123', 250, 500, 0, NULL, 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-02 08:43:35', 'agustin@gmail.com'),
(6, '2025-08-20', 4, '9000000586', 200, 482, 0, NULL, 0, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-08-28 18:16:09', ''),
(7, '2025-08-12', 1, '9000000123', 250, 500, 0, NULL, 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-02 08:43:35', 'agustin@gmail.com'),
(8, '2025-08-14', 4, '9000000586', 200, 482, 0, NULL, 0, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-08-28 18:16:09', ''),
(9, '2025-08-14', 1, '9000000123', 250, 500, 0, NULL, 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-02 08:43:35', 'agustin@gmail.com'),
(10, '2025-08-06', 4, '9000000586', 200, 482, 0, NULL, 0, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-08-28 18:16:09', ''),
(11, '2025-08-07', 2, '9000000123', 250, 500, 0, NULL, 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-02 08:43:35', 'agustin@gmail.com'),
(12, '2025-08-12', 3, '9000000586', 200, 482, 0, '0000-00-00', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-02 09:16:34', 'agustin@gmail.com'),
(13, '2025-08-28', 4, '9000000123', 250, 500, 0, '0000-00-00', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-02 09:16:28', 'agustin@gmail.com'),
(14, '2025-08-28', 3, '9000000586', 200, 482, 0, '0000-00-00', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-02 09:16:34', 'agustin@gmail.com'),
(15, '2025-08-28', 6, '9000000123', 250, 500, 0, '0000-00-00', 1, '2025-08-28 18:14:55', 'agustin@gmail.com', '2025-09-02 09:16:28', 'agustin@gmail.com'),
(16, '2025-08-28', 4, '9000000586', 200, 482, 0, NULL, 0, '2025-08-28 18:14:55', 'agustin@gmail.com', '2025-08-28 18:14:55', '');

-- --------------------------------------------------------

--
-- Table structure for table `mt_user`
--

CREATE TABLE `mt_user` (
  `mt_user_id` int(6) NOT NULL,
  `kd_pegawai` char(10) NOT NULL,
  `email` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mt_user`
--

INSERT INTO `mt_user` (`mt_user_id`, `kd_pegawai`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, '9000000586', 'agustin@gmail.com', '$2y$10$P6bTD/iV6ghdA86GAUEAGe39izhoNIMdQOcQpBGMBoJ/MkRcBsl3e', '2025-08-18 19:55:58', '2025-08-31 02:24:18'),
(5, '9000000123', 'rian@gmail.com', '$2y$10$1MeQpwx8BusjTpEiXn.GK.Tqw7zeytQCN7fcZq4me.4e345v0eStK', '2025-09-02 07:16:54', '2025-09-02 07:16:54');

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
(2, 'Gudang Sutik', 1, '2025-08-21 09:32:09'),
(3, 'Gudang Berkah', 1, '2025-08-28 18:04:57'),
(4, 'Gudang Rejeki', 1, '2025-08-28 18:04:57'),
(5, 'Gudang Untung', 1, '2025-08-28 18:06:21'),
(6, 'Gudang Kabul', 1, '2025-08-28 18:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `m_komponen_gaji`
--

CREATE TABLE `m_komponen_gaji` (
  `m_komponen_gaji_id` int(5) NOT NULL,
  `gudang_id` int(10) NOT NULL,
  `takaran_daging` int(10) NOT NULL DEFAULT 0,
  `upah_takaran_daging` float NOT NULL DEFAULT 0,
  `takaran_kopra` int(10) NOT NULL DEFAULT 0,
  `upah_takaran_kopra` float NOT NULL DEFAULT 0,
  `gaji_driver` float NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_komponen_gaji`
--

INSERT INTO `m_komponen_gaji` (`m_komponen_gaji_id`, `gudang_id`, `takaran_daging`, `upah_takaran_daging`, `takaran_kopra`, `upah_takaran_kopra`, `gaji_driver`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 1, 75, 50000, 35, 10000, 0, '2025-08-21 10:12:03', 'agustin@gmail.com', '2025-08-27 14:14:35', 'agustin@gmail.com'),
(2, 2, 750, 500000, 350, 8000, 0, '2025-08-21 10:12:03', 'agustin@gmail.com', '2025-08-21 10:12:03', '');

-- --------------------------------------------------------

--
-- Table structure for table `m_ktg_pengeluaran`
--

CREATE TABLE `m_ktg_pengeluaran` (
  `m_ktg_pengeluaran_id` int(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_ktg_pengeluaran`
--

INSERT INTO `m_ktg_pengeluaran` (`m_ktg_pengeluaran_id`, `nama`, `keterangan`, `created_at`) VALUES
(1, 'BBM', 'Solar/BBM untuk kendaraan, genset, forklift, dan lain-lain.', '0000-00-00 00:00:00'),
(2, 'Perawatan & Perbaikan', 'Servis mesin press, penggantian spare part conveyor, pelumasan mesin, jasa teknisi.', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `m_role`
--

CREATE TABLE `m_role` (
  `m_role_id` int(2) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `role_scope` enum('all','gudang') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_role`
--

INSERT INTO `m_role` (`m_role_id`, `nama`, `role_scope`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Administrator', 'all', '0000-00-00 00:00:00', '', '2025-09-02 11:37:03', ''),
(2, 'Validator', 'gudang', '0000-00-00 00:00:00', '', '2025-09-02 11:37:03', ''),
(3, 'HRD', 'all', '0000-00-00 00:00:00', '', '2025-09-02 11:37:03', ''),
(4, 'Admin Gudang', 'gudang', '0000-00-00 00:00:00', '', '2025-09-02 11:37:03', ''),
(5, 'Pegawai', 'gudang', '0000-00-00 00:00:00', '', '2025-09-02 11:37:03', ''),
(6, 'Driver', 'gudang', '0000-00-00 00:00:00', '', '2025-09-02 11:37:03', ''),
(8, 'tes', 'gudang', '2025-09-02 06:06:15', '', '2025-09-02 06:58:28', ''),
(9, 'testing', 'gudang', '2025-09-02 06:58:17', '', '2025-09-02 06:58:23', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mt_gaji_pegawai`
--
ALTER TABLE `mt_gaji_pegawai`
  ADD PRIMARY KEY (`mt_gaji_pegawai_id`),
  ADD KEY `fk_gaji_pegawai` (`kd_pegawai`),
  ADD KEY `fk_gaji_peg_gudang` (`gudang_id`);

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
-- Indexes for table `mt_pengeluaran`
--
ALTER TABLE `mt_pengeluaran`
  ADD PRIMARY KEY (`mt_pengeluaran_id`),
  ADD KEY `fk_pengeluaran_pegawai` (`kd_pegawai`),
  ADD KEY `fk_pengeluaran_ktg` (`ktg_pengeluaran_id`),
  ADD KEY `gudang_id` (`gudang_id`);

--
-- Indexes for table `mt_pengolahan`
--
ALTER TABLE `mt_pengolahan`
  ADD PRIMARY KEY (`mt_pengolahan_id`),
  ADD KEY `kd_pegawai` (`kd_pegawai`),
  ADD KEY `gudang_id` (`gudang_id`);

--
-- Indexes for table `mt_user`
--
ALTER TABLE `mt_user`
  ADD PRIMARY KEY (`mt_user_id`),
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
-- Indexes for table `m_ktg_pengeluaran`
--
ALTER TABLE `m_ktg_pengeluaran`
  ADD PRIMARY KEY (`m_ktg_pengeluaran_id`);

--
-- Indexes for table `m_role`
--
ALTER TABLE `m_role`
  ADD PRIMARY KEY (`m_role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mt_gaji_pegawai`
--
ALTER TABLE `mt_gaji_pegawai`
  MODIFY `mt_gaji_pegawai_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `mt_pegawai`
--
ALTER TABLE `mt_pegawai`
  MODIFY `mt_pegawai_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mt_pembelian`
--
ALTER TABLE `mt_pembelian`
  MODIFY `mt_pembelian_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mt_pengeluaran`
--
ALTER TABLE `mt_pengeluaran`
  MODIFY `mt_pengeluaran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mt_pengolahan`
--
ALTER TABLE `mt_pengolahan`
  MODIFY `mt_pengolahan_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `mt_user`
--
ALTER TABLE `mt_user`
  MODIFY `mt_user_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `m_gudang`
--
ALTER TABLE `m_gudang`
  MODIFY `m_gudang_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `m_komponen_gaji`
--
ALTER TABLE `m_komponen_gaji`
  MODIFY `m_komponen_gaji_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `m_ktg_pengeluaran`
--
ALTER TABLE `m_ktg_pengeluaran`
  MODIFY `m_ktg_pengeluaran_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `m_role`
--
ALTER TABLE `m_role`
  MODIFY `m_role_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mt_gaji_pegawai`
--
ALTER TABLE `mt_gaji_pegawai`
  ADD CONSTRAINT `fk_gaji_peg_gudang` FOREIGN KEY (`gudang_id`) REFERENCES `m_gudang` (`m_gudang_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_gaji_peg_pegawai` FOREIGN KEY (`kd_pegawai`) REFERENCES `mt_pegawai` (`kd_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mt_pegawai`
--
ALTER TABLE `mt_pegawai`
  ADD CONSTRAINT `fk_pegawai_penempatan` FOREIGN KEY (`penempatan_id`) REFERENCES `m_gudang` (`m_gudang_id`),
  ADD CONSTRAINT `fk_pegawai_role` FOREIGN KEY (`role_id`) REFERENCES `m_role` (`m_role_id`);

--
-- Constraints for table `mt_pembelian`
--
ALTER TABLE `mt_pembelian`
  ADD CONSTRAINT `fk_pembelian_gudang` FOREIGN KEY (`gudang_id`) REFERENCES `m_gudang` (`m_gudang_id`);

--
-- Constraints for table `mt_pengeluaran`
--
ALTER TABLE `mt_pengeluaran`
  ADD CONSTRAINT `fk_pengeluaran_gudang` FOREIGN KEY (`gudang_id`) REFERENCES `m_gudang` (`m_gudang_id`),
  ADD CONSTRAINT `fk_pengeluaran_ktg` FOREIGN KEY (`ktg_pengeluaran_id`) REFERENCES `m_ktg_pengeluaran` (`m_ktg_pengeluaran_id`),
  ADD CONSTRAINT `fk_pengeluaran_pegawai` FOREIGN KEY (`kd_pegawai`) REFERENCES `mt_pegawai` (`kd_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mt_pengolahan`
--
ALTER TABLE `mt_pengolahan`
  ADD CONSTRAINT `fk_pengolahan_gudang` FOREIGN KEY (`gudang_id`) REFERENCES `m_gudang` (`m_gudang_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengolahan_pegawai` FOREIGN KEY (`kd_pegawai`) REFERENCES `mt_pegawai` (`kd_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mt_user`
--
ALTER TABLE `mt_user`
  ADD CONSTRAINT `fk_user_pegawai` FOREIGN KEY (`kd_pegawai`) REFERENCES `mt_pegawai` (`kd_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `m_komponen_gaji`
--
ALTER TABLE `m_komponen_gaji`
  ADD CONSTRAINT `fk_komponen_gaji_gudang` FOREIGN KEY (`gudang_id`) REFERENCES `m_gudang` (`m_gudang_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
