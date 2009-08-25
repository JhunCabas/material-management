-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 16, 2009 at 05:09 PM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `umw_mms`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE IF NOT EXISTS `branches` (
  `id` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branches`
--


-- --------------------------------------------------------

--
-- Table structure for table `inv_classifications`
--

CREATE TABLE IF NOT EXISTS `inv_classifications` (
  `id` varchar(20) NOT NULL,
  `classification_code` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `sub_category_code` varchar(20) NOT NULL,
  `status` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inv_classifications`
--

INSERT INTO `inv_classifications` (`id`, `classification_code`, `description`, `sub_category_code`, `status`) VALUES
('AT1', '1', 'Gait 1', 'AT', 1),
('AT2', '2', 'Gait 2', 'AT', 1),
('AE1', '1', 'Gait 1', 'AE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inv_items`
--

CREATE TABLE IF NOT EXISTS `inv_items` (
  `id` varchar(20) NOT NULL,
  `main_category_code` varchar(20) NOT NULL,
  `sub_category_code` varchar(20) NOT NULL,
  `classification_code` varchar(20) NOT NULL,
  `item_code` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `weight` int(9) NOT NULL,
  `dimension` int(9) NOT NULL,
  `part_number` varchar(30) NOT NULL,
  `unit_of_measure` varchar(10) NOT NULL,
  `rate` float NOT NULL,
  `currency` varchar(3) NOT NULL,
  `purchase_year` int(4) NOT NULL,
  `detailed_description` varchar(100) NOT NULL,
  `image_url` varchar(1000) DEFAULT NULL,
  `status` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inv_items`
--

INSERT INTO `inv_items` (`id`, `main_category_code`, `sub_category_code`, `classification_code`, `item_code`, `description`, `weight`, `dimension`, `part_number`, `unit_of_measure`, `rate`, `currency`, `purchase_year`, `detailed_description`, `image_url`, `status`) VALUES
('AE1001', 'A', 'AE', 'AE1', '001', 'Power Pack; Detroit [1]', 8000, 4268, 'G1 - PP - 01', 'ea', 1.95735e+006, 'MYR', 2002, '', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `inv_maincategories`
--

CREATE TABLE IF NOT EXISTS `inv_maincategories` (
  `category_code` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `status` int(3) NOT NULL,
  PRIMARY KEY (`category_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inv_maincategories`
--

INSERT INTO `inv_maincategories` (`category_code`, `description`, `status`) VALUES
('A', 'Asset', 1),
('B', 'Rig Supports', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inv_subcategories`
--

CREATE TABLE IF NOT EXISTS `inv_subcategories` (
  `id` varchar(20) NOT NULL,
  `category_code` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `main_category_code` varchar(20) NOT NULL,
  `status` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inv_subcategories`
--

INSERT INTO `inv_subcategories` (`id`, `category_code`, `description`, `main_category_code`, `status`) VALUES
('AT', 'T', 'Tank', 'A', 1),
('BC', 'C', 'Consummables', 'B', 1),
('AE', 'E', 'Equipment', 'A', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_categories`
--

CREATE TABLE IF NOT EXISTS `supplier_categories` (
  `category_code` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `status` int(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier_categories`
--

