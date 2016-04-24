DELIMITER //
CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `SendMessage`(IN UserIDVal INT, IN ProjectIDVal INT, IN MessageTextVal VARCHAR(255), IN AnonymousIndicatorVal BOOL)
BEGIN
	INSERT INTO Message(UserID, ProjectID, MessageText, AnonymousIndicator)
    VALUES (UserIDVal, ProjectIDVal, MessageTextVal, AnonymousIndicatorVal);
END //

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetMessages`(IN ProjectIDVal INT, OUT ReturnValues)
BEGIN
	SET ReturnValues =  (SELECT * FROM Message WHERE ProjectID = ProjectIDVal);

END //

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetUnreadMessages`(IN UserIDVal INT, MessageRoomIDVal INT)
BEGIN
	SELECT count(*) FROM MessageRoom NATURAL JOIN MessageReceipt NATURAL JOIN Message
    WHERE UserID=UserIDVal AND MessageReceipt.LastRead<Message.SendTime;
END

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `SetChatLastRead`(IN UserIDVal INT, MessageRoomIDVal INT)
BEGIN
	INSERT INTO MessageReceipt (UserID, MessageRoomID,LastRead)
    VALUES (UserIDVal, MessageRoomIDVal, NOW()) 
    ON DUPLICATE KEY UPDATE LastRead=NOW();
	SELECT "True" AS status;
END