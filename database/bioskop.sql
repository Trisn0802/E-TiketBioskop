-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2024 at 04:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
  `detail_id` int(11) NOT NULL,
  `transaksi_id` int(11) DEFAULT NULL,
  `kursi_id` int(11) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`detail_id`, `transaksi_id`, `kursi_id`, `harga`) VALUES
(18, 32, 5, 300000.00),
(19, 32, 6, 300000.00),
(20, 32, 7, 300000.00),
(21, 32, 8, 300000.00),
(22, 32, 9, 300000.00),
(23, 33, 21, 300000.00),
(24, 33, 37, 300000.00),
(25, 33, 36, 300000.00),
(26, 33, 20, 300000.00),
(27, 33, 38, 300000.00),
(28, 33, 22, 300000.00),
(29, 34, 6, 100000.00),
(30, 34, 7, 100000.00),
(31, 35, 20, 100000.00),
(32, 35, 21, 100000.00),
(33, 36, 5, 50000.00),
(34, 37, 11, 60000.00),
(35, 38, 5, 100000.00),
(36, 38, 6, 100000.00),
(37, 39, 5, 50000.00),
(38, 40, 3, 45000.00),
(39, 41, 10, 60000.00),
(40, 42, 8, 50000.00),
(41, 43, 13, 240000.00),
(42, 43, 16, 240000.00),
(43, 43, 17, 240000.00),
(44, 43, 18, 240000.00);

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `film_id` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `durasi` int(11) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 0.0,
  `sinopsis` text NOT NULL DEFAULT 'Belum ada sinopsis!',
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `film`
--

