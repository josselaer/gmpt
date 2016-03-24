<?php
	function registerUser($username, $password,$fName,$lName,$email){
		$db = $this->GMPT;		
		$salt = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

		//prepare query
		$registerQuery=$db->prepare("CALL Register (?,?,?,?,?,?)");
		
		$registerQuery->execute(array($username,hash('sha256',$password.$salt),$fName,$lName,$salt,$email));
	}
	
	function updateUser($username, $password, $fName, $lName, $email){
		$db= $this->GMPT;
		
		$salt = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
		
		//insert Routine here 
		//$editUserQuery= $db->prepare("CALL ----- ");
		//$editUserQuery->execute(array($username,hash('sha256',$password.$salt),$fName,$lName,$salt,$email));
		
	}
?>