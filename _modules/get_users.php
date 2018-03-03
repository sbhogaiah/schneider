<?php

	$start = (int)$_GET['start_index'];
	$limit = (int)$_GET['limit_per_page'];
	
	require_once '../_includes/bootstrap.php'; 
	require_once '../_includes/dbconnect.php';

	$sel = "select * from users LIMIT " . $start . ", " . $limit;
		
	$result = mysqli_query($db,$sel) or die('Could not query!');
	
	$rowCount = mysqli_num_rows($result);

	$rows = [];

	if($rowCount > 0) {
		while($r = mysqli_fetch_assoc($result)) {
		    array_push($rows, $r);
		}
		echo json_encode($rows);
	} else if($rowCount == 0) {
		echo "no more records!";
	} 
