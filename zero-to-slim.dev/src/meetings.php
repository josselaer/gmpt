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

	function createMeeting() {
		
	}

?>