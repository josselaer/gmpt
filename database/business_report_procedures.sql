USE GMPT;
DELIMITER //

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetAnonymousMessages`()
COMMENT 'Gets all messages that were sent anonymously'

BEGIN
	SELECT MessageText, SendTime FROM Message WHERE AnonymousIndicator = true;
END //

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetMeetingTimes`()
COMMENT 'Gets meeting date and times. Need to find average or analyze in info in some way'
BEGIN
	SELECT MeetingDate, StartTime, EndTime FROM Meeting;
END //

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetLoginTimes`()
COMMENT 'Gets login times to see when people use app. Need to find average or analyze in info in some way'
BEGIN
	SELECT LoginTimestamp FROM Session;
END //