CREATE DATABASE IF NOT EXISTS GMPT;

USE GMPT;

CREATE TABLE Users(
	userID INT NOT NULL auto_increment,
	userName VARCHAR(255) NOT NULL,
	password VARCHAR(255) NOT NULL,
	salt VARCHAR(255) NOT NULL,
	firstName VARCHAR(255) NOT NULL,
	lastName VARCHAR(255) NOT NULL,
	email VARCHAR(255) NOT NULL,
	moderator TINYINT(1),
	PRIMARY KEY(userID)
);

CREATE TABLE Groups(
	groupID INT NOT NULL auto_increment,
	groupName VARCHAR(100) NOT NULL,
	description VARCHAR(200),
	userID INT NOT NULL,
	PRIMARY KEY (groupID),
	FOREIGN KEY (userID) REFERENCES Users(userID)
);

CREATE TABLE Meetings(
	meetingID INT NOT NULL auto_increment,
	topic VARCHAR(1000),
	date DATE,
	description VARCHAR(5000),
	location VARCHAR(200),
	startTime TIME,
	endTime TIME,
	groupID INT,
	PRIMARY KEY (meetingID),
	FOREIGN KEY (groupID) REFERENCES Groups(groupID)
);

CREATE TABLE Attendance(
	attendanceID INT NOT NULL auto_increment,
	meetingID INT NOT NULL,
	userID INT NOT NULL,
	attended TINYINT(1),
	PRIMARY KEY (attendanceID),
	FOREIGN KEY (meetingID) REFERENCES Meetings(meetingID),
	FOREIGN KEY (userID) REFERENCES Users(userID)
);

CREATE TABLE Messages(
	messageID INT NOT NULL auto_increment,
	userID INT NOT NULL,
	groupID INT NOT NULL,
	text VARCHAR(2000) NOT NULL,
	anonymous TINYINT(1),
	timeDate DATETIME,
	flag TINYINT(1),
	PRIMARY KEY (messageID),
	FOREIGN KEY (userID) REFERENCES Users(userID),
	FOREIGN KEY (groupID) REFERENCES Groups(groupID)
);
