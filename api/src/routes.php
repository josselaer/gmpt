<?php
// Routes
include 'project.php';
//test
$app->get('/project',
	function($request,$response,$args) {
		$db=$this->GMPT;
		
		$query=$db->query('select * from Project;');
		
		$response=getGroups($query);
		echo json_encode($response);
	}
))->add($validateSession);


//test
$app->post('/project',
	function($request,$response,$args) {
		$db=$this->GMPT;

		//credentials

		$GroupName = $_POST['GroupName'];
		$Description = $_POST['Description'];

		$query=$db->query("INSERT INTO Project (GroupName,Description) VALUES('$GroupName', '$Description');");
		
		$response = array("worked"=>true);
		return json_encode($response);

	}
);

//test
$app->get('/project/{ProjectID}',
	function($request,$response,$args) {
		$db = $this->GMPT;
		$ProjectID = $request->getAttribute('ProjectID');
		$query=$db->query("SELECT * FROM Project WHERE ProjectID = '$ProjectID';");
		$response = getProjectByID($query);
		echo json_encode($response);
	}		
);

$app->group('/project/{id}', function() {
	$this->map(['GET','DELETE','PATCH','PUT'], '', function ($request,$response,$args) {

	})->setName('project');
	$this->get('/getByUserID', function($request,$response,$args) {
		

	})->setName('getProjectByUserID');
});

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