INSERT INTO `film` (`film_id`, `judul`, `genre`, `durasi`, `rating`, `sinopsis`, `foto`) VALUES
(18, 'Kung Fu Panda 4', ' Animasi, Keluarga, Fantasi, Aksi', 94, 7.1, 'Po bersiap untuk menjadi pemimpin spiritual Lembah Kedamaiannya, tetapi juga membutuhkan seseorang untuk menggantikannya sebagai Prajurit Naga. Karena itu, ia akan melatih praktisi kung fu baru untuk posisi tersebut dan akan menghadapi penjahat bernama Bunglon yang memanggil penjahat dari masa lalu.', '67430d50adc60.jpg'),
(19, ' Deadpool &amp; Wolverine', ' Aksi, Komedi, Cerita Fiksi ', 128, 7.7, 'Wade Wilson yang lesu bekerja keras dalam kehidupan sipil dengan hari-harinya sebagai tentara bayaran yang fleksibel secara moral, Deadpool, di belakangnya. Namun ketika dunia asalnya menghadapi ancaman eksistensial, Wade terpaksa harus kembali bekerja sama dengan Wolverine yang bahkan lebih enggan.', '67430de3abc6f.jpg'),
(20, 'Venom The Last Dance', ' Cerita Fiksi, Aksi, Petualangan ', 109, 6.5, 'Dalam Venom: The Last Dance, Tom Hardy kembali sebagai Venom, salah satu karakter Marvel terhebat dan paling kompleks, untuk film terakhir dalam trilogi tersebut. Eddie dan Venom sedang dalam pelarian. Diburu oleh kedua dunia mereka dan dengan semakin dekatnya jaring, keduanya terpaksa mengambil keputusan yang menghancurkan yang akan menutup tirai tarian terakhir Venom dan Eddie.', '674311ee9daca.jpg'),
(21, 'Gladiator 2', ' Aksi, Petualangan, Drama ', 148, 6.8, 'Bertahun-tahun setelah menyaksikan kematian pahlawan yang disegani Maximus di tangan pamannya, Lucius dipaksa memasuki Colosseum setelah rumahnya ditaklukkan oleh Kaisar tirani yang kini memimpin Roma dengan tangan besi. Dengan amarah di hatinya dan masa depan Kekaisaran yang dipertaruhkan, Lucius harus menengok ke masa lalunya untuk menemukan kekuatan dan kehormatan guna mengembalikan kejayaan Roma kepada rakyatnya.', '6743128515032.jpg'),
(22, 'Smile 2', ' Kengerian, Misteri ', 127, 6.9, 'Akan memulai tur dunia baru, sensasi pop global Skye Riley mulai mengalami kejadian yang semakin menakutkan dan tidak dapat dijelaskan. Dilanda oleh kengerian yang meningkat dan tekanan ketenaran, Skye terpaksa menghadapi masa lalunya yang kelam untuk mendapatkan kembali kendali atas hidupnya sebelum menjadi tidak terkendali.', '674312e4b34aa.jpg'),
(23, 'OVERLORD: The Sacred Kingdom', ' Aksi, Petualangan, Animasi, Fantasi ', 134, 7.8, 'Bahasa Indonesia: Setelah dua belas tahun memainkan game MMORPG favoritnya, Momonga masuk untuk terakhir kalinya hanya untuk menemukan dirinya dipindahkan ke dunianya memainkannya tanpa batas waktu. Sepanjang petualangannya, avatarnya naik ke gelar Sorcerer King Ains Ooal Gown. Dulunya makmur tetapi sekarang di ambang kehancuran, Kerajaan Suci menikmati tahun-tahun perdamaian setelah pembangunan tembok besar yang melindungi mereka dari invasi tetangga. Tapi, suatu hari ini berakhir ketika Kaisar Iblis Jaldabaoth tiba dengan pasukan manusia setengah jahat. Karena takut invasi tanah mereka sendiri, wilayah tetangga Teokrasi Slane terpaksa memohon musuh-musuh mereka di Kerajaan Sorcerer untuk meminta bantuan. Mengindahkan panggilan itu, Momonga, yang sekarang dikenal sebagai Raja Sorcerer Ains Ooal Gown, mengerahkan Kerajaan Sorcerer dan pasukan mayat hidup untuk bergabung dalam pertarungan bersama Kerajaan Suci dan Teokrasi Slane dengan harapan dapat mengalahkan Kaisar Iblis.', '6743138d48929.jpg'),
(25, 'Avangers Endgame', ' Petualangan, Cerita Fiksi, Aksi ', 181, 8.2, 'Terdampar di luar angkasa tanpa persediaan makanan dan minuman, Tony Stark berusaha mengirim pesan untuk Pepper Potts dimana persediaan oksigen mulai menipis. Sementara itu para Avengers yang tersisa harus menemukan cara untuk mengembalikan 50% mahluk di seluruh dunia yang telah dilenyapkan oleh Thanos.', '67431b038242e.jpg'),
(26, 'Pengabdi Setan 2', ' Drama, Kengerian, Misteri ', 119, 7.0, 'Beberapa tahun setelah berhasil menyelamatkan diri dari kejadian mengerikan yang membuat mereka kehilangan ibu dan si bungsu Ian, Rini dan adik-adiknya, Toni dan Bondi, serta Bapak tinggal di rumah susun karena percaya tinggal di rumah susun aman jika terjadi sesuatu karena ada banyak orang. Namun, mereka segera menyadari bahwa tinggal bersama banyak orang mungkin juga sangat berbahaya, jika mereka tidak sangat mengenali siapa saja yang menjadi tetangga mereka. Pada sebuah malam penuh teror, Rini dan Keluarganya harus kembali menyelamatkan diri. Tapi kali ini, mungkin sudah terlambat untuk lari.', '67431b5cb1339.jpg'),
(27, 'Drive 2011', ' Drama, Cerita Seru, Kejahatan ', 100, 7.6, 'Driver adalah pemeran pengganti Hollywood yang bekerja sambilan sebagai sopir pelarian bagi para penjahat. Meskipun ia memiliki sifat dingin, akhir-akhir ini ia mulai dekat dengan tetangga cantik bernama Irene dan putranya yang masih kecil, Benicio. Ketika suami Irene keluar dari penjara, ia meminta bantuan Driver dalam perampokan senilai satu juta dolar. Pekerjaan itu berakhir buruk, dan Driver harus mempertaruhkan nyawanya untuk melindungi Irene dan Benicio dari dalang pendendam di balik perampokan tersebut.', '67431c510c373.jpg'),
(28, 'Your Name 2016', ' Animasi, Percintaan, Drama', 106, 8.5, 'Mitsuha dan Taki, dua siswa SMA, adalah orang asing yang menjalani kehidupan terpisah. Namun, suatu malam, mereka tiba-tiba bertukar tempat. Mitsuha terbangun di tubuh Taki, dan Taki di tubuh Mitsuha. Kejadian aneh ini terus terjadi secara acak, dan keduanya harus menyesuaikan kehidupan mereka satu sama lain.', '67431cc62348b.jpg'),
(29, 'Pamali 2022', ' Kengerian, Misteri ', 99, 5.0, 'Akibat krisis ekonomi yang dialaminya, Jaka Sunarya terpaksa menjual rumah warisan keluarga yang telah ditinggalkannya 20 tahun lalu. Rumah tersebut tidak terawat dengan baik dan terletak jauh di pedalaman desa. Bersama Rika, istrinya yang sedang hamil, mereka semaksimal mungkin menikmati rumah tersebut sebelum calon pembeli berdatangan. Namun berturut-turut mereka melakukan hal-hal yang dianggap pamali tanpa mereka sadari. Alhasil, banyak keanehan yang muncul selama mereka berada di rumah tersebut. Saat sedang membersihkan, Jaka menemukan buku harian mendiang ibunya, yang berisi catatan-catatan dari kehidupan yang tidak diketahui, serta keberadaan makhluk halus yang mengancam kehidupan mereka.', '67431d59b2082.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `jadwal_id` int(11) NOT NULL,
  `film_id` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`jadwal_id`, `film_id`, `tanggal`, `jam_mulai`, `harga`) VALUES
