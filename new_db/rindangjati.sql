-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2023 at 07:26 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rindangjati`
--

-- --------------------------------------------------------

--
-- Table structure for table `log_produksi`
--

DROP TABLE IF EXISTS `log_produksi`;
CREATE TABLE `log_produksi` (
  `id_lgp` int(3) NOT NULL,
  `id_user` int(3) NOT NULL,
  `kode_produksi` varchar(5) NOT NULL,
  `tms_tmp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `log` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `log_produksi`
--

INSERT INTO `log_produksi` (`id_lgp`, `id_user`, `kode_produksi`, `tms_tmp`, `log`) VALUES
(1, 1, '58430', '2023-01-28 02:57:42', 'Telah menambahkan produksi baru dengan kode produksi (<strong>58430</strong>)'),
(2, 1, '872B3', '2023-01-28 03:02:03', 'Telah menambahkan produksi baru dengan kode produksi (<strong>872B3</strong>)'),
(3, 1, 'E85D0', '2023-01-28 03:04:13', 'Telah menambahkan produksi baru dengan kode produksi (<strong>E85D0</strong>)'),
(4, 1, '58430', '2023-01-28 03:06:08', 'Telah mengirim barang ke <strong>Pusatex</strong> dengan kode produksi (<strong>58430</strong>)');

-- --------------------------------------------------------

--
-- Table structure for table `log_program`
--

