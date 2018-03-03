<?php

require_once '../_includes/bootstrap.php'; 
require_once '../_includes/dbconnect.php';

if(isset($_GET['search-model'])) {

	$model_id = $_GET['search-model'];

	$query = " SELECT * FROM productcycles WHERE ModelId like '%".$model_id."%' OR FamilyName like '%".$model_id."%'";
	$result = mysqli_query($db, $query);

	$rows = [];
	if(mysqli_num_rows($result) > 0) {
		while($r = mysqli_fetch_assoc($result)) {
		    array_push($rows, $r);
		}
		echo json_encode($rows);
	} else {
		echo "err:1";
	}

}