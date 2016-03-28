<?php
// Routes
include 'meetings.php';

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


//test json_encode
$app->get('/groups',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$query=$db->query('select * from Groups;');
		$results = [];
		$row = $query->fetchAll();
		foreach($row as $data) {
			$GroupName = $data['GroupName'];
			$Description = $data['Description'];
			$DateCreated = $data['DateCreated'];
			$group = array("GroupName"=>$GroupName, "Description"=>$Description, "DateCreated"=>$DateCreated);
			array_push($results,$group);
		}
		$response=$results;
		echo json_encode($response);
	}
);


//test
$app->post('/groups',
	function($request,$response,$args) {
		$db=$this->GMPT;

		//credentials

		$GroupName = $_POST['GroupName'];
		$Description = $_POST['Description'];

		$query=$db->query("INSERT INTO Groups (GroupName,Description) VALUES('$GroupName', '$Description');");
		
		$response = array("worked"=>true);
		return json_encode($response);

	}
);

$app->get('/groups/{groupID}',
	function($request,$response,$args) {
		$db = $this->GMPT;
		$GroupID = $request->getAttribute('groupID');

		$query=$db->query("SELECT * FROM Groups WHERE GroupID = '$GroupID';");
		//echo $query;
		$row = $query->fetchAll();
		//echo $row;
		$data = $row[0];

		$GroupName = $data['GroupName'];
		$Description = $data['Description'];
		$DateCreated = $data['DateCreated'];

		$response = array("GroupName"=>$GroupName, "Description"=>$Description, "DateCreated"=>$DateCreated);
		echo json_encode($response);
	}		
);

/*
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