DROP TABLE IF EXISTS `log_program`;
CREATE TABLE `log_program` (
  `id_logprogram` int(3) NOT NULL,
  `id_user` int(3) NOT NULL,
  `tm_stmp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `log` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `log_program`
--

INSERT INTO `log_program` (`id_logprogram`, `id_user`, `tm_stmp`, `log`) VALUES
(1, 1, '2023-01-28 02:57:42', 'Telah menambahkan produksi baru dengan kode produksi (<strong>58430</strong>)'),
(2, 1, '2023-01-28 02:59:36', 'Menambahkan sebanyak 10 roll data di dalam packing list (58430).'),
(3, 1, '2023-01-28 03:02:03', 'Telah menambahkan produksi baru dengan kode produksi (<strong>872B3</strong>)'),
(4, 1, '2023-01-28 03:03:30', 'Menambahkan sebanyak 10 roll data di dalam packing list (872B3).'),
(5, 1, '2023-01-28 03:04:13', 'Telah menambahkan produksi baru dengan kode produksi (<strong>E85D0</strong>)'),
(6, 1, '2023-01-28 03:05:11', 'Menambahkan sebanyak 4 roll data di dalam packing list (E85D0).'),
(7, 1, '2023-01-28 03:06:08', 'Telah mengirim barang ke <strong>Pusatex</strong> dengan kode produksi (<strong>58430</strong>)'),
(8, 1, '2023-01-28 03:06:19', 'Menambahkan sebanyak 10 roll data di dalam packing list (58430).');

-- --------------------------------------------------------

--
-- Table structure for table `new_tb_pkg_fol`
--

DROP TABLE IF EXISTS `new_tb_pkg_fol`;
CREATE TABLE `new_tb_pkg_fol` (
  `id_fol` int(11) NOT NULL,
  `kode_produksi` varchar(10) NOT NULL,
  `asal` enum('list','ins') NOT NULL,
  `id_asal` int(11) NOT NULL,
  `no_roll` varchar(10) NOT NULL,
  `tgl` date NOT NULL,
  `ukuran` double NOT NULL,
  `operator` varchar(150) NOT NULL,
  `st_folding` enum('Grey','Finish') NOT NULL,
  `ukuran_now` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `new_tb_pkg_ins`
--

DROP TABLE IF EXISTS `new_tb_pkg_ins`;
CREATE TABLE `new_tb_pkg_ins` (
  `id_pkgins` int(11) NOT NULL,
  `id_pkg` int(11) NOT NULL,
  `kode_produksi` varchar(6) NOT NULL,
  `no_roll` varchar(10) NOT NULL,
  `ukuran_a` double NOT NULL,
  `ukuran_b` double NOT NULL,
  `ukuran_c` double NOT NULL,
  `ukuran_bs` double NOT NULL,
  `ukuran_now` double NOT NULL,
  `operator` varchar(100) NOT NULL,
  `satuan` enum('Yard','Meter') NOT NULL,
  `tgl` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `new_tb_pkg_list`
--

DROP TABLE IF EXISTS `new_tb_pkg_list`;
CREATE TABLE `new_tb_pkg_list` (
  `id_pkg` int(11) NOT NULL,
  `kode_produksi` varchar(6) NOT NULL,
  `no_roll` varchar(10) NOT NULL,
  `ukuran_a` double NOT NULL,
  `ukuran_b` double NOT NULL,
  `ukuran_c` double NOT NULL,
  `ukuran_bs` double NOT NULL,
  `ukuran_now` double NOT NULL,
  `operator` varchar(100) NOT NULL,
  `st_pkg` enum('IG','FG') NOT NULL,
  `satuan` enum('Yard','Meter') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `new_tb_pkg_list`
--

INSERT INTO `new_tb_pkg_list` (`id_pkg`, `kode_produksi`, `no_roll`, `ukuran_a`, `ukuran_b`, `ukuran_c`, `ukuran_bs`, `ukuran_now`, `operator`, `st_pkg`, `satuan`) VALUES
(1, '58430', 'Q1', 200, 0, 0, 15, 200, 'Deni', 'IG', 'Meter'),
(2, '58430', 'Q2', 200, 0, 0, 20, 200, 'Deni', 'IG', 'Meter'),
(3, '58430', 'Q3', 200, 0, 0, 15, 200, 'Deni', 'IG', 'Meter'),
(4, '58430', 'Q4', 200, 0, 0, 10, 200, 'Deni', 'IG', 'Meter'),
(5, '58430', 'Q5', 200, 0, 0, 0, 200, 'Deni', 'IG', 'Meter'),
(6, '58430', 'R1', 200, 0, 0, 0, 200, 'Deni', 'IG', 'Meter'),
(7, '58430', 'R2', 200, 0, 0, 0, 200, 'Deni', 'IG', 'Meter'),
(8, '58430', 'R3', 200, 0, 0, 0, 200, 'Deni', 'IG', 'Meter'),
(9, '58430', 'R4', 200, 0, 0, 0, 200, 'Deni', 'IG', 'Meter'),
(10, '58430', 'R5', 200, 0, 0, 0, 200, 'Deni', 'IG', 'Meter'),
(11, '872B3', 'P1', 500, 0, 0, 0, 500, 'JONI', 'IG', 'Meter'),
(12, '872B3', 'P2', 500, 0, 0, 0, 500, 'JONI', 'IG', 'Meter'),
(13, '872B3', 'P3', 500, 0, 0, 0, 500, 'JONI', 'IG', 'Meter'),
(14, '872B3', 'P4', 500, 0, 0, 0, 500, 'JONI', 'IG', 'Meter'),
(15, '872B3', 'P5', 500, 0, 0, 0, 500, 'JONI', 'IG', 'Meter'),
(16, '872B3', 'L1', 500, 0, 0, 0, 500, 'JONI', 'IG', 'Meter'),
(17, '872B3', 'L2', 500, 0, 0, 0, 500, 'JONI', 'IG', 'Meter'),
(18, '872B3', 'L3', 500, 0, 0, 0, 500, 'JONI', 'IG', 'Meter'),
(19, '872B3', 'L4', 500, 0, 0, 0, 500, 'JONI', 'IG', 'Meter'),
(20, '872B3', 'L5', 500, 0, 0, 0, 500, 'JONI', 'IG', 'Meter'),
(21, 'E85D0', 'T1', 1000, 0, 0, 15, 1000, 'HENI', 'IG', 'Meter'),
(22, 'E85D0', 'T2', 1000, 0, 0, 15, 1000, 'HENI', 'IG', 'Meter'),
(23, 'E85D0', 'T3', 1000, 0, 0, 20, 1000, 'HENI', 'IG', 'Meter'),
(24, 'E85D0', 'T4', 500, 0, 0, 30, 500, 'HENI', 'IG', 'Meter');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
CREATE TABLE `notifikasi` (
  `id_notif` int(4) NOT NULL,
  `departement` enum('RJS','Pusatex','Samatex') NOT NULL,
  `notif` text NOT NULL,
  `tm_stmp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id_notif`, `departement`, `notif`, `tm_stmp`) VALUES
(1, 'Pusatex', 'RJS telah mengirim barang ke Pusatex dengan nomor packing list (58430)', '2023-01-28 03:06:08');

-- --------------------------------------------------------

--
-- Table structure for table `pkg`
--

DROP TABLE IF EXISTS `pkg`;
CREATE TABLE `pkg` (
  `no_pkg` int(4) NOT NULL,
  `kode_produksi` varchar(6) NOT NULL,
  `nolist` varchar(10) NOT NULL,
  `alpabet` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `report_produksi_harian`
--

DROP TABLE IF EXISTS `report_produksi_harian`;
CREATE TABLE `report_produksi_harian` (
  `id_rptd` int(4) NOT NULL,
  `kode_konstruksi` varchar(10) NOT NULL,
  `ins_grey` double NOT NULL,
  `ins_finish` double NOT NULL,
  `fol_grey` double NOT NULL,
  `fol_finish` double NOT NULL,
  `lokasi_produksi` varchar(10) NOT NULL,
  `waktu` date NOT NULL,
  `terjual` double NOT NULL,
  `bs` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `report_produksi_harian`
--

INSERT INTO `report_produksi_harian` (`id_rptd`, `kode_konstruksi`, `ins_grey`, `ins_finish`, `fol_grey`, `fol_finish`, `lokasi_produksi`, `waktu`, `terjual`, `bs`) VALUES
(1, 'SM200', 2000, 0, 0, 0, 'RJS', '2023-01-28', 0, 60),
(2, 'SM001', 5000, 0, 0, 0, 'RJS', '2023-01-28', 0, 0),
(3, 'SM009', 3500, 0, 0, 0, 'RJS', '2023-01-28', 0, 80);

-- --------------------------------------------------------

--
-- Table structure for table `report_stok`
--

DROP TABLE IF EXISTS `report_stok`;
CREATE TABLE `report_stok` (
  `id_stok` int(3) NOT NULL,
  `kode_konstruksi` varchar(15) NOT NULL,
  `stok_ins` double NOT NULL,
  `stok_ins_finish` double NOT NULL,
  `stok_fol` double NOT NULL,
  `stok_fol_finish` double NOT NULL,
  `terjual` double NOT NULL,
  `bs` double NOT NULL,
  `retur` int(4) NOT NULL,
  `departement` enum('RJS','Pusatex','Samatex') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `report_stok`
--

INSERT INTO `report_stok` (`id_stok`, `kode_konstruksi`, `stok_ins`, `stok_ins_finish`, `stok_fol`, `stok_fol_finish`, `terjual`, `bs`, `retur`, `departement`) VALUES
(1, 'SM200', 2000, 0, 0, 0, 0, 60, 0, 'RJS'),
(2, 'SM001', 5000, 0, 0, 0, 0, 0, 0, 'RJS'),
(3, 'SM009', 3500, 0, 0, 0, 0, 80, 0, 'RJS');

-- --------------------------------------------------------

--
-- Table structure for table `tb_konstruksi`
--

DROP TABLE IF EXISTS `tb_konstruksi`;
CREATE TABLE `tb_konstruksi` (
  `id_konstruksi` int(3) NOT NULL,
  `kode_konstruksi` varchar(10) NOT NULL,
  `stok` int(3) NOT NULL,
  `ket` varchar(254) NOT NULL,
  `jumlah_mesin` int(3) NOT NULL,
  `id_user` int(3) NOT NULL,
  `tm_stmp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `minstok` int(3) NOT NULL,
  `maxstok` int(3) NOT NULL,
  `stok_akhir` int(3) NOT NULL,
  `chto` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_konstruksi`
--

INSERT INTO `tb_konstruksi` (`id_konstruksi`, `kode_konstruksi`, `stok`, `ket`, `jumlah_mesin`, `id_user`, `tm_stmp`, `minstok`, `maxstok`, `stok_akhir`, `chto`) VALUES
(1, 'SM5E', 0, 'Stok SM5E terbaru', 10, 1, '2023-01-26 01:35:56', 500, 10000, 0, '9001P'),
(2, 'SM001', 0, 'Stok SM001 terbaru', 10, 1, '2023-01-26 01:34:10', 500, 10000, 0, 'NULL'),
(3, 'SM009', 0, 'Stok SM009 terbaru', 10, 1, '2023-01-26 01:34:32', 500, 10000, 0, 'NULL'),
(4, 'SM200', 0, 'Stok SM200 terbaru', 10, 1, '2023-01-26 01:34:54', 500, 10000, 0, 'NULL'),
(5, '9001P', 0, 'Stok 9001P terbaru', 20, 1, '2023-01-26 01:35:39', 500, 10000, 0, 'NULL'),
(6, 'PST001', 0, 'input qiyu qiyu', 10, 1, '2023-01-26 03:21:39', 500, 10000, 0, 'NULL');

-- --------------------------------------------------------

--
-- Table structure for table `tb_packinglist`
--

DROP TABLE IF EXISTS `tb_packinglist`;
CREATE TABLE `tb_packinglist` (
  `id_pkg` int(3) NOT NULL,
  `kode_produksi` varchar(5) NOT NULL,
  `ig_nomc` varchar(10) NOT NULL,
  `ig_ukuran` double NOT NULL,
  `ig_ukuran_skrg` double NOT NULL,
  `ig_ukuranb` double NOT NULL,
  `ig_ukuranc` double NOT NULL,
  `ig_ukuranbs` double NOT NULL,
  `ig_satuan` enum('null','Yard','Meter') NOT NULL,
  `ig_operator` varchar(200) NOT NULL,
  `fg_tgl` date NOT NULL,
  `fg_operator` varchar(100) NOT NULL,
  `fg_ukuran` double NOT NULL,
  `fg_satuan` enum('null','Yard','Meter') NOT NULL,
  `if_tgl` date NOT NULL,
  `if_operator` varchar(100) NOT NULL,
  `if_ukuran` double NOT NULL,
  `if_ukuran_skrg` double NOT NULL,
  `if_ukuranb` double NOT NULL,
  `if_ukuranc` double NOT NULL,
  `if_ukuranbs` double NOT NULL,
  `if_satuan` enum('null','Yard','Meter') NOT NULL,
  `if_chto` varchar(10) DEFAULT NULL,
  `ff_tgl` date NOT NULL,
  `ff_operator` varchar(100) NOT NULL,
  `ff_ukuran` double NOT NULL,
  `ff_satuan` enum('null','Yard','Meter') NOT NULL,
  `new_pkglist` varchar(10) NOT NULL,
  `st_folding` enum('Null','Grey','Finish') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_penjualan`
--

DROP TABLE IF EXISTS `tb_penjualan`;
CREATE TABLE `tb_penjualan` (
  `id_penjualan` int(3) NOT NULL,
  `kode_konstruksi` varchar(10) NOT NULL,
  `tgl` date NOT NULL,
  `jumlah_penjualan` int(3) NOT NULL,
  `tm_stmp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_user` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_produksi`
--

DROP TABLE IF EXISTS `tb_produksi`;
CREATE TABLE `tb_produksi` (
  `id_produksi` int(3) NOT NULL,
  `kode_produksi` varchar(5) NOT NULL,
  `tgl_produksi` date NOT NULL,
  `kode_konstruksi` varchar(10) NOT NULL,
  `lokasi_produksi` enum('RJS','Pusatex','Samatex') NOT NULL,
  `jumlah_mesin` int(3) NOT NULL,
  `tm_stmp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_user` int(3) NOT NULL,
  `jumlah_produksi_awal` double NOT NULL,
  `jumlah_produksi_now` double NOT NULL,
  `lokasi_saat_ini` enum('RJS','Pusatex','Samatex') NOT NULL,
  `satuan` enum('Yard','Meter') NOT NULL,
  `st_produksi` enum('IG','IF','FG','FF') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_produksi`
--

INSERT INTO `tb_produksi` (`id_produksi`, `kode_produksi`, `tgl_produksi`, `kode_konstruksi`, `lokasi_produksi`, `jumlah_mesin`, `tm_stmp`, `id_user`, `jumlah_produksi_awal`, `jumlah_produksi_now`, `lokasi_saat_ini`, `satuan`, `st_produksi`) VALUES
(1, '58430', '2023-01-28', 'SM200', 'RJS', 10, '2023-01-28 03:06:08', 1, 2000, 2000, 'Pusatex', 'Meter', 'IG'),
(2, '872B3', '2023-01-28', 'SM001', 'RJS', 10, '2023-01-28 03:02:03', 1, 5000, 5000, 'RJS', 'Meter', 'IG'),
(3, 'E85D0', '2023-01-28', 'SM009', 'RJS', 4, '2023-01-28 03:04:13', 1, 3500, 3500, 'RJS', 'Meter', 'IG');

-- --------------------------------------------------------

--
-- Table structure for table `tb_proses_produksi`
--

DROP TABLE IF EXISTS `tb_proses_produksi`;
CREATE TABLE `tb_proses_produksi` (
  `id_proses` int(11) NOT NULL,
  `kode_produksi` varchar(6) NOT NULL,
  `tgl` date NOT NULL,
  `jumlah_awal` double NOT NULL,
  `satuan` enum('Yard','Meter') NOT NULL,
  `proses_name` enum('IF','FG','FF') NOT NULL,
  `tm_stmp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `pemroses` int(3) NOT NULL,
  `jumlah_akhir` double NOT NULL,
  `ch_to` int(3) NOT NULL,
  `lokasi_produksi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_tracking_produksi`
--

DROP TABLE IF EXISTS `tb_tracking_produksi`;
CREATE TABLE `tb_tracking_produksi` (
  `id_tracking` int(11) NOT NULL,
  `kode_produksi` varchar(5) NOT NULL,
  `tgl_produksi` date NOT NULL,
  `lokasi_produksi` enum('RJS','Pusatex','Samatex') NOT NULL,
  `jumlah_mesin` int(3) NOT NULL,
  `tm_stmp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `jumlah_produksi_inspect` double NOT NULL,
  `jumlah_produksi_folding` double NOT NULL,
  `id_user` int(3) NOT NULL,
  `satuan` enum('Yard','Meter') NOT NULL,
  `st_folding` enum('Null','Grey','Finish') NOT NULL,
  `inspect_finish` enum('n','y') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id_user` int(3) NOT NULL,
  `username` varchar(70) NOT NULL,
  `password` varchar(200) NOT NULL,
  `nama_user` varchar(200) NOT NULL,
  `hak_akses` enum('Operator','Manager') NOT NULL,
  `departement` enum('RJS','Pusatex','Samatex') NOT NULL,
  `yg_nambah` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama_user`, `hak_akses`, `departement`, `yg_nambah`) VALUES
(1, 'hamid@rindangjati.com', '8cb2237d0679ca88db6464eac60da96345513964', 'Hamid', 'Manager', 'RJS', 0),
(2, 'adi@rindangjati.com', '8cb2237d0679ca88db6464eac60da96345513964', 'Adi Subuhadir', 'Operator', 'RJS', 0),
(5, 'nisa@rindangjati.com', '8cb2237d0679ca88db6464eac60da96345513964', 'Nisa', 'Operator', 'RJS', 1),
(6, 'risqi@rindangjati.com', '8cb2237d0679ca88db6464eac60da96345513964', 'Risqi', 'Operator', 'Samatex', 1),
(8, 'yusuf@rindangjati.com', '8cb2237d0679ca88db6464eac60da96345513964', 'Yusuf', 'Operator', 'Pusatex', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_stok`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `v_stok`;
CREATE TABLE `v_stok` (
`id_produksi` int(3)
,`kode_produksi` varchar(5)
,`kode_konstruksi` varchar(10)
,`id_tracking` int(11)
,`newkd` varchar(5)
,`jumlah_produksi_folding` double
,`lokasi_produksi` enum('RJS','Pusatex','Samatex')
,`satuan` enum('Yard','Meter')
,`st_folding` enum('Null','Grey','Finish')
);

-- --------------------------------------------------------

--
-- Structure for view `v_stok`
--
DROP TABLE IF EXISTS `v_stok`;

DROP VIEW IF EXISTS `v_stok`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stok`  AS SELECT `tb_produksi`.`id_produksi` AS `id_produksi`, `tb_produksi`.`kode_produksi` AS `kode_produksi`, `tb_produksi`.`kode_konstruksi` AS `kode_konstruksi`, `tb_tracking_produksi`.`id_tracking` AS `id_tracking`, `tb_tracking_produksi`.`kode_produksi` AS `newkd`, `tb_tracking_produksi`.`jumlah_produksi_folding` AS `jumlah_produksi_folding`, `tb_tracking_produksi`.`lokasi_produksi` AS `lokasi_produksi`, `tb_tracking_produksi`.`satuan` AS `satuan`, `tb_tracking_produksi`.`st_folding` AS `st_folding` FROM (`tb_produksi` join `tb_tracking_produksi`) WHERE `tb_produksi`.`kode_produksi` = `tb_tracking_produksi`.`kode_produksi` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_produksi`
--
ALTER TABLE `log_produksi`
  ADD PRIMARY KEY (`id_lgp`);

--
-- Indexes for table `log_program`
--
ALTER TABLE `log_program`
  ADD PRIMARY KEY (`id_logprogram`);

--
-- Indexes for table `new_tb_pkg_fol`
--
ALTER TABLE `new_tb_pkg_fol`
  ADD PRIMARY KEY (`id_fol`);

--
-- Indexes for table `new_tb_pkg_ins`
--
ALTER TABLE `new_tb_pkg_ins`
  ADD PRIMARY KEY (`id_pkgins`);

--
-- Indexes for table `new_tb_pkg_list`
--
ALTER TABLE `new_tb_pkg_list`
  ADD PRIMARY KEY (`id_pkg`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notif`);

--
-- Indexes for table `pkg`
--
ALTER TABLE `pkg`
  ADD PRIMARY KEY (`no_pkg`);

--
-- Indexes for table `report_produksi_harian`
--
ALTER TABLE `report_produksi_harian`
  ADD PRIMARY KEY (`id_rptd`);

--
-- Indexes for table `report_stok`
--
ALTER TABLE `report_stok`
  ADD PRIMARY KEY (`id_stok`);

--
-- Indexes for table `tb_konstruksi`
--
ALTER TABLE `tb_konstruksi`
  ADD PRIMARY KEY (`id_konstruksi`);

--
-- Indexes for table `tb_packinglist`
--
ALTER TABLE `tb_packinglist`
  ADD PRIMARY KEY (`id_pkg`);

--
-- Indexes for table `tb_penjualan`
--
ALTER TABLE `tb_penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indexes for table `tb_produksi`
--
ALTER TABLE `tb_produksi`
  ADD PRIMARY KEY (`id_produksi`);

--
-- Indexes for table `tb_proses_produksi`
--
ALTER TABLE `tb_proses_produksi`
  ADD PRIMARY KEY (`id_proses`);

--
-- Indexes for table `tb_tracking_produksi`
--
ALTER TABLE `tb_tracking_produksi`
  ADD PRIMARY KEY (`id_tracking`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_produksi`
--
ALTER TABLE `log_produksi`
  MODIFY `id_lgp` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `log_program`
--
ALTER TABLE `log_program`
  MODIFY `id_logprogram` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `new_tb_pkg_fol`
--
ALTER TABLE `new_tb_pkg_fol`
  MODIFY `id_fol` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `new_tb_pkg_ins`
--
ALTER TABLE `new_tb_pkg_ins`
  MODIFY `id_pkgins` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `new_tb_pkg_list`
--
ALTER TABLE `new_tb_pkg_list`
  MODIFY `id_pkg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notif` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pkg`
--
ALTER TABLE `pkg`
  MODIFY `no_pkg` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_produksi_harian`
--
ALTER TABLE `report_produksi_harian`
  MODIFY `id_rptd` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `report_stok`
--
ALTER TABLE `report_stok`
  MODIFY `id_stok` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_konstruksi`
--
ALTER TABLE `tb_konstruksi`
  MODIFY `id_konstruksi` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_packinglist`
--
ALTER TABLE `tb_packinglist`
  MODIFY `id_pkg` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_penjualan`
--
ALTER TABLE `tb_penjualan`
  MODIFY `id_penjualan` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_produksi`
--
ALTER TABLE `tb_produksi`
  MODIFY `id_produksi` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_proses_produksi`
--
ALTER TABLE `tb_proses_produksi`
  MODIFY `id_proses` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_tracking_produksi`
--
ALTER TABLE `tb_tracking_produksi`
  MODIFY `id_tracking` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
