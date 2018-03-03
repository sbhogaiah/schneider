<?php

require_once '../_includes/bootstrap.php'; 
require_once '../_includes/dbconnect.php';

if(isset($_POST['serialnum'])) {

	$sn = $_POST['serialnum'];
	$mid = $_POST['modelid'];

	$products_query = "SELECT * FROM products WHERE SerialNumber = '$sn'";
	$run_products_query = mysqli_query($db, $products_query);

	$stage_query = "SELECT ProductStage FROM producttimestamp WHERE SerialNumber = '$sn'";
	$run_stage_query = mysqli_query($db,$stage_query); 

	if(mysqli_num_rows($run_products_query) > 0) {

		$products_data = mysqli_fetch_assoc($run_products_query);	
		$stage_data = mysqli_fetch_assoc($run_stage_query);	
		$data = array_merge($products_data, $stage_data);

		if(strcasecmp($products_data['ModelID'], $mid) == 0) {	
			echo json_encode($data);
		} else {
			echo "Serial and ModelID do not match!";
		}

	} else {

		$model_query = "SELECT * FROM productcycles WHERE ModelID = '$mid'";
		$run_model_query = mysqli_query($db,$model_query); 

		if(mysqli_num_rows($run_model_query) <= 0) {
			echo "No such model!";
		} else {
			echo "Product not found!";
		}
	}
}