<?php

	function getProjects($query,$db) {
		$results = [];
		$row = $query->fetchAll();
		unset($query);
		foreach($row as $data) {
			$ProjectID = $data['ProjectID'];
			$GroupName = $data['ProjectName'];
			$Description = $data['Description'];
			$DateCreated = $data['DateCreated'];
			$RoleName = $data['RoleName'];
			$Notification = $data['Notification'];
			
			//unset($query);
			$lastMeeting=array();
			$getLastMeetingQuery = $db->query("CALL GetLastMeeting($ProjectID)");
			foreach ($getLastMeetingQuery as $r1){
				$lastMeeting['NextMeetingDate']=$r1['MeetingDate'];
				$lastMeeting['StartTime']= $r1['StartTime'];
				$lastMeeting['MeetingID']= $r1['MeetingID'];
			}
			unset($getLastMeetingQuery);
		$project = array("ProjectID"=>$ProjectID, "GroupName"=>$GroupName, "Description"=>$Description, "DateCreated"=>$DateCreated, "RoleName"=>$RoleName, "Notification"=>$Notification, "NextMeetingDate"=>$lastMeeting['NextMeetingDate'], "NextMeetingTime" => $lastMeeting['StartTime'], "MeetingID"=>$lastMeeting['MeetingID']);
			array_push($results,$project);
		}
		$resultSize =  count($results);
		$results['projects'] = $results;
		for($i = 0; $i < $resultSize; $i++) {
			$temp = (string)$i;
			unset($results[$temp]);
		}
		return $results;
	}

	function getProjectByID($query) {
		$data = $query->fetchAll();
		//print_r($data);
		$row = $data[0];
		$ProjectID = $row['ProjectID'];
		$GroupName = $row['ProjectName'];
		$Description = $row['Description'];
		$DateCreated = $row['DateCreated'];
		$Users = array();
		foreach($data as $row) {
			$user = array();
			$user['username'] = $row['UserName'];
			$user['role'] = $row['RoleName'];
			array_push($Users, $user);
			
		}
		$result = array("ProjectID"=>$ProjectID, "GroupName"=>$GroupName, "Description"=>$Description, "DateCreated"=>$DateCreated, "Users"=>$Users);
		$project = array();
		$project['project'] = $result;
		return $project;
	}
	
	function sendEmailInvite($senderEmail,$email,$db){
		$mail = new PHPMailer;

		//$mail->SMTPDebug = 0;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'ssl://smtp.gmail.com:465'; // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'gmptDBGUI@gmail.com';                 // SMTP username
		$mail->Password = 'gmptMaster1';                           // SMTP password
		//$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		//$mail->Port = 465;                                    // TCP port to connect to

		$mail->setFrom('gmptDBGUI@gmail.com');
		$mail->addAddress("$email");     
		$mail->addReplyTo('noreply@gmpt.tech', 'No-REPLY');
		$registrationToken= substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 25);
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'GMPT Registration Invite';
		$mail->Body    = "Your friend $senderEmail sent you a request to join him in one of his groups in GMPT!  <a href='http://52.37.56.8/gmpt/www/registerinvite.html/#/!/?token=$registrationToken'>Register For GMPT</a> !";

		if(!$mail->send()) {
			echo 'Message could not be sent.';
		} else {
			$registerQuery2=$db->prepare("CALL CreateUnregisteredUser (?,?)");
			$registerQuery2->bindValue(1, $registrationToken, PDO::PARAM_STR);
			$registerQuery2->bindValue(2, $email, PDO::PARAM_STR);
			$registerQuery2->execute();
			unset($registerQuery2);
			$getUserID= $db->prepare("CALL GetUserIDByEmail(?)");
			$getUserID->execute(array($email));
			$UserIDResult= $getUserID->fetchAll();
			$userID=$UserIDResult[0]['UserID'];
			unset($getUserID);
			return $userID;
		}
		
		
	}
?>