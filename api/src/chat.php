<?php 
$app->get('/messages/{project_id}', function($request,$response,$args) {
		$userID = (int)$request->getAttribute('UserID');
		$projectID = $request->getAttribute('project_id');
		$db=$this->GMPT;
		
		$stmt = $db->prepare("CALL GetMessageRoomID(?)");
        $stmt->bindParam(1, $projectID, PDO::PARAM_INT);
		$result = $stmt->execute();
		if ($result) {
			$messageRoomID = (int)$stmt->fetchAll()[0]["MessageRoomID"];
			unset($stmt);
			$stmt1 = $db->prepare("CALL GetMessages(?,?,?)");
			$stmt1->bindParam(1, $messageRoomID, PDO::PARAM_INT);
			$stmt1->bindParam(2, $userID, PDO::PARAM_INT);
			$stmt1->bindParam(3, $projectID, PDO::PARAM_INT);
			$resultOne = $stmt1->execute();
			if ($resultOne) {
				$messages = $stmt1->fetchAll();
				$returnArray = array();
				$returnArray["messages"] = $messages;
				$response= $response->getBody()->write(json_encode($returnArray));
				unset($stmt1);
			}
			else {
				$response = $response->withStatus(400);
			}
		}
		else {
			$response = $response->withStatus(400);
		}
	}
)->add($validateSession);


//test
$app->post('/messages/{project_id}', function($request,$response,$args) {
		$userID = (int)$request->getAttribute('UserID');
		$projectID = $request->getAttribute('project_id');
		$message_data = $request->getParsedBody();
		$text = $message_data['text'];
		$anonymous = $message_data['anonymous'];
		$db=$this->GMPT;
		
		$stmt = $db->prepare("CALL GetMessageRoomID(?)");
        $stmt->bindParam(1, $projectID, PDO::PARAM_INT);
		$result = $stmt->execute();
		if ($result) {
			$messageRoomID = (int)$stmt->fetchAll()[0]["MessageRoomID"];
			unset($stmt);
			$stmt1 = $db->prepare("CALL CreateMessage(?,?,?,?)");
			$stmt1->bindParam(1, $userID, PDO::PARAM_INT);
			$stmt1->bindParam(2, $messageRoomID, PDO::PARAM_INT);
			$stmt1->bindParam(3, $text, PDO::PARAM_STR);
			$stmt1->bindParam(4, $anonymous, PDO::PARAM_BOOL);
			$resultOne = $stmt1->execute();
			if ($resultOne) {
				unset($stmt1);
			}
			else {
				$response = $response->withStatus(400);
			}
		}
		else {
			$response = $response->withStatus(400);
		}
	}
)->add($validateSession);
