<?php
// Routes
include 'project.php';
include 'meetings.php';
include 'user.php';
include 'chat.php';
include 'stats.php';
include 'notification.php';
include 'attendance.php';

//test
$app->get('/meetings/{ProjectID}',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$ProjectID = (int)$request->getAttribute('ProjectID');
		//$ProjectID = 58;

		$query = $db->prepare("CALL GetMeetings(?)");
		$query->bindParam(1,$ProjectID, PDO::PARAM_INT);
		$result=$query->execute();
		//$response->write(json_encode(getMeetings($query)));
		$response = getMeetings($query);
		unset($query);	
		echo json_encode($response);
		return $response;
	}
)->add($validateSession);

//test
$app->get('/meetingbyid',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$MeetingID = (int)$request->getAttribute('MeetingID');
		
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
		//$LocationName = "Innovation Gym";
		$StartTime = $form_data['StartTime'];
		$EndTime = $form_data['EndTime'];
		//$EndTime = "13:30";
		$query = $db->prepare("CALL CreateMeeting(?,?,?,?,?,?)");
		$query->bindParam(1,$ProjectID, PDO::PARAM_INT);
		$query->bindParam(2,$Description, PDO::PARAM_STR);
		$query->bindParam(3,$MeetingDate, PDO::PARAM_STR);
		$query->bindParam(4,$LocationName, PDO::PARAM_STR);
		$query->bindParam(5,$StartTime, PDO::PARAM_STR);
		$query->bindParam(6,$EndTime, PDO::PARAM_STR);

		$query->execute();
		//$MeetingID = (int)$result[0]['MeetingID'];
		$response = $query->fetchAll();
		unset($query);
		
		return json_encode($response);

	}
)->add($validateSession);

$app->post('/meetings/{MeetingID}',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$form_data = $request->getParsedBody();	
		$MeetingID = (int)$request->getAttribute('MeetingID');
		$Description = $form_data['MeetingDescription'];
		$MeetingDate = $form_data['MeetingDate'];
		$LocationName = $form_data['LocationName'];
		$StartTime = $form_data['StartTime'];
		$EndTime = $form_data['EndTime'];

		$query = $db->prepare("CALL EditMeeting(?,?,?,?,?,?)");
		$query->bindParam(1,$MeetingID, PDO::PARAM_INT);
		$query->bindParam(2,$Description, PDO::PARAM_STR);
		$query->bindParam(3,$MeetingDate, PDO::PARAM_STR);
		$query->bindParam(4,$LocationName, PDO::PARAM_STR);
		$query->bindParam(5,$StartTime, PDO::PARAM_STR);
		$query->bindParam(6,$EndTime, PDO::PARAM_STR);

		$query->execute();
		//$MeetingID = (int)$result[0]['MeetingID'];
		$response = $query->fetchAll();
		unset($query);
		
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
		$response->write(json_encode(getProjects($query,$db)));
		//$response = getProjects($query);
		unset($query);	
		//echo json_encode($response);
		return $response;
	}
)->add($validateSession);


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
		
		//getSenderEmail by ID
		$SenderID = (int)$request->getAttribute('UserID');
		$getSenderEmailQuery=$db->prepare("CALL GetEmailByUserID(?)");
		$getSenderEmailQuery->execute(array($SenderID));
		$SenderEmailResult= $getSenderEmailQuery->fetchAll();
		$SenderEmail=$SenderEmailResult[0]['Email'];
		
		unset($getSenderEmailQuery);
		
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
			$userID=0;
			if($query2->rowCount()==0){
				unset($query2);
				$userID=sendEmailInvite($SenderEmail,$email,$db);
			}
			else{
				$userID = $q2result[0]["UserID"];
				unset($query2);
			}
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
			unset($query3);
			$counter = $counter + 1;
		}
		
		//$response = array("worked"=>true);
		return $response;

	}
)->add($validateSession);

//test
$app->post('/projects/add',
	function($request,$response,$args) {
		$db=$this->GMPT;
		$form_data = $request->getParsedBody();	
		$ProjectID = $form_data['ProjectID'];
		//get user id by email
		$user = $form_data['user'];
		//foreach ($users as $user) {
		print_r ($user);
			$email = $user['email'];

			$isProfessor = $user['isProfessor'];
			if($isProfessor == True) {
				$role = "Teacher";
			}
			else {
				$role = "Student";
			}
			echo "ROLE", $role;
			$query2 = $db->prepare("CALL GetUserIDByEmail(?)");
			$query2->bindParam(1,$email,PDO::PARAM_STR);
			$query2->execute();
			$q2result = $query2->fetchAll();
			$userID=0;
			
			if($query2->rowCount()==0){
				unset($query2);
				$userID=sendEmailInvite($SenderEmail,$email,$db);
			}
			else{
				$userID = $q2result[0]["UserID"];
				unset($query2);
			}
			echo "UserID ";
			echo $userID;

			//array_push($userIDs,(int)$userID);
			//array_push($userRoles,$role);	
		//}
		//add user to project
		//$counter = 0;		
		//foreach($userIDs as $uID) {
			$query3 = $db->prepare("CALL AddUserToProject(?,?,?)");
			$query3->bindParam(1,$ProjectID, PDO::PARAM_INT);
			$query3->bindParam(2,$userID, PDO::PARAM_INT);
			$query3->bindParam(3,$role, PDO::PARAM_STR);
			$result = $query3->execute();
			//echo  "DID QUERY EXECUTE???: ", $result;
			//print_r($query3->errorInfo());
			//echo "<br>";
			unset($query3);
			//$counter = $counter + 1;
		
		
		//echo $ProjectID;
		//$response = array("worked"=>true);
		return $response;

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
		$mail = new PHPMailer;

		$mail->SMTPDebug = 1;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'ssl://smtp.gmail.com:465'; // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'gmptDBGUI@gmail.com';                 // SMTP username
		$mail->Password = 'gmptMaster1';                           // SMTP password
		//$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		//$mail->Port = 465;                                    // TCP port to connect to

		$mail->setFrom('gmptDBGUI@gmail.com');
		$mail->addAddress('spopov@smu.edu');     
		$mail->addReplyTo('info@example.com', 'Information');

		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'PLEASE';
		$mail->Body    = 'PLEASE WORK';

		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent';
		}
	
	}
);


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
	$result = $validateUserQuery->execute();
	$userData = array();
	if ($result) {
		$data = $validateUserQuery->fetchAll();
		foreach ($data as $row) {
			$userData["userID"] = $row["userID"];
			$userData["username"] = $row["username"];
			$userData["token"] = $row["token"];
			$userData["firstName"] = $row["firstName"];
			$userData["lastName"] = $row["lastName"];
			$userData["email"] = $row["email"];
		}
		$response = $response->getBody()->write(json_encode($userData));
	}
	else {
		$response = $response->withStatus(400);
	}

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

