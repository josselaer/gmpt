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

$app->get('/statistics/loginCount/{project_id}', function($request,$response,$args) {
	$db=$this->GMPT;
	$projectID = $args['project_id'];
	
	$query = $db->prepare("CALL GetLoginCount(?)");
	$query->bindParam(1,$projectID, PDO::PARAM_INT);
	$result = $query->execute();
	if ($response) {
		$data = $query->fetchAll();
		
		$members = array();
		foreach ($data as $row) {
			$tmpArray = array("data"=>$row['date'], "numOfLogins"=>$row['numOfLogins']);
			if (array_key_exists($row['username'], $members)) {
				array_push($members[$row['username']], $tmpArray);
			}
			else {
				$members[$row['username']] = array();
				array_push($members[$row['username']], $tmpArray);
			}
		}
		$response = $response->getBody()->write(json_encode(array("members"=>$members)));
	}
	unset($query);	
	return $response;
}
)->add($validateSession);

//if (gettype($returnArray[$row['username']] == Array) {
//	echo "$row['username'] is in return Array";
//	array_push($returnArray[$row['username']], $tmpArray);
//}
//else {
//	$returnArray[$row['username']] = array();
//	array_push($returnArray[$row['username']], $tmpArray);
//}
?>

