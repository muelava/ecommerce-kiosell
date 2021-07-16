-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2021 at 12:55 PM
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
(3, 'PT. Kiosell', 'Kiosell', 'admin@kiosell.com', '628810534512', '457', 'Griya block A no 1, Serpong', 'sundulu', 'admin');

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
(65, 3, '60e52e2eb7a7a.jpg', '60e52e2eb7f78.jpeg', '60e52e2eb832c.jpeg', 'SABLON BAJU SATUAN BAHAN COMBED 20S, 30S DAN NSA ALL SIZE', '95000', 1000, 'Baru', '<p>Jasa sablon kaos kostum bisa :</p>\r\n\r\n<ul>\r\n	<li>Design sendiri</li>\r\n	<li>Tulisan sendiri</li>\r\n	<li>Gambar sendiri</li>\r\n	<li>Proses cepat</li>\r\n	<li>Harga miring</li>\r\n	<li>Bahan premium</li>\r\n	<li>Bisa satuan juga</li>\r\n</ul>\r\n\r\n<p>Bahan :</p>\r\n\r\n<ul>\r\n	<li>Cotton combed 30s</li>\r\n	<li>Cotton combed 20s</li>\r\n	<li>Cotton combed 24s</li>\r\n	<li>Cotton banboo 30s</li>\r\n	<li>NSA</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Harga :</strong></p>\r\n\r\n<ol>\r\n	<li><strong>Untuk satuan : 95k/pcs</strong></li>\r\n	<li><strong>Untuk Lusinan Minimal 12pcs = 65k/pcs</strong></li>\r\n</ol>\r\n\r\n<p>Order sekarang sebelum kaum #kimochi menyerang.</p>\r\n\r\n<p>Banyak diskon geng, beli tiga gratis satu. syarat dan Ketentuan Berlaku</p>\r\n\r\n<p>#belikaos #jualkaos #bajumurah #kaosbandung #kaossablon #bajukasual #bajukostum #bajutunang #kaosseragam #zonajepang #tulisanjepang #fastclothid #sablonbajusatuan #bikinbajusatuan #bajucostum</p>\r\n', 'Pakaian', '6', 'Rabu, 14/07/2021 23:42:23'),
(66, 3, '60e5318767fbf.png', '60e531876878a.png', '60e531876a5b0.png', 'SENDAL MANUSIA', '20000', 100, 'Baru', '<p>Jual Sendal Manusia Murah meriah tanpa adanya minus. Ada diskon beli satu gratis tiga. Yuk! tunggu apalagi</p>\r\n', 'Pakaian', '7', 'Rabu, 14/07/2021 23:42:14'),
(68, 3, '60e663b1d7222.png', '60e663b1d8364.png', '', 'HELM DAN OLI ', '500000', 250, 'Baru', '<p>Halo semuanya, pada kesempatan kali ini saya ingin memberikan barang diskon yang menarik. Kalian tau apa itu? yups bagi kalian pecinta otomotif tentu sudah tidak asing lagi dengan benda yang diatas.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<ol>\r\n	<li><strong>Helm KYT White (ada juga warna merah, biru dan hitam)</strong></li>\r\n	<li><strong>Oli Motor Matic (belum ada untuk motor non matik)</strong></li>\r\n</ol>\r\n\r\n<p>Nah itulah barang yang bisa saya promosikan didalam postingan ini, untuk yang ingin order, langsung saja klik beli yaa.. Ada dison khusus ketika mood saya bagus, akan saya berikan beli 1 gratis 3.</p>\r\n', 'Otomotif', '5', 'Rabu, 14/07/2021 23:38:26'),
(69, 3, '60e6781438466.png', '60e6781439064.png', '', 'HELM KYT KYOTO SOLID WHITE', '21000', 500, 'Baru', '<p>test</p>\r\n', 'Otomotif', '7', 'Rabu, 14/07/2021 23:41:54'),
(70, 3, '60e7bf6a8159f.png', '60e7bf6a81e8f.png', '', 'SEPATU KUDA', '150000', 9000, 'Baru', '<p>Coba</p>\r\n', 'Pakaian', '7', 'Rabu, 14/07/2021 23:43:53'),
(71, 3, '60e9abab5c4c2.png', '60e9abab5d666.png', '', 'SPEAKER BLUETOOTH PORTABLE + 2 MIC', '200000', 100, 'Baru', '<p><strong>Halo<em> semuanya balbalba</em></strong></p>\r\n\r\n<ol>\r\n	<li style=\"text-align:center\">asdfas</li>\r\n	<li style=\"text-align:center\">wasdfas</li>\r\n</ol>\r\n\r\n<p> </p>\r\n\r\n<ul>\r\n	<li style=\"text-align:center\">asdfasdfsadf</li>\r\n</ul>\r\n\r\n<p style=\"text-align:center\">asdfasdf</p>\r\n\r\n<p style=\"text-align:center\">asdf</p>\r\n\r\n<p style=\"text-align:center\">asdfa</p>\r\n\r\n<p style=\"text-align:center\">sdfasdfasdf</p>\r\n\r\n<p style=\"text-align:center\">asd</p>\r\n\r\n<p style=\"text-align:center\">f</p>\r\n\r\n<p style=\"text-align:center\">asd</p>\r\n\r\n<p style=\"text-align:center\">fasdf</p>\r\n', 'Elektronik', '8', 'Kamis, 15/07/2021 19:03:06'),
(72, 3, '60eef9236f218.jpg', '60eef92372b4e.jpg', '', 'BISMILLAH LAKU', '50000', 100, 'Baru', '<p>coba gaes</p>\r\n', 'Pakaian', '6', 'Rabu, 14/07/2021 23:40:30');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `kode_transaksi` int(11) NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `rekening` varchar(25) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `jml_tagihan` varchar(100) DEFAULT NULL,
  `jml_barang` int(11) DEFAULT NULL,
  `harga` varchar(100) DEFAULT NULL,
  `subtotal` varchar(100) DEFAULT NULL,
  `total_berat` int(11) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `alamat_pembeli` text DEFAULT NULL,
  `alamat_penjual` text DEFAULT NULL,
  `kode_pos` varchar(25) DEFAULT NULL,
  `kota_kab` varchar(50) DEFAULT NULL,
  `provinsi` varchar(50) DEFAULT NULL,
  `kurir` varchar(25) DEFAULT NULL,
  `ongkir` varchar(100) DEFAULT NULL,
  `status` enum('true','false','done','process') DEFAULT NULL,
  `wkt_beli` varchar(30) DEFAULT NULL,
  `no_resi` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `kode_transaksi`, `id_barang`, `id_admin`, `id_user`, `rekening`, `nama_barang`, `jml_tagihan`, `jml_barang`, `harga`, `subtotal`, `total_berat`, `catatan`, `alamat_pembeli`, `alamat_penjual`, `kode_pos`, `kota_kab`, `provinsi`, `kurir`, `ongkir`, `status`, `wkt_beli`, `no_resi`) VALUES
