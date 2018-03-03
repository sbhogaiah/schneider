<?php
require_once '../../_includes/bootstrap.php'; 
require_once '../../_includes/dbconnect.php';

if(isset($_POST['username'])) {

	$username = $_POST['username'];

	$check_query = "SELECT * FROM testeractivity WHERE TesterUsername = '$username'";
	$run_check_query = mysqli_query($db, $check_query);

	if(mysqli_num_rows($run_check_query) > 0) {

		$data = mysqli_fetch_assoc($run_check_query);

		if($data['TestStage'] != 0) {

			$serial = $data['SerialNumber'];
			// get tester from products
			$get_tester_q = "SELECT Tester FROM products WHERE SerialNumber = '$serial'";
			$run_get_tester_q = mysqli_query($db, $get_tester_q) or die('Cant connect to products at check_activity.php');
			$tester = mysqli_fetch_assoc($run_get_tester_q);
			
			$data2 = array_merge($data, $tester);
			// var_dump($data2);
			echo json_encode($data2);
		} else {
			echo json_encode($data);
		}

	}

}