<?php
	function getMeetings() {
		$db = $this->GMPT;
		return $query = $db->query('select * from Meetings');
	}

	function getMeetingsByGroup($groupID) {
		$db = $this->GMPT;
		return $query = $db->query("SELECT * FROM Meetings WHERE groupID = '$groupID'");

	}


?>