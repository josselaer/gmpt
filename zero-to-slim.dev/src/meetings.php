<?php
	function getMeetings() {
		$db = $this->GMPT;
		return $query = $db->query('select * from Meetings');
	}

	function getMeetingsByGroup($groupID) {
		$db = $this->GMPT;
		return $query = $db->query("SELECT * FROM Meetings WHERE groupID = '$groupID'");

	}

	function getMeetingByMeetingID($meetingID) {
		$db = $this->GMPT;
		return $query = $db->query("SELECT * FROM Meetings WHERE meetingID = '$meetingID'");
	}

	function createMeeting($topic,$groupName,$date,$description,$location,$startTime,$endTime) {
		$db = $this->GMPT;
		return $query=$db->query("INSERT INTO Meetings (topic,groupName,date,description,location,startTime,endTime) VALUES('$topic','$groupName','$date','$description','$location','$startTime','$endTime');");

	}

?>