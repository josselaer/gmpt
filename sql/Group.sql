USE GMPT;

DROP TABLE IF EXISTS Groups;
CREATE TABLE IF NOT EXISTS Groups
(
	groupID 	int NOT NULL AUTO_INCREMENT,
	groupName 	varchar(50),
	description varchar(300),
	meetingID INT,
	PRIMARY KEY 	(groupID) 
);
