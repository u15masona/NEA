-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 13, 2021 at 12:26 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Fishy`
--

-- --------------------------------------------------------

--
-- Table structure for table `EmployeeType`
--

CREATE TABLE `EmployeeType` (
  `EmployeeTypeID` int(11) NOT NULL,
  `EmployeeType` tinytext NOT NULL,
  `Age` int(11) NOT NULL,
  `Wage` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `EmployeeType`
--

INSERT INTO `EmployeeType` (`EmployeeTypeID`, `EmployeeType`, `Age`, `Wage`) VALUES
(1, 'Owner', 0, '0.00'),
(2, 'Manager', 21, '13.00'),
(3, 'Lead Chef', 25, '9.20'),
(4, 'Cook', 16, '5.00'),
(5, 'Cook', 17, '5.50'),
(6, 'Cook', 18, '6.60'),
(7, 'Cook', 19, '7.30'),
(8, 'Cook', 21, '9.00'),
(9, 'Cook', 20, '8.50'),
(10, 'Kitchen Porter', 15, '4.40'),
(11, 'Kitchen Porter', 16, '4.45'),
(12, 'Kitchen Porter', 17, '5.00'),
(13, 'Kitchen Porter', 18, '6.61'),
(14, 'Kitchen Porter', 19, '6.61'),
(15, 'Kitchen Porter\r\n', 20, '7.00'),
(16, 'Kitchen Porter', 21, '9.00'),
(17, 'Front of House Team Member', 16, '5.50'),
(18, 'Front of House Team Member', 17, '5.50'),
(19, 'Front of House Team Member', 18, '6.20'),
(20, 'Front of House Team Member', 19, '6.20'),
(21, 'Front of House Team Member', 20, '7.00'),
(22, 'Front of House Team Member', 21, '9.00'),
(23, 'Team Leader', 21, '10.00');

-- --------------------------------------------------------

--
-- Table structure for table `Locations`
--

CREATE TABLE `Locations` (
  `LocationID` int(11) NOT NULL,
  `ManagerID` int(11) NOT NULL,
  `AddressLine1` text NOT NULL,
  `AddressLine2` text DEFAULT NULL,
  `Postcode` text NOT NULL,
  `Town` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Locations`
--

INSERT INTO `Locations` (`LocationID`, `ManagerID`, `AddressLine1`, `AddressLine2`, `Postcode`, `Town`) VALUES
(1, 2, '26 Fishy Street', '', 'TQ14PR', 'Torquay'),
(2, 3, '40 Chip View', NULL, 'TQ26HX', 'Torquay'),
(3, 4, '1 Trout Street', NULL, 'TQ50LN', 'Paignton'),
(4, 1004012004, '14 Vicarage Road', 'Chelston', 'TQ26HX', 'Torquay');

-- --------------------------------------------------------

--
-- Table structure for table `Rota`
--

CREATE TABLE `Rota` (
  `StaffID` int(11) NOT NULL,
  `ShiftDate` varchar(45) NOT NULL,
  `LocationID` int(11) NOT NULL,
  `StartTime` tinytext NOT NULL,
  `EndTime` tinytext NOT NULL,
  `ClockedIn` tinytext NOT NULL,
  `ClockedOut` tinytext NOT NULL,
  `Earning` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Rota`
--

INSERT INTO `Rota` (`StaffID`, `ShiftDate`, `LocationID`, `StartTime`, `EndTime`, `ClockedIn`, `ClockedOut`, `Earning`) VALUES
(1, '06/10/2021', 1, '08:00', '18:00', '-', '-', '0.00'),
(1, '27/09/2021', 1, '15:00', '23:00', '-', '-', '0.00'),
(2, '27/09/2021', 1, '08:00', '18:00', '-', '-', '0.00'),
(6, '06/10/2021', 1, '10:00', '18:00', '-', '-', '0.00'),
(6, '27/09/2021', 1, '17:00', '22:00', '-', '-', '0.00'),
(10, '06/10/2021', 1, '10:00', '18:00', '-', '-', '0.00'),
(10, '08/12/2021', 1, '22:15', '23:59', '22:18', '22:20', '0.18'),
(10, '1/12/2021', 1, '09:00', '18:00', '-', '-', '0.00'),
(10, '27/09/2021', 1, '18:00', '22:00', '-', '-', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `Staff`
--

CREATE TABLE `Staff` (
  `StaffID` int(11) NOT NULL,
  `Forename` text NOT NULL,
  `Surname` text NOT NULL,
  `DoB` text NOT NULL,
  `AddressLine1` text NOT NULL,
  `AddressLine2` text DEFAULT NULL,
  `Town` text NOT NULL,
  `Postcode` text NOT NULL,
  `PhoneNumber` text NOT NULL,
  `BaseLocationID` int(11) NOT NULL,
  `Wage` decimal(6,2) NOT NULL,
  `EmployeeType` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Staff`
--

INSERT INTO `Staff` (`StaffID`, `Forename`, `Surname`, `DoB`, `AddressLine1`, `AddressLine2`, `Town`, `Postcode`, `PhoneNumber`, `BaseLocationID`, `Wage`, `EmployeeType`) VALUES
(1, 'Dale', 'Heffersworth', '12/01/1978', '14 Vicarage Road', 'Chelston', 'Torquay', 'TQ26HX', '01803392298', 1, '8.80', 'Owner'),
(2, 'Katy', 'Hudson', '24/02/2005', '92 Spinner Street', 'Hellston', 'Brixham', 'TQ67HX', '01803 348293', 1, '8.80', 'Manager'),
(3, 'James', 'Dickinson', '24/10/2005', '1 Winner Lane', '-', 'Torquay', 'TQ14PR', '01803 483384', 2, '8.80', 'Manager'),
(4, 'Adele', 'Adkins', '19/12/2002', '21 Chelston Road', 'Chelston', 'Torquay', 'TQ12WX', '01803 482394', 3, '8.80', 'Manager'),
(6, 'Lauren', 'Higgins', '10/07/2004', 'Maidenway Road', NULL, 'Paignton', 'TQ26HX', '0180339228', 1, '5.50', 'Front of House Team Member'),
(10, 'Archie', 'Mason', '04/01/2004', '14 Vicarage Road', 'Chelston', 'Torquay', 'TQ26HX', '01803392298', 1, '5.50', 'Front of House Team Member'),
(11, 'Abigail', 'Carthy', '29/08/2004', '-', '-', 'Paignton', 'TQ32ST', '-', 1, '0.00', 'Front of House Team Member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `EmployeeType`
--
ALTER TABLE `EmployeeType`
  ADD PRIMARY KEY (`EmployeeTypeID`);

--
-- Indexes for table `Locations`
--
ALTER TABLE `Locations`
  ADD PRIMARY KEY (`LocationID`);

--
-- Indexes for table `Rota`
--
ALTER TABLE `Rota`
  ADD PRIMARY KEY (`StaffID`,`ShiftDate`);

--
-- Indexes for table `Staff`
--
ALTER TABLE `Staff`
  ADD PRIMARY KEY (`StaffID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `EmployeeType`
--
ALTER TABLE `EmployeeType`
  MODIFY `EmployeeTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `Locations`
--
ALTER TABLE `Locations`
  MODIFY `LocationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Staff`
--
ALTER TABLE `Staff`
  MODIFY `StaffID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004012006;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
