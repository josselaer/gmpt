<?php
	function getGroupTotals($db,$ProjectID){
		$GetProjectTotalMeetings= $db->prepare('CALL GetProjectTotalMeetings(?)');
		$GetProjectTotalMeetings->execute(array($ProjectID));
		$result= $GetProjectTotalMeetings->fetchAll();
		$totalNumberOfMeetings= (int)$result[0]['count(*)'];
		unset($GetProjectTotalMeetings);
		
		
		$GetProjectTotalMessages=$db->prepare('CALL GetProjectTotalMessages(?)');
		$GetProjectTotalMessages->execute(array($ProjectID));
		$result2= $GetProjectTotalMessages->fetchAll();
		$totalNumberOfMessages = (int) $result2[0]['count(*)'];
		unset($getProjectTotalMessages);
		
		$result=array();
		$result['totalNumOfMeetings']=$totalNumberOfMeetings;
		$result['totalNumOfChatMessages']=$totalNumberOfMessages;
		$result2=array();
		$result2['Totals']=$result;
		
		
		return $result2;
	}
?>