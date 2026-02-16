-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 16, 2026 at 11:19 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bioskop`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `detail_id` int NOT NULL,
  `transaksi_id` int DEFAULT NULL,
  `kursi_id` int DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `film_id` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `genre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `durasi` int DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT '0.0',
  `sinopsis` text COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `jadwal_id` int NOT NULL,
  `film_id` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kursi`
--

CREATE TABLE `kursi` (
  `kursi_id` int NOT NULL,
  `nomor_kursi` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('tersedia','dipesan','rusak','diblokir') COLLATE utf8mb4_general_ci DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kursi`
--

INSERT INTO `kursi` (`kursi_id`, `nomor_kursi`, `status`) VALUES
(1, 'A1', 'rusak'),
(3, 'A2', 'diblokir'),
(5, 'A4', 'tersedia'),
(6, 'A5', 'tersedia'),
(7, 'A6', 'tersedia'),
(8, 'A7', 'tersedia'),
(9, 'A8', 'tersedia'),
(10, 'A9', 'tersedia'),
(11, 'A10', 'tersedia'),
(12, 'A11', 'tersedia'),
(13, 'A12', 'tersedia'),
(16, 'A13', 'tersedia'),
(17, 'A14', 'tersedia'),
(18, 'A15', 'tersedia'),
(19, 'B1', 'tersedia'),
(20, 'B2', 'tersedia'),
(21, 'B3', 'tersedia'),
(22, 'B4', 'tersedia'),
(23, 'B5', 'tersedia'),
(24, 'B6', 'tersedia'),
(25, 'B7', 'tersedia'),
(26, 'B8', 'tersedia'),
(27, 'B9', 'tersedia'),
(28, 'B10', 'tersedia'),
(30, 'B11', 'tersedia'),
(31, 'B12', 'tersedia'),
(32, 'B13', 'tersedia'),
(33, 'B14', 'tersedia'),
(34, 'B15', 'tersedia'),
(35, 'C1', 'tersedia'),
(36, 'C2', 'tersedia'),
(37, 'C3', 'tersedia'),
(38, 'C4', 'tersedia'),
(39, 'C5', 'tersedia'),
(41, 'C6', 'tersedia'),
(42, 'C7', 'tersedia'),
(43, 'C8', 'tersedia'),
(44, 'C9', 'tersedia'),
(45, 'C10', 'tersedia'),
(46, 'C11', 'tersedia'),
(47, 'C12', 'tersedia'),
(48, 'C13', 'tersedia'),
(49, 'C14', 'tersedia'),
(52, 'C15', 'tersedia'),
(53, 'C16', 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `kursi_jadwal`
--

CREATE TABLE `kursi_jadwal` (
  `kursi_jadwal_id` int NOT NULL,
  `kursi_id` int NOT NULL,
  `jadwal_id` int NOT NULL,
  `status` enum('tersedia','dipesan','rusak','diblokir') COLLATE utf8mb4_general_ci DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `pelanggan_id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `no_telepon` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `foto` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`pelanggan_id`, `nama`, `email`, `password`, `no_telepon`, `role`, `foto`) VALUES
(1, 'Trisna Almuti', 'trisna@gmail.com', '123', '0895711856677', 'admin', ''),
(8, 'ucok', 'ucok@gmail.com', '123', '08903245321', 'user', '');

UPDATE `pelanggan`
SET `foto` = 'admin_profil.jpg'
WHERE `role` = 'admin' AND (`foto` IS NULL OR `foto` = '');

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `pengumuman_id` tinyint NOT NULL DEFAULT '1',
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `isi` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'nonaktif',
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diupdate_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pengumuman_id`),
  CONSTRAINT `chk_single_pengumuman` CHECK ((`pengumuman_id` = 1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`pengumuman_id`, `judul`, `isi`, `status`) VALUES
(1, 'Pengumuman', '<p>Belum ada pengumuman.</p>', 'nonaktif');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `transaksi_id` int NOT NULL,
  `pelanggan_id` int DEFAULT NULL,
  `jadwal_id` int DEFAULT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL,
  `status` enum('dipesan','dibatalkan','selesai') COLLATE utf8mb4_general_ci DEFAULT 'dipesan',
  `tanggal_pembatalan` date DEFAULT NULL,
  `jumlah_refund` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `transaksi_id` (`transaksi_id`),
  ADD KEY `kursi_id` (`kursi_id`);

--
-- Indexes for table `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`film_id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`jadwal_id`),
  ADD KEY `film_id` (`film_id`);

--
-- Indexes for table `kursi`
--
ALTER TABLE `kursi`
  ADD PRIMARY KEY (`kursi_id`);

--
-- Indexes for table `kursi_jadwal`
--
ALTER TABLE `kursi_jadwal`
  ADD PRIMARY KEY (`kursi_jadwal_id`),
  ADD KEY `jadwal_id` (`jadwal_id`) USING BTREE,
  ADD KEY `kursi_id` (`kursi_id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`pelanggan_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`transaksi_id`),
  ADD KEY `jadwal_id` (`jadwal_id`),
  ADD KEY `pelanggan_id` (`pelanggan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `detail_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `film_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `jadwal_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `kursi`
--
ALTER TABLE `kursi`
  MODIFY `kursi_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `kursi_jadwal`
--
ALTER TABLE `kursi_jadwal`
  MODIFY `kursi_jadwal_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `transaksi_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`transaksi_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kursi_jadwal`
--
ALTER TABLE `kursi_jadwal`
  ADD CONSTRAINT `kursi_jadwal_ibfk_2` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`jadwal_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`jadwal_id`),
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`pelanggan_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
