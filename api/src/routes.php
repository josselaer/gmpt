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

$app->get('/meetings',
	function($request,$response,$args) {
		$db = $this->GMPT;
		$query = $db->query('select * from Meetings;');
		$response=getMeetings($query);
		echo json_encode($response);
	}	
);

//test
$app->get('/meetings/{groupID}',
	function($request,$response,$args) {
		$groupID=$request->getAttribute('groupID');
		$db = $this->GMPT;
		$query = $db->query("SELECT * FROM Meetings WHERE GroupID = '$groupID';");
		$response=getMeetingsByGroup($query);
		echo json_encode($response);
	}		
);

//test
/*$app->get('/meetings/{meetingID}',
	function($request,$response,$args) {

		$meetingID=$request->getAttribute('meetingID');
		$response=getMeetingByMeetingID($meetingID);

	}		
);*/

//test
$app->post('/meetings',
	function($request,$response,$args) {

		$topic = $_POST['Topic'];
		$date = $_POST['Date'];
		$description = $_POST['Description'];
		$location = $_POST['Location'];
		$startTime = $_POST['StartTime'];
		$endTime = $_POST['EndTime'];
		$groupID = $_POST['GroupID'];

		$db = $this->GMPT;
		$query=$db->query("INSERT INTO Meetings (Topic,Date,Description,Location,StartTime,EndTime,GroupID) VALUES('$topic','$date','$description','$location','$startTime','$endTime','$groupID');");

		$response=createMeeting($query);		
		echo json_encode($response);
	}	
);

/*
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