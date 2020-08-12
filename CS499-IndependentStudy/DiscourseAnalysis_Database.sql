-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2014 at 03:10 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `discourseanalysis`
--
CREATE DATABASE IF NOT EXISTS `discourseanalysis` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `discourseanalysis`;

-- --------------------------------------------------------

--
-- Table structure for table `conjunctions`
--

DROP TABLE IF EXISTS `conjunctions`;
CREATE TABLE IF NOT EXISTS `conjunctions` (
  `listName` varchar(50) NOT NULL,
  `conjunction` varchar(50) NOT NULL,
  PRIMARY KEY (`listName`,`conjunction`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conjunctions`
--

INSERT INTO `conjunctions` (`listName`, `conjunction`) VALUES
('Default Conjunction List', 'ACCORDINGLY'),
('Default Conjunction List', 'ALSO'),
('Default Conjunction List', 'ALTHOUGH'),
('Default Conjunction List', 'AND'),
('Default Conjunction List', 'AS'),
('Default Conjunction List', 'AS A RESULT'),
('Default Conjunction List', 'AS LONG AS'),
('Default Conjunction List', 'AS SOON AS'),
('Default Conjunction List', 'AT THE SAME TIME AS'),
('Default Conjunction List', 'BECAUSE'),
('Default Conjunction List', 'BUT'),
('Default Conjunction List', 'BY THAT MEANS'),
('Default Conjunction List', 'BY THIS MEANS'),
('Default Conjunction List', 'CONSEQUENTLY'),
('Default Conjunction List', 'CONVERSELY'),
('Default Conjunction List', 'EITHER'),
('Default Conjunction List', 'ELSE'),
('Default Conjunction List', 'EVEN IF'),
('Default Conjunction List', 'EVEN THOUGH'),
('Default Conjunction List', 'EXCEPT IF'),
('Default Conjunction List', 'EXCEPT THAT'),
('Default Conjunction List', 'FOR'),
('Default Conjunction List', 'FOR THE PURPOSE THAT'),
('Default Conjunction List', 'FOR THIS REASON'),
('Default Conjunction List', 'HENCE'),
('Default Conjunction List', 'IF'),
('Default Conjunction List', 'IN AS MUCH AS'),
('Default Conjunction List', 'IN ORDER THAT'),
('Default Conjunction List', 'IN THAT MANNER'),
('Default Conjunction List', 'IN THIS MANNER'),
('Default Conjunction List', 'INASMUCH AS'),
('Default Conjunction List', 'INDEED'),
('Default Conjunction List', 'INSTEAD'),
('Default Conjunction List', 'JUST AS'),
('Default Conjunction List', 'LEST'),
('Default Conjunction List', 'LIKEWISE'),
('Default Conjunction List', 'MOREOVER'),
('Default Conjunction List', 'NEITHER'),
('Default Conjunction List', 'NEVERTHELESS'),
('Default Conjunction List', 'NOR'),
('Default Conjunction List', 'NOTWITHSTANDING'),
('Default Conjunction List', 'NOW'),
('Default Conjunction List', 'ON THE CONTRARY'),
('Default Conjunction List', 'ON THE OTHER HAND'),
('Default Conjunction List', 'ONLY'),
('Default Conjunction List', 'ONLY IF'),
('Default Conjunction List', 'OR'),
('Default Conjunction List', 'OR ELSE'),
('Default Conjunction List', 'OTHERWISE'),
('Default Conjunction List', 'SINCE'),
('Default Conjunction List', 'SO'),
('Default Conjunction List', 'SO THAT'),
('Default Conjunction List', 'STILL'),
('Default Conjunction List', 'SUCH THAT'),
('Default Conjunction List', 'THAN'),
('Default Conjunction List', 'THAT'),
('Default Conjunction List', 'THEN'),
('Default Conjunction List', 'THEREFORE'),
('Default Conjunction List', 'THOUGH'),
('Default Conjunction List', 'THUS'),
('Default Conjunction List', 'TILL'),
('Default Conjunction List', 'TO THE END THAT'),
('Default Conjunction List', 'UNLESS'),
('Default Conjunction List', 'UNTIL'),
('Default Conjunction List', 'WHEN'),
('Default Conjunction List', 'WHENEVER'),
('Default Conjunction List', 'WHERE'),
('Default Conjunction List', 'WHEREVER'),
('Default Conjunction List', 'WHETHER'),
('Default Conjunction List', 'WHILE'),
('Default Conjunction List', 'X'),
('Default Conjunction List', 'YET');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `owner` varchar(25) NOT NULL,
  `projectName` varchar(35) NOT NULL,
  `fileName` varchar(35) NOT NULL,
  `storedFileName` varchar(50) NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `lastUpdate` datetime NOT NULL,
  PRIMARY KEY (`owner`,`projectName`),
  KEY `fk_owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
CREATE TABLE IF NOT EXISTS `permission` (
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `username` varchar(25) NOT NULL,
  `fileName` varchar(50) NOT NULL,
  `edit` tinyint(1) NOT NULL,
  `read` tinyint(1) NOT NULL,
  `delete` tinyint(1) NOT NULL,
  `add` tinyint(1) NOT NULL,
  PRIMARY KEY (`username`,`fileName`),
  KEY `fk_filename` (`fileName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `username` varchar(25) NOT NULL,
  `startTime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `sessionID` int(15) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sessionID`),
  KEY `fk_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tempusersinfo`
--

DROP TABLE IF EXISTS `tempusersinfo`;
CREATE TABLE IF NOT EXISTS `tempusersinfo` (
  `confirm_code` varchar(65) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(75) NOT NULL,
  `name` varchar(60) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `usersinfo`
--

DROP TABLE IF EXISTS `usersinfo`;
CREATE TABLE IF NOT EXISTS `usersinfo` (
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(75) NOT NULL,
  `name` varchar(60) NOT NULL,
  `firstName` varchar(25) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `isApprovedUser` tinyint(1) NOT NULL,
  `isOnline` tinyint(1) NOT NULL,
  `lastLogin` datetime DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usersinfo`
--

INSERT INTO `usersinfo` (`username`, `password`, `email`, `name`, `firstName`, `admin`, `isApprovedUser`, `isOnline`, `lastLogin`) VALUES
('admin', '$2y$10$/gMa82nHTsYaqCKjvXvmhuVqylwoLLaH6UBLVMuBBPGIDGAf6glxu', 'admin@email.com', 'Administrator', 'Administrator', 1, 1, 1, '2014-11-11 11:03:45'),
('jdoe', '$2y$10$/gMa82nHTsYaqCKjvXvmhuVqylwoLLaH6UBLVMuBBPGIDGAf6glxu', 'jdoe@email.com', 'Jane Doe', 'Jane', 0, 0, 0, '2014-11-11 11:05:01'),
('user', '$2y$10$/gMa82nHTsYaqCKjvXvmhuVqylwoLLaH6UBLVMuBBPGIDGAf6glxu', 'user@gmail.com', 'John Doe', 'John', 0, 0, 0, '2014-11-10 22:56:41');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fk_owner` FOREIGN KEY (`owner`) REFERENCES `usersinfo` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
