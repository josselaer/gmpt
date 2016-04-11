<?php
	function getMeetings($query) {
		$results = [];
		$row = $query->fetchAll();
		foreach($row as $data) {
			$MeetingID = $data['MeetingID'];
			$GroupName = $data['ProjectName'];
			$MeetingDescription = $data['MeetingDescription'];
			$LocationName = $data['LocationName'];
			$MeetingDate = $data['MeetingDate'];
			$StartTime = $data['StartTime'];
			$EndTime = $data['EndTime'];

			$meeting = array("MeetingID"=>$MeetingID,"GroupName"=>$ProjectName, "MeetingDescription"=>$MeetingDescription, "MeetingDate"=>$MeetingDate, "StartTime"=>$StartTime, "EndTime"=>$EndTime);
			array_push($results,$meeting);
		}
		$resultSize =  count($results);
		$results['meetings'] = $results;
		for($i = 0; $i < $resultSize; $i++) {
			$temp = (string)$i;
			unset($results[$temp]);
		}
		return $results;
	}

	function getUserIDsMeeting($query) {
		$results = [];
		$row = $query->fetchAll();
		foreach($row as $data) {
			$UserID = $data['UserID'];
			$UserIDs = array("UserID"=>$UserID);
			array_push($results,$UserIDs);
		}
		return $results;
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