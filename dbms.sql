-- Airline Management System Database Schema
-- Improved with proper foreign keys, constraints, and security
-- Version: 2.0 (Fixed and Enhanced)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbms`
--

-- --------------------------------------------------------

--
-- Table structure for table `airline`
--

CREATE TABLE `airline` (
  `AIRLINE_ID` varchar(10) NOT NULL,
  `AIRLINE_NAME` varchar(50) NOT NULL,
  `CREATED_AT` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`AIRLINE_ID`),
  UNIQUE KEY `AIRLINE_NAME` (`AIRLINE_NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `C_NAME` varchar(30) NOT NULL,
  `STATE` varchar(30) NOT NULL,
  `COUNTRY` varchar(30) NOT NULL,
  PRIMARY KEY (`C_NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `airport`
--

CREATE TABLE `airport` (
  `A_NAME` varchar(50) NOT NULL,
  `STATE` varchar(30) NOT NULL,
  `COUNTRY` varchar(30) NOT NULL,
  `C_NAME` varchar(30) NOT NULL,
  PRIMARY KEY (`A_NAME`),
  KEY `FK_CITY` (`C_NAME`),
  CONSTRAINT `FK_CITY` FOREIGN KEY (`C_NAME`) REFERENCES `city` (`C_NAME`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `flight`
--

CREATE TABLE `flight` (
  `FLIGHT_CODE` char(10) NOT NULL,
  `SOURCE` varchar(30) NOT NULL,
  `DESTINATION` varchar(30) NOT NULL,
  `DEPARTURE` varchar(5) NOT NULL,
  `ARRIVAL` varchar(5) NOT NULL,
  `DURATION` varchar(5) NOT NULL,
  `AIRLINE_ID` varchar(10) NOT NULL,
  `PRICE_BUSINESS` decimal(10,2) NOT NULL DEFAULT 0.00,
  `PRICE_ECONOMY` decimal(10,2) NOT NULL DEFAULT 0.00,
  `PRICE_STUDENTS` decimal(10,2) NOT NULL DEFAULT 0.00,
  `PRICE_DIFFERENTLYABLED` decimal(10,2) NOT NULL DEFAULT 0.00,
  `DATE` date NOT NULL,
  `CREATED_AT` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`FLIGHT_CODE`),
  KEY `FK_AIRLINE` (`AIRLINE_ID`),
  CONSTRAINT `FK_AIRLINE` FOREIGN KEY (`AIRLINE_ID`) REFERENCES `airline` (`AIRLINE_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `passenger`
--

CREATE TABLE `passenger` (
  `PASSPORT_NO` char(8) NOT NULL,
  `FNAME` varchar(50) NOT NULL,
  `MNAME` varchar(50) DEFAULT NULL,
  `LNAME` varchar(50) NOT NULL,
  `AGE` int(3) NOT NULL,
  `SEX` char(1) NOT NULL,
  `PHONE` char(10) NOT NULL,
  `ADDRESS` varchar(100) DEFAULT NULL,
  `CREATED_AT` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PASSPORT_NO`),
  CONSTRAINT `CHK_AGE` CHECK (`AGE` >= 0 AND `AGE` <= 120),
  CONSTRAINT `CHK_SEX` CHECK (`SEX` IN ('M','F','O'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `TICKET_NO` int(11) NOT NULL AUTO_INCREMENT,
  `PRICE` decimal(10,2) NOT NULL,
  `SOURCE` varchar(30) NOT NULL,
  `DESTINATION` varchar(30) NOT NULL,
  `DATE_OF_TRAVEL` date NOT NULL,
  `PASSPORT_NO` char(8) NOT NULL,
  `FLIGHT_CODE` char(10) NOT NULL,
  `TYPE` varchar(30) NOT NULL,
  `BOOKING_STATUS` enum('CONFIRMED','CANCELLED') DEFAULT 'CONFIRMED',
  `CREATED_AT` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`TICKET_NO`),
  KEY `FK_PASSENGER` (`PASSPORT_NO`),
  KEY `FK_FLIGHT` (`FLIGHT_CODE`),
  CONSTRAINT `FK_PASSENGER` FOREIGN KEY (`PASSPORT_NO`) REFERENCES `passenger` (`PASSPORT_NO`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_FLIGHT` FOREIGN KEY (`FLIGHT_CODE`) REFERENCES `flight` (`FLIGHT_CODE`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ADMIN_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `CREATED_AT` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ADMIN_ID`),
  UNIQUE KEY `USERNAME` (`USERNAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Insert default admin (username: admin, password: admin123 - hashed)
-- CRITICAL: After importing this database, visit update_admin_password.php in your browser
-- This will generate the correct bcrypt hash for 'admin123' and update the database
-- Default credentials: admin / admin123
-- Note: In production, change this password immediately
--
INSERT INTO `admin` (`USERNAME`, `PASSWORD`, `EMAIL`) VALUES
('admin', '$2y$10$TEMPORARY_RUN_UPDATE_PASSWORD_SCRIPT', 'admin@airline.com');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
