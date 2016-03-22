<?php
// Routes

//$app->get('/[{name}]', function ($request, $response, $args) {
$app->get('/hello', function($request,$response,$args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/goodbye', 
	function($request,$response,$args) {
	    return $response->write("Time to go. Goodbye!");
	}
);

$app->get('/lists/1/1',
	function($request,$response,$args) {
		$db = $this->ToDoList;
		$query = $db->query('select * from List');
		$strToReturn = '';

		foreach ($query as $row) {
			$strToReturn .= $row['ListName'] . ', ' . $row['ListID'] . '<br/>';
		}
		return $response->write($strToReturn);
	}
);


$app->get('/recommendations/{userId}/{itemId}',
	function($request,$response,$args) {
		$db = $this->Recommender;
		//$name = $request->getAttribute('name');
		$userId=$request->getAttribute('userId');
		$itemId = $request->getAttribute('itemId');
		$query = $db->query("CALL getRecommendation ('$itemId',$userId)");
		$strToReturn = '';

		$returnArray=array();
		foreach ($query as $row) {
			$strToReturn .= $row['ProductName'] . ', ' . $row['COUNT(*)'] . '<br/>';
			$returnArray[$row['ProductName']] = $row['COUNT(*)'];
		}
		return $response->write(json_encode($returnArray));
	}		
);

$app->get('/meetings/',
	function($request,$response,$args) {
		$response=getMeetings();

	}	
);

$app->get('/meetings/{groupID}',
	function($request,$response,$args) {

		$groupID=$request->getAttribute('groupID');
		$response=getMeetingsByGroup($groupID);

	}		
);

$app->get('/meetings/{meetingID}',
	function($request,$response,$args) {

		$meetingID=$request->getAttribute('meetingID');
		$response=getMeetingByMeetingID($meetingID);

	}		
);

$app->post('/meetings/',
	function($request,$response,$args) {

		$topic = $request->getAttribute('topic');
		$groupName = $request->getAttribute('groupName');
		$date = $request->getAttribute('date');
		$description = $request->getAttribute('description');
		$location = $request->getAttribute('location');
		$startTime = $request->getAttribute('startTime');
		$endTime = $request->getAttribute('endTime');


		$response=createMeeting($topic,$groupName,$date,$description,$location,$startTime,$endTime);		

	}	
);

