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