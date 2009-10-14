-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 14, 2009 at 04:01 AM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

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

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `country` varchar(20) NOT NULL,
  `exchange` float NOT NULL,
  `month` date NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE IF NOT EXISTS `document_types` (
  `id` varchar(10) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `good_receipt_notes`
--

CREATE TABLE IF NOT EXISTS `good_receipt_notes` (
  `doc_number` varchar(50) NOT NULL,
  `doc_date` date NOT NULL,
  `doc_type` varchar(20) NOT NULL,
  `branch_id` varchar(10) NOT NULL,
  `supplier` varchar(20) NOT NULL,
  `do_no` varchar(30) NOT NULL,
  `po_no` varchar(50) NOT NULL,
  `inspector` varchar(20) DEFAULT NULL,
  `inspector_date` date DEFAULT NULL,
  `receiver` varchar(20) DEFAULT NULL,
  `receiver_date` date DEFAULT NULL,
  `status` enum('completed','incomplete') NOT NULL DEFAULT 'incomplete',
  PRIMARY KEY (`doc_number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `good_receipt_note_details`
--

CREATE TABLE IF NOT EXISTS `good_receipt_note_details` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `doc_number` varchar(50) NOT NULL,
  `item_id` varchar(20) NOT NULL,
  `quantity` int(15) NOT NULL,
  `remark` varchar(500) DEFAULT NULL,
  `assessment` enum('OK','NG','Q','X') NOT NULL DEFAULT 'OK',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

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
  `description` varchar(1000) DEFAULT NULL,
  `weight` int(9) DEFAULT NULL,
  `dimension` int(9) DEFAULT NULL,
  `part_number` varchar(30) DEFAULT NULL,
  `unit_of_measure` varchar(10) DEFAULT NULL,
  `rate` decimal(14,2) DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `purchase_year` int(4) DEFAULT NULL,
  `detailed_description` varchar(100) DEFAULT NULL,
  `image_url` varchar(1000) DEFAULT NULL,
  `status` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `inv_stocks`
--

CREATE TABLE IF NOT EXISTS `inv_stocks` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `branch_id` varchar(20) NOT NULL,
  `item_id` varchar(20) NOT NULL,
  `quantity` int(15) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8221 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `material_transfers`
--

CREATE TABLE IF NOT EXISTS `material_transfers` (
  `doc_number` varchar(30) NOT NULL,
  `doc_date` date NOT NULL,
  `doc_type` varchar(20) NOT NULL,
  `branch_id` varchar(10) NOT NULL,
  `approver` varchar(20) DEFAULT NULL,
  `approver_date` date DEFAULT NULL,
  `requester` varchar(20) NOT NULL,
  `requester_date` date NOT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`doc_number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `material_transfer_details`
--

CREATE TABLE IF NOT EXISTS `material_transfer_details` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `doc_number` varchar(30) NOT NULL,
  `item_id` varchar(20) NOT NULL,
  `quantity` int(15) NOT NULL,
  `remark` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `production_issues`
--

CREATE TABLE IF NOT EXISTS `production_issues` (
  `doc_number` varchar(30) NOT NULL,
  `doc_date` date NOT NULL,
  `doc_type` varchar(20) NOT NULL,
  `branch_id` varchar(10) NOT NULL,
  `notes` varchar(1000) DEFAULT NULL,
  `issuer` varchar(20) DEFAULT NULL,
  `issuer_date` date DEFAULT NULL,
  `receiver` varchar(20) DEFAULT NULL,
  `receiver_date` date DEFAULT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`doc_number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `production_issue_details`
--

CREATE TABLE IF NOT EXISTS `production_issue_details` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `doc_number` varchar(30) NOT NULL,
  `item_id` varchar(20) NOT NULL,
  `quantity` int(15) NOT NULL DEFAULT '0',
  `remark` varchar(500) DEFAULT NULL,
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE IF NOT EXISTS `purchases` (
  `doc_number` varchar(50) NOT NULL,
  `running_number` int(6) NOT NULL,
  `doc_date` date NOT NULL,
  `doc_type` varchar(20) NOT NULL,
  `doc_tag` enum('po','pr') NOT NULL DEFAULT 'pr',
  `branch_id` varchar(10) NOT NULL,
  `currency` varchar(20) DEFAULT NULL,
  `discount` float NOT NULL,
  `total` float NOT NULL DEFAULT '0',
  `supplier_1` varchar(20) NOT NULL,
  `supplier_2` varchar(20) DEFAULT NULL,
  `supplier_3` varchar(20) DEFAULT NULL,
  `supplier_1_contact` varchar(50) DEFAULT NULL,
  `supplier_2_contact` varchar(50) DEFAULT NULL,
  `supplier_3_contact` varchar(50) DEFAULT NULL,
  `supplier_1_tel` varchar(30) DEFAULT NULL,
  `supplier_2_tel` varchar(30) DEFAULT NULL,
  `supplier_3_tel` varchar(30) DEFAULT NULL,
  `requester` varchar(20) NOT NULL,
  `requester_date` date NOT NULL,
  `approver_1` varchar(20) DEFAULT NULL,
  `approver_1_date` date DEFAULT NULL,
  `payment` text,
  `delivery` text,
  `status` enum('approved','rejected','unapproved','completed') NOT NULL DEFAULT 'unapproved',
  PRIMARY KEY (`doc_number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

CREATE TABLE IF NOT EXISTS `purchase_details` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `doc_number` varchar(50) NOT NULL,
  `item_id` varchar(20) NOT NULL,
  `quantity` int(15) NOT NULL DEFAULT '0',
  `unit_price` float NOT NULL DEFAULT '0',
  `extended_price` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `line_1` text,
  `line_2` text,
  `line_3` text,
  `contact_person` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `info` text,
  `fax_no` varchar(20) DEFAULT NULL,
  `status` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `branch_id` varchar(20) NOT NULL,
  `level` varchar(20) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
