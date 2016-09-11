-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 10, 2016 at 08:38 PM
-- Server version: 5.5.42-37.1-log
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `projectx_vvarsc2`
--
CREATE DATABASE IF NOT EXISTS `projectx_vvarsc2` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `projectx_vvarsc2`;

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

DROP TABLE IF EXISTS `divisions`;
CREATE TABLE IF NOT EXISTS `divisions` (
  `div_id` int(11) NOT NULL,
  `div_name` varchar(100) NOT NULL,
  `div_orderby` int(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

DROP TABLE IF EXISTS `manufacturers`;
CREATE TABLE IF NOT EXISTS `manufacturers` (
  `manu_id` int(11) NOT NULL,
  `manu_name` varchar(200) NOT NULL,
  `manu_shortName` varchar(20) DEFAULT NULL,
  `manu_smallImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `mem_id` int(111) NOT NULL,
  `mem_name` varchar(200) NOT NULL,
  `mem_callsign` varchar(200) DEFAULT NULL,
  `mem_sc` tinyint(1) NOT NULL DEFAULT '0',
  `mem_gint` text,
  `mem_avatar_link` varchar(1000) NOT NULL DEFAULT 'https://robertsspaceindustries.com/rsi/static/images/account/avatar_default_big.jpg',
  `ranks_rank_id` int(11) DEFAULT NULL,
  `divisions_div_id` int(11) DEFAULT NULL,
  `specialties_spec_id` int(11) DEFAULT NULL,
  `CreatedOn` date DEFAULT NULL,
  `websiteRole` varchar(10) DEFAULT NULL,
  `password` varchar(512) DEFAULT NULL,
  `pass_reset` tinyint(1) DEFAULT '0',
  `pass_reset_date` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=940 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `member_qualifications`
--

DROP TABLE IF EXISTS `member_qualifications`;
CREATE TABLE IF NOT EXISTS `member_qualifications` (
  `RowID` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `qualification_id` int(11) DEFAULT NULL,
  `qualification_level_id` int(11) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mysql_testing`
--

DROP TABLE IF EXISTS `mysql_testing`;
CREATE TABLE IF NOT EXISTS `mysql_testing` (
  `db_names` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `qualifications`
--

DROP TABLE IF EXISTS `qualifications`;
CREATE TABLE IF NOT EXISTS `qualifications` (
  `qualification_id` int(11) NOT NULL,
  `qualification_name` varchar(200) DEFAULT NULL,
  `divisions_div_id` int(11) DEFAULT NULL,
  `qualification_image` varchar(1000) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `qualification_levels`
--

DROP TABLE IF EXISTS `qualification_levels`;
CREATE TABLE IF NOT EXISTS `qualification_levels` (
  `RowID` int(11) NOT NULL,
  `qualification_id` int(11) DEFAULT NULL,
  `qualification_level_id` int(11) DEFAULT NULL,
  `qualification_level_name` varchar(200) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ranks`
--

DROP TABLE IF EXISTS `ranks`;
CREATE TABLE IF NOT EXISTS `ranks` (
  `rank_id` int(11) NOT NULL,
  `rank_type` varchar(100) NOT NULL DEFAULT 'Navy',
  `rank_name` varchar(100) NOT NULL,
  `rank_image` varchar(1000) DEFAULT NULL,
  `rank_abbr` varchar(45) NOT NULL,
  `rank_orderby` int(2) NOT NULL,
  `rank_tinyImage` varchar(1000) DEFAULT NULL,
  `rank_level` varchar(100) NOT NULL,
  `rank_groupName` varchar(100) DEFAULT NULL,
  `rank_groupImage` varchar(100) DEFAULT NULL,
  `rank_groupTinyImage` varchar(100) DEFAULT NULL,
  `rank_group_orderby` int(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `role_shortName` varchar(100) DEFAULT NULL,
  `role_orderby` int(2) NOT NULL,
  `isPrivate` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ships`
--

DROP TABLE IF EXISTS `ships`;
CREATE TABLE IF NOT EXISTS `ships` (
  `ship_id` int(11) NOT NULL,
  `ship_name` varchar(200) NOT NULL,
  `ship_pname` varchar(200) NOT NULL,
  `ship_model_designation` varchar(50) DEFAULT NULL,
  `ship_model_visible` bit(1) DEFAULT NULL,
  `ship_role_primary` varchar(200) NOT NULL,
  `ship_role_secondary` varchar(200) DEFAULT NULL,
  `ship_classification` varchar(200) DEFAULT NULL,
  `ship_link` varchar(1000) DEFAULT NULL,
  `ship_image_link` varchar(1000) DEFAULT NULL,
  `ship_brochure_link` varchar(1000) DEFAULT NULL,
  `ship_silo` varchar(1000) DEFAULT NULL,
  `ship_desc` text,
  `manufacturers_manu_id` int(11) NOT NULL,
  `ship_price` decimal(14,2) DEFAULT '0.00',
  `ship_status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ships_has_members`
--

DROP TABLE IF EXISTS `ships_has_members`;
CREATE TABLE IF NOT EXISTS `ships_has_members` (
  `rowID` int(11) NOT NULL,
  `ships_ship_id` int(11) NOT NULL,
  `members_mem_id` int(11) NOT NULL,
  `shm_package` tinyint(1) DEFAULT '0',
  `shm_lti` tinyint(1) DEFAULT '0',
  `CreatedOn` datetime NOT NULL,
  `ModifiedOn` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=446 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ship_extended_data`
--

DROP TABLE IF EXISTS `ship_extended_data`;
CREATE TABLE IF NOT EXISTS `ship_extended_data` (
  `RowID` int(11) NOT NULL,
  `ships_ship_id` int(11) NOT NULL,
  `ship_length` decimal(14,2) DEFAULT NULL,
  `ship_width` decimal(14,2) DEFAULT NULL,
  `ship_height` decimal(14,2) DEFAULT NULL,
  `ship_mass` decimal(14,2) DEFAULT NULL,
  `ship_cargo_capacity` decimal(14,2) DEFAULT NULL,
  `ship_max_crew` int(11) DEFAULT NULL,
  `ship_max_powerPlant` varchar(45) DEFAULT NULL,
  `ship_max_mainThruster` varchar(45) DEFAULT NULL,
  `ship_max_maneuveringThruster` varchar(45) DEFAULT NULL,
  `ship_max_shield` varchar(45) DEFAULT NULL,
  `ship_hardpoint_fixed` varchar(45) DEFAULT NULL,
  `ship_hardpoint_gimbal` varchar(45) DEFAULT NULL,
  `ship_hardpoint_pylon` varchar(45) DEFAULT NULL,
  `ship_hardpoint_unmannedTurret` varchar(45) DEFAULT NULL,
  `ship_hardpoint_mannedTurret` varchar(45) DEFAULT NULL,
  `ship_hardpoint_class6` varchar(45) DEFAULT NULL,
  `ship_hardpoint_class7` varchar(45) DEFAULT NULL,
  `ship_hardpoint_class8` varchar(45) DEFAULT NULL,
  `ship_hardpoint_class9` varchar(45) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `specialties`
--

DROP TABLE IF EXISTS `specialties`;
CREATE TABLE IF NOT EXISTS `specialties` (
  `spec_id` int(11) NOT NULL,
  `spec_name` varchar(200) DEFAULT NULL,
  `spec_orderby` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `UnitMembers`
--

DROP TABLE IF EXISTS `UnitMembers`;
CREATE TABLE IF NOT EXISTS `UnitMembers` (
  `RowID` int(11) NOT NULL,
  `UnitID` int(11) DEFAULT NULL,
  `MemberID` int(11) DEFAULT NULL,
  `MemberRoleID` int(11) DEFAULT NULL,
  `CreatedOn` datetime DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Units`
--

DROP TABLE IF EXISTS `Units`;
CREATE TABLE IF NOT EXISTS `Units` (
  `UnitID` int(11) NOT NULL,
  `ParentUnitID` int(11) DEFAULT NULL,
  `UnitName` varchar(200) DEFAULT NULL,
  `UnitShortName` varchar(100) DEFAULT NULL,
  `UnitCallsign` varchar(100) DEFAULT NULL,
  `UnitFullName` varchar(500) DEFAULT NULL,
  `DivisionID` int(11) DEFAULT NULL,
  `IsActive` bit(1) DEFAULT NULL,
  `MaxUnitSize` int(11) DEFAULT NULL,
  `UnitDepth` int(11) NOT NULL,
  `UnitLevel` varchar(100) DEFAULT NULL,
  `UnitLeaderID` int(11) DEFAULT NULL,
  `CreatedOn` datetime DEFAULT NULL,
  `UnitDescription` text,
  `UnitSlogan` varchar(500) DEFAULT NULL,
  `UnitBackgroundImage` varchar(200) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `UnitShips`
--

DROP TABLE IF EXISTS `UnitShips`;
CREATE TABLE IF NOT EXISTS `UnitShips` (
  `RowID` int(11) NOT NULL,
  `UnitID` int(11) NOT NULL,
  `ShipID` int(11) NOT NULL,
  `Purpose` varchar(100) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `users_id` int(11) NOT NULL,
  `users_name` varchar(45) NOT NULL,
  `users_password` varchar(200) NOT NULL,
  `users_email` varchar(100) NOT NULL,
  `members_mem_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`div_id`), ADD UNIQUE KEY `div_id_UNIQUE` (`div_id`), ADD UNIQUE KEY `div_name_UNIQUE` (`div_name`);

--
-- Indexes for table `manufacturers`
--
ALTER TABLE `manufacturers`
  ADD PRIMARY KEY (`manu_id`), ADD UNIQUE KEY `manu_id_UNIQUE` (`manu_id`), ADD UNIQUE KEY `manu_name_UNIQUE` (`manu_name`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`mem_id`), ADD UNIQUE KEY `mem_id_UNIQUE` (`mem_id`), ADD UNIQUE KEY `mem_name_UNIQUE` (`mem_name`), ADD KEY `fk_members_ranks1_idx` (`ranks_rank_id`), ADD KEY `fk_members_divisions1_idx` (`divisions_div_id`);

--
-- Indexes for table `member_qualifications`
--
ALTER TABLE `member_qualifications`
  ADD PRIMARY KEY (`RowID`), ADD UNIQUE KEY `RowID_UNIQUE` (`RowID`);

--
-- Indexes for table `qualifications`
--
ALTER TABLE `qualifications`
  ADD PRIMARY KEY (`qualification_id`), ADD UNIQUE KEY `qualification_id_UNIQUE` (`qualification_id`);

--
-- Indexes for table `qualification_levels`
--
ALTER TABLE `qualification_levels`
  ADD PRIMARY KEY (`RowID`), ADD UNIQUE KEY `RowID_UNIQUE` (`RowID`);

--
-- Indexes for table `ranks`
--
ALTER TABLE `ranks`
  ADD PRIMARY KEY (`rank_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`), ADD UNIQUE KEY `role_id_UNIQUE` (`role_id`);

--
-- Indexes for table `ships`
--
ALTER TABLE `ships`
  ADD PRIMARY KEY (`ship_id`,`manufacturers_manu_id`), ADD UNIQUE KEY `ship_id_UNIQUE` (`ship_id`), ADD KEY `fk_ships_manufacturers1_idx` (`manufacturers_manu_id`);

--
-- Indexes for table `ships_has_members`
--
ALTER TABLE `ships_has_members`
  ADD PRIMARY KEY (`rowID`), ADD KEY `fk_ships_has_members_members1_idx` (`members_mem_id`), ADD KEY `fk_ships_has_members_ships1_idx` (`ships_ship_id`);

--
-- Indexes for table `ship_extended_data`
--
ALTER TABLE `ship_extended_data`
  ADD PRIMARY KEY (`RowID`), ADD UNIQUE KEY `RowID_UNIQUE` (`RowID`), ADD UNIQUE KEY `ships_ship_id_UNIQUE` (`ships_ship_id`), ADD KEY `fk_ship_extended_data_ships1_idx` (`ships_ship_id`);

--
-- Indexes for table `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`spec_id`), ADD UNIQUE KEY `spec_id_UNIQUE` (`spec_id`), ADD UNIQUE KEY `spec_name_UNIQUE` (`spec_name`);

--
-- Indexes for table `UnitMembers`
--
ALTER TABLE `UnitMembers`
  ADD PRIMARY KEY (`RowID`), ADD UNIQUE KEY `RowID_UNIQUE` (`RowID`);

--
-- Indexes for table `Units`
--
ALTER TABLE `Units`
  ADD PRIMARY KEY (`UnitID`), ADD UNIQUE KEY `UnitID_UNIQUE` (`UnitID`);

--
-- Indexes for table `UnitShips`
--
ALTER TABLE `UnitShips`
  ADD PRIMARY KEY (`RowID`), ADD KEY `FK_ships_UnitShips_idx` (`ShipID`), ADD KEY `FK_Units_UnitShips_idx` (`UnitID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`,`members_mem_id`), ADD UNIQUE KEY `users_id_UNIQUE` (`users_id`), ADD UNIQUE KEY `users_name_UNIQUE` (`users_name`), ADD UNIQUE KEY `users_email_UNIQUE` (`users_email`), ADD KEY `fk_users_members1_idx` (`members_mem_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `div_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `manufacturers`
--
ALTER TABLE `manufacturers`
  MODIFY `manu_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `mem_id` int(111) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=940;
--
-- AUTO_INCREMENT for table `member_qualifications`
--
ALTER TABLE `member_qualifications`
  MODIFY `RowID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `qualifications`
--
ALTER TABLE `qualifications`
  MODIFY `qualification_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `qualification_levels`
--
ALTER TABLE `qualification_levels`
  MODIFY `RowID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ranks`
--
ALTER TABLE `ranks`
  MODIFY `rank_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `ships`
--
ALTER TABLE `ships`
  MODIFY `ship_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT for table `ships_has_members`
--
ALTER TABLE `ships_has_members`
  MODIFY `rowID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=446;
--
-- AUTO_INCREMENT for table `ship_extended_data`
--
ALTER TABLE `ship_extended_data`
  MODIFY `RowID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `specialties`
--
ALTER TABLE `specialties`
  MODIFY `spec_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `UnitMembers`
--
ALTER TABLE `UnitMembers`
  MODIFY `RowID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=75;
--
-- AUTO_INCREMENT for table `Units`
--
ALTER TABLE `Units`
  MODIFY `UnitID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `UnitShips`
--
ALTER TABLE `UnitShips`
  MODIFY `RowID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ships`
--
ALTER TABLE `ships`
ADD CONSTRAINT `fk_ships_manufacturers1` FOREIGN KEY (`manufacturers_manu_id`) REFERENCES `manufacturers` (`manu_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `ships_has_members`
--
ALTER TABLE `ships_has_members`
ADD CONSTRAINT `fk_ships_has_members_members1` FOREIGN KEY (`members_mem_id`) REFERENCES `members` (`mem_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_ships_has_members_ships1` FOREIGN KEY (`ships_ship_id`) REFERENCES `ships` (`ship_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `fk_users_members1` FOREIGN KEY (`members_mem_id`) REFERENCES `members` (`mem_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
