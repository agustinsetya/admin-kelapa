-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 04:25 AM
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
-- Database: `cvkelapa-template`
--

-- --------------------------------------------------------

--
-- Table structure for table `mt_gaji_driver`
--

CREATE TABLE `mt_gaji_driver` (
  `mt_gaji_driver_id` int(10) NOT NULL,
  `tg_proses_gaji` date NOT NULL,
  `kd_pegawai` char(10) NOT NULL,
  `gudang_id` int(10) NOT NULL,
  `total_upah_perjalanan` int(15) NOT NULL,
  `total_bonus` int(15) NOT NULL,
  `total_gaji_bersih` int(15) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mt_gaji_pegawai`
--

CREATE TABLE `mt_gaji_pegawai` (
  `mt_gaji_pegawai_id` int(10) NOT NULL,
  `tg_proses_gaji` date NOT NULL,
  `kd_pegawai` char(10) NOT NULL,
  `gudang_id` int(10) NOT NULL,
  `total_upah_daging` int(15) NOT NULL,
  `total_upah_kopra` int(15) NOT NULL,
  `total_upah_produksi` int(15) NOT NULL,
  `total_bonus` int(15) NOT NULL,
  `total_gaji_bersih` int(15) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mt_komponen_gaji`
--

CREATE TABLE `mt_komponen_gaji` (
  `mt_komponen_gaji_id` int(5) NOT NULL,
  `gudang_id` int(10) NOT NULL,
  `takaran_daging` int(10) NOT NULL DEFAULT 0,
  `upah_takaran_daging` int(15) NOT NULL DEFAULT 0,
  `takaran_kopra` int(10) NOT NULL DEFAULT 0,
  `upah_takaran_kopra` int(15) NOT NULL DEFAULT 0,
  `gaji_driver` int(15) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `biaya` int(15) NOT NULL,
  `kd_pegawai` char(10) NOT NULL,
  `status` enum('BELUM_BAYAR','SUDAH_BAYAR') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mt_pengiriman`
--

CREATE TABLE `mt_pengiriman` (
  `mt_pengiriman_id` int(10) NOT NULL,
  `tg_pengiriman` date NOT NULL DEFAULT current_timestamp(),
  `gudang_id` int(10) NOT NULL,
  `kd_pegawai` char(10) NOT NULL,
  `berat_daging` int(10) NOT NULL,
  `berat_kopra` int(10) NOT NULL,
  `jumlah_perjalanan` int(5) NOT NULL,
  `bonus` int(15) NOT NULL,
  `tg_proses_gaji` date DEFAULT NULL,
  `is_stat_gaji` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `bonus` int(15) NOT NULL,
  `tg_proses_gaji` date DEFAULT NULL,
  `is_stat_gaji` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `m_gudang`
--

CREATE TABLE `m_gudang` (
  `m_gudang_id` int(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `takaran_daging` int(10) NOT NULL DEFAULT 0,
  `upah_takaran_daging` int(15) NOT NULL DEFAULT 0,
  `takaran_kopra` int(10) NOT NULL DEFAULT 0,
  `upah_takaran_kopra` int(15) NOT NULL DEFAULT 0,
  `gaji_driver` int(15) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_ktg_pengeluaran`
--

CREATE TABLE `m_ktg_pengeluaran` (
  `m_ktg_pengeluaran_id` int(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for dumped tables
--

--
-- Indexes for table `mt_gaji_driver`
--
ALTER TABLE `mt_gaji_driver`
  ADD PRIMARY KEY (`mt_gaji_driver_id`),
  ADD KEY `fk_gaji_pegawai` (`kd_pegawai`),
  ADD KEY `fk_gaji_peg_gudang` (`gudang_id`);

--
-- Indexes for table `mt_gaji_pegawai`
--
ALTER TABLE `mt_gaji_pegawai`
  ADD PRIMARY KEY (`mt_gaji_pegawai_id`),
  ADD KEY `fk_gaji_pegawai` (`kd_pegawai`),
  ADD KEY `fk_gaji_peg_gudang` (`gudang_id`);

--
-- Indexes for table `mt_komponen_gaji`
--
ALTER TABLE `mt_komponen_gaji`
  ADD PRIMARY KEY (`mt_komponen_gaji_id`),
  ADD KEY `gudang_id` (`gudang_id`);

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
-- Indexes for table `mt_pengiriman`
--
ALTER TABLE `mt_pengiriman`
  ADD PRIMARY KEY (`mt_pengiriman_id`),
  ADD KEY `kd_pegawai` (`kd_pegawai`),
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
-- AUTO_INCREMENT for table `mt_gaji_driver`
--
ALTER TABLE `mt_gaji_driver`
  MODIFY `mt_gaji_driver_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mt_gaji_pegawai`
--
ALTER TABLE `mt_gaji_pegawai`
  MODIFY `mt_gaji_pegawai_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `mt_komponen_gaji`
--
ALTER TABLE `mt_komponen_gaji`
  MODIFY `mt_komponen_gaji_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mt_pegawai`
--
ALTER TABLE `mt_pegawai`
  MODIFY `mt_pegawai_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `mt_pengiriman`
--
ALTER TABLE `mt_pengiriman`
  MODIFY `mt_pengiriman_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  MODIFY `m_gudang_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `m_ktg_pengeluaran`
--
ALTER TABLE `m_ktg_pengeluaran`
  MODIFY `m_ktg_pengeluaran_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- Constraints for table `mt_komponen_gaji`
--
ALTER TABLE `mt_komponen_gaji`
  ADD CONSTRAINT `fk_komponen_gaji_gudang` FOREIGN KEY (`gudang_id`) REFERENCES `m_gudang` (`m_gudang_id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
