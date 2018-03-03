-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2016 at 11:05 AM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schneider`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminID` int(11) NOT NULL,
  `Username` varchar(50) DEFAULT NULL,
  `Fullname` varchar(250) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Password` varchar(200) NOT NULL,
  `SecuKy` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `Username`, `Fullname`, `Email`, `Password`, `SecuKy`) VALUES
(1, 'schneider', 'Schneider Main', 'admin@schneider-electric.com', 'bd7cdd31ba07b93b22981305b264a2ab', 'schneider'),
(2, 'guest', 'Guest', 'guest@schneider-electric.com', '084e0343a0486ff05530df6c705c8bb4', 'guest');

-- --------------------------------------------------------

--
-- Table structure for table `bays`
--

CREATE TABLE `bays` (
  `BayID` int(11) NOT NULL,
  `BayName` varchar(50) DEFAULT NULL,
  `BayActualName` varchar(50) DEFAULT NULL,
  `BayRating` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bays`
--

INSERT INTO `bays` (`BayID`, `BayName`, `BayActualName`, `BayRating`) VALUES
(1, 'PC167A', 'PC167', '250 kVA'),
(2, 'PC167B', 'PC167', '250 kVA'),
(3, 'PC168A', 'PC168', '250 kVA'),
(4, 'PC168B', 'PC168', '250 kVA'),
(5, 'PC169A', 'PC169', '250 kVA'),
(6, 'PC169B', 'PC169', '250 kVA'),
(7, 'PC170A', 'PC170', '500 kVA'),
(8, 'PC170B', 'PC170', '500 kVA'),
(9, 'PC171A', 'PC171', '500 kVA'),
(10, 'PC171B', 'PC171', '500 kVA'),
(11, 'PC172A', 'PC172', '500 kVA'),
(12, 'PC172B', 'PC172', '500 kVA'),
(13, 'PC173A', 'PC173', '2000 kVA'),
(14, 'PC173B', 'PC173', '2000 kVA'),
(15, 'PC174A', 'PC174', '1000 kVA'),
(16, 'PC174B', 'PC174', '1000 kVA'),
(17, 'PC175A', 'PC175', '1000 kVA'),
(18, 'PC175B', 'PC175', '1000 kVA'),
(19, 'F778', 'F778', 'DRIVES'),
(20, 'PC194', 'PC194', 'DRIVES'),
(21, 'FT1248', 'FT1248', 'DRIVES'),
(22, 'BI167', 'BI167', 'DRIVES'),
(23, 'FT1266', 'FT1266', 'DRIVES'),
(24, 'BI170', 'BI170', 'DRIVES'),
(25, 'PC234', 'PC234', 'DRIVES');

-- --------------------------------------------------------

--
-- Table structure for table `bayutil`
--

