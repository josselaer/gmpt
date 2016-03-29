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
		$userID = $request->getAttribute('UserID');
			
	    $response->getBody()->write($userID);	
		
		return $response;
	}
	
	
)->add($validateSession);


//validate if user is correct
$app->post('/login', function ($request, $response, $args) {
    
	$form_data = $request->getParsedBody();
	$username = $form_data['username'];
	$password = $form_data['password'];
	//set token
	$db=$this->GMPT;
		
	//get salt
	$getSaltQuery = $db->prepare("CALL GetSalt(?)");
	$getSaltQuery->execute(array($username));
	$rArray=array();
	foreach($getSaltQuery as $row){
		$rArray[$username]=$row['Salt'];
	}
	//hash pass with salt
	$hashedPass= hash('sha256',$password.($rArray[$username]));
	$getSaltQuery->closeCursor();
	//validate user
	$validateUserQuery= $db->prepare("CALL ValidateUser(?,?)");
	
	$validateUserQuery->bindValue(1, $username, PDO::PARAM_STR);
	$validateUserQuery->bindValue(2, $hashedPass, PDO::PARAM_STR);
	$validateUserQuery->execute();
	$tokenArray=array();
	foreach($validateUserQuery as $row){
		$tokenArray[$username]=$row['ReturnToken'];
	}
	
	//get token 
	
	
	
	//set Authorization header to token
	$returnArray1=array();
	$returnArray1['Authorization']=$tokenArray[$username];
	
	$response= $response->getBody()->write(json_encode($returnArray1));

	//return the response
	return $response;
	
});

//test
//Register:  POST @ /user endpoint
$app->post('/user', 
	function($request, $response,$args){
		$form_data = $request->getParsedBody();
		$username = $form_data['username'];
		$password = $form_data['password'];
		$fName  = $form_data['firstName'];
		$lName = $form_data['lastName'];
		$email = $form_data['email'];
		$db = $this->GMPT;		
		$salt = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
		$passwordHash = hash('sha256',$password.$salt);
		
		//$returnArray = array("Volvo", "BMW", "Toyota");
		//prepare query
		$registerQuery=$db->prepare("CALL CreateUser (?,?,?,?,?,?)");
		$registerQuery->bindValue(1, $username, PDO::PARAM_STR);
		$registerQuery->bindValue(2, $passwordHash, PDO::PARAM_STR);
		$registerQuery->bindValue(3, $fName, PDO::PARAM_STR);
		$registerQuery->bindValue(4, $lName, PDO::PARAM_STR);
		$registerQuery->bindValue(5, $salt, PDO::PARAM_STR);
		$registerQuery->bindValue(6, $email, PDO::PARAM_STR);
		
		$registerQuery->execute();

		
		$returnArray = array($username, $passwordHash, $fName, $lName, $email);
		$response->getBody()->write(json_encode($returnArray));
		return $response;
		
	}
)->add($mw);	

//close session
$app->get('/logout', function ($request, $response, $args) {
	$token = $request->getHeader("Authorization")[0];
	$db=$this->GMPT;
	$closeSessionQuery= $db->prepare("CALL CloseSession(?)");
	$closeSessionQuery->bindValue(1, $token, PDO::PARAM_STR);
	print_r($closeSessionQuery);
	if($closeSessionQuery->execute()) {
		echo "True";
	}
	else {
		echo "False";
	}
	return $response;
})->add($validateSession);

