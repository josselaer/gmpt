<?php
	function registerUser($username, $password,$fName,$lName,$email){
		$db = $this->GMPT;		
		$salt = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

		//prepare query
		$registerQuery=$db->prepare("CALL CreateUser (?,?,?,?,?,?)");
		
		$registerQuery->execute(array($username,hash('sha256',$password.$salt),$fName,$lName,$salt,$email));
	}
	
	function updateUser($userID,$username, $password, $fName, $lName, $email){
		$db= $this->GMPT;
		
		$salt = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
		
		//insert Routine here 
		//$editUserQuery= $db->prepare("CALL ----- ");
		//$editUserQuery->execute(array($username,hash('sha256',$password.$salt),$fName,$lName,$salt,$email));
		
	}
	
	//get Salt from DB for that username, compare username and hashed pass
	function validateUser($username,$password){
		$db=$this->GMPT;
		
		//get salt
		$getSaltQuery = $db->prepare("CALL GetSalt(?)");
		$getSaltQuery->execute(array($username));
		$rArray=array();
		foreach($getSaltQuery as $row){
			$rArray[$row['UserName']]=$row['Salt'];
		}
		
		//hash pass with salt
		$hashedPass= hash('sha256',$pasword.($rArray[$username]));
			
		//validate user
		$validateUserQuery= $db->prepare("CALL ValidateUser(?,?)");
		$validateUserQuery->execute(array($username,$hashedPass));
		
		//get token 
		$returnArray=array();
		foreach($validateUserQuery as $row){
			$returnArray[0]=$row['Token'];
		}
		
		
		//return the token
		return $returnArray[0];
	}
	
	//close session
	function closeSession($token){
		$db=$this->GMPT;
		$closeSessionQuery= $db->prepare("CALL CloseSession(?)");
		$closeSessionQuery->execute($token);
		
	}
?>