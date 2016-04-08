<?php

$app->get('/notifications/{project_id}',
	function($request,$response,$args) {
		$db = $this->GMPT;
		$userID = (int)$request->getAttribute('UserID');
		$projectID = (int)$request->getAttribute('project_id');
		$query = $db->prepare("CALL GetProjectNotifications(?,?)");
		$query->bindParam(1,$projectID, PDO::PARAM_INT);
		$query->bindParam(2,$userID, PDO::PARAM_INT);
		$result = $query->execute();
		if ($result) {
			$data = $query->fetchAll();
			$notifications = array();
			foreach ($data as $row) {
				$notifications[$row['notificationType']] = $row['count'];
			}
			$returnArray = array();
			$returnArray['notifications'] = $notifications;
			$response->write(json_encode($returnArray));
		}
		else {
			$response = $response->withStatus(400);
		}
		unset($query);	
	
		return $response;
	}
)->add($validateSession);