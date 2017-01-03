CREATE DATABASE  IF NOT EXISTS `projectx_vvarsc2` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `projectx_vvarsc2`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)
--
-- Host: box549.bluehost.com    Database: projectx_vvarsc2
-- ------------------------------------------------------
-- Server version	5.6.32-78.1-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `LK_InfoSecLevels`
--

DROP TABLE IF EXISTS `LK_InfoSecLevels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LK_InfoSecLevels` (
  `InfoSecLevelID` int(11) NOT NULL,
  `InfoSecLevelName` varchar(200) NOT NULL,
  `InfoSecLevelShortName` varchar(40) NOT NULL,
  PRIMARY KEY (`InfoSecLevelID`),
  UNIQUE KEY `SecurityLevelID_UNIQUE` (`InfoSecLevelID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LK_MissionStatus`
--

DROP TABLE IF EXISTS `LK_MissionStatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LK_MissionStatus` (
  `MissionStatus` int(11) NOT NULL,
  `Description` varchar(100) NOT NULL,
  PRIMARY KEY (`MissionStatus`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LK_OpUnitTypes`
--

DROP TABLE IF EXISTS `LK_OpUnitTypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LK_OpUnitTypes` (
  `OpUnitTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `OpUnitTypeDescription` varchar(100) NOT NULL,
  `Support_AirFlight` bit(1) NOT NULL DEFAULT b'1',
  `Support_GroundTeam` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`OpUnitTypeID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LK_QualificationCategories`
--

DROP TABLE IF EXISTS `LK_QualificationCategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LK_QualificationCategories` (
  `CategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(100) NOT NULL,
  PRIMARY KEY (`CategoryID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MissionShipMembers`
--

DROP TABLE IF EXISTS `MissionShipMembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MissionShipMembers` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `MissionUnitID` int(11) NOT NULL DEFAULT '1',
  `MissionShipID` int(11) NOT NULL,
  `MemberID` int(11) DEFAULT NULL,
  `OpUnitMemberRoleID` int(11) NOT NULL,
  `MissionID` int(11) NOT NULL DEFAULT '2',
  `CreatedOn` datetime DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  `ModifiedOn` datetime DEFAULT NULL,
  `ModifiedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`RowID`)
) ENGINE=MyISAM AUTO_INCREMENT=398 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MissionShips`
--

DROP TABLE IF EXISTS `MissionShips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MissionShips` (
  `MissionShipID` int(11) NOT NULL AUTO_INCREMENT,
  `MissionUnitID` int(11) NOT NULL,
  `ShipID` int(11) NOT NULL,
  `Callsign` varchar(100) DEFAULT NULL,
  `MissionShipOrderBy` int(11) NOT NULL DEFAULT '1',
  `MissionID` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`MissionShipID`)
) ENGINE=MyISAM AUTO_INCREMENT=354 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MissionUnitMembers`
--

DROP TABLE IF EXISTS `MissionUnitMembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MissionUnitMembers` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `MissionUnitID` int(11) NOT NULL,
  `MemberID` int(11) DEFAULT NULL,
  `OpUnitMemberRoleID` int(11) NOT NULL,
  `MissionID` int(11) NOT NULL DEFAULT '2',
  `CreatedOn` datetime DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  `ModifiedOn` datetime DEFAULT NULL,
  `ModifiedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`RowID`)
) ENGINE=MyISAM AUTO_INCREMENT=319 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MissionUnits`
--

DROP TABLE IF EXISTS `MissionUnits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MissionUnits` (
  `MissionUnitID` int(11) NOT NULL AUTO_INCREMENT,
  `MissionUnitType` int(11) NOT NULL DEFAULT '1',
  `MissionID` int(11) NOT NULL,
  `UnitID` int(11) NOT NULL,
  `MissionUnitObjectives` text,
  `PackageNumber` int(11) NOT NULL,
  `Callsign` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`MissionUnitID`)
) ENGINE=MyISAM AUTO_INCREMENT=391 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Missions`
--

DROP TABLE IF EXISTS `Missions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Missions` (
  `MissionID` int(11) NOT NULL AUTO_INCREMENT,
  `OpTemplateID` int(11) NOT NULL,
  `MissionName` varchar(100) NOT NULL,
  `MissionType` varchar(100) NOT NULL,
  `StartingLocation` varchar(100) NOT NULL,
  `StartDate` datetime NOT NULL,
  `EndDate` datetime NOT NULL,
  `Mission` text,
  `Description` text,
  `MissionStatus` int(11) NOT NULL DEFAULT '1',
  `CreatedOn` datetime DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  `ModifiedOn` datetime DEFAULT NULL,
  `ModifiedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`MissionID`)
) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OpTemplateShipMembers`
--

DROP TABLE IF EXISTS `OpTemplateShipMembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OpTemplateShipMembers` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `OpTemplateUnitID` int(11) NOT NULL DEFAULT '1',
  `OpTemplateShipID` int(11) NOT NULL,
  `MemberID` int(11) DEFAULT NULL,
  `OpUnitMemberRoleID` int(11) NOT NULL,
  `OpTemplateID` int(11) NOT NULL DEFAULT '2',
  `CreatedOn` datetime DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  `ModifiedOn` datetime DEFAULT NULL,
  `ModifiedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`RowID`)
) ENGINE=MyISAM AUTO_INCREMENT=224 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OpTemplateShips`
--

DROP TABLE IF EXISTS `OpTemplateShips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OpTemplateShips` (
  `OpTemplateShipID` int(11) NOT NULL AUTO_INCREMENT,
  `OpTemplateUnitID` int(11) NOT NULL,
  `ShipID` int(11) NOT NULL,
  `Callsign` varchar(100) DEFAULT NULL,
  `OpTemplateShipOrderBy` int(11) NOT NULL DEFAULT '1',
  `OpTemplateID` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`OpTemplateShipID`)
) ENGINE=MyISAM AUTO_INCREMENT=189 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OpTemplateUnitMembers`
--

