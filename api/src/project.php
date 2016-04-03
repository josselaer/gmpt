<?php

	function getProjects($query) {
		$results = [];
		$row = $query->fetchAll();
		foreach($row as $data) {
			$ProjectID = $data['ProjectID'];
			$GroupName = $data['GroupName'];
			$Description = $data['Description'];
			$DateCreated = $data['DateCreated'];
			$project = array("ProjectID"=>$ProjectID, "GroupName"=>$GroupName, "Description"=>$Description, "DateCreated"=>$DateCreated);
			array_push($results,$project);
		}
		return $results;
	}

	function getProjectByID($query) {
		$row = $query->fetchAll();
		$data = $row[0];
		$ProjectID = $data['ProjectID'];
		$GroupName = $data['GroupName'];
		$Description = $data['Description'];
		$DateCreated = $data['DateCreated'];
		$result = array("ProjectID"=>$ProjectID, "GroupName"=>$GroupName, "Description"=>$Description, "DateCreated"=>$DateCreated);
		return $result;
	}

?>