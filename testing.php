<?php
	
	require_once '_includes/bootstrap.php'; 
	require_once '_includes/dbconnect.php';

	$sel = "SELECT * FROM productcycles LIMIT 0,10";
		
	$result = mysqli_query($db,$sel) or die('Could not query!');

	$rowCount = mysqli_num_rows($result);

	$rows = [];
	
	if($rowCount > 0) {
		while($r = mysqli_fetch_assoc($result)) {
		    array_push($rows, $r);
		}
		echo json_encode($rows);
	} else {
		echo "no more records!";
	}