DROP TABLE IF EXISTS `OpTemplateUnitMembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OpTemplateUnitMembers` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `OpTemplateUnitID` int(11) NOT NULL,
  `MemberID` int(11) DEFAULT NULL,
  `OpUnitMemberRoleID` int(11) NOT NULL,
  `OpTemplateID` int(11) NOT NULL DEFAULT '2',
  `CreatedOn` datetime DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  `ModifiedOn` datetime DEFAULT NULL,
  `ModifiedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`RowID`)
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OpTemplateUnits`
--

DROP TABLE IF EXISTS `OpTemplateUnits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OpTemplateUnits` (
  `OpTemplateUnitID` int(11) NOT NULL AUTO_INCREMENT,
  `OpTemplateUnitType` int(11) NOT NULL DEFAULT '1',
  `OpTemplateID` int(11) NOT NULL,
  `UnitID` int(11) NOT NULL,
  `OpUnitObjectives` text,
  `PackageNumber` int(11) NOT NULL,
  `Callsign` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`OpTemplateUnitID`)
) ENGINE=MyISAM AUTO_INCREMENT=92 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OpTemplates`
--

DROP TABLE IF EXISTS `OpTemplates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OpTemplates` (
  `OpTemplateID` int(11) NOT NULL AUTO_INCREMENT,
  `OpTemplateName` varchar(200) NOT NULL,
  `OpTemplateType` varchar(100) NOT NULL,
  `StartingLocation` varchar(100) NOT NULL,
  `Mission` text,
  `Description` text,
  `CreatedOn` datetime DEFAULT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  `ModifiedOn` datetime DEFAULT NULL,
  `ModifiedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`OpTemplateID`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OpUnitTypeMemberRoles`
--

DROP TABLE IF EXISTS `OpUnitTypeMemberRoles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OpUnitTypeMemberRoles` (
  `OpUnitMemberRoleID` int(11) NOT NULL AUTO_INCREMENT,
  `RoleCategory` varchar(100) NOT NULL,
  `RoleName` varchar(100) NOT NULL,
  `RoleOrderBy` int(11) DEFAULT NULL,
  `IsLeadership` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`OpUnitMemberRoleID`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `UnitMembers`
--

DROP TABLE IF EXISTS `UnitMembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UnitMembers` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UnitID` int(11) DEFAULT NULL,
  `MemberID` int(11) DEFAULT NULL,
  `MemberRoleID` int(11) DEFAULT NULL,
  `CreatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`RowID`),
  UNIQUE KEY `RowID_UNIQUE` (`RowID`)
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `UnitShips`
--

DROP TABLE IF EXISTS `UnitShips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UnitShips` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `UnitID` int(11) NOT NULL,
  `ShipID` int(11) NOT NULL,
  `Purpose` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`RowID`),
  KEY `FK_ships_UnitShips_idx` (`ShipID`),
  KEY `FK_Units_UnitShips_idx` (`UnitID`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Units`
