<?php

$app->post('/attendance/{meeting_id}', function($request, $response,$args){
		$userID = (int)$request->getAttribute('UserID');
		$meetingID = (int)$request->getAttribute('meeting_id');
		$db = $this->GMPT;
		$query = $db->prepare("CALL CreateAttendance (?,?)");
		$query->bindValue(1, $userID, PDO::PARAM_STR);
		$query->bindValue(2, $meetingID, PDO::PARAM_STR);
		$result = $query->execute();
		if ($result) {
			$response = $response->getBody()->write(json_encode(array("success"=>"true")));
		}
		else {
			$response = $response->withStatus(400);
		}
		return $response;
})->add($validateSession);