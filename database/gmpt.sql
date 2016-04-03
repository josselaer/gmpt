DROP DATABASE IF EXISTS GMPT;
CREATE DATABASE IF NOT EXISTS GMPT;
USE GMPT;


CREATE TABLE IF NOT EXISTS User(
	UserID 				INT NOT NULL AUTO_INCREMENT,
	UserName 			VARCHAR(25) NOT NULL UNIQUE,
	PasswordHash 		VARCHAR(255) NOT NULL,
	Salt 				VARCHAR(10) NOT NULL,
	FirstName 			VARCHAR(25) NOT NULL,
	LastName 			VARCHAR(25) NOT NULL,
	Email 				VARCHAR(25) NOT NULL UNIQUE,
	RegisterTimeStamp 	TIMESTAMP DEFAULT NOW(),
	PRIMARY KEY(UserID)
);

CREATE TABLE IF NOT EXISTS Project(
	ProjectID 		INT NOT NULL AUTO_INCREMENT,
	GroupName 		VARCHAR(32) NOT NULL,
	Description 	VARCHAR(255),
	DateCreated 	DATE,
	PRIMARY KEY (ProjectID)
);

CREATE TABLE IF NOT EXISTS Session(
	SessionID 			VARCHAR(36) NOT NULL,
	UserID 				INT NOT NULL,
	ExpirationTimestamp TIMESTAMP,
	LoginTimestamp 		TIMESTAMP,
	LogoutTimestamp 	TIMESTAMP,
	ActiveState		BOOL,
	PRIMARY KEY(SessionID),
	FOREIGN KEY(UserID) REFERENCES User(UserID)
);

CREATE TABLE IF NOT EXISTS Role(
	RoleID 		INT NOT NULL,
	RoleName 	VARCHAR(25),
	PRIMARY KEY(RoleID)
);

CREATE TABLE IF NOT EXISTS Permission(
	PermissionID 	INT NOT NULL,
	Name 			VARCHAR(50),
	PRIMARY KEY (PermissionID)
);

CREATE TABLE IF NOT EXISTS UserProject(
	ProjectID 	INT NOT NULL,
	UserID 		INT NOT NULL,
	RoleID 		INT NOT NULL,
	PRIMARY KEY (ProjectID,UserID),
	FOREIGN KEY (ProjectID)REFERENCES Project(ProjectID),
	FOREIGN KEY (UserID)REFERENCES User(UserID),
	FOREIGN KEY (RoleID)REFERENCES Role(RoleID)
);

CREATE TABLE IF NOT EXISTS Meeting(
	MeetingID 			INT NOT NULL AUTO_INCREMENT,
    ProjectID				INT NOT NULL,
	MeetingDescription 	VARCHAR(50),
	MeetingDate 		DATE,
	LocationName 		VARCHAR(32),
	StartTime 			TIME,
	EndTime 			TIME,
	PRIMARY KEY (MeetingID),
	FOREIGN KEY (ProjectID) REFERENCES Project(ProjectID)
);

CREATE TABLE IF NOT EXISTS Attendance(
	MeetingID 			INT NOT NULL,
	UserID 				INT NOT NULL,
	AttendanceIndicator BOOL,
	PRIMARY KEY(MeetingID, UserID),
	FOREIGN KEY(MeetingID) REFERENCES Meeting(MeetingID),
	FOREIGN KEY(UserID) REFERENCES User(UserID)
);


CREATE TABLE IF NOT EXISTS Message(
	MessageID 			INT NOT NULL AUTO_INCREMENT,
	UserID 				INT NOT NULL,
	ProjectID 			INT NOT NULL,
	MessageText 		VARCHAR(255) NOT NULL,
	AnonymousIndicator 	BOOL,
	SendTime 			TIMESTAMP,
	FlagIndicator 		BOOL,
	PRIMARY KEY (MessageID),
	FOREIGN KEY (UserID) REFERENCES User(UserID),
	FOREIGN KEY (ProjectID) REFERENCES Project(ProjectID)
);

CREATE TABLE IF NOT EXISTS MessageReceipt(
	UserID 			INT NOT NULL,
	ProjectID 		INT NOT NULL,
	ReadIndicator 	BOOL,
	PRIMARY KEY(UserID, ProjectID),
	FOREIGN KEY(UserID) REFERENCES User(UserID),
	FOREIGN KEY(ProjectID) REFERENCES Project(ProjectID)

);

CREATE TABLE IF NOT EXISTS RolePermission(
	RoleID 			INT NOT NULL,
	PermissionID 	INT NOT NULL,
	PRIMARY KEY(RoleID, PermissionID),
	FOREIGN KEY(RoleID) REFERENCES Role(RoleID),
	FOREIGN KEY(PermissionID) REFERENCES Permission(PermissionID)
);

CREATE TABLE IF NOT EXISTS Roles(
	RoleID INT NOT NULL,
	Name VARCHAR(25),
	PRIMARY KEY (RoleID)
);

CREATE TABLE IF NOT EXISTS RolePermissions(
	RoleID INT NOT NULL,
	PermissionID INT NOT NULL,
	PRIMARY KEY (RoleID, PermissionID),
	FOREIGN KEY (RoleID) REFERENCES Roles(RoleID),
	FOREIGN KEY (PermissionID) REFERENCES Permssions(PermissionID)
);

CREATE TABLE IF NOT EXISTS Permissions(
	PermissionID INT NOT NULL,
	Name VARCHAR(50),
	PRIMARY KEY (PermissionID)
);

DELIMITER // 
	CREATE PROCEDURE 	`Register` (IN uName VARCHAR(255), pass VARCHAR(255), fName VARCHAR(255), lName VARCHAR(255), salt VARCHAR(255), mail VARCHAR(255))
	LANGUAGE SQL
	DETERMINISTIC
	SQL 				SECURITY DEFINER
	COMMENT 			'This procedure Registers a User and puts all his data in the Users table, hashing the password with salt added'
BEGIN
	INSERT INTO Users (UserName, Password, Salt, FirstName, LastName, Email, RegisterDate, LastLogin) VALUES (uName, pass, salt, fName,lName,mail, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
	
END//
DELIMITER ;

