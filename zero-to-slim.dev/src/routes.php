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


$app->get('/groups',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$query=$db->query('select * from Groups;');

	}

);

$app->post('/groups',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$groupID = $request->getAttribute('groupID');
		$groupName = $request->getAttribute('groupName');
		$description = $request->getAttribute('description');
		$meetingID = $request->getAttribute('meetingID');
		$query=$db->query('INSERT INTO Groups (groupName,description, meetingID) VALUES($groupName, $description, $meetingID);');
		
	}
);

$app->get('/groups/{groupID}',
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


//Register:  POST @ /user endpoint
$app->post('/user', 
	function($request, $response,$args){
		$db = $this->GMPT;
		$username= $request->getAttribute('userName');
		$password = $request->getAttribute('password');
		$fName =$request->getAttribute('firstName');
		$lName =$request->getAttribute('lastName');
		$email= $request->getAttribute('email');
		
		$salt = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

		//prepare query
		$registerQuery=$db->prepare("CALL Register (?,?,?,?,?)");
		
		$registerQuery->execute(array($username,hash('sha256',$password.$salt),$fName,$lName,$salt,$email));
	}
);	

$app->put('/groups/{groupID}',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$groupID = $request->getAttribute('groupID');
		$groupName = $request->getAttribute('groupName');
		$description = $request->getAttribute('description');
		$meetingID = $request->getAttribute('meetingID');
		$query=$db->query('INSERT INTO Groups (groupName,description, meetingID) VALUES($groupName, $description, $meetingID);');
		
	}
);

$app->get('/chat/{groupID}',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$groupID = $request->getAttribute('groupID');
		$query=$db->query("SELECT * FROM Messages WHERE groupID = '$groupID' limit 100;");
	}
);