--

DROP TABLE IF EXISTS `Units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Units` (
  `UnitID` int(11) NOT NULL AUTO_INCREMENT,
  `ParentUnitID` int(11) DEFAULT NULL,
  `UnitName` varchar(200) DEFAULT NULL,
  `UnitShortName` varchar(100) DEFAULT NULL,
  `UnitCallsign` varchar(100) DEFAULT NULL,
  `UnitFullName` varchar(500) DEFAULT NULL,
  `DivisionID` int(11) DEFAULT NULL,
  `OpUnitTypeID` int(11) DEFAULT NULL,
  `IsActive` bit(1) DEFAULT NULL,
  `MaxUnitSize` int(11) DEFAULT NULL,
  `UnitDepth` int(11) NOT NULL,
  `UnitLevel` varchar(100) DEFAULT NULL,
  `UnitLeaderID` int(11) DEFAULT NULL,
  `CreatedOn` datetime DEFAULT NULL,
  `UnitDescription` text,
  `UnitSlogan` varchar(500) DEFAULT NULL,
  `UnitBackgroundImage` varchar(200) DEFAULT NULL,
  `UnitEmblemImage` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`UnitID`),
  UNIQUE KEY `UnitID_UNIQUE` (`UnitID`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `divisions`
--

DROP TABLE IF EXISTS `divisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `divisions` (
  `div_id` int(11) NOT NULL AUTO_INCREMENT,
  `div_name` varchar(100) NOT NULL,
  `div_orderby` int(2) NOT NULL,
  PRIMARY KEY (`div_id`),
  UNIQUE KEY `div_id_UNIQUE` (`div_id`),
  UNIQUE KEY `div_name_UNIQUE` (`div_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manufacturers`
--

DROP TABLE IF EXISTS `manufacturers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manufacturers` (
  `manu_id` int(11) NOT NULL AUTO_INCREMENT,
  `manu_name` varchar(200) NOT NULL,
  `manu_shortName` varchar(20) DEFAULT NULL,
  `manu_smallImage` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`manu_id`),
  UNIQUE KEY `manu_id_UNIQUE` (`manu_id`),
  UNIQUE KEY `manu_name_UNIQUE` (`manu_name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `member_qualifications`
--

DROP TABLE IF EXISTS `member_qualifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_qualifications` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL,
  `qualification_id` int(11) DEFAULT NULL,
  `qualification_level_id` int(11) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`RowID`),
  UNIQUE KEY `RowID_UNIQUE` (`RowID`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `mem_id` int(111) NOT NULL AUTO_INCREMENT,
  `mem_name` varchar(200) NOT NULL,
  `mem_callsign` varchar(200) DEFAULT NULL,
  `mem_sc` tinyint(1) NOT NULL DEFAULT '0',
  `mem_gint` text,
  `mem_avatar_link` varchar(1000) NOT NULL DEFAULT 'https://robertsspaceindustries.com/rsi/static/images/account/avatar_default_big.jpg',
  `ranks_rank_id` int(11) DEFAULT NULL,
  `divisions_div_id` int(11) DEFAULT NULL,
  `CreatedOn` date DEFAULT NULL,
  `websiteRole` varchar(10) DEFAULT NULL,
  `password` varchar(512) DEFAULT NULL,
  `pass_reset` tinyint(1) DEFAULT '0',
  `pass_reset_date` date DEFAULT NULL,
  `member_bio` text,
  `membership_type` tinyint(1) NOT NULL DEFAULT '1',
  `InfoSecLevelID` int(11) NOT NULL DEFAULT '1',
  `RankModifiedOn` date DEFAULT NULL,
  `vvar_id` bigint(20) NOT NULL,
  `mem_email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`mem_id`),
  UNIQUE KEY `mem_id_UNIQUE` (`mem_id`),
  UNIQUE KEY `mem_name_UNIQUE` (`mem_name`),
  KEY `fk_members_ranks1_idx` (`ranks_rank_id`),
  KEY `fk_members_divisions1_idx` (`divisions_div_id`)
) ENGINE=InnoDB AUTO_INCREMENT=978 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mysql_testing`
--

DROP TABLE IF EXISTS `mysql_testing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mysql_testing` (
  `db_names` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `qualifications`
--

DROP TABLE IF EXISTS `qualifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qualifications` (
  `qualification_id` int(11) NOT NULL AUTO_INCREMENT,
  `qualification_name` varchar(200) NOT NULL,
  `qualification_image` varchar(1000) DEFAULT NULL,
  `qualification_categoryID` int(11) NOT NULL,
  `IsActive` bit(1) NOT NULL DEFAULT b'1',
  `level1_reqs` text,
  `level2_reqs` text,
  `level3_reqs` text,
  PRIMARY KEY (`qualification_id`),
  UNIQUE KEY `qualification_id_UNIQUE` (`qualification_id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ranks`
--

DROP TABLE IF EXISTS `ranks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ranks` (
  `rank_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `rank_group_orderby` int(2) NOT NULL,
  PRIMARY KEY (`rank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(100) NOT NULL,
  `role_shortName` varchar(100) DEFAULT NULL,
  `role_displayName` varchar(100) DEFAULT NULL,
  `role_orderby` int(2) NOT NULL,
  `isPrivate` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_id_UNIQUE` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ship_extended_data`
--

DROP TABLE IF EXISTS `ship_extended_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ship_extended_data` (
  `RowID` int(11) NOT NULL AUTO_INCREMENT,
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
  `ship_hardpoint_class9` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`RowID`),
  UNIQUE KEY `RowID_UNIQUE` (`RowID`),
  UNIQUE KEY `ships_ship_id_UNIQUE` (`ships_ship_id`),
  KEY `fk_ship_extended_data_ships1_idx` (`ships_ship_id`)
) ENGINE=MyISAM AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ships`
--

DROP TABLE IF EXISTS `ships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ships` (
  `ship_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `ship_status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ship_id`,`manufacturers_manu_id`),
  UNIQUE KEY `ship_id_UNIQUE` (`ship_id`),
  KEY `fk_ships_manufacturers1_idx` (`manufacturers_manu_id`),
  CONSTRAINT `fk_ships_manufacturers1` FOREIGN KEY (`manufacturers_manu_id`) REFERENCES `manufacturers` (`manu_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ships_has_members`
--

DROP TABLE IF EXISTS `ships_has_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ships_has_members` (
  `rowID` int(11) NOT NULL AUTO_INCREMENT,
  `ships_ship_id` int(11) NOT NULL,
  `members_mem_id` int(11) NOT NULL,
  `shm_package` tinyint(1) DEFAULT '0',
  `shm_lti` tinyint(1) DEFAULT '0',
  `CreatedOn` datetime NOT NULL,
  `ModifiedOn` datetime NOT NULL,
  PRIMARY KEY (`rowID`),
  KEY `fk_ships_has_members_members1_idx` (`members_mem_id`),
  KEY `fk_ships_has_members_ships1_idx` (`ships_ship_id`),
  CONSTRAINT `fk_ships_has_members_members1` FOREIGN KEY (`members_mem_id`) REFERENCES `members` (`mem_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ships_has_members_ships1` FOREIGN KEY (`ships_ship_id`) REFERENCES `ships` (`ship_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=584 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `users_id` int(11) NOT NULL AUTO_INCREMENT,
  `users_name` varchar(45) NOT NULL,
  `users_password` varchar(200) NOT NULL,
  `users_email` varchar(100) NOT NULL,
  `members_mem_id` int(11) NOT NULL,
  PRIMARY KEY (`users_id`,`members_mem_id`),
  UNIQUE KEY `users_id_UNIQUE` (`users_id`),
  UNIQUE KEY `users_name_UNIQUE` (`users_name`),
  UNIQUE KEY `users_email_UNIQUE` (`users_email`),
  KEY `fk_users_members1_idx` (`members_mem_id`),
  CONSTRAINT `fk_users_members1` FOREIGN KEY (`members_mem_id`) REFERENCES `members` (`mem_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-02 16:41:36
