-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2025 at 10:40 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `software`
--

-- --------------------------------------------------------

--
-- Table structure for table `admintbl`
--

CREATE TABLE `admintbl` (
  `sno` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `col1` varchar(20) DEFAULT NULL,
  `col2` varchar(20) DEFAULT NULL,
  `col3` varchar(20) DEFAULT NULL,
  `col4` varchar(20) DEFAULT NULL,
  `col5` varchar(20) DEFAULT NULL,
  `col6` varchar(20) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admintbl`
--

INSERT INTO `admintbl` (`sno`, `name`, `pass`, `role`, `col1`, `col2`, `col3`, `col4`, `col5`, `col6`, `time`) VALUES
(1, 'admin', '123', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-10 09:42:35'),
(6, 'Ashakiran', '123', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-15 14:37:50'),
(7, 'user1', '9090', 'user', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-22 11:43:19');

-- --------------------------------------------------------

--
-- Table structure for table `tblcat`
--

CREATE TABLE `tblcat` (
  `sno` int(111) NOT NULL,
  `cat_name` varchar(50) NOT NULL,
  `des` varchar(100) DEFAULT NULL,
  `col1` varchar(20) DEFAULT NULL,
  `col2` varchar(20) DEFAULT NULL,
  `col3` varchar(20) DEFAULT NULL,
  `col4` varchar(20) DEFAULT NULL,
  `col5` varchar(20) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcat`
--

INSERT INTO `tblcat` (`sno`, `cat_name`, `des`, `col1`, `col2`, `col3`, `col4`, `col5`, `time`) VALUES
(1, 'Resistor', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-18 14:27:30');

-- --------------------------------------------------------

--
-- Table structure for table `tblinward`
--

CREATE TABLE `tblinward` (
  `sno` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'IN',
  `supplier` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `subtype` varchar(20) NOT NULL,
  `quantity` int(200) NOT NULL,
  `unit` varchar(100) NOT NULL,
  `billno` int(100) NOT NULL,
  `challen` varchar(20) NOT NULL,
  `checkedby` varchar(20) NOT NULL,
  `col2` varchar(20) DEFAULT NULL,
  `col3` varchar(20) DEFAULT NULL,
  `col4` varchar(20) DEFAULT NULL,
  `col5` varchar(20) DEFAULT NULL,
  `col6` varchar(20) DEFAULT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblinward`
--

INSERT INTO `tblinward` (`sno`, `status`, `supplier`, `category`, `type`, `subtype`, `quantity`, `unit`, `billno`, `challen`, `checkedby`, `col2`, `col3`, `col4`, `col5`, `col6`, `time`) VALUES
(1, 'IN', 'Genius Electronics	', 'Resistor', 'SMD 603 Package', '47K', 200, 'Nos.', 1001, '1001', 'Ashakiran', NULL, NULL, NULL, NULL, NULL, '2025-02-18 10:01:47');

-- --------------------------------------------------------

--
-- Table structure for table `tbloutward`
--

CREATE TABLE `tbloutward` (
  `sno` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'OUT',
  `vendor_name` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `subtype` varchar(50) NOT NULL,
  `available_quantity` int(200) NOT NULL,
  `quantity` varchar(50) NOT NULL,
  `unit` varchar(100) NOT NULL,
  `billno` varchar(50) NOT NULL,
  `challen` varchar(50) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `des` varchar(50) NOT NULL,
  `issuedby` varchar(50) NOT NULL,
  `col2` varchar(20) DEFAULT NULL,
  `col3` varchar(20) DEFAULT NULL,
  `col4` varchar(20) DEFAULT NULL,
  `col5` varchar(20) DEFAULT NULL,
  `col6` varchar(20) DEFAULT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbloutward`
--

INSERT INTO `tbloutward` (`sno`, `status`, `vendor_name`, `category`, `type`, `subtype`, `available_quantity`, `quantity`, `unit`, `billno`, `challen`, `purpose`, `des`, `issuedby`, `col2`, `col3`, `col4`, `col5`, `col6`, `time`) VALUES
(1, 'OUT', 'DHAYARESHWAR ELECTRICAL & ENGINEERING ( All types ', 'Resistor', 'SMD 603 Package', '47K', 200, '22', 'Nos.', '2001', '2001', 'Sample', 'N/A', 'Ashakiran', NULL, NULL, NULL, NULL, NULL, '2025-02-18 10:02:20');

-- --------------------------------------------------------

--
-- Table structure for table `tblreq`
--

CREATE TABLE `tblreq` (
  `sno` int(11) NOT NULL,
  `req_name` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `subtype` varchar(50) NOT NULL,
  `available_quantity` int(200) NOT NULL,
  `quantity` varchar(50) NOT NULL,
  `unit` varchar(100) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `des` varchar(50) NOT NULL,
  `issuedby` varchar(50) NOT NULL,
  `col2` varchar(20) DEFAULT NULL,
  `col3` varchar(20) DEFAULT NULL,
  `col4` varchar(20) DEFAULT NULL,
  `col5` varchar(20) DEFAULT NULL,
  `col6` varchar(20) DEFAULT NULL,
  `time` datetime NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblsubtype`
--

CREATE TABLE `tblsubtype` (
  `sno` int(111) NOT NULL,
  `cat` varchar(50) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `subtype_name` varchar(50) NOT NULL,
  `min` int(50) DEFAULT NULL,
  `rac` varchar(50) DEFAULT NULL,
  `avail_quant` int(50) DEFAULT NULL,
  `col2` varchar(20) DEFAULT NULL,
  `col3` varchar(20) DEFAULT NULL,
  `col4` varchar(20) DEFAULT NULL,
  `col5` varchar(20) DEFAULT NULL,
  `col6` varchar(20) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsubtype`
--

INSERT INTO `tblsubtype` (`sno`, `cat`, `type`, `subtype_name`, `min`, `rac`, `avail_quant`, `col2`, `col3`, `col4`, `col5`, `col6`, `time`) VALUES
(1, 'Resistor', 'SMD 603 Package', '47K', 500, '1', 178, NULL, NULL, NULL, NULL, NULL, '2025-02-18 14:28:37');

-- --------------------------------------------------------

--
-- Table structure for table `tblsupplier`
--

CREATE TABLE `tblsupplier` (
  `sno` int(111) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `gst` varchar(50) NOT NULL DEFAULT 'none',
  `col1` varchar(20) DEFAULT NULL,
  `col2` varchar(20) DEFAULT NULL,
  `col3` varchar(20) DEFAULT NULL,
  `col4` varchar(20) DEFAULT NULL,
  `col5` varchar(20) DEFAULT NULL,
  `col6` varchar(20) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsupplier`
--

INSERT INTO `tblsupplier` (`sno`, `name`, `address`, `contact`, `email`, `gst`, `col1`, `col2`, `col3`, `col4`, `col5`, `col6`, `time`) VALUES
(1, 'Genius Electronics	', 'S. No. 50/10/5,Narhe Village, Royal Plaza, First Floor, Office No 2, Near Deshpande Garden, Vadgaon ', 'N/A', 'geniuspune46@gmail.com', 'N/A', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-18 14:30:39');

-- --------------------------------------------------------

--
-- Table structure for table `tbltype`
--

CREATE TABLE `tbltype` (
  `sno` int(111) NOT NULL,
  `cat` varchar(50) DEFAULT NULL,
  `type_name` varchar(50) NOT NULL,
  `col1` varchar(20) DEFAULT NULL,
  `col2` varchar(20) DEFAULT NULL,
  `col3` varchar(20) DEFAULT NULL,
  `col4` varchar(20) DEFAULT NULL,
  `col5` varchar(20) DEFAULT NULL,
  `col6` varchar(20) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbltype`
--

INSERT INTO `tbltype` (`sno`, `cat`, `type_name`, `col1`, `col2`, `col3`, `col4`, `col5`, `col6`, `time`) VALUES
(1, 'Resistor', 'SMD 603 Package', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-18 14:27:58');

-- --------------------------------------------------------

--
-- Table structure for table `tblvendor`
--

CREATE TABLE `tblvendor` (
  `sno` int(111) NOT NULL,
  `ven_name` varchar(50) NOT NULL,
  `ven_address` varchar(100) NOT NULL,
  `ven_contact` varchar(50) NOT NULL,
  `ven_email` varchar(50) NOT NULL,
  `ven_gst` varchar(50) NOT NULL DEFAULT 'none',
  `col1` varchar(20) DEFAULT NULL,
  `col2` varchar(20) DEFAULT NULL,
  `col3` varchar(20) DEFAULT NULL,
  `col4` varchar(20) DEFAULT NULL,
  `col5` varchar(20) DEFAULT NULL,
  `col6` varchar(20) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblvendor`
--

INSERT INTO `tblvendor` (`sno`, `ven_name`, `ven_address`, `ven_contact`, `ven_email`, `ven_gst`, `col1`, `col2`, `col3`, `col4`, `col5`, `col6`, `time`) VALUES
(1, 'DHAYARESHWAR ELECTRICAL & ENGINEERING ( All types ', 'Dhayarigaon Near Dhayaar Mandir Road, Pune 411041', 'N/A', 'dhayareshwarelec.eng@gmail.com', 'N/A', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-18 14:31:41');

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `sno` int(111) NOT NULL,
  `unit_name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `col1` varchar(20) DEFAULT NULL,
  `col2` varchar(20) DEFAULT NULL,
  `col3` varchar(20) DEFAULT NULL,
  `col4` varchar(20) DEFAULT NULL,
  `col5` varchar(20) DEFAULT NULL,
  `col6` varchar(20) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`sno`, `unit_name`, `type`, `col1`, `col2`, `col3`, `col4`, `col5`, `col6`, `time`) VALUES
(1, 'Nos.', 'Nos.', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-18 14:29:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admintbl`
--
ALTER TABLE `admintbl`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `tblcat`
--
ALTER TABLE `tblcat`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `tblinward`
--
ALTER TABLE `tblinward`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `tbloutward`
--
ALTER TABLE `tbloutward`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `tblreq`
--
ALTER TABLE `tblreq`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `tblsubtype`
--
ALTER TABLE `tblsubtype`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `tblsupplier`
--
ALTER TABLE `tblsupplier`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `tbltype`
--
ALTER TABLE `tbltype`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `tblvendor`
--
ALTER TABLE `tblvendor`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`sno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admintbl`
--
ALTER TABLE `admintbl`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblcat`
--
ALTER TABLE `tblcat`
  MODIFY `sno` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblinward`
--
ALTER TABLE `tblinward`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbloutward`
--
ALTER TABLE `tbloutward`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblreq`
--
ALTER TABLE `tblreq`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblsubtype`
--
ALTER TABLE `tblsubtype`
  MODIFY `sno` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblsupplier`
--
ALTER TABLE `tblsupplier`
  MODIFY `sno` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbltype`
--
ALTER TABLE `tbltype`
  MODIFY `sno` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblvendor`
--
ALTER TABLE `tblvendor`
  MODIFY `sno` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `sno` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
