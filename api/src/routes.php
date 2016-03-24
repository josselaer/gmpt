<?php
// Routes
include 'groups.php'
include 'meetings.php'
include 'user.php'

$app->get('/hello', function ($request, $response, $args) {
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

$app->get('/login/[{username}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return "Hello {$args['username']}";
});

$app->post('/login', function ($request, $response, $args) {
    //Get credentials from request body
	$credentials = $request->getParsedBody();
	
	//Authenticate against the Database
	$db = $this->dbConn;
	$sql = 'CALL Login(\''. $credentials['username'] .'\',\''. $credentials['password'] . '\');';
	$query = $db->query($sql);
	$row = $query->fetchAll();
	//$result = $query->execute();
	$returnArray = array();
	//If username and password exists in database
	if (sizeof($row) == 1) {
		$returnArray['success'] = 'True';
		//print_r($query->fetchAll());		
		//retrieve name and add it to final return array
		$data = $row[0];
		$returnArray['userID'] = $data['userID'];
		$returnArray['token'] = $data['token'];	
	}
	else {
		$returnArray['success'] = 'False';
	}

	
	return $response->body(json_encode($returnArray));
});

$app->post('/logout', function ($request, $response, $args) {
    //Get credentials from request body
	$credentials = $request->getParsedBody();
	
	//Authenticate against the Database
	$db = $this->dbConn;
	$sql = 'CALL Logout(\''. $credentials['Authorization'] .'\');';
	$query = $db->query($sql);
	$row = $query->fetchAll();
	//$result = $query->execute();
	$returnArray = array();
	//If username and password exists in database
	if (sizeof($row) == 1) {
		$returnArray['success'] = 'True';
	}
	else {
		$returnArray['success'] = 'False';
	}

	
	return $response->body(json_encode($returnArray));
});

/*

//test json_encode
//Returns all groups for the currently authenticated user
$app->get('/groups',
	function($request,$response,$args) {
		return $response->body(json_encode(getGroups()));
	}

);

//test
//Creates a new group
$app->post('/groups',
	function($request,$response,$args) {
		$groupName = $request->getAttribute('groupName');
		$description = $request->getAttribute('description');
		$users= $request->getAttribute('users');
		postGroups($groupID,$groupName,$users);
	}
);

//test
//Gets all information about a group by groupID
$app->get('/groups/{groupID}',
	function($request,$response,$args) {
		//
	}		
);

//test
//Gets all meetings for currently authenticated user
$app->get('/meetings/',
	function($request,$response,$args) {
		return $response->body(json_encode(getMeetings()));

	}	
);

//test
//Gets all meetings by GroupID
$app->get('/meetings/{groupID}',
	function($request,$response,$args) {

		$groupID=$request->getAttribute('groupID');
		return $response->body(json_encode(getMeetingsByGroup($groupID)));

	}		
);

//test
//Gets meeting by meetingID
$app->get('/meetings/{meetingID}',
	function($request,$response,$args) {

		$meetingID=$request->getAttribute('meetingID');
		return $response->body(json_encode(getMeetingByMeetingID($meetingID)));

	}		
);

//test
//Creates a meeting
$app->post('/meetings/',
	function($request,$response,$args) {

		$topic = $request->getAttribute('topic');
		$groupName = $request->getAttribute('groupName');
		$date = $request->getAttribute('date');
		$description = $request->getAttribute('description');
		$location = $request->getAttribute('location');
		$startTime = $request->getAttribute('startTime');
		$endTime = $request->getAttribute('endTime');


		createMeeting($topic,$groupName,$date,$description,$location,$startTime,$endTime);		

	}	
);

//test
//Register:  POST @ /user endpoint
$app->post('/user', 
	function($request, $response,$args){
		$username= $request->getAttribute('userName');
		$password = $request->getAttribute('password');
		$fName =$request->getAttribute('firstName');
		$lName =$request->getAttribute('lastName');
		$email= $request->getAttribute('email');
		
		registerUser($username,$password,$fName,$lName,$email);
	}
);	

//test 
//Edit a user: PUT @ /user endpoint
$app->post('/user', 
	function($request, $response,$args){
	
	}
);

//test
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

//test
$app->get('/chat/{groupID}',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$groupID = $request->getAttribute('groupID');
		$query=$db->query("SELECT * FROM Messages WHERE groupID = '$groupID' limit 100;");
	}
);

*/
