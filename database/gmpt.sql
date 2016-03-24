DROP DATABASE IF EXISTS GMPT;

CREATE DATABASE IF NOT EXISTS GMPT;

USE GMPT; 


CREATE TABLE IF NOT EXISTS Users(
	UserID INT NOT NULL auto_increment,
	UserName VARCHAR(32) NOT NULL,
	Password VARCHAR(255) NOT NULL,
	Salt VARCHAR(10) NOT NULL,
	FirstName VARCHAR(32) NOT NULL,
	LastName VARCHAR(32) NOT NULL,
	Email VARCHAR(32) NOT NULL,
	RegisterDate TIMESTAMP,
	LastLogin TIMESTAMP,
	PRIMARY KEY(UserID)
);

CREATE TABLE IF NOT EXISTS Groups(
	GroupID INT NOT NULL auto_increment,
	GroupName VARCHAR(32) NOT NULL,
	Description VARCHAR(255),
	DateCreated DATE,
	PRIMARY KEY (GroupID)
);

CREATE TABLE IF NOT EXISTS Sessions(
	SessionID INT NOT NULL auto_increment,
	UserID INT NOT NULL,
	Experation DateTime,
	LoginDateTime DateTime,
	LogoutDateTime DateTime,
	PRIMARY KEY (SessionID),
	FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE IF NOT EXISTS Roles(
	RoleID INT NOT NULL,
	Name VARCHAR(25),
	PRIMARY KEY (RoleID)
);

CREATE TABLE IF NOT EXISTS Permissions(
	PermissionID INT NOT NULL,
	Name VARCHAR(50),
	PRIMARY KEY (PermissionID)
);

CREATE TABLE IF NOT EXISTS UserRole(
	GroupID INT NOT NULL,
	UserID INT NOT NULL,
	RoleID INT NOT NULL,
	PRIMARY KEY (GroupID,UserID),
	FOREIGN KEY (GroupID) REFERENCES Groups(GroupID),
	FOREIGN KEY (UserID) REFERENCES Users(UserID),
	FOREIGN KEY (RoleID) REFERENCES Roles(RoleID)
);

CREATE TABLE IF NOT EXISTS Meetings(
	MeetingID INT NOT NULL auto_increment,
	Topic VARCHAR(50),
	Date DATE,
	Description VARCHAR(255),
	Location VARCHAR(32),
	StartTime TIME,
	EndTime TIME,
	GroupID INT,
	PRIMARY KEY (MeetingID),
	FOREIGN KEY (GroupID) REFERENCES Groups(GroupID)
);

CREATE TABLE IF NOT EXISTS Attendance(
	AttendanceID INT NOT NULL auto_increment,
	MeetingID INT NOT NULL,
	UserID INT NOT NULL,
	Attended Bool,
	PRIMARY KEY (AttendanceID),
	FOREIGN KEY (MeetingID) REFERENCES Meetings(MeetingID),
	FOREIGN KEY (userID) REFERENCES Users(userID)
);


CREATE TABLE IF NOT EXISTS Messages(
	MessageID INT NOT NULL auto_increment,
	UserID INT NOT NULL,
	GroupID INT NOT NULL,
	Text VARCHAR(255) NOT NULL,
	Anonymous Bool,
	TimeSent TIMESTAMP,
	Flag Bool,
	PRIMARY KEY (MessageID),
	FOREIGN KEY (UserID) REFERENCES Users(UserID),
	FOREIGN KEY (GroupID) REFERENCES Groups(GroupID)
);

CREATE TABLE IF NOT EXISTS ReadReceipt(
	UserID INT NOT NULL,
	GroupID INT NOT NULL,
	LastRead TIMESTAMP,
	PRIMARY KEY (UserID, GroupID),
	FOREIGN KEY (UserID) REFERENCES Users(UserID),
	FOREIGN KEY (GroupID) REFERENCES Groups(GroupID)

);

CREATE TABLE IF NOT EXISTS RolePermissions(
	RoleID INT NOT NULL,
	PermissionID INT NOT NULL,
	PRIMARY KEY (RoleID, PermissionID),
	FOREIGN KEY (RoleID) REFERENCES Roles(RoleID),
	FOREIGN KEY (PermissionID) REFERENCES Permissions(PermissionID)
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
