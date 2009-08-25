-- phpMyAdmin SQL Dump
-- version 2.11.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 25, 2009 at 03:03 PM
-- Server version: 5.0.67
-- PHP Version: 5.2.6

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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `location`, `phone_no`) VALUES
('HQKL', 'Headquarters', 'Kuala Lumpur', '+603-45454433'),
('KDSP', 'Kedah Branch', 'Sungai Petani', '+604-4222222');

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE IF NOT EXISTS `document_types` (
  `id` varchar(10) NOT NULL,
  `description` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `document_types`
--

INSERT INTO `document_types` (`id`, `description`) VALUES
('PO1', 'Purchase Order Type 1'),
('PO2', 'Purchase Order Type 2'),
('GRN', 'Good Receive Note Type'),
('MT', 'Material Transfer');

-- --------------------------------------------------------

--
-- Table structure for table `good_receipt_notes`
--

CREATE TABLE IF NOT EXISTS `good_receipt_notes` (
  `doc_number` varchar(20) NOT NULL,
  `doc_date` date NOT NULL,
  `doc_type` varchar(20) NOT NULL,
  `supplier` varchar(20) NOT NULL,
  `do_no` varchar(30) NOT NULL,
  `po_no` varchar(20) NOT NULL,
  `inspector` varchar(20) NOT NULL,
  `inspector_date` date NOT NULL,
  `receiver` varchar(20) NOT NULL,
  `receiver_date` date NOT NULL,
  `status` enum('complete','incomplete') NOT NULL,
  PRIMARY KEY  (`doc_number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `good_receipt_notes`
--


-- --------------------------------------------------------

--
-- Table structure for table `good_receipt_note_details`
--

CREATE TABLE IF NOT EXISTS `good_receipt_note_details` (
  `id` int(15) NOT NULL auto_increment,
  `doc_number` varchar(20) NOT NULL,
  `item_id` varchar(20) NOT NULL,
  `quantity` int(15) NOT NULL,
  `remark` varchar(500) NOT NULL,
  `assessment` enum('OK','NG','Q','X') NOT NULL default 'OK',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `good_receipt_note_details`
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inv_classifications`
--

INSERT INTO `inv_classifications` (`id`, `classification_code`, `description`, `sub_category_code`, `status`) VALUES
('AT1', '1', 'Gait 1', 'AT', 1),
('AT2', '2', 'Gait 2', 'AT', 1),
('AE1', '1', 'Gait 1', 'AE', 1),
('AE2', '2', 'Gait 2', 'AE', 1);

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
  `image_url` varchar(1000) default NULL,
  `status` int(3) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inv_items`
--

INSERT INTO `inv_items` (`id`, `main_category_code`, `sub_category_code`, `classification_code`, `item_code`, `description`, `weight`, `dimension`, `part_number`, `unit_of_measure`, `rate`, `currency`, `purchase_year`, `detailed_description`, `image_url`, `status`) VALUES
('AE1001', 'A', 'AE', 'AE1', '001', 'Power Pack; Detroit [1]', 8000, 4268, 'G1 - PP - 01', 'ea', 1.95735e+06, 'MYR', 2002, '', NULL, 1),
('AE1003', 'A', 'AE', 'AE1', '003', 'Power Pack 2', 8000, 4000, '1', 'ea', 288888, 'MYR', 2009, 'Testing Purposes', NULL, 1),
('AE1002', 'A', 'AE', 'AE1', '002', 'Power Pack Detroit [2]', 8000, 5000, 'ER34985', 'ea', 388888, 'MYR', 2008, 'none', '/umw/storage/image/AE1002/Photo 19.jpg', 1),
('AT1001', 'A', 'AT', 'AT1', '001', 'Huge Tank', 40000, 8000, 'ERKd2938', 'ea', 2.88889e+07, 'MYR', 2009, 'Very big', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `inv_maincategories`
--

CREATE TABLE IF NOT EXISTS `inv_maincategories` (
  `category_code` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `status` int(3) NOT NULL,
  PRIMARY KEY  (`category_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inv_maincategories`
--

INSERT INTO `inv_maincategories` (`category_code`, `description`, `status`) VALUES
('A', 'Asset', 1),
('B', 'Rig Supports', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inv_stocks`
--

CREATE TABLE IF NOT EXISTS `inv_stocks` (
  `id` int(15) NOT NULL auto_increment,
  `branch_id` varchar(20) NOT NULL,
  `item_id` varchar(20) NOT NULL,
  `quantity` int(15) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `inv_stocks`
--

INSERT INTO `inv_stocks` (`id`, `branch_id`, `item_id`, `quantity`) VALUES
(1, 'HQKL', 'AE1001', 10);

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
  PRIMARY KEY  (`id`)
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
-- Table structure for table `production_issues`
--

CREATE TABLE IF NOT EXISTS `production_issues` (
  `doc_number` varchar(20) NOT NULL,
  `doc_date` date NOT NULL,
  `doc_type` varchar(20) NOT NULL,
  `notes` varchar(1000) default NULL,
  `issuer` varchar(20) NOT NULL,
  `issuer_date` date NOT NULL,
  `receiver` varchar(20) NOT NULL,
  `receiver_date` date NOT NULL,
  PRIMARY KEY  (`doc_number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `production_issues`
--


-- --------------------------------------------------------

--
-- Table structure for table `production_issue_details`
--

CREATE TABLE IF NOT EXISTS `production_issue_details` (
  `id` int(15) NOT NULL auto_increment,
  `doc_number` varchar(20) NOT NULL,
  `item_id` varchar(20) NOT NULL,
  `quantity` int(15) NOT NULL,
  `remark` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `production_issue_details`
--


-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE IF NOT EXISTS `purchases` (
  `doc_number` varchar(20) NOT NULL,
  `doc_date` date NOT NULL,
  `doc_type` varchar(20) NOT NULL,
  `doc_tag` enum('po','pr') NOT NULL default 'pr',
  `total` float NOT NULL,
  `supplier_1` varchar(20) NOT NULL,
  `supplier_2` varchar(20) default NULL,
  `supplier_3` varchar(20) default NULL,
  `supplier_1_contact` varchar(50) NOT NULL,
  `supplier_2_contact` varchar(50) NOT NULL,
  `supplier_3_contact` varchar(50) NOT NULL,
  `supplier_1_tel` varchar(30) NOT NULL,
  `supplier_2_tel` varchar(30) NOT NULL,
  `supplier_3_tel` varchar(30) NOT NULL,
  `requester` varchar(20) NOT NULL,
  `requester_date` date NOT NULL,
  `approver_1` varchar(20) default NULL,
  `approver_1_date` date default NULL,
  `approver_2` varchar(20) default NULL,
  `approver_2_date` date default NULL,
  `status` enum('approved','rejected','unapproved') NOT NULL default 'unapproved',
  PRIMARY KEY  (`doc_number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchases`
--


-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

CREATE TABLE IF NOT EXISTS `purchase_details` (
  `id` int(15) NOT NULL,
  `doc_number` varchar(20) NOT NULL,
  `item_id` varchar(20) NOT NULL,
  `quantity` int(15) NOT NULL default '0',
  `unit_price` float NOT NULL default '0',
  `extended_price` float NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_details`
--


-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(15) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `info` varchar(1000) default NULL,
  `image_url` varchar(1000) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact`, `info`, `image_url`) VALUES
(1, 'Mahen Sdn Bhd', '+6012-2222222', 'owned by Mahendran Balakrishnan', NULL);

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
  PRIMARY KEY  (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `name`, `email`, `branch_id`, `level`) VALUES
('Administrator', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Administrator', 'admin@admin.com', 'HQKL', 'admin'),
('User', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'User', 'user@user.com', 'KDSP', 'user'),
('Guest', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Guest', 'guest@user.com', 'HQKL', 'user'),
('Boss', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Boss', 'boss@admin.com', 'HQKL', 'admin');