(8, 12, '2024-11-24', '10:00:00', 50000.00),
(14, 12, '2024-11-25', '10:00:00', 50000.00),
(15, 12, '2024-11-24', '15:30:00', 50000.00),
(16, 12, '2024-11-24', '20:00:00', 50000.00),
(17, 11, '2024-11-25', '13:00:00', 75000.00),
(18, 16, '2024-11-26', '19:00:00', 80000.00),
(19, 27, '2024-11-24', '10:00:00', 50000.00),
(20, 19, '2024-11-25', '19:00:00', 50000.00),
(21, 27, '2024-11-24', '19:00:00', 50000.00),
(22, 18, '2024-11-26', '13:00:00', 50000.00),
(23, 25, '2024-11-29', '14:00:00', 75000.00),
(24, 28, '2024-11-27', '13:00:00', 60000.00),
(25, 18, '2024-12-25', '16:00:00', 65000.00),
(26, 21, '2024-11-30', '13:00:00', 45000.00),
(27, 19, '2024-11-30', '19:00:00', 65000.00);

-- --------------------------------------------------------

--
-- Table structure for table `kursi`
--

CREATE TABLE `kursi` (
  `kursi_id` int(11) NOT NULL,
  `nomor_kursi` varchar(10) DEFAULT NULL,
  `status` enum('tersedia','dipesan','rusak','diblokir') DEFAULT 'tersedia'
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
  `kursi_jadwal_id` int(11) NOT NULL,
  `kursi_id` int(11) NOT NULL,
  `jadwal_id` int(11) NOT NULL,
  `status` enum('tersedia','dipesan','rusak','diblokir') DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kursi_jadwal`
--

INSERT INTO `kursi_jadwal` (`kursi_jadwal_id`, `kursi_id`, `jadwal_id`, `status`) VALUES
(32, 5, 24, 'tersedia'),
(33, 6, 24, 'dipesan'),
(34, 7, 24, 'dipesan'),
(35, 8, 24, 'dipesan'),
(36, 9, 24, 'dipesan'),
(37, 21, 21, 'tersedia'),
(38, 37, 21, 'tersedia'),
(39, 36, 21, 'tersedia'),
(40, 20, 21, 'tersedia'),
(41, 38, 21, 'tersedia'),
(42, 22, 21, 'tersedia'),
(43, 6, 20, 'dipesan'),
(44, 7, 20, 'dipesan'),
(45, 20, 19, 'dipesan'),
(46, 21, 19, 'dipesan'),
(47, 5, 20, 'dipesan'),
(48, 11, 24, 'dipesan'),
(49, 5, 21, 'dipesan'),
(50, 6, 21, 'dipesan'),
(51, 3, 26, 'dipesan'),
(52, 10, 24, 'dipesan'),
(53, 8, 20, 'dipesan'),
(54, 13, 24, 'tersedia'),
(55, 16, 24, 'tersedia'),
(56, 17, 24, 'tersedia'),
(57, 18, 24, 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `pelanggan_id` int(15) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `foto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`pelanggan_id`, `nama`, `email`, `password`, `no_telepon`, `role`, `foto`) VALUES
(1, 'Trisna Almuti', 'trisna@gmail.com', '123456', '0895711856677', 'user', '6743fd793be80.jpg'),
(2, 'Dani Putra', 'dani@gmail.com', '123', '083294237134', 'admin', ''),
(3, 'Ario Azhar', 'ariogntng@gmail.com', '123', '08923453212', 'user', ''),
(4, 'Aldi', 'aldi@gmail.com', '123', '08340952341', 'admin', ''),
(5, 'Keem', 'keem@gmail.com', 'tes123', '0813', 'user', ''),
(6, 'Trisna', 'muuhiqbal7@gmail.com', 'beduk123', '8992555867', 'user', ''),
(7, 'anggun', 'iqbalmuuh@gmail.com', 'jajay123', '08992555867', 'user', ''),
(8, 'ucok', 'ucok@gmail.com', '123', '08903245321', 'user', '6745b444632f4.jpg'),
(9, 'Hakeem', 'jawa@gmail.com', '123', '08923532123', 'user', '67471eaec7d0f.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `transaksi_id` int(11) NOT NULL,
  `pelanggan_id` int(15) DEFAULT NULL,
  `jadwal_id` int(11) DEFAULT NULL,
  `tanggal_transaksi` date DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL,
  `status` enum('dipesan','dibatalkan','selesai') DEFAULT 'dipesan',
  `tanggal_pembatalan` date DEFAULT NULL,
  `jumlah_refund` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`transaksi_id`, `pelanggan_id`, `jadwal_id`, `tanggal_transaksi`, `total_harga`, `status`, `tanggal_pembatalan`, `jumlah_refund`) VALUES
