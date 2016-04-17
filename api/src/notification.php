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

$app->put('/notifications/{project_id}/{notification_type}',
	function($request,$response,$args) {
		$db = $this->GMPT;
		$userID = (int)$request->getAttribute('UserID');
		$projectID = (int)$request->getAttribute('project_id');
		$notificationType = $request->getAttribute('notification_type');
		echo $notificationType;
		$query = $db->prepare("CALL UpdateNotification(?,?,?);");
		$query->bindParam(1,$userID, PDO::PARAM_INT);
		$query->bindParam(2,$projectID, PDO::PARAM_INT);
		$query->bindParam(3,$notificationType, PDO::PARAM_STR);
		$result = $query->execute();
		if ($result) {
			$response = $response->withStatus(200);
		}
		else {
			print_r($query->errorInfo());
			$response = $response->withStatus(400);
		}
		unset($query);	
	
		return $response;
	}
)->add($validateSession);