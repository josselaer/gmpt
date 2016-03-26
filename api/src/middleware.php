<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
$mw = (function ($request, $response, $next) {
	

	$token = $request->getHeader("Authorization")[0];
	echo $token;
	$response = $next($request, $response);
	$response= $response->withHeader('Authorization','test');
           
	
	return $response;
});

$validateSession= (function ($request,$response,$next) {
	//get authorization header
	$token = $request->getHeader("Authorization")[0];
	
	$db=$this->GMPT;
	//see if token exists 
	$validateSessionQuery=$db->prepare("CALL ValidateSession(?)");
	$validateSessionQuery->execute(array($token));
	
	//if we found the token
	if($validateSessionQuery->rowCount()==1){
		
		//put username in body of request somehow 
		
		
		//do everything else in routes and return response 
		$response=$next($request,$response);
		return $response;
	}
	//if no such token, return nothing in response 
	else{
		return $response;
	}
		
	
	
});
?>