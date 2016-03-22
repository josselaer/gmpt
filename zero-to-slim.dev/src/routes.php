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
	