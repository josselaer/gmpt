<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employees";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $stmt = $conn->prepare("SELECT *
    						FROM employees
							LIMIT 100");



    /*foreach ($stmt as $key => $value) {
    	echo $value;
    }*/

    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }

?>