-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2016 at 12:40 AM
-- Server version: 5.5.20
-- PHP Version: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dbretailkain`
--

-- --------------------------------------------------------

--
-- Table structure for table `reff_groupid`
--

CREATE TABLE IF NOT EXISTS `reff_groupid` (
  `groupid` varchar(20) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reff_groupid`
--

INSERT INTO `reff_groupid` (`groupid`, `description`) VALUES
('11111', 'administrator'),
('stockist1', 'stockist');

-- --------------------------------------------------------

--
-- Table structure for table `reff_groupmenu`
--

CREATE TABLE IF NOT EXISTS `reff_groupmenu` (
  `groupid` varchar(20) NOT NULL,
  `menuid` varchar(50) NOT NULL,
  `isView` bit(1) DEFAULT NULL,
  `isAdd` bit(1) DEFAULT NULL,
  `isUpdate` bit(1) DEFAULT NULL,
  `isDelete` bit(1) DEFAULT NULL,
  PRIMARY KEY (`groupid`,`menuid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reff_groupmenu`
--

INSERT INTO `reff_groupmenu` (`groupid`, `menuid`, `isView`, `isAdd`, `isUpdate`, `isDelete`) VALUES
('11111', '1', b'1', b'1', b'1', b'1'),
('11111', '2', b'1', b'1', b'1', b'1'),
('11111', '3', b'1', b'1', b'1', b'1'),
('11111', '4', b'1', b'1', b'1', b'1'),
('11111', '5', b'1', b'1', b'1', b'1'),
('11111', '6', b'1', b'1', b'1', b'1'),
('stockist1', '1', b'1', b'1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reff_items`
--

CREATE TABLE IF NOT EXISTS `reff_items` (
  `ItemName` varchar(150) NOT NULL,
  `description` varchar(8000) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`ItemName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reff_items`
--

INSERT INTO `reff_items` (`ItemName`, `description`, `Quantity`) VALUES
('test1', 'test1', 10),
('test2', 'test2', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `reff_itemss`
--
CREATE TABLE IF NOT EXISTS `reff_itemss` (
`ItemName` varchar(150)
,`Description` varchar(8000)
,`Quantity` int(11)
);
-- --------------------------------------------------------

--
-- Table structure for table `reff_menu`
--

CREATE TABLE IF NOT EXISTS `reff_menu` (
  `menuid` varchar(50) NOT NULL,
  `menu_desc` varchar(250) NOT NULL,
  `menu_url` varchar(500) NOT NULL,
  `menu_image_url` varchar(500) NOT NULL,
  PRIMARY KEY (`menuid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reff_menu`
--

INSERT INTO `reff_menu` (`menuid`, `menu_desc`, `menu_url`, `menu_image_url`) VALUES
('1', 'Stok Barang', 'Home/Stok', ''),
('2', 'User', 'Home/User', ''),
('3', 'User - Group menu', 'Home/GroupMenu', ''),
('4', 'Master Barang', 'Home/Items', ''),
('5', 'Group Menu', 'Home/GroupMenus', ''),
('6', 'Cek Stok barang', 'Home/CekStok', '');

-- --------------------------------------------------------

--
-- Table structure for table `reff_table`
--

CREATE TABLE IF NOT EXISTS `reff_table` (
  `maintable` varchar(150) NOT NULL,
  `refffield` varchar(150) NOT NULL,
  `reffquery` varchar(150) NOT NULL,
  `reffqueryedit` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reff_table`
--

INSERT INTO `reff_table` (`maintable`, `refffield`, `reffquery`, `reffqueryedit`) VALUES
('reff_users', 'groupid', 'select CONCAT(groupid,'' - '',description) as ''description'' FROM reff_groupid', 'select groupid,description FROM reff_groupid'),
('reff_groupmenu', 'groupid', 'select CONCAT(groupid,'' - '',description) as ''description'' FROM reff_groupid', 'select groupid,description FROM reff_groupid'),
('reff_groupmenu', 'menuid', 'select menu_desc as ''description'' FROM reff_menu', 'select menuid,menu_desc as ''description'' FROM reff_menu'),
('trans_stock', 'ItemName', 'select CONCAT(ItemName,'' - '',Description) as ''description'' FROM reff_items', 'select ItemName,description FROM reff_items');

-- --------------------------------------------------------

--
-- Table structure for table `reff_tablekey`
--

CREATE TABLE IF NOT EXISTS `reff_tablekey` (
  `tablename` varchar(150) NOT NULL,
  `keyfields` varchar(500) NOT NULL,
  `fields` varchar(2000) NOT NULL,
  `Condition` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`tablename`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reff_tablekey`
--

INSERT INTO `reff_tablekey` (`tablename`, `keyfields`, `fields`, `Condition`) VALUES
('reff_groupid', 'groupid', 'groupid,description', 'groupid != ''11111'''),
('reff_groupmenu', 'groupid,menuid', 'groupid,menuid,isView,isAdd,isUpdate,isDelete', 'groupid != ''11111'''),
('reff_items', 'ItemName', 'ItemName,description', NULL),
('reff_itemss', 'ItemName', 'ItemName,Description,Quantity', NULL),
('reff_users', 'userid', 'userid,password,groupid,full_name,address,phone_number,email_address,active', 'userid != ''admin'''),
('trans_invoice', 'invoiceId,SPKId', 'invoiceId,SPKId,tanggal_invoice,jenis_kendaraan,no_polisi_kendaraan,Pemilik,no_hp', NULL),
('trans_stock', 'Id', 'Id,Tgl_Barang_Masuk,Jenis,ItemName,Quantity', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reff_users`
--

CREATE TABLE IF NOT EXISTS `reff_users` (
  `userid` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL,
  `groupid` varchar(20) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `address` varchar(700) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `is_login` bit(1) NOT NULL,
  `active` bit(1) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reff_users`
--

INSERT INTO `reff_users` (`userid`, `password`, `groupid`, `full_name`, `address`, `phone_number`, `email_address`, `is_login`, `active`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', '11111', 'admin', 'tes', 'tes', 'tes', b'0', b'1'),
('teststock', '827ccb0eea8a706c4c34a16891f84e7b', 'stockist1', 'teststock', 'teststock', '081111111111', 'test@mail.com', b'0', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `trans_invoice`
--

CREATE TABLE IF NOT EXISTS `trans_invoice` (
  `invoiceId` int(10) unsigned zerofill NOT NULL,
  `SPKId` int(10) unsigned zerofill NOT NULL,
  `tanggal_invoice` date NOT NULL,
  `jenis_kendaraan` varchar(50) NOT NULL,
  `no_polisi_kendaraan` varchar(50) NOT NULL,
  `Pemilik` varchar(100) NOT NULL,
  `no_hp` varchar(50) NOT NULL,
  PRIMARY KEY (`invoiceId`,`SPKId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trans_invoice`
--

INSERT INTO `trans_invoice` (`invoiceId`, `SPKId`, `tanggal_invoice`, `jenis_kendaraan`, `no_polisi_kendaraan`, `Pemilik`, `no_hp`) VALUES
(0000000001, 0000000001, '2015-10-28', 'asdsa', 'sadasd', 'sadas', '1241');

-- --------------------------------------------------------

--
-- Table structure for table `trans_invoice_detail`
--

CREATE TABLE IF NOT EXISTS `trans_invoice_detail` (
  `invoiceId` varchar(10) NOT NULL,
  `detailId` int(11) NOT NULL,
  `bagian_kendaraan` varchar(150) NOT NULL,
  `jenis_jasa` varchar(150) NOT NULL,
  `Price` int(11) NOT NULL,
  `Price2` int(11) NOT NULL,
  `Price3` int(11) NOT NULL,
  PRIMARY KEY (`invoiceId`,`detailId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trans_invoice_detail`
--

INSERT INTO `trans_invoice_detail` (`invoiceId`, `detailId`, `bagian_kendaraan`, `jenis_jasa`, `Price`, `Price2`, `Price3`) VALUES
('0000000001', 1, 'test bagian', 'test jenis', 12345, 0, 0),
('0000000002', 1, 'htasdg', 'abc', 12123454, 0, 0),
('0000000002', 2, 'safagsa', 'abc', 12315, 0, 0),
('0000000003', 1, 'htasdg', 'abc', 12123454, 0, 0),
('0000000005', 1, 'htasdg', 'tes', 12, 0, 0),
('0000000006', 1, 'htasdg', 'es', 12111, 112111, 11111),
('0000000007', 1, 'htasdg', 'tes', 12111, 112111, 11111),
('0000000008', 1, 'htasdg', 'asda', 12111, 112111, 11111),
('0000000009', 1, 'sdafas', 'a', 123, 0, 0),
('0000000010', 1, 'htasdg', 'tes', 12111, 112111, 11111),
('0000000011', 1, 'htasdg', 'a', 12111, 112111, 11111),
('0000000012', 1, 'sdafas', 'asd', 123, 0, 0),
('0000000013', 1, 'sdafas', 'a', 123, 0, 0),
('0000000014', 1, 'sdafas', 'ab', 123, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `trans_stock`
--

CREATE TABLE IF NOT EXISTS `trans_stock` (
  `Id` int(10) unsigned zerofill NOT NULL,
  `Tgl_Barang_Masuk` datetime NOT NULL,
  `Jenis` varchar(15) NOT NULL,
  `ItemName` varchar(150) NOT NULL,
  `Quantity` int(8) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trans_stock`
--

INSERT INTO `trans_stock` (`Id`, `Tgl_Barang_Masuk`, `Jenis`, `ItemName`, `Quantity`) VALUES
(0000000002, '2016-05-07 00:00:00', 'barang masuk', 'test1', 10);

-- --------------------------------------------------------

--
-- Structure for view `reff_itemss`
--
DROP TABLE IF EXISTS `reff_itemss`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u440958084_usr`@`mysql.idhostinger.com` SQL SECURITY DEFINER VIEW `reff_itemss` AS select `reff_items`.`ItemName` AS `ItemName`,`reff_items`.`description` AS `Description`,`reff_items`.`Quantity` AS `Quantity` from `reff_items`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
