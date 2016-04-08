<?php

	function getProjects($query) {
		$results = [];
		$row = $query->fetchAll();
		print_r($row);
		foreach($row as $data) {
			$ProjectID = $data['ProjectID'];
			$GroupName = $data['ProjectName'];
			$Description = $data['Description'];
			$DateCreated = $data['DateCreated'];
			$Notification = $data['Notification'];
			$project = array("ProjectID"=>$ProjectID, "GroupName"=>$GroupName, "Description"=>$Description, "DateCreated"=>$DateCreated, "Notification"=>$Notification);
			array_push($results,$project);
		}
		$resultSize =  count($results);
		$results['projects'] = $results;
		for($i = 0; $i < $resultSize; $i++) {
			$temp = (string)$i;
			unset($results[$temp]);
		}
		return $results;
	}

	function getProjectByID($query) {
		$data = $query->fetchAll();
		//print_r($data);
		$row = $data[0];
		$ProjectID = $row['ProjectID'];
		$GroupName = $row['ProjectName'];
		$Description = $row['Description'];
		$DateCreated = $row['DateCreated'];
		$Users = array();
		foreach($data as $row) {
			$user = array();
			$user['username'] = $row['UserName'];
			$user['role'] = $row['RoleName'];
			array_push($Users, $user);
			
		}
		$result = array("ProjectID"=>$ProjectID, "GroupName"=>$GroupName, "Description"=>$Description, "DateCreated"=>$DateCreated, "Users"=>$Users);
		$project = array();
		$project['project'] = $result;
		return $project;
	}

?>