<?php
	function getMeetings($userID) {
		$db = $this->GMPT;
		return $query = $db->query('select * from Meetings');
	}

	function getMeetingsByGroup($userID,$groupID) {
		$db = $this->GMPT;
		return $query = $db->query("SELECT * FROM Meetings WHERE groupID = '$groupID'");

	}

	function getMeetingByMeetingID($userID,$meetingID) {
		$db = $this->GMPT;
		return $query = $db->query("SELECT * FROM Meetings WHERE meetingID = '$meetingID'");
	}

	function createMeeting($userID,$topic,$groupName,$date,$description,$location,$startTime,$endTime) {
		$db = $this->GMPT;
		return $query=$db->query("INSERT INTO Meetings (topic,groupName,date,description,location,startTime,endTime) VALUES('$topic','$groupName','$date','$description','$location','$startTime','$endTime');");

	}
	
	function editMeetingByMeetingID($userID,$topic,$groupName,$date,$description,$location,$startTime,$endTime, $meetingID){
		$db = $this->GMPT;
		//call apropriate routine (not written yet)
		//$editMeetingQuery= $db->query("CALL ----");
		
	}
?>