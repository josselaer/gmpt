-- MySQL dump 10.13  Distrib 5.7.9, for osx10.9 (x86_64)
--
-- Host: gmpt-dev.cze344fgq3d6.us-west-2.rds.amazonaws.com    Database: GMPT
-- ------------------------------------------------------
-- Server version	5.6.27-log

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
-- Table structure for table `Attendance`
--

DROP TABLE IF EXISTS `Attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Attendance` (
  `MeetingID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `CheckInTime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MeetingID`,`UserID`),
  KEY `UserID` (`UserID`),
  CONSTRAINT `Attendance_ibfk_1` FOREIGN KEY (`MeetingID`) REFERENCES `Meeting` (`MeetingID`),
  CONSTRAINT `Attendance_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Invite`
--

DROP TABLE IF EXISTS `Invite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invite` (
  `InviteID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL,
  `InviteTimestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  `RegisterIndicator` tinyint(1) DEFAULT '0',
  `InviteToken` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`InviteID`),
  KEY `UserID` (`UserID`),
  KEY `ProjectID` (`ProjectID`),
  CONSTRAINT `Invite_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`),
  CONSTRAINT `Invite_ibfk_2` FOREIGN KEY (`ProjectID`) REFERENCES `Project` (`ProjectID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Meeting`
--

DROP TABLE IF EXISTS `Meeting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Meeting` (
  `MeetingID` int(11) NOT NULL AUTO_INCREMENT,
  `ProjectID` int(11) NOT NULL,
  `MeetingDescription` varchar(50) DEFAULT NULL,
  `MeetingDate` date DEFAULT NULL,
  `LocationName` varchar(32) DEFAULT NULL,
  `StartTime` time DEFAULT NULL,
  `EndTime` time DEFAULT NULL,
  PRIMARY KEY (`MeetingID`),
  KEY `ProjectID` (`ProjectID`),
  CONSTRAINT `Meeting_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `Project` (`ProjectID`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Message`
--

DROP TABLE IF EXISTS `Message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Message` (
  `MessageID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `MessageRoomID` int(11) NOT NULL,
  `MessageText` varchar(255) DEFAULT NULL,
  `AnonymousIndicator` tinyint(1) DEFAULT NULL,
  `SendTime` timestamp NULL DEFAULT NULL,
  `FlagIndicator` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`MessageID`),
  KEY `UserID` (`UserID`),
  KEY `MessageRoomID` (`MessageRoomID`),
  CONSTRAINT `Message_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`),
  CONSTRAINT `Message_ibfk_2` FOREIGN KEY (`MessageRoomID`) REFERENCES `MessageRoom` (`MessageRoomID`)
) ENGINE=InnoDB AUTO_INCREMENT=330 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MessageReceipt`
--

DROP TABLE IF EXISTS `MessageReceipt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MessageReceipt` (
  `UserID` int(11) NOT NULL,
  `MessageRoomID` int(11) NOT NULL,
  `LastRead` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`UserID`,`MessageRoomID`),
  KEY `MessageRoomID` (`MessageRoomID`),
  CONSTRAINT `MessageReceipt_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`),
  CONSTRAINT `MessageReceipt_ibfk_2` FOREIGN KEY (`MessageRoomID`) REFERENCES `MessageRoom` (`MessageRoomID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MessageRoom`
--

DROP TABLE IF EXISTS `MessageRoom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MessageRoom` (
  `ProjectID` int(11) NOT NULL,
  `MessageRoomID` int(11) NOT NULL AUTO_INCREMENT,
  `CreationDate` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`MessageRoomID`),
  KEY `ProjectID` (`ProjectID`),
  CONSTRAINT `MessageRoom_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `Project` (`ProjectID`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Notification`
--

DROP TABLE IF EXISTS `Notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Notification` (
  `NotificationID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL,
  `NotificationTypeID` int(11) NOT NULL,
  `ReadIndicator` tinyint(1) NOT NULL DEFAULT '0',
  `CreateTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`NotificationID`),
  KEY `UserID` (`UserID`),
  KEY `ProjectID` (`ProjectID`),
  KEY `NotificationTypeID` (`NotificationTypeID`),
  CONSTRAINT `Notification_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`),
  CONSTRAINT `Notification_ibfk_2` FOREIGN KEY (`ProjectID`) REFERENCES `Project` (`ProjectID`),
  CONSTRAINT `Notification_ibfk_3` FOREIGN KEY (`NotificationTypeID`) REFERENCES `NotificationType` (`NotificationTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=440 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `NotificationType`
--

DROP TABLE IF EXISTS `NotificationType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NotificationType` (
  `NotificationTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `NotificationTypeDescription` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`NotificationTypeID`),
  UNIQUE KEY `NotificationTypeDescription` (`NotificationTypeDescription`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Permission`
--

DROP TABLE IF EXISTS `Permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Permission` (
  `PermissionID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`PermissionID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Project`
--

DROP TABLE IF EXISTS `Project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Project` (
  `ProjectID` int(11) NOT NULL AUTO_INCREMENT,
  `ProjectName` varchar(32) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `DateCreated` date DEFAULT NULL,
  PRIMARY KEY (`ProjectID`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Role`
--

DROP TABLE IF EXISTS `Role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Role` (
  `RoleID` int(11) NOT NULL,
  `RoleName` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`RoleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `RolePermission`
--

DROP TABLE IF EXISTS `RolePermission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RolePermission` (
  `RoleID` int(11) NOT NULL,
  `PermissionID` int(11) NOT NULL,
  PRIMARY KEY (`RoleID`,`PermissionID`),
  KEY `PermissionID` (`PermissionID`),
  CONSTRAINT `RolePermission_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `Role` (`RoleID`),
  CONSTRAINT `RolePermission_ibfk_2` FOREIGN KEY (`PermissionID`) REFERENCES `Permission` (`PermissionID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Session`
--

DROP TABLE IF EXISTS `Session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Session` (
  `SessionID` varchar(36) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ExpirationTimestamp` timestamp NULL DEFAULT NULL,
  `LoginTimestamp` timestamp NULL DEFAULT NULL,
  `LogoutTimestamp` timestamp NULL DEFAULT NULL,
  `ActiveState` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`SessionID`),
  KEY `UserID` (`UserID`),
  CONSTRAINT `Session_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(25) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Salt` varchar(10) NOT NULL,
  `FirstName` varchar(25) NOT NULL,
  `LastName` varchar(25) NOT NULL,
  `Email` varchar(25) NOT NULL,
  `RegisterTimeStamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `UserName` (`UserName`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `UserProject`
--

DROP TABLE IF EXISTS `UserProject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UserProject` (
  `ProjectID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `RoleID` int(11) NOT NULL,
  `StateID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ProjectID`,`UserID`),
  KEY `UserID` (`UserID`),
  KEY `RoleID` (`RoleID`),
  KEY `StateID` (`StateID`),
  CONSTRAINT `UserProject_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `Project` (`ProjectID`),
  CONSTRAINT `UserProject_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`),
  CONSTRAINT `UserProject_ibfk_3` FOREIGN KEY (`RoleID`) REFERENCES `Role` (`RoleID`),
  CONSTRAINT `UserProject_ibfk_4` FOREIGN KEY (`StateID`) REFERENCES `UserProjectState` (`StateID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `UserProjectState`
--

DROP TABLE IF EXISTS `UserProjectState`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UserProjectState` (
  `StateID` int(11) NOT NULL AUTO_INCREMENT,
  `StateDescriptionText` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`StateID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-24 14:20:51
