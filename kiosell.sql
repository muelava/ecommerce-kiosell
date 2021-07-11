-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2021 at 05:51 PM
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
-- Database: `kiosell`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nomor_hp` varchar(25) DEFAULT NULL,
  `distrik` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `status` enum('admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `username`, `email`, `nomor_hp`, `distrik`, `alamat`, `password`, `status`) VALUES
(3, 'Elang Hardifal', 'elang', 'elang@gmail.com', '6282115100979', '457', 'Griya block A no 1, Serpong', '123', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `gambar1` text DEFAULT NULL,
  `gambar2` text DEFAULT NULL,
  `gambar3` text DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `harga` varchar(50) DEFAULT NULL,
  `berat` int(11) DEFAULT NULL,
  `kondisi` enum('Baru','Bekas') DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `jml_barang` varchar(20) DEFAULT NULL,
  `wkt_post` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `id_admin`, `gambar1`, `gambar2`, `gambar3`, `nama_barang`, `harga`, `berat`, `kondisi`, `deskripsi`, `kategori`, `jml_barang`, `wkt_post`) VALUES
(65, 3, '60e52e2eb7a7a.jpg', '60e52e2eb7f78.jpeg', '60e52e2eb832c.jpeg', 'Sablon Baju Satuan Bahan Combed 20s, 30s dan NSA All Size', '95000', 1000, 'Baru', '<p>Jasa sablon kaos kostum bisa :</p><ul><li>Design sendiri</li><li>Tulisan sendiri</li><li>Gambar sendiri</li><li>Proses cepat</li><li>Harga miring</li><li>Bahan premium</li><li>Bisa satuan juga</li></ul><p>Bahan :</p><ul><li>Cotton combed 30s</li><li>Cotton combed 20s</li><li>Cotton combed 24s</li><li>Cotton banboo 30s</li><li>NSA</li></ul><p>&nbsp;</p><p><strong>Harga :</strong></p><ol><li><strong>Untuk satuan : 95k/pcs</strong></li><li><strong>Untuk Lusinan Minimal 12pcs = 65k/pcs</strong></li></ol><p>Order sekarang sebelum kaum #kimochi menyerang.</p><p>Banyak diskon geng, beli tiga gratis satu. syarat dan Ketentuan Berlaku</p><p>#belikaos #jualkaos #bajumurah #kaosbandung #kaossablon #bajukasual #bajukostum #bajutunang #kaosseragam #zonajepang #tulisanjepang #fastclothid #sablonbajusatuan #bikinbajusatuan #bajucostum</p>', 'Elektronik', '12', 'Jumat, 09/07/2021 10:26:25'),
(66, 3, '60e5318767fbf.png', '60e531876878a.png', '60e531876a5b0.png', 'Sendal Manusia', '20000', 100, 'Baru', '<p>Jual Sendal Manusia Murah meriah tanpa adanya minus. Ada diskon beli satu gratis tiga. Yuk! tunggu apalagi</p>', 'Elektronik', '46', 'Jumat, 09/07/2021 10:24:25'),
(68, 3, '60e663b1d7222.png', '60e663b1d8364.png', '', 'Helm dan Oli ', '500000', 250, 'Baru', '<p>Halo semuanya, pada kesempatan kali ini saya ingin memberikan barang diskon yang menarik. Kalian tau apa itu? yups bagi kalian pecinta otomotif tentu sudah tidak asing lagi dengan benda yang diatas.</p><p>&nbsp;</p><ol><li><strong>Helm KYT White (ada juga warna merah, biru dan hitam)</strong></li><li><strong>Oli Motor Matic (belum ada untuk motor non matik)</strong></li></ol><p>Nah itulah barang yang bisa saya promosikan didalam postingan ini, untuk yang ingin order, langsung saja klik beli yaa.. Ada dison khusus ketika mood saya bagus, akan saya berikan beli 1 gratis 3.</p>', 'Elektronik', '10', 'Jumat, 09/07/2021 10:25:57'),
(69, 3, '60e6781438466.png', '60e6781439064.png', '', 'Helm KYT Kyoto Solid White', '21000', 500, 'Baru', '<p>test</p>', 'Otomotif', '12', 'Jumat, 09/07/2021 10:22:47'),
(70, 3, '60e7bf6a8159f.png', '60e7bf6a81e8f.png', '', 'Sepatu', '150000', 9000, 'Baru', '<p>Coba</p>', 'Pakaian', '6', 'Sabtu, 10/07/2021 07:20:17'),
(71, 3, '60e9abab5c4c2.png', '60e9abab5d666.png', '', 'SPEAKER BLUETOOTH PORTABLE + 2 MIC', '200000', 100, 'Baru', '<p><strong>Halo<em> semuanya balbalba</em></strong></p>\r\n\r\n<ol>\r\n	<li style=\"text-align:center\">asdfas</li>\r\n	<li style=\"text-align:center\">wasdfas</li>\r\n</ol>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<ul>\r\n	<li style=\"text-align:center\">asdfasdfsadf</li>\r\n</ul>\r\n\r\n<p style=\"text-align:center\">asdfasdf</p>\r\n\r\n<p style=\"text-align:center\">asdf</p>\r\n\r\n<p style=\"text-align:center\">asdfa</p>\r\n\r\n<p style=\"text-align:center\">sdfasdfasdf</p>\r\n\r\n<p style=\"text-align:center\">asd</p>\r\n\r\n<p style=\"text-align:center\">f</p>\r\n\r\n<p style=\"text-align:center\">asd</p>\r\n\r\n<p style=\"text-align:center\">fasdf</p>\r\n', 'Elektronik', '5', 'Sabtu, 10/07/2021 21:19:34');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nomor_hp` varchar(25) DEFAULT NULL,
  `provinsi` int(11) DEFAULT NULL,
  `distrik` int(11) DEFAULT NULL,
  `kode_pos` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` enum('user') NOT NULL,
  `wkt_register` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_user`, `username`, `email`, `nomor_hp`, `provinsi`, `distrik`, `kode_pos`, `alamat`, `password`, `status`, `wkt_register`) VALUES
(36, 'Nasirudin Ahmad', 'ahmad', 'ahmad@gmail.com', '0823456789', 5, 501, '16111', ' Bogor itu kota hujan tuh abis', '$2y$10$w3kFmVT4BzVEauVur8AUcOOysAV8ScRmHvALHTgHl3qEOa8U89PcS', 'user', 'Sabtu, 10/07/2021 07:12:07'),
(37, 'Nasir Ahmad', 'nasirudin', 'nasirsuganda2@gmail.com', '083819802919', 9, 430, '43355', 'Desa Lebaksari, Parakansalak', '$2y$10$YqehniKybHEvUZTQR7qUtuPWJlxYcboCJPNf7A63l/wjvJSuVng76', 'user', 'Sabtu, 10/07/2021 20:02:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