//Edit a user WHEN HE registers: PUT @ /user endpoint
$app->post('/update/user', 
	function($request, $response,$args){
		$form_data = $request->getParsedBody();
		$username = $form_data['username'];
		$password = $form_data['password'];
		$fName  = $form_data['firstName'];
		$lName = $form_data['lastName'];
		$email = $form_data['email'];
		$registrationToken= $form_data['token'];
		$db = $this->GMPT;
		$salt = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
		$passwordHash = hash('sha256',$password.$salt);
		
		//$returnArray = array("Volvo", "BMW", "Toyota");
		//prepare query
		$registerQuery=$db->prepare("CALL UpdateUser (?,?,?,?,?,?,?)");
		$registerQuery->bindValue(1, $username, PDO::PARAM_STR);
		$registerQuery->bindValue(2, $passwordHash, PDO::PARAM_STR);
		$registerQuery->bindValue(3, $fName, PDO::PARAM_STR);
		$registerQuery->bindValue(4, $lName, PDO::PARAM_STR);
		$registerQuery->bindValue(5, $salt, PDO::PARAM_STR);
		$registerQuery->bindValue(6, $email, PDO::PARAM_STR);
		$registerQuery->bindValue(7, $registrationToken, PDO::PARAM_STR);
		
		$registerQuery->execute();

		
		$returnArray = array($username, $passwordHash, $fName, $lName, $email);
		$response->getBody()->write(json_encode($returnArray));
		return $response;
		
	}
);

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

$app->post('/projects/{project_id}', function($request,$response, $args) {
	$db = $this->GMPT;
	$form_data = $request->getParsedBody();
	if (array_key_exists('projDescription', $form_data) && array_key_exists('groupName', $form_data)) {
		$projectID = $args['project_id'];
		$projectName = $form_data['groupName'];
		$projectDescription = $form_data['projDescription'];
		$query = $db->prepare('CALL UpdateProject(?,?,?)');
		$query->bindParam(1,$projectID, PDO::PARAM_INT);
		$query->bindParam(2,$projectName, PDO::PARAM_STR);
		$query->bindParam(3,$projectDescription, PDO::PARAM_STR);
		$result = $query->execute();
		if ($result) {
			$response = $response->withStatus(200);
		}
		else {
			$response = $response->withStatus(400);
			$response = $response->getBody()->write(json_encode($query->errorInfo()));
		}
	}
	else {
		$response = $response->withStatus(200);
		$response = $response->getBody()->write(json_encode(array("error"=>True, "errorInfo"=>"Proper Arguments not found")));
	}
	return $response;

})->add($validateSession);

$app->delete('/users/project/{project_id}', function($request,$response, $args) {
	$db = $this->GMPT;
	$form_data = $request->getParsedBody();
	if (array_key_exists('project_id', $args)) {
		$projectID = $args['project_id'];
		$userID = (int)$request->getAttribute('UserID');
		$query = $db->prepare('CALL DeleteUserProject(?,?)');
		$query->bindParam(1,$userID, PDO::PARAM_INT);
		$query->bindParam(2,$projectID, PDO::PARAM_STR);
		$result = $query->execute();
		if ($result) {
			$response = $response->withStatus(200);
		}
		else {
			$response = $response->withStatus(400);
			$response = $response->getBody()->write(json_encode($query->errorInfo()));
		}
	}
	else {
		$response = $response->withStatus(200);
		$response = $response->getBody()->write(json_encode(array("error"=>True, "errorInfo"=>"Proper Arguments not found")));
	}
	return $response;

})->add($validateSession);

$app->delete('/meetings/{meetingID}', function($request,$response, $args) {
	$db = $this->GMPT;
	$form_data = $request->getParsedBody();
	
	$MeetingID = (int)$request->getAttribute('meetingID');
	
	$query = $db->prepare("CALL DeleteMeeting(?)");
	$query->bindParam(1,$MeetingID, PDO::PARAM_INT);
	$result = $query->execute();
	if ($result) {
		$response = $response->withStatus(200);
	}
	else {
		$response = $response->withStatus(400);
		$response = $response->getBody()->write(json_encode($query->errorInfo()));
	}
	echo $response;


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

