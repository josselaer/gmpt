DELIMITER //

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `ValidateUser`(IN UserNameVal VARCHAR(25), IN PasswordVal VARCHAR(255))
    DETERMINISTIC
    COMMENT 'Validates UserName and Password'
BEGIN

DECLARE ReturnUserID INT;
DECLARE ReturnName INT;
DECLARE ReturnToken VARCHAR(36);

IF EXISTS (SELECT UserName as name, UserID as userID FROM User WHERE UserName = UserNameVal AND PasswordHash = PasswordVal)
THEN
	SET 	ReturnUserID = (SELECT 	UserID
							FROM 	User 
							WHERE 	UserName = UserNameVal AND PasswordHash = PasswordVal);
							SET 	ReturnToken = UUID();
	SET 	ReturnName =   (SELECT 	UserName
							FROM 	User 
							WHERE 	UserName = UserNameVal AND PasswordHash = PasswordVal);
	CALL CreateSession(ReturnUserID, ReturnToken);

	SELECT ReturnUserID as userID, ReturnToken AS token, ReturnName as name;
END IF;
END //


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
END //

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateUser`(IN UserNameVal VARCHAR(25), PasswordHashVal VARCHAR(255), FirstNameVal VARCHAR(25), LastNameVal VARCHAR(25), EmailVal VARCHAR(25))
BEGIN
	INSERT INTO User(UserName, PasswordHash, FirstName, LastName, Email)
    VALUES (UserNameVal, PasswordHashVal, FirstNameVal, LastNameVal, EmailVal);
    SELECT "True" AS status;
END //

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `UpdateUser`(IN UserNameVal VARCHAR(25), PasswordHashVal VARCHAR(255), FirstNameVal VARCHAR(25), LastNameVal VARCHAR(25),SaltVal VARCHAR(10), EmailVal VARCHAR(25), Token VARCHAR(25))
BEGIN
	UPDATE User
    SET UserName = UserNameVal, PasswordHash = PasswordHashVal, FirstName = FirstNameVal, LastName =  LastNameVal, Salt =  SaltVal
    WHERE Username = Token;
    SELECT "True" AS status;
END

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateSession`(IN UserIDVal VARCHAR(25), INOUT ReturnToken VARCHAR(36))
    DETERMINISTIC
    COMMENT 'Validates UserName and Password'
BEGIN

DECLARE ReturnToken VARCHAR(36);
SET ReturnToken = UUID();
	
INSERT INTO Session(UserID, SessionID, LoginTimeStamp,  ExpirationTimeStamp)
VALUES (UserIDVal, ReturnToken, NOW(), DATE_ADD(NOW(),INTERVAL 5 MINUTE));
SELECT ReturnToken;

END //

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CloseSession`(IN Token VARCHAR(36))
BEGIN
	UPDATE	Session
	SET 	LogoutTimestamp = NOW(), ActiveState = False
	WHERE 	SessionID = Token;
	SELECT 	"True" as status;
END //