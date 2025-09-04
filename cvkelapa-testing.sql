-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 04:24 AM
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

--
-- Dumping data for table `mt_gaji_driver`
--

INSERT INTO `mt_gaji_driver` (`mt_gaji_driver_id`, `tg_proses_gaji`, `kd_pegawai`, `gudang_id`, `total_upah_perjalanan`, `total_bonus`, `total_gaji_bersih`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, '2025-09-04', '9000000123', 1, 0, 0, 0, '2025-09-04 04:11:13', '', '2025-09-04 04:11:13', ''),
(2, '2025-09-04', '9000000586', 4, 3500000, 450000, 3950000, '2025-09-04 04:13:45', '', '2025-09-04 04:13:45', '');

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

--
-- Dumping data for table `mt_gaji_pegawai`
--

INSERT INTO `mt_gaji_pegawai` (`mt_gaji_pegawai_id`, `tg_proses_gaji`, `kd_pegawai`, `gudang_id`, `total_upah_daging`, `total_upah_kopra`, `total_upah_produksi`, `total_bonus`, `total_gaji_bersih`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(30, '2025-09-04', '9000000123', 1, 0, 0, 0, 0, 0, '2025-09-04 03:39:10', '', '2025-09-04 03:39:10', ''),
(31, '2025-09-04', '9000000123', 2, 0, 0, 0, 0, 0, '2025-09-04 03:41:06', '', '2025-09-04 03:41:06', ''),
(32, '2025-09-04', '9000000586', 3, 0, 0, 0, 0, 0, '2025-09-04 03:41:06', '', '2025-09-04 03:41:06', ''),
(33, '2025-09-04', '9000000586', 4, 840000, 2169000, 3009000, 0, 3009000, '2025-09-04 03:41:06', '', '2025-09-04 03:41:06', '');

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

--
-- Dumping data for table `mt_komponen_gaji`
--

INSERT INTO `mt_komponen_gaji` (`mt_komponen_gaji_id`, `gudang_id`, `takaran_daging`, `upah_takaran_daging`, `takaran_kopra`, `upah_takaran_kopra`, `gaji_driver`, `created_at`, `created_by`) VALUES
(1, 1, 75, 50000, 35, 10000, 0, '2025-08-21 10:12:03', 'agustin@gmail.com'),
(2, 2, 750, 500000, 350, 8000, 0, '2025-08-21 10:12:03', 'agustin@gmail.com'),
(3, 1, 45, 125000, 70, 450000, 0, '2025-09-02 14:31:42', 'agustin@gmail.com'),
(4, 7, 50, 500000, 40, 400000, 400000, '2025-09-02 14:36:22', 'agustin@gmail.com');

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

--
-- Dumping data for table `mt_pegawai`
--

INSERT INTO `mt_pegawai` (`mt_pegawai_id`, `kd_pegawai`, `nama`, `jenis_kelamin`, `role_id`, `penempatan_id`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, '9000000586', 'Agustin Setya', 'P', 1, 1, '2025-08-18 19:55:58', '', '2025-08-27 17:21:01', ''),
(2, '9000000123', 'Riansss', 'L', 4, 3, '2025-08-18 19:55:58', '', '2025-09-02 13:03:17', ''),
(3, '0512132132', 'tes', 'L', 6, 4, '2025-09-02 13:07:27', '', '2025-09-04 06:23:22', '');

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
  `biaya` int(15) NOT NULL,
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

--
-- Dumping data for table `mt_pengiriman`
--

INSERT INTO `mt_pengiriman` (`mt_pengiriman_id`, `tg_pengiriman`, `gudang_id`, `kd_pegawai`, `berat_daging`, `berat_kopra`, `jumlah_perjalanan`, `bonus`, `tg_proses_gaji`, `is_stat_gaji`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(3, '2025-08-26', 1, '9000000123', 250, 500, 0, 0, '2025-09-04', 1, '2025-08-28 18:12:38', 'agustin@gmail.com', '2025-09-04 11:11:13', 'agustin@gmail.com'),
(4, '2025-08-19', 4, '9000000586', 200, 482, 0, 0, '2025-09-04', 1, '2025-08-28 18:12:38', 'agustin@gmail.com', '2025-09-04 11:13:45', 'agustin@gmail.com'),
(5, '2025-08-20', 1, '9000000123', 250, 500, 0, 0, '2025-09-04', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 11:11:13', 'agustin@gmail.com'),
(6, '2025-08-20', 4, '9000000586', 200, 482, 5, 450000, '2025-09-04', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 11:13:45', 'agustin@gmail.com'),
(7, '2025-08-12', 1, '9000000123', 250, 500, 0, 0, '2025-09-04', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 11:11:13', 'agustin@gmail.com'),
(8, '2025-08-14', 4, '9000000586', 200, 482, 2, 0, '2025-09-04', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 11:13:45', 'agustin@gmail.com'),
(9, '2025-08-14', 1, '9000000123', 250, 500, 0, 0, '2025-09-04', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 11:11:13', 'agustin@gmail.com'),
(10, '2025-08-06', 4, '9000000586', 200, 482, 0, 0, NULL, 0, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-08-28 18:16:09', ''),
(11, '2025-08-07', 2, '9000000123', 250, 500, 0, 0, NULL, 0, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-03 10:41:15', 'agustin@gmail.com'),
(12, '2025-08-12', 3, '9000000586', 200, 482, 0, 0, '0000-00-00', 0, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-03 10:41:15', 'agustin@gmail.com'),
(13, '2025-08-28', 4, '9000000123', 250, 500, 0, 0, '0000-00-00', 0, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-03 10:41:15', 'agustin@gmail.com'),
(14, '2025-08-28', 3, '9000000586', 200, 482, 0, 0, '0000-00-00', 0, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-03 10:41:15', 'agustin@gmail.com'),
(15, '2025-08-28', 6, '9000000123', 250, 500, 2, 0, '0000-00-00', 0, '2025-08-28 18:14:55', 'agustin@gmail.com', '2025-09-04 11:08:58', 'agustin@gmail.com'),
(16, '2025-08-28', 4, '9000000586', 250, 482, 0, 550000, NULL, 0, '2025-08-28 18:14:55', 'agustin@gmail.com', '2025-09-03 14:27:45', 'agustin@gmail.com');

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

--
-- Dumping data for table `mt_pengolahan`
--

INSERT INTO `mt_pengolahan` (`mt_pengolahan_id`, `tg_pengolahan`, `gudang_id`, `kd_pegawai`, `berat_daging`, `berat_kopra`, `bonus`, `tg_proses_gaji`, `is_stat_gaji`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(3, '2025-08-26', 1, '9000000123', 250, 500, 0, '2025-09-04', 1, '2025-08-28 18:12:38', 'agustin@gmail.com', '2025-09-04 10:39:10', 'agustin@gmail.com'),
(4, '2025-08-19', 4, '9000000586', 200, 482, 0, '2025-09-04', 1, '2025-08-28 18:12:38', 'agustin@gmail.com', '2025-09-04 10:41:06', 'agustin@gmail.com'),
(5, '2025-08-20', 1, '9000000123', 250, 500, 0, '2025-09-04', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 10:39:10', 'agustin@gmail.com'),
(6, '2025-08-20', 4, '9000000586', 200, 482, 0, '2025-09-04', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 10:41:06', 'agustin@gmail.com'),
(7, '2025-08-12', 1, '9000000123', 250, 500, 0, '2025-09-04', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 10:39:10', 'agustin@gmail.com'),
(8, '2025-08-14', 4, '9000000586', 200, 482, 0, '2025-09-04', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 10:41:06', 'agustin@gmail.com'),
(9, '2025-08-14', 1, '9000000123', 250, 500, 0, '2025-09-04', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 10:39:10', 'agustin@gmail.com'),
(10, '2025-08-06', 4, '9000000586', 200, 482, 0, '2025-09-04', 0, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 10:28:09', 'agustin@gmail.com'),
(11, '2025-08-07', 2, '9000000123', 250, 500, 0, '2025-09-04', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 10:41:06', 'agustin@gmail.com'),
(12, '2025-08-12', 3, '9000000586', 200, 482, 0, '2025-09-04', 1, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 10:41:06', 'agustin@gmail.com'),
(13, '2025-08-28', 4, '9000000123', 250, 500, 0, '2025-09-04', 0, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 10:28:09', 'agustin@gmail.com'),
(14, '2025-08-28', 3, '9000000586', 200, 482, 0, '2025-09-04', 0, '2025-08-28 18:14:06', 'agustin@gmail.com', '2025-09-04 10:15:54', 'agustin@gmail.com'),
(15, '2025-08-28', 6, '9000000123', 250, 500, 0, '2025-09-04', 0, '2025-08-28 18:14:55', 'agustin@gmail.com', '2025-09-04 10:15:54', 'agustin@gmail.com'),
(16, '2025-08-28', 4, '9000000586', 200, 482, 500000, '2025-09-04', 0, '2025-08-28 18:14:55', 'agustin@gmail.com', '2025-09-04 10:28:09', 'agustin@gmail.com');

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
(5, '9000000123', 'rians@gmail.com', '$2y$10$1MeQpwx8BusjTpEiXn.GK.Tqw7zeytQCN7fcZq4me.4e345v0eStK', '2025-09-02 07:16:54', '2025-09-02 10:58:20');

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

--
-- Dumping data for table `m_gudang`
--

INSERT INTO `m_gudang` (`m_gudang_id`, `nama`, `takaran_daging`, `upah_takaran_daging`, `takaran_kopra`, `upah_takaran_kopra`, `gaji_driver`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Gudang Luluk', 0, 125000, 0, 450000, 575000, '2025-08-21 09:32:09', '', '2025-09-02 14:31:42', 'agustin@gmail.com'),
(2, 'Gudang Sutik', 0, 0, 0, 0, 0, '2025-08-21 09:32:09', '', '2025-09-02 20:25:48', ''),
(3, 'Gudang Berkah', 0, 0, 0, 0, 0, '2025-08-28 18:04:57', '', '2025-09-02 20:25:48', ''),
(4, 'Gudang Rejeki', 150, 210000, 300, 450000, 500000, '2025-08-28 18:04:57', '', '2025-09-03 10:06:31', ''),
(5, 'Gudang Untung', 0, 0, 0, 0, 0, '2025-08-28 18:06:21', '', '2025-09-02 20:25:48', ''),
(6, 'Gudang Kabul', 0, 0, 0, 0, 250000, '2025-08-28 18:06:21', '', '2025-09-04 11:10:27', ''),
(7, 'tes', 0, 500000, 0, 400000, 400000, '2025-09-02 14:36:22', 'agustin@gmail.com', '2025-09-02 14:36:22', '');

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

--
-- Dumping data for table `m_ktg_pengeluaran`
--

INSERT INTO `m_ktg_pengeluaran` (`m_ktg_pengeluaran_id`, `nama`, `keterangan`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'BBM', 'Solar/BBM untuk kendaraan, genset, forklift, dan lain-lain.', '0000-00-00 00:00:00', '', '2025-09-02 22:26:21', ''),
(2, 'Perawatan & Perbaikan', 'Servis mesin press, penggantian spare part conveyor, pelumasan mesin, jasa teknisi.', '0000-00-00 00:00:00', '', '2025-09-02 15:26:50', ''),
(3, 'tes', 'tes', '2025-09-02 15:26:56', '', '2025-09-02 15:26:56', '');

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
(6, 'Driver', 'gudang', '0000-00-00 00:00:00', '', '2025-09-02 11:37:03', '');

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