(22, 6437, 71, 3, 39, 'bca', 'SPEAKER BLUETOOTH PORTABLE + 2 MIC', '212.652', 1, '200000', '200000', 100, '', 'Jl. Pangandaran - Ciamis', 'Tangerang Selatan, Banten', '46385', 'Ciamis', 'Jawa Barat', 'jne', '12.000', 'done', '1626335662310', '1234'),
(24, 8040, 68, 3, 39, 'bri', 'HELM DAN OLI ', '1.024.997', 2, '500000', '1000000', 500, 'coba', 'Jl. Pangandaran - Ciamis', 'Tangerang Selatan, Banten', '46385', 'Ciamis', 'Jawa Barat', 'pos', '24.000', 'true', '1626442320590', 'asdfasdf'),
(28, 8190, 72, 3, 39, 'bri', 'BISMILLAH LAKU', '62.363', 1, '50000', '50000', 100, 'asdfasdf', ' Jl. Pangandaran - Ciamis', 'Tangerang Selatan, Banten', '46385', 'Ciamis', 'Jawa Barat', 'jne', '12.000', 'false', '1626488275690', '');

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `id_ulasan` int(11) NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `rating` varchar(5) DEFAULT NULL,
  `komentar` text DEFAULT NULL,
  `wkt_ulasan` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ulasan`
--

INSERT INTO `ulasan` (`id_ulasan`, `id_barang`, `id_user`, `rating`, `komentar`, `wkt_ulasan`) VALUES
(14, 71, 39, '5', 'Good Job! barangnya bagus tapi pengirimannya agak telat.', 'Jumat, 16/07/2021');

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
(39, 'Elang Hardifal', 'elang', 'muelava@gmail.com', '082115100979', 9, 103, '46385', ' Jl. Pangandaran - Ciamis', '$2y$10$74T9iuFIB7U5N7vxVsVqzesgINdgaHKa4Gm7YRkcJu5ewZak4VAji', 'user', 'Rabu, 14/07/2021 11:36:09');

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
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `transaksi` (`id_user`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id_ulasan`),
  ADD KEY `ulasan` (`id_barang`,`id_user`) USING BTREE,
  ADD KEY `id_user` (`id_user`);

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
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id_ulasan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `ulasan_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ulasan_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