CREATE TABLE `bayutil` (
  `ID` int(11) NOT NULL,
  `BayDate` date NOT NULL,
  `BayID` int(11) NOT NULL,
  `BayActualName` varchar(50) NOT NULL,
  `UsageTime` int(11) NOT NULL DEFAULT '0',
  `DownTime` int(11) NOT NULL DEFAULT '0',
  `IdleTime` int(11) NOT NULL DEFAULT '1440',
  `UsageStart` datetime DEFAULT NULL,
  `UsageEnd` datetime DEFAULT NULL,
  `DownStart` datetime DEFAULT NULL,
  `DownEnd` datetime DEFAULT NULL,
  `Status` enum('idle','up','down','') NOT NULL DEFAULT 'idle',
  `StaticUsageTime` int(11) NOT NULL DEFAULT '0',
  `StaticDownTime` int(11) NOT NULL DEFAULT '0',
  `StaticIdleTime` int(11) NOT NULL DEFAULT '1440'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `idf`
--

CREATE TABLE `idf` (
  `idfID` int(11) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `idf`
--

INSERT INTO `idf` (`idfID`, `Name`, `Location`) VALUES
(1, 'IDF1', 'SchneiderLocation1'),
(2, 'IDF2', 'SchneiderLocation2'),
(3, 'IDF3', 'SchneiderLocation3'),
(4, 'IDF4', 'SchneiderLocation4'),
(5, 'IDF5', 'SchneiderLocation5'),
(6, 'IDF6', 'SchneiderLocation6'),
(7, 'IDF7', 'SchneiderLocation7'),
(8, 'IDF8', 'SchneiderLocation8'),
(9, 'IDF9', 'SchneiderLocation9'),
(10, 'IDF10', 'SchneiderLocation10');

-- --------------------------------------------------------

--
-- Table structure for table `ppi`
--

CREATE TABLE `ppi` (
  `id` int(11) NOT NULL,
  `SerialNumber` varchar(100) DEFAULT NULL,
  `ModelID` varchar(100) DEFAULT NULL,
  `BayName` varchar(50) NOT NULL,
  `Status` enum('inprogress','excess','under','complete','idle','fail') NOT NULL DEFAULT 'idle',
  `StartTest` datetime DEFAULT NULL,
  `EndTest` datetime DEFAULT NULL,
  `FailTime` datetime DEFAULT NULL,
  `ResumeTime` datetime DEFAULT NULL,
  `UsageTime` int(11) NOT NULL DEFAULT '0',
  `DownTime` int(11) NOT NULL DEFAULT '0',
  `FailCount` int(11) NOT NULL DEFAULT '0',
  `CycleTime` int(11) NOT NULL DEFAULT '0',
  `TotalTime` int(11) NOT NULL DEFAULT '0',
  `Tolerance` int(11) NOT NULL DEFAULT '0',
  `StaticUsageTime` int(11) NOT NULL DEFAULT '0',
  `StaticDownTime` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ppi`
--

INSERT INTO `ppi` (`id`, `SerialNumber`, `ModelID`, `BayName`, `Status`, `StartTest`, `EndTest`, `FailTime`, `ResumeTime`, `UsageTime`, `DownTime`, `FailCount`, `CycleTime`, `TotalTime`, `Tolerance`, `StaticUsageTime`, `StaticDownTime`) VALUES
(1, NULL, NULL, 'PC167A', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(2, NULL, NULL, 'PC167B', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(3, NULL, NULL, 'PC168A', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(4, NULL, NULL, 'PC168B', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(5, NULL, NULL, 'PC169A', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(6, NULL, NULL, 'PC169B', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(7, NULL, NULL, 'PC170A', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(8, NULL, NULL, 'PC170B', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(9, NULL, NULL, 'PC171A', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(10, NULL, NULL, 'PC171B', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(11, NULL, NULL, 'PC172A', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(12, NULL, NULL, 'PC172B', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(13, NULL, NULL, 'PC173A', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(14, NULL, NULL, 'PC173B', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(15, NULL, NULL, 'PC174A', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(16, NULL, NULL, 'PC174B', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(17, NULL, NULL, 'PC175A', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(18, NULL, NULL, 'PC175B', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(19, NULL, NULL, 'F778', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(20, NULL, NULL, 'PC194', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(21, NULL, NULL, 'FT1248', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(22, NULL, NULL, 'BI167', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(23, NULL, NULL, 'FT1266', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(24, NULL, NULL, 'BI170', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0),
(25, NULL, NULL, 'PC234', 'idle', NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `productcycles`
--

CREATE TABLE `productcycles` (
  `ProductCycleID` int(11) NOT NULL,
  `ModelID` varchar(50) DEFAULT NULL,
  `IDF` int(11) NOT NULL,
  `FamilyName` varchar(50) DEFAULT NULL,
  `Rating` varchar(50) DEFAULT NULL,
  `CycleTime` int(11) NOT NULL,
  `CycTol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productcycles`
--

INSERT INTO `productcycles` (`ProductCycleID`, `ModelID`, `IDF`, `FamilyName`, `Rating`, `CycleTime`, `CycTol`) VALUES
(1, 'PVSDC41101', 8, 'DC Box 600MM', NULL, 540, 30),
(2, 'PVSDC41201', 8, 'DC Box 600MM', NULL, 540, 30),
(3, 'PVSDC31101', 8, 'DC Box 600MM', NULL, 540, 30),
(4, 'PVSDC31111', 8, 'DC Box 600MM', NULL, 540, 30),
(5, 'PVSDC31201', 8, 'DC Box 600MM', NULL, 540, 30),
(6, 'PVSDC31211', 8, 'DC Box 600MM', NULL, 540, 30),
(7, 'NVE20497', 8, 'LV Box', NULL, 540, 30),
(8, 'PVSLV21110', 8, 'LV Box', NULL, 540, 30),
(9, 'PVSLV21210', 8, 'LV Box', NULL, 540, 30),
(10, 'PVSLV21310', 8, 'LV Box', NULL, 540, 30),
(11, 'PVSLV21410', 8, 'LV Box', NULL, 540, 30),
(12, 'PVSLV23410', 8, 'LV Box', NULL, 540, 30),
(13, 'NVE11823', 8, 'PV BOX 1.36W', NULL, 540, 30),
(14, 'NVE11829', 8, 'PV BOX 1.36W', NULL, 540, 30),
(15, 'NVE11833', 8, 'PV BOX 1.36W', NULL, 540, 30),
(16, 'NVE11840', 8, 'PV BOX 1.36W', NULL, 540, 30),
(17, 'NVE11867', 8, 'PV BOX 1.36W', NULL, 540, 30),
(18, 'NVE11885', 8, 'PV BOX 1.36W', NULL, 540, 30),
(19, 'NVE11887', 8, 'PV BOX 1.36W', NULL, 540, 30),
(20, 'NVE11891', 8, 'PV BOX 1.36W', NULL, 540, 30),
(21, 'NVE11894', 8, 'PV BOX 1.36W', NULL, 540, 30),
(22, 'PVBOX', 8, 'PV BOX 1.36W', NULL, 540, 30),
(23, '0G-XC500', 8, 'Xantrex - Cheetah', NULL, 540, 30),
(24, '0L-XCOPT-001', 8, 'Xantrex - Cheetah', NULL, 540, 30),
(25, '0L-XCOPT-012', 8, 'Xantrex - Cheetah', NULL, 540, 30),
(26, '0L-XCOPT-013', 8, 'Xantrex - Cheetah', NULL, 540, 30),
(27, '0M-XC540', 8, 'Xantrex - Cheetah', NULL, 540, 30),
(28, '0M-XC630', 8, 'Xantrex - Cheetah', NULL, 540, 30),
(29, '0M-XC680', 8, 'Xantrex - Cheetah', NULL, 540, 30),
(30, '0N-XCOPTFLO', 8, 'Xantrex - Cheetah', NULL, 540, 30),
(31, '0W-XCOPTNEG', 8, 'Xantrex - Cheetah', NULL, 540, 30),
(32, '0W-XCOPTPOS', 8, 'Xantrex - Cheetah', NULL, 540, 30),
(33, '0G-XC-BB', 8, 'Xantrex - Cheetah IEC', NULL, 540, 30),
(34, '0M-XC540BB', 8, 'Xantrex - Cheetah IEC', NULL, 540, 30),
(35, '0M-XC630BB', 8, 'Xantrex - Cheetah IEC', NULL, 540, 30),
(36, '0M-XC680BB', 8, 'Xantrex - Cheetah IEC', NULL, 540, 30),
(37, '0N-XCFLO-50178', 8, 'Xantrex - Cheetah IEC', NULL, 540, 30),
(38, '0N-XCFLO-62109', 8, 'Xantrex - Cheetah IEC', NULL, 540, 30),
(39, '0N-XCNEG-50178', 8, 'Xantrex - Cheetah IEC', NULL, 540, 30),
(40, '0N-XCNEG-50178-GF', 8, 'Xantrex - Cheetah IEC', NULL, 540, 30),
(41, '0N-XCNEG-62109', 8, 'Xantrex - Cheetah IEC', NULL, 540, 30),
(42, '0N-XCPOS-50178', 8, 'Xantrex - Cheetah IEC', NULL, 540, 30),
(43, '0N-XCPOS-50178-GF', 8, 'Xantrex - Cheetah IEC', NULL, 540, 30),
(44, '0N-XCPOS-62109', 8, 'Xantrex - Cheetah IEC', NULL, 540, 30),
(45, '0G-XC-UL', 8, 'Xantrex - Cheetah II', NULL, 540, 30),
(46, '0G-XCULCB', 8, 'Xantrex - Cheetah II', NULL, 540, 30),
(47, '0G-XCULDCBG', 8, 'Xantrex - Cheetah II', NULL, 540, 30),
(48, '0H-XCULDCBG-1SW2ST', 4, 'Xantrex - Cheetah II', NULL, 540, 30),
(49, 'ATV930C11N4C', 4, 'Fortis-HHP', NULL, 540, 30),
(50, 'ATV930C13N4C', 4, 'Fortis-HHP', NULL, 540, 30),
(51, 'ATV930C16N4C', 4, 'Fortis-HHP', NULL, 540, 30),
(52, 'ATV930D55M3C', 4, 'Fortis-HHP', NULL, 540, 30),
(53, 'ATV930D75M3C', 4, 'Fortis-HHP', NULL, 540, 30),
(54, 'ATV930D11M3', 4, 'Fortis-MHP', NULL, 540, 30),
(55, 'ATV930D15M3', 4, 'Fortis-MHP', NULL, 540, 30),
(56, 'ATV930D15N4', 4, 'Fortis-MHP', NULL, 540, 30),
(57, 'ATV930D18M3', 4, 'Fortis-MHP', NULL, 540, 30),
(58, 'ATV930D18N4', 4, 'Fortis-MHP', NULL, 540, 30),
(59, 'ATV930D22M3', 4, 'Fortis-MHP', NULL, 540, 30),
(60, 'ATV930D22N4', 4, 'Fortis-MHP', NULL, 540, 30),
(61, 'ATV930D30M3', 4, 'Fortis-MHP', NULL, 540, 30),
(62, 'ATV930D30M3C', 4, 'Fortis-MHP', NULL, 540, 30),
(63, 'ATV930D30N4', 4, 'Fortis-MHP', NULL, 540, 30),
(64, 'ATV930D37M3', 4, 'Fortis-MHP', NULL, 540, 30),
(65, 'ATV930D37M3C', 4, 'Fortis-MHP', NULL, 540, 30),
(66, 'ATV930D37N4', 4, 'Fortis-MHP', NULL, 540, 30),
(67, 'ATV930D45M3', 4, 'Fortis-MHP', NULL, 540, 30),
(68, 'ATV930D45M3C', 4, 'Fortis-MHP', NULL, 540, 30),
(69, 'ATV930D45N4', 4, 'Fortis-MHP', NULL, 540, 30),
(70, 'ATV930D55N4', 4, 'Fortis-MHP', NULL, 540, 30),
(71, 'ATV930D55N4C', 4, 'Fortis-MHP', NULL, 540, 30),
(72, 'ATV930D75N4', 4, 'Fortis-MHP', NULL, 540, 30),
(73, 'ATV930D75N4C', 4, 'Fortis-MHP', NULL, 540, 30),
(74, 'ATV930D90N4', 4, 'Fortis-MHP', NULL, 540, 30),
(75, 'ATV930D90N4C', 4, 'Fortis-MHP', NULL, 540, 30),
(76, 'ATV930U75M3', 4, 'Fortis-MHP', NULL, 540, 30),
(77, 'ATV61HC11N4', 4, 'HHP India', NULL, 540, 30),
(78, 'ATV61HC11N4D', 4, 'HHP India', NULL, 540, 30),
(79, 'ATV61HC11Y', 4, 'HHP India', NULL, 540, 30),
(80, 'ATV61HC13N4', 4, 'HHP India', NULL, 540, 30),
(81, 'ATV61HC13N4D', 4, 'HHP India', NULL, 540, 30),
(82, 'ATV61HC13Y', 4, 'HHP India', NULL, 540, 30),
(83, 'ATV61HC16N4', 4, 'HHP India', NULL, 540, 30),
(84, 'ATV61HC16N4D', 4, 'HHP India', NULL, 540, 30),
(85, 'ATV61HC16Y', 4, 'HHP India', NULL, 540, 30),
(86, 'ATV61HC20Y', 4, 'HHP India', NULL, 540, 30),
(87, 'ATV61HC22N4', 4, 'HHP India', NULL, 540, 30),
(88, 'ATV61HC22N4D', 4, 'HHP India', NULL, 540, 30),
(89, 'ATV61HC25N4', 4, 'HHP India', NULL, 540, 30),
(90, 'ATV61HC25N4D', 4, 'HHP India', NULL, 540, 30),
(91, 'ATV61HC25Y', 4, 'HHP India', NULL, 540, 30),
(92, 'ATV61HC31N4', 4, 'HHP India', NULL, 540, 30),
(93, 'ATV61HC31N4D', 4, 'HHP India', NULL, 540, 30),
(94, 'ATV61HC31Y', 4, 'HHP India', NULL, 540, 30),
(95, 'ATV61HC40N4', 4, 'HHP India', NULL, 540, 30),
(96, 'ATV61HC40N4D', 4, 'HHP India', NULL, 540, 30),
(97, 'ATV61HC40Y', 4, 'HHP India', NULL, 540, 30),
(98, 'ATV61HC50N4', 4, 'HHP India', NULL, 540, 30),
(99, 'ATV61HC50N4D', 4, 'HHP India', NULL, 540, 30),
(100, 'ATV61HC50Y', 4, 'HHP India', NULL, 540, 30),
(101, 'ATV61HC63N4', 4, 'HHP India', NULL, 540, 30),
(102, 'ATV61HC63N4D', 4, 'HHP India', NULL, 540, 30),
(103, 'ATV61HC63Y', 4, 'HHP India', NULL, 540, 30),
(104, 'ATV61HC80Y', 4, 'HHP India', NULL, 540, 30),
(105, 'ATV61HD90M3X', 4, 'HHP India', NULL, 540, 30),
(106, 'ATV61HD90N4', 4, 'HHP India', NULL, 540, 30),
(107, 'ATV61HD90N4D', 4, 'HHP India', NULL, 540, 30),
(108, 'ATV71HC11N4', 4, 'HHP India', NULL, 540, 30),
(109, 'ATV71HC11N4D', 4, 'HHP India', NULL, 540, 30),
(110, 'ATV71HC11Y', 4, 'HHP India', NULL, 540, 30),
(111, 'ATV71HC13N4', 4, 'HHP India', NULL, 540, 30),
(112, 'ATV71HC13N4D', 4, 'HHP India', NULL, 540, 30),
(113, 'ATV71HC13Y', 4, 'HHP India', NULL, 540, 30),
(114, 'ATV71HC16N4', 4, 'HHP India', NULL, 540, 30),
(115, 'ATV71HC16N4D', 4, 'HHP India', NULL, 540, 30),
(116, 'ATV71HC16Y', 4, 'HHP India', NULL, 540, 30),
(117, 'ATV71HC20N4', 4, 'HHP India', NULL, 540, 30),
(118, 'ATV71HC20N4D', 4, 'HHP India', NULL, 540, 30),
(119, 'ATV71HC20Y', 4, 'HHP India', NULL, 540, 30),
(120, 'ATV71HC25N4', 4, 'HHP India', NULL, 540, 30),
(121, 'ATV71HC25N4D', 4, 'HHP India', NULL, 540, 30),
(122, 'ATV71HC25Y', 4, 'HHP India', NULL, 540, 30),
(123, 'ATV71HC28N4', 4, 'HHP India', NULL, 540, 30),
(124, 'ATV71HC28N4D', 4, 'HHP India', NULL, 540, 30),
(125, 'ATV71HC31N4', 4, 'HHP India', NULL, 540, 30),
(126, 'ATV71HC31N4D', 4, 'HHP India', NULL, 540, 30),
(127, 'ATV71HC31Y', 4, 'HHP India', NULL, 540, 30),
(128, 'ATV71HC40N4', 4, 'HHP India', NULL, 540, 30),
(129, 'ATV71HC40N4D', 4, 'HHP India', NULL, 540, 30),
(130, 'ATV71HC40Y', 4, 'HHP India', NULL, 540, 30),
(131, 'ATV71HC50N4', 4, 'HHP India', NULL, 540, 30),
(132, 'ATV71HC50N4D', 4, 'HHP India', NULL, 540, 30),
(133, 'ATV71HC50Y', 4, 'HHP India', NULL, 540, 30),
(134, 'ATV71HC63Y', 4, 'HHP India', NULL, 540, 30),
(135, 'ATV71HD90N4', 4, 'HHP India', NULL, 540, 30),
(136, 'ATV71HD90N4D', 4, 'HHP India', NULL, 540, 30),
(137, 'ATV71HC11N4DG1IN', 4, 'HHP PACY', NULL, 540, 30),
(138, 'ATV71HC13N4DG1IN', 4, 'HHP PACY', NULL, 540, 30),
(139, 'ATV71HC16N4DG1IN', 4, 'HHP PACY', NULL, 540, 30),
(140, 'ATV71HC20N4DG1IN', 4, 'HHP PACY', NULL, 540, 30),
(141, 'ATV71HC25N4DG1IN', 4, 'HHP PACY', NULL, 540, 30),
(142, 'ATV71HC28N4DG1IN', 4, 'HHP PACY', NULL, 540, 30),
(143, 'ATV71HC31N4DG1IN', 4, 'HHP PACY', NULL, 540, 30),
(144, 'ATV71HC40N4DG1IN', 4, 'HHP PACY', NULL, 540, 30),
(145, 'ATV71HC50N4DG1IN', 4, 'HHP PACY', NULL, 540, 30),
(146, 'ATV71HD90N4DG1IN', 4, 'HHP PACY', NULL, 540, 30),
(147, 'ATV630D11M3', 4, 'KALA', NULL, 540, 30),
(148, 'ATV630D15M3', 4, 'KALA', NULL, 540, 30),
(149, 'ATV630D15N4', 4, 'KALA', NULL, 540, 30),
(150, 'ATV630D18M3', 4, 'KALA', NULL, 540, 30),
(151, 'ATV630D18N4', 4, 'KALA', NULL, 540, 30),
(152, 'ATV630D22M3', 4, 'KALA', NULL, 540, 30),
(153, 'ATV630D22N4', 4, 'KALA', NULL, 540, 30),
(154, 'ATV630D30M3', 4, 'KALA', NULL, 540, 30),
(155, 'ATV630D30N4', 4, 'KALA', NULL, 540, 30),
(156, 'ATV630D37M3', 4, 'KALA', NULL, 540, 30),
(157, 'ATV630D37N4', 4, 'KALA', NULL, 540, 30),
(158, 'ATV630D45M3', 4, 'KALA', NULL, 540, 30),
(159, 'ATV630D45N4', 4, 'KALA', NULL, 540, 30),
(160, 'ATV630D55N4', 4, 'KALA', NULL, 540, 30),
(161, 'ATV630D75N4', 4, 'KALA', NULL, 540, 30),
(162, 'ATV630D90N4', 4, 'KALA', NULL, 540, 30),
(163, 'ATV630U75M3', 4, 'KALA', NULL, 540, 30),
(164, 'ATV630C11N4', 4, 'KALA S6', NULL, 540, 30),
(165, 'ATV630C13N4', 4, 'KALA S6', NULL, 540, 30),
(166, 'ATV630C16N4', 4, 'KALA S6', NULL, 540, 30),
(167, 'ATV630D55M3', 4, 'KALA S6', NULL, 540, 30),
(168, 'ATV630D75M3', 4, 'KALA S6', NULL, 540, 30),
(169, 'ATV61HD18M3X', 4, 'MHP Export', NULL, 540, 30),
(170, 'ATV61HD18M3XZ', 4, 'MHP Export', NULL, 540, 30),
(171, 'ATV61HD22M3X', 4, 'MHP Export', NULL, 540, 30),
(172, 'ATV61HD22M3XZ', 4, 'MHP Export', NULL, 540, 30),
(173, 'ATV61HD30M3X', 4, 'MHP Export', NULL, 540, 30),
(174, 'ATV61HD30M3XZ', 4, 'MHP Export', NULL, 540, 30),
(175, 'ATV61HD37M3X', 4, 'MHP Export', NULL, 540, 30),
(176, 'ATV61HD37M3XZ', 4, 'MHP Export', NULL, 540, 30),
(177, 'ATV61HD45M3X', 4, 'MHP Export', NULL, 540, 30),
(178, 'ATV61HD45M3XZ', 4, 'MHP Export', NULL, 540, 30),
(179, 'ATV61HD45N4Z', 4, 'MHP Export', NULL, 540, 30),
(180, 'ATV61HD55M3X', 4, 'MHP Export', NULL, 540, 30),
(181, 'ATV61HD55N4Z', 4, 'MHP Export', NULL, 540, 30),
(182, 'ATV61HD75M3X', 4, 'MHP Export', NULL, 540, 30),
(183, 'ATV61HD75N4Z', 4, 'MHP Export', NULL, 540, 30),
(184, 'ATV71HD45N4Z', 4, 'MHP Export', NULL, 540, 30),
(185, 'ATV71HD55M3X', 4, 'MHP Export', NULL, 540, 30),
(186, 'ATV71HD55M3XD', 4, 'MHP Export', NULL, 540, 30),
(187, 'ATV71HD55N4Z', 4, 'MHP Export', NULL, 540, 30),
(188, 'ATV71HD75M3X', 4, 'MHP Export', NULL, 540, 30),
(189, 'ATV71HD75M3XD', 4, 'MHP Export', NULL, 540, 30),
(190, '0G-E8TM1100G', 8, 'EPS 1100', NULL, 540, 30),
(191, '0G-9459', 8, 'EPS 7000', NULL, 540, 30),
(192, '0G-7213010844-01', 8, 'EPS 8000', NULL, 540, 30),
(193, '0G-7213110844-01', 8, 'EPS 8000', NULL, 540, 30),
(194, '0G-7213111444-01', 8, 'EPS 8000', NULL, 540, 30),
(195, '0G-7213010844-TAA', 8, 'EPS 8000 - ETO', NULL, 540, 30),
(196, '0G-0146', 8, 'EPS Make over', NULL, 540, 30),
(197, '0G-0147', 8, 'EPS Make over', NULL, 540, 30),
(198, 'GVXIOCG', 5, 'Blue Door', NULL, 540, 30),
(199, 'GVXMBCG', 5, 'Blue Door', NULL, 540, 30),
(200, 'GVXMBCKA', 5, 'Blue Door', NULL, 540, 30),
(201, 'GVXP250KD', 5, 'Blue Door', NULL, 540, 30),
(202, 'GVXSFOPT1', 5, 'Blue Door', NULL, 540, 30),
(203, '0G-0901641', 8, 'Symmetra', NULL, 540, 30),
(204, '0G-0901642', 8, 'Symmetra', NULL, 540, 30),
(205, '0G-0901643', 8, 'Symmetra', NULL, 540, 30),
(206, '0G-0901644', 8, 'Symmetra', NULL, 540, 30),
(207, '0G-0901645', 8, 'Symmetra', NULL, 540, 30),
(208, '0G-0901646', 8, 'Symmetra', NULL, 540, 30),
(209, '0G-1006', 8, 'Symmetra', NULL, 540, 30),
(210, '0G-1007', 8, 'Symmetra', NULL, 540, 30),
(211, '0G-1008', 8, 'Symmetra', NULL, 540, 30),
(212, '0G-1009', 8, 'Symmetra', NULL, 540, 30),
(213, '0G-1024', 8, 'Symmetra', NULL, 540, 30),
(214, '0G-1025', 8, 'Symmetra', NULL, 540, 30),
(215, '0G-1031', 8, 'Symmetra', NULL, 540, 30),
(216, '0G-1032', 8, 'Symmetra', NULL, 540, 30),
(217, '0G-1033', 8, 'Symmetra', NULL, 540, 30),
(218, '0G-1034', 8, 'Symmetra', NULL, 540, 30),
(219, '0G-1040', 8, 'Symmetra', NULL, 540, 30),
(220, '0G-1041', 8, 'Symmetra', NULL, 540, 30),
(221, '0G-1055', 8, 'Symmetra', NULL, 540, 30),
(222, '0G-1083', 8, 'Symmetra', NULL, 540, 30),
(223, '0G-1084', 8, 'Symmetra', NULL, 540, 30),
(224, '0G-1085', 8, 'Symmetra', NULL, 540, 30),
(225, '0G-1086', 8, 'Symmetra', NULL, 540, 30),
(226, '0G-1087', 8, 'Symmetra', NULL, 540, 30),
(227, '0G-4000', 8, 'Symmetra', NULL, 540, 30),
(228, '0G-4001', 8, 'Symmetra', NULL, 540, 30),
(229, '0G-4017', 8, 'Symmetra', NULL, 540, 30),
(230, '0G-4018', 8, 'Symmetra', NULL, 540, 30),
(231, '0G-4020', 8, 'Symmetra', NULL, 540, 30),
(232, '0G-4022', 8, 'Symmetra', NULL, 540, 30),
(233, '0G-4023', 8, 'Symmetra', NULL, 540, 30),
(234, '0G-4023', 8, 'Symmetra', NULL, 540, 30),
(235, '0G-4027', 8, 'Symmetra', NULL, 540, 30),
(236, '0G-9342', 8, 'Symmetra', NULL, 540, 30),
(237, '0G-9343', 8, 'Symmetra', NULL, 540, 30),
(238, '0G-9572', 8, 'Symmetra', NULL, 540, 30),
(239, '0M-2196', 8, 'Symmetra', NULL, 540, 30),
(240, '0M-2197', 8, 'Symmetra', NULL, 540, 30),
(241, '0M-2274', 8, 'Symmetra', NULL, 540, 30),
(242, '0M-3408', 8, 'Symmetra', NULL, 540, 30),
(243, '0M-3409', 8, 'Symmetra', NULL, 540, 30),
(244, '0M-4137', 8, 'Symmetra', NULL, 540, 30),
(245, '0M-4281', 8, 'Symmetra', NULL, 540, 30),
(246, '0M-4991', 8, 'Symmetra', NULL, 540, 30),
(247, '0M-5042', 8, 'Symmetra', NULL, 540, 30),
(248, '0M-6438', 8, 'Symmetra', NULL, 540, 30),
(249, '0M-6439', 8, 'Symmetra', NULL, 540, 30),
(250, '0M-6474', 8, 'Symmetra', NULL, 540, 30),
(251, '0M-6475', 8, 'Symmetra', NULL, 540, 30),
(252, '0M-6476', 8, 'Symmetra', NULL, 540, 30),
(253, '0M-6477', 8, 'Symmetra', NULL, 540, 30),
(254, '0M-9722', 8, 'Symmetra', NULL, 540, 30),
(255, 'SYMWACBL1', 8, 'Symmetra', NULL, 540, 30),
(256, 'SYMWACBL10', 8, 'Symmetra', NULL, 540, 30),
(257, 'SYMWACBL20', 8, 'Symmetra', NULL, 540, 30),
(258, 'SYMWACBL30', 8, 'Symmetra', NULL, 540, 30),
(259, 'SYMWACBL75', 8, 'Symmetra', NULL, 540, 30),
(260, 'SYMWP126CBL10', 8, 'Symmetra', NULL, 540, 30),
(261, 'SYMWP126CBL15', 8, 'Symmetra', NULL, 540, 30),
(262, 'SYMWP126CBL25', 8, 'Symmetra', NULL, 540, 30),
(263, 'SYMWP127CBL10', 8, 'Symmetra', NULL, 540, 30),
(264, 'SYMWP127CBL15', 8, 'Symmetra', NULL, 540, 30),
(265, 'SYMWP127CBL25', 8, 'Symmetra', NULL, 540, 30),
(266, 'SYMWSU', 8, 'Symmetra', NULL, 540, 30),
(267, 'SYMWSUCBL200', 8, 'Symmetra', NULL, 540, 30),
(268, 'SYMWSUCBL25', 8, 'Symmetra', NULL, 540, 30),
(269, '0G-4002', 8, 'Symmetra - Laguna', NULL, 540, 30),
(270, '0G-4019', 8, 'Symmetra - Laguna', NULL, 540, 30),
(271, '0G-83419', 8, 'Symmetra - Laguna', NULL, 540, 30),
(272, '0G-GVMI200KH', 5, 'Valhal', NULL, 540, 30),
(273, '0G-GVMI225KG65K', 5, 'Valhal', NULL, 540, 30),
(274, '0G-GVMIP200KH', 5, 'Valhal', NULL, 540, 30),
(275, '0G-GVMIP225KG65K', 5, 'Valhal', NULL, 540, 30),
(276, '0G-GVMIPR225KG65K', 5, 'Valhal', NULL, 540, 30),
(277, '0G-GVMPB160K180D', 5, 'Valhal', NULL, 540, 30),
(278, '0G-GVMPB160KG', 5, 'Valhal', NULL, 540, 30),
(279, '0G-GVMPB200K225D', 5, 'Valhal', NULL, 540, 30),
(280, '0N-9255', 5, 'Valhal', NULL, 540, 30),
(281, '0N-9272', 5, 'Valhal', NULL, 540, 30),
(282, '0N-9281', 5, 'Valhal', NULL, 540, 30),
(283, '0N-9733', 5, 'Valhal', NULL, 540, 30),
(284, '0N-9934', 5, 'Valhal', NULL, 540, 30),
(285, 'GVMBBB600UL', 5, 'Valhal', NULL, 540, 30),
(286, 'GVMBBB630EL', 5, 'Valhal', NULL, 540, 30),
(287, 'GVMBBB630IEC', 5, 'Valhal', NULL, 540, 30),
(288, 'GVMBBK250IEC', 5, 'Valhal', NULL, 540, 30),
(289, 'GVMBBK250UL', 5, 'Valhal', NULL, 540, 30),
(290, 'GVMBBK400UL', 5, 'Valhal', NULL, 540, 30),
(291, 'GVMBBK630IEC', 5, 'Valhal', NULL, 540, 30),
(292, 'GVMDFW-KIT', 5, 'Valhal', NULL, 540, 30),
(293, 'GVMFU800WW', 5, 'Valhal', NULL, 540, 30),
(294, 'GVMIP1-KIT', 5, 'Valhal', NULL, 540, 30),
(295, 'GVMIP2-KIT', 5, 'Valhal', NULL, 540, 30),
(296, 'GVMKHS32KIT', 5, 'Valhal', NULL, 540, 30),
(297, 'GVML2MBCN-KIT', 5, 'Valhal', NULL, 540, 30),
(298, 'GVML2MBCW-KIT', 5, 'Valhal', NULL, 540, 30),
(299, 'GVMMODBCN', 5, 'Valhal', NULL, 540, 30),
(300, 'GVMMODBCW', 5, 'Valhal', NULL, 540, 30),
(301, 'GVMSBC450KG', 5, 'Valhal', NULL, 540, 30),
(302, 'GVMSBC640KHEL', 5, 'Valhal', NULL, 540, 30),
(303, 'GVMSBC675KG', 5, 'Valhal', NULL, 540, 30),
(304, 'GVMSBCLB675KG', 5, 'Valhal', NULL, 540, 30),
(305, 'GVMTF225KGF', 5, 'Valhal', NULL, 540, 30),
(306, '0M-3400230000AA', 1, 'Galaxy 5000 - Sierra', NULL, 540, 30),
(307, '0M-3400230100AA', 1, 'Galaxy 5000 - Sierra', NULL, 540, 30),
(308, '0M-G55TXOPT004', 1, 'Galaxy 5500', NULL, 540, 30),
(309, '0M-G5TGOPT004', 1, 'Galaxy 5500', NULL, 540, 30),
(310, '0M-G5TGOPT005', 1, 'Galaxy 5500', NULL, 540, 30),
(311, '0M-G5TGOPT006', 1, 'Galaxy 5500', NULL, 540, 30),
(312, 'G55TTC120RH', 1, 'Galaxy 5500', NULL, 540, 30),
(313, 'G55TTC40RH', 1, 'Galaxy 5500', NULL, 540, 30),
(314, 'G55TTC60RH', 1, 'Galaxy 5500', NULL, 540, 30),
(315, 'G55TUPSM100H', 1, 'Galaxy 5500', NULL, 540, 30),
(316, 'G55TUPSM100HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(317, 'G55TUPSM120H', 1, 'Galaxy 5500', NULL, 540, 30),
(318, 'G55TUPSM120H', 1, 'Galaxy 5500', NULL, 540, 30),
(319, 'G55TUPSM120HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(320, 'G55TUPSM20H', 1, 'Galaxy 5500', NULL, 540, 30),
(321, 'G55TUPSM20HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(322, 'G55TUPSM30H', 1, 'Galaxy 5500', NULL, 540, 30),
(323, 'G55TUPSM30HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(324, 'G55TUPSM40H', 1, 'Galaxy 5500', NULL, 540, 30),
(325, 'G55TUPSM40HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(326, 'G55TUPSM60H', 1, 'Galaxy 5500', NULL, 540, 30),
(327, 'G55TUPSM60HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(328, 'G55TUPSM80H', 1, 'Galaxy 5500', NULL, 540, 30),
(329, 'G55TUPSM80HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(330, 'G55TUPSU100H', 1, 'Galaxy 5500', NULL, 540, 30),
(331, 'G55TUPSU100HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(332, 'G55TUPSU120H', 1, 'Galaxy 5500', NULL, 540, 30),
(333, 'G55TUPSU120HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(334, 'G55TUPSU20H', 1, 'Galaxy 5500', NULL, 540, 30),
(335, 'G55TUPSU20HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(336, 'G55TUPSU30H', 1, 'Galaxy 5500', NULL, 540, 30),
(337, 'G55TUPSU30HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(338, 'G55TUPSU40H', 1, 'Galaxy 5500', NULL, 540, 30),
(339, 'G55TUPSU40HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(340, 'G55TUPSU60H', 1, 'Galaxy 5500 - Sierra', NULL, 540, 30),
(341, 'G55TUPSU60HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(342, 'G55TUPSU80H', 1, 'Galaxy 5500', NULL, 540, 30),
(343, 'G55TUPSU80HIN', 1, 'Galaxy 5500', NULL, 540, 30),
(344, 'G5K9635CH', 1, 'Galaxy 5500', NULL, 540, 30),
(345, 'G5KETO', 1, 'Galaxy 5500', NULL, 540, 30),
(346, 'G55TUPM100HM', 1, 'Galaxy 5500 Marine', NULL, 540, 30),
(347, 'G55TUPM100HX', 1, 'Galaxy 5500', NULL, 540, 30),
(348, 'G55TUPM120HM', 1, 'Galaxy 5500', NULL, 540, 30),
(349, 'G55TUPM120HX', 1, 'Galaxy 5500', NULL, 540, 30),
(350, 'G55TUPM20HM', 1, 'Galaxy 5500', NULL, 540, 30),
(351, 'G55TUPM20HX', 1, 'Galaxy 5500', NULL, 540, 30),
(352, 'G55TUPM30HM', 1, 'Galaxy 5500', NULL, 540, 30),
(353, 'G55TUPM30HX', 1, 'Galaxy 5500', NULL, 540, 30),
(354, 'G55TUPM40HM', 1, 'Galaxy 5500', NULL, 540, 30),
(355, 'G55TUPM40HX', 1, 'Galaxy 5500', NULL, 540, 30),
(356, 'G55TUPM60HM', 1, 'Galaxy 5500', NULL, 540, 30),
(357, 'G55TUPM60HX', 1, 'Galaxy 5500', NULL, 540, 30),
(358, 'G55TUPM80HM', 1, 'Galaxy 5500', NULL, 540, 30),
(359, 'G55TUPM80HX', 1, 'Galaxy 5500', NULL, 540, 30),
(360, 'G55TUPU100HM', 1, 'Galaxy 5500', NULL, 540, 30),
(361, 'G55TUPU100HX', 1, 'Galaxy 5500', NULL, 540, 30),
(362, 'G55TUPU120HM', 1, 'Galaxy 5500', NULL, 540, 30),
(363, 'G55TUPU120HX', 1, 'Galaxy 5500', NULL, 540, 30),
(364, 'G55TUPU20HM', 1, 'Galaxy 5500', NULL, 540, 30),
(365, 'G55TUPU20HX', 1, 'Galaxy 5500', NULL, 540, 30),
(366, 'G55TUPU30HM', 1, 'Galaxy 5500', NULL, 540, 30),
(367, 'G55TUPU30HX', 1, 'Galaxy 5500', NULL, 540, 30),
(368, 'G55TUPU40HM', 1, 'Galaxy 5500', NULL, 540, 30),
(369, 'G55TUPU40HX', 1, 'Galaxy 5500', NULL, 540, 30),
(370, 'G55TUPU60HM', 1, 'Galaxy 5500', NULL, 540, 30),
(371, 'G55TUPU60HX', 1, 'Galaxy 5500', NULL, 540, 30),
(372, 'G55TUPU80HM', 1, 'Galaxy 5500', NULL, 540, 30),
(373, 'G55TUPU80HX', 1, 'Galaxy 5500', NULL, 540, 30),
(374, '0M-3460078300', 1, 'Galaxy 5500', NULL, 540, 30),
(375, '0M-3460078400', 1, 'Galaxy 5500', NULL, 540, 30),
(376, '0M-3460085600', 1, 'Galaxy 5500', NULL, 540, 30),
(377, '0M-3460085700', 1, 'Galaxy 5500', NULL, 540, 30),
(378, '0M-3460085900', 1, 'Galaxy 5500', NULL, 540, 30),
(379, '0M-3460086100', 1, 'Galaxy 5500', NULL, 540, 30),
(380, '0M-G7TAB1', 1, 'Galaxy 5500', NULL, 540, 30),
(381, '0M-G7TD160B9', 1, 'Galaxy 5500', NULL, 540, 30),
(382, '0M-G7TD160K9', 1, 'Galaxy 5500', NULL, 540, 30),
(383, '0M-G7TD200B9', 1, 'Galaxy 5500', NULL, 540, 30),
(384, '0M-G7TD200K9', 1, 'Galaxy 5500', NULL, 540, 30),
(385, '0M-G7TD250B9', 1, 'Galaxy 5500', NULL, 540, 30),
(386, '0M-G7TD300B5', 1, 'Galaxy 5500', NULL, 540, 30),
(387, '0M-G7TD300B6', 1, 'Galaxy 5500', NULL, 540, 30),
(388, '0M-G7TD400B3', 1, 'Galaxy 5500', NULL, 540, 30),
(389, '0M-G7TD400B4', 1, 'Galaxy 5500', NULL, 540, 30),
(390, '0M-G7TD400K3', 1, 'Galaxy 5500', NULL, 540, 30),
(391, '0M-G7TD500B9', 1, 'Galaxy 5500', NULL, 540, 30),
(392, '0M-G7TGOPT001', 1, 'Galaxy 5500', NULL, 540, 30),
(393, '0M-G7TGOPT004', 1, 'Galaxy 5500', NULL, 540, 30),
(394, '0M-G7TGOPT013', 1, 'Galaxy 5500', NULL, 540, 30),
(395, '0M-G7TGOPT014', 1, 'Galaxy 5500', NULL, 540, 30),
(396, '0M-G7TM250CS', 1, 'Galaxy 5500', NULL, 540, 30),
(397, '0M-G7TM300CS', 1, 'Galaxy 5500', NULL, 540, 30),
(398, '0M-G7TM400CS', 1, 'Galaxy 5500', NULL, 540, 30),
(399, '0M-G7TM500CS', 1, 'Galaxy 5500', NULL, 540, 30),
(400, '0M-G7TP300CS', 1, 'Galaxy 5500', NULL, 540, 30),
(401, '0M-G7TP400CS', 1, 'Galaxy 5500', NULL, 540, 30),
(402, '0M-G7TP400S', 1, 'Galaxy 5500', NULL, 540, 30),
(403, '0M-G7TP500CS', 1, 'Galaxy 5500', NULL, 540, 30),
(404, '0M-G7TS1200CS', 1, 'Galaxy 5500', NULL, 540, 30),
(405, '0M-G7TS2000CS', 1, 'Galaxy 5500', NULL, 540, 30),
(406, '0M-G7TS2000CSL', 1, 'Galaxy 5500', NULL, 540, 30),
(407, '0M-G7TS800CS', 1, 'Galaxy 5500', NULL, 540, 30),
(408, '0M-G7TS800S', 1, 'Galaxy 5500', NULL, 540, 30),
(409, '0M-G7TSOPT001', 1, 'Galaxy 5500', NULL, 540, 30),
(410, '0M-G7TSOPT005', 1, 'Galaxy 5500', NULL, 540, 30),
(411, '0M-G7TSOPT014', 1, 'Galaxy 5500', NULL, 540, 30),
(412, '0M-G7TX200SK', 1, 'Galaxy 5500', NULL, 540, 30),
(413, '0M-G7TXOPT001', 1, 'Galaxy 5500', NULL, 540, 30),
(414, '0M-G7TXOPT011', 1, 'Galaxy 5500', NULL, 540, 30),
(415, '0M-G7TXTC', 1, 'Galaxy 5500', NULL, 540, 30),
(416, '0M-TMRAB001', 1, 'Galaxy 5500', NULL, 540, 30),
(417, '0M-TMRAB002', 1, 'Galaxy 5500', NULL, 540, 30),
(418, '0M-TMRAL009', 1, 'Galaxy 5500', NULL, 540, 30),
(419, '0M-0M-TMRAL010', 1, 'Galaxy 5500', NULL, 540, 30),
(420, 'G7TQ500CS', 1, 'Galaxy 5500 shore ', NULL, 540, 30),
(421, 'G9K9635CH', 1, 'Galaxy 5500 shore ', NULL, 540, 30),
(422, 'G9K9635CHSSC', 1, 'Galaxy 5500 shore ', NULL, 540, 30),
(423, 'G9KEPS9635CH', 1, 'Galaxy 5500 shore ', NULL, 540, 30),
(424, '0G-0168', 1, 'PMM ', NULL, 540, 30),
(425, '0G-0169', 1, 'PMM ', NULL, 540, 30),
(426, '0G-PMMOPT235', 1, 'PMM ', NULL, 540, 30),
(427, '0G-PMMOPT241', 1, 'PMM', NULL, 540, 30),
(428, '0G-PMMOPT243', 1, 'PMM', NULL, 540, 30),
(429, '0G-PMMOPT249', 1, 'PMM', NULL, 540, 30),
(430, '0G-PMMOPT253', 1, 'PMM', NULL, 540, 30),
(431, '0G-PMMX30120A', 1, 'PMM', NULL, 540, 30),
(432, '0G-PMMX50230A', 1, 'PMM', NULL, 540, 30),
(433, '0G-PMMX75450A', 1, 'PMM', NULL, 540, 30),
(434, 'PMMETO', 1, 'PMM', NULL, 540, 30),
(435, '0M-STSU100HY3', 1, 'STS APJ', NULL, 540, 30),
(436, '0M-STSU100HY4', 1, 'STS APJ', NULL, 540, 30),
(437, '0M-STSU100HZ3', 1, 'STS APJ', NULL, 540, 30),
(438, '0M-STSU100HZ4', 1, 'STS APJ', NULL, 540, 30),
(439, '0M-STSU100HZ5', 1, 'STS APJ', NULL, 540, 30),
(440, '0M-STSU160HX4', 1, 'STS APJ', NULL, 540, 30),
(441, '0M-STSU160HY3', 1, 'STS APJ', NULL, 540, 30),
(442, '0M-STSU160HY4', 1, 'STS APJ', NULL, 540, 30),
(443, '0M-STSU160HZ3', 1, 'STS APJ', NULL, 540, 30),
(444, '0M-STSU160HZ4', 1, 'STS APJ', NULL, 540, 30),
(445, '0M-STSU250HX4', 1, 'STS APJ', NULL, 540, 30),
(446, '0M-STSU250HY3', 1, 'STS APJ', NULL, 540, 30),
(447, '0M-STSU250HY4', 1, 'STS APJ', NULL, 540, 30),
(448, '0M-STSU250HZ3', 1, 'STS APJ', NULL, 540, 30),
(449, '0M-STSU250HZ4', 1, 'STS APJ', NULL, 540, 30),
(450, '0M-STSU30HY3', 1, 'STS APJ', NULL, 540, 30),
(451, '0M-STSU30HY4', 1, 'STS APJ', NULL, 540, 30),
(452, '0M-STSU30HZ3', 1, 'STS APJ', NULL, 540, 30),
(453, '0M-STSU30HZ4', 1, 'STS APJ', NULL, 540, 30),
(454, '0M-STSU400HZ3', 1, 'STS APJ', NULL, 540, 30),
(455, '0M-STSU400QX4', 1, 'STS APJ', NULL, 540, 30),
(456, '0M-STSU400QZ4', 1, 'STS APJ', NULL, 540, 30),
(457, '0M-STSU400RZ4', 1, 'STS APJ', NULL, 540, 30),
(458, '0M-STSU60HY3', 1, 'STS APJ', NULL, 540, 30),
(459, '0M-STSU60HY4', 1, 'STS APJ', NULL, 540, 30),
(460, '0M-STSU60HZ3', 1, 'STS APJ', NULL, 540, 30),
(461, '0M-STSU60HZ4', 1, 'STS APJ', NULL, 540, 30),
(462, '0M-STSU630HZ3', 1, 'STS APJ', NULL, 540, 30),
(463, '0M-STSU630QX4', 1, 'STS APJ', NULL, 540, 30),
(464, '0M-STSU630QZ4', 1, 'STS APJ', NULL, 540, 30),
(465, '0M-STSU630RX4', 1, 'STS APJ', NULL, 540, 30),
(466, '0M-STSU630RZ4', 1, 'STS APJ', NULL, 540, 30);

-- --------------------------------------------------------

--
-- Table structure for table `productrating`
--

CREATE TABLE `productrating` (
  `ProductRatingID` int(11) NOT NULL,
  `Rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productrating`
--

INSERT INTO `productrating` (`ProductRatingID`, `Rating`) VALUES
(1, 50),
(2, 80),
(3, 100),
(4, 110),
(5, 120),
(6, 180),
(7, 200),
(8, 250),
(9, 500),
(10, 1000),
(11, 1500),
(12, 2000);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `SerialNumber` varchar(50) NOT NULL,
  `ModelID` varchar(50) NOT NULL,
  `idfID` int(11) NOT NULL,
  `SourceDT` datetime DEFAULT NULL,
  `EndDT` datetime DEFAULT NULL,
  `TestStart` datetime DEFAULT NULL,
  `TestEnd` datetime DEFAULT NULL,
  `BayName` varchar(50) DEFAULT NULL,
  `TestFailCount` int(11) DEFAULT '0',
  `TestCompleted` int(11) DEFAULT '0',
  `FailReasons` varchar(1000) DEFAULT '',
  `Downtime` int(11) NOT NULL DEFAULT '0',
  `Tester` varchar(1000) DEFAULT '',
  `CurStatus` varchar(100) DEFAULT NULL,
  `StandbyRemarks` text DEFAULT NULL,
  `UsageTime` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `producttimestamp`
--

CREATE TABLE `producttimestamp` (
  `ProductTimeStampID` int(11) NOT NULL,
  `SerialNumber` varchar(50) DEFAULT NULL,
  `Src_SendProductionDT` datetime NOT NULL,
  `Src_SendProductionOpID` varchar(50) DEFAULT NULL,
  `Src_RxdLogisticDT` datetime NOT NULL,
  `Src_RxdLogisticOpID` varchar(50) DEFAULT NULL,
  `Src_SendLogisticDT` datetime NOT NULL,
  `Src_SendLogisticOpID` varchar(50) DEFAULT NULL,
  `Idf4In_RxdLogisticDT` datetime NOT NULL,
  `Idf4In_RxdLogisticOpID` varchar(50) DEFAULT NULL,
  `Idf4In_SendLogisticDT` datetime NOT NULL,
  `Idf4In_SendLogisticOpID` varchar(50) DEFAULT NULL,
  `Idf4In_RxdProductionDT` datetime NOT NULL,
  `Idf4In_RxdProductionOpID` varchar(50) DEFAULT NULL,
  `Idf4In_SendProductionDT` datetime NOT NULL,
  `Idf4In_SendProductionOpID` varchar(50) DEFAULT NULL,
  `BayIn_TestInfraDT` datetime NOT NULL,
  `BayIn_TestInfraOpID` varchar(50) DEFAULT NULL,
  `BayOut_TestInfraDT` datetime NOT NULL,
  `BayOut_TestInfraOpID` varchar(50) DEFAULT NULL,
  `Idf4Out_RxdProductionDT` datetime NOT NULL,
  `Idf4Out_RxdProductionOpID` varchar(50) DEFAULT NULL,
  `Idf4Out_SendProductionDT` datetime NOT NULL,
  `Idf4Out_SendProductionOpID` varchar(50) DEFAULT NULL,
  `Idf4Out_RxdLogisticDT` datetime NOT NULL,
  `Idf4Out_RxdLogisticOpID` varchar(50) DEFAULT NULL,
  `Idf4Out_SendLogisticDT` datetime NOT NULL,
  `Idf4Out_SendLogisticOpID` varchar(50) DEFAULT NULL,
  `ProductStage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `testeractivity`
--

CREATE TABLE `testeractivity` (
  `ID` int(11) NOT NULL,
  `TesterUsername` varchar(250) NOT NULL,
  `TesterFullname` varchar(250) NOT NULL,
  `BayID` int(11) DEFAULT NULL,
  `BayName` varchar(100) DEFAULT NULL,
  `SerialNumber` varchar(100) DEFAULT NULL,
  `ModelID` varchar(100) DEFAULT NULL,
  `TestStartDT` datetime DEFAULT NULL,
  `TestEndDT` datetime DEFAULT NULL,
  `TestStage` varchar(100) DEFAULT NULL,
  `FailCount` int(11) NOT NULL DEFAULT '0',
  `TotalTestCount` int(11) NOT NULL DEFAULT '0',
  `TotalFailCount` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `OperatorID` varchar(100) DEFAULT NULL,
  `Username` varchar(50) CHARACTER SET utf8 NOT NULL,
  `Fullname` varchar(250) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Lastlogin` datetime DEFAULT NULL,
  `Role` enum('production','logistic','baytester') NOT NULL,
  `idfID` int(11) NOT NULL,
  `UserOnline` enum('online','offline') NOT NULL DEFAULT 'offline',
  `Password` varchar(200) NOT NULL,
  `SecuKy` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `bays`
--
ALTER TABLE `bays`
  ADD PRIMARY KEY (`BayID`);

--
-- Indexes for table `bayutil`
--
ALTER TABLE `bayutil`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `idf`
--
ALTER TABLE `idf`
  ADD PRIMARY KEY (`idfID`);

--
-- Indexes for table `ppi`
--
ALTER TABLE `ppi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productcycles`
--
ALTER TABLE `productcycles`
  ADD PRIMARY KEY (`ProductCycleID`);

--
-- Indexes for table `productrating`
--
ALTER TABLE `productrating`
  ADD PRIMARY KEY (`ProductRatingID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `producttimestamp`
--
ALTER TABLE `producttimestamp`
  ADD PRIMARY KEY (`ProductTimeStampID`);

--
-- Indexes for table `testeractivity`
--
ALTER TABLE `testeractivity`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `bays`
--
ALTER TABLE `bays`
  MODIFY `BayID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `bayutil`
--
ALTER TABLE `bayutil`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `idf`
--
ALTER TABLE `idf`
  MODIFY `idfID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `ppi`
--
ALTER TABLE `ppi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `productcycles`
--
ALTER TABLE `productcycles`
  MODIFY `ProductCycleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=467;
--
-- AUTO_INCREMENT for table `productrating`
--
ALTER TABLE `productrating`
  MODIFY `ProductRatingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `producttimestamp`
--
ALTER TABLE `producttimestamp`
  MODIFY `ProductTimeStampID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `testeractivity`
--
ALTER TABLE `testeractivity`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
