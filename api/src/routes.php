<?php
// Routes
include 'project.php';
include 'meetings.php';
include 'user.php';
include 'chat.php';
include 'notification.php';

//test
$app->get('/meetings',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$ProjectID = (int)$request->getAttribute('ProjectID');
		
		$query = $db->prepare("CALL GetMeetings(?)");
		$query->bindParam(1,$ProjectID, PDO::PARAM_INT);
		$result=$query->execute();
		$response->write(json_encode(getMeetings($query)));
		//$response = getProjects($query);
		unset($query);	
		//echo json_encode($response);
		return $response;
	}
)->add($validateSession);

//test
$app->get('/meetingbyid',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$Meeting = (int)$request->getAttribute('MeetingID');
		
		$query = $db->prepare("CALL GetMeetingByID(?)");
		$query->bindParam(1,$MeetingID, PDO::PARAM_INT);
		$result=$query->execute();
		$response->write(json_encode(getMeetings($query)));
		//$response = getProjects($query);
		unset($query);	
		//echo json_encode($response);
		return $response;
	}
)->add($validateSession);

//test
$app->post('/meetings',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$form_data = $request->getParsedBody();	
		$ProjectID = $form_data['ProjectID'];
		$Description = $form_data['MeetingDescription'];
		$MeetingDate = $form_data['MeetingDate'];
		$LocationName = $form_data['LocationName'];
		$StartTime = $form_data['StartTime'];
		$EndTime = $form_data['EndTime'];
		$UserID = $form_data['UserID'];

		$query = $db->prepare("CALL CreateMeeting(?,?,?,?,?,?)");
		$query->bindParam(1,$ProjectID, PDO::PARAM_INT);
		$query->bindParam(2,$Description, PDO::PARAM_STR);
		$query->bindParam(3,$MeetingDate, PDO::PARAM_STR);
		$query->bindParam(4,$LocationName, PDO::PARAM_STR);
		$query->bindParam(5,$StartTime, PDO::PARAM_STR);
		$query->bindParam(6,$EndTime, PDO::PARAM_STR);

		$query->execute();
		$result = $query->fetchAll();
		$MeetingID = (int)$result[0]['MeetingID'];
		$response = $query->fetchAll();
		echo $response;
		unset($query);
		//get user id by email
		$query2 = $db->prepare("CALL CreateNotification2(?,?)");
		$query2->bindParam(1,$ProjectID, PDO::PARAM_INT);
		$query2->bindParam(2,"Meeting Created", PDO::PARAM_STR);
		$query2->execute();

		//$response = array("worked"=>true);
		return json_encode($response);

	}
)->add($validateSession);

//test
$app->put('/meetings',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$form_data = $request->getParsedBody();	
		$MeetingID = $form_data['MeetingID'];
		$Description = $form_data['MeetingDescription'];
		$MeetingDate = $form_data['MeetingDate'];
		$LocationName = $form_data['LocationName'];
		$StartTime = $form_data['StartTime'];
		$EndTime = $form_data['EndTime'];

		$query = $db->prepare("CALL EditMeeting(?,?,?,?,?,?)");
		$query->bindParam(1,$ProjectID, PDO::PARAM_INT);
		$query->bindParam(2,$Description, PDO::PARAM_STR);
		$query->bindParam(3,$MeetingDate, PDO::PARAM_STR);
		$query->bindParam(4,$LocationName, PDO::PARAM_STR);
		$query->bindParam(5,$StartTime, PDO::PARAM_STR);
		$query->bindParam(6,$EndTime, PDO::PARAM_STR);

		$query->execute();
		$result = $query->fetchAll();
		$MeetingID = (int)$result[0]['MeetingID'];
		$response = $query->fetchAll();
		echo $response;
		unset($query);
		//get user id by email
		$query2 = $db->prepare("CALL CreateNotification2(?,?)");
		$query2->bindParam(1,$ProjectID, PDO::PARAM_INT);
		$query2->bindParam(2,"Meeting Edited", PDO::PARAM_STR);
		$query2->execute();
		//$response = array("worked"=>true);
		return json_encode($response);

	}
)->add($validateSession);

$app->get('/projects',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$UserID = (int)$request->getAttribute('UserID');
		
		$query = $db->prepare("CALL GetProjects(?)");
		$query->bindParam(1,$UserID, PDO::PARAM_INT);
		$result=$query->execute();
		$response->write(json_encode(getProjects($query)));
		//$response = getProjects($query);
		unset($query);	
		//echo json_encode($response);
		return $response;
	}
)->add($validateSession);


