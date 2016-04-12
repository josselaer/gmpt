<?php

	//Get Group Totals
	$app->get('/statistics/totals/{project_id}', function($request, $response,$args){
		$db=$this->GMPT;
		$numOfMeetings = 0;
		$numOfMessages = 0;
		$projectID = $request->getAttribute('project_id');
		$query = $db->prepare('CALL GetProjectTotalMeetings(?)');
		$query->bindParam(1,$projectID);
		$result = $query->execute();
		$stats = array();
		if ($result) {
			$data = $query->fetchAll()[0];
			$numOfMeetings = $data['totalNumOfMeetings'];
			$stats['totalNumOfMeetings'] = $numOfMeetings;
		}
		unset($query);
		$query = $db->prepare('CALL GetProjectTotalMessages(?)');
		$query->bindParam(1,$projectID);
		$result = $query->execute();
		if ($result) {
			$data = $query->fetchAll()[0];
			$numOfMessages = $data['totalNumOfChatMessages'];
			$stats['totalNumOfChatMessages'] = $numOfMessages;
		}
		$returnArray = array("Totals"=>$stats);
		$response = $response->getBody()->write(json_encode($returnArray));
		return $response;
		}
	)->add($validateSession);
?>