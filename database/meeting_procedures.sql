CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetMeetings`(IN ProjectIDVal INT)
BEGIN
	SELECT ProjectName, MeetingDescription, LocationName, MeetingDate, StartTime, EndTime FROM Project NATURAL JOIN Meeting WHERE ProjectID=ProjectIDVal;
END

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateMeeting`(IN ProjectIDVal INT, DescriptionVal VARCHAR(50), MeetingDateVal DATE, LocationNameVal VARCHAR(32), StartTimeVal TIME, EndTimeVal TIME)
BEGIN
	INSERT INTO Meeting (ProjectID, MeetingDescription, MeetingDate, LocationName, StartTime, EndTime) 
    VALUES (ProjectIDVal, DescriptionVal, MeetingDateVal, LocationNameVal, StartTimeVal, EndTimeVal);
    SELECT MeetingID FROM Meeting WHERE MeetingDescription=DescriptionVal ORDER BY MeetingID DESC LIMIT 1;
END

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `ChangeAttendace`(IN MeetingIDVal INT, UserIDVal INT, AttendedVal BOOL)
BEGIN
	INSERT INTO Attendance (MeetingID, UserID, AttendanceIndicator)
	VALUES (MeetingIDVal, UserIDVal, AttendedVal);
END

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetMeetingByID`(IN MeetingIDVal INT)
BEGIN
	SELECT ProjectName, MeetingDescription, LocationName, MeetingDate, StartTime, EndTime 
	FROM Project NATURAL JOIN Meeting 
	WHERE MeetingID=MeetingIDVal;
END