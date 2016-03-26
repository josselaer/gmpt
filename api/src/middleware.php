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
?>