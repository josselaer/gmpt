<?php
	function getGroups(){
		$db=$this->GMPT;
		$getGroupsQuery=$db->query("SELECT GroupName, Description FROM GROUPS");
		$returnArray=array();
		foreach($getGroupsQuery as $row){
			$returnArray[$row['GroupName']]=$row['Description'];
		}
		return $returnArray;
	}
	
	
	//TBD
	function postGroups($groupName,$description, $users){
		$db=$this->GMPT;
		//call apropriate query
		//$createGroupQuery=$db->prepare("CALL ----");
		//$createGroupQuery->execute($groupName,$description,$users);
	}
	
	function editGroupByGroupID($groupID, $groupName, $description, $users){
		$db=$this->GMPT;
		//call apropriate query
		//$editGroupByGroupIDQuery=$db->prepare("CALL ----");
		//$editGroupByGroupIDQuery->execute($groupID, $groupName, $description, $users);
	}
?>