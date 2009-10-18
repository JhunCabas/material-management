-- phpMyAdmin SQL Dump
-- version 2.11.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 18, 2009 at 07:57 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: 'umw_new'
--

-- --------------------------------------------------------

--
-- Table structure for table 'branches'
--

CREATE TABLE branches (
  id varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  location varchar(100) NOT NULL,
  phone_no varchar(15) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'currencies'
--

CREATE TABLE currencies (
  id int(10) NOT NULL auto_increment,
  country varchar(20) NOT NULL,
  exchange float NOT NULL,
  `month` date NOT NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table 'document_types'
--

CREATE TABLE document_types (
  id varchar(10) NOT NULL,
  description varchar(200) default NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'good_receipt_notes'
--

CREATE TABLE good_receipt_notes (
  doc_number varchar(50) NOT NULL,
  doc_date date NOT NULL,
  doc_type varchar(20) NOT NULL,
  branch_id varchar(10) NOT NULL,
  supplier varchar(20) NOT NULL,
  do_no varchar(30) NOT NULL,
  po_no varchar(50) NOT NULL,
  inspector varchar(20) default NULL,
  inspector_date date default NULL,
  receiver varchar(20) default NULL,
  receiver_date date default NULL,
  `status` enum('completed','incomplete') NOT NULL default 'incomplete',
  PRIMARY KEY  (doc_number)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'good_receipt_note_details'
--

CREATE TABLE good_receipt_note_details (
  id int(15) NOT NULL auto_increment,
  doc_number varchar(50) NOT NULL,
  item_id varchar(20) NOT NULL,
  description text NOT NULL,
  quantity int(15) NOT NULL,
  remark varchar(500) default NULL,
  assessment enum('OK','NG','Q','X') NOT NULL default 'OK',
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=68 ;

-- --------------------------------------------------------

--
-- Table structure for table 'inv_classifications'
--

CREATE TABLE inv_classifications (
  id varchar(20) NOT NULL,
  classification_code varchar(20) NOT NULL,
  description varchar(50) NOT NULL,
  sub_category_code varchar(20) NOT NULL,
  `status` int(3) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'inv_items'
--

CREATE TABLE inv_items (
  id varchar(20) NOT NULL,
  main_category_code varchar(20) NOT NULL,
  sub_category_code varchar(20) NOT NULL,
  classification_code varchar(20) NOT NULL,
  item_code varchar(20) NOT NULL,
  description varchar(1000) default NULL,
  weight int(9) default NULL,
  dimension int(9) default NULL,
  part_number varchar(30) default NULL,
  unit_of_measure varchar(10) default NULL,
  rate decimal(14,2) default NULL,
  currency varchar(3) default NULL,
  purchase_year int(4) default NULL,
  detailed_description varchar(100) default NULL,
  image_url varchar(1000) default NULL,
  `status` int(3) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'inv_maincategories'
--

CREATE TABLE inv_maincategories (
  category_code varchar(20) NOT NULL,
  description varchar(50) NOT NULL,
  `status` int(3) NOT NULL,
  PRIMARY KEY  (category_code)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'inv_stocks'
--

CREATE TABLE inv_stocks (
  id int(15) NOT NULL auto_increment,
  branch_id varchar(20) NOT NULL,
  item_id varchar(20) NOT NULL,
  quantity int(15) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8234 ;

-- --------------------------------------------------------

--
-- Table structure for table 'inv_subcategories'
--

CREATE TABLE inv_subcategories (
  id varchar(20) NOT NULL,
  category_code varchar(20) NOT NULL,
  description varchar(50) NOT NULL,
  main_category_code varchar(20) NOT NULL,
  `status` int(3) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'material_transfers'
--

CREATE TABLE material_transfers (
  doc_number varchar(30) NOT NULL,
  doc_date date NOT NULL,
  doc_type varchar(20) NOT NULL,
  branch_id varchar(10) NOT NULL,
  approver varchar(20) default NULL,
  approver_date date default NULL,
  requester varchar(20) NOT NULL,
  requester_date date NOT NULL,
  `status` enum('pending','completed') NOT NULL default 'pending',
  PRIMARY KEY  (doc_number)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'material_transfer_details'
--

CREATE TABLE material_transfer_details (
  id int(15) NOT NULL auto_increment,
  doc_number varchar(30) NOT NULL,
  item_id varchar(20) NOT NULL,
  quantity int(15) NOT NULL,
  remark varchar(500) default NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table 'production_issues'
--

CREATE TABLE production_issues (
  doc_number varchar(30) NOT NULL,
  doc_date date NOT NULL,
  doc_type varchar(20) NOT NULL,
  branch_id varchar(10) NOT NULL,
  notes varchar(1000) default NULL,
  `issuer` varchar(20) default NULL,
  issuer_date date default NULL,
  receiver varchar(20) default NULL,
  receiver_date date default NULL,
  `status` enum('pending','completed') NOT NULL default 'pending',
  PRIMARY KEY  (doc_number)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'production_issue_details'
--

CREATE TABLE production_issue_details (
  id int(15) NOT NULL auto_increment,
  doc_number varchar(30) NOT NULL,
  item_id varchar(20) NOT NULL,
  quantity int(15) NOT NULL default '0',
  remark varchar(500) default NULL,
  `status` enum('pending','completed') NOT NULL default 'pending',
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table 'purchases'
--

CREATE TABLE purchases (
  doc_number varchar(50) NOT NULL,
  running_number int(6) NOT NULL,
  doc_date date NOT NULL,
  doc_type varchar(20) NOT NULL,
  doc_tag enum('po','pr') NOT NULL default 'pr',
  branch_id varchar(10) NOT NULL,
  currency varchar(20) default NULL,
  discount float NOT NULL,
  total float NOT NULL default '0',
  supplier_1 varchar(20) NOT NULL,
  supplier_2 varchar(20) default NULL,
  supplier_3 varchar(20) default NULL,
  supplier_1_contact varchar(50) default NULL,
  supplier_2_contact varchar(50) default NULL,
  supplier_3_contact varchar(50) default NULL,
  supplier_1_tel varchar(30) default NULL,
  supplier_2_tel varchar(30) default NULL,
  supplier_3_tel varchar(30) default NULL,
  requester varchar(20) NOT NULL,
  requester_date date NOT NULL,
  approver_1 varchar(20) default NULL,
  approver_1_date date default NULL,
  payment text,
  delivery text,
  special_instruction text,
  `status` enum('approved','rejected','unapproved','completed','grn') NOT NULL default 'unapproved',
  PRIMARY KEY  (doc_number)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table 'purchase_details'
--

CREATE TABLE purchase_details (
  id int(15) NOT NULL auto_increment,
  doc_number varchar(50) NOT NULL,
  item_id varchar(20) NOT NULL,
  description text NOT NULL,
  quantity int(15) NOT NULL default '0',
  unit_price float NOT NULL default '0',
  extended_price float NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
-- Table structure for table 'suppliers'
--

CREATE TABLE suppliers (
  id int(15) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  address text NOT NULL,
  line_1 text,
  line_2 text,
  line_3 text,
  contact_person varchar(100) NOT NULL,
  contact varchar(20) NOT NULL,
  info text,
  fax_no varchar(20) default NULL,
  `status` int(3) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table 'users'
--

CREATE TABLE users (
  username varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  email varchar(20) NOT NULL,
  branch_id varchar(20) NOT NULL,
  `level` varchar(20) NOT NULL,
  PRIMARY KEY  (username)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