/*
//test json_encode
//Returns all groups for the currently authenticated user
$app->get('/groups',
	function($request,$response,$args) {
		$userID = $request->getAttribute('UserID');
		return $response->getBody()->write(json_encode(getGroups($userID)));
	}

)->add($validateSession);
//test
//Creates a new group
$app->post('/groups',
	function($request,$response,$args) {
		$userID = $request->getAttribute('UserID');
		$groupName = $request->getAttribute('groupName');
		$description = $request->getAttribute('description');
		$users= $request->getAttribute('users');
		postGroups($userID,$groupID,$groupName,$users);
	}
)->add($validateSession);

//test
//Gets all information about a group by groupID
$app->get('/groups/{groupID}',
	function($request,$response,$args) {
		//
	}		
)->add($validateSession);

//test
//Gets all meetings for currently authenticated user
$app->get('/meetings/',
	function($request,$response,$args) {
		$userID = $request->getAttribute('UserID');
		return $response->body(json_encode(getMeetings($userID)));

	}	
)->add($validateSession);

//test
//Gets all meetings by GroupID
$app->get('/meetings/{groupID}',
	function($request,$response,$args) {
		
		$userID = $request->getAttribute('UserID');
		$groupID=$request->getAttribute('groupID');
		return $response->body(json_encode(getMeetingsByGroup($userID,$groupID)));

	}		
)->add($validateSession);

//test
//Gets meeting by meetingID
$app->get('/meetings/{meetingID}',
	function($request,$response,$args) {
		$userID = $request->getAttribute('UserID');
		$meetingID=$request->getAttribute('meetingID');
		return $response->body(json_encode(getMeetingByMeetingID($userID,$meetingID)));

	}		
)->add($validateSession);

//test
//Edit meeting by meeting ID : PUT @ /meetings/{meetingID}
$app->put('/meetings/{meetingID}',
	function($request,$response,$args) {
		$userID = $request->getAttribute('UserID');
		$topic = $request->getAttribute('topic');
		$groupName = $request->getAttribute('groupName');
		$date = $request->getAttribute('date');
		$description = $request->getAttribute('description');
		$location = $request->getAttribute('location');
		$startTime = $request->getAttribute('startTime');
		$endTime = $request->getAttribute('endTime');
		$meetingID=$request->getAttribute('meetingID');
		
		return $response->body(json_encode(editMeetingByMeetingID($userID,$topic,$groupName,$date,$description,$location,$startTime,$endTime,$meetingID)));
		
	}		
)->add($validateSession);


//test
//Creates a meeting
$app->post('/meetings/',
	function($request,$response,$args) {
		$userID = $request->getAttribute('UserID');
		$topic = $request->getAttribute('topic');
		$groupName = $request->getAttribute('groupName');
		$date = $request->getAttribute('date');
		$description = $request->getAttribute('description');
		$location = $request->getAttribute('location');
		$startTime = $request->getAttribute('startTime');
		$endTime = $request->getAttribute('endTime');


		createMeeting($userID,$topic,$groupName,$date,$description,$location,$startTime,$endTime);		

	}	
)->add($validateSession);


//test 
//Edit a user: PUT @ /user endpoint
$app->post('/user', 
	function($request, $response,$args){
		$userID = $request->getAttribute('UserID');
		$username= $request->getAttribute('userName');
		$password = $request->getAttribute('password');
		$fName =$request->getAttribute('firstName');
		$lName =$request->getAttribute('lastName');
		$email= $request->getAttribute('email');
		updateUser($userID,$username,$password,$fName,$lName,$email);
	}
)->add($validateSession);

//test
//Edit group by GroupID : PUT @  /groups/{groupID} endpoint 
$app->put('/groups/{groupID}',
	function($request,$response,$args) {
		$userID = $request->getAttribute('UserID');
		$groupID = $request->getAttribute('groupID');
		$groupName = $request->getAttribute('groupName');
		$description = $request->getAttribute('description');
		$users= $request->getAttribute('users');
		
		editGroupByGroupID($userID,$groupID, $groupName, $description, $users);
		
	}
)->add($validateSession);

//test
//Get chat by GroupID : GET @ /chat/{groupID} endpoint
$app->get('/chat/{groupID}',
	function($request,$response,$args) {
		$userID = $request->getAttribute('UserID');
		$groupID = $request->getAttribute('groupID');
		return $request->body(json_encode($getChatByGroupID($userID,$groupID)));
	}
)->add($validateSession);

*/
