CREATE DATABASE  IF NOT EXISTS `GMPT` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `GMPT`;
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
-- Dumping routines for database 'GMPT'
--
/*!50003 DROP PROCEDURE IF EXISTS `AddUserToProject` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `AddUserToProject`(IN ProjectIDVal INT, UserIDVal INT, RoleVal VARCHAR(25))
BEGIN
	DECLARE RoleIDVal INT;
	SET RoleIDVal = (SELECT RoleID FROM Role WHERE RoleName=RoleVal);
    INSERT INTO UserProject (RoleID, UserID, ProjectID, StateID) VALUES (RoleIDVal, UserIDVal, ProjectIDVal, 3);
    SELECT TRUE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `AutocompleteUserEmail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `AutocompleteUserEmail`(IN Term VARCHAR(25))
BEGIN
	SELECT Email FROM User WHERE Email LIKE Term;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CloseSession` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CloseSession`(IN Token VARCHAR(36))
BEGIN
	UPDATE	Session
	SET 	LogoutTimestamp = NOW(), ActiveState = False
	WHERE 	SessionID = Token;
	SELECT 	"True" as status;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CreateAttendance` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateAttendance`(IN UserIDVal INT, MeetingIDVal INT)
BEGIN
	INSERT INTO Attendance(UserID, MeetingID, CheckInTime)
	VALUES (UserIDVal, MeetingIDVal, NOW());
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CreateMeeting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateMeeting`(IN ProjectIDVal INT, DescriptionVal VARCHAR(50), MeetingDateVal DATE, LocationNameVal VARCHAR(32), StartTimeVal TIME, EndTimeVal TIME)
BEGIN
	INSERT INTO Meeting (ProjectID, MeetingDescription, MeetingDate, LocationName, StartTime, EndTime) 
    VALUES (ProjectIDVal, DescriptionVal, MeetingDateVal, LocationNameVal, StartTimeVal, EndTimeVal);
    SELECT MeetingID FROM Meeting WHERE MeetingDescription=DescriptionVal ORDER BY MeetingID DESC LIMIT 1;
    CALL CreateNotification2(ProjectIDVal, "Meeting");
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CreateMessage` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateMessage`(IN UserIDVal INT, MessageRoomIDVal INT, MessageTextVal VARCHAR(255), AnonymousIndicatorVal BOOL)
BEGIN
	INSERT INTO Message (UserID, MessageRoomID, MessageText, AnonymousIndicator, FlagIndicator, SendTime) 
    VALUES (UserIDVal, MessageRoomIDVal, MessageTextVal, AnonymousIndicatorVal, False, NOW());
    CALL CreateNotification3(UserIDVal, (SELECT ProjectID From MessageRoom WHERE MessageRoomID = MessageRoomIDVal), "Message");
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CreateNotification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateNotification`(IN ProjectIDVal INT, NotificationTypeVal VARCHAR(25))
BEGIN
	INSERT INTO Notification(UserID, ProjectID, NotificationTypeID)
		SELECT	UserID, ProjectID, (SELECT NotificationTypeID FROM NotificationType WHERE NotificationTypeDescription = NotificationTypeVal)
        FROM 	UserProject
        WHERE 	ProjectID = ProjectIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CreateNotification2` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateNotification2`(IN ProjectIDVal INT, NotificationTypeVal VARCHAR(25))
BEGIN
	INSERT INTO Notification(UserID, ProjectID, NotificationTypeID)
		SELECT	UserID, ProjectID, (SELECT NotificationTypeID FROM NotificationType WHERE NotificationTypeDescription = NotificationTypeVal)
        FROM 	UserProject
        WHERE 	ProjectID = ProjectIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CreateNotification3` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateNotification3`(IN UserIDVal INT, ProjectIDVal INT, NotificationTypeVal VARCHAR(25))
BEGIN
	INSERT INTO Notification(UserID, ProjectID, NotificationTypeID)
		SELECT	UserID, ProjectID, (SELECT NotificationTypeID FROM NotificationType WHERE NotificationTypeDescription = NotificationTypeVal)
        FROM 	UserProject
        WHERE 	ProjectID = ProjectIDVal AND UserID <> UserIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CreateProject` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateProject`(IN ProjectNameVal VARCHAR(32), DescriptionVal VARCHAR(255))
BEGIN
	INSERT INTO Project (ProjectName, Description, DateCreated) 
    VALUES (ProjectNameVal, DescriptionVal, NOW());
    INSERT INTO MessageRoom (ProjectID, CreationDate) VALUES ((  SELECT ProjectID FROM Project WHERE ProjectName=ProjectNameVal AND Description=DescriptionVal ORDER BY ProjectID DESC LIMIT 1), NOW());
    SELECT ProjectID FROM Project WHERE ProjectName=ProjectNameVal AND Description=DescriptionVal ORDER BY ProjectID DESC LIMIT 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CreateSession` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateSession`(IN UserIDVal VARCHAR(25), INOUT ReturnToken VARCHAR(36))
    DETERMINISTIC
    COMMENT 'Validates UserName and Password'
BEGIN

SET ReturnToken = UUID();
	
INSERT INTO Session(UserID, SessionID, LoginTimeStamp,  ExpirationTimeStamp)
VALUES (UserIDVal, ReturnToken, NOW(), DATE_ADD(NOW(),INTERVAL 5 MINUTE));

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CreateUnregisteredUser` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateUnregisteredUser`(IN RegistrationToken VARCHAR(25), EmailVal VARCHAR(25))
BEGIN
	INSERT INTO User(UserName, PasswordHash, FirstName, LastName, Salt, Email)
    VALUES (RegistrationToken, RegistrationToken,"1", "1", "1", EmailVal);
    SELECT "True" AS status;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CreateUser` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateUser`(IN UserNameVal VARCHAR(25), PasswordHashVal VARCHAR(255), FirstNameVal VARCHAR(25), LastNameVal VARCHAR(25), SaltVal VARCHAR(10), EmailVal VARCHAR(25))
BEGIN
	INSERT INTO User(UserName, PasswordHash, FirstName, LastName, Salt, Email)
    VALUES (UserNameVal, PasswordHashVal, FirstNameVal, LastNameVal, SaltVal, EmailVal);
    SELECT "True" AS status;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `DeleteMeeting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `DeleteMeeting`(IN MeetingIDVal INT)
BEGIN
	DELETE FROM Attendance WHERE MeetingID = MeetingIDVal;
	DELETE FROM Meeting WHERE MeetingID = MeetingIDVal;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `DeleteUserProject` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `DeleteUserProject`(IN UserIDVal INT, ProjectIDVal INT)
BEGIN
	DELETE FROM UserProject
	WHERE ProjectID = ProjectIDVal and UserID = UserIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `EditMeeting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `EditMeeting`(IN MeetingIDVal INT, DescriptionVal VARCHAR(50), MeetingDateVal DATE, LocationNameVal VARCHAR(32), StartTimeVal TIME, EndTimeVal TIME)
BEGIN
	UPDATE Meeting SET MeetingDescription = DescriptionVal, MeetingDate = MeetingDateVal, LocationName = LocationNameVal, StartTime = StartTimeVal, EndTime = EndTimeVal
	WHERE MeetingID = MeetingIDVal;
    SELECT MeetingID FROM Meeting WHERE MeetingID=MeetingIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetAnonymousMessages` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetAnonymousMessages`(IN MessageRoomIDVal INT, UserIDVal INT, ProjectIDVal INT)
BEGIN
	CASE
		WHEN 
			(
            SELECT  up.RoleID
			FROM	UserProject up
			WHERE 	up.UserID = UserIDVal AND up.ProjectID = ProjectIDVal
			)
            IN (
			SELECT	rp.RoleID
			FROM	RolePermission rp
				INNER JOIN	Permission p
				ON			p.PermissionID = rp.PermissionID
			WHERE p.Name = "Chat Read"
            ) 
		THEN
			SELECT u.Username as sender, m.MessageText as text, m.AnonymousIndicator as anonymous, m.FlagIndicator as flag, m.SendTime as timeDate
			FROM MessageRoom mr
				LEFT JOIN Message m 
				ON m.MessageRoomID = mr.MessageRoomID
				LEFT JOIN User u
				ON u.UserID = m.UserID
			WHERE mr.MessageRoomID = MessageRoomIDVal
			ORDER BY m.SendTime DESC;
		ELSE
			BEGIN
				(SELECT		"Anonymous" as sender, m.MessageText as text, m.AnonymousIndicator as anonymous, m.FlagIndicator as flag, m.SendTime as timeDate
				FROM 		MessageRoom mr
					LEFT JOIN	Message m 
					ON 			m.MessageRoomID = mr.MessageRoomID
					LEFT JOIN 	User u
					ON 			u.UserID = m.UserID
				WHERE 		mr.MessageRoomID = MessageRoomIDVal AND AnonymousIndicator = TRUE)
				UNION	DISTINCT
				(SELECT		u.UserName as sender, m.MessageText as text, m.AnonymousIndicator as anonymous, m.FlagIndicator as flag, m.SendTime as timeDate
				FROM 		MessageRoom mr
					LEFT JOIN	Message m 
					ON 			m.MessageRoomID = mr.MessageRoomID
					LEFT JOIN 	User u
					ON 			u.UserID = m.UserID
				WHERE 		mr.MessageRoomID = MessageRoomIDVal AND AnonymousIndicator = FALSE)
				ORDER BY timeDate DESC;
            END;
	END CASE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetAttendance` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetAttendance`(IN MeetingIDVal INT, UserIDVal INT)
BEGIN
	SELECT MeetingID, UserID, AttendanceIndicator FROM Attendance 
	WHERE UserID = UserIDVal AND MeetingID=MeetingIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetAttendanceRate` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetAttendanceRate`(IN ProjectIDVal INT)
BEGIN
	DECLARE NumberOfMeetings DECIMAL(7,2);
    SET NumberOfMeetings = (SELECT count(*)
							FROM Meeting m
							WHERE m.ProjectID = 76
							);
	SELECT u.UserName AS username, ((CAST(count(a.CheckInTime) AS DECIMAL(7,2))/NumberOfMeetings) * 100) as attendanceRate
	FROM	UserProject up
		LEFT OUTER JOIN 			Meeting m
		ON		  					m.ProjectID = up.ProjectID
		LEFT OUTER JOIN 			User u
		ON							u.UserID = up.UserID
		LEFT OUTER JOIN 			Role r
		ON							r.RoleID = up.RoleID
		LEFT OUTER JOIN 			Attendance a
		ON							a.MeetingID = m.MeetingID
	WHERE	up.ProjectID = ProjectIDVal AND r.RoleName <> "Teacher" AND m.MeetingDate < NOW() AND m.StartTime < NOW()
	GROUP BY up.UserID;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetEmailByUserID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetEmailByUserID`(IN UserIDVal INT)
BEGIN
	SELECT Email FROM User WHERE UserID=UserIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetLastMeeting` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetLastMeeting`(IN ProjectIDVal INT)
BEGIN
	SELECT 	m.MeetingID, m.MeetingDescription, m.MeetingDate, m.StartTime
    FROM 	Attendance a RIGHT JOIN Meeting m ON m.MeetingID=a.MeetingID
    WHERE 	m.ProjectID=ProjectIDVal AND a.CheckInTime is NULL
    ORDER BY m.MeetingDate DESC LIMIT 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetLoginCount` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetLoginCount`(IN ProjectIDVal INT)
BEGIN
	SELECT	u.UserName as username, DATE(s.LoginTimestamp) as date, COUNT(DISTINCT s.SessionID) as numOfLogins
	FROM	User u
		INNER JOIN	Session s
		ON			s.UserID  = u.UserID
		INNER JOIN 	UserProject up
		ON			up.UserID = u.UserID
	WHERE ProjectID = ProjectIDVal 
    GROUP BY Date(s.LoginTimestamp)
    ORDER BY u.UserName, s.LoginTimestamp DESC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetMeetingAttendance` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetMeetingAttendance`(IN ProjectIDVal INT)
BEGIN
	SELECT	m.MeetingID, m.StartTime, m.EndTime, MeetingDate, u.UserName, a.CheckInTime
	FROM	Meeting m
		CROSS JOIN 			UserProject up
		ON					up.ProjectID = m.ProjectID
		INNER JOIN 			Role r
		ON					r.RoleID = up.RoleID
		INNER JOIN 			User u
		ON					u.UserID = up.UserID
		LEFT OUTER JOIN 	Attendance a
		ON					a.MeetingID = m.MeetingID
	WHERE 	m.ProjectID = ProjectIDVal AND r.RoleName <> "Teacher"
	ORDER BY m.MeetingDate;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetMeetingByID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetMeetingByID`(IN MeetingIDVal INT)
BEGIN
	SELECT ProjectName, MeetingDescription, LocationName, MeetingDate, StartTime, EndTime
	FROM Project NATURAL JOIN Meeting 
	WHERE MeetingID=MeetingIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetMeetings` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetMeetings`(IN ProjectIDVal INT)
BEGIN
	SELECT 	up.UserID, u.UserName, p.ProjectName, m.*, a.CheckInTime
	FROM 	Meeting m
		INNER JOIN 			UserProject up
		ON					up.ProjectID = m.ProjectID
		INNER JOIN 			Project p
		ON 					m.ProjectID = p.ProjectID
        INNER JOIN			User u
        ON					u.UserID = up.UserID
		LEFT OUTER JOIN 	Attendance a
		ON 					m.MeetingID = a.MeetingID AND a.UserID = up.UserID
	WHERE	m.ProjectID = ProjectIDVal
	ORDER BY m.MeetingID DESC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetMessageRoomID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetMessageRoomID`(IN ProjectIDVal INT)
BEGIN
	SELECT MessageRoomID 
    FROM MessageRoom 
    WHERE ProjectID = ProjectIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetMessages` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetMessages`(IN MessageRoomIDVal INT, UserIDVal INT, ProjectIDVal INT)
BEGIN
	CASE
		WHEN 
			(
            SELECT  up.RoleID
			FROM	UserProject up
			WHERE 	up.UserID = UserIDVal AND up.ProjectID = ProjectIDVal
			)
            IN (
			SELECT	rp.RoleID
			FROM	RolePermission rp
				INNER JOIN	Permission p
				ON			p.PermissionID = rp.PermissionID
			WHERE p.Name = "Chat Read"
            ) 
		THEN
			SELECT u.Username as sender, m.MessageText as text, m.AnonymousIndicator as anonymous, m.FlagIndicator as flag, DATE_FORMAT(m.SendTime,'%Y-%m-%dT%TZ') as timeDate
			FROM MessageRoom mr
				LEFT JOIN Message m 
				ON m.MessageRoomID = mr.MessageRoomID
				LEFT JOIN User u
				ON u.UserID = m.UserID
			WHERE mr.MessageRoomID = MessageRoomIDVal
			ORDER BY m.SendTime ASC;
            CALL UpdateNotification(UserIDVal, ProjectIDVal, "Message");
		ELSE
			BEGIN
				(SELECT		"Anonymous" as sender, m.MessageText as text, m.AnonymousIndicator as anonymous, m.FlagIndicator as flag, DATE_FORMAT(m.SendTime,'%Y-%m-%dT%TZ') as timeDate
				FROM 		MessageRoom mr
					LEFT JOIN	Message m 
					ON 			m.MessageRoomID = mr.MessageRoomID
					LEFT JOIN 	User u
					ON 			u.UserID = m.UserID
				WHERE 		mr.MessageRoomID = MessageRoomIDVal AND AnonymousIndicator = TRUE)
				UNION	DISTINCT
				(SELECT		u.UserName as sender, m.MessageText as text, m.AnonymousIndicator as anonymous, m.FlagIndicator as flag, DATE_FORMAT(m.SendTime,'%Y-%m-%dT%TZ') as timeDate
				FROM 		MessageRoom mr
					LEFT JOIN	Message m 
					ON 			m.MessageRoomID = mr.MessageRoomID
					LEFT JOIN 	User u
					ON 			u.UserID = m.UserID
				WHERE 		mr.MessageRoomID = MessageRoomIDVal AND AnonymousIndicator = FALSE)
				ORDER BY timeDate ASC;
                CALL UpdateNotification(UserIDVal, ProjectIDVal, "Message");
            END;
	END CASE;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetProjectByID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetProjectByID`(IN ProjectIDVal INT)
BEGIN
	SELECT p.ProjectID, p.ProjectName, p.Description, p.DateCreated, u.UserName, r.RoleName
    FROM UserProject up
		INNER JOIN 		User u
        ON				u.UserID = up.UserID
		INNER JOIN 		Project p
        ON				p.ProjectID = up.ProjectID
		INNER JOIN 		Role r
        ON				r.RoleID = up.RoleID
    WHERE up.ProjectID = ProjectIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetProjectNotifications` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetProjectNotifications`(IN ProjectIDVal INT, UserIDVal INT)
BEGIN
	SELECT 	nt.NotificationTypeDescription as notificationType, count(n.NotificationID) as count
    FROM 	NotificationType nt
		LEFT OUTER JOIN (SELECT *
						 FROM	Notification
                         WHERE	ProjectID = ProjectIDVal AND UserID = UserIDVal AND ReadIndicator = False
						) n
        ON			nt.NotificationTypeID = n.NotificationTypeID 
    GROUP BY nt.NotificationTypeDescription;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetProjects` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetProjects`(IN UserIDVal INT)
BEGIN
	SELECT	p.ProjectName, p.ProjectID, p.Description, p.DateCreated, r.RoleName, count(n.NotificationID) as Notification
    FROM 	UserProject up
		INNER JOIN		Project p
		ON 				up.ProjectID = p.ProjectID
        INNER JOIN		Role r
        ON				r.RoleID = up.RoleID
		LEFT OUTER JOIN Notification n
		ON 				up.ProjectID = n.ProjectID AND up.UserID = n.UserID AND ReadIndicator = FALSE
	WHERE	up.UserID = UserIDVal 
    GROUP BY p.ProjectName, p.ProjectID, p.Description, p.DateCreated;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetProjectTotalMeetings` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetProjectTotalMeetings`(IN ProjectIDVal INT)
BEGIN
	SELECT	count(*) as totalNumOfMeetings
    FROM	Project p
		INNER JOIN 	Meeting m
        ON			p.ProjectID = m.ProjectID
    WHERE p.ProjectID = ProjectIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetProjectTotalMessages` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetProjectTotalMessages`(IN ProjectIDVal INT)
BEGIN
	SELECT	count(*) as totalNumOfChatMessages
	FROM 	MessageRoom mr
        INNER JOIN 	Message m
        ON			m.MessageRoomID = mr.MessageRoomID
	WHERE mr.ProjectID = ProjectIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetSalt` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetSalt`(IN UserNameVal VARCHAR(25))
BEGIN
	SELECT Salt FROM User WHERE UserName=UserNameVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetUnreadMessages` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetUnreadMessages`(IN UserIDVal INT, MessageRoomIDVal INT)
BEGIN
	SELECT count(*) FROM MessageRoom NATURAL JOIN MessageReceipt NATURAL JOIN Message
    WHERE UserID=UserIDVal AND MessageReceipt.LastRead<Message.SendTime;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetUserIDByEmail` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetUserIDByEmail`(IN EmailVal VARCHAR(25))
BEGIN
	SELECT UserID FROM User WHERE Email=EmailVal;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getUserIDsByProject` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `getUserIDsByProject`(IN ProjectIDVal INT)
BEGIN
	SELECT UserID FROM User NATURAL JOIN UserProject NATURAL JOIN Project
	WHERE ProjectID = ProjectIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetUserIDsByProjectID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetUserIDsByProjectID`(IN ProjectIDVal INT)
BEGIN
	SELECT FirstName, UserID FROM UserProject NATURAL JOIN User
    WHERE ProjectID=ProjectIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `SetChatLastRead` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `SetChatLastRead`(IN UserIDVal INT, MessageRoomIDVal INT)
BEGIN
	INSERT INTO MessageReceipt (UserID, MessageRoomID,LastRead)
    VALUES (UserIDVal, MessageRoomIDVal, NOW()) 
    ON DUPLICATE KEY UPDATE LastRead=NOW();
	SELECT "True" AS status;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `UpdateAttendance` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `UpdateAttendance`(IN UserIDVal INT, MeetingIDVal INT)
BEGIN
	UPDATE Attendance
    SET CheckInTime = NOW()
    WHERE UserID = UserIDVal AND MeetingID = MeetingIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `UpdateNotification` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `UpdateNotification`(IN UserIDVal INT, ProjectIDVal INT, NotificationTypeVal VARCHAR(25))
BEGIN
	UPDATE	Notification
	SET		ReadIndicator = TRUE
	WHERE 	UserID = UserIDVal AND ProjectID = ProjectIDVal AND NotificationTypeID = (SELECT NotificationTypeID FROM NotificationType WHERE NotificationTypeDescription = NotificationTypeVal); 
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `UpdateProject` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `UpdateProject`(IN ProjectIDVal INT, ProjectNameVal VARCHAR(32), ProjectDescriptionVal VARCHAR(255))
BEGIN
	UPDATE Project
    SET ProjectName = ProjectNameVal, Description = ProjectDescriptionVal
    WHERE ProjectID = ProjectIDVal;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `UpdateUser` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `UpdateUser`(IN UserNameVal VARCHAR(25), PasswordHashVal VARCHAR(255), FirstNameVal VARCHAR(25), LastNameVal VARCHAR(25),SaltVal VARCHAR(10), EmailVal VARCHAR(25), Token VARCHAR(25))
BEGIN
	UPDATE User
    SET UserName = UserNameVal, PasswordHash = PasswordHashVal, FirstName = FirstNameVal, LastName =  LastNameVal, Salt =  SaltVal
    WHERE Username = Token;
    SELECT "True" AS status;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ValidateSession` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `ValidateSession`(IN SessionToken VARCHAR(36))
BEGIN
	DECLARE ReturnUserID INT;
    IF EXISTS (SELECT UserID FROM Session WHERE SessionID = SessionToken)
	THEN
		SET 	ReturnUserID = (SELECT 	UserID
								FROM 	Session
								WHERE 	SessionID = SessionToken);

		SELECT ReturnUserID as userID;
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ValidateUser` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `ValidateUser`(IN UserNameVal VARCHAR(25), IN PasswordVal VARCHAR(255))
    DETERMINISTIC
    COMMENT 'Validates UserName and Password'
BEGIN
DECLARE ReturnUserID INT;
DECLARE ReturnUserName VARCHAR(25);
DECLARE ReturnFirstName VARCHAR(25);
DECLARE ReturnLastName VARCHAR(25);
DECLARE ReturnToken VARCHAR(36);
DECLARE ReturnEmail VARCHAR(25);

IF EXISTS (SELECT UserName as name, UserID as userID FROM User WHERE UserName = UserNameVal AND PasswordHash = PasswordVal)
THEN
	SET 	ReturnUserID = (SELECT 	UserID
							FROM 	User 
							WHERE 	UserName = UserNameVal AND PasswordHash = PasswordVal);
							SET 	ReturnToken = UUID();
	SET 	ReturnUserName =   	(SELECT UserName
								FROM 	User 
								WHERE 	UserName = UserNameVal AND PasswordHash = PasswordVal);
	SET 	ReturnFirstName =   (SELECT FirstName
								 FROM 	User 
								 WHERE 	UserName = UserNameVal AND PasswordHash = PasswordVal);
	SET 	ReturnLastName =   	(SELECT LastName
								 FROM 	User 
								 WHERE 	UserName = UserNameVal AND PasswordHash = PasswordVal);
 	SET 	ReturnEmail =   	(SELECT Email
								 FROM 	User 
								 WHERE 	UserName = UserNameVal AND PasswordHash = PasswordVal);
	
	CALL CreateSession(ReturnUserID, ReturnToken);

	SELECT ReturnUserID as userID, ReturnToken AS token, ReturnUserName as username, ReturnFirstName as firstName, ReturnLastName as lastName, ReturnEmail as email;
END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-24 13:35:54
