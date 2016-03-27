<?php
// Routes
include 'groups.php';
include 'meetings.php';
include 'user.php';
include 'chat.php';

$app->get('/hello', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

//test stuff
$app->get('/goodbye', 
	function($request,$response,$args) {
	    $response->getBody()->write("Time to go. Goodbye!");
		$db=$this->GMPT;
		$getQuery=$db->query("SELECT * FROM User");
		$returnArray=array();
		foreach($getQuery as $row){
			$returnArray[$row['UserID']]=$row['PasswordHash'];
		}
		$response->getBody()->write(json_encode($returnArray));
		return $response;
	}
	
	
)->add($mw);

$app->get('/login/[{username}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return "Hello {$args['username']}";
});


//validate if user is correct
$app->post('/login', function ($request, $response, $args) {
    
	$username = $request->getAttribute('userName');
	$password = $request->getAttribute('password');
	
	//set token
	$token=validateUser($username,$password);
	
	//set Authorization header to token
	$returnArray=array();
	$returnArray['Authorization']=$token;
	
	$response= $response->getBody()->write(json_encode($returnArray));

	//return the response
	return $response;
	
});

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

//close session
$app->post('/logout', function ($request, $response, $args) {
	$token = $request->getHeader("Authorization")[0];
	closeSessionQuery($token);
	
})->add($validateSession);

/*
//test json_encode
//Returns all groups for the currently authenticated user
$app->get('/groups',
	function($request,$response,$args) {
		return $response->getBody()->write(json_encode(getGroups()));
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
//Edit meeting by meeting ID : PUT @ /meetings/{meetingID}
$app->put('/meetings/{meetingID}',
	function($request,$response,$args) {

		$topic = $request->getAttribute('topic');
		$groupName = $request->getAttribute('groupName');
		$date = $request->getAttribute('date');
		$description = $request->getAttribute('description');
		$location = $request->getAttribute('location');
		$startTime = $request->getAttribute('startTime');
		$endTime = $request->getAttribute('endTime');
		$meetingID=$request->getAttribute('meetingID');
		
		return $response->body(json_encode(editMeetingByMeetingID($topic,$groupName,$date,$description,$location,$startTime,$endTime,$meetingID)));
		
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
//Edit a user: PUT @ /user endpoint
$app->post('/user', 
	function($request, $response,$args){
		$username= $request->getAttribute('userName');
		$password = $request->getAttribute('password');
		$fName =$request->getAttribute('firstName');
		$lName =$request->getAttribute('lastName');
		$email= $request->getAttribute('email');
		updateUser($username,$password,$fName,$lName,$email);
	}
);

//test
//Edit group by GroupID : PUT @  /groups/{groupID} endpoint 
$app->put('/groups/{groupID}',
	function($request,$response,$args) {
		$groupID = $request->getAttribute('groupID');
		$groupName = $request->getAttribute('groupName');
		$description = $request->getAttribute('description');
		$users= $request->getAttribute('users');
		
		editGroupByGroupID($groupID, $groupName, $description, $users);
		
	}
);

//test
//Get chat by GroupID : GET @ /chat/{groupID} endpoint
$app->get('/chat/{groupID}',
	function($request,$response,$args) {
		$groupID = $request->getAttribute('groupID');
		return $request->body(json_encode($getChatByGroupID($groupID)));
	}
);

*/