(32, 1, 24, '2024-11-24', 300000.00, 'dipesan', NULL, NULL),
(33, 1, 21, '2024-11-24', 300000.00, 'dibatalkan', '2024-11-25', 300000.00),
(34, 1, 20, '2024-11-24', 100000.00, 'dipesan', NULL, NULL),
(35, 1, 19, '2024-11-24', 100000.00, 'dipesan', NULL, NULL),
(36, 3, 20, '2024-11-24', 50000.00, 'dibatalkan', '2024-11-25', 50000.00),
(37, 3, 24, '2024-11-25', 60000.00, 'dipesan', NULL, NULL),
(38, 1, 21, '2024-11-26', 100000.00, 'dipesan', NULL, NULL),
(39, 8, 20, '2024-11-26', 50000.00, 'dipesan', NULL, NULL),
(40, 8, 26, '2024-11-26', 45000.00, 'dipesan', NULL, NULL),
(41, 5, 24, '2024-11-27', 60000.00, 'dipesan', NULL, NULL),
(42, 9, 20, '2024-11-27', 50000.00, 'dipesan', NULL, NULL),
(43, 9, 24, '2024-11-27', 240000.00, 'dibatalkan', '2024-11-27', 240000.00);

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
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `film_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `jadwal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `kursi`
--
ALTER TABLE `kursi`
  MODIFY `kursi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `kursi_jadwal`
--
ALTER TABLE `kursi_jadwal`
  MODIFY `kursi_jadwal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `transaksi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

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
