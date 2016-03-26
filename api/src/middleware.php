<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
$mw = (function ($request, $response, $next) {
	//
	//$response->withHeader('Authorization','test');
	//$headers=$request->getHeaders();
	////$auth= $headers['Authorization'];
	//foreach ($headers as $name => $values) {
	//	//echo $name . ": " . implode(", ", $values);
	//	$response->getBody()->write($name);
	//	$response->getBody()->write("</br>");
	//}
	//$response->getBody()->write($auth);
	//echo $auth;
	$headers=$request->getHeaders();
	foreach($headers as $name => $values ){
		
		echo $name. ": " . implode(", ",$values) . "</br>";
	}
	$response = $next($request, $response);
	$response= $response->withHeader('Authorization','test');
           
	
	return $response;
});
?>