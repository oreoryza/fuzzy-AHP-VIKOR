-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2024 at 06:43 AM
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
-- Database: `dbfahpvik`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_alternatif`
--

CREATE TABLE `tb_alternatif` (
  `kode_alternatif` varchar(16) NOT NULL,
  `tanggal` date NOT NULL,
  `nama_alternatif` varchar(256) NOT NULL DEFAULT '',
  `kategori` varchar(256) NOT NULL,
  `total` double NOT NULL,
  `rank` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_alternatif`
--

INSERT INTO `tb_alternatif` (`kode_alternatif`, `tanggal`, `nama_alternatif`, `kategori`, `total`, `rank`) VALUES
('A3', '2024-11-14', 'Pendapatan naik', 'Peluang', 0, 0),
('A2', '2024-11-14', 'pencurian', 'Ancaman', 1, 0),
('A1', '2024-11-14', 'kebakaran', 'Ancaman', 0.53707136237257, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_experts`
--

CREATE TABLE `tb_experts` (
  `kode_expert` varchar(16) NOT NULL,
  `tanggal` date NOT NULL,
  `nama_expert` varchar(256) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_experts`
--

INSERT INTO `tb_experts` (`kode_expert`, `tanggal`, `nama_expert`) VALUES
('E2', '2024-11-14', 'Fadia'),
('E1', '2024-11-14', 'Andita');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kriteria`
--

CREATE TABLE `tb_kriteria` (
  `kode_kriteria` varchar(16) NOT NULL,
  `tanggal` date NOT NULL,
  `nama_kriteria` varchar(256) NOT NULL,
  `atribut` varchar(256) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_kriteria`
--

INSERT INTO `tb_kriteria` (`kode_kriteria`, `tanggal`, `nama_kriteria`, `atribut`) VALUES
('C2', '2024-11-14', 'Dampak', 'benefit'),
('C3', '2024-11-14', 'Efektivitas Pengendalian Risiko', 'cost'),
('C1', '2024-11-14', 'Kemungkinan', 'benefit');

-- --------------------------------------------------------

--
-- Table structure for table `tb_periode`
--

CREATE TABLE `tb_periode` (
  `tanggal` date NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_periode`
--

INSERT INTO `tb_periode` (`tanggal`, `nama`, `keterangan`) VALUES
('2024-11-06', 'B', ''),
('2024-11-14', 'A', '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_rel_alternatif`
--

CREATE TABLE `tb_rel_alternatif` (
  `ID` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `kode_expert` varchar(16) NOT NULL,
  `kode_alternatif` varchar(16) DEFAULT NULL,
  `kode_kriteria` varchar(16) DEFAULT NULL,
  `nilai` decimal(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_rel_alternatif`
--

INSERT INTO `tb_rel_alternatif` (`ID`, `tanggal`, `kode_expert`, `kode_alternatif`, `kode_kriteria`, `nilai`) VALUES
(880, '2024-11-14', 'E1', 'A3', 'C1', 2.00),
(879, '2024-11-14', 'E1', 'A3', 'C3', 3.00),
(878, '2024-11-14', 'E1', 'A3', 'C2', 1.00),
(877, '2024-11-14', 'E2', 'A3', 'C1', 4.00),
(876, '2024-11-14', 'E2', 'A3', 'C3', 2.00),
(875, '2024-11-14', 'E2', 'A3', 'C2', 3.00),
(874, '2024-11-14', 'E1', 'A2', 'C1', 2.00),
(873, '2024-11-14', 'E1', 'A2', 'C3', 3.00),
(872, '2024-11-14', 'E1', 'A2', 'C2', 1.00),
(871, '2024-11-14', 'E2', 'A2', 'C1', 3.00),
(870, '2024-11-14', 'E2', 'A2', 'C3', 4.00),
(869, '2024-11-14', 'E2', 'A2', 'C2', 2.00),
(868, '2024-11-14', 'E1', 'A1', 'C1', 3.00),
(867, '2024-11-14', 'E1', 'A1', 'C3', 2.00),
(866, '2024-11-14', 'E1', 'A1', 'C2', 4.00),
(865, '2024-11-14', 'E2', 'A1', 'C1', 2.00),
(864, '2024-11-14', 'E2', 'A1', 'C3', 4.00),
(863, '2024-11-14', 'E2', 'A1', 'C2', 5.00);

-- --------------------------------------------------------

--
-- Table structure for table `tb_rel_kriteria`
--

CREATE TABLE `tb_rel_kriteria` (
  `ID` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `kode_expert` varchar(16) NOT NULL,
  `ID1` varchar(16) DEFAULT NULL,
  `ID2` varchar(16) DEFAULT NULL,
  `nilai` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_rel_kriteria`
--

INSERT INTO `tb_rel_kriteria` (`ID`, `tanggal`, `kode_expert`, `ID1`, `ID2`, `nilai`) VALUES
(824, '2024-11-14', 'E1', 'C3', 'C3', 1),
(823, '2024-11-14', 'E1', 'C3', 'C2', 4),
(822, '2024-11-14', 'E1', 'C3', 'C1', 4),
(821, '2024-11-14', 'E1', 'C2', 'C3', 0.25),
(820, '2024-11-14', 'E1', 'C2', 'C2', 1),
(819, '2024-11-14', 'E1', 'C2', 'C1', 1),
(818, '2024-11-14', 'E1', 'C1', 'C3', 0.25),
(817, '2024-11-14', 'E1', 'C1', 'C2', 1),
(816, '2024-11-14', 'E1', 'C1', 'C1', 1),
(815, '2024-11-14', 'E2', 'C3', 'C3', 1),
(814, '2024-11-14', 'E2', 'C3', 'C2', 2),
(813, '2024-11-14', 'E2', 'C3', 'C1', 2),
(812, '2024-11-14', 'E2', 'C2', 'C3', 0.5),
(811, '2024-11-14', 'E2', 'C2', 'C2', 1),
(810, '2024-11-14', 'E2', 'C2', 'C1', 0.5),
(809, '2024-11-14', 'E2', 'C1', 'C3', 0.5),
(808, '2024-11-14', 'E2', 'C1', 'C2', 2),
(807, '2024-11-14', 'E2', 'C1', 'C1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `kode_user` varchar(16) DEFAULT NULL,
  `nama_user` varchar(255) DEFAULT NULL,
  `user` varchar(16) DEFAULT NULL,
  `pass` varchar(16) DEFAULT NULL,
  `level` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`kode_user`, `nama_user`, `user`, `pass`, `level`) VALUES
('U001', 'Administrator', 'admin', 'admin', 'admin'),
('U002', 'User', 'user', 'user', 'user'),
('U003', 'User', 'oryza', 'ore060203', 'ADMIN');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_alternatif`
--
ALTER TABLE `tb_alternatif`
  ADD PRIMARY KEY (`kode_alternatif`,`tanggal`) USING BTREE;

--
-- Indexes for table `tb_experts`
--
ALTER TABLE `tb_experts`
  ADD PRIMARY KEY (`kode_expert`,`tanggal`) USING BTREE;

--
-- Indexes for table `tb_kriteria`
--
ALTER TABLE `tb_kriteria`
  ADD PRIMARY KEY (`kode_kriteria`,`tanggal`) USING BTREE;

--
-- Indexes for table `tb_periode`
--
ALTER TABLE `tb_periode`
  ADD PRIMARY KEY (`tanggal`);

--
-- Indexes for table `tb_rel_alternatif`
--
ALTER TABLE `tb_rel_alternatif`
  ADD PRIMARY KEY (`ID`) USING BTREE;

--
-- Indexes for table `tb_rel_kriteria`
--
ALTER TABLE `tb_rel_kriteria`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_rel_alternatif`
--
ALTER TABLE `tb_rel_alternatif`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=881;

--
-- AUTO_INCREMENT for table `tb_rel_kriteria`
--
ALTER TABLE `tb_rel_kriteria`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=825;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