//test
$app->post('/projects',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$form_data = $request->getParsedBody();	
		$GroupName = $form_data['groupName'];
		$Description = $form_data['projDescription'];
		$query = $db->prepare("CALL CreateProject(?,?)");
		$query->bindParam(1,$GroupName, PDO::PARAM_STR);
		$query->bindParam(2,$Description, PDO::PARAM_STR);
		$query->execute();
		$q1result = $query->fetchAll();
		$ProjID = (int)$q1result[0]['ProjectID'];
		$response = $query->fetchAll();
		unset($query);
		//get user id by email
		$users = $form_data['users'];
		$userIDs = [];
		$userRoles = [];
		foreach ($users as $user) {
			$email = $user['email'];
			$isProfessor = $user['isProfessor'];
			if($isProfessor == true) {
				$role = "Teacher";
			}
			else {
				$role = "Student";
			}
			$query2 = $db->prepare("CALL GetUserIDByEmail(?)");
			$query2->bindParam(1,$email,PDO::PARAM_STR);
			$query2->execute();
			$q2result = $query2->fetchAll();
			$userID = $q2result[0]["UserID"];
			unset($query2);
			array_push($userIDs,(int)$userID);
			array_push($userRoles,$role);
		}
		//add user to project
		$counter = 0;
		foreach($userIDs as $uID) {
			$query3 = $db->prepare("CALL AddUserToProject(?,?,?)");
			$query3->bindParam(1,$ProjID, PDO::PARAM_INT);
			$query3->bindParam(2,$uID, PDO::PARAM_INT);
			$query3->bindParam(3,$userRoles[$counter], PDO::PARAM_STR);
			$query3->execute();
			echo "Query 3";
			echo json_encode($query3->fetchAll());
			unset($query3);
			$counter = $counter + 1;
		}
		
		//$response = array("worked"=>true);
		return json_encode($response);

	}
)->add($validateSession);

//test
$app->get('/project/{ProjectID}',
	function($request,$response,$args) {
		$db = $this->GMPT;
		$ProjectID = $request->getAttribute('ProjectID');
		$query = $db->prepare("CALL GetProjectByID(?)");
		$query->bindParam(1,$ProjectID, PDO::PARAM_INT);
		$query->execute();
		$response->write(json_encode(getProjectByID($query)));
		//echo json_encode($response);
		return $response;
	}		
);
/*
$app->group('/project/{id}', function() {
	$this->map(['GET','DELETE','PATCH','PUT'], '', function ($request,$response,$args) {

	})->setName('project');
	$this->get('/getByUserID', function($request,$response,$args) {
		

	})->setName('getProjectByUserID');
});
*/
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
);	

//close session
$app->get('/logout', function ($request, $response, $args) {

	$token = $request->getAttribute('Token');
	$db=$this->GMPT;
	echo "We passed the middleware authentication";
	
	//$closeSessionQuery= $db->prepare('UPDATE Session SET LogoutTimestamp=NOW() WHERE SessionID = ?');
	$closeSessionQuery= $db->prepare('CALL CloseSession(?)');
	//$closeSessionQuery->bindParam(1, $token, PDO::PARAM_STR);
	$closeSessionQuery->execute(array($token));
	return $response;
})->add($validateSession);

//Edit a user: PUT @ /user endpoint
$app->put('/user', 
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
		$registerQuery=$db->prepare("CALL UpdateUser (?,?,?,?,?,?)");
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
)->add($validateSession);

$app->post('/autocomplete', 
	function($request,$response, $args){
		$form_data = $request->getParsedBody();
		$term = '%'.$form_data['term'].'%';
		
		$db= $this->GMPT;
		$autocompleteQuery= $db->prepare("CALL AutocompleteUserEmail(?)");
		$autocompleteQuery->bindValue(1,$term);
		$autocompleteQuery->execute();
		$results = [];
		$row = $autocompleteQuery->fetchAll();
		foreach($row as $data) {
			$ProjectID = $data['Email'];
			$suggestion = array("suggestion"=>$ProjectID);
			array_push($results,$suggestion);
		}
		$resultSize =  count($results);
		$results['suggestions'] = $results;
		for($i = 0; $i < $resultSize; $i++) {
			$temp = (string)$i;
			unset($results[$temp]);
		}
		
		$response->write(json_encode($results));
		
	}
)->add($validateSession);
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
		$ProjectID = $request->getAttribute('groupID');
		return $request->body(json_encode($getChatByGroupID($userID,$groupID)));
		$query2 = $db->prepare("CALL CreateNotification3(?,?,?)");
		$query2->bindParam(1,$UserID, PDO::PARAM_INT);
		$query2->bindParam(2,$ProjectID, PDO::PARAM_INT);
		$query2->bindParam(3,"Chat", PDO::PARAM_STR);
		$query2->execute();
	}
)->add($validateSession);

*/
 
/*
$app->put('/groups/{groupID}',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$groupID = $request->getAttribute('groupID');
		$groupName = $request->getAttribute('groupName');
		$description = $request->getAttribute('description');
		$meetingID = $request->getAttribute('meetingID');
		$query=$db->query('INSERT INTO Groups (groupName,description, meetingID) VALUES($groupName, $description, $meetingID);');
		
	}
);*/

