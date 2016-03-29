<?php

	function getGroups($query) {
		$results = [];
		$row = $query->fetchAll();
		foreach($row as $data) {
			$GroupName = $data['GroupName'];
			$Description = $data['Description'];
			$DateCreated = $data['DateCreated'];
			$group = array("GroupName"=>$GroupName, "Description"=>$Description, "DateCreated"=>$DateCreated);
			array_push($results,$group);
		}
		return $results;
	}

	function getGroupsByID($query) {
		$row = $query->fetchAll();
		$data = $row[0];
		$GroupName = $data['GroupName'];
		$Description = $data['Description'];
		$DateCreated = $data['DateCreated'];
		$result = array("GroupName"=>$GroupName, "Description"=>$Description, "DateCreated"=>$DateCreated);
		return $result;
	}

?>