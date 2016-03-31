<?php
	function getMeetings($query) {
		$results = [];
		$row = $query->fetchAll();
		foreach($row as $data) {
			$MeetingID = $data['MeetingID'];
			$Topic = $data['Topic'];
			$Date = $data['Date'];
			$Description = $data['Description'];
			$Location = $data['Location'];
			$StartTime = $data['StartTime'];
			$EndTime = $data['EndTime'];
			$GroupID = $data['GroupID'];

			$meeting = array("MeetingID"=>$MeetingID, "Topic"=>$Topic, "Date"=>$Date, "Description"=>$Description,"Location"=>$Location,"StartTime"=>$StartTime,"EndTime"=>$EndTime,"GroupID"=>$GroupID);
			array_push($results,$meeting);
		}
		return $results;
	}

	function getMeetingsByGroup($query) {
		$row = $query->fetchAll();
		$results=[];
		foreach($row as $data) {
			$MeetingID = $data['MeetingID'];
			$Topic = $data['Topic'];
			$Date = $data['Date'];
			$Description = $data['Description'];
			$Location = $data['Location'];
			$StartTime = $data['StartTime'];
			$EndTime = $data['EndTime'];
			$GroupID = $data['GroupID'];

			$meeting = array("MeetingID"=>$MeetingID, "Topic"=>$Topic, "Date"=>$Date, "Description"=>$Description,"Location"=>$Location,"StartTime"=>$StartTime,"EndTime"=>$EndTime,"GroupID"=>$GroupID);
			array_push($results,$meeting);
		}
		return $results;

	}

	/*function getMeetingByMeetingID($meetingID) {
		$db = $this->GMPT;
		return $query = $db->query("SELECT * FROM Meetings WHERE meetingID = '$meetingID'");
	}*/

	function createMeeting($query) {
		return array("worked"=>true);
	}

?>