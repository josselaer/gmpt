<?php
// Routes
include 'groups.php'
include 'meetings.php'

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

	
	return json_encode($returnArray);
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

	
	return json_encode($returnArray);
});

/*
//test json_encode
$app->get('/groups',
	function($request,$response,$args) {
		return getGroups();
	}

);

//test
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

//test
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

//test
$app->get('/meetings/',
	function($request,$response,$args) {
		$response=getMeetings();

	}	
);

//test
$app->get('/meetings/{groupID}',
	function($request,$response,$args) {

		$groupID=$request->getAttribute('groupID');
		$response=getMeetingsByGroup($groupID);

	}		
);

//test
$app->get('/meetings/{meetingID}',
	function($request,$response,$args) {

		$meetingID=$request->getAttribute('meetingID');
		$response=getMeetingByMeetingID($meetingID);

	}		
);

//test
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

//test
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
