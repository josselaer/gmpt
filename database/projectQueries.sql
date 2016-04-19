CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetProjects`(IN UserIDVal INT)
BEGIN
	SELECT ProjectName, ProjectID FROM Project NATURAL JOIN UserProject NATURAL JOIN User WHERE UserID=UserIDVal;
END

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetUserIDByEmail`(IN EmailVal VARCHAR(25))
BEGIN
	SELECT UserID FROM User WHERE Email=EmailVal;
END

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `CreateProject`(IN ProjectNameVal VARCHAR(32), DescriptionVal VARCHAR(255))
BEGIN
	INSERT INTO Project (ProjectName, Description, DateCreated) 
    VALUES (ProjectNameVal, DescriptionVal, NOW());
    SELECT ProjectID FROM Project WHERE ProjectName=ProjectNameVal AND Description=DescriptionVal;
END

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `AddUserToProject`(IN ProjectIDVal INT, UserIDVal INT, RoleVal VARCHAR(25))
BEGIN
	DECLARE RoleIDVal INT;
	SET RoleIDVal = (SELECT RoleID FROM Role WHERE RoleName=RoleVal);
    INSERT INTO UserProject (RoleID, UserID, ProjectID, StateID) VALUES (RoleIDVal, UserIDVal, ProjectIDVal, 3);
    SELECT TRUE;
END

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `GetProjectByID`(IN ProjectIDVal VARCHAR(25))
BEGIN
	SELECT ProjectName, Description, FirstName, LastName, UserID, RoleID 
    FROM User NATURAL JOIN UserProject NATURAL JOIN Project NATURAL JOIN Role
    WHERE ProjectID=ProjectIDVal;
END

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `AutocompleteUserEmail`(IN Term VARCHAR(25))
BEGIN
	SELECT Email FROM User WHERE Email LIKE Term;
END

CREATE DEFINER=`gmpt_master_user`@`%` PROCEDURE `RemoveUserFromProject`(IN ProjectIDVal INT, UserIDVal INT)
BEGIN
	DELETE FROM UserProject 
	WHERE UserID = UserIDVal 
	AND ProjectID=ProjectIDVal;
END


