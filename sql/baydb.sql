-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2016 at 07:53 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `baydb`
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

-- --------------------------------------------------------

--
-- Table structure for table `productrating`
--

CREATE TABLE `productrating` (
  `ProductRatingID` int(11) NOT NULL,
  `Rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `UsageTime` int(11) NOT NULL DEFAULT '0',
  `Downtime` int(11) NOT NULL DEFAULT '0',
  `Tester` varchar(1000) NOT NULL,
  `CurStatus` varchar(100) DEFAULT NULL,
  `StandbyRemarks` text NOT NULL,
  `TestType` enum('burnin','final') NOT NULL DEFAULT 'burnin',
  `TestStartFinal` datetime DEFAULT NULL,
  `TestEndFinal` datetime DEFAULT NULL,
  `TestCompletedFInal` int(11) NOT NULL DEFAULT '0'
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
  `Src_RxdPartTestDT` datetime NOT NULL,
  `Src_RxdPartTestOpID` varchar(50) DEFAULT NULL,
  `Src_SendPartTestDT` datetime NOT NULL,
  `Src_SendPartTestOpID` varchar(50) DEFAULT NULL,
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
  `ProductStage` float NOT NULL
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
  `TotalFailCount` int(11) NOT NULL DEFAULT '0',
  `TestType` enum('burnin','final') NOT NULL DEFAULT 'burnin',
  `PausedUsageTime` int(11) NOT NULL DEFAULT '0',
  `PausedDownTime` int(11) NOT NULL DEFAULT '0',
  `PausedStatus` varchar(100) DEFAULT NULL
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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
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
  MODIFY `ProductCycleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=471;
--
-- AUTO_INCREMENT for table `productrating`
--
ALTER TABLE `productrating`
  MODIFY `ProductRatingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `producttimestamp`
--
ALTER TABLE `producttimestamp`
  MODIFY `ProductTimeStampID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `testeractivity`
--
ALTER TABLE `testeractivity`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
