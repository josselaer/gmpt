<?php
	function getMeetings($query) {
		$results = [];
		$meetingIDs = [];
		$Attendances = [];
		$row = $query->fetchAll();
		foreach($row as $data) {
			$meetingSize =  count($meetingIDs);
			$MeetingID = (int)$data['MeetingID'];
			if($meetingSize > 0) {
				if($meetingIDs[$meetingSize-1] != $MeetingID) {
					array_push($meetingIDs, $MeetingID);
				}
			}
			else {
				array_push($meetingIDs, $MeetingID);
			}

		}
		$userNum = count($row) / count($meetingIDs);
		$userCounter = 0;

		foreach($row as $data) {
			$UserName = $data['UserName'];
			$CheckInTime = $data['CheckInTime'];
			$Attendance = array("UserName"=>$UserName,"CheckInTime"=>$CheckInTime);
			array_push($Attendances, $Attendance);
			$userCounter++;
			if($userCounter == $userNum) {
				$MeetingID = (int)$data['MeetingID'];
				$GroupName = $data['ProjectName'];
				$ProjectID = (int)$data['ProjectID'];
				$MeetingDescription = $data['MeetingDescription'];
				$MeetingDate = $data['MeetingDate'];
				$LocationName = $data['LocationName'];
				$StartTime = $data['StartTime'];
				$EndTime = $data['EndTime'];
				$meeting = array("MeetingID"=>$MeetingID,"GroupName"=>$GroupName, "MeetingDescription"=>$MeetingDescription, "MeetingDate"=>$MeetingDate, "StartTime"=>$StartTime, "EndTime"=>$EndTime, "Attendances"=>$Attendances);
				array_push($results,$meeting);
				$Attendances = [];
				$userCounter = 0;
			}
		}
		
		//echo json_encode($results[1]);
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